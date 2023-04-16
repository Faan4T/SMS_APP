<?php
	set_time_limit(900000);
	ini_set('max_execution_time', 900000);	
	session_start();
	include_once("database.php");
	include_once("functions.php");
?>
<script src="assets/js/jquery-1.10.2.js"></script>
<link rel="stylesheet" href="css/ranksol_hazii.css">
<input id="hidden_sms_id" name="hidden_sms_id" value="<?php echo DBout(DBin($_REQUEST["hidden_sms_id"])); ?>" type="hidden"/>
<input id="bulk_type" name="bulk_type" value="<?php echo DBout(DBin($_REQUEST["bulk_type"])); ?>" type="hidden"/>
<input id="client_id" name="client_id" value="<?php echo DBout(DBin($_REQUEST["client_id"])); ?>" type="hidden"/>
<input id="from_number" name="from_number" value="<?php echo DBout(DBin($_REQUEST["from_number"])); ?>" type="hidden"/>
<input id="group_id" name="group_id" value="<?php echo DBout(DBin($_REQUEST["group_id"])); ?>" type="hidden"/>
<input id="phone_number_id" name="phone_number_id" value="<?php echo DBout(DBin($_REQUEST["phone_number_id"])); ?>" type="hidden"/>
<input id="start_date" name="start_date" value="<?php echo DBout(DBin($_REQUEST["start_date"])); ?>" type="hidden"/>
<input id="end_date" name="end_date" value="<?php echo DBout(DBin($_REQUEST["end_date"])); ?>" type="hidden"/>
<input id="daterange_group_id" name="daterange_group_id" value="<?php echo DBout(DBin($_REQUEST["daterange_group_id"])); ?>" type="hidden"/>
<div class="width-80-per margin-0-auto">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 id="json_msg_response">Please wait! Sending Bulk Messages... <img src="images/ajax-loader-black-bar.gif" /> </h3>
			<div class="remove_bulk_style" onclick="window.close()" title="Close the window"><span class="glyphicon glyphicon-remove"></span></div>
		</div>
		<div class="panel-body" id="response_wait_image">
			<div class="alert alert-danger" id="warning_msg"> Please don't close this tab untill system sends the messages to the selected recipients. </div>
		</div>
	</div>
</div>
<?php

if(trim($_REQUEST['hidden_sms_id']) == "")
{
    die("Not Allowed");
}
?>
<script type="text/javascript" src="scripts/bulk.js">
</script>