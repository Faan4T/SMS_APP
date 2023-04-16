<?php
	include_once("database.php");
	include_once("functions.php");
	if($_REQUEST['payment_status']=="Completed" || $_REQUEST['payment_status']=="1"){
		$custom = explode('_',DBin($_REQUEST['custom']));
		$quantity = DBout($custom[0]);
		$userID	  = DBout($custom[1]);
		
        if(isset($_REQUEST['payment_processor']) && $_REQUEST['payment_processor']==2){
            $payment_processor = DBin($_REQUEST['payment_processor']);
        }else{
            $payment_processor = 1;
        }
        
        $sql_chk = sprintf("select * from payment_history where txn_id = %s",
                    mysqli_real_escape_string($link,DBin($_REQUEST['txn_id']))
            );
        $exe_chk = mysqli_query($link,$sql_chk);
        if(mysqli_num_rows($exe_chk)==0)
        {
            $sql = sprintf("insert into payment_history
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
						mysqli_real_escape_string($link,DBin($_REQUEST['mc_gross'])),
						mysqli_real_escape_string($link,DBin($_REQUEST['item_name'])),
						mysqli_real_escape_string($link,DBin($userID)),
                        mysqli_real_escape_string($link,DBin($payment_processor))

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
                            mysqli_real_escape_string($link,$quantity)
                );
    		$resRollover = mysqli_query($link,$sqlrollover);
    		if($resRollover){
    			$sqlUpdate = sprintf("update user_package_assignment set sms_credits=sms_credits+'%s' where user_id=%s",
                                mysqli_real_escape_string($link,DBin($quantity)),
                                mysqli_real_escape_string($link,DBin($userID))
                    );
    			$resUpdate = mysqli_query($link,$sqlUpdate);
    		}
        }

	}
?>