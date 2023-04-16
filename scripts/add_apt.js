"use strict";
$(document).on('ready',function() {
    if(request_id === '1'){
        addMoreAlertMsg();
        addMoreFollowUpMsg();
    }
});
function getGroupNumbers(groupID){
    $('#phone_number_id').html('<option value="">Loading...</option>');
    $.post('server.php',{groupID:groupID,"cmd":"get_group_subscribers"},function(r){
        $('#phone_number_id').html(r);
    });
}
function addMoreFollowUpMsg(){

    var html = $("#follow_up_structure").html();
    html = html.replace(/DatePickerToBe/g, "DatePickerToBe1");
    $('#followUpContainer').append('<div>'+html+'</div>');
    $('.showCounter').hide();

    $(".DatePickerToBe1").datepicker({
        inline: true,
        dateFormat: 'yy-mm-dd'
    });
    $(".DatePickerToBe1").addClass("addDatePicker");
    $(".DatePickerToBe1").removeClass("DatePickerToBe1");
}

function addMoreAlertMsg(){
    var html = $("#alert_msg_structure").html();
    html = html.replace(/DatePickerToBe/g, "DatePickerToBe1");
    $('#alertMSGContainer').append('<div>'+html+'</div>');
    $('.showCounter').hide();

    $(".DatePickerToBe1").datepicker({
        inline: true,
        dateFormat: 'yy-mm-dd'
    });
    $(".DatePickerToBe1").addClass("addDatePicker");
    $(".DatePickerToBe1").removeClass("DatePickerToBe1");
}

function removeFollowUp(obj){
    if(confirm("Are you sure you want to remove this message?")){
        obj.closest('.delay_table').remove('slow');
    }
}

