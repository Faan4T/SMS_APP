<?php
	@error_reporting(0);
	@session_start();
	if(isset($_SESSION['user_id']))
	if($_SESSION['user_id']==''){
		header("location:index.php");
	}
	include_once("database.php");
	include_once("functions.php");
	if(file_exists("update_script.php")){
		include_once("update_script.php");
		@unlink("update_script.php");
	}
?>
<?php
	/******* Global Vars ********/
	$appSettings   = getAppSettings($_SESSION['user_id']);
	$adminSettings = getAppSettings("",true);
	$pageName 	   = getCurrentPageName();
	$userInfo	   = getUserInfo($_SESSION['user_id']);
	/******* Global Vars end ********/
	if($appSettings!=false){
		$timeZone = $appSettings['time_zone'];
		date_default_timezone_set($timeZone);
	}

	$pkgStatus = checkUserPackageStatus($_SESSION['user_id']);
	if($pkgStatus['go']==false)
		$notification = true;
	else
		$notification = false;

	if($appSettings!=false){
		$appVersion	  = $appSettings['version'];
		if(trim($appVersion)=='')
			$appVersion = '1.1.0';

		$updateResult = getUpdateDetails($appVersion);
		$upResult = json_decode($updateResult);

		$latestVersion = @$upResult->version;
		$updateError   = @$upResult->error;

		if(($updateError=="invalid") || ($updateError=="")){
			$displayUpdate  = "none";
		}else{
			$displayUpdate  = "";
		}
		$Latestupdates = @$upResult->updates;
	}else{
		$displayUpdate  = "none";
	}

	$displayUpdate  = "none"; // temp


	if(isset($userInfo['business_name'])==''){
		$business_name = "SMS";
	}else{
		$business_name = $userInfo['business_name'];
	}
?>
<!doctype html>
<html lang="en">
<head>
<style>
.notification{
	background-color:red;
	color:#ffffff;
	padding:10px;
	border-radius:7px;
}
.notification-list{
	max-height:none !important
}
.showCount{
	color:#7E57C2 !important
}
.btn-grey{
	-webkit-border-radius: 5;
	-moz-border-radius: 5;
	-o-border-radius: 5;
	border-radius: 5px;
	color: #999999;
	font-size: 16px;
	background: #fff;
	padding: 5px 10px 5px 10px;
	border: solid #eeeeee 1px;
	text-decoration: none;
}
.btn-grey:hover{
	background: #7E57C2;
	text-decoration: none;
	color:#fff;
}
.btn-pages-active{
	font-family: Arial;
	color: #ffffff;
	font-size: 16px;
	background: #7E57C2 !important;
	padding: 8px 10px 8px 10px;
	text-decoration: none;
	border: solid #7E57C2 1px;
}
.btn-pages-active:hover{
	background: #1B53B7;
	text-decoration: none;
	border: solid #7E57C2 1px;
}
.btn-pages-inactive{
	font-family: Arial;
	color: #999999;
	font-size: 16px;
	background: #FFFFFF;
	padding: 8px 10px 8px 10px;
	text-decoration: none;
	border: solid #eeeeee 1px;
}
.btn-pages-inactive:hover{
	background: #EEEEEE;
	text-decoration: none;
	color:#2A6496;
}
#google_translate_element{
	margin-top:13% !important
}
.goog-te-gadget-icon{
	display:none !important
}
.goog-te-gadget-simple{
	border:none !important
}
.listTable > thead > tr > td{
	text-align:left;
}


.bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    .b-example-divider {
      height: 3rem;
      background-color: rgba(0, 0, 0, .1);
      border: solid rgba(0, 0, 0, .15);
      border-width: 1px 0;
      box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
    }

    .b-example-vr {
      flex-shrink: 0;
      width: 1.5rem;
      height: 100vh;
    }

    .bi {
      vertical-align: -.125em;
      fill: currentColor;
    }

    .nav-scroller {
      position: relative;
      z-index: 2;
      height: 2.75rem;
      overflow-y: hidden;
    }

    .nav-scroller .nav {
      display: flex;
      flex-wrap: nowrap;
      padding-bottom: 1rem;
      margin-top: -1px;
      overflow-x: auto;
      text-align: center;
      white-space: nowrap;
      -webkit-overflow-scrolling: touch;
    }
</style>
<meta charset="utf-8" />
<link rel="icon" type="image/png" href="images/favi.png">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title><?php echo $business_name; ?></title>
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<meta name="viewport" content="width=device-width" />
<?php
if(basename($_SERVER['PHP_SELF'])!="create_pages.php"){
    ?>
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
    <?php
    /*
    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    */

}
?>

<link rel="stylesheet" href="./Foundation-Sites-CSS/css/foundation.css" />
<link rel="stylesheet" href="./Foundation-Sites-CSS/css/foundation.min.css" />
<link rel="stylesheet" href="./Foundation-Sites-CSS/css/app.css" />
<script src="Foundation-Sites-CSS/js/vendor/jquery.js"></script>
<script src="Foundation-Sites-CSS/js/vendor/foundation.js"></script>
<script src="Foundation-Sites-CSS/js/vendor/foundation.min.js"></script>
<script src="Foundation-Sites-CSS/js/app.js"></script>

<link href="./assets/css/bootstrap.min.css" rel="stylesheet">
 <!-- Custom styles for this template -->
 <link href="./assets/css/dashboard.css" rel="stylesheet">

<!-- Animation library for notifications -->
<link href="./assets/css/animate.min.css" rel="stylesheet"/>

<!--  Light Bootstrap Table core CSS -->
<link href="./assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>

<!--  CSS for Demo Purpose, don't include it in your project     -->
<link href="./assets/css/demo.css" rel="stylesheet" />

<!--     Fonts and icons     -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<!--
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
-->
<link href='//fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<link href="./assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
<link href="./assets/css/jquery-ui.css" rel="stylesheet">
<!--Slim scroll -->
<!--
<link href="css/prettify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="assets/js/prettify.js"></script>
<script type="text/javascript" src="assets/js/jquery.slimscroll.js"></script>
-->
<!--<link href="css/slim_scroll_style.css" type="text/css" rel="stylesheet" />-->
<!--Slim scroll end -->

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script>
	function goBack(step='-1'){
		window.history.go(""+step+"");
	}
	function googleTranslateElementInit(){
		new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
	}
	var maxLength = '<?php echo @$maxLength?>';
</script>
</head>
<body>
<div class="wrapper">
