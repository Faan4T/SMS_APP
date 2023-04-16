"use strict";
$(document).on('ready',function(){
    $('#cron_stop_time_from').timepicki();
    $('#cron_stop_time_to').timepicki();
});
$('#plivoTable').cardtable();

function getSignalWireNumbersAreaCode(){
	$("#loading").show();
	$("#showNumbers").html('');
	var areaCode = $("#selected_areacode").val();
	var Qry = 'areacode='+areaCode+'&country=US&cmd=get_signalewire_numbers_areacode';
	$.post("server.php",Qry,function(res){
		$("#showNumbers").html(res);
		$("#showNumbers").show();
		$("#loading").hide();
	});
}
function getSignalWireNumbers(){
	$("#loading").show();
	$("#showNumbers").html('');
	var areaCode = $("#areacode").val();
	var Qry = 'areacode='+areaCode+'&country=US&cmd=get_signalewire_numbers_areacode';
	$.post("server.php",Qry,function(res){
		$("#showNumbers").html(res);
		$("#showNumbers").show();
		$("#loading").hide();
	});
}	
	
$('#plivoTable').cardtable();
function addNexmoToInstall(phoneNumber){
	$("#loading").show();
	if(confirm("Are you sure you want to assign this number to application?")){
		$.post("server.php",{"cmd":"add_nexmo_to_install","phone_number":phoneNumber},function(r){
			$("#loading").hide();
			if(r!='1'){
				alert(r);	
			}
			window.location='settings.php';
		});
	}
}
function removeNexmoFromInstall(phoneNumber){
	$("#loading").show();
	if(confirm("Are you sure you want to remove this number from application?")){
		if(confirm("All campaigns configured with this number will be soppped working.")){
			$.post("server.php",{"cmd":"remove_nexmo_from_install","phone_number":phoneNumber},function(r){
				$("#loading").hide();
				if(r!='1'){
					alert(r);	
				}
				window.location='settings.php';
			});
		}
	}
}
function buyNexmoNumber(){
	var phoneNumber = $('input[class="nexmo_buy_number"]:checked').val();
	var isoCountry	= $('select[name="nexmo_country"] option:selected').val();
	if(phoneNumber=="undefined"){
		alert("Select at least one number.");
		return false;
	}else{
		if(confirm("Are you sure you want to buy this number?")){
			$("#loading").show();
			var Qry = 'cmd=buy_nexmo_number&phoneNumber='+encodeURIComponent(phoneNumber)+'&isoCountry='+isoCountry;
			$.post('server.php',Qry,function(r){
				window.location = 'settings.php';
			});
		}
	}
}
function removePlivoFromInstall(phoneNumber){

	if(confirm("Are you sure you want to remove number from this install?")){

		$("#loading").show();

		var Qry = 'cmd=remove_plivo_from_install&phoneNumber='+encodeURIComponent(phoneNumber);

		$.post("server.php",Qry,function(res){

			if(res=="1"){

				$("#loading").html('<span id="green-color">Number released from your install.</span>');	

				window.location = 'settings.php';

			}else{

				$("#loading").html('<span id="red-color" ">'+res+'</span>');

			}

		});

	}}

function addPlivoToInstall(phoneNumber){

	if(confirm("Are you sure you want to assign number to this install?")){

		$("#loading").show();

		var Qry = 'cmd=add_plivo_number_to_install&phoneNumber='+encodeURIComponent(phoneNumber);

		$.post("server.php",Qry,function(res){
			if(res=="1"){
				$("#loading").html('<span id="green-color">Number assigned to your install.</span>');	
				window.location = 'settings.php';
			}else{
				$("#loading").html('<span id="red-color">'+res+'</span>');
			}

		});

	}}

function buyPlivoNumber(){

	var phoneNumber = $('input[class="plivo_buy_number"]:checked').val();

	if((phoneNumber=="") || (phoneNumber=="undefined")){

		alert("Select at least one number.");

	}else{

		if(confirm("Are you sure you want to buy this number?")){

			$("#loading").show();

			var Qry = 'cmd=buy_plivo_number&phoneNumber='+encodeURIComponent(phoneNumber);

			$.post('server.php',Qry,function(r){

				window.location = 'settings.php';

			});

		}

	}}

