<?php 
	include_once("database.php");
	include_once("functions.php");
	if($_REQUEST['txn_type']=='subscr_payment'){
		$custom = explode('_',DBin($_REQUEST['custom']));
		$pkgID  = DBout($custom[0]);
		$userID = $custom[1];
		$sel = sprintf("select * from package_plans where id=%s ",
                mysqli_real_escape_string($link,DBin($pkgID))
            );
		$exe = mysqli_query($link,$sel);
		if(mysqli_num_rows($exe)){
			$row = mysqli_fetch_assoc($exe);
			$startDate= date('Y-m-d H').':00:00';
			$endDate= date('Y-m-d H:i',strtotime('+1 month'.$today));
			$up = sprintf(
			    "update user_package_assignment set 
                            start_date='%s',
                             end_date='%s', 
                             pkg_id='%s',
                              sms_credits=sms_credits+'%s',
                               phone_number_limit='%s', 
                               pkg_country='%s'
                                where user_id=%s ",
                    mysqli_real_escape_string($link,DBin($startDate)),
                    mysqli_real_escape_string($link,DBin($endDate)),
                    mysqli_real_escape_string($link,DBin($pkgID)),
                    mysqli_real_escape_string($link,DBin($row['sms_credits'])),
                    mysqli_real_escape_string($link,DBin($row['phone_number_limit'])),
                    mysqli_real_escape_string($link,DBin($row['country'])),
                    mysqli_real_escape_string($link,DBin($userID))
                );
			mysqli_query($link,$up);
			$sql4 = sprintf("insert into payment_history	
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
			mysqli_query($link,$sql4);
		}
	}
?>