<?php
	error_reporting(0);
	session_start();
	if($_SESSION['user_id']==''){
		header("location:index.php");
	}
	include_once("database.php");
	include_once("functions.php");
	if(file_exists("update_script.php")){
		include_once("update_script.php");
		unlink("update_script.php");
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
		$appVersion	  = DBout($appSettings['version']);
		if(trim($appVersion)=='')
			$appVersion = '1.1.0';
			
		$updateResult = getUpdateDetails($appVersion);
		$upResult = json_decode($updateResult);
		
		$latestVersion = $upResult->version;
		$updateError   = $upResult->error;
		
		if(($updateError=="invalid") || ($updateError=="")){
			$displayUpdate  = "none";
		}else{
			$displayUpdate  = "";
		}
		$Latestupdates = $upResult->updates;
	}else{
		$displayUpdate  = "none";	
	}

	if(trim($userInfo['business_name'])==''){
		$business_name = DBout("SMS Machine");
	}else{
		$business_name = DBout($userInfo['business_name']);
	}
	$maxLength = '140';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<link rel="icon" type="image/png" href="images/favi.png">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title><?php echo DBout($business_name); ?></title>
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<meta name="viewport" content="width=device-width" />
<?php
if(basename($_SERVER['PHP_SELF'])!="create_pages.php"){
    ?>
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
    <?php
}
?>    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="css/ranksol.css">
<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/animate.min.css" rel="stylesheet"/>
<link href="assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>
    <link href="assets/css/demo.css" rel="stylesheet" />
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href='//fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
<link href="assets/css/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" href="css/ranksol_hazii.css">
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script>
    var max_lenght = '<?php echo $maxLength; ?>';
</script>
    <script src="scripts/header.js"></script>
</head>
<body>
<div class="wrapper">