function searchPlivoNumbers(){
	$("#loading").show();
	$("#showNumbers").html('');
	var state = $('select[name="state"]').val();
	var pattern = $('input[name="pattern"]').val();
	var Qry = 'cmd=search_plivo_numbers&state='+state+'&pattern='+pattern;
	$.post("server.php",Qry,function(res){
		$("#showNumbers").html(res);
		$("#showNumbers").show();
		$("#loading").hide();
	});
}

function showPlivoSections(obj){
	var numType = $(obj).val();
	if(numType=='1'){
		$("#buy_credits_section").hide('slow');
		
		$("#purchase_number").show('slow');
		$('#search_pattren').show('slow');
		$("#showNumbers").show('slow');
		$("#existing_number").hide('slow');
	}else if(numType=='2'){
		$("#buy_credits_section").show('slow');
		$('#search_pattren').hide('slow');
		$("#purchase_number").hide('slow');
		
		$("#showNumbers").hide('slow');
		$("#existing_number").hide('slow');
		$("#loading").hide();
	}else{
		$("#loading").show();
		if(user_type=='1'){
		var Qry = 'cmd=get_plivo_existing_numbers';
		
		var Qry = 'cmd=get_plivo_existing_numbers_for_subaccount&user_id=sessions';

		$.post("server.php",Qry,function(res){
			$("#buy_credits_section").hide('slow');
			$('#search_pattren').hide('slow');
			$("#purchase_number").hide('slow');
			$("#existing_number").html(res);
			$("#showNumbers").hide('slow');
			$("#existing_number").show('slow');
			$("#loading").hide();
		});
	}
 }

}

$('.smsGateWay').on('change',function(r){
	$('.nexmoInfo').hide('slow');
	$('.plivoInfo').hide('slow');
	$('.twilioInfo').hide('slow');
	$('.mobileSimSection').hide('slow');
	
	if($(this).val()=='twilio'){
		$('.twilioInfo').show('slow');
	}else if($(this).val()=='plivo'){
		$('.plivoInfo').show('slow');
	}else if($(this).val()=='nexmo'){
		$('.nexmoInfo').show('slow');
	}else if($(this).val()=='mobile_sim'){
		$('.mobileSimSection').show('slow');
	}
});



	window.onload = function(){
		if(user_type=='1'){
		CKEDITOR.config.autoParagraph = false;
		CKEDITOR.replace('footer_customization');
		CKEDITOR.config.autoParagraph = false;
		CKEDITOR.replace('new_user_email');
		CKEDITOR.config.autoParagraph = false;
		CKEDITOR.replace('success_payment_email');
		CKEDITOR.config.autoParagraph = false;
		CKEDITOR.replace('failed_payment_email');
		CKEDITOR.config.autoParagraph = false;
		CKEDITOR.replace('payment_noti_email');
		CKEDITOR.config.autoParagraph = false;
		CKEDITOR.replace('new_app_user_email_for_admin');
		
			}
			
		
			searchNumbers(iso_country);
		
			
		
	};

		
function searchNexmoNumbers(ISOCountry){
	$("#loading").show();
	$("#showNumbers").html('');
	var Qry = 'cmd=search_nexmo_numbers&ISOCountry='+ISOCountry;
	$.post("server.php",Qry,function(res){
		$("#showNumbers").html(res);
		$("#showNumbers").show();
		$("#loading").hide();
	});
}

function generateApiKey(obj,apikey,server_url){
    if($.trim(apikey)==''){
        $(obj).val('Generating...');
        $.post('server.php',{"cmd":"generate_apikey"},function(r){
            $('input[name="api_key"]').val(r);
            $('#showApiUrl').html(server_url+'/nmapi/phpapi.php?api_key='+r+'&cmd={desired_resource}');
            $(obj).val('Generate Key');
        });
    }else{
        if(confirm("Are you sure? Previous key will be stopped working?")){
            $(obj).val('Generating...');
            $.post('server.php',{"cmd":"generate_apikey"},function(r){
                $('input[name="api_key"]').val(r);
                $('#showApiUrl').html(server_url+'/nmapi/phpapi.php?api_key='+r+'&cmd={desired_resource}');
                $(obj).val('Generate Key');
            });
        }
    }
}
function applySidebarColor(color){
    if(color=="#1A4180"){
        $('.sidebar-wrapper').css('background',color);
        $('.sidebar').attr('data-color',"");
    }else{
        $('.sidebar').attr('data-color',color);
        $('.sidebar-wrapper').css('background',"");
    }
}

