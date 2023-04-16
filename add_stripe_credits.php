<?php
session_start();
include_once('database.php');
include_once('functions.php');
require_once('stripe-php/init.php');
$appSettings= getAppSettings(1,true);
\Stripe\Stripe::setApiKey($appSettings['stripe_secret_key']);
$token = DBin($_POST['stripeToken']);

try
{
    $charge = \Stripe\Charge::create(array(
      "amount" => DBin($_REQUEST['amt']),
      "currency" => "usd",
      "description" => "Add Credits",
      "source" => DBin($token),
    ));

    $chargeData = getProtectedValues($charge,"_values");
    $user = getUserInfo(DBin($_REQUEST['user_id']));
    
    if(isset($_REQUEST['stripeEmail']) && $_REQUEST['stripeEmail']!=""){
        $email = DBin($_REQUEST['stripeEmail']);
    }else{
        $email = $user['email'];   
    }
    $amount = DBin($_REQUEST['amt']/100);
    $userID = DBin($user['id']);
    $quantity = DBin($_REQUEST['q']);
    
    $sql = sprintf("insert into payment_history
			(
                payer_status,
				payer_email,
				txn_id,
				payment_status,
				gross_payment,
				product_name,
				user_id,
                payment_processor
			)
		values
			(
                '1',
				'%s',
				'%s',
				'Completed',
				'%s',
				'Add Credits',
				'%s',
                '3'
			)",
            mysqli_real_escape_string($link,DBin($email)),
            mysqli_real_escape_string($link,DBin($chargeData)),
            mysqli_real_escape_string($link,DBin($amount)),
            mysqli_real_escape_string($link,DBin($userID))
        );
	mysqli_query($link,$sql);
	
	$sqlrollover = sprintf("insert into rollover_credits
						(
							user_id,
							credits
						)
					values
						(
							'%s',
							'%s'
						)",
                        mysqli_real_escape_string($link,DBin($userID)),
                        mysqli_real_escape_string($link,DBin($quantity))
        );
	$resRollover = mysqli_query($link,$sqlrollover);
	if($resRollover){
		$sqlUpdate = sprintf("update user_package_assignment set sms_credits=sms_credits+'%s' where user_id=%s",
                        mysqli_real_escape_string($link,DBin($quantity)),
                        mysqli_real_escape_string($link,DBin($userID))
            );
		$resUpdate = mysqli_query($link,$sqlUpdate);
	}
    
    $_SESSION['message'] = "<div class='alert alert-success'>Credits updated successfully</div>";
    header("location: payment_history.php");
    
}
catch(Exception $e)
{
    $_SESSION['message'] = "<div class='alert alert-danger'>Unable to Charge Card... <br> error:" . DBout($e->getMessage()).'</div>';
    header("location: settings.php");
}

?>