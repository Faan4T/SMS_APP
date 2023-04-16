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
								Appointments
                                <input type="button" class="btn btn-primary" value="Add New" id="btn_right" onclick="window.location='add_apts.php'" />
							</h4>
							<p class="category">Already scheduled appointments</p>
						</div>
                        <?php
                        $sql_apts =mysqli_query($link,sprintf("select * from appointments where user_id=%s",
									        	mysqli_real_escape_string($link,filterVar($_SESSION['user_id'])))
                                        );
                        if(isset($_REQUEST['search']) && $_REQUEST['search'] != ''){
                            $sql_apts = mysqli_query($link,"SELECT * FROM `appointments` WHERE `title` LIKE '%".$_REQUEST['search']."%' and user_id=".$_SESSION['user_id']) or die(mysqli_error($link));
                        }
                        ?>
                        <div class="col-md-4"><span class="badge badge-success"><?php echo 'Total : '.mysqli_num_rows($sql_apts); ?></span></div><br>

                        <div class="content table-responsive table-full-width">
                            <div class="row" style="margin: 19px 0px">
                                <form action="view_apts.php" class="view_subscriber_class">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-5">
                                        <input type="text" name="search" id="search" class="form-control" placeholder="Search by title" value="<?php echo DBout($_REQUEST['search'])?>" />
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-md btn-success"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
							<table id="aptTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
                                    <tr>
                                        <th>Sr#</th>
                                        <th>title</th>
                                        <th>Scheduled Time</th>
                                        <th>Alerts / Follow Up</th>
                                        <th>Manage</th>
                                    </tr>
								</thead>
								<tbody>
								<?php
									
									$sql = sprintf("select * from appointments where user_id=%s",
										mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))	
									);
                                if(isset($_REQUEST['search']) && $_REQUEST['search'] != ''){
                                    $sql ="SELECT * FROM `appointments` WHERE `title` LIKE '%".$_REQUEST['search']."%' and user_id=".$_SESSION['user_id'];
                                }

									$res = mysqli_query($link,$sql);
									if(mysqli_num_rows($res)){
										$index = 1;
										while($row = mysqli_fetch_assoc($res)){
								?>
										<tr>
											<td><?php echo $index++ ?></td>
											<td ><?php echo $row['title']?></td>
											<td>
                                            <?php echo DBout($row['apt_time'])?>
                                            </td>
											<td>
												<?php
                                                $exe = mysqli_query($link,sprintf("SELECT id FROM appointment_alerts WHERE apt_id = '%s'",
                                                            mysqli_real_escape_string($link,filterVar($row['id']))));
                                                echo mysqli_num_rows($exe);
													echo DBout(' / ');
											
											 $r = mysqli_query($link,sprintf("SELECT id FROM appointment_followup_msgs WHERE apt_id = '%s'",
                                                        mysqli_real_escape_string($link,filterVar($row['id']))
                                                 ));
											echo DBout(mysqli_num_rows($r));
											        
												?>
											</td>
											<td align="center">
												<a href="edit_apt.php?id=<?php echo DBout($row['id'])?>"><i class="fa fa-edit" ></i></a>&nbsp;&nbsp;
												<i class="fa fa-trash-o" id="fa_remove" onclick="deleteApt('<?php echo DBout($row['id'])?>')"></i>
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
	
<div id="loadAptAlerOrFollowUp" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title typeTitle">Alerts/FollowUps</h6>
			</div>
			<div class="modal-body loadAptAlerts">Loading...</div>
			<div class="modal-footer">
				<span id="duplicateCampaignloading" >Loading...</span>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php include_once("footer.php");?>
<script   src="scripts/js/view_apts.js">
	
</script>