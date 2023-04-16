<?php

include_once'database.php';

$get_user_gateway = mysqli_query($link,"select sms_gateway from application_settings where user_id=".$_SESSION['user_id']);

$pageName = getCurrentPageName();

	if($pageName!='edit_app_user.php'){

		if($_SESSION['user_type']=='1'){

			if(trim($appSettings['sms_gateway'])==''){

			?>

<div class="alert alert-danger"><span><b> Warning - </b> Application settings are not configured, please configure sms gateway settings <a href="settings.php" class="white"><b>here</b></a>.</span></div>

<?php			}

		}

		else if($_SESSION['user_type']=='2'){

			if(mysqli_num_rows($get_user_gateway) == 0){ ?>

			<div class="alert alert-danger"><span><b> Warning - </b>Application settings are not configured,</div>

	<?php		}

		}

	}

?>

<nav class="navbar navbar-default navbar-fixed">

	<div class="container-fluid">

		<div class="navbar-header">

			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>

			<a class="navbar-brand" href="javascript:void(0)"><?php echo DBout($business_name);?></a> </div>

		<div class="collapse navbar-collapse">

			<ul class="nav navbar-nav navbar-right">

				<?php 

                if(0 && $pkgStatus['go']==false){

                ?>

				<li>

                    

                    <a href="javascript:void(0)">

					   <p class="pkg_status"><?php echo DBout($pkgStatus['message'])?></p>

					</a>

                </li>

				<?php }?>

				

				<?php

				if($_SESSION['user_type']=='1'){

				     $server_name =  $_SERVER['HTTP_HOST'];

                     $server_name = str_replace("www.","",$server_name);

                     if($server_name!="herbert.securedserverspace.com"){

                    ?>

				<!--

                <li> <a href="https://codecanyon.net/item/nimble-messaging-business-mobile-sms-marketing-application-for-android/20956083" target="_blank"><i class="fa fa-android" aria-hidden="true"></i>&nbsp;Get Mobile App</a> </li>

				-->

                <?php 

                    }

                }

                 ?>

				

				<?php

				if($_SESSION['user_type']=='1'){

					if($displayUpdate=='none'){

				?>

				<!--

				<li>

				<?php 

					if(trim($appVersion)!='')

						$appVersion = 'v'.$appVersion;

					?>

                    <a href="javascript:void(0)"><p><?php echo DBout($appVersion)?></p></a>



				</li>

				-->

				<?php }else{?>

					<!--<li> <a href="update_app.php" class="btn btn-danger">Update to <?php echo DBout($latestVersion)?></a> </li>-->

				<?php }



				}

				?>

				

				<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">

					<p> <?php echo DBout($_SESSION['first_name']).' '.DBout($_SESSION['last_name']);?> <b class="caret"></b> </p>

					</a>

					<ul class="dropdown-menu">

						<li><a href="profile.php"><i class="ti-user m-r-5"></i> Profile</a></li>

						<!--<li><a href="settings.php"><i class="ti-settings m-r-5"></i> Settings</a></li>-->

						<li class="separator"></li>

						<li><a href="server.php?cmd=logout">Log out</a></li>

					</ul>

				</li>

			</ul>

		</div>

	</div>

</nav>