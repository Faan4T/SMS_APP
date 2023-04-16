"use strict";
$(document).on('ready',function(){
    $('form').parsley();
});
function getSchedulerNumber(groupID,numberID){
    var Qry = 'cmd=get_scheduler_numbers&group_id='+groupID+'&numberID='+numberID;
    $.post('server.php',Qry,function(r){
        $('#list_group_number').html(r);
    });
}
function getGroupNumbers(groupID){
    var Qry = 'cmd=get_group_numbers&group_id='+groupID;
    $.post('server.php',Qry,function(r){
        $('#list_group_number').html(r);
    });
}
