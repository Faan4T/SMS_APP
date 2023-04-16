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
								Bulk SMS
							</h4>
							<p class="category">Create bulk sms here.</p>
						</div>
                        <?php
                        $sql_bulk = mysqli_query($link,sprintf("select * from bulk_sms where user_id=%s order by id desc",
                                            mysqli_real_escape_string($link,filterVar($_SESSION['user_id'])))
                            );
                        ?>
                        <div class="col-md-4"><span class="badge badge-success"><?php echo 'Total : '.mysqli_num_rows($sql_bulk); ?></span></div><br>

                        <div class="content table-responsive">
							<form method="post" action="server.php" enctype="multipart/form-data">
							<div class="form-group">
								<label>Message</label>
								<span class="merge_style_bulk">Merge tags: Name = %name%</span>
								<textarea class="form-control textCounter" name="bulk_sms" required></textarea>
								<span class="showCounter">
									<span class="showCount"><?php echo DBout($maxLength)?></span> Characters left
								</span>
							</div>
                            <div class="form-group">
								<label>Select Media</label>
								<input type="file" name="bulk_media" class="display-inline"/>
							</div>
							<div class="form-group">
								<input type="hidden" name="cmd" value="save_bulk_sms">
								<input type="submit" value="Save" class="btn btn-primary">
							</div>
						</form>
						</div>
						
						<div class="content table-responsive table-full-width">
							<table id="bulkSMSTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th width="60%">Message</th>
										<th>Send</th>
										<th>Media</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
							<?php
								$sel = sprintf("select * from bulk_sms where user_id=%s order by id desc",
                                            mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
                                    );
								if(is_numeric($_GET['page']))
									$pageNum = DBin($_GET['page']);
								else
									$pageNum = 1;
								$max_records_per_page = 20;
								$pagelink 	= DBout("bulk_sms.php?");
								$pages 		= generatePaging($sel,$pagelink,$pageNum,$max_records_per_page);
								$limit 		= DBout($pages['limit']);
								$sel 	   .= DBout($limit);
								if($pageNum==1)
									$countPaging=1;
								else
									$countPaging=(($pageNum*$max_records_per_page)-$max_records_per_page)+1;
											
								if($_SESSION['TOTAL_RECORDS'] <= $max_records_per_page){
									$maxLimit = $_SESSION['TOTAL_RECORDS'];	
								}else{
									$maxLimit = (((int)$countPaging+(int)$max_records_per_page)-1);
								}
								if($maxLimit >= $_SESSION['TOTAL_RECORDS']){
									$maxLimit = $_SESSION['TOTAL_RECORDS'];	
								}

								$exe = mysqli_query($link,$sel);
								if(mysqli_num_rows($exe)){
									$index = $countPaging;
									while($row = mysqli_fetch_assoc($exe)){
							?>
										<tr>
											<td><?php echo DBout($index++)?></td>
											<td><?php echo DBout($row['message'])?></td>
											<td>
												<a data-target="#custom-modal" data-toggle="modal" title="Send bulk sms" onClick="getSMSID('<?php echo DBout($row['id'])?>')"><i class="btn btn-warning btn-custom btn-rounded">Send</i></a>
											</td>
                                            <td>
												<?php 
													echo isMediaExists($row['bulk_media']);
												?>
                                            </td>
											<td class="text-center">
												<a href="edit_bulk_sms.php?id=<?php echo DBout($row['id'])?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
												<i onclick="deleteBulkSMS('<?php echo DBout($row['id'])?>')" class="fa fa-remove pointer red"></i>
											</td>
										</tr>
							<?php			
									}	
								}
							?>	
								<tr>
									<td colspan="5" class="padding-left-0 padding-right-0"><?php echo $pages['pagingString'];?></td>
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
<div id="custom-modal" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Send Bulk SMS</h6>
			</div>
			
			<div class="modal-body buklSMSBody">
				<div class="form-group">
					<form action="server.php" method="post">
					<table width="100%" id="bulksmstable">
						<tr>
							<td align="left" width="25%"><label>Select Type</label></td>
							<td align="left">
							<label class="radio-inline">
								<input type="radio" name="bulk_type" class="bulk_type" value="1" checked>Single number/Group
							</label>
							<label class="radio-inline">
								<input type="radio" name="bulk_type" class="bulk_type" value="2">Date range
							</label>
							</td>
						</tr>
						<?php
						if($_SESSION['user_type']==1){
							$display="";
						}else{
							$display=DBout("none");
						}
						?>
						<tr style="display:<?php echo DBout($display); ?>;">
							<td align="left"><label>Choose Account</label></td>
							<td>
								<select name="client_id" id="client_id" class="form-control" onchange="getAccountGroups(this.value)">
									<option value="all">All Accounts</option>
								<?php
									$seln = sprintf("select id, first_name, last_name, business_name from users where status='1'");
									$resn = mysqli_query($link,$seln);
									if(mysqli_num_rows($resn)){
										while($rown = mysqli_fetch_assoc($resn)){
											if($rown['id']==$_SESSION['user_id']){ $sel = "selected='selected'"; } else { $sel = ""; }
											?>
											<option <?php echo DBout($sel)?> value="<?php echo DBout($rown['id'])?>"><?php echo DBout($rown['first_name'].' '.$rown['last_name'].' '.$rown['business_name'])?></option>
									<?php	}
									}
									else{ ?>
									    <option value="">No phone number found.</option>';
								<?php	}
								?>
								</select>
							</td>
						</tr>
						
						<tr>
							<td align="left"><label>From Number</label></td>
							<td>
							<?php
								echo '<select name="from_number" id="twilio_numbers" class="form-control" onchange="listMobileDevices(this)" required>';
									if($appSettings['sms_gateway']=='twilio'){
										$seln = "select id, phone_number from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='1'";
									}else if($appSettings['sms_gateway']=='plivo'){
										$seln = "select id, phone_number from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='2'";
									}else if($appSettings['sms_gateway']=='nexmo'){
										$seln = "select id, phone_number from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='3'";
									}
									$resn = mysqli_query($link,$seln);
									if(mysqli_num_rows($resn)){
										while($rown = mysqli_fetch_assoc($resn)){
											echo '<option value="'.$rown['phone_number'].'">'.$rown['phone_number'].'</option>';
										}	
									}
									
									$sqlm = "select id from mobile_devices where device_token != '' and device_status='1' and user_id='".$_SESSION['user_id']."'";
									$exem = mysqli_query($link,$sqlm);
									if(mysqli_num_rows($exem)){
										if(mysqli_num_rows($resn)==0){
											echo '<option value="">- Select One -</option>';	
										}
										echo '<option value="mobile_sim">From Mobile Device</option>';
									}
								
								echo '</select>';
							?>
							</td>
						</tr>
						<tr id="listOfMobileDevices" style="display: none">
							<td align="left"><label>Select Device</label></td>
							<td>
							<?php
								echo '<select name="device_id" class="form-control">';
									$selm = "select * from mobile_devices where device_status='1' and user_id='".$_SESSION['user_id']."' and device_token != ''";
									$resm = mysqli_query($link,$selm);
									if(mysqli_num_rows($resm)){
										while($rowm = mysqli_fetch_assoc($resm)){
											echo '<option value="'.$rowm['id'].'">'.$rowm['device_name'].'</option>';
										}	
									}
									
									
								echo '</select>';
							?>
							</td>
						</tr>
						
						<tr class="single_group">
							<td align="left"><label>Select Group</label></td>
							<td>
								<select name="group_id" id="group_list" class="form-control" onChange="getGroupNumbers(this.value)" required>
									<option value="">Select One</option>
									<option value="all">All Groups</option>
									<?php
									
										$sql = sprintf("select id,title from campaigns where user_id=%s",
                                                mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
                                            );
										$res = mysqli_query($link,$sql) or die(mysqli_error($link));
										if(mysqli_num_rows($res)){
											while($row = mysqli_fetch_assoc($res)){ ?>
												<option value="<?php echo DBout($row['id'])?>"><?php echo DBout($row['title'])?></option>
										<?php	}
										}
									
									?>	
								</select>
							</td>
						</tr>
						<tr class="single_group phoneListRow">
							<td align="left"><label>Select Number</label></td>
							<td>
								<select name="phone_number_id" id="phoneid" class="form-control" required>
									<option value="">Select One</option>
								</select>
							</td>
						</tr>

						<tr class="daterange display-none">
							<td align="left"><label>Date range</label></td>
							<td>
								<input type="text" class="form-control addDatePicker width-48-per display-inline" name="start_date" placeholder="Start date.">
								<input type="text" class="form-control addDatePicker width-48-per display-inline" name="end_date" placeholder="End date.">
							</td>
						</tr>
						<tr class="daterange display-none">
							<td align="left"><label>Select Group</label></td>
							<td>
								<select name="daterange_group_id" id="daterange_group_id" class="form-control">
									<option value="">Select One</option>
									<option value="all">All Groups</option>
									<?php
										$sql = sprintf("select id,title from campaigns where user_id=%s",
                                                    mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
                                            );
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											while($row = mysqli_fetch_assoc($res)){ ?>
                                    <option value="<?php echo DBout($row['id']) ?>"><?php echo DBout($row['title'])?></option>
									<?php		}
										}
									?>	
								</select>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td align="left">
								<input type="submit" value="Send Now" class="btn btn-success" />
								&nbsp;<img src="images/busy.gif" id="loading" class="display-none">&nbsp;<span id="showresponse"></span>
								<input type="hidden" name="hidden_sms_id" id="hidden_sms_id" value="">
								<input type="hidden" name="cmd" value="process_bulk_sms" />
							</td>
						</tr>
					</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>

<script src="scripts/js/custombox.min.js"></script>
<script src="scripts/js/legacy.min.js"></script>

<script type="text/javascript" src="scripts/js/parsley.min.js"></script>
<script type="text/javascript" src="scripts/bulk_sms.js"></script>
<script>
function listMobileDevices(obj){
	if($(obj).val()=='mobile_sim'){
		$('#listOfMobileDevices').show();
	}else{
		$('#listOfMobileDevices').hide();
	}
}
</script>