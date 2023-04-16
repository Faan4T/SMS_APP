<?php

error_reporting(0);
session_start();
if($_SESSION['rndir']=='true'){
	unset($_SESSION['rndir']);
	$_SESSION['rndir'] = '';
	rename('installer','_installer');
}
if(file_exists("installer/index.php")){
	header("location: installer/index.php");
	die();
}
if($_SESSION['user_id']!=''){
	header("location:dashboard.php");	
}
include_once("database.php");
include_once("functions.php");

$sql_adm = "select business_name from users where type='1'";
$res_adm = mysqli_query($link,$sql_adm);
$row_adm = mysqli_fetch_assoc($res_adm);
$business_name = $row_adm['business_name'];
if(trim($business_name)==""){
    $business_name= "SMS Machine";
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link rel="shortcut icon" href="images/favi.png">
<title><?php echo ($business_name); ?></title>
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<meta name="viewport" content="width=device-width" />
   <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/animate.min.css" rel="stylesheet"/>
    <link href="assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>
    <link href="assets/css/demo.css" rel="stylesheet" />
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/ranksol.css">
    
    <!-- For multiselect dropdown-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />


</head>
<body>
  
<?php
$adminSettings= getAppSettings("",true);
$sidebarColor = DBout($adminSettings['sidebar_color']);
$colors = array("purple"=>"#9368E9",
"blue"=>"#1F77D0",
"azure"=>"#1DC7EA",
"green"=>"#87CB16",
"orange"=>"#FFA534",
"red"=>"#FB404B",
"#1A4180"=>"#1A4180"
);

$sidebarColor = $colors[$adminSettings['sidebar_color']];

?>
<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">
	<div class="card-box">
		<div class="panel-heading">
			<h3 class="text-center"> Sign In to <strong style="color:<?php echo DBout($sidebarColor); ?>;" ><?php echo DBout($business_name); ?></strong> </h3>
			<?php
				if((isset($_SESSION['message'])) && (trim($_SESSION['message'])!='')){
					echo $_SESSION['message'];
					unset($_SESSION['message']);
				}
			?>
		</div>
		<div class="panel-body">
			<form class="form-horizontal m-t-20" action="server.php?cmd=login" method="post">
				<div class="form-group ">
					<div class="col-xs-12">
						<input class="form-control" type="email" required placeholder="Registered email address" name="username">
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12">
						<input class="form-control" type="password" required placeholder="Password" name="password">
					</div>
				</div>
				<div class="form-group text-center m-t-40">
					<div class="col-xs-12">
						<button class="btn btn-block text-uppercase waves-effect waves-light apniclass white" type="submit" style="background:<?php echo DBout($sidebarColor); ?>">Log In</button>
					</div>
				</div>
				<div class="form-group m-t-30 m-b-0">
					<div class="col-sm-12">
						<a href="forgot_password.php" class="text-dark purple"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
						<a href="pricing_plans.php" class="text-dark move-right purple">SignUp?</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</body>
</html>