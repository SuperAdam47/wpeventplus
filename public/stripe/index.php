<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Stripe Payment Gateway Using PHP</title>
</head>
<body>
	<div class='tLink'><strong>Tutorial Link:</strong> <a href='how-to-integrate-stripe-payment-gateway-using-php' target='_blank'>Click Here</a></div><br/>
<div class='web'>
	<h1>Stripe Payment Gateway Using PHP</h1>
	<p>Price: $21.00</p>
	<p>Name: Demo Transaction</p>
	<form action="payment.php" method="POST">
		<script src="https://checkout.stripe.com/checkout.js" class="stripe-button" 
		data-key="pk_test_PwUbCXKP4cwR80H8USrwzmuX" 
		data-image="http://www.stepblogging.com/wp-content/uploads/2014/12/logo1.png" 
		data-name="StepBlogging.com" 
		data-description="Demo Transaction ($21.00)"
		data-amount="2100" />
		</script>
	</form>
	<p class='error'>
		<strong>Testing Card Number</strong> - 4242424242424242<br />
		<strong>CVV Number</strong> - 1234 <br />
		<strong>Card Expiry Date</strong> - Use any future date <br />
	</p>
</div>
</body>
</html>
<style>
.web{
	font-family:tahoma;
	size:12px;
	top:10%;
	border:1px solid #CDCDCD;
	border-radius:10px;
	padding:10px;
	width:45%;
	margin:auto;
	height:auto;
}
h1, h2{
	margin:3px 0;
	font-size:13px;
	text-decoration:underline;
}
.tLink{
	font-family:tahoma;
	size:12px;
	padding-left:10px;
	text-align:center;
}

.talign_right{
	text-align:right;
}
.username_availability_result{
	display:block;
	width:auto;
	float:left;
	padding-left:10px;
}
.input{
	float:left;
}
.error{
	color:#FF0000;
	font-size:12px;
}

</style>