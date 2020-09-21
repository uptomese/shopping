<?php

	function getCheckoutFormFields () {
    
	/*
        Hash Key = Password + ServiceID + PaymentID + MerchantReturnURL + MerchantCallBackURL + Amount + CurrencyCode + CustIP + PageTimeout
        */
	$fields = array(
            'TransactionType'           => $payment_action,
            'PymtMethod'                => $PymtMethod,
            'ServiceID'                 => $ServiceID,
            'PaymentID'                 => $PaymentID,
            'OrderNumber'               => $orderReferenceValue,
            'PaymentDesc'               => $PaymentDesc,
            'MerchantReturnURL'         => $postbackground,
            'MerchantCallBackURL'       => $callback,
            'Amount'                    => $grandTotalAmount,
            'CurrencyCode'              => $currency_code,
            'HashValue'                 => hash('sha256', $m_password . $ServiceID . $PaymentID . $postbackground . $callback . $grandTotalAmount . $currency_code . $ip . 600),
            'CustIP'                    => $ip,
            'CustName'                  => $fullname,
            'CustEmail'                 => $email,
            'CustPhone'                 => $tel,
            'PageTimeout'               => 600

		);

		$filtered_fields = array();
        foreach ($fields as $k=>$v) {
            $value = str_replace("&","and",$v);
            $filtered_fields[$k] =  $value;
        }
        return $filtered_fields;
	}//end function getCheckoutFormFields

?>
