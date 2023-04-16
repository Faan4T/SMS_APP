<?php
include_once("header.php");
include_once("left_menu.php");
$sql_wa = "SELECT * FROM `users_phone_numbers` where type = '4' AND user_id='".$_SESSION['user_id']."'";
$res_wa = mysqli_query($link,$sql_wa);
$row_wa = mysqli_fetch_assoc($res_wa);
$whatsapp_business_number = $row_wa['phone_number'];
$sql = sprintf("select * from application_settings where user_id=%s",
       mysqli_real_escape_string($link,filtervar($_SESSION['user_id'])));
$res = mysqli_query($link,$sql);
$row = mysqli_fetch_assoc($res);
$sid = DBout($row['twilio_sid']);
$token = DBout($row['twilio_token']);
if($_SESSION['user_type']=='1'){?>
	<script src="scripts/js/ckeditor/ckeditor.js"></script>'
	<?php
}
?>
<link href="assets/css/timepicki.css" rel="stylesheet" />
<link rel="stylesheet" href="assets/css/bootstrap-select.min.css">
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title"> Settings
								<input type="button" class="btn btn-primary" value="Back" id="btn_right" onclick="window.location=history.go(-1)" />
							</h4>
							<p class="category">Add your application configuration here. <span id="loading"><img src="images/busy.gif"></span></p>
						</div>
						<div class="content table-responsive">
							<ul class="nav nav-tabs tabs">
								<li class="active tab">
									<a href="#general_settings" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-home"></i></span> <span class="hidden-xs">General Settings</span> </a>
								</li>

                                <?php if($_SESSION['user_type']=='2') { ?>
                                    <?php
                                    $package_sql = "select pkg.sms_gateway from user_package_assignment upa , package_plans pkg where upa.user_id=" . $_SESSION['user_id'] . " and pkg.id=upa.pkg_id";
                                    $package_query = mysqli_query($link, $package_sql) or die(mysqli_error());
                                    $data = mysqli_fetch_assoc($package_query);
                                    if ($data['sms_gateway'] != 'mobile_sim') {
                                        ?>
                                        <li class="tab">
                                            <a href="#buy_numbers" data-toggle="tab" aria-expanded="false"> <span
                                                        class="visible-xs"><i class="fa fa-user"></i></span> <span
                                                        class="hidden-xs">Buy Numbers</span> </a>
                                        </li>

                                        <?php
                                    } else {
                                        ?>
										<!--
                                        <li class="tab">
                                            <a href="#mobile_devices" data-toggle="tab" aria-expanded="false"> <span
                                                        class="visible-xs"><i class="fa fa-user"></i></span> <span
                                                        class="hidden-xs">Mobile Devices</span> </a>
                                        </li>
										-->
                                        <?php
                                    }
                                }
                            ?>
								<?php if($_SESSION['user_type']=='1'){?>
                                    <li class="tab">
                                        <a href="#buy_numbers" data-toggle="tab" aria-expanded="false"> <span
                                                    class="visible-xs"><i class="fa fa-user"></i></span> <span
                                                    class="hidden-xs">Buy Numbers</span> </a>
                                    </li>
									<!--
                                    <li class="tab">
                                        <a href="#mobile_devices" data-toggle="tab" aria-expanded="false"> <span
                                                    class="visible-xs"><i class="fa fa-user"></i></span> <span
                                                    class="hidden-xs">Mobile Devices</span> </a>
                                    </li>
									-->
									<li class="tab">
										<a href="#sms_gateways" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">SMS Gateways</span> </a>
									</li>
									
									<li class="tab">
										<a href="#payment_processors" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Payment Processors</span> </a>
									</li>
									<li class="tab">
										<a href="#pricing_details" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Pricing</span> </a>
									</li>
									<li class="tab">
										<a href="#email_templates" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Email Templates</span> </a>
									</li>
                                    <li class="tab">
                                        <a href="#cron_jobs_section" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Cron Job</span> </a>
                                    </li>
                                    <li class="tab">
                                        <a href="#bitly_section" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Bitly Keys</span> </a>
                                    </li>
								<?php }?>
								<li class="tab">
									<a href="#propend_messages" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Messages</span> </a>
								</li>

								<?php if($_SESSION['user_type']=='1'){?>
									<li class="tab">
										<a href="#footer_section" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Footer Customization</span> </a>
									</li>
								<?php } ?>
							</ul>
							<div class="tab-content" id="tab_content">
								<div class="tab-pane active" id="general_settings">
									<form method="post" action="server.php" enctype="multipart/form-data">
										<div class="form-group">
											<?php
											$colors = array('purple'=>'Purple','blue'=>'Blue','azure'=>'Azure','green'=>'Green','orange'=>'Orange','red'=>'Red','#1A4180'=>'Navy Blue');
											?>
											<label>Sidebar Color</label>
											<select name="sidebar_color" class="form-control" onchange="applySidebarColor(this.value)">
												<?php
												foreach($colors as $k => $v){
													if($row['sidebar_color']==$k)
														$selColor = DBout('selected="selected"');
													else
														$selColor = DBout('');
													?>
												    	<option <?php echo DBout($selColor) ?> type="Checkbox" value="<?php echo DBout($k) ?>"><?php echo DBout($v) ?></option>
												    <?php
												}
												?>	
											</select>
										</div>
										<div class="form-group">
											<label>Admin Phone Number</label>
											<input type="text" name="admin_phone" class="form-control" value="<?php echo DBout($row['admin_phone'])?>"/>
										</div>
										<div class="form-group">
											<label>Time Zone</label>
											<select name="time_zone" class="form-control">
												<?php
												$sqlt = sprintf("select * from time_zones");
												$rest = mysqli_query($link,$sqlt)or die(mysqli_error($link));
												if(mysqli_num_rows($rest)){
													while($rowt = mysqli_fetch_assoc($rest)){
														if($row['time_zone']==$rowt['time_zone']){
															$selected = DBout('selected="selected"');
														}else{
															$selected = DBout('');
														}
														?>
													 <option <?php echo DBout($selected) ?> value="<?php echo DBout($rowt['time_zone']) ?>"><?php echo DBout($rowt['time_zone_value']) ?></option>
												       <?php
													}
												}else{
													?>
													<option value="">No time zone added.</option>
												<?php
												}
												?>
											</select>
										</div>
										<div class="form-group">
											<label>Date Format</label>
											<select name="app_date_format" class="form-control">
												<option <?php if($row['app_date_format']=='d-m-Y')echo DBout('selected="selected"');?> value="d-m-Y">d-m-Y</option>
												<option <?php if($row['app_date_format']=='m-d-Y')echo DBout('selected="selected"');?> value="m-d-Y">m-d-Y</option>
												<option <?php if($row['app_date_format']=='Y-m-d')echo DBout('selected="selected"');?> value="Y-m-d">Y-m-d</option>
												<option <?php if($row['app_date_format']=='M-d-y')echo DBout('selected="selected"');?> value="M-d-y">M-d-y</option>
											</select>
										</div>
										
										<?php if($_SESSION['user_type']=='1'){?>
											<div class="form-group">
												<label>Admin Email</label>
												<input type="email" name="admin_email" class="form-control" value="<?php echo DBout($row['admin_email'])?>" />
											</div>
											<div class="form-group">
												<label>API Key</label><br />
												<input type="text" name="api_key" class="form-control" value="<?php echo DBout($row['api_key'])?>" id="api_key" readonly/>
												<input type="button" id="generate_key" value="Generate Key" id="file" class="btn btn-btn-success" onclick="generateApiKey(this)" />
											</div>
											<div class="form-group">
												<label>API Base URL</label>
												<p id="showApiUrl"><?php echo DBout(getServerUrl()).'/nmapi/phpapi.php?api_key='.DBout($row['api_key']).'&cmd={desired_resource}'?></p>
											</div>
										<?php }?>
				
			<div class="form-group">
				<?php if($_SESSION['user_type']!='1'){?>
					<label id="h5">Admin's banned words</label>
					<textarea class="form-control" readonly id="left"><?php echo DBout($adminSettings['banned_words'])?></textarea>
				<?php }?>
				<label>Banned Words</label>
				<span id="immediate_msg">Enter banned words comma separated.</span>
				<textarea name="banned_words" class="form-control"><?php echo DBout($row['banned_words'])?></textarea>
				<span class="showCounter"> <span class="showCount"><?php echo DBout($maxLength-strlen($row['banned_words']))?></span> Characters left </span>
			</div>
			<?php if($_SESSION['user_type']=='1'){?>
				<div class="form-group">
					<label>Upload Logo</label>
					<span id="dimendion">Recomended dimensions are 170x50</span>
					<input type="file" name="app_logo" />
					<input type="hidden" name="hidden_app_logo" value="<?php echo DBout($row['app_logo'])?>" />
				</div>
				<?php
			}
			?>
			<div class="form-group">
				<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
				<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
				<input type="hidden" name="cmd" value="update_general_settings" />
			</div>
		</form>
	</div>
	
	<div class="tab-pane" id="buy_numbers">
		<?php
		if($appSettings['sms_gateway']=='twilio'){
					if($_SESSION['user_type']=='1'){ 
						?>
						<table width="100%" align="center" id="top" >
							<tr>
								<td width="30%">Select One</td>
								<td id="left"><label>Buy Number:
									<input type="radio" name="number_type" value="1" onclick="showSections(this)" checked="checked" id="margin_zero" />
								</label>
								&nbsp;&nbsp;
								<label>Existing Number:
									<input type="radio" name="number_type" value="3" onclick="showSections(this)" id="margin_zero" />
								</label>
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr id="purchase_number">
							<td class="attach_media">Select Country</td>
							<td align="left" id="left">
								<select name="phone_type" id="phone_type"  onchange="searchNumbers(this.value);" class="form-control" id="full_width">
									<?php
									$countries = getTwilioCountries($sid,$token);
									for($i=0;$i<count($countries->Countries->Country);$i++){
										if($countries->Countries->Country[$i]->CountryCode=="US")
											$sele = DBout('selected="selected"');
										else
											$sele = DBout('');
										?>
										<option <?php echo DBout($sele) ?> value="<?php echo DBout($countries->Countries->Country[$i]->CountryCode )?>"><?php echo DBout($countries->Countries->Country[$i]->Country) ?></option>
									   <?php
										
									}
									?>
								</select>
								<br>
								<div id="usa_section" class="form-group" id="left">
									<label>State&nbsp;&nbsp;
										<input type="radio" name="us_number_type" value="state" onclick="showSection(this);" />
									</label>
									&nbsp;&nbsp;
									<label>Area Code&nbsp;&nbsp;
										<input type="radio" name="us_number_type" value="areacode" onclick="showSection(this);" />
									</label>
									<div id="showStateSection" class="showStateSection">
										<select name="state" id="state" class="form-control" onchange="getareacodes(this);">
											<?php
											$sqlState = sprintf("select * from states");
											$resStats = mysqli_query($link,$sqlState);
											if(mysqli_num_rows($resStats)){
												while($rowStats = mysqli_fetch_assoc($resStats)){
													?>
												      <option value="<?php echo DBout($rowStats['Code']) ?>"><?php echo DBout($rowStats['State']) ?></option>
											       <?php
													
												}	
											}
											?>
										</select>
										<select name="areacode" id="areacode" class="form-control" onchange="getnumbers(this);" id=""></select>
									</div>
									<div id="showAraaCodeSection" class="showAraaCodeSection">
										<label>Enter Code: </label>
										<input type="text" name="areacode" id="selected_areacode" class="form-control"  onkeypress="OnKeyPress(event);" />
										<img src="images/search.png" id="search_img" title="Search" alt="Search" onclick="getNumberByAreaCode();" />
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td id="existing_number" colspan="2"></td>
						</tr>
					</table>
					<div id="showNumbers" class="showNumbers"></div>
					<?php			
				}
					else if($_SESSION['user_type']=='2'){
						$getNumberOnload = false;
						$pkgInfo = getAssingnedPackageInfo($_SESSION['user_id']);
						?>
						<table width="100%" align="center" id="top">
							<tr>
								<td colspan="2">
									
								Using application in <?php ?>
						           <span id="h5"><b><?php echo DBout($pkgInfo['pkg_country']) ?></b></span>
									<?php
								?>
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td width="30%">Select One</td>
							<td id="left"><label>Buy Number:
								<input type="radio" name="number_type" value="1" onclick="showSections(this)" checked="checked" id="margin_zero" />
							</label>
							&nbsp;&nbsp;
							<label>Buy Credits:
								<input type="radio" name="number_type" value="2" onclick="showSections(this)" id="margin_zero" />
							</label>
							&nbsp;&nbsp;
							<label>Existing Number:
								<input type="radio" name="number_type" value="3" onclick="showSections(this)" id="margin_zero" />
							</label>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr id="purchase_number">
						<td class="purchase_num" width="30%">&nbsp;</td>
						<td align="left" id="left">
							<select name="phone_type" id="phone_type" >
								<option value="<?php echo DBout($pkgInfo['iso_country'])?>" selected="selected"><?php echo DBout($pkgInfo['pkg_country'])?></option>
							</select>
							<?php 
							if($pkgInfo['iso_country']=='US'){
								?>
								<div id="usa_section" class="form-group" id="left">
									<label>State&nbsp;&nbsp;
										<input type="radio" name="us_number_type" value="state" onclick="showSection(this);" />
									</label>
									&nbsp;&nbsp;
									<label>Area Code&nbsp;&nbsp;
										<input type="radio" name="us_number_type" value="areacode" onclick="showSection(this);" />
									</label>
									<div id="showStateSection" class="showStateSection">
										<select name="state" id="state" class="form-control" onchange="getareacodes(this);" >
											<?php
											$sqlState = sprintf("select * from states");
											$resStats = mysqli_query($link,$sqlState);
											if(mysqli_num_rows($resStats)){
												
												while($rowStats = mysqli_fetch_assoc($resStats)){
													?>
													   <option value="<?php echo DBout($rowStats['Code']) ?>"><?php echo DBout($rowStats['State']) ?></option>
											      <?php
												}	
											}
											?>
										</select>
										<select name="areacode" id="areacode" class="form-control" onchange="getnumbers(this);" ></select>
									</div>
									<div id="showAraaCodeSection" class="showAraaCodeSection">
										<label>Enter Code: </label>
										<input type="text" name="areacode" id="selected_areacode" class="form-control"  onkeypress="OnKeyPress(event);" />
										<img src="images/search.png"  id="search_img" title="Search" alt="Search" onclick="getNumberByAreaCode();" />
									</div>
								</div>
								<?php 
							}else{
								$getNumberOnload = DBout(true);
							}
							?>
						</td>
					</tr>
					<tr  id="buy_credits_section">
						<td>Buy credits</td>
						<td>
							<?php
							if($adminSettings['payment_processor']=="3"){
								$action= DBout("add_stripe_credits_form.php");
							}else{
								$action="server.php";
							}
							?>
							<form action="<?php echo DBout($action); ?>" method="post">
								<input type="text" name="credit_quantity" class="form-control" id="credit_quantity" placeholder="Amount of credits..." required>
								<input type="submit" class="btn btn-danger" value="Buy"id="btn_danger">
								<input type="hidden" name="cmd" value="buy_credits">
							</form>
						</td>
					</tr>
					<tr>
						<td id="existing_number" colspan="2"></td>
					</tr>
				</table>
				<div id="showNumbers" class="showNumbers"></div>
				<?php			
			}
		}
		else if($appSettings['sms_gateway']=='plivo'){
					if($_SESSION['user_type']=='1'){ 
						?>
						<table width="100%" align="center" id="top">
							<tr>
								<td colspan="1" width="25%">Select One</td>
								<td id="left" colspan="3">
									<label>Buy Number:&nbsp;
										<input type="radio" name="plivo_number_type" value="1" id="margin_zero" onclick="showPlivoSections(this)" checked="checked" />
									</label>
									&nbsp;&nbsp;
									<label>Existing Number:&nbsp;
										<input type="radio" name="plivo_number_type" value="3" id="margin_zero" onclick="showPlivoSections(this)" />
									</label>
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr id="search_pattren">
								<td width="10%">State:</td>
								<td width="40%">
									<select name="state" class="form-control">
										<?php
										$sels = sprintf("select Code,State from states");
										$ress = mysqli_query($link,$sels);
										if(mysqli_num_rows($ress)){
											while($rows = mysqli_fetch_assoc($ress)){
												?>
												   
										 <option value="<?php echo DBout($rows['Code']) ?>"><?php echo DBout($rows['State']) ?></option>
												<?php
											}
										}
										?>
									</select>
								</td>
								<td width="10%" class="pattrens">Pattern:</td>
								<td width="40%">
									<input maxlength="3" name="pattern" class="form-control" id="pattern">
									&nbsp;<img src="images/search.png" id="search_img1" title="Search" alt="Search" onclick="searchPlivoNumbers()" />
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr>
								<td id="existing_number" colspan="4"></td>
							</tr>
						</table>
						<div id="showNumbers"></div>
						<?php			
					}
					else if($_SESSION['user_type']=='2'){ 
						$getNumberOnload = false;
						$pkgInfo = getAssingnedPackageInfo($_SESSION['user_id']);
						?>
						<table width="100%" align="center" id="pattrens1">
							<tr>
								<td colspan="4">
									
								Using application in <?php ?>
									
									<span id="h5"><b><?php echo  DBout($pkgInfo['pkg_country']) ?></b></span>
									<?php ?>
							</td>
						</tr>
						<tr>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="1" width="25%">Select One</td>
							<td id="left" colspan="3">
								<label>Buy Number:&nbsp;
									<input type="radio" name="plivo_number_type" value="1" id="margin_zero" onclick="showPlivoSections(this)" checked="checked" />
								</label>
								&nbsp;&nbsp;
								<label>Existing Number:&nbsp;
									<input type="radio" name="plivo_number_type" value="3" id="margin_zero" onclick="showPlivoSections(this)" />
								</label>
							</td>
						</tr>
						<tr>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr id="search_pattren">
							<td width="10%">State:</td>
							<td width="40%">
								<select name="state" class="form-control">
									<?php
									$sels = sprintf("select Code,State from states");
									$ress = mysqli_query($link,$sels);
									if(mysqli_num_rows($ress)){
										while($rows = mysqli_fetch_assoc($ress)){
											?>
											   <option value="<?php echo DBout($rows['Code']) ?>"><?php echo DBout($rows['State'] )?></option>
									       <?php
										}
									}
									?>
								</select>
							</td>
							<td width="10%" calss="pattrens" >Pattern:</td>
							<td width="40%">
								<input maxlength="3" name="pattern" class="form-control" id="pattern">
								&nbsp;<img src="images/search.png" id="search_img1" title="Search" alt="Search" onclick="searchPlivoNumbers()" />
							</td>
						</tr>
						<tr>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td id="existing_number" colspan="4"></td>
						</tr>
					</table>
					<div id="showNumbers" class="showNumbers" ></div>
					<?php
					if($pkgInfo['iso_country']!='US'){
						$getNumberOnload = DBout(true);
					}
				}
			}
			else if($appSettings['sms_gateway']=='nexmo'){
					if($_SESSION['user_type']=='1'){ 
						?>
						<table width="100%" align="center" id="pattrens1">
							<tr>
								<td colspan="1" width="25%">Select One</td>
								<td id="left" colspan="3">
									<label>Buy Number:&nbsp;
										<input type="radio" name="nexmo_number_type" value="1"id="margin_zero"  onclick="showNexmoSections(this)" checked="checked" />
									</label>
									&nbsp;&nbsp;
									<label>Existing Number:&nbsp;
										<input type="radio" name="nexmo_number_type" value="3" id="margin_zero" onclick="showNexmoSections(this)" />
									</label>
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr id="purchase_nexmo_number">
								<td width="10%">Select Country:</td>
								<td width="40%">
									<select name="nexmo_country" class="form-control" onchange="searchNexmoNumbers(this.value)">
										<?php
										$isoCountries = countries();
										foreach($isoCountries as $key => $value){
											if($key=="US")
												$sele = DBout('selected="selected"');
											else
												$sele = DBout('');
											?>
											   <option <?php echo DBout($sele) ?> value="<?php echo DBout($key) ?>"><?php echo DBout($value) ?></option>
										<?php
										}
										?>	
									</select>
								</td>
								<td width="10%" id="left">&nbsp;</td>
								<td width="40%">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr>
								<td id="existing_number" colspan="4"></td>
							</tr>
						</table>
						<div id="showNumbers" class="showNumbers" ></div>
						<?php
					}
					else if($_SESSION['user_type']=='2'){ 
						$getNumberOnload = false;
						$pkgInfo = getAssingnedPackageInfo($_SESSION['user_id']);
						?>
						<table width="100%" align="center" id="pattrens1">
							<tr>
								<td colspan="4">
									
								Using application in <?php ?>
									
									 <span id="h5"><b><?php echo DBout($pkgInfo['pkg_country']) ?></b></span>
									<?php ?>
							</td>
						</tr>
						<tr>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="1" width="25%">Select One</td>
							<td id="left" colspan="3">
								<label>Buy Number:&nbsp;
									<input type="radio" name="nexmo_number_type" value="1" id="margin_zero"onclick="showNexmoSections(this)" checked="checked" />
								</label>
								&nbsp;&nbsp;
								<label>Existing Number:&nbsp;
									<input type="radio" name="nexmo_number_type" value="3" id="margin_zero"existing_number onclick="showNexmoSections(this)" />
								</label>
							</td>
						</tr>
						<tr>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr id="purchase_nexmo_number">
							<td width="10%">Select Country:</td>
							<td width="40%">
								<select name="nexmo_country" class="form-control" onchange="searchNexmoNumbers(this.value)">
									<?php
									$isoCountries = countries();
									foreach($isoCountries as $key => $value){
										if($key=="US")
											$sele = DBout('selected="selected"');
										else
											$sele = DBout('');
										?>
										 <option <?php echo DBout($sele) ?> value="<?php echo DBout($key) ?>"><?php echo DBout($value)?></option>
									<?php
									}
									?>	
								</select>
							</td>
							<td width="10%" id="pattrens">&nbsp;</td>
							<td width="40%">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td id="existing_number" colspan="4"></td>
						</tr>
					</table>
					<div id="showNumbers" class="showNumbers"></div>
					<?php
				}
			}
			else if($appSettings['sms_gateway']=='mobile_sim'){
				?>
		           <div id="updateds" class="updated"><p>Your mobile number will use to send messages.</p></div
		       <?php
			}
			else{
				?>
				<div id="updateds" class="updated"><p>You are not able to buy numbers for now.</p></div>
					<?php   
			}
			?>	
		</div>
		<!--
		<div class="tab-pane" id="mobile_devices">
			<div class="alert alert-warning">
				<h4>Instructions</h4>
				<strong>
					You can get Nimble Messaging android app from <a href="https://codecanyon.net/item/nimble-messaging-business-mobile-sms-marketing-application-for-android/20956083" target="_blank">here</a>.<br />
					After installing Nimble Android App follow the steps listed below:<br />
					1. Enter the url of your nimble messaging web app and hit "GO".<br />
					2. After verifying your appUrl enter any device name of your choice and hit "Go".<br />
					3. Sign in to your nimble messaging app with the credentials you use on Nimble Messaging web app.<br />
					4. Device Id with user info will be saved in the app.<br />
					6. Now you can enable device messaging feature from top menu in the app<br />
					7. you have to allow following permissions on runtime in order to enable this feature.<br />
					<span id="pattrens1">&gt; send and view sms messages</span><br />
					<span id="pattrens1">&gt; make and manage phone calls</span><br />
					8. By allowing second permission the app only will get sim status and info in order to enable dual sim feature.<br />
					9. You can enable/disable sms response as well any time.<br />
					10. You can also select sim for messaging if device has dual sim feature otherwise it will use default sim.<br />
				</strong>
			</div>
			<form method="post" action="server.php" enctype="multipart/form-data">
				<?php
					$selDevices = "select * from mobile_devices where device_token !='' and user_id='".$_SESSION['user_id']."' order by id desc";
					$exeDevices = mysqli_query($link,$selDevices);
					if(mysqli_num_rows($exeDevices)==0){
						echo '<div class="alert alert-danger">No active device found.</div>';
					}else{
						echo '<ul class="list-group">';
						while($device = mysqli_fetch_assoc($exeDevices)){
							if($device['device_status']=='1')
								$seld = 'checked="checked"';
							else
								$seld = '';
							echo '<li class="list-group-item"><label>';
								echo '<input '.$seld.' type="checkbox" name="mobile_device[]" style="margin-top:-2px; vertical-align:middle" value="'.$device['id'].'"> '.$device['device_name'].'</label>';
								echo '<label class="label label-danger" style="float:right; color:#ffffff; font-weight:bold; padding:5px;margin-left:10px; cursor:pointer" onclick="deleteMobileDevice('.$device['id'].')">Delete</label>';
								echo '<label class="label label-success" style="float:right; color:#ffffff; font-weight:bold; padding:5px;">'.$device['created_date'].'</label>';
							echo '</li>';
						}
						echo '</ul>';
					}
				?>	
				<div class="form-group">
					<input type="hidden" name="devices_json" id="devices_json" value="" />
					<input type="hidden" name="cmd" value="update_mobile_device" />
					<button class="btn btn-primary waves-effect waves-light" type="submit" onClick="getDeviceStatus()"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>

					<span style="text-align:right; padding-right:0px; float: right">
						<a target="_blank" href="<?php echo getServerUrl().'/tandc.php'?>">Terms and Conditions</a>&nbsp;
						
						<a href="https://codecanyon.net/item/nimble-messaging-business-mobile-sms-marketing-application-for-android/20956083" target="_blank">Get Mobile App</a>
					</span>
				</div>
			</form>
		</div>
		-->
		<?php if($_SESSION['user_type']=='1'){?>
			<div class="tab-pane" id="sms_gateways">
				<form method="post" action="server.php" enctype="multipart/form-data">
					<?php
						$twilio = 'none';
						$plivo  = 'none';
						$nexmo  = 'none';
						$signalWire  = 'none';
						$mobileSim  = 'none';
						$trumpia = 'none';
	
						if(($row['sms_gateway']=='twilio') || (trim($row['sms_gateway'])=='')){
							$twilio = 'block';
						}else if($row['sms_gateway']=='plivo'){
							$plivo  = 'block';
						}else if($row['sms_gateway']=='nexmo'){
							$nexmo  = 'block';
						}else if($row['sms_gateway']=='mobile_sim'){
							$mobileSim  = 'block';
						}else if($row['sms_gateway']=='trumpia'){
							$trumpia  = 'block';
						}
					?>
					<div class="form-group">
						<label>SMS Gateway</label>
						<select name="sms_gateway" class="form-control smsGateWay">
							<option <?php if($row['sms_gateway']=='twilio')echo  DBout('selected="selected"');?> value="twilio">Twilio</option>
							<option <?php if($row['sms_gateway']=='plivo')echo  DBout('selected="selected"');?> value="plivo">Plivo</option>
							<option <?php if($row['sms_gateway']=='nexmo')echo  DBout('selected="selected"');?> value="nexmo">Nexmo</option>
							<option <?php if($row['sms_gateway']=='trumpia')echo  DBout('selected="selected"');?> value="trumpia">Trumpia</option>
						</select>
					</div>
			
					<div class="trumpiaInfo" style="display:<?php echo $trumpia;?>">
						<div class="form-group">
							<label>Trumpia API Key</label>
							<input type="text" name="trumpia_api_key" class="form-control" value="<?php echo  DBout($row['trumpia_api_key'])?>" />
						</div>
					</div>

					<div class="nexmoInfo" style="display:<?php echo  DBout($nexmo)?>">
						<div class="alert alert-danger"><span><b> Warning - </b> Nexmo does not support MMS messages.<br /><b> Info - </b> Please set this <?php echo DBout(getServerUrl()).'/sms_controlling.php'?> as a webhook url by editing your desired number from nexmo dashboard.</span></div>
						<div class="form-group">
							<label>Nexmo API Key</label>
							<input type="text" name="nexmo_api_key" class="form-control" value="<?php echo  DBout($row['nexmo_api_key'])?>" />
						</div>
						<div class="form-group">
							<label>Nexmo API Secret</label>
							<input type="text" name="nexmo_api_secret" class="form-control" value="<?php echo  DBout($row['nexmo_api_secret'])?>" />
						</div>
					</div>
		
					<div class="plivoInfo" style="display:<?php echo  DBout($plivo)?>">
						<div class="form-group">
							<label>Plivo Auth ID</label>
							<input type="text" name="plivo_auth_id" class="form-control" value="<?php echo  DBout($row['plivo_auth_id'])?>" />
						</div>
						<div class="form-group">
							<label>Plivo Auth Token</label>
							<input type="text" name="plivo_auth_token" class="form-control" value="<?php echo  DBout($row['plivo_auth_token'])?>" />
						</div>
					</div>
			
					<div class="twilioInfo" style="display:<?php echo DBout($twilio)?>">
						<div class="form-group">
							<label>Twilio Account sid</label>
							<input type="text" name="twilio_sid" class="form-control" value="<?php echo DBout($row['twilio_sid'])?>" />
						</div>
						<div class="form-group">
							<label>Twilio Account Token</label>
							<input type="text" name="twilio_token" class="form-control" value="<?php echo DBout($row['twilio_token'])?>" />
						</div>
						<div class="form-group">
							<?php
							$disSenderID = DBout('none');
							$senderIDCheck = DBout('');
							if($row['enable_sender_id']=='1'){
								$senderIDCheck = DBout('checked="checked"');
								$disSenderID = DBout('block');
							}
							?>
							<label>Enable Sender ID:
								<input <?php echo DBout($senderIDCheck) ?> type="checkbox" name="enable_sender_id" class="enableSenderID" value="1" />
							</label>
						</div>
						<div class="form-group senderID" style="display:<?php echo DBout($disSenderID)?>">
							<p id="para_text">
								Before using Must enable in your twilio account.<br />
								Must configure your phone number with a twilio messaging service.<br />
								<i>For branded one-way messaging, many countries allow an alphanumeric string as the sender ID. Alpha Sender ID allows you to add your company name or brand to your Messaging Service. When sending messages to a country where an alphanumeric sender ID is accepted, Twilio will use your Alpha Sender ID as the From parameter to deliver your message. A phone number from your Messaging Service will be selected if your recipient is in a country where alphanumeric sender IDs are not supported.</i>
							</p>
							<label>Twilio Sender ID</label>
							<input type="text" name="twilio_sender_id" class="form-control" value="<?php echo DBout($row['twilio_sender_id'])?>" />
						</div>

							<?php
								$whatsappdiv = 'none';
								$whatsapp_checker = '';
								if($row['enable_whatsapp'] == '1'){
									$whatsappdiv = 'block';
									$whatsapp_checker = 'checked="checked"';
								}
							?>
							<label style="margin: 13px 0px;">Enable Whatsapp:
								<input <?php echo DBout($whatsapp_checker) ?> type="checkbox" name="enable_whatsapp" class="enablewhatsapp" value="1" />
							</label>
						<div class="form-group" id="whatsapp_div" style="display: <?php echo $whatsappdiv?>">
							<label>WhatsApp Business Number</label>

							<input type="text" name="whatsapp_business_number" class="form-control" value="<?php echo $whatsapp_business_number; ?>" />
							<br /><span>Enable WhatApp Sender <a href="https://www.twilio.com/console/sms/whatsapp/senders" target="_blank">here</a></span>
						</div>
					</div>	
				<div class="form-group mobileSimSection" style="display:<?php echo DBout($mobileSim)?>"></div>
			
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_sms_gateways" />
					<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(1)'"> Cancel </button>
				</div>
			</form>
		</div>
		<div class="tab-pane" id="payment_processors">
			<form action="server.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label class="radio-inline">
						<input type="radio" name="payment_processor" value="1" <?php if(($row['payment_processor']==1) || ($row['payment_processor']=='0')){ echo DBout("checked"); } ?> >
					Paypal</label>
					&nbsp;&nbsp;
					<label class="radio-inline">
						<input type="radio" name="payment_processor" value="2" <?php if($row['payment_processor']==2){ echo DBout("checked"); } ?>>
					Auth.Net</label>
					&nbsp;&nbsp;
					<label class="radio-inline">
						<input type="radio" name="payment_processor" value="3" <?php if($row['payment_processor']==3){ echo DBout("checked"); } ?>>
					Stripe</label>
				</div>
				<?php 
				if(($row['payment_processor']==1) || $row['payment_processor']==0){
					
					$paypal= DBout("block");
					
					$auth= DBout("none");
					
					$stripe= DBout("none");
					
				}else if($row['payment_processor']==2){
					
					$paypal= DBout("none");
					
					$auth= DBout("block");
					
					$stripe= DBout("none");
					
				}else if($row['payment_processor']==3){
					
					$paypal= DBout("none");
					
					$auth= DBout("none");
					
					$stripe= DBout("block");
					
				}
				?>
				
			<div id="stripe_area" style="display:<?php echo DBout($stripe); ?>;">
					<div class="form-group" id="stripe_secret_key">
						<label>Stripe</label>
						<br />
						<label>Stripe Secret Key</label>
						<input type="text" name="stripe_secret_key" class="form-control" value="<?php echo DBout($row['stripe_secret_key'])?>"/>
					</div>
					<div class="form-group" id="stripe_publishable_key">
						<label>Stripe Publishable Key</label>
						<input type="text" name="stripe_publishable_key" class="form-control" value="<?php echo DBout($row['stripe_publishable_key'])?>"/>
					</div>
				</div>
				<div id="authnet_area" style="display:<?php echo DBout($auth); ?>;">
					<div class="form-group" id="auth_net_trans_key">
						<label>Authorize.Net</label>
						<br />
						<label>Transaction ID</label>
						<input type="text" name="auth_net_trans_key" class="form-control" value="<?php echo DBout($row['auth_net_trans_key'])?>"/>
					</div>
					<div class="form-group" id="auth_net_api_login_id">
						<label>API Login ID</label>
						<input type="text" name="auth_net_api_login_id" class="form-control" value="<?php echo DBout($row['auth_net_api_login_id'])?>"/>
					</div>
				</div>
					<div id="paypal_area" style="display:<?php echo DBout($paypal); ?>">
					<div class="form-group">
						<label>Paypal</label><br />
						<?php 
						if($row['paypal_switch']=='0'){
							$sandbox = DBout('checked="checked"');
							$sandboxSection = DBout('block');
							$live = DBout('');
							$liveSection = DBout('none');
						}else{
							$live = DBout('checked="checked"');
							$liveSection = DBout('block');
							$sandbox = DBout('');
							$sandboxSection = DBout('none');
						}
						?>
						<label class="radio-inline">
							<input type="radio" name="paypal_switch" value="0" <?php echo DBout($sandbox)?>>
						Sandbox</label>
						&nbsp;&nbsp;
						<label class="radio-inline">
							<input type="radio" name="paypal_switch" value="1" <?php echo DBout($live)?>>
						Live</label>
					</div>
					<div class="form-group" id="paypal_sandbox_email" style="display:<?php echo DBout($sandboxSection)?>;">
						<label>Paypal Sandbox Email</label>
						<input type="email" name="paypal_sandbox_email" class="form-control" value="<?php echo DBout($row['paypal_sandbox_email'])?>"/>
					</div>
					<div class="form-group" id="paypal_live_email" style="display:<?php echo DBout($liveSection)?>;">
						<label>Paypal Live Email</label>
						<input type="email" name="paypal_email" class="form-control" value="<?php echo DBout($row['paypal_email'])?>"/>
					</div>
				</div>
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_payment_processor" />
					<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
				</div>
			</form>
		</div>
		<div class="tab-pane" id="pricing_details">
			<form action="server.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label>InComing SMS Credits Charges</label>
					<input type="text" name="incoming_sms_charge" class="form-control decimalOnly" value="<?php echo DBout($row['incoming_sms_charge'])?>" required="required"/>
				</div>
				<div class="form-group">
					<label>Outgoing SMS Credits Charges</label>
					<input type="text" name="outgoing_sms_charge" class="form-control decimalOnly" value="<?php echo DBout($row['outgoing_sms_charge'])?>" required="required"/>
				</div>
				<div class="form-group">
					<label>MMS Credits Charges</label>
					<input type="text" name="mms_credit_charges" class="form-control decimalOnly" value="<?php echo DBout($row['mms_credit_charges'])?>" required="required"/>
				</div>
				<div class="form-group">
					<label>Per Credit Charges</label>
					<input type="text" name="per_credit_charges" class="form-control decimalOnly" value="<?php echo DBout($row['per_credit_charges'])?>" required="required"/>
				</div>
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_pricing_details" />
					<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
				</div>
			</form>
		</div>
		<div class="tab-pane" id="email_templates">
			<form action="server.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label>New User Email Subject </label>
					<input type="text" name="email_subject" class="form-control" value="<?php echo DBout($row['email_subject'])?>" />
				</div>
				<div class="form-group">
					<label>New User Email Message</label>
					<span id="email_message">Merge tags: First name = %first_name%, Last name = %last_name% Login Email = %login_email%, Login password = %login_pass%, Login URL = %login_url%</span>
					<textarea name="new_app_user_email" id="new_user_email" class="form-control"><?php echo DBout($row['new_app_user_email'])?></textarea>
				</div>
				<div class="form-group">
					<label>New User Email Notification Subject for Admin </label>
					<input type="text" name="email_subject_for_admin_notification" class="form-control" value="<?php echo DBout($row['email_subject_for_admin_notification'])?>" />
				</div>
				<div class="form-group">
					<label>New User Email Notification Message for Admin</label>
					<span id="immediate_msg">Merge tags: Email = %email%</span>
					<textarea name="new_app_user_email_for_admin" id="new_app_user_email_for_admin" class="form-control"><?php echo DBout($row['new_app_user_email_for_admin'])?></textarea>
				</div>
				<div class="form-group">
					<label>Successful Payment Email Subject </label>
					<input type="text" name="success_payment_email_subject" class="form-control" value="<?php echo DBout($row['success_payment_email_subject'])?>" />
				</div>
				<div class="form-group">
					<label>Successful Payment Email Message</label>
					<textarea name="success_payment_email" id="success_payment_email" class="form-control"><?php echo  DBout($row['success_payment_email'])?></textarea>
				</div>
				<div class="form-group">
					<label>Failed Payment Email Subject </label>
					<input type="text" name="failed_payment_email_subject" class="form-control" value="<?php echo DBout($row['failed_payment_email_subject'])?>" />
				</div>
				<div class="form-group">
					<label>Failed Payment Email Message</label>
					<textarea name="failed_payment_email" id="failed_payment_email" class="form-control"><?php echo DBout($row['failed_payment_email'])?></textarea>
				</div>
				<div class="form-group">
					<label>Payment Notification Email Subject </label>
					<input type="text" name="payment_noti_subject" class="form-control" value="<?php echo DBout($row['payment_noti_subject'])?>" />
				</div>
				<div class="form-group">
					<label>Payment Notification Email Message</label>
					<span id="immediate_msg">Merge tags: Email = %email%</span>
					<textarea name="payment_noti_email" id="payment_noti_email" class="form-control"><?php echo DBout($row['payment_noti_email'])?></textarea>
				</div>			
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_email_templates" />
					<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
				</div>
			</form>
		</div>
	<?php }?>
	<div class="tab-pane" id="propend_messages">
		<form action="server.php" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label>Append Text</label>
				<span id="immediate_msg">Will be appended with each outbound message. </span>
				<textarea name="append_text" class="form-control textCounter"><?php echo DBout($row['append_text'])?></textarea>
				<span class="showCounter"> <span class="showCount"><?php echo DBout($maxLength-strlen($row['append_text']))?></span> Characters left </span>
			</div>
				
			<div class="form-group">
				<label>Unsubscribe Message</label>
				<span id="immediate_msg">Will respond on unsubscription. </span>
				<textarea name="unsub_message" class="form-control textCounter"><?php echo DBout($row['unsub_message'])?></textarea>
				<span class="showCounter"> <span class="showCount"><?php echo DBout($maxLength-strlen($row['unsub_message']))?></span> Characters left </span>
			</div>
			<div class="form-group">
				<label>GDPR Message</label>
				<span id="immediate_msg">Will respond on receiving GDPR Keyword, use %gdpr_link% where you want to add profile link in message.</span>
				<textarea name="gdpr_message" class="form-control textCounter"><?php echo DBout($row['gdpr_message'])?></textarea>
				<span class="showCounter"> <span class="showCount"><?php echo DBout($maxLength-strlen($row['gdpr_message']))?></span> Characters left </span>
			</div>
			<div class="form-group">
				<input type="hidden" name="cmd" value="update_propend_msgs" />
				<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
				<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
			</div>
		</form>
	</div>
	
