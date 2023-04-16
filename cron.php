<?php
include_once("database.php");
include_once("functions.php");

$sql = "select * from queued_msgs where status = 0";
$res = mysqli_query($link,$sql);
if(mysqli_num_rows($res)){
    $date = date('Y-m-d H:i');
    $index = 1;
    while($row = mysqli_fetch_assoc($res)){
		$subscriberData = getSubscribersDetail($row['to_number']);
		
        $userID = $row['user_id'];
        $appInfo  = getAppSettings($userID);
        $timeZone = DBout($appInfo['time_zone']);
        date_default_timezone_set($timeZone);
        $date = date('Y-m-d H:i');
        if((trim($appInfo['cron_stop_time_from'])!='')&&(trim($appInfo['cron_stop_time_to'])!='')){
            $fromTime = preg_replace('/\s+/', '', DBout($appInfo['cron_stop_time_from']));
            $toTime   = preg_replace('/\s+/', '', DBout($appInfo['cron_stop_time_to']));
            $fromTime = explode(":", DBout($fromTime));
            $toTime   = explode(":",DBout($toTime));
            $fromT = DBout($fromTime[0].":".$fromTime[1].$fromTime[2]);
            $toT   =  DBout($toTime[0].":".$toTime[1].$toTime[2]);
            $cronfromTime = DBout(date("Gi", strtotime($fromT)));
            $crontoTime   = DBout(date("Gi", strtotime($toT)));
            if(($fromTime < date("Gi")) && ($toTime > date("Gi"))){
                echo ("Cron stop time has been started for user id ".$userID);
                continue;
            }
        }

        $userPkgStatus = checkUserPackageStatus($userID);
        if($userPkgStatus['go']==false){
            $remainingCredits = 0;
            echo ($userPkgStatus['message']);
            continue;
        }else{
            $remainingCredits = DBout($userPkgStatus['remaining_credits']);
        }

        $deviceID	= DBout($row['device_id']);
        if($row['type']=='2'){
            if($row['message_time'] <= $date){
                $from	= DBout($row['from_number']);
                $to		= DBout($row['to_number']);
                $body	= DBout($row['message']);
				$body   = str_replace('%name%',$subscriberData['first_name'],$body);
                $userID	= DBout($row['user_id']);
                $groupID= DBout($row['group_id']);
                echo sendMessage($from,$to,$body,$row['media'],$userID,$groupID,$deviceID);

                $up = sprintf("delete from
									queued_msgs
								where
									id=%s ",
                mysqli_real_escape_string($link,DBin($row['id'])));
                mysqli_query($link,$up);
            }
        }
        else{
            $from	= DBout($row['from_number']);
            $to		= DBout($row['to_number']);
            $body	= DBout($row['message']);
            $body   = str_replace('%name%',$subscriberData['first_name'],$body);
            $userID	= DBout($row['user_id']);
            $groupID= DBout($row['group_id']);
            echo sendMessage($from,$to,$body,$row['media'],$userID,$groupID,$deviceID);
			
            $up = sprintf("delete from
								queued_msgs
							where
								id=%s ",
                mysqli_real_escape_string($link,DBin($row['id']))
            );
            mysqli_query($link,$up);
        }
        $index++;
    }
}
else {
    echo DBout('No pending queued message found.');
}



