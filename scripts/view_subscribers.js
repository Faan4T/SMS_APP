"use strict";
$(document).on('ready',function(){
    $(".numbers-checkbox").change(function(){
        showHideActions();
    })
})
function checkAll(obj){
    if($(obj).is(":checked")){
        $(".numbers-checkbox").prop("checked",true);
    }else{
        $(".numbers-checkbox").prop("checked",false);
    }
    showHideActions();
}

function showHideActions(){
    var selected_numbers = $(".numbers-checkbox").filter(':checked').length;
    if(selected_numbers>0){
        $(".numberActions").fadeIn();
    }else{
        $(".numberActions").fadeOut();
    }
}

function getSubsCustomInfo(subsID){
    $('.loadCustomInfo').html('Loading...');
    $.post('server.php',{"cmd":"load_subs_custom_info","subs_id":subsID},function(r){
        $('.loadCustomInfo').html(r);
    });
}
$('#subscribersTable').cardtable();
function showSubscriberDetails(obj,subsID){
    if($(obj).attr('class')=='fa fa-arrow-down'){
        $(obj).attr('class','fa fa-arrow-up')
    }else{
        $(obj).attr('class','fa fa-arrow-down')
    }
    $('.showSubsDetials_'+subsID).slideToggle();
}
function OnKeyPress(e){
    if(window.event){ e = window.event; }
    if(e.keyCode == 13){
        var searchkeyword = document.getElementById('searchkeyword').value;
        window.location = 'view_subscribers.php?searchkeyword='+encodeURIComponent(searchkeyword);
    }
}
function deleteSubscriber(id){
    if(confirm("Are you sure you want to delete this subscriber?")){
        window.location = 'server.php?cmd=delete_subscriber&id='+id;
    }
}
function scheduleSMS(){
    var search = $("#search").val();
    var group_id = $("#group_id").val();
    if($(".all_numbers_chk").is(":checked")){
        var all_numbers=1;
    }else{
        var all_numbers=0;
    }

    var checked_numbers = $('[class="numbers-checkbox"]:checked').map(function() { return $(this).val().toString(); } ).get().join(",");
    window.location = "scheduler.php?search="+search+"&group_id="+group_id+"&all_numbers="+all_numbers+"&checked_numbers="+checked_numbers+"&custom=1";
}

function deleteNumbers(){
    var cnfrm = confirm("Are you sure to delete selected numbers?");
    if(cnfrm){
        var checked_numbers = $('[class="numbers-checkbox"]:checked').map(function() { return $(this).val().toString(); } ).get().join(",");
        window.location = "server.php?cmd=delete_checked_numbers&checked_numbers="+checked_numbers;
    }
}