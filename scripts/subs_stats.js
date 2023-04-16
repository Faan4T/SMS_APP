"use strict";
function getSubsCustomInfo(subsID){
    $('.loadCustomInfo').html('Loading...');
    $.post('server.php',{"cmd":"load_subs_custom_info","subs_id":subsID},function(r){
        $('.loadCustomInfo').html(r);
    });
}
$('#subscribersTable').cardtable();
function OnKeyPress(e){
    if(window.event){ e = window.event; }
    if(e.keyCode == 13){
        var searchkeyword = document.getElementById('searchkeyword').value;
        window.location = 'subscribers_stats.php?group_id='+group_id+'&searchType=subscribers&searchkeyword='+encodeURIComponent(searchkeyword);

    }
}
function deleteSubscriber(id){
    if(confirm("Are you sure you want to delete this subscriber?")){
        window.location = 'server.php?cmd=delete_subscriber&id='+id;
    }
}