<?php
include_once("header.php");
include_once("left_menu.php");
$timeArray = getTimeArray();
$timeOptions = '';
foreach ($timeArray as $key => $value) {
    $timeOptions .= '<option value="' . DBout($key) . '">' . DBout($value) . '</option>';
}
$options = '';
for ($i = 1; $i <= 23; $i++) {
    if ($i > 1) {
        $hour = DBout('hours');
    } else {
        $hour = DBout('hour');
    }
    $options .= '<option value="+' . DBout($i) . ' ' . DBout($hour) . '">After ' . DBout($i) . ' ' . DBout(ucfirst($hour)) . '</option>';
}
?>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">  
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">   -->
  <link rel="stylesheet" type="text/css" href="css/multi-select.css">
  <script src="js/jquery.multi-select.js"></script>
</head>

<div class="main-panel">
    <?php include_once('navbar.php'); ?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h4 class="title"> Add Company
                                <input type="button" class="btn btn-primary move-right" value="Back" onclick="window.location='view_company.php'" />
                            </h4>
                            <p class="category">Add your company here.</p>
                        </div>
                        <div class="content table-responsive">
                            <form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
                                <div class="form-group">
                                    <label>Name *</label>
                                    <input type="text" name="company_name" class="form-control removeSpaces" required>
                                </div>
                                <div class="form-group">
                                    <label>Website URl *</label>
                                    <input type="text" name="website_url" class="form-control removeSpaces" required>
                                    <span style="color: red; font-size: 12px;">Ex: https://yourdomain.com/root_folder</span>
                                </div>             
                                    <div class="form-group">
                                    <label>Merchant Login *</label>
                                    <input type="text" name="merchant_login" class="form-control removeSpaces"required>
                                </div> 
                                    <div class="form-group">
                                    <label>Merchant Token *</label>
                                    <input type="text" name="merchant_token" class="form-control removeSpaces" required>
                                </div> 
                                
                            <div class="form-group phoneNumberSection">
                                    <label>Assign Number*</label> 
                                    <label style="margin-left: 13%;">Assigned Number*</label>
                                       
                                    </div>

                                    <?php
                                        $booked_numbers=[];
                                        if ($appSettings['sms_gateway'] == 'twilio') {
                                            
                                            $query = mysqli_query($link,"SELECT `Assign_numbers` FROM `companies`");
	                                        while($row = mysqli_fetch_assoc($query)){
                                                $data = explode(',',$row['Assign_numbers']);
                                                foreach($data as $d){
                                                    if ($d != ''){
                                                        $booked_numbers[] = $d;
                                                    }
                                                }
                                            }


                                            $sel = "select * from users_phone_numbers where user_id='" . $_SESSION['user_id'] . "' and ( type='1' or type='4' )";
                                        } else if ($appSettings['sms_gateway'] == 'plivo') {
                                            $sel = "select * from users_phone_numbers where user_id='" . $_SESSION['user_id'] . "' and type='2'";
                                        } else if ($appSettings['sms_gateway'] == 'nexmo') {
                                            $sel = "select * from users_phone_numbers where user_id='" . $_SESSION['user_id'] . "' and type='3'";
                                        } else {
                                            $sel = "select * from users_phone_numbers where user_id='" . $_SESSION['user_id'] . "'";
                                        }
                                        
                                        ?>


                                    <select id='pre-selected-options' multiple='multiple' name="Assign_number[]" >
                                      <?php
                                      $rec = mysqli_query($link, $sel);
                                        while ($numbers = mysqli_fetch_assoc(
                                            $rec
                                        )) {
                                            if (!in_array($numbers["phone_number"],$booked_numbers)){
                                        ?>
                                        <option value='<?php echo $numbers["phone_number"]; ?>' ><?php echo $numbers["phone_number"];
                                                // To show the category name to the user
                                                ?></option>
                                           
                                        <?php
                                        }} ?>


                                    </select>



                                <div class="form-group smsTextsSection"><label>Description *</label>
                                    <textarea name="description" required placeholder="Enter client description..." class="form-control"></textarea>
                                </div>

                                <div class="form-group text-right m-b-0">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit"> Save
                                    </button>
                                    <button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel
                                    </button>
                                    <input type="hidden" name="cmd" value="create_company" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once("footer_info.php"); ?>
</div>
<?php include_once("footer.php"); ?>

<script type="text/javascript">
    var timeoptions = '<?php echo $timeOptions ?>';
    var options = '<?php echo $options ?>';
    var maxlenght = '<?php echo DBout($maxLength); ?>';
</script>
<script src="scripts/add_campaign.js"></script>'

<!-- Initialize the plugin: -->
<script type="text/javascript">
    $(document).ready(function() {
        // $('#multiselect').multiselect();
        $('#pre-selected-options').multiSelect();
    });
</script>

