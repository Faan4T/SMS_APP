<?php include_once '../functions.php'?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
    <link rel="stylesheet" href="../css/ranksol_hazii.css">

    <link rel="shortcut icon" href="images/favi.png">
<title>Nimble Messaging</title>

<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<style>
		label{
			display: block;
		}
	</style>
</head>

<body class="fixed-left">

<div id="wrapper" class="padding-top-5-per">
<div class="topbar"> 
	
  	<div class="topbar-left">
		<div class="text-center"><span><img src="../images/installer_logo.png"></span></div>
	</div>
</div>

<div class="content-page margin-0-auto">
	<div class="content">
		<div class="container"> 
			<div class="row">
				<div class="col-sm-12">
					<div class="card-boxs">
						<div class="row">
							<div class="col-lg-12">
								<h4 class="m-t-0 header-title"><b>Please provide your personal information below</b></h4>
	<p class="text-muted font-13">
		Provide personal information below for application admin account.
	</p>
	<?php
		include_once("../database.php");
		if(trim($_REQUEST['message'])!=''){
			echo DBout($_REQUEST['message']);
			$_REQUEST['message']='';
		}
	?>
	<div class="p-20">
		<h4>SignUp here for <span class="orange">Application Admin</span> account.<span class="red"></span></h4>
		<form method="post" enctype="multipart/form-data" action="../server.php">
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
				<label>Time Zone</label>
				<select name="time_zone" class="form-control" required>
					<?php
						$sqlt = sprintf("select * from time_zones");
						$rest = mysqli_query($link,$sqlt)or die(mysqli_error($link));
						if(mysqli_num_rows($rest)){
							while($rowt = mysqli_fetch_assoc($rest)){
							if($row['time_zone']==$rowt['time_zone']){
								$selected = 'selected="selected"';
							}else{
								$selected = '';
							} ?>
								<option <?php echo DBout($selected)?> value="<?php echo DBout($rowt['time_zone'])?>"><?php echo DBout($rowt['time_zone_value'])?></option>
                    <?php
							}
						}else{ ?>
                    <option value="">No time zone added.</option>
                    <?php
						}
					?>
				</select>
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
			<div class="form-group">
				<label><input type="checkbox" name="tcap_ctia" value="1" required> 100% TPCA & CTIA Compliant</label>
				<label><input type="checkbox" name="msg_and_data_rate" value="1" required> Msg & Data Rates May Apply</label>
				<label><input type="checkbox" name="privacy_policy" value="1" required> T&C/Privacy Policy <a href="tandc.php">Read here</a></label>
			</div>
			
			<div class="form-group text-right m-b-0">
				<input type="hidden" name="app_version" value="2.5.2">
				<input type="hidden" name="cmd" value="add_admin_account" />
				<button class="btn btn-primary waves-effect waves-light" type="submit"> Register Account </button>
				<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"> Cancel </button>
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
		Powered by <a href="http://ranksol.com" target="_blank">ranksol.com</a> copyright@2017
	</footer>
</div>