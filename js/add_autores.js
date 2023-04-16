"use strict";
$(document).on("ready",function(){
             if(camp){
            $("input,select,textarea,checkbox").prop("disabled",true);
            $("button[type='submit']").remove();
            $(".content form").prop("action","/");
           } 
        });