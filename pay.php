<!DOCTYPE html>
<?php
    include_once('header.php');
    //include_once('navbar.php');
    $baseUrl = 'https://textingpays.net/admin/';
    $id = $_GET['id'];
    $baseUrl = 'https://staging.collectbytext.ca/getcusdata.php?id='.$id;
    // $companyID = $_REQUEST['cmny_id'];
    // $clientID  = $_REQUEST['clnt_id'];
    // $subsID = $_REQUEST['sid'];
    // $accountNumber = $_REQUEST['clnt_acc_id'];
    // $ammount = $_REQUEST['amnt'];

    // $url  = $baseUrl.'server.php';
    // $cData = array(
    //     'cmd' => 'get_site_data',
    //     'campaign_id' => $clientID,
    //     'compnay_id' => $companyID,
    //     'subscriber_id' => $subsID
    // );
    $data = getData($baseUrl,[]);
    $data = json_decode($data,true);
    // print_r($data);


    if (isset($_POST['pay_api'])){
        print_r($_POST);

        exit();
    }

    $has_remaining_balance = false;
    $total_paid=0;

    if (is_array($data['payments'])){
        foreach($data['payments'] as $key){
            // print_r($key);
            $total_paid += $key['amount'];
        }
        $data['Last_Paid_Amount'] = $total_paid;

        if ($total_paid != 0 && $total_paid < $data['amount_to_be_paid']){
            $has_remaining_balance=$data['amount_to_be_paid'] - $total_paid;
        }

        if ($has_remaining_balance){
            $data['amount_to_be_paid'] = $has_remaining_balance;
            $data['Last_Paid_Amount'] = $total_paid;
            $data['Outstanding_Balance'] = $has_remaining_balance;
        }
    }

    // echo $total_paid;


?>
<style>
#profile .form-group {
    margin-bottom: 10px;
    border: 1px solid #e6e6e6;
    padding: 10px 20px;
    border-radius: 6px;
}

.label-left {
    float: left;
    font-weight: 500;
}

.label-right {
    float: right;
    font-size: 11.5px;
    font-weight: 500;
}

.form-group {
    float: left;
    width: 100%;
}

.selfbutton {
    font-size: 11.9px !important;
    width:125px!important;
    padding: 7px !important;
    font-weight: 600!important;
    border-radius: 5px!important;
    text-align: center!important;
}


.selfstyle{
    margin-left: 2px;
    width:280px
}

.selfstyle>label {
    margin: 4px;
    font-size: 13px;

}

.selfstyle>input {

    font-size: 13px;
    font-weight: 600;
    margin-bottom: 10px;
    border: 1px solid #e6e6e6;
    padding: 19px 20px;
    border-radius: 6px;
}

@media screen and (max-width:768px) {
    .form-group>.label-right {
        float: left !important;
    }

    .form-group>label {
        display: block !important
    }

    .custom-group {
        float: left !important;
        width: 100%;
    }

    .custom-group2 {
        float: none;
        width: 100%;
    }
}
</style>
<script src="https://secure.nmi.com/token/Collect.js" data-tokenization-key="<?= $data['merchant_token'] ?>"></script>
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<header id="header" class="fixed-top">
    <div class="container">

        <div class="logo float-left">
            <!-- Uncomment below if you prefer to use an text logo -->
            <h1 class="text-light"><a href="#header"><span>AccessPays</span></a></h1>
            <!--<a href="#intro" class="scrollto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
        </div>

        <nav class="main-nav float-right d-none d-lg-block">
            <ul>
                <li class="active"><a href="#intro">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#contactus">Contact Us</a></li>
            </ul>
        </nav><!-- .main-nav -->

    </div>
</header><!-- #header -->

