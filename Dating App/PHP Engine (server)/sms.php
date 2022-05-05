<?php

    // This file for demo only!

	include_once($_SERVER['DOCUMENT_ROOT']."/core/init.inc.php");
 
  	// check the signature
  	$secret = "FORTUMO_SECRET";

  	if(empty($secret) || !check_signature($_GET, $secret)) {

    	header("HTTP/1.0 404 Not Found");
    	die("Error: Invalid signature");
  	}
 
  	$sender = $_GET['sender'];//phone num.
  	$amount = $_GET['amount'];//credit
  	$cuid = $_GET['cuid'];//resource i.e. user
  	$payment_id = $_GET['payment_id'];//unique id
  	$test = $_GET['test']; // this parameter is present only when the payment is a test payment, it's value is either 'ok' or 'fail'
 
  //hint: find or create payment by payment_id
  //additional parameters: operator, price, user_share, country
    
  	if(preg_match("/failed/i", $_GET['status'])) {

    // mark payment as failed
  	} else {

    // mark payment successful
  	}
 
  // print out the reply
  	if($test) {

    	echo('TEST OK');

  	} else {

    	echo('OK');
  	}
 
  	function check_signature($params_array, $secret) {

    	ksort($params_array);
 
    	$str = '';
    	foreach ($params_array as $k=>$v) {

      		if($k != 'sig') {

        		$str .= "$k=$v";
      		}
    	}

    	$str .= $secret;
    	$signature = md5($str);
 
    	return ($params_array['sig'] == $signature);
  	}