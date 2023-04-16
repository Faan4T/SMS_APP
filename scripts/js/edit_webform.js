"use strict";
function saveWebForm(){
		var customFields = prepareCustomFields();
		var webform_name = $('input[name="webform_name"]').val();
		var campaign_id = $('select[name="campaign_id"]').val();
		var label_for_name_field = $('input[name="label_for_name_field"]').val();
		var label_for_phone_field = $('input[name="label_for_phone_field"]').val();
		var label_for_email_field = $('input[name="label_for_email_field"]').val();
		var disclaimer_text = $('textarea[name="disclaimer_text"]').val();
		var label_for_disclaimer_text = $('input[name="label_for_disclaimer_text"]').val();
		var heading_for_custom_info_panel = $('input[name="heading_for_custom_info_panel"]').val();
		var wbID = $('#id').val();
		if($(".showing_method:checked").val()=='1'){
			var showing_method = '1';
		}else{
			var showing_method = '2';
		}
		if($(".webform_type:checked").val()=='2'){
			var webform_type = '2';
			var field_width = $('input[name="field_width"]').val();
			var field_height = $('input[name="field_height"]').val();
			var color_for_label = $('input[name="color_for_label"]').val();
			var frame_width = $('input[name="frame_width"]').val();
			var frame_bg_color = $('input[name="frame_bg_color"]').val();
			var subs_btn_bg_color = $('input[name="subs_btn_bg_color"]').val();
			var close_btn_bg_color = $('input[name="close_btn_bg_color"]').val();
		}else{
			var webform_type = '1';	
		}
		$("#webFormSaveButton").prop("disabled",true);
		$.post("server.php",{"cmd":"update_webform","customFields":customFields,webform_name:webform_name,campaign_id:campaign_id,label_for_name_field:label_for_name_field,label_for_phone_field:label_for_phone_field,label_for_email_field:label_for_email_field,disclaimer_text:disclaimer_text,webform_type:webform_type,field_width:field_width,field_height:field_height,color_for_label:color_for_label,frame_width:frame_width,frame_bg_color:frame_bg_color,subs_btn_bg_color:subs_btn_bg_color,close_btn_bg_color:close_btn_bg_color,label_for_disclaimer_text:label_for_disclaimer_text,heading_for_custom_info_panel:heading_for_custom_info_panel,wbID:wbID,showing_method:showing_method},function(r){
		    $("#webFormSaveButton").prop("disabled",false);
			window.location = 'view_webform.php';
		});
	}
	function prepareCustomFields(){
		var obj = [];
		$('.fieldSection').each(function(index){
			var fieldLabel = $(this).find('#field_label').val();
			var fieldType  = $(this).find('#field_type').val();
			var isRequired = $(this).find('#is_required').prop('checked');
			obj[index] = {
				'field_label' : fieldLabel,
				'field_type'  : fieldType,
				'is_required' : isRequired
			};
			if($(this).find('.fieldOptions').length){
				var fieldOptions = '';
				$(this).find('.fieldOptions').each(function(i){
					fieldOptions += $(this).val()+'|';
				});
				obj[index]['filed_options'] = fieldOptions;
			}else{
				obj[index]['filed_options'] = '';
			}
		});
		return obj;
	}
	function addNewOption(obj){
		var html = '<p class="optionHolder"><input type="text" class="form-control fieldOptions" id="optionHolder" placeholder="Enter option label"><img src="images/minus.png" id="minus" title="Delete option" alt="Delete option" onclick="deleteFieldOption(this)"></p>';
		$(obj).closest('.fieldSection').find('.fieldOptionContainer > .addMoreOption').append(html);
	}
	function checkFieldType(obj){
		var elemType = $(obj).val();
		if(elemType=='text'){
			$(obj).closest('.fieldSection').find('.fieldOptionContainer').html('');
		}else if(elemType=='textarea'){
			$(obj).closest('.fieldSection').find('.fieldOptionContainer').html('');
		}else if(elemType=='dropdown'){
			var	html = '<div class="col-md-4 addMoreOption" id="padding_left"><p class="optionHolder"><input type="text" class="form-control fieldOptions" placeholder="Enter option label"></p><p class="optionHolder"><input type="text" class="form-control fieldOptions" placeholder="Enter option label"></p></div>';
				html +=	'<div class="col-md-4"><a href="javascript:void(0)" id="addnew_option" onclick="addNewOption(this)" title="Add new '+elemType+' option">Add Option</a></div>';
				html +=	'<div class="col-md-4">&nbsp;</div>';
			$(obj).closest('.fieldSection').find('.fieldOptionContainer').html(html);
		}else if(elemType=='radio'){
			var	html = '<div class="col-md-4 addMoreOption" id="padding_left"><p class="optionHolder"><input type="text" class="form-control fieldOptions" placeholder="Enter option label"></p><p class="optionHolder"><input type="text" class="form-control fieldOptions" placeholder="Enter option label"></p></div>';
				html +=	'<div class="col-md-4"><a href="javascript:void(0)" id="addnew_option" onclick="addNewOption(this)" title="Add new '+elemType+' option">Add Option</a></div>';
				html +=	'<div class="col-md-4">&nbsp;</div>';
			$(obj).closest('.fieldSection').find('.fieldOptionContainer').html(html);
		}else if(elemType=='checkbox'){
			var	html = '<div class="col-md-4 addMoreOption" id="padding_left"><p class="optionHolder"><input type="text" class="form-control fieldOptions" placeholder="Enter option label"></p><p class="optionHolder"><input type="text" class="form-control fieldOptions" placeholder="Enter option label"></p></div>';
				html +=	'<div class="col-md-4"><a href="javascript:void(0)" id="addnew_option" onclick="addNewOption(this)" title="Add new '+elemType+' option">Add Option</a></div>';
				html +=	'<div class="col-md-4">&nbsp;</div>';
			$(obj).closest('.fieldSection').find('.fieldOptionContainer').html(html);
		}
	}
	function createCustomField(){
		var html  = '<div class="fieldSection">';
				html  += '<div class="col-md-12 padding_left">';
					html += '<div class="col-md-4" padding_left1>';
					html += '<input type="text" id="field_label" class="form-control" placeholder="Enter field label">';
					html += '</div>';
					
					html += '<div class="col-md-4 padding_left1" >';
					html += '<select id="field_type" class="form-control" onchange="checkFieldType(this)"><option value="text">Text Field</option><option value="textarea">Text Box</option><option value="dropdown">Drop down</option><option value="radio">Radio box</option><option value="checkbox">Check Box</option></select>';
					html += '</div>';
					
					html += '<div class="col-md-4 padding_left1">';
					html += '<label><input type="checkbox" id="is_required" /> Required</label>';
					html += '<label id="cross_img"><img src="images/cross.png" alt="Remove" title="Remove" onclick="removeField(this)"></label>';
					html += '</div>';
				html += '</div>';
				html  += '<div class="col-md-12 fieldOptionContainer" id="padding_left"></div>';
			html += '</div>';
		$('#fieldSectionContainer').append(html);
	}
	function deleteFieldOption(obj){
		if(confirm("Are you sure you want to delete this option?")){
			$(obj).closest('.optionHolder').remove();
		}
	}
	function removeField(obj){
		if(confirm("Are you sure you want to delete this field?")){
			$(obj).closest('.fieldSection').remove();
		}
	}
	$(document).on("ready",function(){
		$(".color").pickAColor({
		showSpectrum            : true,
		showSavedColors         : true,
		saveColorsPerElement    : true,
		fadeMenuToggle          : true,
		showAdvanced			: true,
		showBasicColors         : true,
		showHexInput            : true,
		allowBlank				: true,
		inlineDropdown			: true
		});
	});

	$('.webform_type').on('click',function(r){
		if($(this).val()=='1'){
			$('.customize_section').slideUp('slow');
		}
		else{
			$('.customize_section').slideDown('slow');	
		}
	});