<?php
	include_once 'formEGHLsend.php';
	
	$obj = new formEGHLsend('https://test2pay.ghl.com/IPGSG/Payment.aspx');


		if ($_SERVER["REQUEST_METHOD"] == "POST") {
		  // collect value of input field
		  $OrderNumber = $_POST['OrderNumber'];
		  $PaymentDesc = $_POST['PaymentDesc'];
		  $PaymentID = $_POST['PaymentID'];
		  $Amount = $_POST['Amount'];
		  $CurrencyCode = $_POST['CurrencyCode'];
		  
		}

		
	
	//assign the appropraite values to each of the param
	$obj->set('TransactionType','SALE');
	$obj->set('PymtMethod','ANY');
	$obj->set('OrderNumber',$OrderNumber);
	$obj->set('PaymentDesc',$PaymentDesc);
	$obj->set('ServiceID','SIT');  //assign the Merchant ID given by eGHL
	$obj->set('PaymentID',$PaymentID); //must be unique. Do increment
	$obj->set('MerchantReturnURL','http://screwshop.thailandpages.com/payment/response.php');
	$obj->set('MerchantCallBackURL','http://screwshop.thailandpages.com');
	$obj->set('Amount',$Amount);    //correct format = 10000.00
	$obj->set('CurrencyCode',$CurrencyCode);
	$obj->set('CustIP','127.0.0.1');
	$obj->set('PageTimeout','780');
	$obj->set('CustName','TBP test');
	$obj->set('CustEmail','sciant@gmail.com');
	$obj->set('CustPhone','6627519274');
	$obj->set('MerchantTermsURL','http://screwshop.thailandpages.com');


	$form_html = $obj->getFormHTML('sit12345');  //assign the merchant password given by eGHL
	echo $form_html;

	
?>
