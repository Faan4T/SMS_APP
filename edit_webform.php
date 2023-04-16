<?php

include_once("header.php");

include_once("left_menu.php");

$id = DBin($_REQUEST['id']);

$sel = sprintf("select * from webforms where id=%s",

	mysqli_real_escape_string($link,filtervar($id)));



$exe= mysqli_query($link,$sel);

if(mysqli_num_rows($exe))

	$wb = mysqli_fetch_assoc($exe);

else

	$wb = array();





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

								Edit WebForm

								<input type="button" class="btn btn-primary" value="Back" id="btn_right" onclick="window.location='view_webform.php'" />

							</h4>

							<p class="category">Edit webform here.</p>

						</div>

						<div class="content table-responsive">

							<form method="post" enctype="multipart/form-data" action="server.php">

								<div class="form-group">

									<label>WebForm Name</label>

									<input type="text" name="webform_name" class="form-control" value="<?php echo DBout($wb['webform_name'])?>" required>

								</div>

								<div class="form-group">

									<label>Select Campaign</label>

									<select name="campaign_id" class="form-control">

										<?php

										$sql = sprintf("select id,title from campaigns");

										$res = mysqli_query($link,$sql);

										if(mysqli_num_rows($res)){

											while($row = mysqli_fetch_assoc($res)){

												if($wb['campaign_id']==$row['id'])

													$sele = DBout('selected="selected"');

												else

													$sele = DBout('');

												?>

												<option <?php echo DBout($sele) ?> value="<?php echo DBout($row['id']) ?>"><?php echo DBout($row['title']) ?></option>

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

									<input type="text" name="label_for_name_field" class="form-control" value="<?php echo DBout($wb['label_for_name_field'])?>" required>

								</div>

								<div class="form-group">

									<label>Label for Phone Field</label>

									<input type="text" name="label_for_phone_field" class="form-control" value="<?php echo DBout($wb['label_for_phone_field'])?>" required>

								</div>

								<div class="form-group">

									<label>Label for Email Field</label>

									<input type="text" name="label_for_email_field" class="form-control" value="<?php echo DBout($wb['label_for_email_field'])?>" required>

								</div>

								<div class="form-group">

									<label>Label for Disclaimer Text Field</label>

									<input type="text" name="label_for_disclaimer_text" value="<?php echo DBout($wb['label_for_disclaimer_text'])?>" class="form-control">

								</div>

								<div class="form-group">

									<label>Heading for Custom Information Panel</label>

									<input type="text" name="heading_for_custom_info_panel" value="<?php echo DBout($wb['heading_for_custom_info_panel'])?>" class="form-control">

								</div>

								<div id="fieldSectionContainer">

									<?php



									$customFields = DBout(json_decode($wb['custom_fields'],true));

									if(count($customFields)>0){	

										for($i=0; $i<count($customFields); $i++){

											$fieldType = DBout($customFields[$i]['field_type']);

											$fieldLable = DBout($customFields[$i]['field_label']);

											$filedOptions = DBout($customFields[$i]['filed_options']);

											if($customFields[$i]['is_required']=='true')

												$isRequired = DBout('required');

											else

												$isRequired = DBout('');



											if($fieldType=='text'){

												?>

												<div class="fieldSection">

													<div class="col-md-12 padding_left" >

														<div class="col-md-4 padding_left" >

															<input type="text" id="field_label" value="<?php echo DBout($fieldLable)?>" class="form-control" placeholder="Enter field label">

														</div>



														<div class="col-md-4 padding_left" >

															<select id="field_type" class="form-control" onchange="checkFieldType(this)">

																<option value="text" selected="selected">Text Field</option>

																<option value="textarea">Text Box</option>

																<option value="dropdown">Drop down</option>

																<option value="radio">Radio box</option>

																<option value="checkbox">Check Box</option>

															</select>

														</div>



														<div class="col-md-4 padding_left1">

															<label><input type="checkbox" <?php if($isRequired!='') echo DBout('checked="checked"');?> id="is_required" /> Required</label>

															<label id="cross_img"><img src="images/cross.png" alt="Remove" title="Remove" onclick="removeField(this)"></label>

														</div>

													</div>

													<div class="col-md-12 fieldOptionContainer" id="padding_zero"></div>

												</div>

												<?php	

											}else if($fieldType=='textarea'){

												?>

												<div class="fieldSection">

													<div class="col-md-12 padding_left" >

														<div class="col-md-4 padding_left" >

															<input type="text" id="field_label" value="<?php echo DBout($fieldLable)?>" class="form-control" placeholder="Enter field label">

														</div>



														<div class="col-md-4 padding_left" >

															<select id="field_type" class="form-control" onchange="checkFieldType(this)">

																<option value="text">Text Field</option>

																<option value="textarea" selected="selected">Text Box</option>

																<option value="dropdown">Drop down</option>

																<option value="radio">Radio box</option>

																<option value="checkbox">Check Box</option>

															</select>

														</div>



														<div class="col-md-4 padding_left1">

															<label><input type="checkbox" <?php if($isRequired!='')echo DBout('checked="checked"');?> id="is_required" /> Required</label>

															<label id="cross_img"><img src="images/cross.png" alt="Remove" title="Remove" onclick="removeField(this)"></label>

														</div>

													</div>

													<div class="col-md-12 fieldOptionContainer" id="padding_zero"></div>

												</div>

												<?php	

											}else if($fieldType=='dropdown'){

												?>

												<div class="fieldSection">

													<div class="col-md-12 " id="padding_zero">

														<div class="col-md-4 padding_left">

															<input type="text" id="field_label" value="<?php echo DBout($fieldLable)?>" class="form-control" placeholder="Enter field label">

														</div>



														<div class="col-md-4 padding_left">

															<select id="field_type" class="form-control" onchange="checkFieldType(this)">

																<option value="text">Text Field</option>

																<option value="textarea">Text Box</option>

																<option value="dropdown" selected="selected">Drop down</option>

																<option value="radio">Radio box</option>

																<option value="checkbox">Check Box</option>

															</select>

														</div>



														<div class="col-md-4 padding_left1">

															<label><input type="checkbox" <?php if($isRequired!='')echo DBout('checked="checked"');?> id="is_required" /> Required</label>

															<label id="cross_img"><img src="images/cross.png" alt="Remove" title="Remove" onclick="removeField(this)"></label>

														</div>

													</div>

													<div class="col-md-12 fieldOptionContainer" id="padding_zero">

														<div class="col-md-4 addMoreOption" id="padding_zero">

															<?php

															$options = DBout(explode('|',trim($filedOptions,'|')));

															for($j=0; $j<count($options); $j++){

																?>

																<p class="optionHolder">

																	<input type="text" value="<?php echo DBout($options[$j])?>" class="form-control fieldOptions" placeholder="Enter option label">

																</p>

																<?php			

															}

															?>

														</div>

														<div class="col-md-4">

															<a href="javascript:void(0)" id="addnew_option" onclick="addNewOption(this)" title="Add new dropdown option">Add Option</a>

														</div>

														<div class="col-md-4">&nbsp;</div>

													</div>

												</div>

												<?php

											}else if($fieldType=='checkbox'){

												?>

												<div class="fieldSection">

													<div class="col-md-12" id="padding_zero">

														<div class="col-md-4 padding_left">

															<input type="text" id="field_label" value="<?php echo DBout($fieldLable)?>" class="form-control" placeholder="Enter field label">

														</div>



														<div class="col-md-4 padding_left">

															<select id="field_type" class="form-control" onchange="checkFieldType(this)">

																<option value="text">Text Field</option>

																<option value="textarea">Text Box</option>

																<option value="dropdown">Drop down</option>

																<option value="radio">Radio box</option>

																<option value="checkbox" selected="selected">Check Box</option>

															</select>

														</div>



														<div class="col-md-4 padding_left1">

															<label><input type="checkbox" <?php if($isRequired!='')echo DBout('checked="checked"');?> id="is_required" /> Required</label>

															<label id="cross_img"><img src="images/cross.png" alt="Remove" title="Remove" onclick="removeField(this)"></label>

														</div>

													</div>

													<div class="col-md-12 fieldOptionContainer" id="padding_zero">

														<div class="col-md-4 addMoreOption" id="padding_left">

															<?php

															$options = DBout(explode('|',trim($filedOptions,'|')));

															for($j=0; $j<count($options); $j++){

																?>

																<p class="optionHolder">

																	<input type="text" value="<?php echo DBout($options[$j]); ?>" class="form-control fieldOptions" placeholder="Enter option label">

																</p>

																<?php			

															}

															?>

														</div>

														<div class="col-md-4">

															<a href="javascript:void(0)" onclick="addNewOption(this)" title="Add new dropdown option">Add Option</a>

														</div>

														<div class="col-md-4">&nbsp;</div>

													</div>

												</div>

												<?php

											}else if($fieldType=='radio'){

												?>

												<div class="fieldSection">

													<div class="col-md-12" id="padding_zero">

														<div class="col-md-4 padding_left1">

															<input type="text" id="field_label" value="<?php echo DBout($fieldLable)?>" class="form-control" placeholder="Enter field label">

														</div>



														<div class="col-md-4 padding_left1">

															<select id="field_type" class="form-control" onchange="checkFieldType(this)">

																<option value="text">Text Field</option>

																<option value="textarea">Text Box</option>

																<option value="dropdown">Drop down</option>

																<option value="radio" selected="selected">Radio box</option>

																<option value="checkbox">Check Box</option>

															</select>

														</div>



														<div class="col-md-4 padding_left1">

															<label><input type="checkbox" <?php if($isRequired!='')echo DBout('checked="checked"');?> id="is_required" /> Required</label>

															<label id="cross_img"><img src="images/cross.png" alt="Remove" title="Remove" onclick="removeField(this)"></label>

														</div>

													</div>

													<div class="col-md-12 fieldOptionContainer" id="padding_zero">

														<div class="col-md-4 addMoreOption" id="padding_left">

															<?php

															$options = DBout(explode('|',trim($filedOptions,'|')));

															for($j=0; $j<count($options); $j++){

																?>

																<p class="optionHolder">

																	<input type="text" value="<?php echo DBout($options[$j])?>" class="form-control fieldOptions" placeholder="Enter option label">

																</p>

																<?php			

															}

															?>

														</div>

														<div class="col-md-4">

															<a href="javascript:void(0)" id="addnew_option" onclick="addNewOption(this)" title="Add new dropdown option">Add Option</a>

														</div>

														<div class="col-md-4">&nbsp;</div>

													</div>

												</div>

												<?php

											}

										}

									}

									?>

								</div>

								<div class="form-group">

									<a href="javascript:void(0)" id="new_file" onclick="createCustomField()">Add new field</a>

								</div>

								<div class="form-group">

									<label>Disclaimer Text</label>

									<textarea name="disclaimer_text" class="form-control"><?php echo DBout($wb['disclaimer_text'])?></textarea>

								</div>

								<div class="form-group">

									<label class="radio-inline"><input type="radio" name="showing_method" class="showing_method" value="1" <?php if(($wb['showing_method']=='1') || ($wb['showing_method']=='0'))echo DBout('checked="checked"');?> />Show in PopUp</label>

									<label class="radio-inline"><input type="radio" name="showing_method" class="showing_method" value="2" <?php if($wb['showing_method']=='2')echo DBout('checked="checked"');?> />Show on Page</label>

								</div>

								<div class="form-group">

									<?php 

									if($wb['webform_type']=='1'){

										$responsive = DBout('checked="checked"');

										$fixed	= DBout('');

										$box = DBout('none');

									}

									else{

										$responsive = DBout('');

										$fixed	= DBout('checked="checked"');

										$box = DBout('block');

									}

									?>

									<label class="radio-inline"><input type="radio" name="webform_type" class="webform_type" value="1" <?php echo DBout($responsive)?> >Responsive Widget</label>

									<label class="radio-inline"><input type="radio" name="webform_type" class="webform_type" value="2" <?php echo DBout($fixed)?> >Fixed Width Widget</label>

								</div>

								<div class="customize_section" style="display:<?php echo DBout($box)?>">

									<div class="form-group">

										<label id="field_customize">Field Customization</label>

									</div>

									<div class="form-group">

										<label>Field Width</label>

										<input type="text" name="field_width" value="<?php echo DBout($wb['field_width'])?>" class="form-control numericOnly">

									</div>

									<div class="form-group">

										<label>Field Height</label>

										<input type="text" name="field_height" value="<?php echo DBout($wb['field_height'])?>" class="form-control numericOnly">

									</div>

									<div class="form-group" id="color_for_lable">

										<label>Color for Field Labels</label>

										<input type="text" name="color_for_label" value="<?php echo DBout($wb['color_for_label'])?>" class="color form-control">

									</div>

									<div class="form-group">

										<label id="field_customize">Widget Customization</label>

									</div>

									<div class="form-group">

										<label>Frame Width</label>

										<input type="text" name="frame_width" value="<?php echo DBout($wb['frame_width'])?>" class="form-control numericOnly">

									</div>

									<div class="form-group">

										<label>Frame Height</label>

										<input type="text" class="form-control" value="Height will auto according to width." readonly>

									</div>

									<div class="form-group" id="bkp_color">

										<label>Background Color</label>

										<input type="text" name="frame_bg_color" value="<?php echo DBout($wb['frame_bg_color'])?>" class="color">

									</div>

									<div class="form-group" id="bkp_color">

										<label>Subscribe Button Color</label>

										<input type="text" name="subs_btn_bg_color" class="color form-control" value="<?php echo DBout($wb['subs_btn_bg_color'])?>">

									</div>

									<div class="form-group" id="bkp_color">

										<label>Close Button Color</label>

										<input type="text" name="close_btn_bg_color" class="color form-control" value="<?php echo DBout($wb['close_btn_bg_color'])?>">

									</div>

								</div>



								<div class="form-group text-right m-b-0">

									<input type="hidden" name="id" id="id" value="<?php echo DBout($wb['id'])?>">

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


<script src="scripts/js/add_webfrom.js"></script>

