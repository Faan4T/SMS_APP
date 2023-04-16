"use strict";

$(document).on('ready',function(){

    updateScroll();

});

function updateScroll(){

    var element = document.getElementById("chat_container");

    element.scrollTop = element.scrollHeight;

}

setInterval(function loadChat(){

    $.post('server.php',{"cmd":"load_chat",phoneID:phone_id},function(r){

        $('.chat').html(r);

        updateScroll();

    });

}, 10000);

function checkKey(e){

    if(window.event){e = window.event;}

    if(e.keyCode == 13){

        sendChatMessage();

    }

}

function sendChatMessage(){

	// var deviceID = document.getElementById('deviceList').value;

    var deviceID = 0;

    var chatMessage = document.getElementById('chat_message').value;

    if (chatMessage == ''){
        return false;
    }

    var appendElement = '<li class="right clearfix"><span class="chat-img pull-right">';

    appendElement += '<img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" /></span>';

    appendElement += '<div class="chat-body clearfix"><div class="header chat_header"><small class=" text-muted"><span class="fa fa-clock-o"></span>Just now</small><strong class="pull-right primary-font">'+first_name+'</strong></div><p>'+chatMessage+'</p></div></li>';

    $('.chat').append(appendElement);

    document.getElementById('chat_message').value='';

    updateScroll();

    $.post('server.php',{chatMessage:encodeURIComponent(chatMessage),"cmd":"save_chat_message","phone_id":phone_id,"To":to_number,"From":$('#from_number option:selected').val(),deviceID:deviceID},function(r){

        if(r!='1'){

        }

    });

}