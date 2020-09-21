<?php

//Receive POSTED variables from the gateway
$txnType = $_REQUEST['TransactionType'];
$pymtMethod = $_REQUEST['PymtMethod'];
$serviceID = $_REQUEST['ServiceID'];
$paymentID = $_REQUEST['PaymentID'];
$orderNumber = $_REQUEST['OrderNumber'];
$amount = $_REQUEST['Amount'];
$currencyCode = $_REQUEST['CurrencyCode'];
$hashValue2 = $_REQUEST['HashValue2'];
$txnID = $_REQUEST['TxnID'];
$issuingBank = $_REQUEST['IssuingBank'];
$txnStatus = $_REQUEST['TxnStatus'];
$authCode = $_REQUEST['AuthCode'];
$bankRefNo = $_REQUEST['BankRefNo'];
$txnMessage = $_REQUEST['TxnMessage'];
$param6 = $_REQUEST['Param6'];
$param7 = $_REQUEST['Param7'];

//check Hash Value
$m_password = 'sit12345' ; // Specify Merchant Password assigned by eGHL here
$hashchk = hash('sha256', $m_password . $txnID . $serviceID . $paymentID . $txnStatus . $amount . $currencyCode . $authCode . $orderNumber . $param6 . $param7);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field
  print("Payment gateway respose method as : POST <br>");
}elseif($_SERVER["REQUEST_METHOD"] == "GET"){
  print("Payment gateway respose method as : GET <br>");
  exit ;
}
print("=================================== <br>");
foreach($_REQUEST as $key => $value){
   print ($key ." : " .  $value . "<br>" ) ;
}

print("=================================== <br>");

print ("<br>HASK CHECK local response : ".$hashchk."<br>");

if ($hashValue2 == $hashchk) {
    if ($txnStatus == '0') { 
      print ('<br> Check hashValue2  == hashchk <br>') ;
      print ("Payment Ststus : $txnStatus "."<br>" );
      print ("OrderNumber : $OrderNumber "."<br>" );
      print  ("Payment success handling here<br> " ) ;
    } elseif ($txnStatus == '1') {
      print ('<br> Check txnStatus  == 1 <br>' );
      print ("Payment Ststus : $txnStatus "."<br>" );
      print ("OrderNumber : $OrderNumber"."<br>" );
      print  ("Payment Fail status<br> " ) ;
    }
}
?>


<a href="/payment/post.php" > back to post </a>