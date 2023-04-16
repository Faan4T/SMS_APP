<?php
session_start();
require_once 'anet_php_sdk/AuthorizeNet.php';
$api_login_id 	 = '8YZE8Tfb6e';
$transaction_key = '23cc9sJvw6A7F2U4';
$amount 		 = "4.44";
$packageID 		 = "1-200-".date("ymdhis");
$fp_timestamp 	 = time();
$fp_sequence 	 = "123" . time();
$userID			 = $_SESSION['admin_id']; 
$fingerprint = AuthorizeNetSIM_Form::getFingerprint($api_login_id,
$transaction_key, $amount, $fp_sequence, $fp_timestamp)
?>
<form method='post' action="https://test.authorize.net/gateway/transact.dll">
<input type='hidden' name="x_login" value="<?php echo DBout($api_login_id)?>" />
<input type='hidden' name="x_fp_hash" value="<?php echo DBout($fingerprint)?>" />
<input type='hidden' name="x_amount" value="<?php echo DBout($amount)?>" />
<input type='hidden' name="x_fp_timestamp" value="<?php echo DBout($fp_timestamp)?>" />
<input type='hidden' name="x_fp_sequence" value="<?php echo DBout($fp_sequence)?>" />
<input type='hidden' name="x_version" value="3.1">
<input type='hidden' name="x_show_form" value="payment_form">
<input type='hidden' name="x_test_request" value="false" />
<input type='hidden' name="x_method" value="cc">
<input type='hidden' name="x_invoice_num" value="<?php echo DBout($packageID);?>">
<input type='hidden' name="x_description" value="Package 1">
<input type='hidden' name="x_cust_id" value="<?php echo DBout($userID)?>">
<input type='hidden' name="x_first_name" value="M Ahsan">
<input type='hidden' name="x_last_name" value="Mirza">
<input type='hidden' name="x_company" value="Nimble">
<input type='hidden' name="x_address" value="127-p Eric Street, Newyork">
<input type='hidden' name="x_city" value="Newyork">
<input type='hidden' name="x_state" value="Alberta">
<input type='hidden' name="x_zip" value="380000">
<input type='hidden' name="x_country" value="USA">
<input type='hidden' name="x_phone" value="+18323041166">
<input type='hidden' name="x_fax" value="+854568545">
<input type='hidden' name="x_email" value="ahsan@nimblewebsolutions.com">

<input type='hidden' name="x_cancel_url" value="http://wreckingballsms.mobi/woottest/">
<input type='hidden' name="x_receipt_link_url" value="http://wreckingballsms.mobi/woottest/Auth.net/thankyou.php">

<input type='hidden' name="x_relay_always" value="TRUE">
<input type='hidden' name="x_relay_response" value="TRUE">

<input type='hidden' name="x_relay_url" value="http://wreckingballsms.mobi/woottest/Auth.net/thankyou.php">
<input type='submit' value="Click here for the secure payment form">
</form>