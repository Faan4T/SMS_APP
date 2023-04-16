"use strict";
$(document).on('ready',function(){
    $('form').parsley();
});
function hidePassword(){
    $('input[name="password"]').attr('type','password');
    $('input[name="retype_password"]').attr('type','password');
    $('.showPass').html('<i class="fa fa-eye" onclick="showPassword()"></i>');
}
function showPassword(){
    $('input[name="password"]').attr('type','text');
    $('input[name="retype_password"]').attr('type','text');
    $('.showPass').html('<i class="fa fa-eye-slash" onclick="hidePassword()"></i>');
}
