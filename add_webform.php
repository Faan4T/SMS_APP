	<?php

	include_once("header.php");

	include_once("left_menu.php");

	?>

	<link rel="stylesheet" href="css/pick-a-color-1.2.3.min.css" />

	<div class="main-panel">

		<?php include_once('navbar.php');?>

		<div class="content">

			<div class="container-fluid">

				<div class="row">

					<div class="col-md-12">

						<div class="card">

							<div class="header">

								<h4 class="title">

									Add WebForm

									<input type="button" class="btn btn-primary" value="Back" id="btn_right" onclick="window.location='view_autores.php'" />

								</h4>

								<p class="category">Add new webform here.</p>

							</div>

							<div class="content table-responsive">

								<form method="post" enctype="multipart/form-data" id="createWebForm">

									<div class="form-group">

										<label>WebForm Name</label>

										<input type="text" name="webform_name" class="form-control" required>

									</div>

									<div class="form-group">

										<label>Select Campaign</label>

										<select name="campaign_id" class="form-control" required>

											<?php

											$sql = sprintf("select id,title from campaigns where user_id=%s",

                                                mysqli_real_escape_string($link,filterVar($_SESSION['user_id'])));

											$res = mysqli_query($link,$sql);

											if(mysqli_num_rows($res)){

												while($row = mysqli_fetch_assoc($res)){

													?>

													<option value="<?php echo DBout($row['id']) ?>"><?php echo DBout($row['title'])?></option>

													<?php

												}

											}

											else{

												?>

												<option value="">No campaign added yet.</option>

												<?php

											}

											?>

										</select>

									</div>

									<div class="form-group">

										<label>Label for Name Field</label>

										<input type="text" name="label_for_name_field" class="form-control" required>

									</div>

									<div class="form-group">

										<label>Label for Phone Field</label>

										<input type="text" name="label_for_phone_field" class="form-control" required>

									</div>

									<div class="form-group">

										<label>Label for Email Field</label>

										<input type="text" name="label_for_email_field" class="form-control" required>

									</div>

									<div class="form-group">

										<label>Label for Disclaimer Text Field</label>

										<input type="text" name="label_for_disclaimer_text" class="form-control">

									</div>

									<div class="form-group">

										<label>Heading for Custom Information Panel</label>

										<input type="text" name="heading_for_custom_info_panel" class="form-control">

									</div>

									<div id="fieldSectionContainer"></div>

									<div class="form-group">

										<a href="javascript:void(0)" id="fieldsection"  onclick="createCustomField()">Add new field</a>

									</div>

									<div class="form-group">

										<label>Disclaimer Text</label>

										<textarea name="disclaimer_text" class="form-control textCounter"></textarea>

									</div>

									<div class="form-group">

										<label class="radio-inline"><input type="radio" name="showing_method" class="showing_method" value="1" checked="checked">Show in PopUp</label>

										<label class="radio-inline"><input type="radio" name="showing_method" class="showing_method" value="2">Show on Page</label>

									</div>

									<div class="form-group">

										<label class="radio-inline"><input type="radio" name="webform_type" class="webform_type" value="1" checked="checked">Responsive Widget</label>

										<label class="radio-inline"><input type="radio" name="webform_type" class="webform_type" value="2">Fixed Width Widget</label>

									</div>

									<div class="customize_section" id="customize_section">

										<div class="form-group">

											<label id="customize_custom">Field Customization</label>

										</div>

										<div class="form-group">

											<label>Field Width</label>

											<input type="text" name="field_width" class="form-control numericOnly">

										</div>

										<div class="form-group">

											<label>Field Height</label>

											<input type="text" name="field_height" class="form-control numericOnly">

										</div>

										<div class="form-group" id="color_for_lable">

											<label>Color for Field Labels</label>

											<input type="text" name="color_for_label" class="color form-control">

										</div>

										<div class="form-group">

											<label id="customize_custom">Widget Customization</label>

										</div>

										<div class="form-group">

											<label>Frame Width</label>

											<input type="text" name="frame_width" class="form-control numericOnly">

										</div>

										<div class="form-group">

											<label>Frame Height</label>

											<input type="text" class="form-control" value="Height will auto according to width." readonly>

										</div>

										<div class="form-group" id="color_for_lable">

											<label>Background Color</label>

											<input type="text" name="frame_bg_color" class="color form-control">

										</div>

										<div class="form-group" id="color_for_lable">

											<label>Subscribe Button Color</label>

											<input type="text" name="subs_btn_bg_color" class="color form-control">

										</div>

										<div class="form-group" id="color_for_lable">

											<label>Close Button Color</label>

											<input type="text" name="close_btn_bg_color" class="color form-control">

										</div>

									</div>

									<div class="form-group text-right m-b-0">

										<input type="hidden" name="cmd" value="add_new_webform" />



			<button id="webFormSaveButton" class="btn btn-primary waves-effect waves-light" type="button" onclick="saveWebForm()"> Save </button>

			<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>

		</div>

	</form>

</div>

</div>

</div>

</div>

</div>

</div>

<?php include_once("footer_info.php");?>

</div>

<?php include_once("footer.php");?>

<script src="scripts/js/tinycolor-0.9.15.min.js"></script>

<script src="scripts/js/pick-a-color-1.2.3.min.js"></script>

<script src="scripts/js/add_webfrom.js" >



</script>