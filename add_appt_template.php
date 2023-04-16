	<?php
	include_once("header.php");
	include_once("left_menu.php");
	?>
	<div class="main-panel">
		<?php include_once('navbar.php');?>

		<?php
		if(isset($_REQUEST['id']) && $_REQUEST['id']!="" && $_REQUEST['id']!="0"){
			$sql1 = sprintf("select * from appointment_templates where id=%s",
				mysqli_real_escape_string($link,DBin($_REQUEST['id']))
			);
			$res1 = mysqli_query($link,$sql1);
			$appt_temp = mysqli_fetch_assoc($res1);
		}
		?>

		<div class="content">
			<div class="container-fluid">
				<div class="row">
					<form method="post" enctype="multipart/form-data" action="server.php">
						<div class="col-md-12">
							<div class="card">
								<div class="header">
									<h4 class="title">
										Make Appointment Templates
										<input type="button" class="btn btn-default" value="Back" id="btn_right" onclick="window.location='view_apts.php'" />
									</h4>
									<p class="category">Make new appointment template from here.</p>
								</div>
								<div class="content table-responsive">
									<div class="form-group">
										<label>Title</label>
										<input type="text" name="title" class="form-control" value="<?php echo DBout($appt_temp['title']); ?>" />
									</div>								
									<div class="form-group">
										<label>Select Group</label>
										<select name="group_id" class="form-control" onchange="getGroupNumbers(this.value)">
											<option value="">Choose One</option>
											<?php
											$sql = sprintf("select id,title from campaigns where user_id=%s",
												mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
											);
											
											
											$res = mysqli_query($link,$sql);
											if(mysqli_num_rows($res)){
												while($row=mysqli_fetch_assoc($res)){
													
													if($row['id']==$appt_temp['group_id']){ $sel = "selected"; }else{ $sel = ""; }
													?>
													<option <?php echo DBout($sel) ?> value="<?php echo DBout($row['id']) ?>"><?php echo DBout($row['title']) ?></option>	
													<?php
												}
											}
											?>
										</select>
									</div>
									<div class="form-group">
										<label>Immediate Message</label>									
										<textarea name="immediate_sms" class="form-control"><?php echo DBout($appt_temp['immediate_sms']); ?></textarea>
										<small class="small text-info"> Notes : This messsage will send immediately on create appointment</small>
										<span class="small text-inverse">
											<br /> Appointment date = %apt_date%  
											<br /> Client Name = %name% 
										</span>
									</div>

									<div class="col-lg-12 padding_left">
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
												<div class="portlet-body padding_top" id="alertMSGContainer">
													<?php
													$si = DBout(1);
													$sql2 = sprintf("select * from template_reminders where template_id=%s and reminder_type = 1 order by id asc",
														mysqli_real_escape_string($link,filterVar($appt_temp['id']))
													);

													$res2 = mysqli_query($link,$sql2);
													if(mysqli_num_rows($res2)){
														while($temp_remin = mysqli_fetch_assoc($res2)){
															?>
															<div>
																<table width="100%" class="delay_table">
																	<tr><td colspan="2"><hr class="hr_line2"></td></tr>
																	<tr>
																		<td width="25%">Date/Time</td>
																		<td>

																			<select name="reminder_days[]" class="form-control"  id="remainder_dely">
																				<?php
																				for($days=0;$days<=180; $days++)
																				{
																					if($days == $temp_remin['reminder_days'])
																					{
																						$sel_day = DBout('selected="selected"');
																					}
																					else
																					{
																						$sel_day = DBout("");
																					}
																					?>
																					<option <?php echo DBout($sel_day)?> value="<?php echo DBout($days)?>">Before <?php echo DBout($days)?> Day(s)</option>
																					<?php
																				}
																				?>
																			</select>
																			<select class="form-control"  name="reminder_time[]" id="remainder_dely">
																				<?php
																				$timeArray = getTimeArray();
																				foreach($timeArray as $key => $value){
																					if($key==$temp_remin['reminder_time']){ $sel = "selected"; }else{ $sel = ""; }
																					?>
																					<option <?php echo DBout($sel) ?> value="<?php echo DBout($key)?>">At <?php echo DBout($value) ?></option>
																					<?
																				}
																				?>
																			</select>
																			<input name="reminder_type[]" value="1" type="hidden" />
																		</td>
																	</tr>
																	<tr>
																		<td>Message</td>
																		<td>
																			<textarea name="sms_text[]" class="form-control textCounter"><?php echo DBout($temp_remin['sms_text']); ?></textarea>
																			<span class="small text-inverse">
																				Appointment date = %apt_date%  
																				<br /> Client Name = %name% 
																			</span>
																			<span class="showCounter">
																				<span class="showCount"><?php echo DBout($maxLength)?></span> Characters left
																			</span>
																		</td>
																	</tr>
																	<tr>
																		<td>Attach Media</td>
																		<td>
																			<input type="file" name="reminder_media[]">
																			<?php if($si>1){ ?>
																				<span class="fa fa-trash" id="fa_trash"  title="Remove Message" onclick="removeFollowUp(this)"></span>
																			<?php } ?>
																		</td>
																	</tr>
																</table>
															</div>
															<?php
															$si++;
														}
													}
													?>
												</div>
											</div>
										</div>
									</div>

									<div class="col-lg-12 padding_left">
										<div class="portlet">
											<div class="portlet-heading bg-custom" id="bg_custom_green">
												<h5 id="h5">
													Follow up Messages for this appointment.
													<a data-toggle="" href="javascript:;"><i class="fa fa-plus" title="Add More" onClick="addMoreFollowUpMsg()" id="fa_plus_sign" ></i></a>
												</h5>
												<div class="portlet-widgets">
													<span class="divider"></span>
													<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" id="h5"></i></a>
												</div>
												<div class="clearfix"></div>
											</div>

											<div class="panel-collapse collapse in" id="bg-primary"  aria-expanded="true">
												<div class="portlet-body padding_top" id="followUpContainer">
													<?php
													$si=1;
													$sql3 = sprintf("select * from template_reminders where template_id=%s and reminder_type='2' order by id asc",
														mysqli_real_escape_string($link,filterVar($appt_temp['id']))
													);
													$res3 = mysqli_query($link,$sql3);
													if(mysqli_num_rows($res3)){
														while($temp_remin = mysqli_fetch_assoc($res3)){
															?>  
															<div>
																<table width="100%" class="delay_table">
																	<tr><td colspan="2"><hr class="hr_line2"></td></tr>
																	<tr>
																		<td width="25%">Date/Time</td>
																		<td>
																			<select name="reminder_days[]" class="form-control" id="remainder_dely">
																				<?php
																				for($days=0;$days<=180; $days++)
																				{
																					if($days == $temp_remin['reminder_days'])
																					{
																						$sel_day = DBout('selected="selected"');
																					}
																					else
																					{
																						$sel_day = DBout("");
																					}
																					?>
																					<option <?php echo DBout($sel_day)?> value="<?php echo DBout($days)?>">After <?php echo DBout($days)?> Day(s)</option>
																					<?php
																				}
																				?>
																			</select>
																			<select class="form-control"  name="reminder_time[]" id="remainder_dely">
																				<?php
																				$timeArray = getTimeArray();
																				foreach($timeArray as $key => $value){
																					if($key==$temp_remin['reminder_time']){ $sel = "selected"; }else{ $sel = ""; }


																					?>
																					<option <?php echo DBout($sel) ?> value="<?php echo DBout($key)?>">At <?php echo DBout($value) ?></option>
																					<?php  

																				}
																				?>
																			</select>
																			<input name="reminder_type[]" value="2" type="hidden" />
																		</td>
																	</tr>

																	<tr>
																		<td>Message</td>
																		<td>
																			<textarea name="sms_text[]" class="form-control textCounter"><?php echo DBout($temp_remin['sms_text']); ?></textarea>
																			<span class="small text-inverse">
																				Appointment date = %apt_date%  
																				<br /> Client Name = %name% 
																			</span>
																			<span class="showCounter">
																				<span class="showCount"><?php echo DBout($maxLength)?></span> Characters left
																			</span>
																		</td>
																	</tr>
																	<tr>
																		<td>Attach Media</td>
																		<td>
																			<input type="file" name="reminder_media[]">
																			<?php if($si>1){ ?>
																				<span class="fa fa-trash" id="fa_trash" title="Remove Message" onclick="removeFollowUp(this)"></span>
																			<?php } ?>
																		</td>
																	</tr>
																</table>
															</div>
															<?php
															$si++;
														}
													}
													?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<?php
										if(isset($_REQUEST['id'])){
											?>
											<input type="hidden" name="id" value="<?php echo DBout($appt_temp['id']); ?>" />
											<?php
										}
										?>
										<input type="hidden" name="cmd" value="save_appt_template" />
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
						<select name="reminder_days[]" class="form-control" id="remainder_dely">
							<?php
							for($days=0; $days<=180; $days++)
							{

								?>
								<option value="<?php echo DBout($days)?>">After <?php echo DBout($days)?> Day(s)</option>
								<?php
							}
							?>
						</select>
						<select class="form-control"  name="reminder_time[]" id="remainder_dely">
							<?php
							$timeArray = getTimeArray();
							foreach($timeArray as $key => $value){
								?>

								<option value="<?php echo DBout($key) ?>"> At <?php echo DBout($value) ?></option>
								<?php

							}
							?>
						</select>
						<input name="reminder_type[]" value="2" type="hidden" />
					</td>
				</tr>
				<tr><td>Message</td><td><textarea name="sms_text[]" class="form-control textCounter"></textarea>
					<span class="small text-inverse">
						Appointment date = %apt_date%  
						<br /> Client Name = %name% 
					</span>
					<span class="showCounter"><span class="showCount"><?php echo DBout($maxLength)?></span> Characters left</span></td></tr>
					<tr><td>Attach Media</td><td><input type="file" name="reminder_media[]" id="file"><span class="fa fa-trash" id="fa_trash" title="Remove Message" onclick="removeFollowUp(this)"></span></td></tr>
				</table>
			</div>

			<div id="alert_msg_structure" class="alert_msg_structure">
				<table width="100%" class="delay_table">
					<tr><td colspan="2"><hr id="hr_line2"></td></tr>
					<tr>
						<td width="25%">Date/Time</td>
						<td>
							<select name="reminder_days[]" class="form-control" id="remainder_dely">
								<?php
								for($days=0;$days<=180; $days++)
								{

									?>
									<option value="<?php echo DBout($days)?>">Before <?php echo DBout($days)?> Day(s)</option>
									<?php
								}
								?>
							</select>
							<select class="form-control"  name="reminder_time[]" id="remainder_dely">
								<?php
								$timeArray = getTimeArray();
								foreach($timeArray as $key => $value){

									?>
									<option value="<?php echo DBout($key)?>">At <?php echo DBout($value) ?></option>';

									<?php
								}
								?>
							</select>
							<input name="reminder_type[]" value="1" type="hidden" />
						</td>
					</tr>
					<tr><td>Message</td><td><textarea name="sms_text[]" class="form-control textCounter"></textarea>
						<span class="small text-inverse">
							Appointment date = %apt_date%  
							<br /> Client Name = %name% 
						</span><span class="showCounter"><span class="showCount"><?php echo DBout($maxLength)?></span> Characters left</span></td></tr>
						<tr><td>Attach Media</td><td><input type="file" name="reminder_media[]" id="file" ><span class="fa fa-trash" id="fa_trash" title="Remove Message" onclick="removeFollowUp(this)"></span></td></tr>
					</table>
				</div>


				<?php include_once("footer_info.php");?>
			</div>
			<?php include_once("footer.php");?>
			<script>
				<?php
				if(!isset($_REQUEST['id'])){
					?>
					var request_id = DBout(1);
					<?php
				}
				?>
			</script>
			<script src="js/add_appt_temp.js"></script>