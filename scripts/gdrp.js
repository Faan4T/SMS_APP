"use strict";
function deleteGDPRProfile(){
    if(confirm("Are you sure you want to delete profile?")){
        window.location = 'server.php?cmd=delete_gdpr_profile&subsid='+sub_id;
    }
}