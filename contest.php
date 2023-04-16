<?php
	include_once("header.php");
	include_once("left_menu.php");
?>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">Contest Campagins
								<input type="button" class="btn btn-primary move-right" value="Add New" onclick="window.location='add_contest.php'" />
							</h4>
							<p class="category">Your already saved list of campaigns.</p>
							<div id="alertArea"></div>
						</div>
                        <?php
                        $sql_contest = mysqli_query($link,sprintf("select * from campaigns where user_id=%s and type='0'",
                                                    mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
                                            ));
                        if(isset($_REQUEST['search']) && $_REQUEST['search'] != ''){
                            $sql_viral = mysqli_query($link,"SELECT * FROM `campaigns` WHERE `title` LIKE '%".$_REQUEST['search']."%' and user_id=".$_SESSION['user_id']." and type=0") or die(mysqli_error($link));
                        }
                        ?>
                        <div class="col-md-4"><span class="badge badge-success"><?php echo 'Total : '.mysqli_num_rows($sql_contest); ?></span></div><br>

                        <div class="content table-responsive table-full-width">
                            <div class="row">
                                <form action="contest.php" class="view_subscriber_class">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-5">
                                        <input type="text" name="search" id="search" class="form-control" placeholder="Search by title" value="<?php echo DBout($_REQUEST['search'])?>" />
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-md btn-success"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
							<table id="campaignTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>Title</th>
										<th>Keyword</th>
										<th>Phone Number</th>
										<th>Follow Up</th>
										<th>Subscribers / Unsubscribers</th>
										<th>Media</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sql = sprintf("select * from campaigns where user_id=%s and type='0' order by id desc",
                                                    mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
                                            );
                                    if(isset($_REQUEST['search']) && $_REQUEST['search'] != ''){
                                        $sql ="SELECT * FROM `campaigns` WHERE `title` LIKE '%".$_REQUEST['search']."%' and user_id=".$_SESSION['user_id']." and type=0";
                                    }
										if(is_numeric($_GET['page']))
											$pageNum = DBin($_GET['page']);
										else
											$pageNum = 1;
										$max_records_per_page = DBout(20);
										$pagelink 	= DBout("contest.php?");
										$pages 		= generatePaging($sql,$pagelink,$pageNum,$max_records_per_page);
										$limit 		= DBout($pages['limit']);
										$sql 	   .= $limit;
										if($pageNum==1)
											$countPaging=1;
										else
											$countPaging=(($pageNum*$max_records_per_page)-$max_records_per_page)+1;
													
										if($_SESSION['TOTAL_RECORDS'] <= $max_records_per_page){
											$maxLimit = DBout($_SESSION['TOTAL_RECORDS']);
										}else{
											$maxLimit = (((int)$countPaging+(int)$max_records_per_page)-1);
										}
										if($maxLimit >= $_SESSION['TOTAL_RECORDS']){
											$maxLimit = DBout($_SESSION['TOTAL_RECORDS']);
										}
										
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											$index = $countPaging;
											while($row = mysqli_fetch_assoc($res)){
									?>
												<tr>
													<td><?php echo DBout($index++)?></td>
													<td><?php echo DBout($row['title']);?></td>
													<td align="center"><?php echo DBout($row['keyword']);?></td>
													<td><?php echo DBout($row['phone_number']);?></td>
													<td align="center">
														<?php
                                                        $sql1 = sprintf("select id from follow_up_msgs where group_id=%s",
                                                                    mysqli_real_escape_string($link,filterVar($row['id']))
                                                            );
															$f = mysqli_query($link,$sql1);
															echo DBout(mysqli_num_rows($f));
														?>
													</td>
													<td align="center">
														<?php
                                                        $sql2 = sprintf("select s.id from subscribers_group_assignment sga, subscribers s where sga.group_id=%s and sga.subscriber_id=s.id and s.status='1'",
                                                                        mysqli_real_escape_string($link,filterVar($row['id']))
                                                                );
															$exe = mysqli_query($link,$sql2);
															if(mysqli_num_rows($exe)=='0'){
																echo DBout(mysqli_num_rows($exe));
															}else{ ?>
																<a href="subscribers_stats.php?group_id=<?php echo DBout(encode($row["id"]))?>&searchType=subscribers" title="View details" target="_blank"><?php echo DBout(mysqli_num_rows($exe))?></a>
															<?php
															}
															echo DBout(' / ');
															$sql3 = sprintf("select s.id from subscribers_group_assignment sga, subscribers s where sga.group_id=%s and sga.subscriber_id=s.id and s.status='2'",
                                                                        mysqli_real_escape_string($link,filterVar($row['id']))
                                                                );
															$exe = mysqli_query($link,$sql3);
															if(mysqli_num_rows($exe)=='0'){
																echo DBout(mysqli_num_rows($exe));
															}else{ ?>

                                                                <a href="subscribers_stats.php?group_id=<?php echo DBout(encode($row["id"]))?>&searchType=unsubscribers" title="View details" target="_blank"><?php echo DBout(mysqli_num_rows($exe))?></a>
													<?php		}
														?>
													</td>
													<td align="center">
														<?php 
															echo DBout(isMediaExists($row['media']));
														?>
													</td>
													<td class="text-center">
														<a href="#copyCampaign" title="Duplicate campaign" class="orange" onclick="getCampaignID('<?php echo DBout($row['id'])?>')" data-toggle="modal">
															<i class="fa fa-copy"></i>
														</a>
														<a href="add_contest.php?id=<?php echo DBout($row['id'])?>" title="Edit campaign"><i class="fa fa-edit"></i></a>
														<i class="fa fa-remove red pointer" title="Delete campaign" onclick="deleteCampaign('<?php echo DBout($row['id'])?>')"></i>
													</td>
												</tr>
									<?php			
											}	
										}
									?>	
										<tr>
											<td colspan="8" class="padding-right-0 padding-left-0"><?php echo $pages['pagingString'];?></td>
										</tr>
									</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<input type="hidden" id="duplicate_camp_id" value="" />
