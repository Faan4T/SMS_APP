<?php
include_once("header.php");
include_once("left_menu.php");
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
									Make New Appointment
									<input type="button" class="btn btn-default" value="Back" id="btn_right" onclick="window.location='view_apts.php'" />
								</h4>
								<p class="category">Make new appointment from here.</p>
							</div>
							<div class="content table-responsive">
								<div class="form-group">
									<label>Title</label>
									<input type="text"  parsley-trigger="change" name="apt_title" class="form-control" required />
								</div>
								<div class="form-group">
									<label>Date/Time</label><br />
									<input type="text" name="apt_date"  parsley-trigger="change" required class="form-control addDatePicker remainder_dely" />
									<select name="apt_time" class="form-control remainder_dely">
										<?php
										$timeArray = getTimeArray();
										foreach($timeArray as $key => $value){

											?>
											<option value="<?php echo DBout($key) ?>"><?php echo DBout($value)?></option>
											<?php
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<label>Select Group</label>
									<select name="group_id"  parsley-trigger="change" required class="form-control" onchange="getGroupNumbers(this.value)">
										<option value="">Choose One</option>
										<?php
										$sql = sprintf("select id,title from campaigns where user_id=%s",
											mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
										);

										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											while($row=mysqli_fetch_assoc($res)){
												?>
												<option value="<?php echo DBout($row['id']) ?>"><?php echo DBout($row['title']) ?></option>
												<?php
											}
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<label>Select Recipient</label>
									<select name="phone_number_id"  parsley-trigger="change" required id="phone_number_id" class="form-control">
										<option value="">- Select Group Above -</option>
									</select>
								</div>
								<div class="form-group">
									<label>Immediate Message</label>
									<span id="immediate_msg">Appointment date = %apt_date%.</span>
									<textarea name="apt_message"  parsley-trigger="change" required class="form-control"></textarea>
									<small class="small text-info"> Notes : This messsage will send immediately on create appointment</small>
								</div>

								<div class="col-lg-12 padding_left" >
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
											<div class="portlet-body padding_top" id="alertMSGContainer" >
												<div>
													<table width="100%">
														<tr>
															<td width="25%">Date/Time</td>
															<td>
																<input type="text" name="before_date[]" placeholder="Date/Time" class="form-control addDatePicker remainder_dely">
																<select class="form-control remainder_dely"  name="before_time[]" >
																	<?php
																	$timeArray = getTimeArray();
																	foreach($timeArray as $key => $value){
																		?>
																		<option value="<?php echo DBout($key) ?>"><?php echo DBout($value)?></option>
																		<?php
																	}
																	?>
																</select>
															</td>
														</tr>
														<tr>
															<td>Message</td>
															<td>
																<textarea name="before_message[]" class="form-control textCounter"></textarea>
																<span class="showCounter">
																	<span class="showCount"><?php echo DBout($maxLength)?></span> Characters left
																</span>
															</td>
														</tr>
														<tr>
															<td>Attach Media</td>
															<td>
																<input type="file" name="before_media[]">
															</td>
														</tr>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-12 padding_left1">
									<div class="portlet">
										<div class="portlet-heading bg-custom"  id="bg_custom_green">
											<h5 id="h5" >
												Follow up Messages for this appointment.
												<a data-toggle="" href="javascript:;"><i class="fa fa-plus" title="Add More" onClick="addMoreFollowUpMsg()" id="fa_plus_sign"></i></a>
											</h5>
											<div class="portlet-widgets">
												<span class="divider"></span>
												<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" id="h5" ></i></a>
											</div>
											<div class="clearfix"></div>
										</div>

										<div class="panel-collapse collapse in" id="bg-primary"  aria-expanded="true">
											<div class="portlet-body" id="followUpContainer" class="padding_ten">
												<div>
													<table width="100%">
														<tr>
															<td width="25%">Date/Time</td>
															<td>
																<input type="text" placeholder="Date/Time" name="delay_date[]" class="form-control addDatePicker remainder_dely">
																<select class="form-control remainder_dely"  name="delay_time[]">
																	<?php
																	$timeArray = getTimeArray();
																	foreach($timeArray as $key => $value){
																		?>
																		<option value="<?php echo DBout($key)?>"><?php echo DBout($value)?></option>
																		<?php
																	}
																	?>
																</select>
															</td>
														</tr>

														<tr>
															<td>Message</td>
															<td>
																<textarea name="delay_message[]" class="form-control textCounter"></textarea>
																<span class="showCounter">
																	<span class="showCount"><?php echo DBout($maxLength)?></span> Characters left
																</span>
															</td>
														</tr>
														<tr>
															<td>Attach Media</td>
															<td>
																<input type="file" name="delay_media[]">
															</td>
														</tr>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<input type="hidden" name="cmd" value="save_appointment" />
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

	<div id="follow_up_structure" class="follow_up_structure">
		<table width="100%" class="delay_table">
			<tr><td colspan="2"><hr id="hr_line2"></td></tr>
			<tr>
				<td width="25%">Date/Time</td>
				<td>
					<input type="text" placeholder="Date/Time" name="delay_date[]" class="form-control DatePickerToBe remainder_dely">
					<select class="form-control remainder_dely"  name="delay_time[]">
						<?php
						$timeArray = getTimeArray();
						foreach($timeArray as $key => $value){
							?>
							<option value="<?php echo DBout($key) ?>"><?php echo DBout($value) ?></option>
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

	<div id="alert_msg_structure" class="alert_msg_structure">
		<table width="100%" class="delay_table">
			<tr><td colspan="2"><hr id="hr_line2" ></td></tr>
			<tr>
				<td width="25%">Date/Time</td>
				<td>
					<input type="text" placeholder="Date/Time" name="before_date[]" class="form-control DatePickerToBe remainder_dely" />
					<select class="form-control remainder_dely"  name="before_time[]">
						<?php
						$timeArray = getTimeArray();
						foreach($timeArray as $key => $value){
							?>
							<option value="<?php echo DBout($key) ?>"><?php echo DBout($value) ?></option>
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
<script type="text/javascript" src="scripts/js/parsley.min.js"></script>
<script src="scripts/js/bootstrap-datepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="js/add_apts.js">
</script>