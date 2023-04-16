<?php

	include_once("header.php");

	include_once("left_menu.php");

	$month = DBout(date("Y-m-d",strtotime("-11 days")));

	$endDay= DBout(date("M-d"));

	$smsIn = 0;

	$smsOut= 0;

	$data  = '';

	for($i=0;$i<=11;$i++){

		$day = DBout(date("M-d-y",strtotime($month."+".$i." day")));

		$curDate = DBout(date("Y-m-d",strtotime($month."+".$i." day")));

		

		$sqlIn = sprintf("select id from sms_history where user_id=%s and date(created_date)='%s' and direction='in-bound'",

                        mysqli_real_escape_string($link,filterVar($_SESSION['user_id'])),

                        mysqli_real_escape_string($link,filterVar($curDate))

            );

		$resIn = mysqli_query($link,$sqlIn);

		$smsIn = DBout(mysqli_num_rows($resIn));

		

		$sqlOut = sprintf("select id from sms_history where user_id=%s and date(created_date)='%s' and direction='out-bound' and ( is_sent = 'true' or is_sent = true) ",

                    mysqli_real_escape_string($link,filterVar($_SESSION['user_id'])),

                    mysqli_real_escape_string($link,filterVar($curDate))

            );

		$resOut = mysqli_query($link,$sqlOut);

		$smsOut = DBout(mysqli_num_rows($resOut));

		$data .= "['".date($appSettings['app_date_format'],strtotime($day))."' , ".DBout($smsIn)." , ".DBout($smsOut)."],";

		if($day == $endDay)

			exit();

	}

	$data = DBout(trim($data,','));


	$payment_data = mysqli_query($link,"SELECT subscribers.id, subscribers.amount_to_be_paid , SUM(subscribers_payments.amount) AS amount FROM subscribers 
	LEFT JOIN subscribers_payments on subscribers.id = subscribers_payments.subscriber_id
	GROUP BY subscribers.id");

	$total_paid_customer=0;
	$total_unpaid_customer=0;
	$total_partial_paid_customer=0;
	$total_amount_paid=0;
	
	
	

	while($row = mysqli_fetch_assoc($payment_data)){

		if ($row['amount_to_be_paid'] == $row['amount']){
			$total_paid_customer++;
		} 
		if ($row['amount'] == NULL){
			$total_unpaid_customer++;
		}
		if ($row['amount_to_be_paid'] > $row['amount'] && $row['amount'] != NULL){
			$total_partial_paid_customer++;
		}

		$total_amount_paid += (float)$row['amount'];
	}

?>

<div class="main-panel">

	<?php include_once('navbar.php');?>

	<?php if($_SESSION['user_type']=='1'){?>

	<div class="content">

		<div class="container-fluid">

			<div class="row">

				<div class="col-lg-3 col-md-6">

					<div class="panel panel-primary">

						<div class="panel-heading">

							<div class="row">

								<div class="col-xs-3">

									<i class="fa fa-group fa-5x"></i>

								</div>

								<div class="col-xs-9 text-right">

									<div class="huge"><?php echo DBout(getTotalGroups($_SESSION['user_id']))?></div>

									<div>Campaigns</div>

								</div>

							</div>

						</div>

						<a href="view_clients.php">

							<div class="panel-footer">

								<span class="pull-left">View Details</span>

								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

								<div class="clearfix"></div>

							</div>

						</a>

					</div>

				</div>

				<div class="col-lg-3 col-md-6">

					<div class="panel panel-green">

						<div class="panel-heading">

							<div class="row">

								<div class="col-xs-3">

									<i class="fa fa-bullhorn fa-5x"></i>

								</div>

								<div class="col-xs-9 text-right">

									<div class="huge"><?php echo DBout(getTotalAutoresponders($_SESSION['user_id']))?></div>

									<div>Autoresponders</div>

								</div>

							</div>

						</div>

						<a href="view_autores.php">

							<div class="panel-footer">

								<span class="pull-left">View Details</span>

								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

								<div class="clearfix"></div>

							</div>

						</a>

					</div>

				</div>

				<div class="col-lg-3 col-md-6">

					<div class="panel panel-yellow">

						<div class="panel-heading">

							<div class="row">

								<div class="col-xs-3">

									<i class="fa fa-user fa-5x"></i>

								</div>

								<div class="col-xs-9 text-right">

									<div class="huge"><?php echo DBout(getTotalActiveSubscribers($_SESSION['user_id']))?></div>

									<div>Customers</div>

								</div>

							</div>

						</div>

						<a href="view_subscribers.php">

							<div class="panel-footer">

								<span class="pull-left">View Details</span>

								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

								<div class="clearfix"></div>

							</div>

						</a>

					</div>

				</div>

				<div class="col-lg-3 col-md-6">

					<div class="panel panel-red">

						<div class="panel-heading">

							<div class="row">

								<div class="col-xs-3">

									<i class="fa fa-user-times fa-5x"></i>

								</div>

								<div class="col-xs-9 text-right">

									<div class="huge"><?php echo DBout(getTotalBlockedSubscribers($_SESSION['user_id']))?></div>

									<div>Unsubscribers</div>

								</div>

							</div>

						</div>

						<a href="block_subscribers.php">

							<div class="panel-footer">

								<span class="pull-left">View Details</span>

								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

								<div class="clearfix"></div>

							</div>

						</a>

					</div>

				</div>

                <div class="col-lg-3 col-md-6">

                    <div class="panel panel-yellow">

                        <div class="panel-heading">

                            <div class="row">

                                <div class="col-xs-3">

                                    <i class="fa fa-user fa-5x"></i>

                                </div>

                                <div class="col-xs-9 text-right">

                                    <div class="huge"><?php echo DBout(getAllClients($_SESSION['user_id']))?></div>

                                    <div>Clients</div>

                                </div>

                            </div>

                        </div>

                        <a href="view_company.php">

                            <div class="panel-footer">

                                <span class="pull-left">View Details</span>

                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                <div class="clearfix"></div>

                            </div>

                        </a>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <div class="panel panel-green">

                        <div class="panel-heading">

                            <div class="row">

                                <div class="col-xs-3">

                                    <i class="fa fa-money fa-5x"></i>

                                </div>

                                <div class="col-xs-9 text-right">

                                    <div class="huge"><?php echo $total_paid_customer ?></div>

                                    <div>Paid Customer</div>

                                </div>

                            </div>

                        </div>

                        <a href="paid_customers.php">

                            <div class="panel-footer">

                                <span class="pull-left">View Details</span>

                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                <div class="clearfix"></div>

                            </div>

                        </a>

                    </div>

                </div>

				<div class="col-lg-3 col-md-6">

                    <div class="panel panel-green">

                        <div class="panel-heading">

                            <div class="row">

                                <div class="col-xs-3">

                                    <i class="fa fa-money fa-5x"></i>

                                </div>

                                <div class="col-xs-9 text-right">

                                    <div class="huge"><?php echo $total_partial_paid_customer; ?></div>

                                    <div>Partial Paid Customers</div>

                                </div>

                            </div>

                        </div>

                        <a href="partialpaid_customers.php">

                            <div class="panel-footer">

                                <span class="pull-left">View Details</span>

                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                <div class="clearfix"></div>

                            </div>

                        </a>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <div class="panel panel-primary">

                        <div class="panel-heading">

                            <div class="row">

                                <div class="col-xs-3">

                                    <i class="fa fa-user-times fa-5x"></i>

                                </div>

                                <div class="col-xs-9 text-right">

                                    <div class="huge"><?php echo $total_unpaid_customer; ?></div>

                                    <div>UnPaid Customer</div>

                                </div>

                            </div>

                        </div>

                        <a href="unpaid_customers.php">

                            <div class="panel-footer">

                                <span class="pull-left">View Details</span>

                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                <div class="clearfix"></div>

                            </div>

                        </a>

                    </div>

                </div>

				<div class="col-lg-3 col-md-6">

                    <div class="panel panel-red">

                        <div class="panel-heading">

                            <div class="row">

                                <div class="col-xs-3">

                                    <i class="fa fa-user-times fa-5x"></i>

                                </div>

                                <div class="col-xs-9 text-right">

                                    <div class="huge"><?php echo '$'.$total_amount_paid;?></div>

                                    <div>Paid Amount</div>

                                </div>

                            </div>

                        </div>

                        <a href="paid_amount.php">

                            <div class="panel-footer">

                                <span class="pull-left">View Details</span>

                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                <div class="clearfix"></div>

                            </div>

                        </a>

                    </div>

                </div>

			</div>

			<div class="row">

				<div class="col-md-12">

					<div class="card ">

						<div class="content ct-chart" id="chartActivity">

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

		<?php }?>

	<?php include_once("footer_info.php");?>

		<?php if($_SESSION['user_type']=='2'){?>

			<div class="content">

		<div class="container-fluid">

			<div class="row">

				<div class="col-lg-3 col-md-6">

					<div class="panel panel-red">

						<div class="panel-heading">

							<div class="row">

								<div class="col-xs-3">

									<i class="fa fa-group fa-5x"></i>

								</div>

								<div class="col-xs-9 text-right">

									<div class="huge"><?php echo DBout(getTotalGroups($_SESSION['user_id']))?></div>

									<div>Campaigns</div>

								</div>

							</div>

						</div>

						<a href="view_clients.php">

							<div class="panel-footer">

								<span class="pull-left">View Details</span>

								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

								<div class="clearfix"></div>

							</div>

						</a>

					</div>

				</div>

				<!--<div class="col-lg-3 col-md-6">-->

				<!--	<div class="panel panel-green">-->

				<!--		<div class="panel-heading">-->

				<!--			<div class="row">-->

				<!--				<div class="col-xs-3">-->

				<!--					<i class="fa fa-bullhorn fa-5x"></i>-->

				<!--				</div>-->

				<!--				<div class="col-xs-9 text-right">-->

				<!--					<div class="huge"><?php echo DBout(getTotalAutoresponders($_SESSION['user_id']))?></div>-->

				<!--					<div>Autoresponders</div>-->

				<!--				</div>-->

				<!--			</div>-->

				<!--		</div>-->

				<!--		<a href="view_autores.php">-->

				<!--			<div class="panel-footer">-->

				<!--				<span class="pull-left">View Details</span>-->

				<!--				<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>-->

				<!--				<div class="clearfix"></div>-->

				<!--			</div>-->

				<!--		</a>-->

				<!--	</div>-->

				<!--</div>-->

				<div class="col-lg-3 col-md-6">

					<div class="panel panel-yellow">

						<div class="panel-heading">

							<div class="row">

								<div class="col-xs-3">

									<i class="fa fa-user fa-5x"></i>

								</div>

								<div class="col-xs-9 text-right">

									<div class="huge"><?php echo DBout(getTotalActiveSubscribers($_SESSION['user_id']))?></div>

									<div>Customers</div>

								</div>

							</div>

						</div>

						<a href="view_subscribers.php">

							<div class="panel-footer">

								<span class="pull-left">View Details</span>

								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

								<div class="clearfix"></div>

							</div>

						</a>

					</div>

				</div>

				<div class="col-lg-3 col-md-6">

					<div class="panel panel-red">

						<div class="panel-heading">

							<div class="row">

								<div class="col-xs-3">

									<i class="fa fa-user-times fa-5x"></i>

								</div>

								<div class="col-xs-9 text-right">

									<div class="huge"><?php echo DBout(getTotalBlockedSubscribers($_SESSION['user_id']))?></div>

									<div>Unsubscribers</div>

								</div>

							</div>

						</div>

						<a href="block_subscribers.php">

							<div class="panel-footer">

								<span class="pull-left">View Details</span>

								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

								<div class="clearfix"></div>

							</div>

						</a>

					</div>

				</div>

                <!--<div class="col-lg-3 col-md-6">-->

                <!--    <div class="panel panel-yellow">-->

                <!--        <div class="panel-heading">-->

                <!--            <div class="row">-->

                <!--                <div class="col-xs-3">-->

                <!--                    <i class="fa fa-user fa-5x"></i>-->

                <!--                </div>-->

                <!--                <div class="col-xs-9 text-right">-->

                <!--                    <div class="huge"><?php echo DBout(getAllClients($_SESSION['user_id']))?></div>-->

                <!--                    <div>Clients</div>-->

                <!--                </div>-->

                <!--            </div>-->

                <!--        </div>-->

                <!--        <a href="view_company.php">-->

                <!--            <div class="panel-footer">-->

                <!--                <span class="pull-left">View Details</span>-->

                <!--                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>-->

                <!--                <div class="clearfix"></div>-->

                <!--            </div>-->

                <!--        </a>-->

                <!--    </div>-->

                <!--</div>-->

                <div class="col-lg-3 col-md-6">

                    <div class="panel panel-green">

                        <div class="panel-heading">

                            <div class="row">

                                <div class="col-xs-3">

                                    <i class="fa fa-money fa-5x"></i>

                                </div>

                                <div class="col-xs-9 text-right">

                                    <div class="huge"><?php echo DBout(getAllPaidSubscribers($_SESSION['user_id']))?></div>

                                    <div>Paid Customer</div>

                                </div>

                            </div>

                        </div>

                        <a href="paid_customers.php">

                            <div class="panel-footer">

                                <span class="pull-left">View Details</span>

                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                <div class="clearfix"></div>

                            </div>

                        </a>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <div class="panel panel-primary">

                        <div class="panel-heading">

                            <div class="row">

                                <div class="col-xs-3">

                                    <i class="fa fa-user-times fa-5x"></i>

                                </div>

                                <div class="col-xs-9 text-right">

                                    <div class="huge"><?php echo  $total_amount_paid; ?></div>

                                    <div>UnPaid Customer</div>

                                </div>

                            </div>

                        </div>

                        <a href="unpaid_customers.php">

                            <div class="panel-footer">

                                <span class="pull-left">View Details</span>

                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                <div class="clearfix"></div>

                            </div>

                        </a>

                    </div>

                </div>

				<!--<div class="col-lg-3 col-md-6">-->

    <!--                <div class="panel panel-red">-->

    <!--                    <div class="panel-heading">-->

    <!--                        <div class="row">-->

    <!--                            <div class="col-xs-3">-->

    <!--                                <i class="fa fa-user-times fa-5x"></i>-->

    <!--                            </div>-->

    <!--                            <div class="col-xs-9 text-right">-->

    <!--                                <div class="huge"><?php echo '$'.getTotalPaidAmount($_SESSION['user_id']);?></div>-->

    <!--                                <div>Paid Amount</div>-->

    <!--                            </div>-->

    <!--                        </div>-->

    <!--                    </div>-->

    <!--                    <a href="paid_amount.php">-->

    <!--                        <div class="panel-footer">-->

    <!--                            <span class="pull-left">View Details</span>-->

    <!--                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>-->

    <!--                            <div class="clearfix"></div>-->

    <!--                        </div>-->

    <!--                    </a>-->

    <!--                </div>-->

    <!--            </div>-->

			</div>

			<div class="row">

				<div class="col-md-12">

					<div class="card ">

						<div class="content ct-chart" id="chartActivity">

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

	<?php }?>

</div>

<?php include_once("footer.php");?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>

    var data2 = [<?php echo DBout($data);?>];

</script>

<script src="scripts/dashboard.js"></script>