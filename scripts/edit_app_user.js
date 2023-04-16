"use strict";
$(document).on('ready',function(){
    $('form').parsley();
    $('.datepicker').datepicker({
        format: 'mm/dd/yyyy',
        startDate: '-0d'
    })
});