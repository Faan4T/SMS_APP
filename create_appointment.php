<?php
	include_once("header.php");
	include_once("left_menu.php");


$sql1 = sprintf("select phone_number from subscribers where user_id=%s and status = 1 ",
		mysqli_real_escape_string($link,filterVar($_SESSION['user_id'])));

$exeAuto = mysqli_query($link,$sql1);

while($rowAuto = mysqli_fetch_assoc($exeAuto))
{
	$array .= '"';
	$array .= $rowAuto["phone_number"];
	$array .='",';
} 
$array = trim($array,",");


$sql2 = sprintf("select * from appointments where id=%s",
				mysqli_real_escape_string($link,DBin($_REQUEST['id']))
			);
$exeAuto = mysqli_query($link,$sql2);

$rowAppt = mysqli_fetch_assoc($exeAppt);

?>


<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<form method="post" enctype="multipart/form-data" action="server.php">
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
									<label>Name</label>
									<input type="text" name="name" class="form-control" value="<?php echo DBout($rowAppt['name']); ?>" />
								</div>
                                <div class="form-group">
									<label>Phone Number</label>
									<input type="text" name="phone_number" class="form-control" id="client_ph_number" required=""  value="<?php echo DBout($rowAppt['phone_number']); ?>"/>
								</div>
                                <div class="form-group">
									<label>Email</label>
									<input type="email" name="email" class="form-control"  value="<?php echo DBout($rowAppt['email']); ?>" />
								</div>
                                <?php
                                $appt_date = DBout(explode(" ",$rowAppt['appointment_date']));
                                ?>
                                
								<div class="form-group">
									<label>Date/Time</label><br />
									<input type="text" name="apt_date" class="form-control addDatePicker" id="remainder_dely" required="" value="<?php echo DBout($appt_date[0]); ?>" />
									<select name="apt_time" class="form-control" id="remainder_dely" required="">
									<?php
										$timeArray = getTimeArray();
										foreach($timeArray as $key => $value){
											if($appt_date[1]==$key){
												$sel = DBout("selected"); 
											}else{
												$sel = DBout(""); 
											}
                                            
											?>
											   <option <?php echo DBout($sel) ?> value="<?php echo DBout($key) ?>"><?php echo DBout($value) ?></option>';
										    <?php
										} 
					                ?>				
									</select>
								</div>
								<div class="form-group">
									<label>Select Appointment Template</label>
									<select name="template_id" class="form-control" required="">
									<option value="">Choose One</option>
                                    <?php
										
										$sql =sprintf("select id,title from appointment_templates where user_id=%s",
										mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
										);
										
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											while($row=mysqli_fetch_assoc($res)){
										
											    if($rowAppt['template_id']==$row['id']){ 
													$sel = DBout("selected");
												}else{ 
													$sel = DBout(""); 
												}
												
												?>
												<option <?php echo DBout($sel) ?> value="<?php echo DBout($row['id']) ?>"><?php echo DBout($row['title']) ?></option>
										<?php
											}
										}
									?>
									</select>
								</div>
								
								<div class="form-group">
									<input type="hidden" name="cmd" value="create_new_appointment" />
                                    <?php if($_REQUEST['id']!=""){
                                        ?>
                                        <input type="hidden" name="id" value="<?php echo DBout($_REQUEST['id']); ?>" />
                                        <?php  
                                    }
                                    ?>
                                    
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
                    <input type="text" name="delay_date[]" class="form-control DatePickerToBe" id="remainder_dely" />
                    <select class="form-control"  name="delay_time[]" id="remainder_dely">
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
        	<tr><td colspan="2"><hr id="hr_line2"></td></tr>
            <tr>
                <td width="25%">Date/Time</td>
                <td>
                <input type="text" name="before_date[]" class="form-control DatePickerToBe" id="remainder_dely" />
                <select class="form-control"  name="before_time[]" id="remainder_dely">
                    <?php
    					$timeArray = getTimeArray();
    					foreach($timeArray as $key => $value){
							?>
    						 <option value="<?php DBout($key) ?>"><?php DBout($value) ?></option>
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

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="js/create_appointment.js">
  var array ='<?php echo DBout($array)?>'
</script>