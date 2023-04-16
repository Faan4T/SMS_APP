<?php
include_once("header.php");
include_once("left_menu.php");
$aptID = DBin($_REQUEST['id']);

$sql = sprintf("select * from appointments where id=%s",
	mysqli_real_escape_string($link,filtervar($aptID)));
$res = mysqli_query($link,$sql);
if($res){
	$row = mysqli_fetch_assoc($res);
}else{
	die('Appointment already deleted.');
}
?>

<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<form method="post" data-parsley-validate enctype="multipart/form-data" action="server.php">
					<div class="col-md-12">
						<div class="card">
							<div class="header">
								<h4 class="title">
									Edit Appointment settings
									<input type="button" class="btn btn-default" value="Back" id="btn_right" onclick="window.location='view_apts.php'" />
								</h4>
								<p class="category">Edit appointment settings from here.</p>
							</div>
							<div class="content table-responsive">
								<div class="form-group">
									<label>Title</label>
									<input type="text" required name="apt_title" class="form-control" value="<?php echo DBout($row['title'])?>" />
								</div>
								<div class="form-group">
									<label>Date/Time </label><br />
									<input type="text" required name="apt_date" class="form-control addDatePicker remainder_dely" value="<?php echo  DBout(date('Y-m-d',strtotime($row['apt_time'])));?>" />
									<select name="apt_time" required class="form-control remainder_dely">
										<?php
										$dateTime = DBout(date('H:i:s',strtotime($row['apt_time'])));
										$dateTime = DBout(explode(':',$dateTime));
										$aptTime  = DBout($dateTime[0].':'.$dateTime[1]);
										$timeArray = getTimeArray();
										foreach($timeArray as $key => $value){
											if($aptTime==$key)
												$sel = DBout('selected="selected"');
											else
												$sel = DBout('');
											?>
											    <option <?php echo DBout($sel) ?> value="<?php echo DBout($key)?>"><?php echo DBout($value) ?></option>
											<?php
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<label>Select Group</label>
									<select name="group_id" required class="form-control" onchange="getGroupNumbers(this.value,'<?php echo DBout($row['phone_number'])?>')">
										<option value="">Choose One</option>   
										<?php
										$sel = sprintf("select id,title from campaigns where user_id=%s",
	                                                 mysqli_real_escape_string($link,filtervar($_SESSION['user_id'])));
										
										$exe = mysqli_query($link,$sel);
										if(mysqli_num_rows($exe)){
											while($rec=mysqli_fetch_assoc($exe)){
												if($row['group_id']==$rec['id'])
													$sele = DBout('selected="selected"');
												else
													$sele = DBout('');
												?>
												<option <?php echo  DBout($sele) ?> value="<?php echo DBout($rec['id']) ?>"><?php echo DBout($rec['title']) ?></option>
												<?php
											}
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<label>Select Recipient</label>
									<select name="phone_number_id" required id="phone_number_id" class="form-control">
										<option value="">- Select Group Above -</option>
									</select>
								</div>
								<div class="form-group">
									<label>Immediate Message</label>
									<textarea name="apt_message" required class="form-control"><?php echo DBout($row['apt_message'])?></textarea>
									<small class="small text-info"> Notes : This messsage will send immediately on create appointment</small>
								</div>

								<div class="col-lg-12" id="padding_zero">
									<div class="portlet">
										<div class="portlet-heading bg-custom" id="bg_custom_orange">
											<h5 id="h5_alert">
												Appointment alerts
												<a data-toggle="" href="javascript:;"><i class="fa fa-plus" title="Add More" onClick="addMoreAlertMsg()" id="fa_plus_sign"></i></a>
											</h5>
											<div class="portlet-widgets">
												<span class="divider"></span>
												<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" id="h5"></i></a>
											</div>
											<div class="clearfix"></div>
										</div>

										<div class="panel-collapse collapse in" id="bg-primary" aria-expanded="true">
											<div class="portlet-body" id="alertMSGContainer" id="padding_ten">
												<div>

													<?php
													$alerts = sprintf("select * from appointment_alerts where apt_id=%s",
	                                                           mysqli_real_escape_string($link,filtervar($aptID)));
													
													$altRes = mysqli_query($link,$alerts);
													if(mysqli_num_rows($altRes)){
														$index = 1;
														while($altRow = mysqli_fetch_assoc($altRes)){
															?>
															<table width="100%" class="delay_table">
																<?php if($index > 1){
																	?>
																	<tr><td colspan="2"><hr id="hr_line2"></td></tr>
																	<?php
																}?>
																<tr>
																	<td width="25%">Date/Time</td>
																	<td>
																		<input type="text" name="before_date[]" class="form-control addDatePicker remainder_dely" value="<?php echo DBout($altRow['message_date']); ?>"/>
																		<select class="form-control remainder_dely"  name="before_time[]">
																			<?php
																			$timeArray = getTimeArray();
																			foreach($timeArray as $key => $value){
																				if($altRow['message_time']==$key) { $sel = 'selected="selected"'; } else { $sel = ''; };
																				?>
																				<option <?php echo DBout($sel) ?> value="<?php echo DBout($key) ?>"><?php echo DBout($value) ?></option>
																				<?php

																			}
																			?>
																		</select>
																	</td>
																</tr>
																<tr>
																	<td>Message</td>
																	<td>
																		<textarea name="before_message[]" class="form-control textCounter"><?php echo DBout($altRow['apt_message'])?></textarea>
																		<span class="showCounter">
																			<span class="showCount"><?php echo DBout($maxLength)?></span> Characters left
																		</span>
																	</td>
																</tr>
																<tr>
																	<td>Attach Media</td>
																	<td>
																		<input type="file" name="before_media[]">
																		<input type="hidden" name="before_hidden_media[]" value="<?php echo DBout($altRow['media'])?>">
																		<?php if($index > 1){?>
																			<span class="fa fa-trash" id="fa_trash" title="Remove Message" onclick="removeFollowUp(this)"></span>
																		<?php }?>
																		<?php
																		if(trim($altRow['media'])!=''){
																			echo DBout(isMediaExists($altRow['media']));
																		}
																		?>
																	</td>
																</tr>
															</table>
															<?php
															$index++;
														}
													}else{

													}
													?>

												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-lg-12" id="portlet">
									<div class="portlet">
										<div class="portlet-heading bg-custom" id="bg_custom_green">
											<h5 id="h5">
												Follow up Messages for this appointment.
												<a data-toggle="" href="javascript:;"><i class="fa fa-plus" title="Add More" onClick="addMoreFollowUpMsg()" id="fa_plus_sign"></i></a>
											</h5>
											<div class="portlet-widgets">
												<span class="divider"></span>
												<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" id="h5"></i></a>
											</div>
											<div class="clearfix"></div>
										</div>

										<div class="panel-collapse collapse in" id="bg-primary"  aria-expanded="true">
											<div class="portlet-body" id="followUpContainer" id="padding_ten">
												<div>

													<?php
													$follow = sprintf("select * from appointment_followup_msgs where apt_id=%s",
	                                                           mysqli_real_escape_string($link,filtervar($aptID)));
													
													
													$followRes = mysqli_query($link,$follow);
													if(mysqli_num_rows($followRes)){
														$index = 1;
														while($followRow = mysqli_fetch_assoc($followRes)){
															?>
															<table width="100%" class="delay_table">
																<?php if($index > 1){
																	?>
																	    <tr><td colspan="2"><hr id="hr_line2"></td></tr>	
																	<?php
																}?>
																<tr>
																	<td width="25%">Date/Time</td>
																	<td>
																		<input type="text" name="delay_date[]" class="form-control addDatePicker remainder_dely" value="<?php echo DBout($followRow['message_date']) ?>" />
																		<select class="form-control remainder_dely"  name="delay_time[]">
																			<?php
																			$timeArray = getTimeArray();
																			foreach($timeArray as $key => $value){
																				if($followRow['message_time']==$key) { $sel = 'selected="selected"'; } else { $sel = DBout(''); };
																				?>
																				
																				<option <?php echo DBout($sel) ?> value="<?php echo DBout($key) ?>"><?php echo DBout($value) ?></option>
																				<?php
																			}
																			?>
																		</select>
																	</td>
																</tr>
																<tr>
																	<td>Message</td>
																	<td>
																		<textarea name="delay_message[]" class="form-control textCounter"><?php echo DBout($followRow['apt_message'])?></textarea>
																		<span class="showCounter">
																			<span class="showCount"><?php echo DBout($maxLength)?></span> Characters left
																		</span>
																	</td>
																</tr>
																<tr>
																	<td>Attach Media</td>
																	<td>
																		<input type="file" name="delay_media[]">
																		<input type="hidden" name="delay_hidden_media[]" value="<?php echo DBout($followRow['media'])?>">
																		<?php if($index > 1){?>
																			<span class="fa fa-trash" id="fa_trash" title="Remove Message" onclick="removeFollowUp(this)"></span>
																		<?php }?>
																		<?php
																		if(trim($followRow['media'])!=''){
																			echo DBout(isMediaExists($followRow['media']));
																		}
																		?>
																	</td>
																</tr>
															</table>
															<?php
															$index++;
														}
													}
													?>

												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<input type="hidden" name="apt_id" value="<?php echo DBout($aptID)?>">
									<input type="hidden" name="cmd" value="update_appointment" />
									<input type="submit" value="Save" class="btn btn-primary" />
									<input type="button" value="Back" class="btn btn-default" />
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>


	<div id="follow_up_structure" id="alert_msg_structure">
		<table width="100%" class="delay_table">
			<tr><td colspan="2"><hr id="hr_line2"></td></tr>
			<tr>
				<td width="25%">Date/Time</td>
				<td>
					<input type="text" name="delay_date[]" class="form-control DatePickerToBe remainder_dely"  />
					<select class="form-control remainder_dely"  name="delay_time[]">
						<?php
						$timeArray = getTimeArray();
						foreach($timeArray as $key => $value){
							?>
							<option value="<?php echo DBout($key) ?>"> <?php echo DBout($value) ?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr><td>Message</td><td><textarea name="delay_message[]" class="form-control textCounter"></textarea><span class="showCounter"><span class="showCount"><?php echo DBout($maxLength)?></span> Characters left</span></td></tr>
			<tr><td>Attach Media</td><td><input type="file" name="delay_media[]" id="file"><span class="fa fa-trash" id="fa_trash" title="Remove Message" onclick="removeFollowUp(this)"></span></td></tr>
		</table>
	</div>

	<div id="alert_msg_structure" id="alert_msg_structure">
		<table width="100%" class="delay_table">
			<tr><td colspan="2"><hr id="hr_line2"></td></tr>
			<tr>
				<td width="25%">Date/Time</td>
				<td>
					<input type="text" name="before_date[]" class="form-control DatePickerToBe remainder_dely" />
					<select class="form-control remainder_dely"  name="before_time[]">
						<?php
						$timeArray = getTimeArray();
						foreach($timeArray as $key => $value){
							
							?>
							<option value="<?php echo DBout($key) ?>"> <?php echo DBout($value) ?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr><td>Message</td><td><textarea name="before_message[]" class="form-control textCounter"></textarea><span class="showCounter"><span class="showCount"><?php echo DBout($maxLength)?></span> Characters left</span></td></tr>
			<tr><td>Attach Media</td><td><input type="file" name="before_media[]" id="file"><span class="fa fa-trash" id="fa_trash" title="Remove Message" onclick="removeFollowUp(this)"></span></td></tr>
		</table>
	</div>


	<?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>
<script>
	var group_id= '<?php echo DBout($row['group_id'])?>';
	var phone_number = '<?php echo DBout($row['phone_number'])?>';
</script>
<script src="js/edit_apts.js" rel="script" type="text/javascript"></script>