<?php
session_start();
include_once("database.php");
include_once("functions.php");
$uid = DBin($_REQUEST['uid']);
$sel = sprintf("select business_name from users where type='1'");
$exe = mysqli_query($link,$sel);
if(mysqli_num_rows($exe)){
	$adminData = mysqli_fetch_assoc($exe);
	$businessName  = DBout($adminData['business_name']);
}else{
	$businessName  = DBout('Nimble Messaging');
}
$adminSettings= getAppSettings("",true);
?>
<!DOCTYPE HTML>
<html>
<head>
<title><?php echo DBout($businessName); ?></title>
<link href="css/pricing_style.css" rel='stylesheet' type='text/css' />
<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic' rel='stylesheet' type='text/css'>
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<meta name="viewport" content="width=device-width" />
</head>
<body>
<div class="pricing-plans">
	<div class="wrap">
		<div class="price-head">
			<h1>Choose a package to signup</h1>
		</div>
		<?php        
        if(isset($_SESSION['authnet_response']) && $_SESSION['authnet_response']==1){ ?>
            <div class="alert alert-success"><?php echo DBout($_SESSION['authnet_msg'])?></div>
        <?php   }
        else if(isset($_SESSION['authnet_response']) && $_SESSION['authnet_response']==0){ ?>
            <div class="alert alert-danger"><?php echo DBout($_SESSION['authnet_msg'])?></div>

        <?php   }else if(isset($_SESSION['message']) && trim($_SESSION['message'])!=''){
			echo $_SESSION['message'];
		}
		unset($_SESSION['message']);
        unset($_SESSION['authnet_msg']);
        unset($_SESSION['authnet_response']);
        ?>
		<div class="pricing-grids">
			<?php
				$id = DBin(decode($_REQUEST['id']));
				if(trim($id)==""){
					$sel = "select id from users where type='1'";
					$exe = mysqli_query($link,$sel);
					$r   = mysqli_fetch_assoc($exe);
					$id	 = DBout($r['id']);
				}
                
                $AppSettings = getAppSettings($id);
                if($AppSettings['payment_processor'] == "3"){
                    $sql = sprintf("select * from package_plans where user_id=%s ",
                            mysqli_real_escape_string($link,DBin($id))
                        );
                }else{
                    $sql = sprintf("select * from package_plans where user_id=%s ",
                                mysqli_real_escape_string($link,DBin($id))
                    );
                }
                
				$res = mysqli_query($link,$sql);
				$totalRecords = mysqli_num_rows($res);
				if($totalRecords>0){
					$records = 0;
					$styles = array('pricing-grid1','pricing-grid2','pricing-grid3');
					while($row = mysqli_fetch_assoc($res)){
						$styleKey = array_rand($styles,1);
						$mainClass = $styles[$styleKey];
						if($mainClass=='pricing-grid1'){
							$index = '';
							$saleBox = DBout('sale-box');
							$cart = DBout('cart1');
						}else if($mainClass=='pricing-grid2'){
							$index = DBout('two');
							$saleBox = DBout('sale-box two');
							$cart = DBout('cart2');
						}else if($mainClass=='pricing-grid3'){
							$index = DBout('three');
							$saleBox = DBout('sale-box three');
							$cart = DBout('cart3');
							
						}
						if(($records+1)==$totalRecords){
							$margin = DBout('0px;');
						}else{
							$margin = DBout('16px');
						}
						$records++;
			?>
			<div class="<?php echo DBout($mainClass)?>" style="margin-right: <?php echo DBout($margin)?>">
				<div class="price-value <?php echo DBout($index)?>">
					<h2><a href="#"> <?php echo DBout(strtoupper($row['title']))?> </a></h2>
					<h5><span>$ <?php echo DBout($row['price']);?></span>
						<label> / month</label>
                    </h5>
					<div class="<?php echo DBout($saleBox)?>"> <span class="on_sale title_shop">NEW</span> </div>
				</div>
				<div class="price-bg">
					<ul>
						<li class="whyt"><a href="#">Available SMS Credits <b><?php echo DBout($row['sms_credits'])?></b></a></li>
						<li><a href="#">Allowed Phone Numbers <b><?php echo DBout($row['phone_number_limit'])?></b></a></li>
						<li class="whyt"><a href="#">Released Date <b><?php echo DBout(date('F/d/Y',strtotime($row['created_date'])))?></b></a></li>
						<li class="whyt"><a href="#">24/7 Support</a></li>
					</ul>
					<?php
                    if($AppSettings['payment_processor'] == "3"){
                        ?>
                        <form action="create_subscription_form.php?id=<?php echo DBout($row['id']); ?>" method="POST">
                          <script
                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="<?php echo DBout($AppSettings['stripe_publishable_key']) ?>"
                            data-image=""
                            data-name="<?php echo DBout($businessName)?>"
                            data-description="<?php echo DBout($row['title']) ?>"
                            data-label="Purchase">
                          </script>
                        </form>
                        <?php
                    }
                    else{
                        ?>
                        <div class="<?php echo DBout($cart)?>">
                            <a class="popup-with-zoom-anim" href="add_user.php?pid=<?php echo DBout(encode($row['id']))?>&uid=<?php echo DBout($uid)?>">
                                Purchase
                            </a>
                        </div>
                        <br>
                        <?php
                    }
                    ?>
				</div>
			</div>
			<?php
					}
				}else{?>
					<h1 class="red">No plans created by admin.</h1>
			<?php	}
			?>
			<div class="clear"></div>
		</div>
		<div class="clear"> </div>
	</div>
</div>
<div class="footer">
	<div class="wrap">
		<?php echo DBout($adminSettings['footer_customization']); ?>
	</div>
</div>
</body>
</html>