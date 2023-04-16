<?php
	include_once("header.php");
	include_once("left_menu.php");
    $sql = sprintf("select * from application_settings where user_id=%s ",
            mysqli_real_escape_string($link,DBin($_SESSION['user_id']))
        );
	$res = mysqli_query($link,$sql);
	$row = mysqli_fetch_assoc($res);
       
?>
<link rel="stylesheet" href="docs/assets/print.css" type="text/css" media="print">
<link rel="stylesheet" href="assets/blueprint-css/Plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								Nimble Messaging <?php echo DBout($row['version']); ?> User Guide by Ranksol
								<input type="button" class="btn btn-primary move-right" value="Back"onclick="window.location=history.go(-1)" />
							</h4>
							<p class="category">Updated version with new look and feel.</p>
						</div>
						<div class="content table-responsive">
							<div class="borderTop">
								<div class="span-6 colborder info prepend-1">
									<p class="prepend-top"> <strong> Created: 05/06/2017<br>
										By: Ranksol<br>
										Email: <a href="mailto:Azhar@ranksol.com">Azhar@ranksol.com</a> </strong> </p>
								</div>
								
								<div class="span-12 last">
									<p class="prepend-top append-0">Thank you for purchasing my Application. If you have any questions that are beyond the scope of this help file, please feel free to email via my user page contact form <a href="http://codecanyon.net/user/promotionking">here</a>. Thanks so much!</p>
								</div>
							</div>
							<hr />
							<h2 id="toc" class="alt">Table of Contents</h2>
							<ol class="alpha">
								<li><a href="#installation">Installation</a></li>
								<li><a href="#create_admin_account">Creating Admin Account</a></li>
								<li><a href="#admin_login">Admin Login</a></li>
								<li><a href="#subaccount_signup">Sub-Account Signup</a></li>
								<li><a href="#app_dashboard">Application Dashboard</a></li>
								<li><a href="#create_campaign">Create SMS Campaign</a></li>
								<li><a href="#create_webform">Create WebForm</a></li>
								<li><a href="#bulk_sms">Bulk SMS</a></li>
								<li><a href="#schduler">SMS Scheduler</a></li>
								<li><a href="#subscribers">Subscribers</a></li>
								<li><a href="#sms_report">SMS Report</a></li>
								<li><a href="#pricing_plans">Pricing Plans</a></li>
								<li><a href="#app_users">Application Sub-Accounts</a></li>
								<li><a href="#payment_history">Payment History</a></li>
								<li><a href="#app_settings">Application Settings</a></li>
                                <li><a href="#api_docs">API Docs</a></li>
                                <li><a href="#whatsapp">WhatsApp integration</a></li>
							</ol>
							<hr>
							
								<h3 id="installation"><strong>A) Application Installation</strong> - <a href="#toc">top</a></h3>
								<p>
									Create a directory with your desired name and unzip purchased application folder in it, after unzip navigate to your created application directory.<br>
									You will have to provide an already created database information here and hit Check Connection button to ensure that the database credentials are valid, hit Save & Next button to proceed further.
								</p>
								<img src="docs/dbinfo.png" alt="" width="100%" />
								<hr>
								
								<h3 id="create_admin_account"><strong>B) Creating Admin Account</strong> - <a href="#toc">top</a></h3>
								<p>
									After connected to your database you have to add information for your application admin account.<br>
									Please read our terms and conditions and privacy policies before creating account.
								</p>
								<img src="docs/add_account.png" alt="" width="100%" />
								<hr>
								
								<h3><strong>B-1) Finalizing installation.</strong> - <a href="#toc">top</a></h3>
								<p>
									You have successfully configured the application, just click on mentioned login link to use application.
								</p>
								<img src="docs/congrates.png" alt="" width="100%" />
								<hr>
							
								<h3 id="admin_login"><strong>C) Admin Login.</strong> - <a href="#toc">top</a></h3>
								<p>
									Login with your provided admin account information during installation.
								</p>
								<img src="docs/login.png" alt="" width="100%" />
								<hr>
							
								<h3 id="subaccount_signup"><strong>D) Sub-Account Signup.</strong> - <a href="#toc">top</a></h3>
								<p>
									You can invite users to create sub-account on your application by clicking on signup link on application login page.
									Sub users will be able to use your application by purchasing your created sms plans.
								</p>
								<img src="docs/sub-signup.png" alt="" width="100%" />
								<hr>
								
								<h3 id="app_dashboard"><strong>E) Application dashboard.</strong> - <a href="#toc">top</a></h3>
								<p>
									After login you will be redirected to application dashboard where you can see stats about your campaigns, webforms, subscribers and un-subscribers.<br>
									It will show you overall stats of the application.
								</p>
								<img src="docs/dashboard.png" alt="" width="100%" />
								<hr>
								
								<h3 id="create_campaign"><strong>F) Create SMS Campaign.</strong> - <a href="#toc">top</a></h3>
								<p>
									To create sms campaign click on Campaigns tab in left menu, click Add New.
									Fill form data according to given instructions.<br>
									<b>Campaign SMS</b>: will send to each subscriber who send campaign keyword.<br>
									<b>Double OPT-IN SMS</b>: will send to each subscriber with campaign sms if you have enabled double opt-in option in application settings, subscriber will not get any follow up messages untill he sends YES in reply of double opt-in message.<br>
								</p>
								<img src="docs/create_campaign.png" alt="" width="100%" />
								<br><br>
								<h3><strong>F-1) Create SMS Campaign.</strong></h3>
								<p>
									<b>Already Member SMS</b>: will send to subscriber if he is already subscribed in campaign.<br>
									You can also schedule unlimited follow up messages for later sending, See screen shot below mentioned for details.
								</p>
								<img src="docs/create_campaign-1.png" alt="" width="100%" />
								<hr>
								
								<h3><strong>F-2) List of Campaigns.</strong></h3>
								<p>
									You can view and edit your campaigns by click view/edit link in menu.
								</p>
								<img src="docs/campaign_list.png" width="100%" />
								<hr>
								
								<h3 id="create_webform"><strong>G) Create WebForm.</strong> - <a href="#toc">top</a></h3>
								<p>
									To create webform click on WebForms tab in left menu, click Add New.
									Fill form data according to given instructions.
									You have choice to create webform responsive or fixed width.
								</p>
								<img src="docs/webform.png" alt="" width="100%" />
								<br><br>
								<h3><strong>G-1) Field Customization.</strong></h3>
								<p>
									You have full customization options for fixed width webform, see screen shot below mentioned for details.
								</p>
								<img src="docs/field_custom.png" alt="" width="100%" />
								<hr>
								
								<br>
								<h3><strong>G-2) WebForm List.</strong></h3>
								<p>
									You can view and edit your campaigns by click view/edit link in menu.
								</p>
								<img src="docs/webform_list.png" alt="" width="100%" />
								<p>Click to copy embed code.</p>
								<img src="docs/copy_embed_code.png" alt="" width="100%" />
								<hr>
								
								<h3 id="bulk_sms"><strong>H) Bulk SMS.</strong> - <a href="#toc">top</a></h3>
								<p>
									You can send bulk sms to subscribers.
								</p>
								<img src="docs/bulk_sms.png" alt="" width="100%" />
								<h3><strong>H-1) Bulk SMS to Single Number/Group.</strong></h3>
								<img src="docs/bulk_single_number.png" alt="" width="100%" />
								
								<h3 id=""><strong>H-2) Send Bulk SMS by Date Range.</strong></h3>
								<img src="docs/bulk_to_date.png" alt="" width="100%" />
								<hr>
								
								<h3 id="schduler"><strong>I) SMS Scheduler.</strong> - <a href="#toc">top</a></h3>
								<p>
									To schedule sms click on SMS Schduler tab in left menu, click Add New.
									Fill form data according to given instructions.
								</p>
								<img src="docs/scheduler.png" alt="" width="100%" />
								<h3><strong>I-1) Schedulers List.</strong></h3>
								<p>
									Listing your already saved schedulers.
								</p>
								<img src="docs/scheduler_list.png" alt="" width="100%" />
								<hr>
								
								<h3 id="subscribers"><strong>J) Subscribers.</strong> - <a href="#toc">top</a></h3>
								<p>
									Admin can add subscribers manualy in any group.
								</p>
								<img src="docs/subscribers.png" alt="" width="100%" />
								<h3><strong>J-1) List of Subscribers.</strong></h3>
								<p>
									Showing all subscribers of the application.
								</p>
								<img src="docs/list_of_subscribers.png" alt="" width="100%" />
								<hr>
								
								<h3 id="sms_report"><strong>K) SMS Report.</strong> - <a href="#toc">top</a></h3>
								<p>
									Application is tracking each sms in/out from your application and building its report accordingly.
								</p>
								<img src="docs/sms_report.png" alt="" width="100%" />
								<hr>
								
								<h3 id="pricing_plans"><strong>L) Pricing Plans.</strong> - <a href="#toc">top</a></h3>
								<p>
									Admin can make pricing plans according to his desired and pricing, To make a pricing plan click on Pricing plan tab in left menu, click Add New.
									Fill form data according to given instructions.
								</p>
								<img src="docs/pricing_plans.png" alt="" width="100%" />
								<h3><strong>L-1) Pricing Plans List.</strong></h3>
								<p>
									Listing your already created plans.
								</p>
								<img src="docs/list_of_plans.png" alt="" width="100%" />
								<hr>
								
								<h3 id="app_users"><strong>M) Application Sub-Accounts.</strong> - <a href="#toc">top</a></h3>
								<p>
									Admin can create application sub accounts manualy, To create an account click on Application users tab in left menu, click Add New.
									Fill form data according to given instructions.
								</p>
								<img src="docs/app_users.png" alt="" width="100%" />
								<h3><strong>L-1) Pricing Plans List.</strong></h3>
								<p>
									Listing your already created sub-accounts.
								</p>
								<img src="docs/app_users_list.png" alt="" width="100%" />
								<hr>
								
								<h3 id="payment_history"><strong>N) Payment History.</strong> - <a href="#toc">top</a></h3>
								<p>
									All of your payment history will be shown under payment history tab.
								</p>
								<img src="docs/payment_history.png" alt="" width="100%" />
								<hr>
								
								<h3 id="app_settings"><strong>O) Application Settings.</strong> - <a href="#toc">top</a></h3>
								<p>
									Admin and sub accounts can set its desired application settings under settings tab.
								</p>
								<img src="docs/application_settings.png" alt="" width="100%" />
								<h3><strong>O-1) Application Settings.</strong></h3>
								<p>
									Admin can set his sms charges and per credit charges in settings.
								</p>
								<img src="docs/application_settings-1.png" alt="" width="100%" />
								<img src="docs/application_settings-2.png" alt="" width="100%" />
								<hr>
                                
                                
                                
                                <h3 id="api_docs"><strong>P) API Docs.</strong> - <a href="#toc">top</a></h3>
								<p>
									Application API has the following Methods : 
								</p>
								<p>
									<ul>
                                        <li><a href="#make_subscriber">Make Subscriber</a></li>
                                        <li><a href="#get_subscribers">Get Subscribers</a></li>
                                        <li><a href="#make_user">Make User</a></li>
                                        <li><a href="#send_bulk_sms">Send Bulk SMS</a></li>
                                        <li><a href="#make_pricing_plan">Make Pricing Plan</a></li>
                                    </ul>
								</p>
                                
                                <h4 class="font-bold">API End Ponit</h4>
                                <div class="alert alert-info custom_alert">
                                    <?php echo DBout(getServerUrl().'/nmapi/phpapi.php?api_key='.$row['api_key'],ENT_COMPAT); ?>
                                </div>
                                
                                <h4 class="font-bold" id="make_subscriber">Make Subscriber</h4>
                                <p>
									The following parametters are required for this method 
								</p>
                                <table class="table table-condensed font-13">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Value</th>
                                            <th>Required</th>
                                            <th class="width-65-per">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="info">
                                            <th>cmd</th>
                                            <td>make_subscriber</td>
                                            <td>true </td>
                                            <td>use <code>make_subscriber</code> to Add Subscribe </td>
                                        </tr>
                                        <tr>
                                            <th>name</th>
                                            <td>Alex</td>
                                            <td>true </td>
                                            <td>Name of the subscribe</td>
                                        </tr>
                                        <tr>
                                            <th>phone</th>
                                            <td>+11234567890</td>
                                            <td>true </td>
                                            <td>Phone Number of the subscriber</td>
                                        </tr>
                                        <tr>
                                            <th>email</th>
                                            <td>+11234567890</td>
                                            <td>false </td>
                                            <td>Email Address of the subscriber</td>
                                        </tr>
                                        <tr>
                                            <th>group_id</th>
                                            <td>1</td>
                                            <td>true </td>
                                            <td>The id of group/campaign, You can get id from URL bar while editing the campaign</td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <br>
                                <hr>
                                
                                <h4 class="font-bold" id="get_subscribers">Get Subscribers</h4>
                                <p>
									The following parametters are required for this method
								</p>
                                <table class="table table-condensed font-13">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Value</th>
                                            <th>Required</th>
                                            <th class="width-65-per">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="info">
                                            <th>cmd</th>
                                            <td>subscriber</td>
                                            <td>true </td>
                                            <td>use <code>subscriber</code> to Add Subscribe </td>
                                        </tr>
                                        <tr>
                                            <th>group_id</th>
                                            <td>1</td>
                                            <td>false </td>
                                            <td>The id of group/campaign, You can get id from URL bar while editing the campaign.<br /> Send <code>group_id</code> parametter to get the subscribers of specific campaign, do not send <code>group_id</code> to get all subscribers</td>
                                        </tr>
                                    </tbody>
                                </table>
								
                                <br>
								<hr>
                                
                                <h4 class="font-bold" id="make_user">Make User</h4>
                                <p>
									The following parametters are required for this method
								</p>
                                <table class="table table-condensed font-13">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Value</th>
                                            <th>Required</th>
                                            <th class="width-65-per">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="info">
                                            <th>cmd</th>
                                            <td>make_user</td>
                                            <td>true </td>
                                            <td>use <code>make_user</code> to Add Application User </td>
                                        </tr>
                                        <tr>
                                            <th>first_name</th>
                                            <td>Alex</td>
                                            <td>true </td>
                                            <td>First Name of User</td>
                                        </tr>
                                        <tr>
                                            <th>last_name</th>
                                            <td>Smith</td>
                                            <td>true </td>
                                            <td>Last Name of User</td>
                                        </tr>
                                        <tr>
                                            <th>email</th>
                                            <td>alex@gmail.com</td>
                                            <td>true </td>
                                            <td>Email Address of User</td>
                                        </tr>
                                        <tr>
                                            <th>password</th>
                                            <td>123456</td>
                                            <td>true </td>
                                            <td>Password of User</td>
                                        </tr>
                                        <tr>
                                            <th>plan_id</th>
                                            <td>1</td>
                                            <td>true </td>
                                            <td>The id of pricing plan, You can get id from URL bar while editing the pricing plan.</td>
                                        </tr>
                                    </tbody>
                                </table>
								
                                <br>
								<hr>
                                
                                <h4 class="font-bold" id="send_bulk_sms">Send Bulk SMS</h4>
                                <p>
									The following parametters are required for this method
								</p>
                                <table class="table table-condensed font-13">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Value</th>
                                            <th>Required</th>
                                            <th class="width-65-per">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="info">
                                            <th>cmd</th>
                                            <td>bulk_sms</td>
                                            <td>true </td>
                                            <td>use <code>bulk_sms</code> to Send Bulk SMS </td>
                                        </tr>
                                        <tr>
                                            <th>text</th>
                                            <td>SMS Text</td>
                                            <td>true </td>
                                            <td>SMS Message that will send to the subscribers</td>
                                        </tr>
                                        <tr>
                                            <th>group_id</th>
                                            <td>1</td>
                                            <td>false </td>
                                            <td>The id of group/campaign, You can get id from URL bar while editing the campaign.<br /> Send <code>group_id</code> parametter to send bulk sms to subscribers of specific campaign, do not send <code>group_id</code> to send bulk sms to all subscribers</td>
                                        </tr>
                                    </tbody>
                                </table>
								
                                <br>
								<hr>
                                
                                <h4 class="font-bold" id="make_pricing_plan">Make Pricing Plan</h4>
                                <p>
									The following parametters are required for this method
								</p>
                                <table class="table table-condensed font-13">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Value</th>
                                            <th>Required</th>
                                            <th class="width-65-per">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="info">
                                            <th>cmd</th>
                                            <td>make_plan</td>
                                            <td>true </td>
                                            <td>use <code>make_plan</code> to Make Pricing Plan </td>
                                        </tr>
                                        <tr>
                                            <th>title</th>
                                            <td>Golden</td>
                                            <td>true </td>
                                            <td>Title/Label of Pricing Plan</td>
                                        </tr>
                                        <tr>
                                            <th>sms_credits</th>
                                            <td>100</td>
                                            <td>true </td>
                                            <td>SMS Credits assign to Pricing Plan</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>phone_number_limit</th>
                                            <td>5</td>
                                            <td>true </td>
                                            <td>No of Phone Number(s) a user (having this package) can buy </td>
                                        </tr>
                                        <tr>
                                            <th>currency</th>
                                            <td>Golden</td>
                                            <td>true </td>
                                            <td>The Currency of Pricing Plan i.e "$"</td>
                                        </tr>
                                        <tr>
                                            <th>price</th>
                                            <td>100</td>
                                            <td>true </td>
                                            <td>Thr Price of Pricing Plan</td>
                                        </tr>
                                        <tr>
                                            <th>iso_country</th>
                                            <td>US</td>
                                            <td>true </td>
                                            <td>ISO ALPHA-2 Code of Country i.e "US", it can be any country's ISO code </td>
                                        </tr>
                                        <tr>
                                            <th>country</th>
                                            <td>United Status</td>
                                            <td>true </td>
                                            <td>The Country Name</td>
                                        </tr>
                                        <tr>
                                            <th>is_free_days</th>
                                            <td>1</td>
                                            <td>true </td>
                                            <td>Either allowed free days or not, <br />Possible values : 1 for Enable Free Days, 0 for Disable Free Days</td>
                                        </tr>
                                        <tr>
                                            <th>free_days</th>
                                            <td>5</td>
                                            <td>true </td>
                                            <td>No of days user is allowed to use free before payment, It can be digits i.e 10, 15, 20 etc</td>
                                        </tr>
                                    </tbody>
                                </table>
								
                                <br>
								<hr>
                                
                                <h3 id="whatsapp"><strong>Q) WhatsApp integration.</strong> - <a href="#toc">top</a></h3>
								<p>
									To integrate/pricing for WhatsApp integration into Nimble Web SMS Application please contact us <a href="https://ranksol.com/help/" target="_blank">here</a> 
								</p>
                                <p>
									When you will contact us, we will enable this feature for you, so when its enable you will see the "WhatsApp Business Number" in "SMS Gateway" tab in "Settings" page, Where you will add your "WhatsApp Business Number" and you have to enable "Enable WhatApp Sender" <a href="https://www.twilio.com/console/sms/whatsapp/senders" target="_blank">here</a> on Twilio<br /><br />
                                    When you will add your "WhatsApp Business Number" after enabling on Twilio, you will able to use this number to send and receive message, it will availabe as your gateway number to assign campaign.<br /> 
								</p>
                                
								<img src="docs/whatsapp.png" alt="" width="100%" />
								<hr>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>