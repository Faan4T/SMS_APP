<?php
session_start();
include_once("database.php");

$sql = sprintf("select * from users where id = %s",
        mysqli_real_escape_string($link,DBin($_SESSION['user_id']))
    );
$exe = mysqli_query($link,$sql);
$row = mysqli_fetch_assoc($exe);
$app_id = DBout($row['app_id']);
$fb_secret = DBout($row['app_secret']);

if(isset($_REQUEST['app_id']) && isset($_REQUEST['app_secret'])){    
    $sql = sprintf(
            "update users set 
                    app_id = %s, 
                    app_secret = %s, 
                    access_token=%s
                     where id = %s",
                    mysqli_real_escape_string($link,DBin($_REQUEST['app_id'])),
                    mysqli_real_escape_string($link,DBin($_REQUEST['app_secret'])),
                    mysqli_real_escape_string($link,DBin($_REQUEST['fb_user_access_token'])),
                    mysqli_real_escape_string($link,DBin($_SESSION['user_id']))
        );
    mysqli_query($link,$sql)or die(mysqli_error($link));
    $app_id = DBin($_REQUEST['app_id']);
    $fb_secret = DBin($_REQUEST['app_secret']);
}

    
$redirect_url = DBout(getServerURL().'api.php');
$redirect_url= DBout(urlencode($redirect_url));
$login_url=DBout("https://www.facebook.com/dialog/oauth?client_id=$app_id&redirect_uri=$redirect_url&response_type=code&scope=email,public_profile,publish_actions",ENT_COMPAT);
    

if(isset($_GET['code']) && $_GET['code']!="")
{
    $code = DBin($_GET['code']);
    $redirect_url = DBout(getServerURL().'api.php');
    $redirect_url= DBout(urlencode($redirect_url));
    $token_url=DBout("https://graph.facebook.com/oauth/access_token?client_id=$app_id&redirect_uri=$redirect_url&type=token&client_secret=$fb_secret&code=$code",ENT_COMPAT);
    $access_token=post_fb($token_url,"get");
    $fb_success=json_decode($access_token,true);
    if(is_array($fb_success) && $fb_success['access_token'] != "")
    {
	    $sql = sprintf("update users set access_token = %s where id = %s",
                mysqli_real_escape_string($link,DBin($fb_success['access_token'])),
                mysqli_real_escape_string($link,DBin($_SESSION['user_id']))
            );
        mysqli_query($link,$sql);  
    }
    else{
        $_SESSION['message'] = '<div class="alert alert-danger">Error While Connected to Facebook API, Please verify your app credentials and try again</div>';
        ?>
        <script> window.location = 'profile.php'; </script>
        <?php
        die();
        exit;
    }
    
    $_SESSION['message'] = '<div class="alert alert-success">Application Connected Successfully.</div>';
    ?>
    <script> window.location = 'profile.php'; </script>
    <?php
    die();
    exit;
}

function post_fb($url,$method,$body=""){
    $url = DBout($url);
    $method = DBout($method);
    $body = DBout($body);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if($method == "post"){
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    else{
        curl_setopt($ch, CURLOPT_HTTPGET, true );   
    }   
    return curl_exec($ch);
}


function post_fb2($url,$method,$body=""){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if($method == "post"){
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    }
    else{
        curl_setopt($ch, CURLOPT_HTTPGET, true );   
    }   
    return curl_exec($ch);
}
    
function getServerURL()
{
    $serverName = $_SERVER['SERVER_NAME'];
    $filePath = $_SERVER['REQUEST_URI'];
    $withInstall = substr($filePath,0,strrpos($filePath,'/')+1);
    $serverPath = $serverName.$withInstall;
    $applicationPath = $serverPath;
    
    if(strpos($applicationPath,'http://www.')===false)
    {
        if(strpos($applicationPath,'www.')===false)
            $applicationPath = 'www.'.$applicationPath;
        if(strpos($applicationPath,'http://')===false)
            $applicationPath = 'http://'.$applicationPath;
    }
    $applicationPath = str_replace("www.","",$applicationPath);
    return DBout($applicationPath);
}

header("location:".$login_url);
?>