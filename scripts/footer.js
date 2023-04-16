"use strict";
$(document).on('ready',function(){
if(noti != ''){

    $.notify({
        icon: 'pe-7s-attention',

        message: noti

    },{

        type: notiType,

        timer: 1000

    });

}


    //$('.showCounter').hide();



    $('body').on('keyup','.decimalOnly',function(){

        var val = $(this).val();

        var filterdVal = val.replace(/[^.\d]/g,'');

        $(this).val(filterdVal);

    });

    $('body').on('keyup','.phoneOnly',function(){

        var val = $(this).val();

        var filterdVal = val.replace(/[^+\d]/g,'');

        $(this).val(filterdVal);

    });


	

	$('body').on('keyup','.removeSpaces',function(){
		$(this).val($(this).val().replace(/^\s+|\s+$/g,""));
    });
	
    $('body').on('keyup','.numericOnly',function(){

        var val = $(this).val();

        var filterdVal = val.replace(/[^\d]/g,'');

        $(this).val(filterdVal);

    });



});


function verifyEnvatoPurchaseCode(){

    var purchaseCode = $('input[name="product_purchase_code"]').val();

    if($.trim(purchaseCode)!=''){

        $('#verify').html('Verifying...');

        $('#verify').show();

        $.post('http://apps.ranksol.com/nm_license/check_code.php',{purchaseCode:purchaseCode,"server_url":server_url()},function(r){

            var res = $.parseJSON(r);

            if(res.error=='no'){

                $('#verify').html(res.message);

                var status = 'verified';

            }else{

                $('#verify').html(res.message);

                var status = 'invalid';

            }

            $.post('server.php',{"cmd":"update_purchase_code","status":status,purchaseCode:purchaseCode,user_id:user_id},function(rr){

                window.location = 'dashboard.php';

            });

        });

    }else{

        alert('Enter purchase code.');

    }

}

$( ".addDatePicker" ).datepicker({

    inline: true,

    dateFormat: 'yy-mm-dd'

});