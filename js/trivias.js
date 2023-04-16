"use strict";
	function duplicateCampaign(){
		var campID = $('#duplicate_camp_id').val();
		var title = $('input[name=title]').val();
		var keyword = $('input[name=keyword]').val();
		if(($.trim(title)!="") && ($.trim(keyword)!="")){
			$('#duplicateCampaignloading').show();
			$.post('server.php',{"cmd":"duplicate_campaign",title:title,keyword:keyword,campID:campID},function(r){
				var res = $.parseJSON(r);
				if(res.error=='no'){
					$('#duplicateCampaignloading').html(res.message);
					window.location = 'view_campaigns.php';
				}else{
					$('#duplicateCampaignloading').html(res.message);
				}
			});
		}else{
			alert("All fields are required.");	
		}
	}
	function getCampaignID(campID){
		$('#duplicate_camp_id').val(campID);
	}
	function loadBlockedNumbers(groupID,searchType){
		$('.showSubsType').html(searchType);
		$('.showBlockedNumbers').html('Loading...');
		$.post('server.php',{"cmd":"subscribers_stats",groupID:groupID,searchType:searchType},function(r){
			$('.showBlockedNumbers').html(r);
		});
	}
	$('#campaignTable').cardtable();
    function PostMessage_tw(){
       
        $("#alertArea").html('<div class="alert alert-info">Posted On Facebook! Please Hold...</div>');
        var post_message = $("#post_message_tw").val();
        var camp_id = $("#camp_id_tw").val();
        var qr = "camp_id="+camp_id+"&post_message="+post_message;
        $.post('share_on_twitter.php?'+qr ,function(res){
            if(res!=""){
                $("#alertArea").html(res);
            }
        });
    }
    function PostMessage(){
      
        $("#alertArea").html('<div class="alert alert-info">Posted On Facebook! Please Hold...</div>');
        var post_message = $("#post_message").val();
        var camp_id = $("#camp_id").val();
        var qr = "camp_id="+camp_id+"&post_message="+post_message;
        $.post('share_on_facebook.php?'+qr ,function(res){
            if(res!=""){
                $("#alertArea").html(res);
            }
        });
    }
    
    function make_post_tw(camp_id){
        $("#post_message_tw").val("");
        $("#camp_id_tw").val(camp_id);
        var qr = "cmd=get_post_message&camp_id="+camp_id
        $.post('server.php?'+qr ,function(res){
            if(res!=""){
                $("#post_message_tw").val(res);
            }
        });
    }
    function make_post_fb(camp_id){
        $("#post_message").val("");
        $("#camp_id").val(camp_id);
        var qr = "cmd=get_post_message&camp_id="+camp_id
        $.post('server.php?'+qr ,function(res){
            if(res!=""){
                $("#post_message").val(res);
            }
        });
    }
    
	function deleteCampaign(id,img){
		if(confirm("Are you sure you want to delete this campagin?")){
			window.location = 'server.php?cmd=delete_campaign&id='+id;
		}
	}