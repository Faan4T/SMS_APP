<?php
include_once('functions.php');
error_reporting(E_ALL);

define("APPROVED", 1);
define("DECLINED", 2);
define("ERROR", 3);

class gwapi {

// Initial Setting Functions

	function setLogin($security_key) {
		$this->login['security_key'] = $security_key;
	}

	function setOrder($orderid,
		$orderdescription,
		$tax,
		$shipping,
		$ponumber,
		$ipaddress) {
		$this->order['orderid']          = $orderid;
		$this->order['orderdescription'] = $orderdescription;
		$this->order['tax']              = $tax;
		$this->order['shipping']         = $shipping;
		$this->order['ponumber']         = $ponumber;
		$this->order['ipaddress']        = $ipaddress;
	}

	function setBilling($firstname,
		$lastname,
		$company,
		$address1,
		$address2,
		$city,
		$state,
		$zip,
		$country,
		$email) {
		$this->billing['firstname'] = $firstname;
		$this->billing['lastname']  = $lastname;
		$this->billing['company']   = $company;
		$this->billing['address1']  = $address1;
		$this->billing['address2']  = $address2;
		$this->billing['city']      = $city;
		$this->billing['state']     = $state;
		$this->billing['zip']       = $zip;
		$this->billing['country']   = $country;
		$this->billing['email']     = $email;
	}

	function setShipping($firstname,
		$lastname,
		$company,
		$address1,
		$address2,
		$city,
		$state,
		$zip,
		$country,
		$email) {
		/*$this->shipping['firstname'] = $firstname;
		$this->shipping['lastname']  = $lastname;
		$this->shipping['company']   = $company;
		$this->shipping['address1']  = $address1;
		$this->shipping['address2']  = $address2;
		$this->shipping['city']      = $city;
		$this->shipping['state']     = $state;
		$this->shipping['zip']       = $zip;
		$this->shipping['country']   = $country;
		$this->shipping['email']     = $email;*/
	}

	// Transaction Functions

	function doSale($amount, $ccnumber, $ccexp, $cvv="") {

		$query  = "";
		// Login Information
		$query .= "security_key=" . urlencode($this->login['security_key']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		$query .= "cvv=" . urlencode($cvv) . "&";

		// Order Information
		/*	$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
			$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
			$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
			$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
			$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
			$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";

			*/

		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		$query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "type=sale";
		return $this->_doPost($query);
	}


	function doAuth($amount, $ccnumber, $ccexp, $cvv="") {

		$query  = "";
		// Login Information
		$query .= "security_key=" . urlencode($this->login['security_key']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		$query .= "cvv=" . urlencode($cvv) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		$query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "type=auth";
		return $this->_doPost($query);
	}

	function doCredit($amount, $ccnumber, $ccexp) {

		$query  = "";
		// Login Information
		$query .= "security_key=" . urlencode($this->login['security_key']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		$query .= "type=credit";
		return $this->_doPost($query);
	}

	function doOffline($authorizationcode, $amount, $ccnumber, $ccexp) {

		$query  = "";
		// Login Information
		$query .= "security_key=" . urlencode($this->login['security_key']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		$query .= "authorizationcode=" . urlencode($authorizationcode) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		$query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "type=offline";
		return $this->_doPost($query);
	}

	function doCapture($transactionid, $amount =0) {

		$query  = "";
		// Login Information
		$query .= "security_key=" . urlencode($this->login['security_key']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		if ($amount>0) {
			$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		}
		$query .= "type=capture";
		return $this->_doPost($query);
	}

	function doVoid($transactionid) {

		$query  = "";
		// Login Information
		$query .= "security_key=" . urlencode($this->login['security_key']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		$query .= "type=void";
		return $this->_doPost($query);
	}

	function doRefund($transactionid, $amount = 0) {

		$query  = "";
		// Login Information
		$query .= "security_key=" . urlencode($this->login['security_key']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		if ($amount>0) {
			$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		}
		$query .= "type=refund";
		return $this->_doPost($query);
	}

	function _doPost($query) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://secure.networkmerchants.com/api/transact.php");
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		curl_setopt($ch, CURLOPT_POST, 1);

		if (!($data = curl_exec($ch))) {
			return ERROR;
		}
		curl_close($ch);
		unset($ch);
		print "\n$data\n";
		$data = explode("&",$data);
		for($i=0;$i<count($data);$i++) {
			$rdata = explode("=",$data[$i]);
			$this->responses[$rdata[0]] = $rdata[1];
		}
		return $this->responses['response'];
	}
	function addPlan($recurring,$plan_payments,$plan_amount,$plan_name,$plan_id,$month_frequency,$day_of_month){
		$query='';
		$query .= "security_key=" . urlencode($this->login['security_key']) . "&";
		$query.="recurring=".$recurring."&";
		$query.="plan_payments=".$plan_payments."&";
		$query.="plan_amount=".$plan_amount."&";
		$query.="plan_name=".$plan_name."&";
		$query.="plan_id=".$plan_id."&";
		$query.="month_frequency=".$month_frequency."&";
		$query.="day_of_month=".$day_of_month;
		return $this->_doPost($query);
	}
	function  addSubscription($recurring,$plan_id){
		$query='';
		$query.='recurring='.$recurring.'&';
		$query.='plan_id='.$plan_id.'&';
		$query.='start_date='.$start_date.'&';
//		payment_token
		$query='ccnumber='.$ccnumber.'&';
		$query='ccexp='.$ccexp.'&';
		$query='currency='.$currency.'&';
		$query='first_name='.$first_name.'&';
		$query='last_name='.$last_name.'&';
		$query='address1='.$address1.'&';
		$query='city='.$city.'&';
		$query='state='.$state.'&';
		$query='zip='.$zip.'&';
		$query='email='.$email.'&';
		$query='customer_receipt='.$customer_receipt.'&';
		$query='order_description='.$order_description.'&';
		return $this->_doPost($query);
	}
}


$security_key = $appSettings['nmi_security_key'];

$sel = sprintf("select * from web_user_info where id='%s'",
	mysqli_real_escape_string($link,DBin($webUserID))
);
$res = mysqli_query($link,$sel);

$row = mysqli_fetch_assoc($res);
$year = $row['year'];
$month = $row['month'];
$card_date = $month."-".$year;

$day_of_month = date('d');


$gw = new gwapi;
$gw->setLogin($security_key);

$gw->addPlan('add_plan','0',$pkgPrice,$pkgName,'plan_98687638125','1',$day_of_month);

$plan_id = 'plan_98687638125';
// 

$gw->addSubscription();


/*
$gw->setBilling($row['first_name'],$row['last_name'],"Acme, Inc.", $row['address'], $row['address'], $row['city'],
	$row['state'],$row['zip'],"US",$row['email']);*/
/*$gw->setShipping("Mary","Smith","na","124 Shipping Main St","Suite Ship", "Beverly Hills",
	"CA","90210","US","support@example.com");
$gw->setOrder("1234","Big Order",1, 2, "PO1234","65.192.14.10");*/
/*
$r = $gw->doSale(str_replace('$','',$pkgPrice),$row['card_number'],$card_date);*/
echo "<pre>";
print_r($gw);
