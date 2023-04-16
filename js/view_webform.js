"use strict";
$('#webformTable').cardtable();
	function getEmbedCode(webFormID){
		$('.embedBody').html('<img src="images/busy.gif">');
		var Qry = 'cmd=generate_embed_code&wbf_id='+webFormID;
		$.post('server.php',Qry,function(r){
			$('.embedBody').html('<textarea class="form-control" rows="8" onClick="this.select()">'+r+'</textarea>');
		});
	}
	function deleteWebform(id){
		if(confirm("Are you sure you want to delete this webform?")){
			window.location = 'server.php?cmd=delete_webform&id='+id;
		}
	}