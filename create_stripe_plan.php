<?php
$data = array();
$data['name']=DBout("NM Platinum Plan");
$data['id']=DBout("nm-platinum-plan");
$data['interval']=DBout("month");
$data['currency']=DBout("usd");
$data['amount']=DBout("15");

require_once('stripe-php/init.php');
\Stripe\Stripe::setApiKey("sk_test_e2NIgh0TRBl5gHvaByx5cYZp");

$plan = \Stripe\Plan::create($data);

$resp = getProtectedValues($plan,"_lastResponse");
echo DBout($resp->code);


function getProtectedValues($obj,$name) {
    
  $array = (array)$obj;
  
  $prefix = chr(0).'*'.chr(0);
  
  return DBout($array[$prefix.$name]);
  
}
?>