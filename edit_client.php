<?php
	include_once("header.php");
	include_once("left_menu.php");
	$id = $_REQUEST['id'];
	$sql = "select * from campaigns where id='".$id."'";
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){
		$row = mysqli_fetch_assoc($res);
	}else{
		die("Client already deleted.");
	}
?>
<div class="main-panel">
	<?php include_once( 'navbar.php' ); ?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title"> Update Campaign
                                <input type="button"
                                       class="btn btn-primary move-right"
                                       value="Back"
                                       onclick="window.location='view_campaigns.php'"/>
                            </h4>
							<p class="category">Update your clients here.</p>
						</div>
						<div class="content table-responsive">
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
								
								<div class="form-group">
									<label>Company*</label> 
									<select name="company_id" class="form-control" required>
										<?php
											$sql = "select * from companies where user_id='".$_SESSION['user_id']."'";
											$exe = mysqli_query($link,$sql);
											if(mysqli_num_rows($exe)){
												while($r = mysqli_fetch_assoc($exe)){
													$selected = ($row['company_id'] == $r['id']) ? 'selected' : '';
													echo '<option '.$selected.' value="'.$r['id'].'">'.$r['name'].'</option>';
												}
											}
										?>
									</select>
								</div>
								
								<div class="form-group">
									<label>Campaign Name*</label> 
									<input type="text" name="client_name" required placeholder="Enter title..." class="form-control" value="<?php echo $row['title'];?>">
								</div>
								<div class="form-group"><label>Notification SMS* <?php echo 'len'.$maxLength;?></label><br>
									<span style="color: brown; font-size: 12px;"> %fn% = first name, %fni% = first initial, %ln% = last name,%lni% = last initial, %add% = address, %ci% = city, %pro% = province, %pc% = postal code, %ac% = account number, %ao% = amount owing, %srd% = services renders, %li% = link, %srv% = service</span>
									<textarea name="notification_sms" required placeholder="Enter notification sms text..." class="form-control textCounter"><?php echo $row['notification_msg'];?></textarea>
									<span class="showCounter"> <span class="showCount"><?php echo ($maxLength-strlen(DBout($row['notification_msg']))); ?></span> Characters left </span>
								</div>
								<!--
								<div class="form-group"><label>Upload CSV</label>
									<input type="file" name="company_contacts" class="display-inline"/>
								</div>
								-->
								<div class="form-group text-right m-b-0">
									<input type="hidden" name="client_id" value="<?php echo $row['id'];?>">
									<button class="btn btn-primary waves-effect waves-light" type="submit"> Update
                                    </button>
									<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel
                                    </button>
									<input type="hidden" name="cmd" value="update_client"/>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once( "footer_info.php" ); ?>
</div>
<?php include_once( "footer.php" ); ?>
<script type="text/javascript">
	var timeoptions = '<?php echo $timeOptions?>';
	var options = '<?php echo $options?>';
	var maxlenght = '<?php echo DBout( $maxLength ); ?>';
	
	$('body').on('keyup','.textCounter',function(){
		var len = $(this).val().length;
		if(len>=maxLength){
			var chars = $(this).val().substring(0,maxLength);
			$(this).val(chars);
			$(this).closest('div').find('.showCount').text(maxLength-chars.length);
		}
		else{
			$(this).closest('div').find('.showCount').text(maxLength-len);
		}
	});

	//$('.showCount').text(maxLength);
	
</script>
<script src="scripts/add_campaign.js"></script>