<?php
	include_once("header.php");
	include_once("left_menu.php");
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
								Add Pool
								<input type="button" class="btn btn-primary move-left" value="Back" onclick="return window.location=history.go(-1)" />
							</h4>
							<p class="category">Add number pool here.</p>
						</div>
						<div class="content table-responsive">
							<form method="post" action="server.php" enctype="multipart/form-data">
								<div class="form-group">
									<label>SMS Gateway</label>
									<select name="sms_gateway" class="form-control smsGateWay">
										<option value="twilio">Twilio</option>
										<option value="plivo">Plivo</option>
										<option value="nexmo">Nexmo</option>
									</select>
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
<div id="exportSubs" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="custom-modal-title">Export Subscribers</h6>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data" action="server.php">
			<div class="form-group">
				<label class="move-left">Select Campaign</label>
				<select name="export_campaign_id" class="form-control">
					<option value="all">ALL Subscribers</option>
				<?php
					$lists = mysqli_query($link,sprintf("select id, title from campaigns where user_id='%s'",mysqli_real_escape_string($link,DBin($_SESSION['user_id']))));
					if(mysqli_num_rows($lists)){
						while($list = mysqli_fetch_assoc($lists)){ ?>
							<option value="<?php echo DBout($list['id'])?>"><?php echo DBout($list['title'])?></option>
					<?php	}
					} else{ ?>
						<option value="">No campaign found.</option>
				<?php	}
				?>
				</select>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="cmd" value="export_subs" />
				<input type="submit" value="Export CSV" class="btn btn-primary" />
			</div>
		</form>
      </div>
    </div>
  </div>
</div>

<div id="importSubs" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="custom-modal-title">Import Subscribers</h6>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data" action="server.php">
			<div class="form-group">
				<label class="move-left">Select Campaign</label>
				<select name="imported_campaign_id" class="form-control">
				<?php
					$lists = mysqli_query($link,sprintf("select id, title from campaigns where user_id='%s'",mysqli_real_escape_string($link,DBin($_SESSION['user_id']))));
					if(mysqli_num_rows($lists)){
						while($list = mysqli_fetch_assoc($lists)){ ?>
							<option value="<?php echo DBout($list['id'])?>"><?php echo DBout($list['title'])?></option>
				<?php		}
					} else{ ?>
                    <option value="">No campaign found.</option>
                    <?php
					}
				?>
				</select>
			</div>
			<div class="form-group">
				<label class="move-left">Select CSV file</label>
				<input type="file" name="imported_csv" class="display-inline" required/><br>
				<span class="red">Note: Please check csv format in sample file before upload.</span>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="cmd" value="import_subs" />
				<input type="submit" value="Import" class="btn btn-primary" />
			</div>
		</form>
      </div>
    </div>
  </div>
</div>