<?php
	if($_SESSION['user_type']=='1'){
		?>
		<div class="tab-pane" id="cron_jobs_section">
			<form method="post" enctype="multipart/form-data" action="server.php">
				<div class="form-group">
					<label>Cron Stop Time From</label>
					<input type="text" name="cron_stop_time_from" id="cron_stop_time_from" class="form-control" value="<?php echo DBout($row['cron_stop_time_from'])?>" />
				</div>
				<div class="form-group">
					<label>Cron Stop Time To</label>
					<input type="text" name="cron_stop_time_to" id="cron_stop_time_to" class="form-control" value="<?php echo DBout($row['cron_stop_time_to'])?>" />
				</div>
				<div class="form-group">
					<label id="fa_trashs">Cron URL for every 15 minutes: </label><br />
					<p><?php echo DBout('curl '.getServerURL().'/cron.php')?></p>
					Or
					<p>wget -q -O - <?php echo DBout(getServerURL()).'/cron.php'?> >/dev/null 2>&1</p>
					<img src="images/cron_guide.png" width="100%" />
					<img src="images/cron_guide_2.png" width="100%" />
				</div>
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_cronjob_settings" />
					<input type="submit" value="Update" class="btn btn-primary" />
				</div>
			</form>
		</div>
		
		<div class="tab-pane" id="bitly_section">
			<form method="post" action="server.php">
				<div class="form-group">
					<label>Bit.ly API Key</label>
					<input type="text" name="bitly_key" class="form-control" value="<?php echo DBout($row['bitly_key'])?>" />
				</div>
				<div class="form-group">
					<label>Bit.ly Token</label>
					<input type="text" name="bitly_token" class="form-control" value="<?php echo DBout($row['bitly_token'])?>" />
				</div>
				<div class="form-group">
					<input type="hidden" name="cmd" value="update_bitly_api_keys" />
					<input type="submit" value="Save" class="btn btn-primary" />
				</div>
			</form>
		</div>
	<?php }
	?>
	<div class="tab-pane" id="footer_section">
		<form method="post" enctype="multipart/form-data" action="server.php">
			<div class="form-group">
				<label>Footer Customization</label>
				<textarea name="footer_customization" id="footer_customization" class="form-control"><?php echo DBout($row['footer_customization'])?></textarea>
			</div>
			<div class="form-group">
				<input type="hidden" name="cmd" value="update_footer_customization" />
				<input type="submit" value="Update" class="btn btn-primary" />
			</div>
		</form>
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
<div id="nexmoInfoModel" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title" id="fa_trashs">Nexmo Information</h6>
			</div>
			<div class="modal-body" id="model_body">
				<p>1- You have to set webhook url for this number from your nexmo dashboard.</p>
				<p><img src="images/nexmo_webhook_guide.png" id="full_width" /></p>
				<p>2- Enter webhook url in mentioned field.</p>
				<p><img src="images/webhook_section.png" id="full_width" /></p>
			</div>
		</div>
	</div>
