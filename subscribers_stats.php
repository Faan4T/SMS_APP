<?php
	include_once("header.php");
	include_once("left_menu.php");
	$groupID = decode($_REQUEST['group_id']);
	if($_REQUEST['searchType']=='subscribers'){
		$status = '1';
		$queryStatus = '1';
		$heading = 'Subscribers stats';
	}else{
		$status = DBout('2');
		$queryStatus = '2';
		$heading = DBout('UnSubscribers stats');
	}
?>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								<?php echo DBout($heading)?>
								<input type="button" class="btn btn-success move-right" value="Back" onclick='goBack("-1")' />
							</h4>
							<p class="category">Will show you subscribers stats group wise.</p>
						</div>
                        <?php
                        $sql_stats = mysqli_query($link,sprintf("select 
								s.* 
							from 
								subscribers s,
								subscribers_group_assignment sga
							where 
								sga.group_id='%s' and
								sga.subscriber_id=s.id and
								s.user_id='%s' and
								s.status='%s'",
                            mysqli_real_escape_string($link,filterVar($groupID)),
                            mysqli_real_escape_string($link,filterVar($_SESSION['user_id'])),
                            mysqli_real_escape_string($link,filterVar($status))
                        ));

                        ?>
                        <div class="col-md-4"><span class="badge badge-success"><?php echo 'Total : '.mysqli_num_rows($sql_stats); ?></span></div><br>

                        <div class="content table-responsive table-full-width">
							<div class="col-md-6"></div>
							<div class="col-md-3"></div>
							<div class="col-md-3">
								<input type="text" name="search_subs" id="searchkeyword" class="form-control" placeholder="Search here..." onkeypress="OnKeyPress(event)" value="<?php echo DBin($_REQUEST['searchkeyword'])?>" />
							</div>
							<table id="subscribersTable" class="table table-hover table-striped">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Phone</th>
										<th>Status</th>
										<th>Subscribed Date</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
			<?php
				if(isset($_REQUEST['searchkeyword']) && $_REQUEST['searchkeyword']!=''){
				    $search = DBin($_REQUEST['searchkeyword']);
					$sql = "select 
								s.* 
							from 
								subscribers s, 
								subscribers_group_assignment sga 
							where 
								sga.group_id='".$groupID."' and
								sga.subscriber_id=s.id and
								s.user_id='".$_SESSION['user_id']."' and
								s.status='".$queryStatus."' and
								(s.phone_number like '%".$search."%') or
								(s.first_name like '%".$search."%')
							order by 
								id desc";
				}
				else{
					$sql = "select 
								s.* 
							from 
								subscribers s,
								subscribers_group_assignment sga
							where 
								sga.group_id='".$groupID."' and
								sga.subscriber_id=s.id and
								s.user_id='".$_SESSION['user_id']."' and
								s.status='".$queryStatus."'
							order by 
								id desc";
				}
				if(is_numeric($_GET['page']))
					$pageNum = DBin($_REQUEST['page']);
				else
					$pageNum = 1;
				$max_records_per_page = 20;
				$pagelink 	= "subscribers_stats.php?group_id=".$_REQUEST['group_id']."&searchType=subscribers&";
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
				$res = mysqli_query($link,$sql) or die(mysqli_error($link));
				if(mysqli_num_rows($res)){
					$index = $countPaging;
					while($row = mysqli_fetch_assoc($res)){
						$sel = sprintf("select id as unReadMsgs from chat_history where phone_id=%s and is_read='0'",
                                    mysqli_real_escape_string($link,filterVar($row['id']))
                            );
						$exe = mysqli_query($link,$sel);
						if(mysqli_num_rows($exe)){
							$unReadMsgs = mysqli_num_rows($exe);
						}else{
							$unReadMsgs = 0;
						}
			?>
						<tr>
							<td><?php echo DBout($index++)?></td>
							<td><?php echo DBout($callerName)?></td>
                            <td><?php echo DBout($row['phone_number']);?></td>
							<td>
								<?php 
									if($row['status']=='1') { ?>
                                           <span class="badge badge-success">Active</span>
                                <?php    }
									else if($row['status']=='2') { ?>
                                           <span class="badge badge-warning">Blocked</span>
                                <?php    }
									else if($row['status']=='3') { ?>
                                           <span class="badge badge-danger">Deleted</span>
                                <?php    }
								?>
							</td>
							<td><?php echo DBout(date($appSettings['app_date_format'].' H:i:s',strtotime($row['created_date'])));?></td>
							<td class="text-center">
								<?php
									if(trim($row['custom_info'])!=''){
								?>
									<a href="#customInfoBox" title="View additional Information" onclick="getSubsCustomInfo('<?php echo DBout($row['id'])?>')" data-toggle="modal"><i class="fa fa-info"></i></a>
								<?php
									}
								?>
								
								<a href="chat.php?phoneid=<?php echo encode(DBout($row['id']).'&ph='.urlencode($row['phone_number']));?>" title="Chat">
									<?php
										if($unReadMsgs>0){ ?>
											<span class="chatBadge"><?php echo DBout($unReadMsgs)?></span>
									<?php	}
									?>
									<i class="fa fa-comments green" aria-hidden="true"></i></a>
									<a href="add_subscribers.php?id=<?php echo DBout($row['id'])?>"><i class="fa fa-edit"></i></a>&nbsp;<i class="fa fa-remove red pointer" onclick="deleteSubscriber('<?php echo DBout($row['id'])?>')"></i>
							</td>
						</tr>
			<?php			
					}	
				}
			?>
            <tr>
                <td colspan="11" class="padding-right-0 padding-left-0"><?php echo $pages['pagingString'];?></td>
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
<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />

<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script>
    var group_id = "<?php echo DBin($_REQUEST['group_id'])?>";
</script>
<script src="scripts/subs_stats.js"></script>
<div id="customInfoBox" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Additional Information of the Subscriber</h6>
			</div>
			<div class="modal-body loadCustomInfo"></div>
		</div>
	</div>
</div>