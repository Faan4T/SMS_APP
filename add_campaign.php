<?php include_once("header.php");
include_once("left_menu.php");
$timeArray = getTimeArray();
$timeOptions = '';
foreach ($timeArray as $key => $value) {
    $timeOptions .= '<option value="' . DBout($key) . '">' . DBout($value) . '</option>';
}
$options = '';
for ($i = 1; $i <= 23; $i++) {
    if ($i > 1) $hour = DBout('hours'); else            $hour = DBout('hour');
    $options .= '<option value="+' . DBout($i) . ' ' . DBout($hour) . '">After ' . DBout($i) . ' ' . DBout(ucfirst($hour)) . '</option>';
} ?>
<div class="main-panel">    <?php include_once('navbar.php'); ?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header"><h4 class="title"> Create Campaign <input type="button"
                                                                                      class="btn btn-primary move-right"
                                                                                      value="Back"
                                                                                      onclick="window.location='view_campaigns.php'"/>
                            </h4>
                            <p class="category">Create your awesome campaigns here.</p></div>
                        <div class="content table-responsive">
                            <form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data"
                                  method="post">
                                <div class="form-group"><label>Title*</label> <input type="text" name="title"
                                                                                     parsley-trigger="change" required
                                                                                     placeholder="Enter title..."
                                                                                     class="form-control"></div>
                                <div class="form-group"><label>Keyword*</label> <input type="text" name="keyword"
                                                                                       parsley-trigger="change" required
                                                                                       placeholder="Enter keyword..."
                                                                                       class="form-control"></div>
                                <div class="form-group phoneNumberSection"><label>Phone Number*</label> <select
                                            name="phone_number" class="form-control">
                                        <option value="">- Select One -
                                        </option> <?php if ($appSettings['sms_gateway'] == 'twilio') {
                                            $sel = "select * from users_phone_numbers where user_id='" . $_SESSION['user_id'] . "' and ( type='1' or type='4' )";
                                        } else if ($appSettings['sms_gateway'] == 'plivo') {
                                            $sel = "select * from users_phone_numbers where user_id='" . $_SESSION['user_id'] . "' and type='2'";
                                        } else if ($appSettings['sms_gateway'] == 'nexmo') {
                                            $sel = "select * from users_phone_numbers where user_id='" . $_SESSION['user_id'] . "' and type='3'";
                                        } else {
                                            $sel = "select * from users_phone_numbers where user_id='" . $_SESSION['user_id'] . "'";
                                        }
                                        $rec = mysqli_query($link, $sel);
                                        if (mysqli_num_rows($rec)) {
                                            while ($numbers = mysqli_fetch_assoc($rec)) {
                                                echo '<option ' . $selected . ' value="' . $numbers['phone_number'] . '">' . $numbers['phone_number'] . '</option>';
                                            }
                                        } ?>                                    </select></div>
                                <div class="form-group"><label><input name="attach_mobile_device" value="1"
                                                                      type="checkbox"/> Attach mobile device</label>
                                </div>
                                <div class="form-group mobileDeviceSection" style="display: none"><label>Select
                                        device</label> <select name="device_id"
                                                               class="form-control">                                    <?php $selDevices = "select * from mobile_devices where user_id = " . $_SESSION['user_id'] . " and device_token !='' and device_status ='1' order by id desc";
                                        $exeDevices = mysqli_query($link, $selDevices) or die(mysqli_error($link));
                                        if (mysqli_num_rows($exeDevices) == 0) {
                                            echo '<option value="">No active device found.</option>';
                                        } else {
                                            while ($device = mysqli_fetch_assoc($exeDevices)) {
                                                echo '<option value="' . $device['id'] . '">' . $device['device_name'] . '</option>';
                                            }
                                        } ?>                                </select>
                                </div> <?php if ($_SESSION['user_type'] == '1') { ?>
                                    <div class="form-group"><label><input
                                                    name="share_with_subaccounts" <?php if ($row['share_with_subaccounts'] == '1') echo DBout('checked="checked"'); else echo DBout(''); ?>
                                                    value="1" type="checkbox"/> Share Campaign With Subaccounts</label>
                                    </div>                            <?php } ?>
                                <div class="col-lg-12 padding-0">
                                    <div class="portlet">
                                        <div class="portlet-heading bg-custom portlet_style"><h5 class="white"> SMS/MMS
                                                <a onclick="slideToggleMainSection(this,'sms_texts_section','');"
                                                   href="javascript:;"><i class="fa fa-plus white move-right"
                                                                          title="Open"></i></a></h5>
                                            <div class="portlet-widgets"><span class="divider"></span> <a
                                                        href="#bg-primary" data-parent="#accordion1"
                                                        data-toggle="collapse" class="" aria-expanded="true"><i
                                                            class="ion-minus-round white" title="Show/Hide"></i></a>
                                            </div>
                                        </div>
                                        <div class="panel-collapse sms_texts_section display-block"
                                             aria-expanded="true">
                                            <div class="portlet-body padding-10">
                                                <div class="form-group smsTextsSection"><label>Welcome SMS*</label>
                                                    <textarea name="welcome_sms" parsley-trigger="change" required
                                                              placeholder="Enter welcome sms text..."
                                                              class="form-control textCounter"></textarea> <span
                                                            class="showCounter">													<span
                                                                class="showCount"><?php echo DBout($maxLength) ?></span> Characters left												</span>
                                                </div>
                                                <div class="form-group smsTextsSection"><label>Already Member
                                                        SMS*</label> <textarea name="already_member_sms"
                                                                               parsley-trigger="change" required
                                                                               placeholder="Enter sms text for existing member..."
                                                                               class="form-control textCounter"></textarea>
                                                    <span class="showCounter">													<span
                                                                class="showCount"><?php echo DBout($maxLength) ?></span> Characters left												</span>
                                                </div>
                                                <div class="form-group smsTextsSection"><label>Select Media</label>
                                                    <input type="file" name="campaign_media" class="display-inline"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom_campaign_div_style"></div>
                                <div class="col-lg-12 padding-0">
                                    <div class="portlet">
                                        <div class="portlet-heading bg-custom portlet_style"><h5 class="white"> Make the
                                                campaign double opt-in <a
                                                        onclick="slideToggleMainSection(this,'double_optin_section','doubleOptInCheck');"
                                                        href="javascript:;"><i class="fa fa-plus white move-right"
                                                                               title="Open"></i></a></h5>
                                            <div class="portlet-widgets"><span class="divider"></span> <a
                                                        href="#bg-primary" data-parent="#accordion1"
                                                        data-toggle="collapse" class="" aria-expanded="true"><i
                                                            class="ion-minus-round white" title="Show/Hide"></i></a>
                                            </div>
                                        </div>
                                        <div class="panel-collapse  double_optin_section display-none"
                                             aria-expanded="true">
                                            <div class="portlet-body padding-10">
                                                <div class="form-group"><label><input type="checkbox"
                                                                                      name="double_optin_check"
                                                                                      onClick="slideToggleInnerSection(this,'doubleOptInSection')"/>
                                                        Enable Do</label></div>
                                                <div class="form-group doubleOptInSection display-none"><label>Do
                                                        SMS</label> <textarea name="double_optin"
                                                                              placeholder="Enter do text..."
                                                                              class="form-control textCounter"></textarea>
                                                    <span class="showCounter">													<span
                                                                class="showCount"><?php echo DBout($maxLength) ?></span> Characters left												</span>
                                                </div>
                                                <div class="form-group doubleOptInSection display-none"><label>Do
                                                        Confirm Message</label> <textarea
                                                            name="double_optin_confirm_message"
                                                            placeholder="Enter do text..."
                                                            class="form-control textCounter"></textarea> <span
                                                            class="showCounter">													<span
                                                                class="showCount"><?php echo DBout($maxLength) ?></span> Characters left												</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom_campaign_div_style"></div>
                                <div class="col-lg-12 padding-0">
                                    <div class="portlet">
                                        <div class="portlet-heading bg-custom portlet_style"><h5 class="white"> Get
                                                subscriber name/email <a
                                                        onclick="slideToggleMainSection(this,'get_email_section','get_email');"
                                                        href="javascript:;"><i class="fa fa-plus white move-right"
                                                                               title="Open"></i></a></h5>
                                            <div class="portlet-widgets"><span class="divider"></span> <a
                                                        href="#bg-primary" data-parent="#accordion1"
                                                        data-toggle="collapse" class="" aria-expanded="true"><i
                                                            class="ion-minus-round white" title="Show/Hide"></i></a>
                                            </div>
                                        </div>
                                        <div class="panel-collapse  get_email_section display-none"
                                             aria-expanded="true">
                                            <div class="portlet-body padding-10">
                                                <div class="form-group"><label class="checkbox-inline"><input
                                                                type="checkbox" name="get_subs_email" value="1"
                                                                onClick="slideToggleInnerSection(this,'subsEmailSection')"/>
                                                        Get subscriber email</label></div>
                                                <div class="form-group subsEmailSection display-none"><label>Message to
                                                        get subscriber Email</label> <textarea name="reply_email"
                                                                                               parsley-trigger="change"
                                                                                               placeholder="Enter sms to ask for email..."
                                                                                               class="form-control textCounter"></textarea>
                                                    <span class="showCounter">													<span
                                                                class="showCount"><?php echo DBout($maxLength) ?></span> Characters left												</span>
                                                </div>
                                                <div class="form-group subsEmailSection display-none"><label>Email
                                                        Received Confirmation Message</label> <textarea
                                                            name="email_updated" parsley-trigger="change"
                                                            placeholder="Confirmation sms text for receiving email..."
                                                            class="form-control textCounter"></textarea> <span
                                                            class="showCounter">													<span
                                                                class="showCount"><?php echo DBout($maxLength) ?></span> Characters left												</span>
                                                </div>
                                                <div class="form-group"><label class="checkbox-inline"><input
                                                                type="checkbox" name="get_subs_name_check"
                                                                onClick="slideToggleInnerSection(this,'subsNameSection')"
                                                                value="1"/> Get subscriber name</label></div>
                                                <div class="subsNameSection display-none">
                                                    <div class="form-group"><label>Message to get subscriber
                                                            name</label> <textarea name="msg_to_get_subscriber_name"
                                                                                   parsley-trigger="change"
                                                                                   placeholder="Message to get subscriber name..."
                                                                                   class="form-control textCounter"></textarea>
                                                        <span class="showCounter">														<span
                                                                    class="showCount"><?php echo DBout($maxLength) ?></span> Characters left													</span>
                                                    </div>
                                                    <div class="form-group"><label>Name Received Confirmation
                                                            Message</label> <textarea
                                                                name="name_received_confirmation_msg"
                                                                parsley-trigger="change"
                                                                placeholder="Name received confirmation message..."
                                                                class="form-control textCounter"></textarea> <span
                                                                class="showCounter">														<span
                                                                    class="showCount"><?php echo DBout($maxLength) ?></span> Characters left													</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom_campaign_div_style"></div>
                                <div class="col-lg-12 padding-0">
                                    <div class="portlet">
                                        <div class="portlet-heading bg-custom portlet_style"><h5 class="white"> Activate
                                                campaign for limited time <a
                                                        onclick="slideToggleMainSection(this,'campaign_expity_section','check_campaign_expiry');"
                                                        href="javascript:;"><i class="fa fa-plus white move-right"
                                                                               title="Open"></i></a></h5>
                                            <div class="portlet-widgets"><span class="divider"></span> <a
                                                        href="#bg-primary" data-parent="#accordion1"
                                                        data-toggle="collapse" class="" aria-expanded="true"><i
                                                            class="ion-minus-round white" title="Show/Hide"></i></a>
                                            </div>
                                        </div>
                                        <div class="panel-collapse  campaign_expity_section display-none"
                                             aria-expanded="true">
                                            <div class="portlet-body padding-10">
                                                <div class="form-group"><label><input type="checkbox"
                                                                                      name="campaign_expiry_check"
                                                                                      value="1"
                                                                                      onClick="slideToggleInnerSection(this,'campaignExpirySection')"/>
                                                        Enable/Disable</label></div>
                                                <div class="campaignExpirySection display-none">
                                                    <div class="col-md-6 padding-left-0">
                                                        <div class="form-group"><label>Start Date</label> <input
                                                                    type="text" class="form-control addDatePicker"
                                                                    name="start_date" placeholder="Start date."></div>
                                                    </div>
                                                    <div class="col-md-6 padding-right-0">
                                                        <div class="form-group"><label>End Date</label> <input
                                                                    type="text" class="form-control addDatePicker"
                                                                    name="end_date" placeholder="End date."></div>
                                                    </div>
                                                    <div class="form-group"><label>Expire Message</label> <textarea
                                                                name="expire_message" parsley-trigger="change"
                                                                placeholder="Expire Message"
                                                                class="form-control textCounter"></textarea> <span
                                                                class="showCounter">														<span
                                                                    class="showCount"><?php echo DBout($maxLength) ?></span> Characters left													</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom_campaign_div_style"></div>
                                <div class="col-lg-12 padding-0">
                                    <div class="portlet">
                                        <div class="portlet-heading bg-custom portlet_style"><h5 class="white"> Add
                                                Delay Messages for this campaign. <a
                                                        onclick="slideToggleMainSection(this,'follow_up_msg_section','');"
                                                        href="javascript:;"><i class="fa fa-plus white move-right"
                                                                               title="Add More"></i></a></h5>
                                            <div class="portlet-widgets"><span class="divider"></span> <a
                                                        href="#bg-primary" data-parent="#accordion1"
                                                        data-toggle="collapse" class="" aria-expanded="true"><i
                                                            class="ion-minus-round white" title="Show/Hide"></i></a>
                                            </div>
                                        </div>
                                        <div class="panel-collapse  follow_up_msg_section display-none" id="bg-primary"
                                             aria-expanded="true">
                                            <div class="form-group padding-10"><label><input type="checkbox"
                                                                                             name="followup_msg_check"
                                                                                             onClick="slideToggleInnerSection(this,'followUpContainer')"
                                                                                             value="1"/> Enable/Disable</label>
                                            </div>
                                            <div class="portlet-body followUpContainer padding-10 display-none"
                                                 id="followUpContainer">
                                                <div>
                                                    <table width="100%" class="delay_table">
                                                        <tr id="newrow">
                                                            <td width="25%">Select Days/Time</td>
                                                            <td><input type="text"
                                                                       class="form-control numericOnly delay-days"
                                                                       placeholder="Days delay..." name="delay_day[]"
                                                                       value="0" onblur="switchTimeDropDown(this)">&nbsp;
                                                                <select class="form-control timeDropDown delay-time"
                                                                        name="delay_time[]">                                                            <?php $timeArray = getTimeArray();
                                                                    foreach ($timeArray as $key => $value) { ?>
                                                                        <option value="<?php echo $key ?>"><?php echo $value ?></option>                                                            <?php } ?>
                                                                </select> <select
                                                                        class="form-control hoursDropDown delay-hours"
                                                                        name="delay_time_hours[]">                                                            <?php echo $options; ?>                                                        </select>
                                                                <span class="pointer margin-left-30"
                                                                      onClick="addMoreFollowUpMsg()"><i
                                                                            class="fa fa-plus plus-green-style"
                                                                            title="Add More"></i></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Message</td>
                                                            <td><textarea name="delay_message[]"
                                                                          class="form-control textCounter"></textarea>
                                                                <span class="showCounter">															<span
                                                                            class="showCount"><?php echo DBout($maxLength) ?></span> Characters left														</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Attach Media</td>
                                                            <td><input type="file" name="delay_media[]"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom_campaign_div_style"></div>
                                <div class="form-group text-right m-b-0">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit"> Save
                                    </button>
                                    <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"
                                            onclick="window.location = 'javascript:history.go(-1)'"> Cancel
                                    </button>
                                    <input type="hidden" name="cmd" value="create_campaign"/></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <?php include_once("footer_info.php"); ?></div><?php include_once("footer.php"); ?>
<script type="text/javascript">    var timeoptions = '<?php echo $timeOptions?>';
    var options = '<?php echo $options?>';
    var maxlenght = '<?php echo DBout($maxLength); ?>';</script>
<script src="scripts/add_campaign.js"></script>