</div>
<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script src="assets/js/timepicki.js"></script> 
<script src="assets/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
	function getDeviceStatus(){
		var devices = {};
		$('input[name="mobile_device[]"]').each(function(index){
			if($(this).is(":checked")==true){
				devices[index] = {
					"id" : $(this).val(),
					"status" : "1"
				}
			}else{
				devices[index] = {
					"id" : $(this).val(),
					"status" : "0"
				}
			}
		});
		var devicesJson = JSON.stringify(devices);
		$('#devices_json').val(devicesJson);
	}
	function deleteMobileDevice(deviceID){
		if(confirm("Are you sure you want to delete this device?")){
			$.post("server.php",{"cmd":"delete_mobile_device",deviceID:deviceID},function(r){
				window.location = 'settings.php';
			});
		}
	}
	
	
    var user_id,user_type,api_key,get_number_on_load,iso_country;
                    api_key = '<?php echo $row['api_key']?>';
            <?php if($_SESSION['user_type']=='1'){?>
                    user_type = 1;
            <?php } ?>
                    user_id = "<?php echo $_SESSION['user_id']?>";
            <?php
            if($getNumberOnload){
            ?>
                get_number_on_load = 1;
            <?php } ?>
                iso_country = '<?php echo $pkgInfo['iso_country']?>';
</script>
<script src="js/settings.js"></script>