<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script src="scripts/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="scripts/js/parsley.min.js"></script>
<div id="copyCampaign" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Duplicate Campaign</h6>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Title</label>
					<input type="text" name="title" class="form-control" />
				</div>
				<div class="form-group">
					<label>Keyword</label>
					<input type="text" name="keyword" class="form-control" placeholder="Keyword should be unique." />
				</div>
			</div>
			<div class="modal-footer">
				<span id="duplicateCampaignloading" class="display-none">Loading...</span>
				<button type="button" class="btn btn-success" onclick="duplicateCampaign()">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div id="blockedNumbersSection" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title showSubsType">Unsubscribers</h6>
			</div>
			<div class="modal-body showBlockedNumbers">Loading...</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div id="custom-modal-twitter" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Post on Twitter</h6>
			</div>
			<div class="modal-body buklSMSBody">
				<div class="form-group">
					<table width="100%" id="">
				<tr>
					<td align="left" width="25%"><label>Message</label></td>
					<td align="left">
					   <textarea name="post_message_tw" id="post_message_tw" class="form-control"></textarea>
                       <input name="camp_id_tw" id="camp_id_tw" type="hidden" />
					</td>
				</tr>
                
				<tr>
					<td>&nbsp;</td>
					<td align="left">
                        <input type="button" value="Post on Twitter" class="btn btn-success margin-top-10" onclick="PostMessage_tw()" />
                        &nbsp;<img src="images/busy.gif" id="loading" class="display-none">&nbsp;<span id="showresponse"></span>
                        <input type="hidden" name="hidden_sms_id" id="hidden_sms_id" value="">
                    </td>
				</tr>
			</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div id="custom-modal" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Post on Facebook</h6>
			</div>
			<div class="modal-body buklSMSBody">
				<div class="form-group">
					<table width="100%" id="">
						<tr>
							<td align="left" width="25%"><label>Message</label></td>
							<td align="left">
							   <textarea name="post_message" id="post_message" class="form-control"></textarea>
							   <input name="camp_id" id="camp_id" type="hidden" />
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td align="left">
								<input type="button" value="Post on Facebook" class="btn btn-success margin-top-10" onclick="PostMessage()" />
								&nbsp;<img src="images/busy.gif" id="loading" class="display-none">&nbsp;<span id="showresponse"></span>
								<input type="hidden" name="hidden_sms_id" id="hidden_sms_id" value="">
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="scripts/view_contest.js">
</script>