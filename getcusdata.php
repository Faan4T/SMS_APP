<?php

header("Access-Control-Allow-Origin: *");

session_start();

include_once("database.php");

include_once("functions.php");

$id = $_GET['id'];


            $sel = "SELECT subscribers.*, subscribers_group_assignment.payment_status,  companies.description , companies.merchant_login, companies.merchant_token FROM `subscribers` 
            LEFT JOIN subscribers_group_assignment ON subscribers.id = subscribers_group_assignment.subscriber_id
            LEFT JOIN campaigns ON campaigns.id = subscribers_group_assignment.group_id
            LEFT JOIN companies ON campaigns.company_id = companies.id
            WHERE subscribers.id=$id";

			$exe = mysqli_query($link,$sel);

            $payments = [];
            $a = mysqli_query($link,"SELECT * FROM `subscribers_payments` WHERE subscriber_id=".$id);

            while($b = mysqli_fetch_assoc($a)){
                $payments[] = $b;
            }


			if(mysqli_num_rows($exe)){

                $subscriber = mysqli_fetch_assoc($exe);

                $subscriber['payments'] = $payments;
                echo json_encode($subscriber);
                // print_r($subscriber);


            }