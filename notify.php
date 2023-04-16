<?php
	include_once("database.php");
	include_once("functions.php");
	if($_REQUEST['txn_type']=='subscr_payment'){
		$webUserID = DBin($_REQUEST['custom']);
		$sql = sprintf("select * from web_user_info where id=%s ",
                    mysqli_real_escape_string($link,DBin($webUserID))
            );
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
			$sel = sprintf("select * from users where email=%s ",
                    mysqli_real_escape_string($link,DBin($row['email']))
                );
			$exe = mysqli_query($link,$sel);
			if(mysqli_num_rows($exe)==0){
				$appSettings = getAppSettings($row['parent_user_id'],true);
				$appUrl		 = getServerUrl();
				$password	 = $row['password'];
				$encryptedPassword = encodePassword(DBout($row['password']));
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
                                                '%s'
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
					$sql1 = sprintf("delete from web_user_info where id=%s ",
                            mysqli_real_escape_string($link,DBin($row['id']))
                        );
					mysqli_query($link,$sql1);
					$client = getTwilioConnection($row['parent_user_id']);
					$account= $client->accounts->create(array(
						"FriendlyName" => DBout($row['email'])
					));
					$subAccountSid 	 = $account->sid;
					$subAccountToken = $account->auth_token;
					$sql2 = sprintf("insert into application_settings
                                                (
                                                        twilio_sid,
                                                        twilio_token,
                                                        user_id,
                                                        user_type
                                                )
                                                values
					                            (
                                                        '%s',
                                                        '%s',
                                                        '%s',
                                                        '2'
					                            )",
                                                        mysqli_real_escape_string($link,DBin($subAccountSid)),
                                                        mysqli_real_escape_string($link,DBin($subAccountToken)),
                                                        mysqli_real_escape_string($link,DBin($userID))
                                                );
					mysqli_query($link,$sql2);
					$pkgInfo= getPackageInfo($row['pkg_id']);
					$_REQUEST['item_name'] = DBout($pkgInfo['title']." SMS Plan");
					
					$today	= DBout(date('Y-m-d H').':00:00');
					$endDate= DBout(date('Y-m-d H:i',strtotime('+1 month'.$today)));
					$insPkg =
                        sprintf("insert into user_package_assignment
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
                                                '%s'
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
					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
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
					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
					$msg	 = str_replace('%email%',$row['email'],$appSettings['new_app_user_email_for_admin']);
					$FullName= 'Admin';
					sendEmail($subject,$to,$from,$msg,$FullName);
				}
			}
		}else{
			if(isset($_REQUEST['payment_processor']) && $_REQUEST['payment_processor']==2){
				$payment_processor = DBin($_REQUEST['payment_processor']);
				$sql = sprintf("select id,email from users where subscription_id=%s ",
                                 mysqli_real_escape_string($link,DBin($_REQUEST['subscription_id']))
                             );
			}else{
				$payment_processor = 1;
				$sql = sprintf("select id,email from users where paypal_subscriber_id=%s ",
                                mysqli_real_escape_string($link,DBin($_REQUEST['subscr_id']))
                               );
			}
			
			$res = mysqli_query($link,$sql);
			if(mysqli_num_rows($res)){
				$row = mysqli_fetch_assoc($res);
				$userID = DBout($row['id']);
				$appSettings = getAppSettings($userID);
				if(($_REQUEST['payment_status']=='Completed') ||  ($_REQUEST['payment_status']=='1')){
					$today	= DBout(date('Y-m-d H').':00:00');
					$endDate= DBout(date('Y-m-d H:i',strtotime('+1 month'.$today)));
					$sqlUp = sprintf("update user_package_assignment set end_date=%s where user_id=%s ",
                                    mysqli_real_escape_string($link,DBin($endDate)),
                                    mysqli_real_escape_string($link,DBin($userID))
                        );
					$resUp = mysqli_query($link,$sqlUp);
					$subject = $appSettings['success_payment_email_subject'];
					$to		 = $row['email'];
					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
					$msg	 = $appSettings['success_payment_email'];
					$FullName= 'Admin';
					sendEmail($subject,$to,$from,$msg,$FullName);

					$appSettings = getAppSettings($userID,true);
					$subject = $appSettings['payment_noti_subject'];
					$to		 = $appSettings['admin_email'];
					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
					$msg	 = str_replace('%email%',$row['email'],$appSettings['payment_noti_email']);
					$FullName= 'Admin';
					sendEmail($subject,$to,$from,$msg,$FullName);
					
				}else{
					$subject = $appSettings['failed_payment_email_subject'];
					$to		 = $row['email'];
					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
					$msg	 = $appSettings['failed_payment_email'];
					$FullName= 'Admin';
					sendEmail($subject,$to,$from,$msg,$FullName);
					$appSettings = getAppSettings($userID,true);
					$subject = $appSettings['payment_noti_subject'];
					$to		 = $appSettings['admin_email'];
					$from	 = 'admin@'.$_SERVER['SERVER_NAME'];
					$msg	 = str_replace('%email%',$row['email'],$appSettings['payment_noti_email']);
					$FullName= 'Admin';
					sendEmail($subject,$to,$from,$msg.'. Payment status is '.$_REQUEST['payment_status'],$FullName);
				}
			}
		}

		$sql5 = sprintf("insert into payment_history	
                                (
                                            business_email,
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
                                         '%s',
                                         '%s',
                                         '%s',
                                         '%s',
                                         '%s',
                                         '%s',
                                         '%s',
                                         '%s',
                                         '%s'
                             )",
                                        mysqli_real_escape_string($link,DBin($_REQUEST['business'])),
                                        mysqli_real_escape_string($link,DBin($_REQUEST['payer_status'])),
                                        mysqli_real_escape_string($link,DBin($_REQUEST['payer_email'])),
                                        mysqli_real_escape_string($link,DBin($_REQUEST['txn_id'])),
                                        mysqli_real_escape_string($link,DBin($_REQUEST['payment_status'])),
                                        mysqli_real_escape_string($link,DBin($_REQUEST['payment_gross'])),
                                        mysqli_real_escape_string($link,DBin($_REQUEST['item_name'])),
                                        mysqli_real_escape_string($link,DBin($row['parent_user_id'])),
                                        mysqli_real_escape_string($link,DBin($payment_processor))
                        );
		mysqli_query($link,$sql5);
	}
?>