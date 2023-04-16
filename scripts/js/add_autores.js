"use strict";
$(document).on("ready",function(){
             if(isset($_GET['camp'])){ 
            $("input,select,textarea,checkbox").prop("disabled",true);
            $("button[type='submit']").remove();
            $(".content form").prop("action","/");
           } 
        });