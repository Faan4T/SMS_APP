"use strict";
$('#packagesTable').cardtable();
function deletePlan(id){
    if(confirm("Are you sure you want to delete this pricing plan?")){
        window.location = 'server.php?cmd=delete_plan&id='+id;
    }
}