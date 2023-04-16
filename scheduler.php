<?php
	include_once("header.php");
	include_once("left_menu.php");
	if($_REQUEST['id']!=''){
		$sql = sprintf("select * from schedulers where id=%s ",
                mysqli_real_escape_string($link,DBin($_REQUEST['id']))
            );
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
		}else
			$row = array();
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
								SMS Scheduler
								<input type="button" class="btn btn-primary move-right" value="Back" onclick="window.location='view_scheduler.php'" />
							</h4>
							<p class="category red"><?php echo DBout(date('g:iA \o\n l jS F Y'))?></p>
						</div>
						<div class="content table-responsive">
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
							<div class="form-group">
								<label>Title*</label>
								<input type="text" name="title" parsley-trigger="change" required placeholder="Enter title..." class="form-control">
							</div>
							<div class="form-group">
								<label>Date*</label>
								<input type="text" class="form-control addDatePicker z-index" name="date" required>
							</div>
							<div class="form-group">
								<label>Time*</label>
								<select name="time" class="form-control" parsley-trigger="change" required>
								<?php
									$time = getTimeArray();
									foreach($time as $key => $value){
										?>

                                    <option <?php echo DBout($selected)?> value="<?php echo DBout($key)?>"><?php echo DBout($value)?></option>
									<?php
                                    }
								?>
								</select>
							</div>
							<div class="form-group">
								<label><input name="attach_mobile_device" value="1" type="checkbox" /> Attach mobile device</label>
							</div>
                            
                            <?php if($_REQUEST['custom']==1){
                            ?>    
                                <div class="form-group">
    								<label>Custom Search <b>"<?php echo DBout(DBin($_REQUEST['search'])); ?>"</b></label>
    								<input type="hidden" name="search" value="<?php echo DBout(DBin($_REQUEST['search'])); ?>">
                                    <input type="hidden" name="custom" value="1">
    							</div>
                            <?php
                            }
                            ?>
                            
							<div class="form-group">
								<label>Select Group</label>
								<select class="form-control" name="group_id" onChange="getGroupNumbers(this.value)" parsley-trigger="change" <?php if($_REQUEST['custom']!=1){?> required <?php } ?>>
									<option value="">- Select One -</option>
								<?php
									$sqlg = sprintf("select id, title from campaigns where user_id=%s ",
                                            mysqli_real_escape_string($link,DBin($_SESSION['user_id']))
                                        );
									$resg = mysqli_query($link,$sqlg);
									if(mysqli_num_rows($resg)){
										while($rowg = mysqli_fetch_assoc($resg)){
											if($_REQUEST['group_id']==$rowg['id']){ $sel = "selected"; }else{ $sel = ""; }
                                            ?>

                                    <option <?php echo DBout($sel)?> value="<?php echo DBout($rowg['id'])?>"><?php echo DBout($rowg['title'])?></option>
									<?php	}
									}else{ ?>
                                    <option value="">No group found</option>';
								<?php	}
								?>		
								</select>
							</div>
							<div class="form-group">
								<label>Select Number</label>
                                <?php
                                if($_REQUEST['custom']==1){
                                    $multiple = DBout("multiple='multiple'");
                                    $f_name = DBout("phone_number[]");
                                } else {
                                    $multiple = "";
                                    $f_name = DBout("phone_number");
                                } 
                                    ?>
								<select name="<?php echo DBout($f_name); ?>" class="form-control" id="list_group_number" <?php echo DBout($multiple); ?>>
                                
                                <?php

                                if($_REQUEST['custom']==1){
                                    $where = "";
                                    
                                    if($_REQUEST['all_numbers']==1){
                                        $where .= " and s.phone_number like '%".$_REQUEST['search']."%'";
                                    }else                                    
                                    if($_REQUEST['checked_numbers']!=""){
                                        $checked_numbers = DBin($_REQUEST['checked_numbers']);
                                        $where .= " and s.id in ($checked_numbers)";
                                    }
                                    
                                    if($_REQUEST['group_id']!=""){
                                        $group_id = DBin($_REQUEST['group_id']);
                                        $where .= " and sga.group_id = $group_id";
                                    }
                                    $sql = "select s.id, s.phone_number,first_name,last_name,email from subscribers s, subscribers_group_assignment sga where sga.subscriber_id=s.id and s.status='1' $where group by s.phone_number";
                                                                        
                                    $selected = DBout("selected");
                                    $res = mysqli_query($link,$sql);
                        			if(mysqli_num_rows($res)){
                        				while($row = mysqli_fetch_assoc($res)){
                        					if(trim($row['first_name'])!=''){
                        						$name = $row['first_name'].' '.$row['last_name'].', &nbsp;';
                        					}else{
                        						$name = '';	
                        					}
                        					if(trim($row['email'])!=''){
                        						$email = DBout(',&nbsp;'.$row['email']);
                        					}else{
                        						$email = '';	
                        					}
                        					$info = DBout($name.$row['phone_number'].$email);
                        					?>
                                    <option <?php echo DBout($selected)?> value="<?php echo DBout($row['id'])?>"><?php echo DBout($info)?></option>
                        			<?php	}
                        			}else{ ?>
                                    <option value="">No subscribers found.</option>
                        		<?php	}
                                }
                                ?>
                                                                
                                </select>
							</div>
							<div class="form-group">
								<label>Message</label>
								<textarea name="message" class="form-control textCounter" parsley-trigger="change" required></textarea>
								<span class="showCounter">
									<span class="showCount"><?php echo DBout($maxLength)?></span> Characters left
								</span>
							</div>
							<div class="form-group">
								<label>Media</label>
								<input type="file" name="media">
							</div>
							<div class="form-group text-right m-b-0">
								<button class="btn btn-primary waves-effect waves-light" type="submit"> Save </button>
								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
								<input type="hidden" name="cmd" value="save_scheduler" />
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
<script type="text/javascript" src="scripts/js/parsley.min.js"></script>
<script src="scripts/scheduler.js"></script>