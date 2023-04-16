"use strict";
function getUserCustomInfo(){
	var $ = jQuery;
    var obj = [];
	var fieldOptions = '';
	$('.customSubsInfo > .form-group').each(function(index){
		var fieldLabel = $(this).find('#fieldTitle').val();
		var fieldType  = $(this).find('#fieldType').val();
		if(fieldType == 'text'){
			var fieldValue = $(this).find('input[type="text"]').val();
			obj[index] = {
				'field_label' : fieldLabel,
				'field_type'  : fieldType,
				'field_value' : fieldValue
			};
		}else if(fieldType == 'textarea'){
			var fieldValue = $(this).find('textarea').val();
			obj[index] = {
				'field_label' : fieldLabel,
				'field_type'  : fieldType,
				'field_value' : fieldValue
			};
		}else if(fieldType=='dropdown'){
			var selectedOption = $(this).find('select option:selected').text();
			obj[index] = {
				'field_label' : fieldLabel,
				'field_type'  : fieldType,
				'field_value' : selectedOption
			};
		}else if(fieldType=='radio'){
			var selectedOption = $(this).find('input[type=radio]:checked').val();
			obj[index] = {
				'field_label' : fieldLabel,
				'field_type'  : fieldType,
				'field_value' : selectedOption
			};
		}else if(fieldType=='checkbox'){
			$(this).find('input[type=checkbox]:checked').each(function(i){
				fieldOptions += $(this).val()+',';
			});
			obj[index] = {
				'field_label' : fieldLabel,
				'field_type'  : fieldType,
				'field_value' : fieldOptions
			};
		}else{
			obj[index]['field_value'] = '';
		}
		fieldOptions = '';
	});
	var jsonString = JSON.stringify(obj);
	return jsonString;
}
function saveWebFormUser(webFormID,campaignID,userID){
    var $ = jQuery;
	var name  = $('input[name="subs_name"]').val();
	var phone = $('input[name="subs_phone"]').val();
	var email = $('input[name="subs_email"]').val();
	if(($.trim(name)=="") || ($.trim(phone)=="") || ($.trim(email)=="")){
		alert('All fields are required.');
		return false;
	}
	if(!isValidEmailAddress(email)){
		alert('Enter valid email address.');
		return false;
	}
	var customInfo = getUserCustomInfo();
	$('.nmLoading').show();
	$('.nmStatus').html('');
	var Qry = 'cmd=save_webform_subscriber&name='+name+'&phone='+encodeURIComponent(phone)+'&email='+email+'&campaign_id='+campaignID+'&user_id='+userID+'&customSubsInfo='+customInfo;
	$.post(get_url,Qry,function(r){
		$('.nmLoading').hide();
		if(r=='success'){
			$('.nmStatus').html('<span id="color_green">Success.</span>');
			window.setTimeout(hideAll,2000);
		}
		else if(r=='exists'){
			$('.nmStatus').html('<span id="color_red">Phone number is already subscribed.</span>');
			window.setTimeout(hideAll,2000);
		}
		else{
			$('.nmStatus').html('<span id="color_red">Unknown Error! Attempt failed.</span>');
			window.setTimeout(hideAll,2000);
		}
	});
}
function isValidEmailAddress(emailAddress) {
    var $ = jQuery;
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
};
function hideAll(){
    var $ = jQuery;
	$('.nmLoading').hide();
	$(".nmBackground").remove();
	$("#nmModalData").remove();		
}
jQuery(".close, .closePop").on('click',function(r){
	jQuery(".nmBackground").remove();
	jQuery("#nmModalData").remove();
});