$('.smsGateWay').on('change',function(r){
    $('.nexmoInfo').hide('slow');
    $('.plivoInfo').hide('slow');
    $('.twilioInfo').hide('slow');
    $('.mobileSimSection').hide('slow');

    if($(this).val()=='twilio'){
        $('.twilioInfo').show('slow');
    }else if($(this).val()=='plivo'){
        $('.plivoInfo').show('slow');
    }else if($(this).val()=='nexmo'){
        $('.nexmoInfo').show('slow');
    }else if($(this).val()=='mobile_sim'){
        $('.mobileSimSection').show('slow');
    }
});


window.onload = function(){
    if(user_type === '1') {
        CKEDITOR.config.autoParagraph = false;
        CKEDITOR.replace('footer_customization');
        CKEDITOR.config.autoParagraph = false;
        CKEDITOR.replace('new_user_email');
        CKEDITOR.config.autoParagraph = false;
        CKEDITOR.replace('success_payment_email');
        CKEDITOR.config.autoParagraph = false;
        CKEDITOR.replace('failed_payment_email');
        CKEDITOR.config.autoParagraph = false;
        CKEDITOR.replace('payment_noti_email');
        CKEDITOR.config.autoParagraph = false;
        CKEDITOR.replace('new_app_user_email_for_admin');
    }

    searchNumbers(getnumberonload);
};

$('.enableSenderID').on('click',function(){

    if($(this).is(":checked")==true){

        $('.senderID').show('slow');

    }else{

        $('.senderID').hide('slow');

    }

});



$('input[name="payment_processor"]').on('click',function(r){

    if($(this).val()=='1'){

        $('#authnet_area').hide('slow');

        $('#paypal_area').show('slow');

        $('#stripe_area').hide('slow');

    }

    else if($(this).val()=='2'){

        $('#paypal_area').hide('slow');

        $('#authnet_area').show('slow');

        $('#stripe_area').hide('slow');

    }

    else{

        $('#paypal_area').hide('slow');

        $('#authnet_area').hide('slow');

        $('#stripe_area').show('slow');



    }

});



$('input[name="paypal_switch"]').on('click',function(r){

    if($(this).val()=='0'){

        $('#paypal_sandbox_email').show('slow');

        $('#paypal_live_email').hide('slow');

    }

    else{

        $('#paypal_sandbox_email').hide('slow');

        $('#paypal_live_email').show('slow');

    }

});

function showNexmoSections(obj){
	var numType = $(obj).val();
	if(numType=='1'){
		$("#buy_credits_section").hide('slow');
		
		$("#purchase_number").hide('slow');
		$('#search_pattren').hide('slow');
		$("#showNumbers").hide('slow');
		$("#existing_number").hide('slow');
		$("#purchase_nexmo_number").show('slow');
	}else if(numType=='2'){
		$("#buy_credits_section").show('slow');
		$('#search_pattren').hide('slow');
		$("#purchase_number").hide('slow');
		
		$("#showNumbers").hide('slow');
		$("#existing_number").hide('slow');
		$("#existing_number").hide('slow');
		$("#purchase_nexmo_number").hide('slow');
		$("#loading").hide();
	}else{
		$("#loading").show();
		if(user_type=='1'){
		var Qry = 'cmd=get_nexmo_existing_numbers';
		
		var Qry = 'cmd=get_nexmo_existing_numbers_in_subaccount&user_id=sessions';
		
		$.post("server.php",Qry,function(res){
			$("#buy_credits_section").hide('slow');
			$('#search_pattren').hide('slow');
			$("#purchase_number").hide('slow');
			$("#existing_number").html(res);
			$("#showNumbers").hide('slow');
			$("#purchase_nexmo_number").hide('slow');
			$("#existing_number").show('slow');
			$("#loading").hide();
		});
	}
 }
}
function buyNumber(){

    var arr = new Array();

    var checked = $('[name=buy_num]:checked');

    var country = $('#phone_type option:selected').text();

    var ISOcountry = $('#phone_type option:selected').val();

    checked.each(function(){

        arr.push($(this).val());

    });

    if(arr==""){

        alert("Select at least one number.");

    }

    else{

        if(confirm("Are you sure you want to buy number(s)?")){

            $("#loading").show();

            var Qry = 'cmd=buy_number&numbers='+encodeURIComponent(arr)+'&country='+country+'&ISOcountry='+ISOcountry;

            $.post('server.php',Qry,function(r){

                window.location = 'buy_number.php';

            });

        }

    }

}

