<?php
require_once('stripe-php/init.php');
\Stripe\Stripe::setApiKey("sk_test_e2NIgh0TRBl5gHvaByx5cYZp");

try
{
  $customer = \Stripe\Customer::create(array(
    'email' => DBin($_POST['stripeEmail']),
    'source'  => DBin($_POST['stripeToken']),
    'plan' => DBout('nm-platinum-plan')
  ));
    $customerData = getProtectedValues($customer,"_values");
    $customerID = DBout($customerData['id']);
    
    $resp = getProtectedValues($customer,"_lastResponse");
  exit;
}
catch(Exception $e)
{
  header('Location:oops.html');
  error_log("unable to sign up customer:" . DBin($_POST['stripeEmail']).
    ", error:" . DBout($e->getMessage()));
}


function getProtectedValues($obj,$name) {
    $obj = DBout($obj);
    $name = DBout($name);
  $array = (array)$obj;
  
  $prefix = chr(0).'*'.chr(0);
  
  return $array[$prefix.$name];
  
}

?>