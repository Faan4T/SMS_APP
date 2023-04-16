"use strict";
$('#accountsTable').cardtable();
function deleteAppUser(userID){
    if(confirm("Are you sure you want to delete this sub-account?")){
        if(confirm("It will delete all related data included twilio sub account and its phone numbers?")){
            window.location = 'server.php?id='+userID+'&cmd=delete_app_user';
        }
    }
}