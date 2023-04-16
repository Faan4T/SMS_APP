<?php
	include_once("header.php");
	include_once("left_menu.php");
	$id = decode(DBin($_REQUEST['id']));
	$sqlu = sprintf("select * from users where id=%s ",
                mysqli_real_escape_string($link,DBin($id))
        );
	$resu = mysqli_query($link,$sqlu);
	if(mysqli_num_rows($resu)==0)
		$rowu = array();
	else{
		$rowu = mysqli_fetch_assoc($resu);
		$appSettings = getAppSettings($id);
	}
?>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								Edit Application User
								<input type="button" class="btn btn-primary move-right" value="Back" onclick="window.location='view_user.php'" />
							</h4>
							<p class="category">Edit application user sub-account.</p>
						</div>
						<div class="content table-responsive">
							<form method="post" enctype="multipart/form-data" action="server.php">
		<div class="form-group">
			<label>First Name</label>
			<input type="text" name="first_name" class="form-control" value="<?php echo DBout($rowu['first_name'])?>" required>
		</div>
		<div class="form-group">
			<label>Last Name</label>
			<input type="text" name="last_name" value="<?php echo DBout($rowu['last_name'])?>" class="form-control" required>
		</div>
		<div class="form-group">
			<label>Login Email</label>
			<input type="email" name="email" value="<?php echo DBout($rowu['email'])?>" class="form-control" required>
		</div>
			<div class="form-group">
									<label>Company *</label>
									<?php
									// print_r($rowu);
									?>
									</select>
									<select name="client_id" class="form-control" required>
										<?php
											$sql = "select * from companies where user_id='".$_SESSION['user_id']."'";
											$exe = mysqli_query($link,$sql);
											if(mysqli_num_rows($exe)){
												while($r = mysqli_fetch_assoc($exe)){
													?> <option <?= ($rowu['Client'] == $r['name']) ? 'selected' : '' ?>  value="<?= $r['name'] ?>"><?= $r['name'] ?></option> <?php
												}
											}
										?> <br>
									</select>
								</div>
		<div class="form-group">
			<label>Select Time Zone</label>
			<select name="time_zone" class="form-control">
				<?php
					$sqlt = sprintf("select time_zone, time_zone_value from time_zones");
					$rest = mysqli_query($link,$sqlt);
					if(mysqli_num_rows($rest)){
						while($rowt = mysqli_fetch_assoc($rest)){
							if($appSettings['time_zone']==$rowt['time_zone'])
								$sele = DBout('selected="selected"');
							else
								$sele = '';
							?>

                <option <?php echo DBout($sele) ?>value="<?php echo DBout($rowt['time_zone'])?>"><?php echo DBout($rowt['time_zone_value'])?></option>
				<?php		}
					}
					else{ ?>
					<option value="">No time zone added yet.</option>
				<?php	}
				?>
			</select>
		</div>
		<div class="form-group">
			<label class="checkbox-inline">
				<input type="checkbox" value="1" class="margin-top-2" name="subs_lookup" <?php if($appSettings['subs_lookup']=='1')echo DBout('checked="checked"');?>> Subscriber lookup
			</label>
			<div class="alert alert-danger"><span>Twilio <b>Lookup</b> feature has been integrated with appliation which is paid featue, if you want to enable this feature for your customers, please navigate to edit user section in appllication.</span></div>
		</div>
		<div class="form-group">
			<label>Select Package Plan</label>
			<select name="pkg_id" class="form-control">
				<?php
                $sql1 = sprintf("select pkg_id,start_date,end_date from user_package_assignment where user_id=%s ",
                            mysqli_real_escape_string($link,DBin($id))
                    );
					$p = mysqli_query($link,$sql1);
					$r = mysqli_fetch_assoc($p);
					$sql = sprintf("select id, title from package_plans where user_id=%s ",
                            mysqli_real_escape_string($link,DBin($_SESSION['user_id']))
                        );
					$res = mysqli_query($link,$sql) or die(mysqli_error($link));
					if(mysqli_num_rows($res)){
						while($row = mysqli_fetch_assoc($res)){
							if($r['pkg_id']==$row['id'])
								$sel = 'selected="selected"';
							else
								$sel = '';
							?>
                <option <?php echo DBout($sel) ?> value="<?php echo DBout($row['id'])?>"><?php echo DBout($row['title'])?></option>
				<?php		}
					}
					else{ ?>
                <option value="">No plan created yet.</option>
				<?php	}
				?>
			</select>
		</div>
		<div class="form-group">
			<label>Package Start Date</label>
			<input type="text" name="start_date" class="form-control datepicker" value="<?php echo DBout(date('m/d/Y',strtotime($r['start_date'])));?>">
		</div>
		<div class="form-group">
			<label>Package End Date</label>
			<input type="text" name="end_date" class="form-control datepicker" value="<?php echo DBout(date('m/d/Y',strtotime($r['end_date'])));?>">
		</div>
		<div class="form-group">
			<label>Business Name</label>
			<input type="text" name="business_name" value="<?php echo DBout($rowu['business_name'])?>" class="form-control" required>
		</div>
		<div class="form-group">
			<label>Login Password</label>
			<input type="text" name="password" value="<?php echo decodePassword(DBout($rowu['password']))?>" class="form-control" required>
		</div>
		<div class="form-group">
			<label>Re-type Password</label>
			<input type="text" name="retype_password" class="form-control" required>
		</div>
		<div class="form-group text-right m-b-0">
			<input type="hidden" name="user_id" value="<?php echo DBout($id)?>">
			<input type="hidden" name="cmd" value="update_app_user_by_admin" />
			<button class="btn btn-primary waves-effect waves-light" type="submit"> Register Account </button>
			<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
		</div>
	</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>
<script src="scripts/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="scripts/js/parsley.min.js"></script> 
<script type="text/javascript" src="scripts/edit_app_user.js">
</script>