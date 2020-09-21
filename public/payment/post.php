
<?php 

// {{$order_id}}
// {{$payment_id}}
// {{$product_name}}
// {{$price_total}}

$order_id = $_GET['order_id'];
$payment_id = $_GET['payment_id'];
$product_name = $_GET['product_name'];
$price_total = $_GET['price_total'];


?>
<form action="/payment/submit.php" method="post" name="myform">

		<label for="OrderNumber">OrderNumber: Type Any number</label><br>
		<input type="text" id="OrderNumber" name="OrderNumber" value="<?php echo $order_id; ?>"><br>
		<label for="PaymentID">PaymentID: type in Format ( SITTBP0000000000XXX ) max 20 charector</label><br>
		<input type="text" id="PaymentID" name="PaymentID" value="<?php echo $payment_id; ?>"><br>
		<label for="PaymentDesc">PaymentDesc: Type Any text </label><br>
		<input type="text" id="PaymentDesc" name="PaymentDesc" value="<?php echo $product_name; ?>"><br>
        <label for="Amount">Amount format 100.00 </label><br>
		<input type="text" id="Amount" name="Amount" value="<?php echo $price_total; ?>" ><br>
        <label for="CurrencyCode">Amount format 100.00 </label>
		<select id="CurrencyCode" name="CurrencyCode">
            <option value="USD">US Dollar(USD)</option>
            <option value="THB" selected>Thai baht(THB)</option>
            <option value="CNY">China yuan(CNY)</option>
            <option value="MYR">Malaysian Ringgit (MYR)</option>
            <option value="PHP">Philippine peso (PHP)</option>
        </select><br>
        <input type="submit" value="Submit">
</form> 

<script type="text/javascript">
    document.myform.submit();
</script>




