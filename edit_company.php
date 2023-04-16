<?php

	include_once( "header.php" );

	include_once( "left_menu.php" );

	$id = $_REQUEST['id'];

	$sql = "select * from companies where id='".$id."' and user_id='".$_SESSION['user_id']."'";

	$res = mysqli_query($link,$sql);

	if(mysqli_num_rows($res))

		$row = mysqli_fetch_assoc($res);

	else

		die("Company already deleted.");

?>

 <link rel="stylesheet" type="text/css" href="css/multi-select.css">
  <script src="js/jquery.multi-select.js"></script>


<div class="main-panel">

	<?php include_once('navbar.php'); ?>

	<div class="content">

		<div class="container-fluid">

			<div class="row">

				<div class="col-md-12">

					<div class="card">

						<div class="header">

							<h4 class="title"> Update Company

                                <input type="button"

                                       class="btn btn-primary move-right"

                                       value="Back"

                                       onclick="window.location='view_company.php'"/>

                            </h4>

							<p class="category">Update Company Details.</p>

						</div>

						<div class="content table-responsive">

							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">

								<div class="form-group">

									<label>Name *</label>

									<input type="text" name="company_name" class="form-control removeSpaces" required value="<?php echo $row['name']; ?>">

								</div>
                               
								<div class="form-group">

									<label>Website URl *</label>

									<input type="text" name="website_url" class="form-control removeSpaces" required value="<?php echo $row['website_url']; ?>">

									<span style="color: red; font-size: 12px;">Ex: https://yourdomain.com/root_folder</span>

								</div>
                                         <div class="form-group">
                                    <label>Merchant Login *</label>
                                    <input type="text" name="merchant_login" class="form-control removeSpaces"required value="<?php echo $row['merchant_login']; ?>">
                                </div> 
                                    <div class="form-group">
                                    <label>Merchant Token *</label>
                                    <input type="text" name="merchant_token" class="form-control removeSpaces" required value="<?php echo $row['merchant_token']; ?>">
                                </div> 
								 <div class="form-group phoneNumberSection">

                                <label>Assign Number*</label> 
								<label style="margin-left: 13%;">Assigned Number*</label>

                                          <?php if ($appSettings['sms_gateway'] == 'twilio') {

												$query = mysqli_query($link,"SELECT `Assign_numbers` FROM `companies` WHERE id != $id");
												while($out = mysqli_fetch_assoc($query)){
													$data = explode(',',$out['Assign_numbers']);
													foreach($data as $d){
														if ($d != ''){
															$booked_numbers[] = $d;
														}
													}
												}

                                            $sel = "select * from users_phone_numbers where user_id='" . $_SESSION['user_id'] . "' and ( type='1' or type='4' )";

                                        } else if ($appSettings['sms_gateway'] == 'plivo') {

                                            $sel = "select * from users_phone_numbers where user_id='" . $_SESSION['user_id'] . "' and type='2'";

                                        } else if ($appSettings['sms_gateway'] == 'nexmo') {

                                            $sel = "select * from users_phone_numbers where user_id='" . $_SESSION['user_id'] . "' and type='3'";

                                        } else {

                                            $sel = "select * from users_phone_numbers where user_id='" . $_SESSION['user_id'] . "'";

                                        }

                                         ?>

                                        

                                        </div>

										
                                        <select id='pre-selected-options' multiple='multiple' name="Assign_number[]" >
                                      <?php
                                     $rec = mysqli_query($link, $sel);

                                        if (mysqli_num_rows($rec)) {
                                        	$Assign_numbers = explode(',', $row['Assign_numbers']);
                                            while ($numbers = mysqli_fetch_assoc($rec)) {
												if (!in_array($numbers["phone_number"],$booked_numbers)){
                                            	$selected = '';

                                            	$selected = in_array($numbers['phone_number'], $Assign_numbers) ? 'selected' : '';
                                                

                                                echo '<option ' . $selected . ' value="' . $numbers['phone_number'] . '">' . $numbers['phone_number'] . '</option>'."\n";

                                            } }

                                           

                                        } ?>


                                    </select>

							

								<div class="form-group smsTextsSection"><label>Description *</label>

									<textarea name="description" required placeholder="Enter company description..." class="form-control"><?php echo $row['description']; ?></textarea>

								</div>


								<div class="form-group text-right m-b-0">

									<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

									<button class="btn btn-primary waves-effect waves-light" type="submit"> Save

                                    </button>

									<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel

                                    </button>

									<input type="hidden" name="cmd" value="update_company"/>

								</div>

							</form>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

	<?php include_once( "footer_info.php" ); ?>

</div>

<?php include_once( "footer.php" ); ?>

<script type="text/javascript">

	var timeoptions = '<?php echo $timeOptions?>';

	var options = '<?php echo $options?>';

	var maxlenght = '<?php echo DBout( $maxLength ); ?>';

</script>

<script src="scripts/add_campaign.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // $('#multiselect').multiselect();
        $('#pre-selected-options').multiSelect();
    });
</script>
