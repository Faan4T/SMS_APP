<?php
	include_once("header.php");
	include_once("left_menu.php");
?>
<link href="assets/fullcalendar/css/fullcalendar.min.css" rel="stylesheet" />

<div class="main-panel">

<script>
var events = [];
</script>

	<?php include_once('navbar.php');?>
    <?php
    $sql = sprintf("select * from schedulers where user_id=%s and scheduler_type!='2' order by id desc",
                    mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
        );
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){
		$index = 0;
		while($row = mysqli_fetch_assoc($res)){
            ?>
            <script>
            var event = {};
            event['id'] = '<?php echo DBout($row["id"]); ?>';
            event['title'] = '<?php echo DBout($row["title"]); ?>';
            event['start'] = '<?php echo DBout($row["scheduled_time"]); ?>';
            event['end'] = '<?php echo DBout($row["scheduled_time"]); ?>';
            event['className'] = 'bg-info';
            event['group_id'] = '<?php echo DBout($row["group_id"]); ?>';
            event['phone_number'] = '<?php echo DBout($row["phone_number"]); ?>';
            event['message'] = '<?php echo DBout($row["message"]); ?>';
            event['attach_mobile_device'] = '<?php echo DBout($row["attach_mobile_device"]); ?>';
            event['send_immediate'] = '<?php echo DBout($row["send_immediate"]); ?>';
            event['media'] = '<?php echo DBout($row["media"]); ?>';
            events['<?php echo DBout($index); ?>'] = event;
            </script>

            <?php
            $index++;
        }
    }
    ?>
    <div class="modal fade none-border" id="event-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="server.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add New Event</h4>
                </div>
                <div class="modal-body p-20">
						<div class="form-group">
							<label>Title*</label>
							<input type="text" name="title" parsley-trigger="change" required placeholder="Enter title..." class="form-control">
						</div>
						<div class="form-group">
							<label>Date*</label>
							<input type="text" class="form-control addDatePicker z-index" name="date" required >
						</div>
						<div class="form-group">
							<label>Time*</label>
							<select name="time" class="form-control" parsley-trigger="change" required>
							<?php
								$time = getTimeArray();
								foreach($time as $key => $value){ ?>
									<option <?php echo DBout($selected)?> value="<?php echo DBout($key)?>"><?php echo DBout($value)?></option>
							<?php	}
							?>
							</select>
						</div>
						<div class="form-group">
							<label><input name="send_immediate" value="1" type="checkbox" /> Send Immediate</label>
						</div>
                        <div class="form-group">
							<label><input name="attach_mobile_device" value="1" type="checkbox" /> Attach mobile device</label>
						</div>
						<div class="form-group">
							<label>Select Group</label>
							<select class="form-control" name="group_id" onChange="getGroupNumbers(this.value)" parsley-trigger="change" required>
								<option value="">- Select One -</option>
							<?php
								$sqlg = sprintf("select id, title from campaigns where user_id=%s",
                                        mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
                                    );
								$resg = mysqli_query($link,$sqlg);
								if(mysqli_num_rows($resg)){
									while($rowg = mysqli_fetch_assoc($resg)){ ?>
										<option <?php echo DBout($sel)?> value="<?php echo DBout($rowg['id'])?>"><?php echo DBout($rowg['title'])?></option>
							<?php		}
								}else{ ?>
									<option value="">No group found</option>
							<?php	}
							?>
							</select>
						</div>
						<div class="form-group">
							<label>Select Number</label>
							<select name="phone_number" class="form-control" id="list_group_number"></select>
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
                        <div class="form-group" id="media_area"></div>


						<div class="form-group text-right m-b-0 display-none">
							<button class="btn btn-primary waves-effect waves-light" type="submit"> Save </button>
							<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
						</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success save-event waves-effect waves-light">Save</button>

                    <input type="hidden" name="hidden_media" value="" />
                    <input type="hidden" name="cmd" value="save_scheduler" />
                    <input type="hidden" name="scheduler_id" value="0" />
                </div>
            </form>
            </div>
        </div>
    </div>

    <div class="modal fade none-border z-index" id="add-category">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add a category MODALLLLLLLL</h4>
                </div>
                <div class="modal-body p-20">
                    <form role="form">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Category Name</label>
                                <input class="form-control form-white" placeholder="Enter name" type="text" name="category-name"/>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Choose Category Color</label>
                                <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                    <option value="success">Success</option>
                                    <option value="danger">Danger</option>
                                    <option value="info">Info</option>
                                    <option value="pink">Pink</option>
                                    <option value="primary">Primary</option>
                                    <option value="warning">Warning</option>
                                    <option value="orange">Orange</option>
                                    <option value="brown">Brown</option>
                                    <option value="teal">Teal</option>
                                    <option value="inverse">Inverse</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								SMS Schedulers
                                <input type="button" class="btn btn-info move-right margin-0 margin-left-10" value="List view" onclick="window.location='view_scheduler.php'"/>
                                <input type="button" class="btn btn-primary move-right" value="Add New" onclick="window.location='scheduler.php'"/>
							</h4>
							<p class="category red"><?php echo DBout(date('g:iA \o\n l jS F Y'))?></p>
						</div>
						<div class="content table-responsive table-full-width">

                            <div id="calendar" class="calender_style"></div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>


<script src="assets/moment/moment.js"></script>
<script src='assets/fullcalendar/js/fullcalendar.min.js'></script>
<script type="text/javascript" src="scripts/view_scheduler.js">
</script>