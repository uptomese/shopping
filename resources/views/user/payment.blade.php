<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>
<body>

    <form name="myform" method="POST" action="{{$URL}}" enctype="multipart/form-data" >
    {{csrf_field()}}                     
        <input type="hidden" name="TransactionType" value="{{$TransactionType}}">
        <input type="hidden" name="PymtMethod" value="{{$PymtMethod}}">
        <input type="hidden" name="ServiceID" value="{{$ServiceID}}">
        <input type="hidden" name="PaymentID" value="{{$PaymentID}}">
        <input type="hidden" name="OrderNumber" value="{{$OrderNumber}}">
        <input type="hidden" name="PaymentDesc" value="{{$PaymentDesc}}">
        <input type="hidden" name="MerchantReturnURL" value="{{$MerchantReturnURL}}">
        <input type="hidden" name="MerchantCallBackURL" value="{{$MerchantCallBackURL}}">
        <input type="hidden" name="Amount" value="{{$Amount}}">
        <input type="hidden" name="CurrencyCode" value="{{$CurrencyCode}}">
        <input type="hidden" name="CustIP" value="{{$CustIP}}">
        <input type="hidden" name="CustName" value="{{$CustName}}">
        <input type="hidden" name="CustEmail" value="{{$CustEmail}}">
        <input type="hidden" name="CustPhone" value="{{$CustPhone}}">
        <input type="hidden" name="HashValue" value="{{$HashValue}}">
        <input type="hidden" name="MerchantTermsURL" value="{{$MerchantTermsURL}}">
        <input type="hidden" name="PageTimeout" value="{{$PageTimeout}}">
        <input type="hidden" name="Param6" value="{{$Param6}}">
    </form>
</body>
</html>

<script type="text/javascript">
    document.myform.submit();
</script>