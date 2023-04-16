<?php
	include_once("header.php");
	include_once("left_menu.php");

	$appSettings= getAppSettings(1,true);
	$amount  	= $appSettings['per_credit_charges'];
	$quantity = DBin($_REQUEST['credit_quantity']);
	$amount = DBin(round($amount*$quantity,2)*100);
?>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								Proceed with payment.
							</h4>
							<p class="category">Enter credit details and buyer email.</p>
						</div>
						<div class="content table-responsive">
							<form action="add_stripe_credits.php?amt=<?php echo DBout($amount); ?>&user_id=<?php echo DBout($_SESSION['user_id']); ?>&q=<?php echo DBout($quantity); ?>" method="POST">
							  <script
								src="https://checkout.stripe.com/checkout.js" class="stripe-button"
								data-key="<?php echo DBout($appSettings['stripe_publishable_key']); ?>"
								data-amount="<?php echo DBout($amount); ?>"
								data-name="Buy SMS Credits"
								data-description="Add Credits"
								data-image="<?php echo DBout(getServerUrl(),ENT_COMPAT) ?>/images/nimble_messaging.png"
								data-locale="auto">
							  </script>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>