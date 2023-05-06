<?php

	error_reporting(0);

	function getTotalPaidAmount($userID){

		global $link;

        $sql = "select sum(s.amount_to_be_paid) as totalPaid from subscribers s, subscribers_group_assignment sga where s.id = sga.subscriber_id and sga.payment_status = 1";

        $query = mysqli_query($link,$sql);

        $row = mysqli_fetch_assoc($query);

		return $row['totalPaid'];

	}

    function getAllUnPaidSubscribers(){

        global $link;

        $sql = "select * from subscribers s, subscribers_group_assignment sga where s.id = sga.subscriber_id and sga.payment_status = 0";

        $query = mysqli_query($link,$sql) or die(mysqli_error($link));

        return mysqli_num_rows($query);

    }

	function getAllPaidSubscribers(){

	    global $link;

	    $sql = "select * from subscribers s, subscribers_group_assignment sga where s.id = sga.subscriber_id and sga.payment_status = 1";

	    $query = mysqli_query($link,$sql) or die(mysqli_error($link));

	    return mysqli_num_rows($query);

    }

	function getAllClients($user_id){

	    global $link;

        $sql = sprintf("select * from companies where user_id=%s ",

            mysqli_real_escape_string($link,filterVar($user_id)));

        $query = mysqli_query($link,$sql);

        return mysqli_num_rows($query);

    }

	function getSubscriberDetailsByNumber($subscriberNumber){

		global $link;

		$sql = "select * from subscribers where phone_number='".$subscriberNumber."'";

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			return mysqli_fetch_assoc($res);

		}

	}

	function getCompanyDetails($companyID){

		global $link;

		$sql = "select * from companies where id='".$companyID."'";

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			return mysqli_fetch_assoc($res);

		}

	}

	function LogErrors($data){

		$myFile = "logs.txt";

		$fh = fopen($myFile, 'a');

		fwrite($fh, $data);

		fclose($fh);

	}

	function handleGDPRKeyword($userID,$from,$to,$smsSid,$appSettings){

		global $link;

		$userID = DBin($userID);

		$from = DBin($from);

		$to = DBin($to);

		$smsSid = DBin($smsSid);

		$appSettings = DBin($appSettings);

		date_default_timezone_set($appSettings['time_zone']);

		$sql = sprintf("select id from subscribers where phone_number=%s  and user_id=%s",

                    mysqli_real_escape_string($link,DBin($from)),

                    mysqli_real_escape_string($link,DBin($userID))

            	);

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			$row = mysqli_fetch_assoc($res);

			$profileUrl = getServerUrl().'/gdpr.php?subid='.encode($row['id']);

			$gdPrMessage = str_replace('%gdpr_link%',$profileUrl,$appSettings['gdpr_message']);

			sendMessage($to,$from,DBout($gdPrMessage),array(),$userID,"");



			date_default_timezone_set($appSettings['time_zone']);

			$today = date('Y-m-d H:i');

			$sql = sprintf("

                                select id from bound_phones where 

                                to_number='".DBin($from)."' and 

                                user_id='".DBin($row['user_id'])."' and 

                                date_format(lease_date, '%Y-%m-%d %H:%i') > '".DBin($today)."'"

                );

			$exe = mysqli_query($link,$sql);

			if(mysqli_num_rows($exe)==0){

			    $sql1 = sprintf("delete from bound_phones where to_number=%s and user_id=%s",

                            mysqli_real_escape_string($link,DBin($from)),

                            mysqli_real_escape_string($link,DBin($userID))

                    );

				mysqli_query($link,$sql1);

				$ins = sprintf("insert into bound_phones 

					(

					to_number,

					from_number,

					user_id,

					lease_date,

					what_is_sent

					)

					values

					(

					'%s',

					'%s',

					'%s',

					'%s',

					'%s'

					)",



                    mysqli_real_escape_string($link,DBin($from)),

                    mysqli_real_escape_string($link,DBin($to)),

                    mysqli_real_escape_string($link,DBin($userID)),

                    mysqli_real_escape_string($link,DBin(date('Y-m-d H:i:s',strtotime("+5 minutes")))),

					mysqli_real_escape_string($link,DBin($profileUrl))

                    );

				mysqli_query($link,$ins);

			}else{

			    $sql2 = sprintf("delete from bound_phones where to_number=%s and user_id=%s",

                        mysqli_real_escape_string($link,DBin($from)),

                        mysqli_real_escape_string($link,DBin($userID))

                    );

				mysqli_query($link,$sql2);

				$ins = sprintf("insert into bound_phones 

					(

					to_number,

					from_number,

					user_id,

					lease_date,

					what_is_sent

					)

					values

					(

					'%s',

					'%s',

					'%s',

					'%s',

					'%s')",

                    mysqli_real_escape_string($link,DBin($from)),

                    mysqli_real_escape_string($link,DBin($to)),

                    mysqli_real_escape_string($link,DBin($userID)),

                    mysqli_real_escape_string($link,DBin(date('Y-m-d H:i:s',strtotime("+5 minutes")))),

                    mysqli_real_escape_string($link,DBin($profileUrl))

                    );

                ;

				mysqli_query($link,$ins);

			}



		}

	}

	function setTimeZone($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select time_zone from application_settings where user_id=%s",

                    mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			$row = mysqli_fetch_assoc($res);

			$timeZone = $row['time_zone'];

			if(trim($timeZone)!=''){

				date_default_timezone_set($timeZone);

			}

		}

	}

	function isMediaExists($media){



		if(trim($media)!=''){

			$fileCheckArray = explode('/',$media);

			$fileCheckName  = end($fileCheckArray);

			$filePath = getServerUrl().'/uploads/'.$fileCheckName;

			if(file_exists('uploads/'.$fileCheckName)){

				return '<img src="'.$media.'" width="30" height="30" />';

			}

		}

	}



	function getTotalBlockedSubscribers($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select id from subscribers where user_id=%s and status='2'",

                    mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		return mysqli_num_rows($res);

	}

	function getTotalActiveSubscribers($userID){

        $userID = DBin($userID);

		global $link;

		$sql = sprintf("select id from subscribers where user_id=%s and status='1'",

                    mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		return mysqli_num_rows($res);

	}

	function getTotalAutoresponders($userID){

        $userID = DBin($userID);

		global $link;

		$sql = sprintf("select id from campaigns where user_id=%s and type='2'",

                    mysqli_real_escape_string($link,DBin($userID))

                );

		$res = mysqli_query($link,$sql);

		return mysqli_num_rows($res);

	}

	function getTotalGroups($userID){

        $userID = $userID;

		global $link;

		$sql = sprintf("select id from campaigns where user_id=%s and type='6'",

            mysqli_real_escape_string($link,DBin($userID))

        );

		$res = mysqli_query($link,$sql);

		return mysqli_num_rows($res);

	}

	function getDeviceInfo($deviceID){

	    $deviceID = DBin($deviceID);

		global $link;

		$sql = sprintf("select * from mobile_devices where id=%s",

                    mysqli_real_escape_string($link,DBin($deviceID))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			return mysqli_fetch_assoc($res);

		}else{

			return 'false';

		}

	}

	function bitlyLinkShortner($url,$userID){

		$appSettings = getAppSettings($userID,true);

		if(trim($appSettings['bitly_token'])!=''){

			$url= "https://api-ssl.bitly.com/v3/shorten?access_token=".$appSettings['bitly_token']."&longUrl=".urlencode($url);

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,$url);

			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$request = curl_exec($ch);

			$request = json_decode($request,true);

			return $request['data']['url'];

			curl_close($ch);

		}else{

			return $url;

		}

	}

	function getPhoneNumberDetails($phone){

	    $phone = DBin($phone);

		global $link;

		$sql = sprintf("select * from users_phone_numbers where phone_number=%s",

                mysqli_real_escape_string($link,DBin($phone))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			return mysqli_fetch_assoc($res);

		}else{

			return 'invalid To phone number.';

		}

	}

	function ResizeImage(

				$file,

				$string = null,

				$width = 0,

				$height = 0,

				$proportional = false,

				$output = 'file',

				$delete_original = true,

				$use_linux_commands = false,

				$quality = 100,

				$grayscale = false

  		 	){

	    $file = DBin($file);

	    $string = DBin($string);

	    $width = DBin($width);

	    $height = DBin($height);

	    $proportional = DBin($proportional);

	    $output = DBin($output);

	    $delete_original = DBin($delete_original);

	    $use_linux_commands = DBin($use_linux_commands);

	    $quality = DBin($quality);

	    $grayscale = DBin($grayscale);

		if($height <= 0 && $width <= 0) return false;

		if($file === null && $string === null) return false;



		$info = $file !== null ? getimagesize($file) : getimagesizefromstring($string);

		$image = '';

		$final_width = 0;

		$final_height = 0;

		list($width_old,$height_old) = $info;

		$cropHeight = $cropWidth = 0;



		if($proportional){

			if($width == 0)$factor = $height/$height_old;

			elseif($height == 0)$factor = $width/$width_old;

			else $factor = min( $width / $width_old, $height / $height_old );



			$final_width  = round($width_old * $factor);

			$final_height = round($height_old * $factor);

		}else{

			$final_width = ($width <= 0) ? $width_old : $width;

			$final_height= ($height <= 0 ) ? $height_old : $height;

			$widthX = $width_old / $width;

			$heightX = $height_old / $height;



			$x = min($widthX, $heightX);

			$cropWidth = ($width_old - $width * $x) / 2;

			$cropHeight = ($height_old - $height * $x) / 2;

		}

		switch($info[2]){

			case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;

			case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;

			case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;

			default: return false;

		}

		if($grayscale){

			imagefilter($image, IMG_FILTER_GRAYSCALE);

		}



		$image_resized = imagecreatetruecolor($final_width, $final_height);

		if(($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)){

			$transparency = imagecolortransparent($image);

			$palletsize = imagecolorstotal($image);



			if ($transparency >= 0 && $transparency < $palletsize) {

			$transparent_color  = imagecolorsforindex($image, $transparency);

			$transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);

			imagefill($image_resized, 0, 0, $transparency);

			imagecolortransparent($image_resized, $transparency);

			}

			elseif ($info[2] == IMAGETYPE_PNG) {

			imagealphablending($image_resized, false);

			$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);

			imagefill($image_resized, 0, 0, $color);

			imagesavealpha($image_resized, true);

			}

		}

		imagecopyresampled($image_resized,$image,0,0,$cropWidth,$cropHeight,$final_width,	$final_height,$width_old - 2 * $cropWidth,$height_old - 2 * $cropHeight);



		if($delete_original){

			if($use_linux_commands)

				exec('rm '.$file);

			else

				unlink($file);

		}



		switch(strtolower($output)){

			case 'browser':

				$mime = image_type_to_mime_type($info[2]);

				header("Content-type: $mime");

				$output = NULL;

			break;

			case 'file':

				$output = $file;

			break;

			case 'return':

				return $image_resized;

			break;

				default:

			break;

		}



		switch($info[2]){

			case IMAGETYPE_GIF: imagegif($image_resized,$output); break;

			case IMAGETYPE_JPEG: imagejpeg($image_resized,$output,$quality); break;

			case IMAGETYPE_PNG:

			$quality = 9 - (int)((0.9*$quality)/10.0);

			imagepng($image_resized, $output, $quality);

			break;

			default: return false;

		}

		return true;

		/**

		* easy image resize function

		* @param  $file - file name to resize

		* @param  $string - The image data, as a string

		* @param  $width - new image width

		* @param  $height - new image height

		* @param  $proportional - keep image proportional, default is no

		* @param  $output - name of the new file (include path if needed)

		* @param  $delete_original - if true the original image will be deleted

		* @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink

		* @param  $quality - enter 1-100 (100 is best quality) default is 100

		* @param  $grayscale - if true, image will be grayscale (default is false)

		* @return boolean|resource

		*/

	}

	function subscriberLookUp($sid,$token,$number,$numberID){

	    $sid = DBin($sid);

	    $token = DBin($token);

	    $number = DBin($number);

	    $numberID = DBin($numberID);

		global $link;

		$url = "https://lookups.twilio.com/v1/PhoneNumbers/".$number."?Type=carrier&Type=caller-name";

		$ch = curl_init();

		curl_setopt($ch,CURLOPT_USERPWD,"$sid:$token");

		curl_setopt($ch, CURLOPT_URL,$url);

		curl_setopt($ch, CURLOPT_HTTPGET, true );

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');

		$request = curl_exec($ch);

		curl_close($ch);

		$response = json_decode($request,true);

		$callerName = $response['caller_name']['caller_name'];

		$callerType = $response['caller_name']['caller_type'];

		$countryCode= $response['country_code'];

		$carrierName= $response['carrier']['name'];

		$carrierType= $response['carrier']['type'];

		$mobCountryCode = $response['carrier']['mobile_country_code'];

		$mobNetworkCode = $response['carrier']['mobile_network_code'];

		if(trim($carrierName)!=''){

			$sql = sprintf("update subscribers set

						first_name='%s',

						caller_type='%s',

						country_code='%s',

						carrier_name='%s',

						carrier_type='%s',

						mobile_country_code='%s',

						mobile_network_code='%s'

					where

						id='%s'",

                        mysqli_real_escape_string($link,DBin($callerName)),

                        mysqli_real_escape_string($link,DBin($callerType)),

                        mysqli_real_escape_string($link,DBin($mobCountryCode)),

                        mysqli_real_escape_string($link,DBin($carrierName)),

                        mysqli_real_escape_string($link,DBin($carrierType)),

                        mysqli_real_escape_string($link,DBin($mobCountryCode)),

                        mysqli_real_escape_string($link,DBin($mobNetworkCode)),

                        mysqli_real_escape_string($link,DBin($numberID))

                );

			mysqli_query($link,$sql);

		}

		return $response;

	}

	function updatePassword($userID,$password){

	    $userID = DBin($userID);

	    $password = DBin($password);

		global $link;

		$pass = encodePassword($password);

		$sql = sprintf("update users set password=%s where id=%s",

                    mysqli_real_escape_string($link,DBin($pass)),

                    mysqli_real_escape_string($link,DBin($userID))

            );

		mysqli_query($link,$sql);

	}

	function encodePassword($str){

	    $str = DBin($str);

		for($i=0; $i<2; $i++){

			$str=strrev(base64_encode($str));

		}

		return $str;

	}

	function decodePassword($str){

		for($i=0; $i<2; $i++){

			$str=base64_decode(strrev($str));

		}

		return $str;

	}

	function handleStartKeyword($userID,$from,$to,$smsSid){

	    $userID = DBin($userID);

	    $from = DBin($from);

	    $to = DBin($to);

	    $smsSid = DBin($smsSid);

		global $link;

		$up = sprintf("update subscribers set status='1' where phone_number='%s' and user_id=%s",

                    mysqli_real_escape_string($link,DBin($from)),

                    mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$up);

		if(mysqli_affected_rows($link))

			$sql = sprintf("insert into sms_history

						(

							to_number,

							from_number,

							text,

							sms_sid,

							direction,

							user_id

						)

					values

						(

							'%s',

							'%s',

							'start',

							'%s',

							'in-bound',

							'%s'

						)",

                            mysqli_real_escape_string($link,DBin($to)),

                            mysqli_real_escape_string($link,DBin($from)),

                            mysqli_real_escape_string($link,DBin($smsSid)),

                            mysqli_real_escape_string($link,DBin($userID))

                );

			mysqli_query($link,$sql);

		die();

	}

    function getProtectedValues($obj,$name){

	    $obj = DBin($obj);

	    $name = DBin($name);

        $array = (array)$obj;

        $prefix = chr(0).'*'.chr(0);

        return $array[$prefix.$name];

    }

	function isValidMd5($md5=''){

	    $md5 = DBin($md5);

		return preg_match('/^[a-f0-9]{32}$/', $md5);

	}

	function validImageExtensions(){

		return array('png','jpg','jpeg','gif','bmp');

	}

	function numberLookUp($sid,$token,$number){

	    $sid = DBin($sid);

	    $token = DBin($token);

	    $number = DBin($number);

		$url = "https://$sid:$token@lookups.twilio.com/v1/PhoneNumbers/".$number."?Type=carrier";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);

		curl_setopt($ch, CURLOPT_HTTPGET, true );

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');

		$request = curl_exec($ch);

		curl_close($ch);

		$response = json_decode($request,true);

		return $response;

	}

	function getUpdateDetails($version){

	    $version = DBin($version);

		$url = "http://updates.ranksol.com/app_updates/nimble_messaging_update/update.php?ver=".$version;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);

		curl_setopt($ch, CURLOPT_HTTPGET, true);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');

		$data = curl_exec($ch);

		curl_close($ch);

		return $data;

	}

	function post_curl_mqs($url,$data){

	    $url = DBin($url);

	    $data = DBin($data);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);

		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');

		$data = curl_exec($ch);

		curl_close($ch);

		return $data;

	}

	function getBulkSMS($smsID){

	    $smsID = DBin($smsID);

		global $link;

		$sql = sprintf("select * from bulk_sms where id=%s",

                        mysqli_real_escape_string($link,DBin($smsID))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			return mysqli_fetch_assoc($res);

		}else

			return '';

	}

    function getBulkMedia($smsID){

	    $smsID = DBin($smsID);

		global $link;

		$sql = sprintf("select bulk_media from bulk_sms where id=%s",

                    mysqli_real_escape_string($link,DBin($smsID))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			$row = mysqli_fetch_assoc($res);

			return $row['bulk_media'];

		}else

			return '';

	}

	function handleYesKeyword($userID,$from,$to,$smsSid){

	    $userID = DBin($userID);

	    $from = DBin($from);

	    $to = DBin($to);

	    $smsSid = DBin($smsSid);

		global $link;

		$sqln = sprintf("select id from subscribers where phone_number='%s' and user_id=%s",

                        mysqli_real_escape_string($link,DBin($from)),

                        mysqli_real_escape_string($link,DBin($userID))

            );

		$resn = mysqli_query($link,$sqln);

		if(mysqli_num_rows($resn)==0){

			die();

		}else{

			$rown = mysqli_fetch_assoc($resn);

			$subscriberID = $rown['id'];

			$sqla = sprintf("select id,group_id from subscribers_group_assignment where subscriber_id=%s and status='2' and user_id=%s order by id desc limit 1",

                            mysqli_real_escape_string($link,DBin($subscriberID)),

                            mysqli_real_escape_string($link,DBin($userID))

                );

			$resa = mysqli_query($link,$sqla);

			if(mysqli_num_rows($resa)==0){

				die();

			}else{

				$rowa = mysqli_fetch_assoc($resa);

				$groupID = $rowa['group_id'];

			}

			$sql = sprintf("insert into sms_history

				('%s','%s','yes','','%s','in-bound','%s','%s')",

                    mysqli_real_escape_string($link,DBin($to)),

                    mysqli_real_escape_string($link,DBin($from)),

                    mysqli_real_escape_string($link,DBin($smsSid)),

                    mysqli_real_escape_string($link,DBin($groupID)),

                    mysqli_real_escape_string($link,DBin($userID))



                );

			mysqli_query($link,$sql);

			$sql1 = sprintf("update subscribers set status='1' where id=%s and user_id=%s",

                    mysqli_real_escape_string($link,DBin($subscriberID)),

                    mysqli_real_escape_string($link,DBin($userID))

                );

			mysqli_query($link,$sql1);

			$sql2 = sprintf("update subscribers_group_assignment set status='1' where group_id=%s and subscriber_id=%s and user_id=%s",

                        mysqli_real_escape_string($link,DBin($groupID)),

                        mysqli_real_escape_string($link,DBin($subscriberID)),

                        mysqli_real_escape_string($link,DBin($userID))

                     );

			mysqli_query($link,$sql2);

			$sql3 = sprintf("delete from subscribers_group_assignment where subscriber_id=%s and user_id=%s and status='2'",

                    mysqli_real_escape_string($link,DBin($subscriberID)),

                    mysqli_real_escape_string($link,DBin($userID))

                );

			mysqli_query($link,$sql3);

			addFollowUpMessages($groupID,$userID,$subscriberID);

			creditCount($userID,'sms','in');



			$groupData = getGroupData($groupID);

			sendMessage($to,$from,DBout($groupData['double_optin_confirm_message']),array(),$groupData['user_id'],$groupData['id']);

			if($groupData['get_subs_name_check']=='1'){

				sendMessage($to,$from,DBout($groupData['msg_to_get_subscriber_name']),array(),$groupData['user_id'],$groupData['id']);

				boundNumber($to,$from,$userID,$groupData['id'],'sms');

				die();

			}

			if($groupData['get_email']=='1'){

				sendMessage($to,$from,DBout($groupData['reply_email']),array(),$groupData['user_id'],$groupData['id']);

				boundNumber($to,$from,$userID,$groupData['id'],'email');

				die();

			}

		}

		die();

	}

	function addFollowUpMessages($groupID,$userID,$subscriberID){

	    $groupID = DBin($groupID);

	    $userID = DBin($userID);

	    $subscriberID = DBin($subscriberID);

		global $link;

		$sqlf = sprintf("select * from follow_up_msgs where group_id=%s and user_id=%s",

                    mysqli_real_escape_string($link,DBin($groupID)),

                    mysqli_real_escape_string($link,DBin($userID))

            );

		$resf = mysqli_query($link,$sqlf);

		if(mysqli_num_rows($resf)){

			$appSettings = getAppSettings($userID);

			$timeZone	 = $appSettings['time_zone'];

			date_default_timezone_set($timeZone);

			$today = date('Y-m-d');

			while($rowf = mysqli_fetch_assoc($resf)){

				$delayDays = $rowf['delay_day'];

				$delayTime = $rowf['delay_time'];

				if($delayDays=='0'){

					$date	   = date('Y-m-d H:i:s',strtotime($delayTime.$today));

					$dateTime  = $date;

				}else{

					$date	   = date('Y-m-d',strtotime("+".$delayDays." days ".$today));

					$dateTime  = $date.' '.$delayTime.':00';

				}

				$message   = DBout($rowf['message']);

				$media	   = $rowf['media'];

				if(trim($message)!=''){

					$sqls = sprintf("insert into schedulers

					(

                            scheduled_time,

                            group_id,

                            phone_number,

                            message,

                            media,

                            scheduler_type,

                            user_id

					)

					values

					(

                            '%s',

                            '%s',

                            '%s',

                            '%s',

                            '%s',

                            '2',

                        '%s'

					)",

                            mysqli_real_escape_string($link,DBin($dateTime)),

                            mysqli_real_escape_string($link,DBin($groupID)),

                            mysqli_real_escape_string($link,DBin($subscriberID)),

                            mysqli_real_escape_string($link,DBin($message)),

                            mysqli_real_escape_string($link,DBin($media)),

                            mysqli_real_escape_string($link,DBin($userID))



                        );

					mysqli_query($link,$sqls);

				}

			}

		}

	}

	function removeMedia($filePath){

	    $filePath = DBin($filePath);

		$pathArray = explode('/',$filePath);

		end($pathArray);

		$key = key($pathArray);

		$fileName = $pathArray[$key];

		unlink('uploads/'.$fileName);

	}

	function checkUserPackageStatus($userID){

	    $userID = $userID;

		$array = array();



		if(isAdmin($userID)){

			$array['message'] = '';

			$array['go'] = true;

			$array['remaining_credits'] = 5000;

			return $array;

		}else{

			$creditsLeft = 0;

			$go = true;

			$userPackage = getAssingnedPackageInfo($userID);

			if($userPackage['status']=='1'){

				$today   = date('Y-m-d H:i');

				$endDate = date('Y-m-d H:i',strtotime($userPackage['end_date']));

				if($today > $endDate){

					$message = 'Your package plan has expired, please add more credits or buy a new plan.';

					$go = false;

				}else{

					if($userPackage['used_sms_credits'] >= $userPackage['sms_credits']){

						$message = 'Your sms credits are finished, please add more credits or buy a new plan.';

						$go = false;

					}else{

						$message = 'Success';

						$creditsLeft = ($userPackage['sms_credits'] - $userPackage['used_sms_credits']);

					}

				}

			}else{

				$message = 'Your package plan has suspended, please contact to administrator.';

				$go = false;

			}



			$array = array();

			$array['message'] = $message;

			$array['go'] = $go;

			$array['remaining_credits'] = $creditsLeft;

			return $array;

		}

	}

	function isAdmin($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select type from users where id=%s",

                    mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			$row = mysqli_fetch_assoc($res);

			if($row['type']=='1')

				return true;

			else

				return false;

		}

	}

	function checkUserNumberslimit($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select id from users_phone_numbers where user_id=%s",

                        mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		return mysqli_num_rows($res);

	}

	function getAssingnedPackageInfo($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select * from user_package_assignment where user_id=%s",

                        mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			return mysqli_fetch_assoc($res);

		}

	}

	function getPackageInfo($id){

	    $id = DBin($id);

		global $link;

		$sql = sprintf("select * from package_plans where id=%s",

                        mysqli_real_escape_string($link,DBin($id))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			return mysqli_fetch_assoc($res);

		}

	}

	function redirectToPaypal($userID,$pkgName,$pkgPrice,$webUserID,$pkgInfo=""){

		$appSettings = getAppSettings($userID);

        if($appSettings['payment_processor']==2){

            include_once("pay_with_authrize_recurring.php");

        }else if($appSettings['payment_processor']==4){

            include_once "pay_with_nmi_recurring.php";

        }else{

    		$redirectUrl = getServerUrl();

    		$notifyUrl   = getServerUrl().'/notify.php';

    		if($appSettings['paypal_switch']=='1'){

    			$endPoint	= 'https://www.paypal.com/cgi-bin/webscr';

    			$businessEmail = $appSettings['paypal_email'];

    		}else{

    			$endPoint	= 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    			$businessEmail = $appSettings['paypal_sandbox_email'];

    		}

    		echo DBout("Redirecting to paypal...");

    		?>

    		    <form action="<?php echo $endPoint?>" name="" method="post" id="recurring_payment_form">

    			<input type="hidden" value="<?php echo $businessEmail?>" name="business">

    			<input type="hidden" name="return" value="<?php echo $redirectUrl?>" />

    			<input type="hidden" name="cancel_return" value="<?php echo $notifyUrl?>" />

    			<input type="hidden" name="notify_url" value="<?php echo $notifyUrl?>" />

    			<input type="hidden" name="cmd" value="_xclick-subscriptions" />

    			<input type="hidden" name="no_note" value="1" />

    			<input type="hidden" name="no_shipping" value="1">

    			<input type="hidden" name="currency_code" value="USD">

    			<input type="hidden" name="country" value="IN" />

    			<input type="hidden" value="<?php echo $pkgName ?> SMS Plan" name="item_name">

    			<input type="hidden" name="a3" value="<?php echo $pkgPrice?>" />

    			<input type="hidden" name="p3" value="1" />

    			<input type="hidden" name="t3" value="M" />

    			<input type="hidden" name="src" value="1" />

    			<input type="hidden" name="sra" value="1" />

    			<input type="hidden" name="custom" value="<?php echo $webUserID?>" />

			<?php

				if($pkgInfo['is_free_days']=='1'){ ?>

					<input type="hidden" name="a1" value="0">

					<input type="hidden" name="p1" value="<?php echo $pkgInfo['free_days']?>">

					<input type="hidden" name="t1" value="D">

			<?php } ?>

			</form>

    		<script>document.forms["recurring_payment_form"].submit();</script>

       <?php }

	}

	function getGroupData($groupID){

	    $groupID = DBin($groupID);

		global $link;

		$sql = sprintf("select * from campaigns where id=%s",

                    mysqli_real_escape_string($link,DBin($groupID))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			return mysqli_fetch_assoc($res);

		}else{

			return false;

		}

	}

	function getAppSettings($userID,$isAdmin=false){

	    $userID = DBin($userID);

	    $isAdmin = DBin($isAdmin);

		global $link;

		if($isAdmin)

			$sql = sprintf("select * from application_settings where user_type='1'");

		else

			$sql = sprintf("select * from application_settings where user_id=%s",

                        mysqli_real_escape_string($link,DBin($userID))

                );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			return mysqli_fetch_assoc($res);

		}else

			return false;

	}

	function getAdminInfo(){

		global $link;

		$sql = sprintf("select * from users where type='1'");

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			return mysqli_fetch_assoc($res);

		}else{

			return array();

		}

	}

	function getUserInfo($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select * from users where id=%s",

                mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			return mysqli_fetch_assoc($res);

		}else{

			return array();

		}

	}

     function getTwilioInfo($userID){

		global $link;

		$sql = "select twilio_sid, twilio_token from application_settings where user_id='".$userID."'";

		$res = mysqli_query($link,$sql) or die(mysqli_error($link));

		if(mysqli_num_rows($res)){

			return mysqli_fetch_assoc($res);

		}else{

			return array();

		}

	}

	function specialCharacters(){

		return array("'",'"',',','@','|','<','>','.');

	}

	function makeSubscriberBlocked($to,$from,$body,$smsSid,$userID){

	    $to = DBin($to);

	    $from = DBin($from);

	    $body = DBin($body);

	    $smsSid = DBin($smsSid);

	    $userID = DBin($userID);

		global $link,$appSettings;

		$sql = sprintf("insert into sms_history

				(to_number,from_number,text,media,sms_sid,direction,group_id,user_id)values

				('%s','%s','%s','','%s','in-bound','','%s')",

                    mysqli_real_escape_string($link,DBin($to)),

                    mysqli_real_escape_string($link,DBin($from)),

                    mysqli_real_escape_string($link,DBin($body)),

                    mysqli_real_escape_string($link,DBin($smsSid)),

                    mysqli_real_escape_string($link,DBin($userID))

            );

		mysqli_query($link,$sql);

		creditCount($userID,'sms','in');

		$sql = sprintf("select id,status from subscribers where phone_number='%s' and user_id=%s",

                    mysqli_real_escape_string($link,DBin($from)),

                    mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			$row = mysqli_fetch_assoc($res);

			$id	 = $row['id'];

			if(isset($appSettings['time_zone']) && trim($appSettings['time_zone'])!=''){

				date_default_timezone_set($appSettings['time_zone']);

				$unSubDateTime = date('Y-m-d H:i:s');

			}else{

				$unSubDateTime = date('Y-m-d H:i:s');

			}

			$sql1 = sprintf("update subscribers set status='2', unsubscribe_date=%s where id=%s",

                    mysqli_real_escape_string($link,DBin($unSubDateTime)),

                    mysqli_real_escape_string($link,DBin($id))

                );

			mysqli_query($link,$sql1);

			$sql2 = sprintf("update subscribers_group_assignment set status='2' where subscriber_id=%s",

                        mysqli_real_escape_string($link,DBin($id))

                );

			mysqli_query($link,$sql2);

		}

	}

	function checkOptOutKeywords(){

		return array('stop','cancel','optout','opt-out','remove','quit','end');

	}

	function checkReserveKeywords(){

		return array('cancel','CANCEL','OPTOUT','optout','OPT-OUT','opt-out','remove','REMOVE','quit','QUIT','help','HELP','STOP','stop','START','start','END','end','RESERVE','reserve');

	}

	function addSubscriber($name="",$phoneNumber,$email="",$subsType="",$city="",$state="",$userID,$status,$customSubsInfo=''){

	    $name = DBin($name);

	    $phoneNumber = DBin($phoneNumber);

	    $email = DBin($email);

	    $subsType = DBin($subsType);

	    $city = DBin($city);

	    $state = DBin($state);

	    $userID = DBin($userID);

	    $status = DBin($status);

	    $customSubsInfo = DBin($customSubsInfo);

		global $link;

		$ins = sprintf("insert into subscribers

				(first_name,phone_number,city,state,user_id,status,email,subs_type,custom_info)values

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

                    mysqli_real_escape_string($link,DBin($name)),

                    mysqli_real_escape_string($link,DBin($phoneNumber)),

                    mysqli_real_escape_string($link,DBin($city)),

                    mysqli_real_escape_string($link,DBin($state)),

                    mysqli_real_escape_string($link,DBin($userID)),

                    mysqli_real_escape_string($link,DBin($status)),

                    mysqli_real_escape_string($link,DBin($email)),

                    mysqli_real_escape_string($link,DBin($subsType)),

                    mysqli_real_escape_string($link,DBin($customSubsInfo))

            );

		mysqli_query($link,$ins) or die(mysqli_error($link));

		return mysqli_insert_id($link);

	}

	function assignGroup($subID,$groupID,$userID,$status){

	    $subID = DBin($subID);

	    $groupID = DBin($groupID);

	    $userID = DBin($userID);

	    $status = DBin($status);

		global $link;

		$sql = sprintf("select id from subscribers_group_assignment where subscriber_id=%s and group_id=%s and user_id=%s",

                        mysqli_real_escape_string($link,DBin($subID)),

                        mysqli_real_escape_string($link,DBin($groupID)),

                        mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)==0){

		    $sql1 = sprintf("insert into subscribers_group_assignment

			(group_id,subscriber_id,user_id,status)values

			(%s,%s,%s,'%s')",

                    mysqli_real_escape_string($link,DBin($groupID)),

                    mysqli_real_escape_string($link,DBin($subID)),

                    mysqli_real_escape_string($link,DBin($userID)),

                    mysqli_real_escape_string($link,DBin($status))

                );

			mysqli_query($link,$sql1);

		}else{

			$row = mysqli_fetch_assoc($res);

			$sql2 = sprintf("update subscribers_group_assignment set

                                    status=%s

                                where

                                    id=%s",

                                mysqli_real_escape_string($link,DBin($status)),

                                mysqli_real_escape_string($link,DBin($row['id']))

                );

			mysqli_query($link,$sql2);

		}

	}

	function checkTrumpiaMessageStatus($messageID){

		global $link;

		$url = "http://api.trumpia.com/http/v2/checkresponse?request_id=".$messageID;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);

		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_HTTPGET, TRUE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');

		$data = curl_exec($ch);

		curl_close($ch);

		$data = json_decode($data,true);

		

		if($data['statuscode'] == '0'){

			$up = "update sms_history set

						is_sent='false',

						trumpia_error_code='".$data['errorcode']."',

						trumpia_error_msg='".$data['errormessage']."'

					where

						sms_sid='".$messageID."'";

			mysqli_query($link,$up);

		}else{

			// $up = "update sms_history set

			// 			is_sent='true',

			// 			trumpia_error_msg='".$data['message']."'

			// 		where

			// 			sms_sid='".$messageID."'";

			// mysqli_query($link,$up);

		}

		return $data;

	}

	function sendMessage($from,$to,$body,$media,$userID,$groupID="",$deviceID='',$isChat=false,$win_bit=0,$send_from=array(),$toname=null,$fromname=null){
			

	    global $link,$remainingCredits;

		

		$from = $from;

	    $to = $to;
	    
	    $fromname = $fromname;
	    
	    $toname = $toname;

	    $body = $body;

	    $media = $media;

	    $userID = $userID;

	    $groupID = $groupID;

	    $isChat = $isChat;

	    $win_bit = $win_bit;

		$settings	  = getAppSettings($userID);

		$adminSettings= getAppSettings('',true);

		$timeZone	= $settings['time_zone'];

		$appendText = $settings['append_text'];

		if(isset($timeZone)){

			date_default_timezone_set($timeZone);

		}

		$sentDate = date('Y-m-d H:i:s');

		$usersWords = DBout($settings['banned_words']);

		$adminBannedWords = DBout($adminSettings['banned_words']);

		$bannedWords = trim($usersWords,',').','.trim($adminBannedWords,',');

		$bannedWords = explode(',',$bannedWords);

		$bannedWords = array_map('trim',$bannedWords);

		$body = str_replace($bannedWords,'****',$body);

		if($isChat==false){

			$body .= ' '.$appendText;

		}

		if(is_array($media)){

			$media = $media[0];

		}

		$smsSid  = '';

		$msgType = '';

		$isSent  = 'false';

		$body = DBout($body);
		

		if($settings['sms_gateway']=='trumpia'){

			$remainingCredits = '5000';

		}



		if(true || $remainingCredits > 0){

			if(($from!='')&&($to!='')&&(trim($body)!='')){



				// Checking number status

				$check = "select status from subscribers where phone_number='".$to."' and user_id='".$userID."'";
				
				$resCheck = mysqli_query($link,$check);
				
				if( mysqli_num_rows($resCheck)){
					
					$rowCheck = mysqli_fetch_assoc($resCheck);
					
					if($rowCheck['status']=='1'){
						


						if($from=='mobile_sim'){
							
							if(trim($deviceID)!=''){

								$deviceInfo = getDeviceInfo($deviceID);

								$pathToFcmServer = 'https://fcm.googleapis.com/fcm/send';

								$fireBaseToken = $deviceInfo['device_token'];

								$androidAppServerKey = $adminSettings['android_app_server_key'];

								$headers = array(

									'Authorization:key=' .$androidAppServerKey,

									'Content-Type:application/json'

								);

								$toNumbers = array($to);

								$fields = array(

									'to' => $fireBaseToken,

									'data' => array('body'=>$body,'numbers'=>$toNumbers)

								);

								$payload = json_encode($fields);

								$curl_session = curl_init();

								curl_setopt($curl_session, CURLOPT_URL, $pathToFcmServer);

								curl_setopt($curl_session, CURLOPT_POST, true);

								curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);

								curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);

								curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);

								curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

								curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

								$result = curl_exec($curl_session);

								$result = json_decode($result,true);

								if($result['success']=='1'){

									$smsSid = $result['results'][0]['message_id'];

									$isSent = 'true';

								}else{

									$smsSid = $result['failure'];

								}

							}

							else{

								$smsSid = 'No active device found.';

							}

						}

						else{

							if($settings['sms_gateway']=='twilio'){

								$twilio_sid = $adminSettings['twilio_sid'];

								$twilio_token = $adminSettings['twilio_token'];

								$fromNumberInfo = getPhoneNumberDetails($from);

								

							if($fromNumberInfo['type']==4){

									if(trim($media)!=''){

										$data = array(

											"To" => "whatsapp:".$to,

											"From" => "whatsapp:".$from,

											"Body" => $body,

											"Media" => $media

										);

										 $msgType = 'mms';
										//$msgType  = '3';

									}else{

										$data = array(

											"To" => "whatsapp:".$to,

											"From" => "whatsapp:".$from,

											"Body" => $body

										);

										// $msgType = 'sms';
										$msgType  = '2'; //RECEITE PAYMENTS
									}

									$url  = "https://$twilio_sid:$twilio_token@api.twilio.com/2010-04-01/Accounts/$twilio_sid/Messages";

									$res = sendTwilioCurl($data,$url,"POST");

									if(isset($res->RestException->Code)){

										$smsSid = $res->RestException->Message;;

									}else{

										$smsSid = (string) $res->Message->Sid;

										$isSent = 'true';

									}

								}

								else{

									$enableSenderID = $settings['enable_sender_id'];

									if($enableSenderID=='1'){

										$senderID = $settings['twilio_sender_id'];

										$from = $senderID;

									}



									if(trim($media)!=''){

										$data = array(

											"To" => $to,

											"From" => $from,

											"Body" => $body,

											"MediaUrl" => $media

										);

										$msgType = 'mms';

									}else{

										$data = array(

											"To" => $to,

											"From" => $from,

											"Body" => $body

										);

										// $msgType = 'sms';
										$msgType  = '2'; //RECEITE PAYMENTS
									}


									// check and queue message if exceded limit

									$a = true;

									foreach ($send_from as $key) {
										if (twilio_total_msg($key,$to) < 3){
											$a = false;
											$from = $key;
											break;
										}
									}



									if ($a && twilio_total_msg($from,$to) > 3){

										insert_queue_msg($to,$from,$body,'',date('Y-m-d h:i:s'),1,$groupID);
										// echo "break";
										// return "Message Queued and send successfully";

									}
									// echo $from.' '.$to."\n";
									// echo "hello";
									// End limit check
									

									 $url  = "https://$twilio_sid:$twilio_token@api.twilio.com/2010-04-01/Accounts/$twilio_sid/Messages";

									// $res = sendTwilioCurl($data,$url,"POST");
									
									if(isset($res->RestException->Code)){

										$smsSid = $res->RestException->Message;

										$isSent='false';

									}else{

										$smsSid = (string) $res->Message->Sid;

										$isSent = 'true';

									}

								}

							}

							else if($settings['sms_gateway']=='plivo'){

								require_once("plivo/vendor/autoload.php");

								require_once("plivo/vendor/plivo/plivo-php/plivo.php");

								$p = new RestAPI($adminSettings['plivo_auth_id'], $adminSettings['plivo_auth_token']);

								$params = array(

									'src' => $from,

									'dst' => $to,

									'text' => $body

								);

								$msgType  = 'sms';

								$response = $p->send_message($params);

								if($response['status']=='202'){

									$smsSid = $response['response']['message_uuid'][0];

									$isSent = 'true';

								}else{

									$smsSid = $response['response']['error'];

								}

							}

							else if($settings['sms_gateway']=='nexmo'){

								$url = 'https://rest.nexmo.com/sms/json?' . http_build_query(array(

									  'api_key' =>  $adminSettings['nexmo_api_key'],

									  'api_secret' => $adminSettings['nexmo_api_secret'],

									  'to' => $to,

									  'from' => $from,

									  'text' => $body

								));

								$ch = curl_init($url);

								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

								$response = curl_exec($ch);

								$response = json_decode($response,true);

								if($response['messages'][0]['status']=='0'){

									$smsSid = $response['messages'][0]['message-id'];

									$isSent = 'true';

								}else{

									$smsSid = $response['messages'][0]['error-text'];

								}

								 $msgType  = 'sms';

							}

							else if($settings['sms_gateway']=='trumpia'){

								$pos = strpos($to,"+");

								if($pos === false){}

								else{

									$to = substr($to,2);

								}

								$url = $url = "http://api.trumpia.com/http/v2/sendverificationsms?apikey=".$settings['trumpia_api_key']."&mobile_number=".$to."&message=".urlencode($body);

								$ch = curl_init();

								curl_setopt($ch, CURLOPT_URL,$url);

								curl_setopt($ch, CURLOPT_POST, true);

								curl_setopt($ch, CURLOPT_HTTPGET, TRUE);

								//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

								curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');

								$data = curl_exec($ch);

								curl_close($ch);

								$data = json_decode($data,true);

								if($data['requestID']!=''){

									$smsSid = $data['requestID'];

									$isSent = 'false';

									$msgType = '1';

								}else{

									$isSent = 'false';

								}

							}

						}

						if($isChat==false){

							$company_id = mysqli_fetch_assoc(mysqli_query($link,"select * from campaigns where id=".$groupID))['company_id'];

    						$sql = sprintf("insert into sms_history

    									(

    										to_number,

    										from_number,

    										text,

    										media,

    										sms_sid,

    										direction,

    										group_id,

    										user_id,

    										created_date,

    										is_sent,

    										win_bit,

    										type,

											Company_id,
											
											to_name,

    										from_name

    									)

    								values

    									(

    										'%s',

    										'%s',

    										'%s',

    										'%s',

    										'%s',

    										'out-bound',

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

    									mysqli_real_escape_string($link,DBin($to)),

    									mysqli_real_escape_string($link,DBin($from)),

    									mysqli_real_escape_string($link,DBin($body)),

    									mysqli_real_escape_string($link,DBin($media)),

    									mysqli_real_escape_string($link,DBin($smsSid)),

    									mysqli_real_escape_string($link,DBin($groupID)),

    									mysqli_real_escape_string($link,DBin($userID)),

    									mysqli_real_escape_string($link,DBin($sentDate)),

    									mysqli_real_escape_string($link,DBin($isSent)),

    									mysqli_real_escape_string($link,DBin($win_bit)),

    									mysqli_real_escape_string($link,DBin($msgType)),

										mysqli_real_escape_string($link,DBin($company_id)),

										mysqli_real_escape_string($link,DBin($toname)),

										mysqli_real_escape_string($link,DBin($fromname))

    						);  
							
    						mysqli_query($link,$sql)or die(mysqli_error($link));
    						
						}

						creditCount($userID,$msgType,'out');

						return $smsSid;

					}else{

						$smsSid = 'Number exists with deleted status.';

						return $smsSid;

					}

				}else{

					$smsSid = 'Number not exists in database.';

					return $smsSid;

				}

			}else{

				$smsSid = 'Parameter(s) is missing.';

				return $smsSid;

			}

		}

		else{

			$smsSid = 'No sms credits.';

			return $smsSid;

		}

	}

	function creditCount($userID,$msgType,$direction){

	    $userID = DBin($userID);

	    $msgType = DBin($msgType);

	    $direction = DBin($direction);

		global $link;

		$appSettings = getAppSettings($userID,true);

		$charges 	 = 0;

		if($direction=='in'){

			if($msgType=='sms'){

				$charges = $appSettings['incoming_sms_charge'];

			}else{

			}

		}else{

			if($msgType=='sms'){

				$charges = $appSettings['outgoing_sms_charge'];

			}else{

				$charges = ($appSettings['mms_credit_charges']+$appSettings['outgoing_sms_charge']);

			}

		}

		if(isAdmin($userID)){

			$sql = sprintf("update users set used_sms_credits=used_sms_credits+%s where id=%s",

                            mysqli_real_escape_string($link,DBin($charges)),

                            mysqli_real_escape_string($link,DBin($userID))

                );

			mysqli_query($link,$sql);

		}else{

			$sql = sprintf("update user_package_assignment set used_sms_credits=used_sms_credits+%s where user_id=%s",

                            mysqli_real_escape_string($link,DBin($charges)),

                            mysqli_real_escape_string($link,DBin($userID))

                );

			mysqli_query($link,$sql);

		}

	}

	function countWebforms($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select id from webforms where user_id=%s",

                    mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		return mysqli_num_rows($res);

	}

	function countAutoresponders($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select id from campaigns where user_id=%s and type='2'",

                    mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		return mysqli_num_rows($res);

	}

	function countCampaigns($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select id from campaigns where user_id=%s and type='1'",

                mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		return mysqli_num_rows($res);

	}

	function countUnSubscribers($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select id from subscribers where user_id=%s and status='2'",

                    mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		return mysqli_num_rows($res);

	}

	function countSubscribers($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select id from subscribers where user_id=%s",

                mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		return mysqli_num_rows($res);

	}

	function getTwilioConnection($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select * from application_settings where user_id=%s",

                    mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			$row = mysqli_fetch_assoc($res);

			include_once("ts/Twilio.php");

			$client = '';

			try{

				$client = new Services_Twilio($row['twilio_sid'],$row['twilio_token']);

				return $client;

			}catch (Services_Twilio_RestException $e) {

				return $client;

			}

		}

	}

	function checkKeyword($userID,$keyword,$campignID=""){

	    $userID = DBin($userID);

	    $keyword = DBin($keyword);

	    $campignID = DBin($campignID);

		global $link;

		$reservekeywords = checkReserveKeywords();

		if(!in_array($keyword,$reservekeywords)){

			if(($campignID=='') || ($campignID=='0')){

				$sql = sprintf("select id from campaigns where lower(keyword)='%s' and user_id=%s",

                    mysqli_real_escape_string($link,DBin(strtolower($keyword))),

                    mysqli_real_escape_string($link,DBin($userID))

                    );

			}else{

				$sql = sprintf("select id from campaigns where lower(keyword)='%s' and user_id=%s and id!=%s",

                            mysqli_real_escape_string($link,DBin(strtolower($keyword))),

                            mysqli_real_escape_string($link,DBin($userID)),

                            mysqli_real_escape_string($link,DBin($campignID))

                    );

			}

			$res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)==0)

				return true;

			else

				return false;

		}else{

			return false;

		}

	}

	function getCurrentPageName(){

		$currentFile = $_SERVER["PHP_SELF"];

		$parts = explode('/', $currentFile);

		$Name = $parts[count($parts) - 1];

		return $Name;

	}

	function encode($str){

	    $str = DBin($str);

		$id=uniqid();

		$last=substr($id,strlen($id)-10);

		$start=rand(11,99);

		return $start.$str.$last;

	}

	function decode($str){

	    $str = DBin($str);

		return substr($str,2,strlen($str)-12);

	}

    function DBin($string)

    {

        return  filter_var($string,FILTER_SANITIZE_STRING);

    }

	function DBout($string,$flag=ENT_NOQUOTES){

		$a = htmlspecialchars($string,$flag,'UTF-8');

		return $a;

	}

	function filterVar($string){

	    $a = filter_var($string,FILTER_SANITIZE_STRING);

	    return $a;

    }

	function getExtension($str){

	    $str = DBin($str);

		$i = strrpos($str,".");

		if (!$i) { return ""; }

		$l = strlen($str) - $i;

		$ext = substr($str,$i+1,$l);

		return $ext;

	}

	function getTimeArray(){

		$timeArray = array('00:00'=>'12:00 AM','00:15'=>'12:15 AM','00:30'=>'12:30 AM','00:45'=>'12:45 AM','01:00'=>'01:00 AM','01:15'=>'01:15 AM','01:30'=>'01:30 AM','01:45'=>'01:45 AM','02:00'=>'02:00 AM','02:15'=>'02:15 AM','02:30'=>'02:30 AM','02:45'=>'02:45 AM','03:00'=>'03:00 AM','03:15'=>'03:15 AM','03:30'=>'03:30 AM','03:45'=>'03:45 AM','04:00'=>'04:00 AM','04:15'=>'04:15 AM','04:30'=>'04:30 AM','04:45'=>'04:45 AM','05:00'=>'05:00 AM','05:15'=>'05:15 AM','05:30'=>'05:30 AM','05:45'=>'05:45 AM','06:00'=>'06:00 AM','06:15'=>'06:15 AM','06:30'=>'06:30 AM','06:45'=>'06:45 AM','07:00'=>'07:00 AM','07:15'=>'07:15 AM','07:30'=>'07:30 AM','07:45'=>'07:45 AM','08:00'=>'08:00 AM','08:15'=>'08:15 AM','08:30'=>'08:30 AM','08:45'=>'08:45 AM','09:00'=>'09:00 AM','09:15'=>'09:15 AM','09:30'=>'09:30 AM','09:45'=>'09:45 AM','10:00'=>'10:00 AM','10:15'=>'10:15 AM','10:30'=>'10:30 AM','10:45'=>'10:45 AM','11:00'=>'11:00 AM','11:15'=>'11:15 AM','11:30'=>'11:30 AM','11:45'=>'11:45 AM','12:00'=>'12:00 PM','12:15'=>'12:15 PM','12:30'=>'12:30 PM','12:45'=>'12:45 PM','13:00'=>'01:00 PM','13:15'=>'01:15 PM','13:30'=>'01:30 PM','13:45'=>'01:45 PM','14:00'=>'02:00 PM','14:15'=>'02:15 PM','14:30'=>'02:30 PM','14:45'=>'02:45 PM','15:00'=>'03:00 PM','15:15'=>'03:15 PM','15:30'=>'03:30 PM','15:45'=>'03:45 PM','16:00'=>'04:00 PM','16:15'=>'04:15 PM','16:30'=>'04:30 PM','16:45'=>'04:45 PM','17:00'=>'05:00 PM','17:15'=>'05:15 PM','17:30'=>'05:30 PM','17:45'=>'05:45 PM','18:00'=>'06:00 PM','18:15'=>'06:15 PM','18:30'=>'06:30 PM','18:45'=>'06:45 PM','19:00'=>'07:00 PM','19:15'=>'07:15 PM','19:30'=>'07:30 PM','19:45'=>'07:45 PM','20:00'=>'08:00 PM','20:15'=>'08:15 PM','20:30'=>'08:30 PM','20:45'=>'08:45 PM','21:00'=>'09:00 PM','21:15'=>'09:15 PM','21:30'=>'09:30 PM','21:45'=>'09:45 PM','22:00'=>'10:00 PM','22:15'=>'10:15 PM','22:30'=>'10:30 PM','22:45'=>'10:45 PM','23:00'=>'11:00 PM','23:15'=>'11:15 PM','23:30'=>'11:30 PM','23:45'=>'11:45 PM');

		return $timeArray;

	}

	function checkTwilioAccountStatus($userID){

	    $userID = DBin($userID);

		global $link;

		$sql = sprintf("select * from application_settings where user_id=%s",

                    mysqli_real_escape_string($link,DBin($userID))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			$row = mysqli_fetch_assoc($res);

			$url = 'https://'.$row['twilio_sid'].':'.$row['twilio_token'].'@api.twilio.com/2010-04-01/Accounts';

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			curl_setopt($ch, CURLOPT_URL, $url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			curl_setopt($ch, CURLOPT_HTTPGET,true);

			$response = curl_exec($ch);

			$response = simplexml_load_string($response);

			$accounts = $response->Accounts->Account;

			$array  = array();

			foreach($accounts as $account){

				if( ($account->Sid==$row['twilio_sid']) && ($account->AuthToken==$row['twilio_token']) ){

					$status = $account->Status;

					$type	= $account->Type;

					$accName= $account->FriendlyName;

					$array['status'] = $status;

					$array['type'] 	 = $type;

					$array['acc_name'] = $accName;

					return $array;

					break;

				}

			}

		}

	}

	function getTwilioCountries($sid,$token){

	    $sid = DBin($sid);

	    $token = DBin($token);

		$url = "https://$sid:$token@api.twilio.com/2010-04-01/Accounts/$sid/AvailablePhoneNumbers";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_HTTPGET,true);

		$response = curl_exec($ch);

		$response = simplexml_load_string($response);

		return $response;

	}





    function sendTwilioCurl($data,$url,$customRequest="POST")

    {



        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if($customRequest=="POST" && is_array($data) && count($data)>0){

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        }else if($customRequest=="PUT"){

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        }



        $res1 = curl_exec($ch);

        $res = simplexml_load_string($res1);

        return $res;

    }

