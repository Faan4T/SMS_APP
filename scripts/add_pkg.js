"use strict";
$(document).on('ready',function(){
    $('form').parsley();
});
function getGatewayCountries(gateway){
    return false;
    $('#country').html('');
    $.post('server.php',{"cmd":"get_gateway_countries",gateway:gateway},function(r){
        $('#country').html(r);
    });
}
function showFreeDays(obj){
    if($(obj).is(":checked")==true){
        $('#free_days').show('slow');
    }else{
        $('#free_days').hide('slow');
    }
}
function getPkgCountry(){
    var country = $('#country option:selected').text();
    $('#pkg_country').val(country);
}