function removeFromInstall(numberSid,number){

    if(confirm("Are you sure you want to remove number from this install?")){

        $("#loading").show();

        var Qry = 'cmd=remove_from_install&phoneSid='+numberSid+'&number='+encodeURIComponent(number);

        $.post("server.php",Qry,function(res){

            if(res=="1"){

                $("#loading").html('<span class="green-color">Number released from your install.</span>');

                window.location = 'buy_number.php';

            }

            else{

                $("#loading").html('<span class="red-color">'+res+'</span>');

            }

        });

    }}

function addToInstall(numberSid,number,country,isoCountry){

    if(confirm("Are you sure you want to add this number?")){

        $("#loading").show();

        var Qry = 'cmd=assign_to_install&phoneSid='+numberSid+'&phone_number='+encodeURIComponent(number)+'&country='+country+'&isoCountry='+isoCountry;

        $.post("server.php",Qry,function(res){

            if(res=="1"){

                $("#loading").html('<span  class="green-color">Number assigned to your install.</span>');

                window.location = 'buy_number.php';

            }

            else{

                $("#loading").html('<span class="red-color">'+res+'</span>');

            }

        });

    }

}



function searchNumbers(country){
    if(country=="US"){
        $("#showNumbers").html('');
        $("#usa_section").show();
    }else{
        $("#loading").show();
        $("#showNumbers").html('');
        $("#usa_section").hide();
        var Qry = 'cmd=get_numbers&country='+country;
        $.post("server.php",Qry,function(res){
            $("#showNumbers").html(res);
            $("#showNumbers").show();
            $("#loading").hide();
        });
    }}

function getnumbers(obj){

    $("#loading").show();

    var state = $("#state").val();

    var areacode = $("#areacode").val();

    var country = $('#phone_type').val();

    var Qry = 'state='+state+'&areacode='+areacode+'&cmd=get_numbers&country='+country;

    $.post("server.php",Qry,function(res){

        $("#showNumbers").html(res);

        $("#showNumbers").show();

        $("#loading").hide();

    });

}

function getareacodes(obj){

    $("#loading").show();

    $("#showNumbers").html('');

    var state = obj.value;

    var Qry = 'state_code='+state+'&cmd=get_area_codes';

    $.post("server.php",Qry,function(res){

        $("#areacode").html(res);

        $("#loading").hide();

    });

}
function showSections(obj){
    if(obj.value=="1"){
        if(user_type2 === 1) {
            $("#purchase_number").show('slow');
            $("#existing_number").hide('slow');
        }else{
            $("#purchase_number").show("slow");
            $("#existing_number").hide("slow");
            searchNumbers(iso_country);
        }
        $("#buy_credits_section").hide('slow');
    }
    else if(obj.value=="2"){
        $("#purchase_number").hide('slow');
        $("#existing_number").html('');
        $("#showNumbers").hide('slow');
        $("#existing_number").hide('slow');
        $("#buy_credits_section").show('slow');
    }else{

        $("#loading").show();

        var Qry = 'cmd=get_existing_numbers';

        $.post("server.php",Qry,function(res){

            $("#buy_credits_section").hide('slow');

            $("#purchase_number").hide('slow');

            $("#existing_number").html(res);

            $("#showNumbers").hide('slow');

            $("#existing_number").show('slow');

            $("#loading").hide();

        });

    }

}

function OnKeyPress(e){

    if(window.event){e=window.event;}

    if(e.keyCode==13){

        getNumberByAreaCode();

    }

}

function getNumberByAreaCode(){

    $("#loading").show();

    $("#showNumbers").html('');

    var areaCode = $("#selected_areacode").val();

    var country = $('#phone_type').val();

    var Qry = 'areacode='+areaCode+'&country='+country+'&cmd=get_numbers_areacode';

    $.post("server.php",Qry,function(res){

        $("#showNumbers").html(res);

        $("#showNumbers").show();

        $("#loading").hide();

    });

}

function showSection(obj){

    if(obj.value=="state"){

        $("#showAraaCodeSection").hide();

        $("#showStateSection").show();

        $("#phone_number").html('');

        $("#showNumbers").hide();

    }

    else{

        $("#showStateSection").hide();

        $("#showAraaCodeSection").show();

        $("#phone_number").html('');

        $("#showNumbers").hide();

    }

}