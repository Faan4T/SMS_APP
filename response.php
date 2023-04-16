<?php
$date = DBout(date('Y-m-d H:i:s'));

$x_cust_id = explode("_",DBin($_REQUEST['x_cust_id']));
if(count($x_cust_id)==3)
{

    if($_REQUEST['x_response_code'] == '1' and $_REQUEST['x_response_reason_code'] == '1')
    {

        $_REQUEST['custom'] = DBin($_REQUEST['x_cust_id']);
        $_REQUEST['payer_status']=DBin( $_REQUEST['x_response_code']);
         $_REQUEST['payer_email'] = DBin($_REQUEST['x_email']);
         $_REQUEST['txn_id'] = DBin($_REQUEST['x_trans_id']);
        $_REQUEST['payment_status'] = DBin("Completed");
         $_REQUEST['mc_gross']= DBin($_REQUEST['x_amount']);
       $_REQUEST['item_name'] = DBin($_REQUEST['x_description']);
        $_REQUEST['payment_processor'] = DBout("2");
        
        include_once("credits_notify.php");
    }
}
else
{
    $subscription_id = DBin($_REQUEST['x_subscription_id']);
   
    include_once('database.php');
    include_once("functions.php");
    
    if(trim($subscription_id)==""){
        die();
    }
    
    $selects = sprintf("select * from web_user_info where subscription_id = %s",
                mysqli_real_escape_string($link,DBin($subscription_id))
        );
    $q_run = mysqli_query($link,$selects);
	if(mysqli_num_rows($q_run)){
		$web_user_info =  mysqli_fetch_assoc($q_run);
		$parent_user_id = DBout($web_user_info['parent_user_id']);
		
	}else{
		$sql = sprintf("select * from usres where subscription_id = %s",
                    mysqli_real_escape_string($link,DBin($subscription_id))
            );
		$res= mysqli_query($link,$sql);
		$web_user_info = mysqli_fetch_assoc($res);
		
	}
    $appSettings = getAppSettings($parent_user_id,true);
    
    $api_login_id = DBout($appSettings['auth_net_api_login_id']);
    $transaction_key = DBout($appSettings['auth_net_trans_key']);
    include_once('AuthnetARB.class.php');
    $subscription = new AuthnetARB($api_login_id, $transaction_key,AuthnetARB::USE_DEVELOPMENT_SERVER);
    $subscription->setParameter('subscrId',$subscription_id);
                $subscription->SubscriptionStatus();
                if ($subscription->isSuccessful())
                {
                    $substat    =   $subscription->getSubscrStatus().$subscription_id; 
                    if($subscription->getSubscrStatus() == "suspended"){
                      if(isset($subscription_id) && $subscription_id!= ""){
                          $sql1 = sprintf("update users set status='4', authorize_status = 'suspended' where subscription_id = %s",
                                    mysqli_real_escape_string($link,DBin($subscription_id))
                              );
                            mysqli_query($link,$sql1);
                        }   
                    }else if($subscription->getSubscrStatus() == "terminated" || $subscription->getSubscrStatus() == "cancelled"){
                      if(isset($subscription_id) && $subscription_id!= ""){
                          $sql2 = sprintf("update users set status='3', authorize_status = 'cancelled' where subscription_id = %s",
                                    mysqli_real_escape_string($link,DBin($subscription_id))
                              );
                            mysqli_query($link,$sql2);
                        }   
                    }
                }else{
                    $subgetresp =   $subscription->getResponse().$subscription_id;
                    if(isset($subscription_id) && $subscription_id!= ""){
                        $sql3 = sprintf("update users set status='4', authorize_status = %s where subscription_id =%s ",
                                mysqli_real_escape_string($link,DBin($subscription->getResponse())),
                                mysqli_real_escape_string($link,DBin($subscription_id))
                            );
                            mysqli_query($link,$sql3);
                        }  
                }
    if(isset($_REQUEST['x_response_code']) && $_REQUEST['x_response_code']==1){
    $jason = json_encode($_REQUEST);
        
        $_REQUEST['custom'] = DBout($web_user_info['id']);
        $_REQUEST['payer_status'] = DBin($_REQUEST['x_response_code']);
        $_REQUEST['payer_email'] = DBout($web_user_info['email']);
        $_REQUEST['txn_id'] = DBin($_REQUEST['x_trans_id']);
        $_REQUEST['payment_status'] = DBin($_REQUEST['x_response_code']);
        $_REQUEST['payment_gross'] = DBin($_REQUEST['x_amount']);
        $_REQUEST['item_name'] = DBin($_REQUEST['x_description']);
        $_REQUEST['txn_type'] = DBout('subscr_payment');
        $_REQUEST['payment_processor'] = DBout("2");
        
        include_once("notify.php");
        
    }
    else if((isset($_REQUEST['x_response_code']) && $_REQUEST['x_response_code']==2) && (isset($_REQUEST['x_response_reason_code']) && $_REQUEST['x_response_reason_code']==3)){
        if(isset($_REQUEST['x_subscription_id']) && $_REQUEST['x_subscription_id']!= ""){
            $sql4 = sprintf("update users set status='4' , authorize_status = 'suspended' where subscription_id = %s",
                    mysqli_real_escape_string($link,DBin($_REQUEST['x_subscription_id']))
                );
            mysqli_query($link,$sql4);
			
			$subject = $appSettings['failed_payment_email_subject'];
			$to		 = $web_user_info['email'];
			$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
			$msg	 = $appSettings['failed_payment_email'];
			$FullName= 'Admin';
			sendEmail($subject,$to,$from,$msg,$FullName);
			
			$appSettings = getAppSettings($userID,true);
			$subject = $appSettings['payment_noti_subject'];
			$to		 = $appSettings['admin_email'];
			$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
			$msg	 = str_replace('%email%',$web_user_info['email'],$appSettings['payment_noti_email'].'. Payment status is '.$_REQUEST['x_response_reason_text']);
			$FullName= 'Admin';
			sendEmail($subject,$to,$from,$msg,$FullName);
			
        }
    }
}

function LogErrors($data)
{
    $data = DBout($data);
	$myFile = "thanku.txt";
	$fh = fopen($myFile, 'a') or die("can't open file");
	fwrite($fh, $data);
	fclose($fh);
}


?>
