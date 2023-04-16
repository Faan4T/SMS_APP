"use strict";
jQuery('#datepicker-autoclose').datepicker({
    autoclose: true,
    todayHighlight: true
});
$(document).on('ready',function(){
    $('input[name=send_immediate]').on('change',function(){
        if($(this).is(":checked")==true){
            $('.dateTimeSection').hide('slow');
        }else{
            $('.dateTimeSection').show('slow');
        }
    });
    $('form').parsley();
});
function getGroupNumbers(groupID,numberID){
    var Qry = 'cmd=get_group_numbers&group_id='+groupID+'&numberID='+numberID;
    $.post('server.php',Qry,function(r){
        $('#list_group_number').html(r);
    });
}