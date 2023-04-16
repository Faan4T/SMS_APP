<?php

// error_reporting(0);

// ini_set("error_reporting","1");

header("Access-Control-Allow-Origin: *");

session_start();

include_once("database.php");

include_once("functions.php");



echo twilio_total_msg('+18888600722','+923219090909');