$sql = sprintf("select * from schedulers where status='0'");
$res = mysqli_query($link,$sql);
if(mysqli_num_rows($res)){
    $adminSettings = getAppSettings('',true);
    while($row = mysqli_fetch_assoc($res)) {
        $userID = $row['user_id'];
        $userInfo = getUserInfo($userID);
        $appInfo = getAppSettings($userID);
        $timeZone = DBout($appInfo['time_zone']);
        date_default_timezone_set($timeZone);
        $date = date('Y-m-d H:i');
        if((trim($appInfo['cron_stop_time_from'])!='')&&(trim($appInfo['cron_stop_time_to'])!='')){
            $fromTime = preg_replace('/\s+/', '', DBout($appInfo['cron_stop_time_from']));
            $toTime   = preg_replace('/\s+/', '', DBout($appInfo['cron_stop_time_to']));
            $fromTime = explode(":", DBout($fromTime));
            $toTime   = explode(":",DBout($toTime));
            $fromT = DBout($fromTime[0].":".$fromTime[1].$fromTime[2]);
            $toT   =  DBout($toTime[0].":".$toTime[1].$toTime[2]);
            $cronfromTime = DBout(date("Gi", strtotime($fromT)));
            $crontoTime   = DBout(date("Gi", strtotime($toT)));
            if(($fromTime < date("Gi")) && ($toTime > date("Gi"))){
                echo ("Cron stop time has been started for user id ".$userID);
                continue;
            }
        }

        $userPkgStatus = checkUserPackageStatus($userID);
        if ($userPkgStatus['go'] == false) {
            $remainingCredits = 0;
            echo($userPkgStatus['message']);
            continue;
        } else {
            $remainingCredits = DBout($userPkgStatus['remaining_credits']);
        }
        if ($row['send_immediate'] == '1') {
            if ($row['attach_mobile_device'] == '1') {
                $from = DBout('mobile_sim');
            } else {
                $nn = sprintf("select phone_number from campaigns where id=%s and phone_number!=''",
                    mysqli_real_escape_string($link, DBin($row['group_id']))
                );
                $n = mysqli_query($link, $nn);
                if (mysqli_num_rows($n)) {
                    $fromNumber = mysqli_fetch_assoc($n);
                    $from = DBout($fromNumber['phone_number']);
                } else {
                    echo('No from phone number found.');
                }

                $deviceID = $row['device_id'];
                $batchData = getBatchDetails($row['id'], "scheduler");
                $last_id = DBout($batchData['last_id']);
                if ($row['phone_number'] == 'all') {
                    $groupID = DBout($row['group_id']);
                    $sqlpnga = sprintf("select s.phone_number, s.id from subscribers s, subscribers_group_assignment sga where sga.group_id=%s and sga.subscriber_id=s.id and s.status='1' and s.id > %s order by s.id asc",
                        mysqli_real_escape_string($link, DBin($groupID)),
                        mysqli_real_escape_string($link, DBin($last_id))
                    );
                    $respnga = mysqli_query($link, $sqlpnga);
                    $no_records = mysqli_num_rows($respnga);
                    if ($no_records > 0) {
                        while ($number = mysqli_fetch_assoc($respnga)) {
                            sendMessage($from, $number['phone_number'], $row['message'], $row['media'], $userID, $groupID, $deviceID);
                            updateBatch($batchData['id'], $number['id']);
                        }
                        $sql1 = sprintf("update schedulers set status='1' where id=%s ",
                            mysqli_real_escape_string($link, DBin($row['id']))
                        );
                        mysqli_query($link, $sql1);
                    } else {
                        $sql1 = sprintf("update schedulers set status='1' where id=%s ",
                            mysqli_real_escape_string($link, DBin($row['id']))
                        );
                        mysqli_query($link, $sql1);
                    }
                }
                else{
                    $phoneID = DBout($row['phone_number']);
                    $sqlqq = sprintf("select phone_number from subscribers where id in (%s) and status='1'",
                        mysqli_real_escape_string($link, DBin($phoneID))
                    );
                    $r = mysqli_query($link, $sqlqq);
                    $number = mysqli_fetch_assoc($r);
                    $toPhone = DBout($number['phone_number']);
                    sendMessage($from, $toPhone, $row['message'], $row['media'], $userID, $row['group_id'], $deviceID);
                    $sql2 = sprintf("update schedulers set status='1' where id=%s ",
                        mysqli_real_escape_string($link, DBin($row['id']))
                    );
                    mysqli_query($link, $sql2);
                }
            }
        }
        else {

            $sel = "select * from schedulers where id=".$row['id']." and status='0' and date_format(scheduled_time,'%Y-%m-%d %H:%i')<='" . DBin($date) . "'";
            $query = mysqli_query($link,$sel);
            if(mysqli_num_rows($query) > 0) {
                if ($row['attach_mobile_device'] == '1') {
                    $from = DBout('mobile_sim');
                } else {
                    $nn = sprintf("select phone_number from campaigns where id=%s and phone_number!=''",
                        mysqli_real_escape_string($link, DBin($row['group_id']))
                    );
                    $n = mysqli_query($link, $nn);
                    if (mysqli_num_rows($n)) {
                        $fromNumber = mysqli_fetch_assoc($n);
                        $from = DBout($fromNumber['phone_number']);
                    } else {
                        echo('No from phone number found.');
                    }
                }
                $deviceID = $row['device_id'];
                $batchData = getBatchDetails($row['id'], "scheduler");
                $last_id = DBout($batchData['last_id']);
                if ($row['phone_number'] == 'all') {
                    $groupID = DBout($row['group_id']);
                    $sqlpnga = sprintf("select s.phone_number, s.id from subscribers s, subscribers_group_assignment sga where sga.group_id=%s and sga.subscriber_id=s.id and s.status='1' and s.id > %s order by s.id asc",
                        mysqli_real_escape_string($link, DBin($groupID)),
                        mysqli_real_escape_string($link, DBin($last_id))
                    );
                    $respnga = mysqli_query($link, $sqlpnga);
                    $no_records = mysqli_num_rows($respnga);
                    if ($no_records > 0) {
                        while ($number = mysqli_fetch_assoc($respnga)) {
                            sendMessage($from, $number['phone_number'], $row['message'], $row['media'], $userID, $groupID, $deviceID);
                            updateBatch($batchData['id'], $number['id']);
                        }
                        $sql1 = sprintf("update schedulers set status='1' where id=%s ",
                            mysqli_real_escape_string($link, DBin($row['id']))
                        );
                        mysqli_query($link, $sql1);
                    } else {
                        $sql1 = sprintf("update schedulers set status='1' where id=%s ",
                            mysqli_real_escape_string($link, DBin($row['id']))
                        );
                        mysqli_query($link, $sql1);
                    }
                } else {
                    $phoneID = DBout($row['phone_number']);
                    $sqlqq = sprintf("select phone_number from subscribers where id in (%s) and status='1'",
                        mysqli_real_escape_string($link, DBin($phoneID))
                    );
                    $r = mysqli_query($link, $sqlqq);
                    $number = mysqli_fetch_assoc($r);
                    $toPhone = DBout($number['phone_number']);
                    sendMessage($from, $toPhone, $row['message'], $row['media'], $userID, $row['group_id'], $deviceID);
                    $sql2 = sprintf("update schedulers set status='1' where id=%s ",
                        mysqli_real_escape_string($link, DBin($row['id']))
                    );
                    mysqli_query($link, $sql2);
                }
            }
        }
    }
}
else{
    echo DBout('No pending scheduler found.');
}
?>