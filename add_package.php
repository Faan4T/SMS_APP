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
								Create New Plan
								<input type="button" class="btn btn-primary move-right" value="Back" onclick="window.location='view_package.php'" />
							</h4>
							<p class="category">Create your attractive sms package plans here.</p>
						</div>
						<div class="content table-responsive">
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post" onsubmit="getPkgCountry()">
							<div class="form-group">
								<label>Title*</label>
								<input type="text" name="title" parsley-trigger="change" required placeholder="Enter title..." class="form-control">
							</div>
							<div class="form-group">
								<label>SMS Credits*</label>
								<input type="text" name="sms_credits" parsley-trigger="change" required placeholder="Enter sms credits..." class="numericOnly form-control">
							</div>
							<div class="form-group">
								<label>Phone Number Limit*</label>
								<input type="text" name="phone_number_limit" parsley-trigger="change" required placeholder="Enter phone number limit..." class="numericOnly form-control">
							</div>
							<div class="form-group">
								<label>SMS Gateway*</label><span class="loading"></span>
								<p class="category red">Package will use this sms gateway to send messages.</p>
								<select name="sms_gateway" class="form-control" parsley-trigger="change" required onchange="getGatewayCountries(this.value)">
									<option value="">- Select One -</option>
									<option value="twilio">Twilio</option>
									<option value="plivo">Plivo</option>
									<option value="nexmo">Nexmo</option>
									<option value="signalwire">Signalwire</option>
                                    <option value="mobile_sim">Mobile Device</option>
								</select>
							</div>
							<div class="form-group">
								<label>Country*</label>
								<select name="country" id="country" class="form-control" parsley-trigger="change" required>
                                <?php
                                $isoCountries = countries();
                        			foreach($isoCountries as $key => $value){
                        				if($key=="US")
                        					$sele = DBout('selected="selected"');
                        				else
                        					$sele = '';
                        				?>
                                    <option <?php echo DBout($sele)?> value="<?php echo DBout($key)?>"><?php echo DBout($value)?></option>
                        		<?php	}
                                ?>
                                </select>
							</div>
							<div class="form-group">
								<label>Price*</label>
								<input type="text" name="price" required placeholder="Enter package price..." class="numericOnly form-control">
							</div>
							<div class="form-group">
								<label class="checkbox-inline">
									<input type="checkbox" name="is_free_days" value="1" class="is_free_days" onclick="showFreeDays(this)" /> Free Days
								</label>
								<input type="text" name="free_days" id="free_days" class="numericOnly form-control display-none"/>
							</div>
							<div class="form-group text-right m-b-0">
								<input type="hidden" name="pkg_country" id="pkg_country" value="" />
								<input type="hidden" name="cmd" value="save_pkg" />
								<button class="btn btn-primary waves-effect waves-light" type="submit"> Save </button>
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
<script type="text/javascript" src="scripts/js/parsley.min.js"></script>
<script type="text/javascript" src="scripts/add_pkg.js">

</script>