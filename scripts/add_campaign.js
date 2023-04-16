"use strict";
$('input[name="attach_mobile_device"]').on("change",function(){
	if($(this).is(":checked")==true){
		$(".phoneNumberSection").slideUp('slow');
		$(".mobileDeviceSection").slideDown('slow');
	}else{
		$(".mobileDeviceSection").slideUp('slow');
		$(".phoneNumberSection").slideDown('slow');
	}
});
function switchTimeDropDown(obj){
    if($(obj).val()=='0'){
        $(obj).parent().find('.timeDropDown').css('display','none');
        $(obj).parent().find('.hoursDropDown').css('display','inline');
    }else{
        $(obj).parent().find('.timeDropDown').css('display','inline');
        $(obj).parent().find('.hoursDropDown').css('display','none');
    }
}
function slideToggleInnerSection(obj,eleMent){
    if($(obj).is(":checked")==true){
        $('.'+eleMent+'').show('slow');
    }else{
        $('.'+eleMent+'').hide('slow');
    }
}
function slideToggleMainSection(obj,section,chkBox){
    var html = $(obj).html();
    var check = html.indexOf("fa-plus");
    if(check=="-1"){
        $(obj).html('<i class="fa fa-plus white move-right" title="Close"></i>');
        $('.'+section).hide('slow');

    }else{
        $(obj).html('<i class="fa fa-minus white move-right" title="Open"></i>');
        $('.'+section).show('slow');

    }
}

function slideToggleBeaconSection(obj,eleMent){
    if(eleMent=='campaignBeaconCouponSection'){
        $('.campaignBeaconCouponSection').show('slow');
        $('.campaignBeaconURLSection').hide('slow');
    }else{
        $('.campaignBeaconCouponSection').hide('slow');
        $('.campaignBeaconURLSection').show('slow');
    }
}

function removeFollowUp(obj){
    if(confirm("Are you sure you want to remove this follow up?")){
        obj.closest('.delay_table').remove('slow');
    }
}
function followUpHtml(){
    var timeOption = timeoptions;
    var html = '<table width="100%" class="delay_table">';
    html += '<tr><td colspan="2"><hr class="hr_style"></td></tr>';
    html += '<tr><td width="25%">Select Days/Time</td><td><input type="text" class="form-control numericOnly delay-days" placeholder="Days delay..." name="delay_day[]" value="0" onblur="switchTimeDropDown(this)">&nbsp;<select class="form-control timeDropDown delay-time" name="delay_time[]">'+timeOption+'</select><select class="form-control hoursDropDown delay-hours" name="delay_time_hours[]">'+options+'</select></td></tr>';
    html += '<tr><td>Message</td><td><textarea name="delay_message[]" class="form-control textCounter"></textarea><span class="showCounter"><span class="showCount">'+maxlenght+'</span> Characters left</span></td></tr>';
    html += '<tr><td>Attach Media</td><td><input type="file" name="delay_media[]" class="display-inline"><span class="fa fa-trash trash-style" title="Remove Message" onclick="removeFollowUp(this)"></span></td></tr></table>';
    return html;
}
function addMoreFollowUpMsg(){
    var html = followUpHtml();
    $('#followUpContainer').append('<div>'+html+'</div>');
    $('.showCounter').hide();
}