<section id="intro" class="clearfix">
    <div class="container">

        <div class="intro-img">
            <!--<img src="assets/img/intro-img.svg" alt="" class="img-fluid">-->
        </div>

        <div class="intro-info" style="width: 100%; text-align: center">
            <h2>Please make Secure payment here.</h2>
            <!--
            <div>
                <a href="#about" class="btn-get-started scrollto">Get Started</a>
                <a href="#contactus" class="btn-services scrollto">Contact Us</a>
            </div>
            -->
        </div>

    </div>
</section>

<main id="main">
    <section id="about">
        <div class="container">

            <header class="section-header">
                <h2 style="text-align: center"><?php
                echo $data['description']; ?></h2>
                <div class="featured-wrap">
                    <?php
                    if (!isset($data['id'])){
                        ?>
                    <div id="profile" class="col-md-8 col-md-offset-2"
                        style="background-color: white;padding: 40px;border-radius: 5px;">
                        <div style="margin-top:15px;" class="alert alert-danger"><strong>Error! </strong>No data found.
                        </div>
                    </div>
                    <?php
                        // $data['payment_status']=='0' ||
                            } elseif($has_remaining_balance > 0){
                    ?>



                    <div id="profile" class="col-md-8 col-md-offset-2"
                        style="background-color: white;padding: 40px;border-radius: 5px;">
                        <!--<h2 style="text-align: center; margin-bottom: 50px;"><span style="border-bottom: 2px solid #eee">Here is your details</span></h2>-->
                        <div class="col-md-12">
                            <div class="col-md-6">

                                <div class="form-group custom-group" >
                                    <label for="">Name: </label> <span
                                        class="label-right"><?php echo $data['first_name'].' '.$data['last_name']; ?></span>
                                </div>
                                <div class="form-group custom-group">
                                    <label for="">First Initial/Last Initial: </label> <span
                                        class="label-right"><?php echo $data['first_initial'].' '.$data['last_initial']; ?></span>
                                </div>
                                <div class="form-group custom-group">
                                    <label for="">Phone: </label> <span
                                        class="label-right"><?php echo $data['phone_number'] ?></span>
                                </div>
                                <div class="form-group custom-group">
                                    <label for="">Email: </label> <span
                                        class="label-right"><?php echo $data['email'] ?></span>
                                </div>
                                <div class="form-group custom-group">
                                    <label>City: </label> <span class="label-right"><?php echo $data['city'] ?></span>
                                </div>
                                <div class="form-group custom-group">
                                    <label for="">Province: </label> <span
                                        class="label-right"><?php echo $data['state'] ?></span>
                                </div>
                                <div class="form-group custom-group">
                                    <label for="">Post Code: </label> <span
                                        class="label-right"><?php echo $data['post_code'] ?></span>
                                </div>
                                <div class="form-group custom-group">
                                    <label for="">Address: </label><span
                                        class="label-right"><?php echo $data['address'] ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group custom-group">
                                    <label for="">Amount to be Paid: </label> <span class="label-right">$
                                        <?php echo $data['amount_to_be_paid'] ?></span>
                                </div>
                                <div class="form-group custom-group">
                                    <label for="">Account Number: </label> <span
                                        class="label-right"><?php echo $data['account_number'] ?></span>
                                </div>
                                <div class="form-group custom-group">
                                    <label for="">Service: </label> <span
                                        class="label-right"><?php echo $data['service_render'] ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="">Bill-No: </label> <span
                                        class="label-right"><?php echo $data['Bill_No'] ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="">Last Payment Date </label> <span
                                        class="label-right"><?php echo $data['Last_Payment_Date'] ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="">Last Paid Amount </label> <span
                                        class="label-right"><?php echo $data['Last_Paid_Amount'] ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="">Outstanding Balance </label> <span
                                        class="label-right"><?php echo $data['Outstanding_Balance'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="border: 0px; text-align: center; margin-top:15px;">
                                <div class="custom-control custom-switch" >
                                    <input type="checkbox" name="pay_by_self" class="custom-control-input "
                                        onchange="if(this.checked){ $('.pay_by_self').show(); $('input[name=pay_by_self]').val('on') } else { $('.pay_by_self').hide(); $('input[name=pay_by_self]').val('0') }"
                                        id="pay_by_self">
                                    <label class="custom-control-label" for="pay_by_self">Pay By Other</label>
                                </div>
                            </div>
                                <div class="col-md-12 pay_by_self selfstyle" style="display: none;">
                                    <div class="col-md-6" >
                                        <div class="selfstyle   ">
                                            <label for="">First Name</label>
                                            <input type="text"
                                                onchange="$('input[name=pay_by_self_first_name]').val(this.value)"
                                                class="form-control">
                                        </div>
                                        <div class="selfstyle">
                                            <label for="">Last Name</label>
                                            <input type="text"
                                                onchange="$('input[name=pay_by_self_last_name]').val(this.value)"
                                                class="form-control">
                                        </div>
                                        <div class="selfstyle">
                                            <label for="">Email</label>
                                            <input type="text"
                                                onchange="$('input[name=pay_by_self_email]').val(this.value)"
                                                class="form-control">
                                        </div>
                                        <div class="selfstyle">
                                            <label for="">Address</label>
                                            <input type="text"
                                                onchange="$('input[name=pay_by_self_address]').val(this.value)"
                                                class="form-control">
                                        </div>
                                        <div class="selfstyle">
                                            <label for="">City</label>
                                            <input type="text"
                                                onchange="$('input[name=pay_by_self_city]').val(this.value)"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class=" col-md-6" style="padding-left:10px!important;">
                                        <div class="selfstyle">
                                            <label for="">State</label>
                                            <input type="text"
                                                onchange="$('input[name=pay_by_self_state]').val(this.value)"
                                                class="form-control">
                                        </div>
                                        <div class="selfstyle">
                                            <label for="">Zip</label>
                                            <input type="text"
                                                onchange="$('input[name=pay_by_self_zip]').val(this.value)"
                                                class="form-control">
                                        </div>
                                        <div class="selfstyle">
                                            <label for="">Country</label>
                                            <input type="text"
                                                onchange="$('input[name=pay_by_self_country]').val(this.value)"
                                                class="form-control">
                                        </div>
                                        <div class="selfstyle">
                                            <label for="">Phone</label>
                                            <input type="text"
                                                onchange="$('input[name=pay_by_self_phone]').val(this.value)"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>



                            <div class="col-md-12" style="border: 0px; text-align: center">
                                <input type="button" value="Pay Now <?= $data['amount_to_be_paid'] ?>"
                                    class="btn btn-primary selfbutton" onClick="showCardSection(this)"
                                    style="margin-top: 20px;width: 100px;">

                                <?php
                                    if($data['partial_payment'] == 1 && $data['partial_payment_amount'] < $data['amount_to_be_paid'] ){ ?>
                                <input type="button" value="Pay Partial Payment" class="btn btn-success selfbutton"
                                    onClick="$(this).prev().remove(); $(this).remove(); $('#PartialPaymentDiv').show()"
                                    style="margin-top: 20px;">
                                <?php } ?>

                            </div>

                            <?php
                                    if($data['partial_payment'] == 1 && $data['partial_payment_amount'] < $data['amount_to_be_paid'] ){ ?>
                            <!-- <div class="col-md-12" style="text-align: center;" > <h1>OR</h1></div> -->
                            <div id="PartialPaymentDiv" class="col-md-12"
                                style="border: 0px;border: 1px solid #d8d8d8;padding: 5px;margin-top: 19px;border-radius: 10px;display: none;">
                                <label>Partial Payment Amount</label>
                                <input type="number" onchange="$('input[name=partial_payment_amount]').val(this.value);"
                                    placeholder="Enter Amount Between <?=  $data['partial_payment_amount'] ?> - <?= $data['amount_to_be_paid'] ?>"
                                    class="form-control form-input">
                                <small>Pay Partial amount of the total debt amount.</small>
                                <input type="button" style="float: right;margin-top: 30px;" value="Pay Partial Payment"
                                    class="btn btn-success"
                                    onClick="$('input[name=partial_payment]').val(1); showCardSection(this)">
                            </div>
                            <?php }  ?>



                            <div class="col-md-12" style="border: 0px; text-align: center">
                                <div id="loader" class="spinner-border" role="status" style="display: none;">
                                    <span class="sr-only">Loading...</span>
                                </div>

                                <div id="showLoading" style="display: none">Authenticating...</div>
                            </div>


                        </div>






                    </div>

_


                    <div id="creditCardInfo" class="col-md-8 col-md-offset-2"
                        style="background-color: white;padding: 40px;border-radius: 5px;display: none">
                        <h2 style="text-align: center; margin-bottom: 50px;"><span
                                style="border-bottom: 2px solid #eee">Enter credit card information.</span></h2>
                        <div class="col-md-12">
                            <form id="paymentForm" method="post">
                                <input type="hidden" name="pay_api" />
                                <input type="hidden" name="token" id="token" />
                                <input type="hidden" name="client" value="<?= $id ?>" />
                                <input type="hidden" name="partial_payment" value="0" />
                                <input type="hidden" name="partial_payment_amount" value="0" />

                                <input type="hidden" name="pay_by_self" value="0" />
                                <input type="hidden" name="pay_by_self_first_name" value="" />
                                <input type="hidden" name="pay_by_self_last_name" value="" />
                                <input type="hidden" name="pay_by_self_email" value="" />
                                <input type="hidden" name="pay_by_self_address" value="" />
                                <input type="hidden" name="pay_by_self_city" value="" />
                                <input type="hidden" name="pay_by_self_state" value="" />
                                <input type="hidden" name="pay_by_self_zip" value="" />
                                <input type="hidden" name="pay_by_self_country" value="" />
                                <input type="hidden" name="pay_by_self_phone" value="" />




                                <div class="form-group custom-group2">
                                    <label for="">Name on card</label>
                                    <input type="text" name="name_of_credit_card" class="form-control"
                                        value="John More Doe">
                                </div>
                                <div class="form-group custom-group2">
                                    <label for="">Credit card number</label>
                                    <input type="text" name="credit_card_number" class="form-control"
                                        value="4111111111111111">
                                </div>
                                <div class="form-group custom-group2">
                                    <label for="">Expiry month</label>
                                    <input type="text" name="expiry_month" class="form-control" value="10">
                                </div>
                                <div class="form-group col-md-6 col-sm-12 custom-group2" style="padding: 0px;">
                                    <label for="">Expiry year</label>
                                    <input type="text" name="expiry_year" class="form-control" value="2020">
                                </div>
                                <div class="form-group col-md-6 col-sm-12 custom-group2" style="padding: 0px;">
                                    <label for="">CVV</label>
                                    <input type="text" name="cvv" class="form-control" value="123">
                                </div>

                                <div class="col-md-12 checkTermsCondition"
                                    style="border: 0px; text-align: center;padding: 0px;">
                                    <div class="alert alert-info"><input type="checkbox" name="term_and_conditions"
                                            value="1"> Do you agree with the terms and conditions? Please accept to
                                        proceed further.<br>
                                        Read <a href="term_conditions.php" target="_blank">Pricay policy</a></div>
                                </div>

                                <div class="col-md-12 proceedPayment" style="border: 0px; text-align: center;">

                                    <input type="hidden" name="subs_id" value="<?php echo $data['id'];?>">
                                    <input type="hidden" name="amount"
                                        value="<?php echo $data['amount_to_be_paid']; ?>">
                                    <input type="submit" id="paymentbutton" class="btn btn-success"
                                        value="$ <?php echo $data['amount_to_be_paid'] ?> Pay now"
                                        style="margin-top:30px;">
                                    <!-- <div id="showLoading" style="display: none">Authenticating...</div> -->

                                </div>
                            </form>
                        </div>
                    </div>

                    <?php
                        }
                        else{
                    ?>
                    <div id="profile" class="col-md-8 col-md-offset-2"
                        style="background-color: white;padding: 40px;border-radius: 5px;">
                        <div style="margin-top:15px;" class="alert alert-success"><strong>Congrates! </strong>No dues
                            pending.</div>
                    </div>
                    <?php
                        }
                    ?>
                </div>
            </header>
        </div>
    </section>
</main>
<?php
    include_once('footer.php');
    function getData($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPGET, $data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20110814 Firefox/6.0');
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>

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
<script>
$(document).ready(function() {
    $("input[name=term_and_conditions]").on("change", function() {
        if ($(this).is(":checked") == true) {
            $(".proceedPayment").show("slow");
        } else {
            $(".proceedPayment").hide("slow");
        }
    });
});
$('#paymentForm').on('submit', (function(e) {
    if (confirm("Are you sure you want to pay $<?php echo $data['amount_to_be_paid'] ?> now?")) {
        $('#paymentbutton').prop("disabled", true);
        $('#showLoading').html('Authenticating...');
        $('#showLoading').show();
        //$('#submitButton').prop("disabled",true);
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '/admin/server.php?cmd=make_nmi_payment',
            // url: '',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(res) {
                if (res == '1') {
                    $('#showLoading').html(
                        '<div style="margin-top:15px;" class="alert alert-success"><strong>Success! </strong>your payment of $<?php echo $data['amount_to_be_paid'] ?> was successful.</div>'
                    );
                } else {
                    $('#showLoading').html(
                        '<div style="margin-top:15px;" class="alert alert-danger"><strong>Failed! </strong>' +
                        res + '.</div>');
                }
                $('#paymentbutton').prop("disabled", false);
            },
            error: function(data) {
                $('#paymentbutton').prop("disabled", false);
                $('#showLoading').html(
                    '<div style="margin-top:15px;" class="alert alert-danger"><strong>Failed! </strong>Invalid credit card number or insufficient balance.</div>'
                );
            }
        });
        return false;
    }
}));
// function showCardSection(obj){
//     $('#profile').slideUp('slow');
//     $('#creditCardInfo').show('slow');
// }

