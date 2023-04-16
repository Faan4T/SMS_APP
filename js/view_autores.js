"use strict";
function deleteCampaign(id,img){
	if(confirm("Are you sure you want to delete this autoresponder?")){
		window.location = 'server.php?cmd=delete_autores&id='+id+'&media='+img;
	}
}