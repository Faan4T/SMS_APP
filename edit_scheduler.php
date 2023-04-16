<?php
	include_once("header.php");
	include_once("left_menu.php");
	if($_REQUEST['id']!=''){
		$sql = sprintf("select * from schedulers where id='%s'",
                mysqli_real_escape_string($link,DBin($_REQUEST['id']))
            );
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
		}else{
			$row = array();
		}
	}
	if($row['send_immediate']=='1'){
		$immediate = 'checked="checked"';
		$dateSection = DBout('none');
	}else{
		$immediate = '';
		$dateSection = DBout('block');
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
								Edit SMS Scheduler
								<input type="button" class="btn btn-primary move-right" value="Back" onclick="window.location='view_scheduler.php'" />
							</h4>
							<p class="category red"><?php echo DBout(date('g:iA \o\n l jS F Y'))?></p>
						</div>
						<div class="content table-responsive">
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
							<div class="form-group">
								<label>Title*</label>
								<input type="text" name="title" parsley-trigger="change" required placeholder="Enter title..." class="form-control" value="<?php echo DBout($row['title'])?>">
							</div>
							<div class="dateTimeSection" style="display:<?php echo DBout($dateSection)?>">
								<div class="form-group">
									<label>Date*</label>
									<input type="text" class="form-control addDatePicker" name="date" parsley-trigger="change" required value="<?php echo DBout(date('Y-m-d',strtotime($row['scheduled_time'])));?>">
								</div>
								<div class="form-group">
									<?php
										$chtime = DBout(date('H:i',strtotime($row['scheduled_time'])));
									?>
									<label>Time*</label>
									<select name="time" class="form-control" parsley-trigger="change" required>
									<?php
										$time = getTimeArray();
										foreach($time as $key => $value){
											if($chtime==$key){
												$selected = DBout('selected="selected"');
											}else{
												$selected = '';
											}?>
                                        <option <?php echo DBout($selected) ?> value="<?php echo DBout($key) ?>"><?php echo DBout($value)?></option>
									<?php	}
									?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label><input <?php echo DBout($immediate)?> name="send_immediate" value="1" type="checkbox" /> Send Immediate</label>
							</div>
							<div class="form-group">
								<label><input name="attach_mobile_device" <?php if($row['attach_mobile_device']=='1')echo DBout('checked="checked"');else echo DBout('');?> value="1" type="checkbox" /> Attach mobile device</label>
							</div>
                            <?php if($row['custom']==1){
                            ?>    
                                <div class="form-group">
    								<label>Custom Search <b>"<?php echo DBout($row['search']); ?>"</b></label>
    								<input type="hidden" name="search" value="<?php echo DBout($row['search']); ?>">
                                    <input type="hidden" name="custom" value="1">
    							</div>
                            <?php
                            }
                            ?>
							<div class="form-group">
								<label>Select Group</label>
								<select class="form-control" name="group_id" onChange="getGroupNumbers(this.value,'<?php echo DBout($row['phone_number'])?>')" parsley-trigger="change" <?php if($row['custom']!=1){?> required <?php } ?>>
									<option value="">- Select One -</option>
								<?php
									$sqlg = sprintf("select id, title from campaigns where user_id=%s",
                                            mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
                                        );
									$resg = mysqli_query($link,$sqlg);
									if(mysqli_num_rows($resg)){
										while($rowg = mysqli_fetch_assoc($resg)){
											if($row['group_id']==$rowg['id']) {
                                                $sel = DBout('selected="selected"');
                                            }
											else {
                                                $sel = '';
                                            } ?>
                                    <option <?php echo DBout($sel)?> value="<?php echo DBout($rowg['id'])?>"><?php echo DBout($rowg['title'])?></option>';
									<?php	}
									}else{?>
                                    <option value="">No group found</option>
								<?php	}
								?>		
								</select>
							</div>
                            
                            <?php
                            $multiple="";
                            $f_name = "phone_number";
                            $pos = strpos($row['phone_number'], ",");
                            if($pos!==false){
                                $multiple=DBout("multiple='multiple'");
                                $f_name = DBout("phone_number[]");
                            }
                            ?>
							<div class="form-group">
								<label>Select Number</label>
								<select name="<?php echo DBout($f_name); ?>" class="form-control" id="list_group_number" <?php echo DBout($multiple); ?>></select>
							</div>
							<div class="form-group">
								<label>Message</label>
								<textarea name="message" class="form-control textCounter" parsley-trigger="change" required><?php echo DBout($row['message'])?></textarea>
								<span class="showCounter">
									<span class="showCount"><?php echo DBout($maxLength-strlen($row['message']))?></span> Characters left
								</span>
							</div>
							<div class="form-group">
								<label>Select Survey (Optional)</label>
								<select class="form-control" name="survey_url">
									<option value="">- Select One -</option>
								<?php
									$sqlg = sprintf("select survey_name,survey_link from surveys where user_id=%s ",
                                                mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
                                        );
									$resg = mysqli_query($link,$sqlg);
									if(mysqli_num_rows($resg)){
										while($rowg = mysqli_fetch_assoc($resg)){
											if($row['survey_url']==$rowg['survey_link'])
												$sel = DBout('selected="selected"');
											else
												$sel = ''; ?>
										<option <?php echo DBout($sel)?> value="<?php echo DBout($rowg['survey_link'])?>"><?php echo DBout($rowg['survey_name'])?></option>';
									<?php	}
									}else{ ?>

                                    <option value="">No survey found</option>
								<?php	}
								?>		
								</select>
							</div>
							<div class="form-group">
								<label>Media</label>
								<input type="file" name="media" class="display-inline">
								<input type="hidden" name="hidden_media" value="<?php echo DBout($row['media'])?>">
								<?php 
									echo DBout(isMediaExists($row['media']));
								?>
							</div>
							<div class="form-group text-right m-b-0">
								<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
								<input type="hidden" name="cmd" value="update_scheduler" />
								<input type="hidden" name="scheduler_id" value="<?php echo DBout($row['id'])?>">
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
<script src="scripts/edit_scheduler.js"></script>
<?php if(trim($_REQUEST['id'])!=''){?>
	<script>
        getGroupNumbers('<?php echo DBout($row['group_id'])?>','<?php echo DBout($row['phone_number'])?>')
    </script>
<?php }?>

