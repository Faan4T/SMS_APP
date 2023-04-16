<?php
/**
 * This file contains config info for the sample app.
 */

require_once 'anet_php_sdk/AuthorizeNet.php';


 $METHOD_TO_USE = "DIRECT_POST";


define("AUTHORIZENET_API_LOGIN_ID","9JyB3epJL27");
define("AUTHORIZENET_TRANSACTION_KEY","64582CszvVYk54Yn");
define("AUTHORIZENET_SANDBOX",true);
define("TEST_REQUEST", "false");


define("AUTHORIZENET_MD5_SETTING","9JyB3epJL27");
$site_root = "http://wreckingballsms.mobi/woottest/";


if (AUTHORIZENET_API_LOGIN_ID == "") {
    die('Enter your merchant credentials in config.php before running the sample app.');
}
