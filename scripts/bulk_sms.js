"use strict";
$(document).on('ready',function(){
    $('form').parsley();
    $('.bulk_type').on('click',function(r){
        if($(this).val()=='1'){
            $('.daterange').hide('slow');
            $('.single_group').show('slow');
            $("#daterange_group_id").removeAttr('required')
            $(".addDatePicker ").removeAttr('required');
            $("#group_list").attr('required','required');
            $("#phoneid").attr('required','required');
        }
        else if($(this).val()=='2'){
            $('.single_group').hide('slow');
            $('.daterange').show('slow');
            $("#daterange_group_id").attr('required','required')
            $(".addDatePicker ").attr('required','required');
            $("#group_list").removeAttr('required');
            $("#phoneid").removeAttr('required');
        }
    });
});
$('#custom-modal').on('shown.bs.modal', function() {
    $( ".addDatePicker" ).datepicker({
        inline: true,
        dateFormat: 'yy-mm-dd'
    });
});

$('#bulkSMSTable').cardtable();
function sendBulkSMS(smsID){
    $('#showresponse').html('');
    var sendType = $('input[name=bulk_type]:checked').val();
    var fromNumber = $('select[name=from_number] option:selected').val();
    var smsID	 = $('#hidden_sms_id').val();
    if(fromNumber==''){
        alert('Select a from number.');
        return false;
    }
    if(sendType=='1'){
        var groupID  = $('select[name="group_id"] option:selected').val();
        var numberID = $('select[name="phone_number_id"] option:selected').val();
        if(($.trim(groupID)=='') || ($.trim(numberID)=='')){
            alert('All fields are required.');
            return false;
        }
        var Qry = 'sendType='+sendType+'&smsID='+smsID+'&groupID='+groupID+'&numberID='+numberID+'&cmd=send_bulk_sms&fromNumber='+encodeURIComponent(fromNumber);
    }
    else{
        var startDate= $('#start_date').val();
        var endDate  = $('#end_date').val();
        var numberID = $('select[name="daterange_group_id"] option:selected').val();
        if(($.trim(startDate)=='') || ($.trim(endDate)=='') || ($.trim(numberID)=='')){
            alert('All fields are required.');
            return false;
        }
        var Qry = 'sendType='+sendType+'&smsID='+smsID+'&startDate='+startDate+'&endDate='+endDate+'&numberID='+numberID+'&cmd=send_bulk_sms&fromNumber='+encodeURIComponent(fromNumber);
    }
    $('#loading').show();
    $.post('server.php',Qry,function(r){
        $('#loading').hide();
        $('#showresponse').html(r);
    });
}
function getSMSID(smsID){
    $('#hidden_sms_id').val(smsID);
}
function getGroupNumbers(groupID){
    if(groupID=='all'){
        $('.phoneListRow').hide('slow');
        $('#phoneid').removeAttr('required')
        return false;
    }
    $('#loading').show();
    var client_id = $("#client_id").val();
    var Qry = 'cmd=get_group_numbers&group_id='+groupID+"&client_id="+client_id;
    $.post('server.php',Qry,function(r){
        $('#loading').hide();
        $('#phoneid').html(r);
        $('.phoneListRow').show('slow');
    });
}
function getAccountGroups(user_id){
    $('#loading').show();
    var Qry = 'cmd=get_groups&user_id='+user_id;
    $.post('server.php',Qry,function(r){
        $('#loading').hide();
        $('#group_list').html(r);
    });

    var Qry = 'cmd=get_twilio_numbers&user_id='+user_id;
    $.post('server.php',Qry,function(r){
        $('#loading').hide();
        $('#twilio_numbers').html(r);
    });

    $('#phoneid').html('<option value="">Select One</option>');
}


function deleteBulkSMS(smsID){
    if(confirm("Are you sure you want to delete this message?")){
        window.location = 'server.php?cmd=delete_bulk_sms&id='+smsID;
    }
}