function searchTwilioNumbers($client,$country,$state,$type,$areaCode,$contains,$user_id=""){

    $client = ($client);

    $country = DBout($country);

    $state = DBout($state);

    $type = DBout($type);

    $areaCode = DBout($areaCode);

    $contains = DBout($contains);

    $user_id = DBout($user_id);

    $twilio = getTwilioInfo($user_id);

    $twilio_sid = $twilio['twilio_sid'];

    $twilio_token = $twilio['twilio_token'];



    if(trim($country)=="")

        $country = 'US';

    if(trim($type)=="")

        $type = 'Local';

    try{

        $url  = "https://$twilio_sid:$twilio_token@api.twilio.com/2010-04-01/Accounts/$twilio_sid/AvailablePhoneNumbers/$country/$type?AreaCode=$areaCode";

        $numbers = sendTwilioCurl("",$url,"POST");

        return $numbers->AvailablePhoneNumbers;

    }

    catch(Services_Twilio_RestException $e){

        try{

            $type = 'Mobile';



            $url  = "https://$twilio_sid:$twilio_token@api.twilio.com/2010-04-01/Accounts/$twilio_sid/AvailablePhoneNumbers/$country/$type?AreaCode=$areaCode";

            $numbers = sendTwilioCurl("",$url,"POST");

            return $numbers->AvailablePhoneNumbers;

        }catch(Services_Twilio_RestException $e){

            return false;

        }

    }

}





	function getServerURL(){

		$protocol = ( ((!empty($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] !== 'off')) || ($_SERVER['SERVER_PORT'] == 443) ) ? "https://" : "http://";

		$domainName = $_SERVER['HTTP_HOST'];

		$filePath   = $_SERVER['REQUEST_URI'];

		$fullUrl = $protocol.$domainName.$filePath;

		$installURL = substr($fullUrl,0,strrpos($fullUrl,'/'));

		return $installURL;

	}

	function sendEmail($subject,$to,$from,$msg,$FullName){

		$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'To: <'.$to.'>'. "\r\n";

		$headers .= 'From: '.$FullName.' <'.$from.'>' . "\r\n";

		mail($to, $subject, $msg, $headers);

	}

	function postData($url,$data){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);

		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');

		$data = curl_exec($ch);

		curl_close($ch);

		return $data;

	}

	function exportSubscribers($campaignID,$userID){

	    $campaignID = DBin($campaignID);

	    $userID = DBin($userID);

		global $link;

		$filename = 'subscribers.csv';

		$fp = fopen($filename, "w");

		$line = "";

		$comma = "";

		$line .= $comma . 'FIRST NAME,FIRST NAME INITIAL,LAST NAME,LAST NAME INITIAL,HOME ADDRESS,POST CODE,ACCOUNT NUMBER,MIN PARTIAL PAYMENT AMOUNT,AMOUNT OWING,SERVICES RENDERED,PHONE NUMBER,EMAIL ADDRESS,BUSINESS NAME,CITY,PROVINCE,BILL NO,INVOICE NO,LAST PAYMENT DATE (YYYY-M-D),LAST PAID AMOUNT';

		$comma = ",";

		$line .= "\n";

		fputs($fp, $line);

		$line = "";

		$comma = "";



		if($campaignID=='all'){

			$sql = sprintf("select * from subscribers where user_id=%s",

                    mysqli_real_escape_string($link,DBin($userID))

                );

		}else{

			$sql = sprintf("select s.* from subscribers s, subscribers_group_assignment sga where sga.group_id=%s and sga.subscriber_id=s.id",

                            mysqli_real_escape_string($link,DBin($campaignID))

                );

		}



		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			$index = 1;

			$count = 0;

			while($row=mysqli_fetch_assoc($res)){

				$line = "";

				$comma = "";

				$count++;

				$line =  
				$row['first_name'] . "," .
				$row['first_initial'] . "," .
				$row['last_name'] . "," .
				$row['last_initial'] . "," .
				$row['address'] . "," .
				$row['post_code'] . "," .
				$row['account_number'] . "," .
				$row['partial_payment_amount'] . "," .
				$row['amount_to_be_paid'] . "," .
				$row['service_render'] . "," .
				$row['phone_number'] . "," .
				$row['email'] . "," .
				$row['business_name'] . "," .
				$row['city'] . "," .
				$row['state'] . "," .
				$row['Bill_No'] . "," .
				$row['Invoice_No'] . "," .
				$row['Last_Payment_Date'] . "," .
				$row['Last_Paid_Amount'];

				$comma = ",";

				$line .= "\n";

				fputs($fp, $line);

			}

		}

	}

    function exportHistory(){

		global $link;

		$filename = 'sms_history_'.date('Y-m-d H:i:s').'.csv';

		$fp = fopen($filename, "w");

		$line = "";

		$comma = "";

		$line .= $comma . 'SR#, From, To, Text, Direction, Sent Date, Status';

		$comma = ",";

		$line .= "\n";

		fputs($fp, $line);

		$line = "";

		$comma = "";



		$sql = $_SESSION['sql_history'];

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			$index = 1;

			while($row=mysqli_fetch_assoc($res)){

				$line = "";

				$comma = "";

				$count++;

				$line .= $comma . '"'.$index++.'","'.$row['from_number'].'","'.$row['to_number'].'","'.$row['text'].'","'.$row['direction'].'","'.$row['created_date'].'","'.$row['is_sent'].'"';

				$comma = ",";

				$line .= "\n";

				fputs($fp, $line);

			}

		}

        return $filename;

	}

    function importCompanySubscribers($filename,$campaignID,$userID){

		global $link;

        $index = 0;

        $handle = fopen("uploads/$filename", "r");

        while(($data=fgetcsv($handle,1000,",")) !== FALSE){

            if($index>0){

                if($number = trim($data[0])==''){

                    $_SESSION['message'] = 'No Data in it..';

                }else{

                    $firstName = trim($data[0]);

                    $lastName  = trim($data[1]);

                    $address   = trim($data[2]);

					$post_code = trim($data[3]);

					$account_number = trim($data[4]);

					$amount_to_be_paid = trim($data[5]);

					$number    = '+1'.trim($data[6]);

					$email = trim($data[7]);

                    $city = trim($data[8]);

                    $province = trim($data[9]);

					$service = trim($data[10]);

					

	                $sql = sprintf("select id from subscribers where phone_number='%s'",

                        mysqli_real_escape_string($link,DBin($number))

                    );

                    $res = mysqli_query($link,$sql);

                    if(mysqli_num_rows($res)==0){

                        $import = sprintf("INSERT into subscribers 

                                (

                                        first_name, 

                                        last_name,

                                        phone_number,

                                        address,

                                        city,

                                        state,

                                        account_number,

                                        amount_to_be_paid,

                                        post_code,

										email,

                                        user_id,

										service,

                                        subs_type

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

                                        '%s',                                        

                                        '%s',

										'%s',

                                        '%s',

										'campaign'

                                )",

                            mysqli_real_escape_string($link,DBin($firstName)),

                            mysqli_real_escape_string($link,DBin($lastName)),

                            mysqli_real_escape_string($link,DBin($number)),

                            mysqli_real_escape_string($link,DBin($address)),

	                        mysqli_real_escape_string($link,DBin($city)),

	                        mysqli_real_escape_string($link,DBin($province)),

	                        mysqli_real_escape_string($link,DBin($account_number)),

	                        mysqli_real_escape_string($link,DBin($amount_to_be_paid)),

	                        mysqli_real_escape_string($link,DBin($post_code)),

							mysqli_real_escape_string($link,DBin($email)),

	                        mysqli_real_escape_string($link,DBin($_SESSION['user_id'])),

							mysqli_real_escape_string($link,DBin($service))

                        );

                        mysqli_query($link,$import) or die(mysqli_error($link));

                        $subsID = mysqli_insert_id($link);

						$sql1 = sprintf("insert into subscribers_group_assignment (group_id,subscriber_id,user_id) values(%s,%s,%s)",

							mysqli_real_escape_string($link,DBin($campaignID)),

							mysqli_real_escape_string($link,DBin($subsID)),

							mysqli_real_escape_string($link,DBin($userID))

						);

						mysqli_query($link,$sql1) or die(mysqli_error($link));

                    }else{

                        $row = mysqli_fetch_assoc($res);

                        $subsID = $row['id'];

                        $sel = sprintf("select id from subscribers_group_assignment where subscriber_id=%s and group_id=%s",

                            mysqli_real_escape_string($link,DBin($subsID)),

                            mysqli_real_escape_string($link,DBin($campaignID))

                        );

                        $exe = mysqli_query($link,$sel) or die(mysqli_error($link));

                        if(mysqli_num_rows($exe)=='0'){

                            $sql2 = sprintf("insert into subscribers_group_assignment (group_id,subscriber_id,user_id) values(%s,%s,%s)",

                                mysqli_real_escape_string($link,DBin($campaignID)),

                                mysqli_real_escape_string($link,DBin($subsID)),

                                mysqli_real_escape_string($link,DBin($userID))

                            );

                            mysqli_query($link,$sql2) or die(mysqli_error($link));

                        }

                    }

                }

            }

            $index++;

        }

    }



	function importSubscribers($filename,$campaignID,$userID){

	    $filename = DBin($filename);

	    $campaignID = DBin($campaignID);

	    $userID = DBin($userID);

		global $link;

		$index = 0;

		$handle = fopen("uploads/$filename", "r");

		while(($data=fgetcsv($handle,1000,",")) !== FALSE){

			if($index>0){

				if($number = trim($data[0])==''){

					$_SESSION['message'] = 'No Data in it..';

				}else{

					// first_name  FIRST NAME
					// first_initial  FIRST NAME INITIAL
					// last_name  LAST NAME
					// last_initial  LAST NAME INITIAL
					// address  HOME ADDRESS
					// post_code  POST CODE
					// account_number  ACCOUNT NUMBER
					// partial_payment_amount MIN PARTIAL PAYMENT AMOUNT
					// amount_to_be_paid  AMOUNT OWING
					// service_render  SERVICES RENDERED
					// phone_number  PHONE NUMBER
					// email  EMAIL ADDRESS
					// business_name  BUSINESS NAME
					// city  CITY
					// state  PROVINCE
					// Bill_No BILL NO
					// Invoice_No  INVOICE NO
					// Last_Payment_Date  LAST PAYMENT DATE (YYYY-M-D)
					// Last_Paid_Amount  LAST PAID AMOUNT 

					$firstName = trim($data[0]);

					$firstNameIntital = trim($data[1]);

					$lastName  = trim($data[2]);

					$LastNameIntital = trim($data[3]);

					$address  = trim($data[4]);

					$postCode  = trim($data[5]);

					$accountNumber  = trim($data[6]);

					$PartialPayment  = trim((($data[7] > 0 && $data[7] != '') ? 1 : 0));

					$minPartialPayment  = trim($data[7]);

					$amountOwing  = trim($data[8]);

					$ServiceRenderd = trim($data[9]);

					$number    = '+1'.trim($data[10]);

                	$email  = trim($data[11]);

					$BusinessName = trim($data[12]);

					$city  = trim($data[13]);

					$state  = trim($data[14]);

					$BillNo = trim($data[15]);

					$IinvoiceNo = trim($data[16]);

					$LastPaymentDate = trim($data[17]);

					$LastPaidAmount = trim($data[18]);

					$balance = $amountOwing-$LastPaidAmount;

					



					$sql = sprintf("select id from subscribers where phone_number='%s'",

                                        mysqli_real_escape_string($link,DBin($number))

                        );

					$res = mysqli_query($link,$sql);

					if(mysqli_num_rows($res)==0){

						$import = sprintf("insert into subscribers

                            (

                                first_name,

                                first_initial,

								last_name,

								last_initial,

								address,

								post_code,

								account_number,

								amount_to_be_paid,

                                partial_payment,

                                partial_payment_amount,

								service_render,

                                phone_number,

                                email,

                                city,

                                state,

                                user_id,
                                
                                Bill_No,
                                
                                business_name,
                                
                                Invoice_No,
                                
                                Last_Payment_Date,
                                
                                Last_Paid_Amount,
                                
                                Outstanding_Balance,

                                subs_type

                            )values

                            (

                                '%s',

								'%s',

                                '%s',

                                '%s',

                                '%s',

                                '%s',

                                '%s',

								'%s',

								'%s',

								'%s',

								'%s',

								'%s',

								'%s',

								'%s',

								'%s',

								'%s',

								'%s',

								'%s',

								'%s',

								'%s',

								'%s',

								'%s',

                                'campaign'

                            )",

                                mysqli_real_escape_string($link,DBin($firstName)),

						   		mysqli_real_escape_string($link,DBin($firstNameIntital)),

						   		mysqli_real_escape_string($link,DBin($lastName)),

						   		mysqli_real_escape_string($link,DBin($LastNameIntital)),

						   		mysqli_real_escape_string($link,DBin($address)),

						   		mysqli_real_escape_string($link,DBin($postCode)),

                                mysqli_real_escape_string($link,DBin($accountNumber)),

                                mysqli_real_escape_string($link,DBin($amountOwing)),

                                mysqli_real_escape_string($link,DBin($PartialPayment)),

                                mysqli_real_escape_string($link,DBin($minPartialPayment)),

                                mysqli_real_escape_string($link,DBin($ServiceRenderd)),

								mysqli_real_escape_string($link,DBin($number)),

								mysqli_real_escape_string($link,DBin($email)),

								mysqli_real_escape_string($link,DBin($city)),

								mysqli_real_escape_string($link,DBin($state)),

								mysqli_real_escape_string($link,DBin($_SESSION['user_id'])),

								mysqli_real_escape_string($link,DBin($BillNo)),

								mysqli_real_escape_string($link,DBin($BusinessName)),

								mysqli_real_escape_string($link,DBin($IinvoiceNo)),

								mysqli_real_escape_string($link,DBin($LastPaymentDate)),

								mysqli_real_escape_string($link,DBin($LastPaidAmount)),

								mysqli_real_escape_string($link,DBin($balance))

                		);

						mysqli_query($link,$import) or die(mysqli_error($link));

						$subsID = mysqli_insert_id($link);

						$sel = sprintf("select id from subscribers_group_assignment where subscriber_id=%s and group_id=%s",

                                            mysqli_real_escape_string($link,DBin($subsID)),

                                            mysqli_real_escape_string($link,DBin($campaignID))

                            );

						$exe = mysqli_query($link,$sel) or die(mysqli_error($link));

						if(mysqli_num_rows($exe)=='0'){

						    $sql1 = sprintf("insert into subscribers_group_assignment (group_id,subscriber_id,user_id) values(%s,%s,%s)",

                                        mysqli_real_escape_string($link,DBin($campaignID)),

                                        mysqli_real_escape_string($link,DBin($subsID)),

                                        mysqli_real_escape_string($link,DBin($userID))

                                );

							mysqli_query($link,$sql1) or die(mysqli_error($link));

						}

					}else{

						$row = mysqli_fetch_assoc($res);

						$subsID = $row['id'];

						$sel = sprintf("select id from subscribers_group_assignment where subscriber_id=%s and group_id=%s",

                                        mysqli_real_escape_string($link,DBin($subsID)),

                                        mysqli_real_escape_string($link,DBin($campaignID))

                            );

						$exe = mysqli_query($link,$sel);

						if(mysqli_num_rows($exe)=='0'){

						    $sql2 = sprintf("insert into subscribers_group_assignment (group_id,subscriber_id,user_id) values(%s,%s,%s)",

                                            mysqli_real_escape_string($link,DBin($campaignID)),

                                            mysqli_real_escape_string($link,DBin($subsID)),

                                            mysqli_real_escape_string($link,DBin($userID))

                                );

							mysqli_query($link,$sql2);

						}

					}

				}

			}

			$index++;

		}

	}

	function downloadFile($file){

	    $file = DBin($file);

		$mime = 'application/force-download';

		header('Pragma: public');

		header('Expires: 0');

		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

		header('Cache-Control: private',false);

		header('Content-Type: '.$mime);

		header('Content-Disposition: attachment; filename="'.basename($file).'"');

		header('Content-Transfer-Encoding: binary');

		header('Connection: close');

		readfile($file);

		exit();

	}

	function countries(){

		return $isoCountries = array(

			'AF' => 'Afghanistan',

			'AX' => 'Aland Islands',

			'AL' => 'Albania',

			'DZ' => 'Algeria',

			'AS' => 'American Samoa',

			'AD' => 'Andorra',

			'AO' => 'Angola',

			'AI' => 'Anguilla',

			'AQ' => 'Antarctica',

			'AG' => 'Antigua And Barbuda',

			'AR' => 'Argentina',

			'AM' => 'Armenia',

			'AW' => 'Aruba',

			'AU' => 'Australia',

			'AT' => 'Austria',

			'AZ' => 'Azerbaijan',

			'BS' => 'Bahamas',

			'BH' => 'Bahrain',

			'BD' => 'Bangladesh',

			'BB' => 'Barbados',

			'BY' => 'Belarus',

			'BE' => 'Belgium',

			'BZ' => 'Belize',

			'BJ' => 'Benin',

			'BM' => 'Bermuda',

			'BT' => 'Bhutan',

			'BO' => 'Bolivia',

			'BA' => 'Bosnia And Herzegovina',

			'BW' => 'Botswana',

			'BV' => 'Bouvet Island',

			'BR' => 'Brazil',

			'IO' => 'British Indian Ocean Territory',

			'BN' => 'Brunei Darussalam',

			'BG' => 'Bulgaria',

			'BF' => 'Burkina Faso',

			'BI' => 'Burundi',

			'KH' => 'Cambodia',

			'CM' => 'Cameroon',

			'CA' => 'Canada',

			'CV' => 'Cape Verde',

			'KY' => 'Cayman Islands',

			'CF' => 'Central African Republic',

			'TD' => 'Chad',

			'CL' => 'Chile',

			'CN' => 'China',

			'CX' => 'Christmas Island',

			'CC' => 'Cocos (Keeling) Islands',

			'CO' => 'Colombia',

			'KM' => 'Comoros',

			'CG' => 'Congo',

			'CD' => 'Congo, Democratic Republic',

			'CK' => 'Cook Islands',

			'CR' => 'Costa Rica',

			'CI' => 'Cote D\'Ivoire',

			'HR' => 'Croatia',

			'CU' => 'Cuba',

			'CY' => 'Cyprus',

			'CZ' => 'Czech Republic',

			'DK' => 'Denmark',

			'DJ' => 'Djibouti',

			'DM' => 'Dominica',

			'DO' => 'Dominican Republic',

			'EC' => 'Ecuador',

			'EG' => 'Egypt',

			'SV' => 'El Salvador',

			'GQ' => 'Equatorial Guinea',

			'ER' => 'Eritrea',

			'EE' => 'Estonia',

			'ET' => 'Ethiopia',

			'FK' => 'Falkland Islands (Malvinas)',

			'FO' => 'Faroe Islands',

			'FJ' => 'Fiji',

			'FI' => 'Finland',

			'FR' => 'France',

			'GF' => 'French Guiana',

			'PF' => 'French Polynesia',

			'TF' => 'French Southern Territories',

			'GA' => 'Gabon',

			'GM' => 'Gambia',

			'GE' => 'Georgia',

			'DE' => 'Germany',

			'GH' => 'Ghana',

			'GI' => 'Gibraltar',

			'GR' => 'Greece',

			'GL' => 'Greenland',

			'GD' => 'Grenada',

			'GP' => 'Guadeloupe',

			'GU' => 'Guam',

			'GT' => 'Guatemala',

			'GG' => 'Guernsey',

			'GN' => 'Guinea',

			'GW' => 'Guinea-Bissau',

			'GY' => 'Guyana',

			'HT' => 'Haiti',

			'HM' => 'Heard Island & Mcdonald Islands',

			'VA' => 'Holy See (Vatican City State)',

			'HN' => 'Honduras',

			'HK' => 'Hong Kong',

			'HU' => 'Hungary',

			'IS' => 'Iceland',

			'IN' => 'India',

			'ID' => 'Indonesia',

			'IR' => 'Iran, Islamic Republic Of',

			'IQ' => 'Iraq',

			'IE' => 'Ireland',

			'IM' => 'Isle Of Man',

			'IL' => 'Israel',

			'IT' => 'Italy',

			'JM' => 'Jamaica',

			'JP' => 'Japan',

			'JE' => 'Jersey',

			'JO' => 'Jordan',

			'KZ' => 'Kazakhstan',

			'KE' => 'Kenya',

			'KI' => 'Kiribati',

			'KR' => 'Korea',

			'KW' => 'Kuwait',

			'KG' => 'Kyrgyzstan',

			'LA' => 'Lao People\'s Democratic Republic',

			'LV' => 'Latvia',

			'LB' => 'Lebanon',

			'LS' => 'Lesotho',

			'LR' => 'Liberia',

			'LY' => 'Libyan Arab Jamahiriya',

			'LI' => 'Liechtenstein',

			'LT' => 'Lithuania',

			'LU' => 'Luxembourg',

			'MO' => 'Macao',

			'MK' => 'Macedonia',

			'MG' => 'Madagascar',

			'MW' => 'Malawi',

			'MY' => 'Malaysia',

			'MV' => 'Maldives',

			'ML' => 'Mali',

			'MT' => 'Malta',

			'MH' => 'Marshall Islands',

			'MQ' => 'Martinique',

			'MR' => 'Mauritania',

			'MU' => 'Mauritius',

			'YT' => 'Mayotte',

			'MX' => 'Mexico',

			'FM' => 'Micronesia, Federated States Of',

			'MD' => 'Moldova',

			'MC' => 'Monaco',

			'MN' => 'Mongolia',

			'ME' => 'Montenegro',

			'MS' => 'Montserrat',

			'MA' => 'Morocco',

			'MZ' => 'Mozambique',

			'MM' => 'Myanmar',

			'NA' => 'Namibia',

			'NR' => 'Nauru',

			'NP' => 'Nepal',

			'NL' => 'Netherlands',

			'AN' => 'Netherlands Antilles',

			'NC' => 'New Caledonia',

			'NZ' => 'New Zealand',

			'NI' => 'Nicaragua',

			'NE' => 'Niger',

			'NG' => 'Nigeria',

			'NU' => 'Niue',

			'NF' => 'Norfolk Island',

			'MP' => 'Northern Mariana Islands',

			'NO' => 'Norway',

			'OM' => 'Oman',

			'PK' => 'Pakistan',

			'PW' => 'Palau',

			'PS' => 'Palestinian Territory, Occupied',

			'PA' => 'Panama',

			'PG' => 'Papua New Guinea',

			'PY' => 'Paraguay',

			'PE' => 'Peru',

			'PH' => 'Philippines',

			'PN' => 'Pitcairn',

			'PL' => 'Poland',

			'PT' => 'Portugal',

			'PR' => 'Puerto Rico',

			'QA' => 'Qatar',

			'RE' => 'Reunion',

			'RO' => 'Romania',

			'RU' => 'Russian Federation',

			'RW' => 'Rwanda',

			'BL' => 'Saint Barthelemy',

			'SH' => 'Saint Helena',

			'KN' => 'Saint Kitts And Nevis',

			'LC' => 'Saint Lucia',

			'MF' => 'Saint Martin',

			'PM' => 'Saint Pierre And Miquelon',

			'VC' => 'Saint Vincent And Grenadines',

			'WS' => 'Samoa',

			'SM' => 'San Marino',

			'ST' => 'Sao Tome And Principe',

			'SA' => 'Saudi Arabia',

			'SN' => 'Senegal',

			'RS' => 'Serbia',

			'SC' => 'Seychelles',

			'SL' => 'Sierra Leone',

			'SG' => 'Singapore',

			'SK' => 'Slovakia',

			'SI' => 'Slovenia',

			'SB' => 'Solomon Islands',

			'SO' => 'Somalia',

			'ZA' => 'South Africa',

			'GS' => 'South Georgia And Sandwich Isl.',

			'ES' => 'Spain',

			'LK' => 'Sri Lanka',

			'SD' => 'Sudan',

			'SR' => 'Suriname',

			'SJ' => 'Svalbard And Jan Mayen',

			'SZ' => 'Swaziland',

			'SE' => 'Sweden',

			'CH' => 'Switzerland',

			'SY' => 'Syrian Arab Republic',

			'TW' => 'Taiwan',

			'TJ' => 'Tajikistan',

			'TZ' => 'Tanzania',

			'TH' => 'Thailand',

			'TL' => 'Timor-Leste',

			'TG' => 'Togo',

			'TK' => 'Tokelau',

			'TO' => 'Tonga',

			'TT' => 'Trinidad And Tobago',

			'TN' => 'Tunisia',

			'TR' => 'Turkey',

			'TM' => 'Turkmenistan',

			'TC' => 'Turks And Caicos Islands',

			'TV' => 'Tuvalu',

			'UG' => 'Uganda',

			'UA' => 'Ukraine',

			'AE' => 'United Arab Emirates',

			'GB' => 'United Kingdom',

			'US' => 'United States',

			'UM' => 'United States Outlying Islands',

			'UY' => 'Uruguay',

			'UZ' => 'Uzbekistan',

			'VU' => 'Vanuatu',

			'VE' => 'Venezuela',

			'VN' => 'Viet Nam',

			'VG' => 'Virgin Islands, British',

			'VI' => 'Virgin Islands, U.S.',

			'WF' => 'Wallis And Futuna',

			'EH' => 'Western Sahara',

			'YE' => 'Yemen',

			'ZM' => 'Zambia',

			'ZW' => 'Zimbabwe',

		);

	}

	function generatePaging($sql,$pagelink,$pageNum,$max_records_per_page){

		global $link;

		if($pageNum==1){

			$tmpRes = mysqli_query($link,$sql);

			$totalRecs = mysqli_num_rows($tmpRes);

			$_SESSION['TOTAL_RECORDS'] = $totalRecs;

		}

		$recStart = ((int)($pageNum-1) )*((int) $max_records_per_page);

		$totalRecs= $_SESSION['TOTAL_RECORDS'];

		$pagingString = '<table border="0" cellspacing="0" class="paging-string move-right" cellpadding="0" ><tr><td align="left" valign="middle" class="pagin_style">';

		$totalPages = ceil(((int)$totalRecs)/((int)$max_records_per_page));

		$pagingStartPage = 1;

		$pagingEndPage = $totalPages;

		if($pageNum>6)

			$pagingStartPage = $pageNum-5;



		if($pageNum<($totalPages-5))

			$pagingEndPage = $pageNum+5;



		if($pageNum>1){

			$prPage = $pageNum -1;



			$pagingString .= '<a href="'.DBout($pagelink).'page=1" class="hyperlink"><span class="btn-grey">First</span></a>';



			$pagingString .= ' <a href="'.DBout($pagelink).'page='. DBout($prPage ).'" ><span class="btn-grey">Previous</span></a> ';

		}

		for($i=$pagingStartPage;$i<=$pagingEndPage;$i++){

			if($pageNum==$i){

				$pagingString .= '<span class="btn-pages-active">'.DBout($i).'</span>';

			}else{

				$pagingString .='<a href="'.DBout($pagelink).'page='.DBout($i).'" class="btn-pages-inactive">'.DBout($i).'</a>';

			}

		}

		if($pageNum<$totalPages){

			$nePage = $pageNum + 1;

			$pagingString .= ' <a href="'.DBout($pagelink).'page='. DBout($nePage) .'" ><span class="btn-grey">Next</span></a> ';

			$pagingString .= '<a href="'.DBout($pagelink).'page='. $totalPages .'" ><span class="btn-grey">Last</span></a> ';

		}

		$pagingString .= '</td></tr></table>';

		$sqlLIMIT = " LIMIT ". $recStart . " , " . $max_records_per_page;



		if($totalPages == 1){

			$a['pagingString'] = '';

			$a['limit'] = '';

		}else{

			$a['pagingString'] = $pagingString;

			$a['limit'] =  $sqlLIMIT;

		}

		return $a;

	}



    function boundNumber($to,$from,$userID,$groupID,$whatIsSent="",$questionID="0",$is_viral_code="0",$viral_code=""){

	    $to = DBin($to);

	    $from = DBin($from);

	    $userID = DBin($userID);

	    $groupID = DBin($groupID);

	    $whatIsSent = DBin($whatIsSent);

	    $questionID = DBin($questionID);

	    $is_viral_code = DBin($is_viral_code);

	    $viral_code = DBin($viral_code);

        global $link;

        $lease_date = date("Y-m-d H:i",strtotime(date("Y-m-d H:i")." + 24 hours"));

        $sel = sprintf("select id from bound_phones where to_number=%s and from_number=%s and user_id='%s' and group_id=%s",

                        mysqli_real_escape_string($link,DBin($to)),

                        mysqli_real_escape_string($link,Dbin($from)),

                        mysqli_real_escape_string($link,DBin($userID)),

                        mysqli_real_escape_string($link,DBin($groupID))

            );

    	$exe = mysqli_query($link,$sel);

    	if(mysqli_num_rows($exe)=='0'){

    	    $sql1 = sprintf("insert into bound_phones 

                                (

                                        to_number,

                                        from_number,

                                        user_id,

                                        group_id,

                                        lease_date,

                                        what_is_sent,

                                        question_id,

                                        is_viral_code,

                                        viral_code

                                ) 

                                values(

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

                                        mysqli_real_escape_string($link,DBin($to)),

                                        mysqli_real_escape_string($link,DBin($from)),

                                        mysqli_real_escape_string($link,DBin($userID)),

                                        mysqli_real_escape_string($link,DBin($groupID)),

                                        mysqli_real_escape_string($link,DBin($lease_date)),

                                        mysqli_real_escape_string($link,DBin($whatIsSent)),

                                        mysqli_real_escape_string($link,DBin($questionID)),

                                        mysqli_real_escape_string($link,DBin($is_viral_code)),

                                        mysqli_real_escape_string($link,DBin($viral_code))

                );

    		mysqli_query($link,$sql1);

    	}else{

            $row = mysqli_fetch_assoc($exe);

            $sql3 = sprintf("update bound_phones set lease_date = %s where id = %s",

                        mysqli_real_escape_string($link,DBin($lease_date)),

                        mysqli_real_escape_string($link,DBin($row['id']))

                );

    	   	mysqli_query($link,$sql3);

    	}

    }

	function generateAPIKey(){

		global $link;

		$couponChars 	= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

		$couponCharLen 	= (strlen($couponChars)-1);

		$couponLength 	= 20;

		$couponCode 	= '';

		$couponCheck 	= '0';

		while($couponCheck == '0'){

			for($i=0; $i<$couponLength; $i++){

				$couponCode .= $couponChars[rand(0,$couponCharLen)];

			}

			$sql = "select id from application_settings where api_key='".$couponCode."'";

			$res = mysqli_query($link,$sql);

			if(mysqli_num_rows($res)==0){

				$couponCheck = '1';

			}

		}

		return $couponCode;

	}

	function timeAgo($mysqlDateTime,$full=false){

	    $mysqlDateTime = DBin($mysqlDateTime);

	    $full = DBin($full);

		$now = new DateTime;

		$ago = new DateTime($mysqlDateTime);

		$diff = $now->diff($ago);



		$diff->w = floor($diff->d / 7);

		$diff->d -= $diff->w * 7;



		$string = array(

			'y' => 'year',

			'm' => 'month',

			'w' => 'week',

			'd' => 'day',

			'h' => 'hour',

			'i' => 'minute',

			's' => 'second',

		);

		foreach ($string as $k => &$v){

			if($diff->$k){

				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');

			}else{

				unset($string[$k]);

			}

		}



		if (!$full) $string = array_slice($string, 0, 1);

		return $string ? implode(', ', $string) . ' ago' : 'just now';

	}





    function curl_process($url){

	    $url = DBin($url);



        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

        $res = curl_exec($ch);

        $error = curl_error($ch);

        if($error!=""){

            return json_encode(array("error"=>$error));

        }

        return $res;

    }



    function curl_process22($url,$data=""){

        $url= DBin($url);

        $data = DBin($data);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json',

                                                'Content-Type: application/json'

                                             ));

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $res = curl_exec($ch);

        $error = curl_error($ch);

        echo $error;

        return $res;

    }



    function getFirstQuestionID($groupID)

    {

        $groupID = DBin($groupID);

        global $link;

    	$sql = sprintf("select * from trivia_questions where campaign_id=%s order by id asc limit 1",

                        mysqli_real_escape_string($link,DBin($groupID))

            );

    	$res = mysqli_query($link,$sql);

    	if(mysqli_num_rows($res)>0)

    	{

    		$row = mysqli_fetch_assoc($res);

    		return $row['id'];

    	}

    	return false;

    }





    function getNextImmediateQuestion($nextImmediateQuestionID)

    {

        $nextImmediateQuestionID = DBin($nextImmediateQuestionID);

        global $link;

    	$sql = sprintf("select * from trivia_questions where id=%s limit 1",

                            mysqli_real_escape_string($link,DBin($nextImmediateQuestionID))

            );

    	$res = mysqli_query($link,$sql);

    	if(mysqli_num_rows($res)>0)

    	{

    		$row  = mysqli_fetch_assoc($res);

			$options      = 'Reply ';

			$msg1		  = $row['question'];

			$sqlAns       = sprintf("select * from trivia_answers where question_id=%s order by id asc",

                                mysqli_real_escape_string($link,DBin($row['id']))

                );

			$resAns		  = mysqli_query($link,$sqlAns) or die(mysqli_error($link));

			while($rowAns = mysqli_fetch_assoc($resAns))

			{

				$options .= $rowAns['value'].' for '.$rowAns['answer'].", \n\r";

			}

			$msg1 .= "\n\r".$options;

			return $msg1;

    	}

    }



    function getNextImmediateQuestionID($groupID,$lastQuestionID)

    {

        $groupID = DBin($groupID);

        $lastQuestionID = DBin($lastQuestionID);

        global $link;

    	$sql = sprintf("select * from trivia_questions where 

                                        campaign_id=%s and id > %s order by id asc limit 1",

                    mysqli_real_escape_string($link,DBin($groupID)),

                    mysqli_real_escape_string($link,DBin($lastQuestionID))

            );

    	$res = mysqli_query($link,$sql);

    	if(mysqli_num_rows($res)>0)

        {

            $row = mysqli_fetch_assoc($res);

  			return $row['id'];

    	}

    	return false;

    }





    function checkAnsArr($questionID, $key)

    {

        $questionID = DBin($questionID);

        $key = DBin($key);

        global $link;

    	$sqlCheckAns = sprintf("select sa.* , sq.question, sq.id as questionID from trivia_answers sa, trivia_questions sq 

                                    where sq.id=%s and sa.question_id=sq.id and sa.value=%s limit 1",

                        mysqli_real_escape_string($link,DBin($questionID)),

                        mysqli_real_escape_string($link,Dbin($key))

            );

    	$exeCheckAns = mysqli_query($link,$sqlCheckAns) ;

    	if($exeCheckAns && mysqli_num_rows($exeCheckAns)>0)

    	{

    		return mysqli_fetch_assoc($exeCheckAns);

    	}

    	return false;

    }



    function createCouponCode()

    {

        global $link;

    	$couponChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    	$couponCharLen = (strlen($couponChars)-1);

    	$couponLength = 6;

    	$couponCode = '';

    	$couponCheck = '0';

    	while($couponCheck == '0')

    	{

    		for($i=0; $i<$couponLength; $i++)

    		{

    			$couponCode .= $couponChars[rand(0,$couponCharLen)];

    		}

    		$sql_coupon_check = sprintf("select * from viral_coupon_codes where code ='%s'",

                                mysqli_real_escape_string($link,$couponCode)

                );

    		$exe_coupon_check = mysqli_query($link,$sql_coupon_check) or die(mysqli_error($link));

    		if($exe_coupon_check && mysqli_num_rows($exe_coupon_check)==0)

    		{

    			$couponCheck = '1';

    		}

    	}

    	return strtolower($couponCode);

    }



    function addUserCoupon($phoneID,$groupID,$code)

    {

        global $link;

    	$ins = sprintf("insert into viral_coupon_codes 

    			(phone_number_id,group_id,code) values 

    			(%s, %s,'%s')",

                    mysqli_real_escape_string($link,DBin($phoneID)),

                    mysqli_real_escape_string($link,DBin($groupID)),

                    mysqli_real_escape_string($link,$code)

            );

    	mysqli_query($link,$ins) or die(mysqli_error($link));

    }



    function check_viral_coupon_code($code)

    {

        $code = DBin($code);

        global $link;

    	$sql = sprintf("select * from viral_coupon_codes where code='%s' order by id asc limit 1",

                    mysqli_real_escape_string($link,DBin($code))

            );

    	$res = mysqli_query($link,$sql);

    	if(mysqli_num_rows($res)>0)

    	{

    		$row = mysqli_fetch_assoc($res);

    		return $row;

    	}

    	return false;

    }



    function addViralFriend($phoneNumberID,$parentPhoneNumberID,$groupID,$status=0)

    {

        $phoneNumberID = DBin($phoneNumberID);

        $parentPhoneNumberID = DBin($parentPhoneNumberID);

        $groupID = DBin($groupID);

        $status = DBin($status);

        global $link;

        $sql = sprintf("select * from viral_friends where phone_number_id=%s and 

                            parent_phone_id = %s and group_id = %s",

                    mysqli_real_escape_string($link,DBin($phoneNumberID)),

                    mysqli_real_escape_string($link,DBin($parentPhoneNumberID)),

                    mysqli_real_escape_string($link,DBin($groupID))

            );

    	$res = mysqli_query($link,$sql);

    	if(mysqli_num_rows($res)==0)

    	{

    	   $sqlfrnd = sprintf("insert into viral_friends 

        				(`phone_number_id`, `parent_phone_id`, `group_id`,`status`) values

        				(%s, %s, %s,%s)",

                        mysqli_real_escape_string($link,DBin($phoneNumberID)),

                        mysqli_real_escape_string($link,DBin($parentPhoneNumberID)),

                        mysqli_real_escape_string($link,DBin($groupID)),

                        mysqli_real_escape_string($link,DBin($status))

               );

        	mysqli_query($link,$sqlfrnd);

 	    }

    }





    function getBatchDetails($scheduler_id,$type){

	    $scheduler_id = DBin($scheduler_id);

	    $type= DBin($type);

        global $link;

        $sql = sprintf("select * from batch where msg_id=%s and type = %s",

                        mysqli_real_escape_string($link,DBin($scheduler_id)),

                        mysqli_real_escape_string($link,DBin($type))

            );

    	$res = mysqli_query($link,$sql);

    	if(mysqli_num_rows($res)==0)

    	{

    	   $sqlfrnd = sprintf("insert into batch 

        				(`msg_id`, `type`, `last_id`) values

        				(%s, %s, '0')",

                    mysqli_real_escape_string($link,DBin($scheduler_id)),

                    mysqli_real_escape_string($link,DBin($type))

               );

        	mysqli_query($link,$sqlfrnd);

            $batch_id = mysqli_insert_id($link);

            return array("last_id"=>0,"id"=>$batch_id);

 	    }

        $row = mysqli_fetch_assoc($res);

        return $row;

    }



    function updateBatch($batch_id,$number_id){

	    $batch_id = DBin($batch_id);

	    $number_id = DBin($number_id);

        global $link;



        $sqlfrnd = sprintf("update batch set last_id = %s where id = %s",

                                mysqli_real_escape_string($link,DBin($number_id)),

                                mysqli_real_escape_string($link,DBin($batch_id))

            );

       	mysqli_query($link,$sqlfrnd);



    }







    function getSubscribersDetail($id){

	    $id = DBin($id);

        global $link;

        $sqlparentPhone = sprintf("select * from subscribers where id=%s",

                            mysqli_real_escape_string($link,DBin($id))

                );

		$resparentPhone = mysqli_query($link,$sqlparentPhone) or die(mysqli_error($link));

		$rowparentPhone = mysqli_fetch_assoc($resparentPhone);

		return $rowparentPhone;

    }



    function count_friend($groupID,$parentPhoneID){

	    $groupID = DBin($groupID);

	    $parentPhoneID = DBin($parentPhoneID);

        global $link;

        $sqlcount = sprintf("select count(id) as count_friend from viral_friends where 

                        group_id=%s and status ='1' and parent_phone_id=%s",

                    mysqli_real_escape_string($link,DBin($groupID)),

                    mysqli_real_escape_string($link,DBin($parentPhoneID))

            );

        $rescount = mysqli_query($link,$sqlcount) or die(mysqli_error($link));

        $rowcount = mysqli_fetch_assoc($rescount);

        return $rowcount['count_friend'];

    }





    function addGiftTracking($phoneNumberID,$groupID,$userID,$winNumber)

    {

        $phoneNumberID = DBin($phoneNumberID);

        $userID  = DBin($userID);

        $groupID = DBin($groupID);

        $winNumber = DBin($winNumber);

        global $link;

        if($winNumber == '1')

		{

			$isGift = '1';

			$currentCycle= getContestCycleNumber($groupID,$userID);



			$sqlUpdate = sprintf("insert into campaign_gift_track 

										(

                                                 phone_number_id,

                                                 campaign_id, 

                                                 client_id, 

                                                 counter, 

                                                 is_gift,

                                                 gift_number,

                                                 cycle_number

										 ) 

										 values 

										(

                                                '%s',

                                                '%s',

                                                '%s',

                                                '0',

                                                '%s',

                                                '1',

                                                '%s'

										)",

                                                mysqli_real_escape_string($link,DBin($phoneNumberID)),

                                                mysqli_real_escape_string($link,DBin($groupID)),

                                                mysqli_real_escape_string($link,DBin($userID)),

                                                mysqli_real_escape_string($link,DBin($isGift)),

                                                mysqli_real_escape_string($link,DBin($currentCycle))

                );

			mysqli_query($link,$sqlUpdate);



			$newCycleNum = ($currentCycle+1);

			$upcycle_number = sprintf("update campaigns set 

									  contest_cycle_num=%s where 

									  id=%s and

									  user_id=%s 

									  limit 1",

                                    mysqli_real_escape_string($link,DBin($newCycleNum)),

                                    mysqli_real_escape_string($link,DBin($groupID)),

                                    mysqli_real_escape_string($link,DBin($userID))

                );

			mysqli_query($link,$upcycle_number);

			return $isGift;

		}

		else

		{

			$sql = sprintf("

                            select id,counter,is_gift from campaign_gift_track where 

                            campaign_id=%s and client_id=%s order by id desc limit 1",

                    mysqli_real_escape_string($link,DBin($groupID)),

                    mysqli_real_escape_string($link,DBin($userID))

                );

			$res = mysqli_query($link,$sql);

			$row = mysqli_fetch_assoc($res);

			$currentMaxCounter = $row['counter'];

			$nextCounterValue  = $currentMaxCounter+1;



			if($nextCounterValue == $winNumber)

			{

				$isGift = '1';

				$currentCycle = getContestCycleNumber($groupID,$userID);

				$newCycleNum 	= ($currentCycle+1);



				$sqlUpdate = sprintf("insert into campaign_gift_track 

							(

							phone_number_id,

							 campaign_id,

							  client_id, 

							  counter, 

							  is_gift,

							  gift_number,

							  cycle_number

							  ) values 

							(

							'%s',

							'%s',

							'%s',

							'0',

							'%s',

							'%s',

							'%s'

							)",

                        mysqli_real_escape_string($link,DBin($phoneNumberID)),

                        mysqli_real_escape_string($link,DBin($groupID)),

                        mysqli_real_escape_string($link,DBin($userID)),

                        mysqli_real_escape_string($link,DBin($isGift)),

                        mysqli_real_escape_string($link,DBin($nextCounterValue)),

                        mysqli_real_escape_string($link,DBin($newCycleNum))



                );

				mysqli_query($link,$sqlUpdate);



				$upcycle_number = sprintf("update campaigns set 

										  contest_cycle_num= %s  where 

										  id=%s and

										  user_id=%s 

										  limit 1",

                                        mysqli_real_escape_string($link,DBin($newCycleNum)),

                                        mysqli_real_escape_string($link,DBin($groupID)),

                                        mysqli_real_escape_string($link,DBin($userID))

                    );

				mysqli_query($link,$upcycle_number);

			}

			else

			{

				$isGift = '0';

				$currentCycle= getContestCycleNumber($groupID,$userID);



				$sqlUpdate = sprintf("insert into campaign_gift_track 

							(

							phone_number_id,

							 campaign_id,

							  client_id,

							   counter,

							    is_gift,

							    gift_number,

							    cycle_number

							    ) values 

							(

							'%s',

							'%s',

							'%s',

							'%s',

							'%s',

							'%s',

							'%s'

							)",

                            mysqli_real_escape_string($link,DBin($phoneNumberID)),

                            mysqli_real_escape_string($link,DBin($groupID)),

                            mysqli_real_escape_string($link,DBin($userID)),

                            mysqli_real_escape_string($link,DBin($nextCounterValue)),

                            mysqli_real_escape_string($link,DBin($isGift)),

                            mysqli_real_escape_string($link,DBin($nextCounterValue)),

                            mysqli_real_escape_string($link,DBin($currentCycle))

                    );

				mysqli_query($link,$sqlUpdate);

			}

			return $isGift;

		}

    }



    function getContestCycleNumber($groupID,$userID)

    {

        $groupID = DBin($groupID);

        $userID = DBin($userID);

        global $link;

    	$selCycleNum = sprintf("select contest_cycle_num from campaigns where id=%s and user_id=%s limit 1",

                    mysqli_real_escape_string($link,DBin($groupID)),

                    mysqli_real_escape_string($link,DBin($userID))

            );

    	$resCycleNum = mysqli_query($link,$selCycleNum);

    	$rowCycleNum = mysqli_fetch_assoc($resCycleNum);

    	$currentCycle= $rowCycleNum['contest_cycle_num'];

    	return $currentCycle;

    }



    function highlightMatch($match,$content){

	    $match = DBin($match);

	    $content = DBin($content);

        $msg = str_replace($match,$match,$content);

        return $msg;

    }



	function insert_queue_msg($toNumber,$fromNumber,$alertMessage,$alertMedia,$altTime,$userID,$groupID){
		global $link;
		$shcAlts = sprintf("insert into queued_msgs

								(

									to_number,

									from_number,

									message,

									media,

									type,

									message_time,

									user_id,

									group_id

								)

							values

								(

									'%s',

									'%s',

									'%s',

									'%s',

									'1',

									'%s',

									'%s',

									'%s'

								)",

									mysqli_real_escape_string($link,DBin($toNumber)),

									mysqli_real_escape_string($link,DBin($fromNumber)),

									mysqli_real_escape_string($link,DBin($alertMessage)),

									mysqli_real_escape_string($link,DBin($alertMedia)),

									mysqli_real_escape_string($link,DBin($altTime)),

									mysqli_real_escape_string($link,DBin($userID)),

									mysqli_real_escape_string($link,DBin($groupID))

			);
		// echo $shcAlts;
		mysqli_query($link,$shcAlts);
	}


	function twilio_total_msg($from,$to){
		global $link;
		$date = date('Y-m-d');
		// $res = mysqli_query($link,"SELECT * FROM `sms_history` WHERE to_number='".$to."' AND from_number='".$from."' and created_date LIKE '%".$date."%'");
		$res = mysqli_query($link,"SELECT * FROM `sms_history` WHERE from_number='".$from."' and created_date LIKE '%".$date."%'");

		return mysqli_num_rows($res);

	}

?>