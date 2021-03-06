<?php
	class formEGHLsend{
		
		private $URL = NULL;
		protected $TransactionType = NULL;
		protected $PymtMethod = NULL;
		protected $ServiceID = NULL;
		protected $PaymentID = NULL;
		protected $OrderNumber = NULL;
		protected $PaymentDesc = NULL;
		protected $MerchantReturnURL = NULL;
		protected $MerchantCallBackURL = NULL;
		protected $Amount = NULL;
		protected $CurrencyCode = NULL;
		protected $HashValue = NULL;
		protected $CustIP = NULL;
		protected $CustName = NULL;
		protected $CustEmail = NULL;
		protected $CustPhone = NULL;
		protected $PageTimeout = NULL;
		protected $MerchantTermsURL =NULL;
		
		// Define all hash value components specifiying if they are mandatory or not
		private $hash_components = array(	//Param => is_mandatory
											'ServiceID'=>true,
											'PaymentID'=>true,
											'MerchantReturnURL'=>true,
											'MerchantCallBackURL'=>false,
											'Amount'=>true,
											'CurrencyCode'=>true,
											'CustIP'=>true,
											'PageTimeout'=>false
										);
		
		// Define all post params specifiying if they are mandatory or not
		private $post_vars = array(	//Param => is_mandatory
									'TransactionType'=>true,
									'PymtMethod'=>true,
									'ServiceID'=>true,
									'PaymentID'=>true,
									'OrderNumber'=>true,
									'PaymentDesc'=>true,
									'MerchantReturnURL'=>true,
									'MerchantCallBackURL'=>false,
									'Amount'=>true,
									'CurrencyCode'=>true,
									'HashValue'=>true,
									'CustIP'=>true,
									'CustName'=>true,
									'CustEmail'=>true,
									'CustPhone'=>true,
									'PageTimeout'=>true,
									'MerchantTermsURL'=>false
									);
		
		// Will contain the HTTP Query format of post params
		private $post_args = NULL;
		
		public function __construct($URL=NULL){
			if(is_null($URL)){
				echo "Payment URL is not provided</br>";
			}
			else{
				$this->URL = $URL;
			}
		}
		
		// Method to get the value of protected/private variable of this class
		public function get($attr){
			return $this->$attr;
		}
		
		// Method to set the value of protected/private variable of this class
		public function set($attr,$value){
			$this->$attr = $value;
		}
		
		//Calling this function will automatically populate all post variables via $_REQUEST
		public function getValuesFromRequest(){
			$exempted_attr = array('URL','hash_components','post_args');
			$args = get_object_vars($this);
			foreach( $args as $ind=>$val){
				if(!in_array($ind,$exempted_attr)){
					if(isset($_REQUEST[$ind])){
						$this->$ind = $_REQUEST[$ind];
					}
				}
			}
		}
		 
		/* 	calculate hashing (HashValue)
			must pass the merchant password as argument to this function 
		*/
		public function calcHash($mPassword=NULL){
			
			if(is_null($mPassword)){
				return false;
			}
			$hash_str = $mPassword;
			if($this->checkHashComponents()){
				foreach($this->hash_components as $component=>$is_mandatory){
					$hash_str.=$this->$component;
				}
				$this->HashValue = hash('sha256',$hash_str);
				return $this->HashValue;
			}
			else
			{
				return false;
			}
		}
		
		
		//forming payment request
		public function getFormHTML($hashpass=NULL){
			
			$hash = $this->calcHash($hashpass);

			if($hash===false){
				echo 'HashValue cannot be calculated';
			}
			elseif($this->checkPostVars())
			{
				$html =	'<!DOCTYPE html>
							<html lang="en">
							<head>
								  <meta charset="UTF-8">
								  <title>Document</title>
							</head>
							<body>  

							   <h3> POST hidden data to EGHL </h3>

								  <form name="frmPayment" method="post" action="'.$this->URL.'"> 

										
										<input type="hidden" name="TransactionType" value="'.$this->TransactionType.'">
										<input type="hidden" name="PymtMethod" value="'.$this->PymtMethod.'">
										<input type="hidden" name="ServiceID" value="'.$this->ServiceID.'">
										<input type="hidden" name="PaymentID" value="'.$this->PaymentID.'">
										<input type="hidden" name="OrderNumber" value="'.$this->OrderNumber.'">
										<input type="hidden" name="PaymentDesc" value="'.$this->PaymentDesc.'">
										<input type="hidden" name="MerchantReturnURL" value="'.$this->MerchantReturnURL.'">
										<input type="hidden" name="MerchantCallBackURL" value="'.$this->MerchantCallBackURL.'">
										<input type="hidden" name="Amount" value="'.$this->Amount.'">
										<input type="hidden" name="CurrencyCode" value="'.$this->CurrencyCode.'">
										<input type="hidden" name="CustIP" value="'.$this->CustIP.'">
										<input type="hidden" name="CustName" value="'.$this->CustName.'">
										<input type="hidden" name="CustEmail" value="'.$this->CustEmail.'">
										<input type="hidden" name="CustPhone" value="'.$this->CustPhone.'">
										<input type="hidden" name="HashValue" value="'.$this->HashValue.'">
										<input type="hidden" name="MerchantTermsURL" value="'.$this->MerchantTermsURL.'">
										<input type="hidden" name="PageTimeout" value="'.$this->PageTimeout.'">
										<input type="submit" value="submit">
								  </form>
							</body>
							</html>';
				return $html;
			}
			else{
				exit;
			}
		}
		
		//to form HTTP Query format i.e. name value params seperated by &
		public function buildPostVarStr()
		{
			$exempted_attr = array('URL','hash_components','post_args');
			$args = get_object_vars($this);
			foreach( $args as $ind=>$val){
				if(in_array($ind,$exempted_attr)){
					unset($args[$ind]);
				}
			}
			$this->post_args = http_build_query($args);
			return $this->post_args;
		}
		
		//to validate the mandatory hash components are present
		private function checkHashComponents(){
			foreach($this->hash_components as $component=>$is_mandatory){
				if(is_null($this->$component) && $is_mandatory){
					echo 'A mandatory hash component "'.$component.'" is missing...<br/>';
					return false;
				}
			}
			return true;
		}
		
		//to validate the mandatory post params are present
		private function checkPostVars(){
			foreach($this->post_vars as $component=>$is_mandatory){
				if(is_null($this->$component) && $is_mandatory){
					echo 'A mandatory Post param "'.$component.'" is missing...<br/>';
					return false;
				}
			}
			return true;
		}
	}
?>
