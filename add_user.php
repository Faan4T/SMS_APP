<?php
session_start();
include_once("database.php");
include_once("functions.php");
$id  = decode(DBin($_REQUEST['pid']));
$uid = DBin($_REQUEST['uid']);


$sel = sprintf("select business_name from users where type='1'");
$exe = mysqli_query($link,$sel);
$adminData = mysqli_fetch_assoc($exe);
$businessName  = DBin($adminData['business_name']);


$appSettings = getAppSettings("",true);
$sql = sprintf("select * from package_plans where id='%s'",
            mysqli_real_escape_string($link,DBin($id))
    );
$res = mysqli_query($link,$sql);
if(mysqli_num_rows($res)){
	$row = mysqli_fetch_assoc($res);
}
$adminSettings = getAppSettings("",true);
if(isset($uid) && trim($uid)!=''){
?>
	<form method="post" action="server.php" enctype="multipart/form-data" id="upgradeuserpackage">
		<input type="hidden" name="pkg_id" value="<?php echo DBout($id)?>">
		<input type="hidden" name="pkg_price" value="<?php echo DBout($row['price'])?>">
		<input type="hidden" name="pkg_title" value="<?php echo DBout($row['title'])?>">
		<input type="hidden" name="user_id" value="<?php echo decode(DBout($uid))?>">
		<input type="hidden" name="cmd" value="upgrade_user_package">
	</form>
<script>document.forms["upgradeuserpackage"].submit();</script>
<?php
	die();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="images/favi.png">
<title><?php echo DBout($businessName); ?> - Get Started</title>

<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
<link href="assets/css/animate.min.css" rel="stylesheet"/>
</head>
<body class="fixed-left">
<div id="wrapper">
<div class="topbar margin-top-20">
	<div class="topbar-left">
		<div class="text-center"><span>
        <?php if(trim($appSettings['app_logo'])==''){?>
		<img src="images/installer_logo.png" />
		<?php }else{?>
		<img src="images/<?php echo trim(DBout($appSettings['app_logo']))?>">
		<?php }?>
        </span></div>
	</div>
</div>

<div class="content-page margin-0-auto">
	<div class="content">
		<div class="container"> 
			<div class="row">
				<div class="col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-lg-12">
								<h4 class="m-t-0 header-title"><b>Please provide information below</b></h4>
	<p class="text-muted font-13">
		You're just one step away.
	</p>
	<?php
		if(trim($_REQUEST['message'])!=''){
			echo DBout($_REQUEST['message']);
			$_REQUEST['message']='';
		}
        
        if(isset($_SESSION['message']) && $_SESSION['message']!=""){
            echo DBout($_SESSION['message']);
        }
        unset($_SESSION['message']);
        
        if(isset($_SESSION['authnet_response']) && $_SESSION['authnet_response']==1){ ?>
            <div class="alert alert-success"><?php echo DBout($_SESSION['authnet_msg'])?></div>

       <?php }else if(isset($_SESSION['authnet_response']) && $_SESSION['authnet_response']==0){ ?>
            <div class="alert alert-danger"><?php echo DBout($_SESSION['authnet_msg'])?></div>
<?php
        }
        unset($_SESSION['authnet_msg']);
        unset($_SESSION['authnet_response']);
	?>
	<div class="p-20">
		<h4>Your are singing up for the <span class="orange"><?php echo DBout($row['title'])?></span> @ <span class="red"><?php echo DBout('$'.$row['price'])?> per month.</span></h4>
		<form method="post" enctype="multipart/form-data" action="server.php">
			<div class="form-group">
				<label>First Name</label>
				<input type="text" name="first_name" class="form-control" required>
			</div>
			<div class="form-group">
				<label>Last Name</label>
				<input type="text" name="last_name" class="form-control" required>
			</div>
			<div class="form-group">
				<label>Login Email</label>
				<input type="email" name="email" class="form-control" required>
			</div>
			<div class="form-group">
				<label>Business Name</label>
				<input type="text" name="business_name" class="form-control" required>
			</div>
			<div class="form-group">
				<label>Login Password</label>
				<input type="password" name="password" class="form-control" required>
			</div>
			<div class="form-group">
				<label>Re-type Password</label>
				<input type="password" name="retype_password" class="form-control" required>
			</div>
            
            <?php
            if(($row['price'] > 1) && ($appSettings['payment_processor']!=1)){
            ?>
            
            <div class="form-group">
				<label>Address</label>
				<input type="text" name="address" class="form-control" required>
			</div>
            <div class="form-group">
				<label>City</label>
				<input type="text" name="city" class="form-control" required>
			</div>
            <div class="form-group">
				<label>State</label>
				<input type="text" name="state" class="form-control" required>
			</div>
            <div class="form-group">
				<label>Zip</label>
				<input type="text" name="zip" class="form-control" required>
			</div>
            
            <div class="form-group">
				<label>Credit Card Number</label>
				<input type="text" name="card_number" class="form-control" required>
			</div>
            <div class="form-group">
				<label>CVC Security Code (Located on back of card)</label>
				<input type="text" name="cvv" class="form-control" required>
			</div>
            <div class="form-group">
				<label>Expiration Month (MM)</label>
				<input type="text" name="month" class="form-control" placeholder="05" required>
			</div>
            <div class="form-group">
				<label>Expiration Year (YY)</label>
				<input type="text" name="year" class="form-control" placeholder="20" required>
			</div>
            <?php
            }
            ?>
            
			<div class="form-group">
				<label class="checkbox"><input type="checkbox" name="tcap_ctia" value="1" class="margin-right-5 margin-left-0 relative" required >100% TPCA & CTIA Compliant</label>
				<label class="checkbox"><input type="checkbox" name="msg_and_data_rate" value="1" required class="margin-right-5 margin-left-0 relative">Msg & Data Rates May Apply</label>
				<label class="checkbox"><input type="checkbox" name="privacy_policy" value="1" required class="margin-right-5 margin-left-0 relative">T&C/Privacy Policy <a href="tandc.php">Read here</a></label>
                <label class="checkbox"><input type="checkbox" name="statement" value="1" required class="margin-right-5 margin-left-0 relative">Your billing statement will show a charge from Swyft Media Group, LLC</label>
			</div>
			
			<div class="form-group text-right m-b-0">
                <?php
                if($row['price'] < 1){ $cmd = "add_app_user_by_admin"; }else{ $cmd = "add_web_user"; }
                ?>
                <input type="hidden" name="cmd" value="<?php echo DBout($cmd); ?>" />
				<input type="hidden" name="pkg_id" value="<?php echo DBout($id)?>">
				<input type="hidden" name="pkg_price" value="<?php echo DBout($row['price'])?>">
				<input type="hidden" name="pkg_title" value="<?php echo DBout($row['title'])?>">
				<input type="hidden" name="parent_user_id" value="<?php echo DBout($row['user_id'])?>">
				<button class="btn btn-primary waves-effect waves-light" type="submit"> Sign up Now </button>
				<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
			</div>
		</form>
	</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer class="footer">
		<?php echo DBout($adminSettings['footer_customization']); ?>
	</footer>
</div>