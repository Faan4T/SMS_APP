"use strict";
$('#smsReportTable').cardtable();
function getMessageDetails(msgID){
    $('#loading').show();
    $.post('server.php',{"cmd":"get_message_details","msg_id":msgID},function(r){
        $('#loading').hide();
        $('.loadMsgDetails').html(r);
    });
}