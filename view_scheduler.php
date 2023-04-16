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
							<h4 class="title">
								SMS Schedulers
                                <input type="button" class="btn btn-info move-right margin-left-10" value="Scheduler view"  onclick="window.location='view_scheduler2.php'"/>
                                
                                <input type="button" class="btn btn-primary move-right" value="Add New" onclick="window.location='scheduler.php'"/>
							</h4>
							<p class="category red"><?php echo DBout(date('g:iA \o\n l jS F Y'))?></p>
						</div>
                        <?php
                        $sql_scheduler = mysqli_query($link,sprintf('select * from schedulers where user_id=%s',
                            mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))));
                        if(isset($_REQUEST['search']) && $_REQUEST['search'] != ''){
                            $sql_scheduler = mysqli_query($link,"SELECT * FROM `schedulers` WHERE `title` LIKE '%".$_REQUEST['search']."%' and user_id=".$_SESSION['user_id']) or die(mysqli_error($link));
                        }
                        ?>
                        <div class="col-md-4"><span class="badge badge-success"><?php echo 'Total : '.mysqli_num_rows($sql_scheduler); ?></span></div><br>

                        <div class="content table-responsive table-full-width">
                            <div class="row">
                                <form action="view_scheduler.php" class="view_subscriber_class">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-5">
                                        <input type="text" name="search" id="search" class="form-control" placeholder="Search campaign title" value="<?php echo DBout($_REQUEST['search'])?>" />
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-md btn-success"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
							<table id="schedulerTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>Title</th>
										<th>Send Time</th>
										<th>Group</th>
                                        <th>Search <small>based</small></th>
										<th>Recipient</th>
										<th>Status</th>
										<th>Media</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sql = sprintf("select * from schedulers where user_id=%s and scheduler_type='1' order by id desc",
                                                        mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
                                            );
                                    if(isset($_REQUEST['search']) && $_REQUEST['search'] != ''){
                                        $sql ="SELECT * FROM `schedulers` WHERE `title` LIKE '%".$_REQUEST['search']."%' and user_id=".$_SESSION['user_id']." and scheduler_type='1' order by id desc";
                                    }
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											$index = 1;
											while($row = mysqli_fetch_assoc($res)){
									?>
												<tr>
													<td><?php echo DBout($index++)?></td>
													<td><?php echo DBout($row['title']);?></td>
													<td><?php echo DBout(date($appSettings['app_date_format'].' H:i:s',strtotime($row['scheduled_time'])));?></td>
													<td>
														<?php
															$sqlg = sprintf("select title from campaigns where id=%s ",
                                                                        mysqli_real_escape_string($link,filterVar($row['group_id']))
                                                                );
															$resg = mysqli_query($link,$sqlg);
															if(mysqli_num_rows($resg)){
																$rowg = mysqli_fetch_assoc($resg);	
																echo DBout($rowg['title']);
															}
															else{
																echo DBout('N/A');
															}
														?>
													</td>
                                                    <td><?php if($row['search']!=""){ echo DBout($row['search']); }else{ echo DBout("N/A"); }?></td>
													<td>
														<?php
															if($row['phone_number']=='all'){
																echo DBout('Whole Group');
															}
															else{
															    $pos = strpos($row['phone_number'],",");
                                                                if($pos!==false){
                                                                    echo DBout("Multiple Numbers");
                                                                }else{
    																$sqln = sprintf("select phone_number from subscribers where id=%s ",
                                                                                mysqli_real_escape_string($link,filterVar($row['phone_number']))
                                                                        );
    																$resn = mysqli_query($link,$sqln);	
    																if(mysqli_num_rows($resn)){
    																	$rown = mysqli_fetch_assoc($resn);	
    																	echo DBout($rown['phone_number']);
    																}
                                                                }
															}
														?>
													</td>
													<td>
														<?php
															if($row['status']=='1') {
                                                                ?>
                                                                <i class="badge badge-success">Sent</i>
                                                        <?php    }
															else { ?>
                                                                <i class="badge badge-warning">Waiting</i>
                                                        <?php    }
																?>
													</td>
													<td>
													<?php
														if(trim($row['media'])!=''){
													?>
														<img src="<?php echo DBout($row['media']);?>" width="30" height="30" />
													<?php
														}
													?>
													</td>
													<td class="text-center">
														<a href="edit_scheduler.php?id=<?php echo DBout($row['id'])?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
														<i class="fa fa-remove red pointer" onclick="deleteScheduler('<?php echo DBout($row['id'])?>')"></i>
													</td>
												</tr>
									<?php			
											}	
										}
									?>
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
<script src="scripts/view_scheduler.js">
</script>