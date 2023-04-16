<?php

	include_once("header.php");

	include_once("left_menu.php");

	if($_REQUEST['id']!=''){

		$sql = sprintf("select * from subscribers where id=%s ",

                mysqli_real_escape_string($link,DBin($_REQUEST['id']))

            );

		$res = mysqli_query($link,$sql);

		if(mysqli_num_rows($res)){

			$row = mysqli_fetch_assoc($res);

			$sel = sprintf("select id,group_id from subscribers_group_assignment where subscriber_id=%s ",

                    mysqli_real_escape_string($link,filterVar($row['id']))

                );

			$exe = mysqli_query($link,$sel);

			$rec = mysqli_fetch_assoc($exe);

			$groupID = DBout($rec['group_id']);

			$assignID= DBout($rec['id']);

			$cmd = DBout('update_subscriber');

			$buttonText = 'Update';

			$pageTitle = 'Update Customer';

		}else

			$row = array();

	}else{

		$row = array();	

		$cmd = DBout('add_subscriber');

		$buttonText = DBout('Save');

		$pageTitle = 'Add Customer';

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

								<?php echo $pageTitle; ?>

								<input type="button" class="btn btn-primary move-right" value="Back" onclick="window.location='view_subscribers.php'" />

							</h4>

							<p class="category">Add customer to your campaigns here.</p>

						</div>

						<div class="content table-responsive">

							<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 padding-right-0">

								<a href="server.php?cmd=download_sample_csv" class="btn btn-primary move-right">Sample CSV</a>&nbsp;

								<a href="#importSubs" data-toggle="modal" class="btn btn-primary move-right margin-right-5">Upload CSV</a>

								<a href="#exportSubs" data-toggle="modal" class="btn btn-primary move-right margin-right-5">Export Subscribers</a>

							</div>

							<form action="server.php" data-parsley-validate enctype="multipart/form-data" method="post">

							<div class="form-group">

								<label>First Name</label>

								<input type="text" name="first_name" placeholder="Enter first name..." class="form-control" value="<?php echo DBout($row['first_name'])?>" required>

							</div>

							<!--<span>or</span>-->

							<div class="form-group">

								<label>First Name Initial</label>

								<input type="text" name="first_initial" placeholder="Enter first name initial..." class="form-control" value="<?php echo DBout($row['first_initial'])?>" >

							</div>

							<div class="form-group">

								<label>Last Name</label>

								<input type="text" name="last_name" placeholder="Enter last name..." class="form-control" value="<?php echo DBout($row['last_name'])?>" required>

							</div>

							<!--<span>or</span>-->

							<div class="form-group">

								<label>Last Name Initial</label>

								<input type="text" name="last_initial" placeholder="Enter last name initial..." class="form-control" value="<?php echo DBout($row['last_initial'])?>">

							</div>

							<div class="form-group">

								<label>Home Address</label>

								<input type="text" name="home_address" placeholder="Enter home address..." class="form-control" value="<?php echo DBout($row['address'])?>" required>

							</div>

							<div class="form-group">

								<label>Post code</label>

								<input type="text" name="post_code" placeholder="Enter post code..." class="form-control" value="<?php echo DBout($row['post_code'])?>" required>

							</div>

							<div class="form-group">

								<label>Account number</label>

								<input type="text" name="account_number" placeholder="Enter account number..." class="form-control" value="<?php echo DBout($row['account_number'])?>" required>

							</div>

							<div class="form-group">

								<label>Partial payment</label>

								<input type="checkbox" <?= (!isset($_GET['id'])) ? 'checked' : '' ?> name="partial_payment" onchange="if(this.checked){$('#ppa').show()}else{$('#ppa').hide()}"  class="form-check"  <?= (DBout($row['partial_payment']) == 1) ? 'checked' : '' ?> >

							</div>

							<div class="form-group " id="ppa" <?= (DBout($row['partial_payment']) == 1 || !isset($_GET['id'])) ? '' : 'style="display: none;"' ?>>

								<label>Min payment Amount</label>

								<input type="number" name="partial_payment_amount"   placeholder="Enter min amount to be paid..." class="form-control" value="<?php echo DBout($row['partial_payment_amount'])?>" >

							</div>

							<div class="form-group">

								<label>Amount Owing</label>

								<input type="text" name="amount_to_be_paid" placeholder="Enter amount to be paid..." class="form-control" value="<?php echo DBout($row['amount_to_be_paid'])?>" required>

							</div>

							<div class="form-group">

								<label>Services Rendered</label>

								<input type="text" name="service_render" placeholder="Enter Services Redered..." class="form-control" value="<?php echo DBout($row['service_render'])?>">

							</div>

							<div class="form-group">

								<label>Phone Number</label>

								<input type="text" name="phone_number" placeholder="Enter phone number..." class="form-control phoneOnly" value="<?php echo DBout($row['phone_number'])?>" required maxlength="13">

							</div>

                            <div class="form-group">

								<label>Email Address</label>

								<input type="email" name="email" placeholder="Enter Email Address..." class="form-control" value="<?php echo DBout($row['email'])?>">

							</div>

							<div class="form-group">

								<label>Business Name</label>

								<input type="business_name" name="business_name" placeholder="Enter Business Name..." class="form-control" value="<?php echo DBout($row['business_name'])?>">

							</div>

							<div class="form-group">

								<label>Group (Campaign)</label>

								<select name="group_id" class="form-control" parsley-trigger="change" required>

								<?php

									$sqlg = sprintf("select id, title from campaigns where user_id=%s ",

                                            mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

                                        );

									$resg = mysqli_query($link,$sqlg);

									if(mysqli_num_rows($resg)){

										while($rowg = mysqli_fetch_assoc($resg)){

											if($rowg['id']==$groupID)

												$sele = DBout('selected="selected"');

											else

												$sele = DBout(''); ?>



											<option <?php echo DBout($sele)?> value="<?php echo DBout($rowg['id'])?>"><?php echo DBout($rowg['title']) ?></option>

								<?php		}

									}

								?>	

								</select>

							</div>

							<div class="form-group">

								<label>City</label>

								<input type="text" name="city" placeholder="Enter city..." class="form-control" value="<?php echo DBout($row['city'])?>">

							</div>

							<div class="form-group">

								<label>Province</label>

								<input type="text" name="state" placeholder="Enter state ..." class="form-control" value="<?php echo DBout($row['state'])?>">

							</div>

							<div class="form-group">

		<label>Bill No</label>

		<input type="text" name="Bill_No" placeholder="Enter Bill No..." class="form-control" value="<?php echo DBout($row['Bill_No'])?>">

		<label>Invoice No</label>

		<input type="text" name="Invoice_No" placeholder="Enter Invoice No..." class="form-control" value="<?php echo DBout($row['Invoice_No'])?>">

		<label>Last Payment Date</label>

		<input type="Date" <?= (isset($_GET['id'])) ? 'readonly' : '' ?> name="Last_Payment_Date" placeholder="Enter Last Payment Date..." class="form-control" value="<?php echo DBout($row['Last_Payment_Date'])?>">

		<label>Last Paid Amount</label>

		<input type="text" <?= (isset($_GET['id'])) ? 'readonly' : '' ?> name="Last_Paid_Amount" placeholder="Enter Last Paid Amount..." class="form-control" value="<?php echo DBout($row['Last_Paid_Amount'])?>">

		<label>Outstanding Balance</label>

		<input type="text" <?= (isset($_GET['id'])) ? 'readonly' : '' ?>  name="Outstanding_Balance" placeholder="Enter Outstanding Balance..." class="form-control" value="<?php echo DBout($row['Outstanding_Balance'])?>">

	</div>	

<!--<div class="form field_wrapper">-->

<!--    	<input type="text"  class="form-control" name="field_name[]" value="" />-->

<!--        <a href="javascript:void(0);" class="add_button" title="Add field"><i class="fa fa-plus-circle" aria-hidden="true"><span>AddMoreField</span></i></a>-->

<!--</div>-->

	<br>

	<div class="form-group text-right m-b-0">

	<button class="btn btn-primary waves-effect waves-light" type="submit"> <?php echo DBout($buttonText)?> </button>

								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>

								<input type="hidden" name="cmd" value="<?php echo DBout($cmd)?>" />

								<input type="hidden" name="subscriber_id" value="<?php echo DBout($row['id'])?>" />

								<input type="hidden" name="assignment_id" value="<?php echo DBout($assignID)?>" />

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

			<label class="move-left">Company</label>

			<select onchange="CompanySelected(this)" class="form-control">

			<?php

			$sql2 = sprintf("select campaigns.id, campaigns.title, companies.name from campaigns
			LEFT JOIN companies on companies.id = campaigns.company_id",

						mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

				);

				$lists = mysqli_query($link,$sql2);
				$companies=[];
				if(mysqli_num_rows($lists)){
					$l = [];
					
					while($list = mysqli_fetch_assoc($lists)){
						$companies[] = $list;
						if (!in_array($list['name'],$l)){
							$l[] = $list['name'];
							
						?>

				<option value="<?php echo DBout($list['name'])?>"><?php echo DBout($list['name'])?></option>

				<?php	} }

				}

				else{?>

				<option value="">No campaign found.</option>

				<?php

				}

			?>

			</select>

		</div>

			<div class="form-group">

				<label class="move-rightt">Select Campaign</label>

				<select name="export_campaign_id" class="form-control">

					<option value="all">ALL Subscribers</option>

				<?php

                $sql1 = sprintf("select campaigns.id, campaigns.title, companies.name from campaigns
				LEFT JOIN companies on companies.id = campaigns.company_id",

                        mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

                    );

					$lists = mysqli_query($link,$sql1);

					if(mysqli_num_rows($lists)){

						while($list = mysqli_fetch_assoc($lists)){
							if ($list['name'] == $companies[0]['name']){
							?>

                    <option value="<?php echo DBout($list['id'])?>"><?php echo DBout($list['title'])?></option>

					<?php } }

					}

					else{

						?>

                    <option value="">No campaign found.</option>';

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

        <h6 class="custom-modal-title">Import Customers</h6>

      </div>

      <div class="modal-body">

        <form method="post" enctype="multipart/form-data" action="server.php">

		<div class="form-group">

			<label class="move-left">Company</label>

			<select onchange="CompanySelected(this)" class="form-control">

			<?php

			$sql2 = sprintf("select campaigns.id, campaigns.title, companies.name from campaigns
			LEFT JOIN companies on companies.id = campaigns.company_id",

						mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

				);

				$lists = mysqli_query($link,$sql2);
				$companies=[];
				if(mysqli_num_rows($lists)){
					$l = [];
					
					while($list = mysqli_fetch_assoc($lists)){
						$companies[] = $list;
						if (!in_array($list['name'],$l)){
							$l[] = $list['name'];
							
						?>

				<option value="<?php echo DBout($list['name'])?>"><?php echo DBout($list['name'])?></option>

				<?php	} }

				}

				else{?>

				<option value="">No campaign found.</option>

				<?php

				}

			?>

			</select>

		</div>
			

			<div class="form-group">

				<label class="move-left">Select Campaign</label>

				<select name="imported_campaign_id" class="form-control">

				<?php

                $sql2 = sprintf("select campaigns.id, campaigns.title, companies.name from campaigns
				LEFT JOIN companies on companies.id = campaigns.company_id",

                            mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

                    );

					$lists = mysqli_query($link,$sql2);

					if(mysqli_num_rows($lists)){

						while($list = mysqli_fetch_assoc($lists)){
							if ($list['name'] == $companies[0]['name']){
							?>

                    <option value="<?php echo DBout($list['id'])?>"><?php echo DBout($list['title'])?></option>

					<?php	} }

					}

					else{?>

                    <option value="">No campaign found.</option>

                    <?php

					}

				?>

				</select>

			</div>

			<div class="form-group">

				<label class="move-left">Select CSV file </label>

				<input type="file" name="imported_csv" accept=".csv" class="display-inline" required/><br>

				<span class="red">Note: Add phone numbers without country code.<br>Please check csv format in sample file before upload.</span>

				

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

<script>
var companies = <?= json_encode($companies) ?>;
function CompanySelected(company){
	var html='';
	companies.forEach(function(a){ 
		if(a.name == company.value && !html.includes(a.title)) {
			html += '<option value="'+a.title+'" >'+a.title+'</option>';
		}
	 })
	 $(company).parent().next().find('select').html(html) 
}
</script>

<style>
    /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
    </style>

<script type="text/javascript">

$(document).ready(function(){

    var maxField = 10; //Input fields increment limitation

    var addButton = $('.add_button'); //Add button selector

    var wrapper = $('.field_wrapper'); //Input field wrapper

    var fieldHTML = '<div><input type="text" class="form-control" name="field_name[]" value=""/><a href="javascript:void(0);" class="remove_button"><i class="fa fa-minus-circle" aria-hidden="true">RemoveField</a></i></div>'; //New input field html 

    var x = 1; //Initial field counter is 1

    

    //Once add button is clicked

    $(addButton).click(function(){

        //Check maximum number of input fields

        if(x < maxField){ 

            x++; //Increment field counter

            $(wrapper).append(fieldHTML); //Add field html

        }

    });

    

    //Once remove button is clicked

    $(wrapper).on('click', '.remove_button', function(e){

        e.preventDefault();

        $(this).parent('div').remove(); //Remove field html

        x--; //Decrement field counter

    });

});

</script>