function showCardSection(obj) {
    if ($('input[name=pay_by_self]')[0].checked) {
        if ($('input[name=pay_by_self_first_name]').val() == '') {
            alert('Please Enter First Name');
            return false;
        }
        if ($('input[name=pay_by_self_last_name]').val() == '') {
            alert('Please Enter Last Name');
            return false;
        }
        if ($('input[name=pay_by_self_email]').val() == '') {
            alert('Please Enter Email');
            return false;
        }
        if ($('input[name=pay_by_self_address]').val() == '') {
            alert('Please Enter Address');
            return false;
        }
        if ($('input[name=pay_by_self_city]').val() == '') {
            alert('Please Enter City');
            return false;
        }
        if ($('input[name=pay_by_self_state]').val() == '') {
            alert('Please Enter State');
            return false;
        }
        if ($('input[name=pay_by_self_zip]').val() == '') {
            alert('Please Enter Zip');
            return false;
        }
        if ($('input[name=pay_by_self_country]').val() == '') {
            alert('Please Enter Country');
            return false;
        }
        if ($('input[name=pay_by_self_phone]').val() == '') {
            alert('Please Enter Phone');
            return false;
        }
    }




    CollectJS.startPaymentRequest()
}

function ProcessPayment() {
    $('#paymentbutton').prop("disabled", true);
    $('#showLoading').html('Authenticating...');
    $('#showLoading').show();
    $('#loader').show();
    //$('#submitButton').prop("disabled",true);
    // e.preventDefault();
    var formData = new FormData($('#paymentForm')[0]);
    $.ajax({
        type: 'POST',
        url: 'https://staging.collectbytext.ca//server.php?cmd=make_nmi_payment',
        // url: './',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(res) {
            if (res.includes('payment_success')) {
                $('#showLoading').html(
                    '<div style="margin-top:15px;" class="alert alert-success"><strong>Success! </strong>your payment is successful.</div>'
                );
                setTimeout(() => {
                    window.location.reload()
                }, 10000);
            } else {
                $('#showLoading').html(
                    '<div style="margin-top:15px;" class="alert alert-danger"><strong>Failed! </strong>' +
                    res + '.</div>');
            }
            $('#paymentbutton').prop("disabled", false);
            $('#loader').hide();
        },
        error: function(data) {
            $('#paymentbutton').prop("disabled", false);
            $('#showLoading').html(
                '<div style="margin-top:15px;" class="alert alert-danger"><strong>Failed! </strong>Invalid credit card number or insufficient balance.</div>'
            );
            $('#loader').hide();
        }
    });
}
document.addEventListener('DOMContentLoaded', function() {
    CollectJS.configure({
        'paymentSelector': '#customPayButton',
        'theme': 'bootstrap',
        'primaryColor': '#ff288d',
        'secondaryColor': '#ffe200',
        'buttonText': 'Pay Now!',
        'paymentType': 'cc',
        'fields': {
            // 'cvv': {
            //     'display': 'hide'
            // },
            // 'googlePay': {
            //     'selector': '.googlePayButton',
            //     'shippingAddressRequired': true,
            //     'shippingAddressParameters': {
            //         'phoneNumberRequired': true,
            //         'allowedCountryCodes': ['US', 'CA']
            //     },
            //     'billingAddressRequired': true,
            //     'billingAddressParameters': {
            //         'phoneNumberRequired': true,
            //         'format': 'MIN'
            //     },
            //     'emailRequired': true,
            //     'buttonType': 'buy',
            //     'buttonColor': 'white',
            //     'buttonLocale': 'en'
            // },
            // 'applePay' : {
            //     'selector' : '.applePayButton',
            //     'shippingMethods': [
            //         {
            //             'label': 'Free Standard Shipping',
            //             'amount': '0.00',
            //             'detail': 'Arrives in 5-7 days',
            //             'identifier': 'standardShipping'
            //         },
            //         {
            //             'label': 'Express Shipping',
            //             'amount': '10.00',
            //             'detail': 'Arrives in 2-3 days',
            //             'identifier': 'expressShipping'
            //         }
            //     ],
            //     'shippingType': 'delivery',
            //     'requiredBillingContactFields': [
            //         'postalAddress',
            //         'name'
            //     ],
            //     'requiredShippingContactFields': [
            //         'postalAddress',
            //         'name'
            //     ],
            //     'contactFields': [
            //         'phone',
            //         'email'
            //     ],
            //     'contactFieldsMappedTo': 'shipping',
            //     'lineItems': [
            //         {
            //             'label': 'Foobar',
            //             'amount': '3.00'
            //         },
            //         {
            //             'label': 'Arbitrary Line Item #2',
            //             'amount': '1.00'
            //         }
            //     ],
            //     'totalLabel': 'foobar',
            //     'type': 'buy',
            //     'style': {
            //         'button-style': 'white-outline',
            //         'height': '50px',
            //         'border-radius': '0'
            //     }
            // }
        },
        "price": "1.00",
        "currency": "USD",
        "country": "US",
        'callback': function(response) {
            // alert(response.token);
            $('#token').val(response.token)
            ProcessPayment()
            // var input = document.createElement("input");
            // input.type = "hidden";
            // input.name = "payment_token";
            // input.value = response.token;
            // var form = document.getElementsByTagName("form")[0];
            // form.appendChild(input);
            // form.submit();
        }
    });
});
</script>