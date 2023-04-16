<?php	
	include_once('AuthnetARB.class.php');
	include_once('functions.php');
	
	$api_login_id = $appSettings['auth_net_api_login_id'];
	$transaction_key = $appSettings['auth_net_trans_key'];
	global $link;
	$sel = sprintf("select * from web_user_info where id='%s'",
                    mysqli_real_escape_string($link,DBin($webUserID))
        );
	$res = mysqli_query($link,$sel);
	if($res and mysqli_num_rows($res)){
		$row = mysqli_fetch_assoc($res);	
	}
	$isFreeDays = $pkgInfo['is_free_days'];
	if($isFreeDays=='1'){
		$freeDays = $pkgInfo['free_days'];
		$paymentDate = date("Y-m-d", strtotime("+ ".$freeDays." days"));
	}else{
		$paymentDate = date("Y-m-d", strtotime("+ 1 day"));	
	}
	$year = $row['year'];
	$month = $row['month'];
	$card_date = $month."-".$year;
	$subscription = new AuthnetARB($api_login_id, $transaction_key,AuthnetARB::USE_DEVELOPMENT_SERVER);
    $subscription->setParameter('amount', str_replace('$','',$pkgPrice));
    $subscription->setParameter('subscrName',$pkgName);
    $subscription->setParameter('cardNumber', $row['card_number']);
    $subscription->setParameter('cardCode', $row['cvv']);
    $subscription->setParameter('expirationDate',$card_date);
    $subscription->setParameter('firstName', $row['first_name']);
    $subscription->setParameter('lastName', $row['last_name']);
    $subscription->setParameter('address', $row['address']);
    $subscription->setParameter('city', $row['city']);
    $subscription->setParameter('state', $row['state']);
    $subscription->setParameter('zip', $row['zip']);
    $subscription->setParameter('email', $row['email']);
    $subscription->setParameter('interval_length', 1);
    $subscription->setParameter('startDate', $paymentDate);
    if($_REQUEST['suspendemail']== "1"){
        $select_subscription  = sprintf("select * from users where email = '%s'",
                    mysqli_real_escape_string($link,DBin($_REQUEST['email']))
            );
        $result_subscription  = mysql_query($select_subscription) or die (mysql_error()); 
            if (mysql_num_rows($result_subscription) > 0){
                $subscriptionRow =  mysql_fetch_assoc($result_subscription);
                $suspendsubscription    =   $subscriptionRow['subscription_id'];
            }
            $subscription->setParameter('subscrId',$suspendsubscription);
            $subscription->updateAccount();
            if ($subscription->isSuccessful())
            {
              $subscription_id    =   $subscriptionRow['subscription_id'];  
            }
    }else{
        $subscription->createAccount();
        if($subscription->isSuccessful()){
            $subscription_id = $subscription->getSubscriberID();
        }
    }
    
    if($subscription->isSuccessful()){
        $response = $subscription->getResponse();
        $response_code = $subscription->getResponseCode();
        $sels = sprintf("update web_user_info set response = '%s', response_code = '%s', subscription_id = '%s' where id='%s'",
                        mysqli_real_escape_string($link,DBin($response)),
                        mysqli_real_escape_string($link,DBin($response_code)),
                        mysqli_real_escape_string($link,DBin($subscription_id)),
                        mysqli_real_escape_string($link,DBin($webUserID))
            );
        $res = mysqli_query($link,$sels);
        $_SESSION['authnet_response'] = 1;
        $_SESSION['authnet_msg'] = "Your Payment is under process, You will get an email wihtin 24 hours with your account login information"; 

		$_REQUEST['response'] = $response;
		$_REQUEST['response_code'] = $response_code;
		$_REQUEST['subscription_id'] = $subscription_id;
		$appSettings = getAppSettings($row['parent_user_id']);
		$appUrl		 = getServerUrl();
		if($isFreeDays=='1'){
			$password	 = $row['password'];
			$encryptedPassword = encodePassword($row['password']);
			$ins = sprintf("insert into users
						(
							first_name,
							last_name,
							email,
							password,
							type,
							parent_user_id,
							business_name,
							tcap_ctia,
							msg_and_data_rate,
							city,
							state,
							zip,
							address,
							card_number,
							cvv,
							year,
							month,
							response,
							response_code,
							subscription_id,
							paypal_subscriber_id
						)
					values
						(
							'%s',
							'%s',
							'%s',
							'%s',
							'2',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
						)",
                            mysqli_real_escape_string($link,DBin($row['first_name'])),
							mysqli_real_escape_string($link,DBin($row['last_name'])),
							mysqli_real_escape_string($link,DBin($row['email'])),
							mysqli_real_escape_string($link,DBin($encryptedPassword)),
							mysqli_real_escape_string($link,DBin($row['parent_user_id'])),
							mysqli_real_escape_string($link,DBin($row['business_name'])),
							mysqli_real_escape_string($link,DBin($row['tcap_ctia'])),
							mysqli_real_escape_string($link,DBin($row['msg_and_data_rate'])),
							mysqli_real_escape_string($link,DBin($row['city'])),
							mysqli_real_escape_string($link,DBin($row['state'])),
							mysqli_real_escape_string($link,DBin($row['zip'])),
							mysqli_real_escape_string($link,DBin($row['address'])),
							mysqli_real_escape_string($link,DBin($row['card_number'])),
							mysqli_real_escape_string($link,DBin($row['cvv'])),
							mysqli_real_escape_string($link,DBin($row['year'])),
							mysqli_real_escape_string($link,DBin($row['month'])),
							mysqli_real_escape_string($link,DBin($row['response'])),
							mysqli_real_escape_string($link,DBin($row['response_code'])),
							mysqli_real_escape_string($link,DBin($row['subscription_id'])),
							mysqli_real_escape_string($link,DBin($_REQUEST['subscr_id']))
                );
			$exe = mysqli_query($link,$ins);
			if($exe){
				$userID	= mysqli_insert_id($link);
				mysqli_query($link,sprintf("delete from web_user_info where id='%s'",mysqli_real_escape_string($link,DBin($row['id']))));
				try{
					$client = getTwilioConnection($row['parent_user_id']);
				}catch(Services_Twilio_RestException $e){
				}
				
				try{
					$account= $client->accounts->create(array(
						"FriendlyName" => $row['email']
					));
					$subAccountSid 	 = $account->sid;
					$subAccountToken = $account->auth_token;
				}catch(Services_Twilio_RestException $e){
				}
				$sql1 = sprintf("insert into application_settings(twilio_sid,twilio_token,user_id,user_type)values
				('%s','%s','%s','2')",
                        mysqli_real_escape_string($link,DBin($subAccountSid)),
                        mysqli_real_escape_string($link,DBin($subAccountToken)),
                        mysqli_real_escape_string($link,DBin($userID))
                    );
				mysqli_query($link,$sql1);
				$pkgInfo= getPackageInfo($row['pkg_id']);
                $_REQUEST['item_name'] = $pkgInfo['title']." SMS Plan";
                
				$today	= date('Y-m-d H').':00:00';
				$endDate= date('Y-m-d H:i',strtotime('+1 month'.$today));
				$insPkg = sprintf("insert into user_package_assignment
								(
									user_id,
									pkg_id,
									start_date,
									end_date,
									sms_credits,
									phone_number_limit,
									iso_country,
									pkg_country
								)
							values
								(
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
								)",
                                    mysqli_real_escape_string($link,DBin($userID)),
                                    mysqli_real_escape_string($link,DBin($row['pkg_id'])),
									mysqli_real_escape_string($link,DBin($today)),
									mysqli_real_escape_string($link,DBin($endDate)),
									mysqli_real_escape_string($link,DBin($pkgInfo['sms_credits'])),
									mysqli_real_escape_string($link,DBin($pkgInfo['phone_number_limit'])),
									mysqli_real_escape_string($link,DBin($pkgInfo['iso_country'])),
									mysqli_real_escape_string($link,DBin($pkgInfo['country']))
                    );
				mysqli_query($link,$insPkg);
				
				$subject = $appSettings['email_subject'];
				$to		 = $row['email'];
				$from	 = 'admin@'.str_replace('www.','',$_SERVER['SERVER_NAME']);
				$msg	 = $appSettings['new_app_user_email'];
				$msg	 = str_replace('%first_name%',$row['first_name'],$msg);
				$msg	 = str_replace('%last_name%',$row['last_name'],$msg);
				$msg	 = str_replace('%login_email%',$row['email'],$msg);
				$msg	 = str_replace('%login_pass%',$password,$msg);
				$msg	 = str_replace('%login_url%',$appUrl,$msg);
				$FullName= 'Admin';
				sendEmail($subject,$to,$from,$msg,$FullName);

				$subject = $appSettings['email_subject_for_admin_notification'];
				$to		 = $appSettings['admin_email'];
				$from	 = 'admin@'.str_replace('www.','',$_SERVER['SERVER_NAME']);
				$msg	 = str_replace('%email%',$row['email'],$appSettings['new_app_user_email_for_admin']);
				$FullName= 'Admin';
				sendEmail($subject,$to,$from,$msg,$FullName);
			}
		}
		else{
			$password	 = $row['password'];
			$encryptedPassword = encodePassword($row['password']);
			$ins = sprintf("insert into users
						(
							first_name,
							last_name,
							email,
							password,
							type,
							parent_user_id,
							business_name,
							tcap_ctia,
							msg_and_data_rate,
							city,
							state,
							zip,
							address,
							card_number,
							cvv,
							year,
							month,
							response,
							response_code,
							subscription_id,
							paypal_subscriber_id
						)
					values
						(
							'%s',
							'%s',
							'%s',
							'%s',
							'2',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
						)",
                             mysqli_real_escape_string($link,DBin($row['first_name'])),
							 mysqli_real_escape_string($link,DBin($row['last_name'])),
							 mysqli_real_escape_string($link,DBin($row['email'])),
							 mysqli_real_escape_string($link,DBin($encryptedPassword)),
							 mysqli_real_escape_string($link,DBin($row['parent_user_id'])),
							 mysqli_real_escape_string($link,DBin($row['business_name'])),
							 mysqli_real_escape_string($link,DBin($row['tcap_ctia'])),
							 mysqli_real_escape_string($link,DBin($row['msg_and_data_rate'])),
							 mysqli_real_escape_string($link,DBin($row['city'])),
							 mysqli_real_escape_string($link,DBin($row['state'])),
							 mysqli_real_escape_string($link,DBin($row['zip'])),
							 mysqli_real_escape_string($link,DBin($row['address'])),
							 mysqli_real_escape_string($link,DBin($row['card_number'])),
							 mysqli_real_escape_string($link,DBin($row['cvv'])),
							 mysqli_real_escape_string($link,DBin($row['year'])),
							 mysqli_real_escape_string($link,DBin($row['month'])),
							 mysqli_real_escape_string($link,DBin($row['response'])),
							 mysqli_real_escape_string($link,DBin($row['response_code'])),
							 mysqli_real_escape_string($link,DBin($row['subscription_id'])),
							 mysqli_real_escape_string($link,DBin($_REQUEST['subscr_id']))

                );
			$exe = mysqli_query($link,$ins)or die(mysqli_error($link));
			if($exe){
				$userID	= mysqli_insert_id($link);
				mysqli_query($link,sprintf("delete from web_user_info where id='%s'",mysqli_real_escape_string($link,DBin($row['id']))));
				try{
					$client = getTwilioConnection($row['parent_user_id']);
				}catch(Services_Twilio_RestException $e){
				}
				
				try{
					$account= $client->accounts->create(array(
						"FriendlyName" => $row['email']
					));
					$subAccountSid 	 = $account->sid;
					$subAccountToken = $account->auth_token;
				}catch(Services_Twilio_RestException $e){
				}
				sprintf("insert into application_settings(twilio_sid,twilio_token,user_id,user_type)values
				('%s','%s','%s','2')",
                        mysqli_real_escape_string($link,DBin($subAccountSid)),
                        mysqli_real_escape_string($link,DBin($subAccountToken)),
                        mysqli_real_escape_string($link,DBin($userID))
                    );
				mysqli_query($link,$sql1);
				$pkgInfo= getPackageInfo($row['pkg_id']);
                $_REQUEST['item_name'] = $pkgInfo['title']." SMS Plan";
                
				$today	= date('Y-m-d H').':00:00';
				$endDate= date('Y-m-d H:i',strtotime('+1 month'.$today));
				$insPkg = sprintf("insert into user_package_assignment
								(
									user_id,
									pkg_id,
									start_date,
									end_date,
									sms_credits,
									phone_number_limit,
									iso_country,
									pkg_country
								)
							values
								(
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
									'%s',
								)",
                                    mysqli_real_escape_string($link,DBin($userID)),
                                    mysqli_real_escape_string($link,DBin($row['pkg_id'])),
									mysqli_real_escape_string($link,DBin($today)),
									mysqli_real_escape_string($link,DBin($endDate)),
									mysqli_real_escape_string($link,DBin($pkgInfo['sms_credits'])),
									mysqli_real_escape_string($link,DBin($pkgInfo['phone_number_limit'])),
									mysqli_real_escape_string($link,DBin($pkgInfo['iso_country'])),
									mysqli_real_escape_string($link,DBin($pkgInfo['country']))

                    );
				mysqli_query($link,$insPkg);
				
				$subject = $appSettings['email_subject'];
				$to		 = $row['email'];
				$from	 = 'admin@'.str_replace('www.','',$_SERVER['SERVER_NAME']);
				$msg	 = $appSettings['new_app_user_email'];
				$msg	 = str_replace('%first_name%',$row['first_name'],$msg);
				$msg	 = str_replace('%last_name%',$row['last_name'],$msg);
				$msg	 = str_replace('%login_email%',$row['email'],$msg);
				$msg	 = str_replace('%login_pass%',$password,$msg);
				$msg	 = str_replace('%login_url%',$appUrl,$msg);
				$FullName= 'Admin';
				$eu = sendEmail($subject,$to,$from,$msg,$FullName);
				
				$subject = $appSettings['email_subject_for_admin_notification'];
				$to		 = $appSettings['admin_email'];
				$from	 = 'admin@'.str_replace('www.','',$_SERVER['SERVER_NAME']);
				$msg	 = str_replace('%email%',$row['email'],$appSettings['new_app_user_email_for_admin']);
				$FullName= 'Admin';
				$ea = sendEmail($subject,$to,$from,$msg,$FullName);
			}	
		}  
    }

    else{
        $response = $subscription->getResponse();
        $response_code = $subscription->getResponseCode();
        
        $sels = sprintf("update web_user_info set response = '%s', response_code = '%s', subscription_id = '%s' where id='%s'",
                mysqli_real_escape_string($link,DBin($response)),
                mysqli_real_escape_string($link,DBin($response_code)),
                mysqli_real_escape_string($link,DBin($subscription_id)),
                mysqli_real_escape_string($link,DBin($webUserID))
            );
        $res = mysqli_query($link,$sels);    
            
        $_SESSION['authnet_response'] = 0;
        $_SESSION['authnet_msg'] = $response_code." : ".$response;
    }
?>
<script> window.location = "add_user.php?pid=<?php echo DBout(encode($row['pkg_id']))?>"; </script>