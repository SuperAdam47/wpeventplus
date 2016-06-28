<?php
$varDirectoryPath = dirname(__FILE__);

$varDirectoryPath = str_replace('wp-content\plugins\wpeventplus\public\stripe', '', $varDirectoryPath);
$varDirectoryPath = str_replace('wp-content/plugins/wpeventplus/public/stripe', '', $varDirectoryPath);


include_once($varDirectoryPath . 'wp-load.php');
include_once($varDirectoryPath . 'wp-config.php');
include_once($varDirectoryPath . 'wp-includes/wp-db.php');

global $wpdb;

if(isset($_POST['stripeToken']) == false){
    die("Invalid request.");
}

$stripeToken = $_POST['stripeToken'];
$amount = $_POST['amount'];
$event_id = $_POST['event_id'];
$stripeEmail = $_POST['stripeEmail'];
$stripeTokenType = $_POST['stripeTokenType'];

$price = $amount * 100;
$company_options = EventPlus_Models_Settings::getSettings();
$stripeurl = $company_options['stripereturn_url'];


try {
    
    require_once('Stripe/lib/Stripe.php');
    Stripe::setApiKey($company_options['secret_key']); //Replace with your Secret Key

    $charge = Stripe_Charge::create(array(
                "amount" => $price,
                "currency" => "usd",
                "card" => $stripeToken,
                "description" => "Demo Transaction"
    ));


    //header('Location:  .$stripeurl.');
    //send the file, this line will be reached if no error was thrown above
    echo "<h1>Your payment has been completed.</h1>";
    //you can send the file to this email:
    //echo $_POST['stripeEmail'];
    ?>
    <style>
        td {border:1px solid #d1d1d1; padding:8px;}
    </style>
    <table width="40%" cellspacing="0" cellpadding="0" border="0" >
        <tbody>
            <tr><td cellpadding="0" cellspacing="0"><strong>StripeToken:</strong></td><td><?php echo $_POST['stripeToken']; ?></td></tr>
            <tr><td><strong>StripeTokenType:</strong></td><td><?php echo $_POST['stripeTokenType']; ?></td></tr>
            <tr><td><strong>StripeEmail:</strong></td><td><?php echo $_POST['stripeEmail']; ?></td>
            <tr><td><strong>Stripeamount:</strong></td><td><?php echo $amount; ?></td>
            </tr>
        </tbody>
    </table>


    <?php
} catch (Stripe_CardError $e) {
    
}

//catch the errors in any way you like
catch (Stripe_InvalidRequestError $e) {
    // Invalid parameters were supplied to Stripe's API
} catch (Stripe_AuthenticationError $e) {
    // Authentication with Stripe's API failed
    // (maybe you changed API keys recently)
} catch (Stripe_ApiConnectionError $e) {
    // Network communication with Stripe failed
} catch (Stripe_Error $e) {

    // Display a very generic error to the user, and maybe send
    // yourself an email
} catch (Exception $e) {

    // Something else happened, completely unrelated to Stripe
}
//echo "INSERT INTO wp_evr_payment (event_id,stripeTokenType,stripeEmail,stripeamount,stripeToken) VALUES($event_id,$stripeTokenType,$stripeEmail,$amount,$stripeToken)"; die;
$q = $wpdb->query("INSERT INTO wp_evr_payment (event_id,stripeTokenType,stripeEmail,stripeamount,stripeToken) VALUES($event_id,'$stripeTokenType','$stripeEmail',$amount,'$stripeToken')");

echo'<script>window.location.href="' . $stripeurl . '?event_id=' . $event_id . '";</script>';
?>
