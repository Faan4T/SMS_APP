<?php

	include_once("header.php");

	include_once("left_menu.php");

	$phoneID = DBin(decode($_REQUEST['phoneid']));

	$sql = sprintf(

	        "select ch.*, s.phone_number, s.first_name from chat_history ch, subscribers s where 

                            ch.phone_id=%s and s.id=ch.phone_id order by id asc",

                mysqli_real_escape_string($link,DBin($phoneID))

        );

	$res = mysqli_query($link,$sql);

	$messages = mysqli_num_rows($res);

	$currenttime = DBout(strtotime(date('Y-m-d H:i:s')));



?>

<link type="text/css" rel="stylesheet" href="css/chat.css" />

<div class="main-panel">

	<?php include_once('navbar.php');?>

	<div class="content">

		<div class="container-fluid">

			<div class="row">

				<div class="col-md-12">

					<div class="card">

						<div class="header">

							<h4 class="title">

								Chat with <?php echo DBout(DBin($_REQUEST['ph']));?> from &nbsp;

								<select name="from_number" id="from_number" class="form-control width-17-per display-inline" onChange="showListOfDevivces(this)">

								<?php

									$sqlm = "select * from mobile_devices where user_id='".$_SESSION["user_id"]."'";

									$resm = mysqli_query($link,$sqlm);

									

									if($adminSettings['sms_gateway']=='twilio'){

										$sel = sprintf("select id,phone_number from users_phone_numbers where user_id=%s and type='1'",

                                                mysqli_real_escape_string($link,DBin($_SESSION['user_id']))

                                            );

									}else if($adminSettings['sms_gateway']=='plivo'){

										$sel = sprintf("select id,phone_number from users_phone_numbers where user_id=%s and type='2'",

                                                mysqli_real_escape_string($link,DBin($_SESSION['user_id']))

                                            );

									}else if($adminSettings['sms_gateway']=='nexmo'){

										$sel = sprintf("select id,phone_number from users_phone_numbers where user_id=%s and type='3'",

                                                mysqli_real_escape_string($link,DBin($_SESSION['user_id']))

                                            );

									}

									$exe = mysqli_query($link,$sel);

									if(mysqli_num_rows($exe)){

										while($rec = mysqli_fetch_assoc($exe)){

											?>

											<option value="<?php echo DBout(urlencode($rec['phone_number']))?>"><?php echo DBout($rec['phone_number'])?></option>

											<?php

										}

										if(mysqli_num_rows($resm)){

											echo '<option value="mobile_sim">- Select Mobile Device -</option>';

										}

									}else{ ?>

                                    <option value="">No from phone available.</option>

									<?php

                                    }

								?>

								</select>

								<?php

									// loading mobile devices

									if(mysqli_num_rows($resm)){

										echo '<select name="device_id" id="deviceList" class="form-control width-17-per display-inline; " >';

										while($rowm = mysqli_fetch_assoc($resm)){

											echo '<option value="'.$rowm['id'].'">'.$rowm['device_name'].'</option>';

										}

										echo '</select>';

									}

									// end

								?>

								

								<input type="button" class="btn btn-primary move-right" value="Back" onclick="window.location='view_subscribers.php'" />

							</h4>

							<p class="category">One by one chat history.</p>

						</div>

						<div class="content table-responsive">

<div class="panel panel-primary">

	<div class="panel-heading">

		<span class="fa fa-comment"></span> Chat

	</div>

	<div class="panel-body" id="chat_container">

		<ul class="chat">

		<?php

			if($messages>0){

				while($row = mysqli_fetch_assoc($res)){

					$ago = timeAgo($row['created_date']);

					if($row['direction']=='in'){

		?>

				<li class="left clearfix"><span class="chat-img pull-left">

					<img src="https://thumbs.dreamstime.com/b/default-avatar-profile-icon-vector-social-media-user-portrait-176256935.jpg" style="width: 50px;" alt="User Avatar" class="img-circle" />

				</span>

					<div class="chat-body clearfix">

						<div class="header chat_header">

							<strong class="primary-font"><?php echo DBout($row['first_name'])?></strong> <small class="pull-right text-muted">

								<span class="fa fa-clock-o"></span><?php echo DBout($ago)?></small>

						</div>

						<p><?php echo DBout($row['message'])?></p>

					</div>

				</li>

		<?php				

					}else{

		?>

				<li class="right clearfix"><span class="chat-img pull-right">

					<img src="https://thumbs.dreamstime.com/b/default-avatar-profile-icon-vector-social-media-user-portrait-176256935.jpg" style="width: 50px;" alt="User Avatar" class="img-circle" />

				</span>

					<div class="chat-body clearfix">

						<div class="header chat_header">

							<small class=" text-muted"><span class="fa fa-clock-o"></span><?php echo DBout($ago)?></small>

							<strong class="pull-right primary-font"><?php echo DBout($_SESSION['first_name'])?></strong>

						</div>

						<p><?php echo DBout($row['message'])?></p>

					</div>

				</li>

		<?php				

					}

				}

			}else{

		?>

				<li class="right clearfix">

					<div class="chat-body clearfix">

						<p>

							No chat history to display.								

						</p>

					</div>

				</li>

		<?php			

			}

		?>

		</ul>

	</div>

	<div class="panel-footer">

		<div class="input-group">

			<input id="chat_message" type="text" class="form-control input-sm" placeholder="Type your message here..." onkeypress="checkKey(event);" />

			<span class="input-group-btn">

				<button class="btn btn-warning btn-sm" onclick="sendChatMessage()">Send</button>

			</span>

		</div>

	</div>

</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

	<?php include_once("footer_info.php");?>

</div>

<?php include_once("footer.php");?>

<script>

	function showListOfDevivces(obj){

		if($(obj).val()=='mobile_sim'){

			$('#deviceList').css("display","inline-block");

		}else{

			$('#deviceList').css("display","none");

		}

	}

    var phone_id = "<?php echo DBout($phoneID)?>"

    var to_number = "<?php echo DBout(urlencode(DBin($_REQUEST['ph'])))?>";

    var first_name = <?php echo DBout($_SESSION['first_name'])?>;

</script>

<script src="scripts/chat.js"></script>