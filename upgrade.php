<?php
	@session_start();
	//ini_set('display_errors','1');
	ini_set('max_execution_time','9000000');
	include_once("database.php");
	include_once("functions.php");
	$adminSettings = getAppSettings($_SESSION['user_id'],true);
	$old_ver = $adminSettings['version'];
	if(!isset($_GET['ver'])){
		$ver = $old_ver;
	}else{
		$ver = $_GET['ver'];
	}

	$url  = str_replace(array("http://","https://","www."),"",getServerURL());
	$time = time();
	echo '<body style="border: 1px solid #fff; width: 600px; height: 100px; margin: 100px auto;"><img src="images/loading.gif" style="float: left; margin-top: 20; margin-right: 20px"><h2>Please wait....</h2><hr></body>';
	$json = file_get_contents("http://updates.ranksol.com/app_updates/nimble_messaging_update/update.php?url=$url&ver=$ver&time=$time");
	if(isset($_GET['log']) && $_GET['log'] == "true"){ // log mysql file
		echo "<b>Json recieved</b>";
		echo $json;
		echo "<b>Old Version---$old_ver</b><hr>";
	}
	$arr = json_decode($json,true);
	
	if($arr['error']=='yes'){
		$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! </strong>'.$arr['reason'].'.</div>';
	}
	if($arr['error'] == "no"){
		if(is_array($arr['sql']) && count($arr['sql'])>0){
			foreach($arr['sql'] as $key => $val){
				$file = @file_get_contents("http://updates.ranksol.com/app_updates/nimble_messaging_update/sql/$val?time=$time");
				$queryArray = array();
				$queryArray = explode(';',$file);
				for($i=0;$i<count($queryArray);$i++){
					if(trim($queryArray[$i])!=''){
						mysqli_query($link,$queryArray[$i]);
					}
				}
				if(isset($_GET['log']) && $_GET['log'] == "true"){// log mysqlfile 
					echo "<b>MySql queries.</b><br>";
					echo $file;
					echo '<br>';
				}
			}
		}
		if(strlen($arr['zip'])>3){
			file_put_contents($arr['zip'], file_get_contents("http://updates.ranksol.com/app_updates/nimble_messaging_update/update/$arr[zip]?time=$time"));
			if(class_exists('ZipArchive')){
				$dir=dirname(__FILE__);
				$zip = new ZipArchive;
				$res = $zip->open("$arr[zip]");
				if($res === TRUE){
					$zip->extractTo("$dir/");
					$zip->close();
					if(isset($_GET['log']) && $_GET['log'] == "true"){ //log zip
						echo "<b>Zip------</b>".$arr['zip']."------<hr>";
					}
				}else{
					echo 'failed, code:' . $res;
				}
			}else{
				include_once('pclzip.lib.php');
				$archive = new PclZip($arr['zip']);
				$v_list=$archive->extract();
				if($v_list == 0){
					die("Error : ".$archive->errorInfo(true));
				}
				if(isset($_GET['log']) && $_GET['log'] == "true"){ //log zip lib
					echo "<b>Zip lib------</b>".$arr['zip']."------<hr>";
				}
			}
			@unlink($arr['zip']);
		}

		if(is_array($arr['del']) && count($arr['del'])>0){
			foreach($arr['del'] as $val_d){
				@unlink($val_d);
				if(isset($_GET['log']) && $_GET['log'] == "true"){ //log unlink						
					echo "<b>unlink------</b>".$val_d."------<hr>";
				}
			}
		}
		include_once("database.php");
		if(isset($arr['version']) && $arr['version'] !=""){
			$sql="update application_settings set version='$arr[version]'";
			mysqli_query($link,$sql);
			echo "<h2>".$_SESSION['message'] =  "Application has been Updated Successfully";
		}
	}
	if(!isset($_GET['log'])){
	    sleep(5);
		echo '<script>window.location.href="update_app.php"</script>';
	}
?>