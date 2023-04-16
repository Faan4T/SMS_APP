"use strict";
$(document).on('ready',function(){
    sendSMS();
});
var sent=-30;
var req=30;
var QryStr='';
var total=100000000000;

function sendSMS(){
    var hidden_sms_id = $('#hidden_sms_id').val();
    var bulk_type = $('#bulk_type').val();
    var client_id = $('#client_id').val();
    var from_number = $('#from_number').val();
    var group_id = $('#group_id').val();
    var phone_number_id = $('#phone_number_id').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var daterange_group_id = $('#daterange_group_id').val();

    QryStr = 'smsid='+hidden_sms_id+'&bulk_type='+bulk_type+'&client_id='+client_id+'&from_number='+from_number+'&group_id='+group_id+'&phone_number_id='+phone_number_id+'&start_date='+start_date+'&end_date='+end_date+'&daterange_group_id='+daterange_group_id;

    sendSMS1();
    if(total>sent)
        sendSMS2();
    if(total>sent)
        sendSMS3();
    if(total>sent)
        sendSMS4();
    if(total>sent)
        sendSMS5();
    if(total>sent)
        sendSMS6();
}

var ajax_res_check={};

function sendSMS1()
{
    ajax_res_check['sms1']="start";
    sent+=req;
    var qr=QryStr+"&start="+(sent-req);
    $.post('send_bulk_sms.php',qr, function(res){
        ajax_res_check['sms1']="completed";
        list_response(res);
        if(total>sent)
            sendSMS1();
    });
}
function sendSMS2()
{
    ajax_res_check['sms2']="start";     sent+=req;
    var qr=QryStr+"&start="+(sent-req);
    $.post('send_bulk_sms.php',qr, function(res){
        ajax_res_check['sms2']="completed";
        list_response(res);
        if(total>sent)
            sendSMS2();
    });

}
function sendSMS3()
{
    ajax_res_check['sms3']="start";
    sent+=req;
    var qr=QryStr+"&start="+(sent-req);
    $.post('send_bulk_sms.php',qr, function(res){
        ajax_res_check['sms3']="completed";
        list_response(res);
        if(total>sent)
            sendSMS3();
    });

}
function sendSMS4()
{
    ajax_res_check['sms4']="start";
    sent+=req;
    var qr=QryStr+"&start="+(sent-req);
    $.post('send_bulk_sms.php',qr ,function(res){
        ajax_res_check['sms4']="completed";
        list_response(res);
        if(total>sent)
            sendSMS4();
    });

}function sendSMS5()
{
    ajax_res_check['sms5']="start";
    sent+=req;
    var qr=QryStr+"&start="+(sent-req);
    $.post('send_bulk_sms.php',qr ,function(res){
        ajax_res_check['sms5']="completed";
        list_response(res);
        if(total>sent)
            sendSMS5();
    });

}function sendSMS6()
{
    ajax_res_check['sms6']="start";
    sent+=req;
    var qr=QryStr+"&start="+(sent-req);
    $.post('send_bulk_sms.php',qr ,function(res){
        ajax_res_check['sms6']="completed";
        list_response(res);
        if(total>sent)
            sendSMS6();
    });

}

function show_completed(){

    if((ajax_res_check['sms1']=="completed")&&(ajax_res_check['sms2']=="completed")&&(ajax_res_check['sms3']=="completed")&&(ajax_res_check['sms4']=="completed")&&(ajax_res_check['sms5']=="completed")&&(ajax_res_check['sms6']=="completed"))
    {
        $("#json_msg_response").html("Message Sending Completed");
        $('#warning_msg').hide();
    }

}

function list_response(res){
    var result=$.parseJSON(res);
    show_completed();
    if(result.error == "yes" || sent === total)
    {
        total=0;
    }
    var res2='';
    if(result.result.length>0)
    {  $.each(result.result,function(k,v){
        if(v['response'] == 'sent')
            res2+='Message Sent to '+v['Phone']+"<br/>";
        else
            res2+='Message status pending '+v['Phone']+"<br/>";
    } );
    }
    document.getElementById('response_wait_image').innerHTML +=res2;

}
