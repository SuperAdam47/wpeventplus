<?php

/**
  Stripe handler
 */
class EventPlus_Payments_Stripe_Handler {

    function handleResponse() {
        if( isset($_REQUEST['stripeToken']) && isset($_REQUEST['stripeTokenType']) ) {
            // No need library now, the payment process done by wp_safe_remote_post
            //require_once(EVENT_PLUS_PLUGIN_PATH . 'public/stripe/Stripe/lib/Stripe.php');
            $this->processResponse();
        }
    }

    /* Handle return */
    public function processResponse() {

        global $wpdb;

        $registraionToken = $_REQUEST['token'];

        $company_options = EventPlus_Models_Settings::getSettings();

        $isPending = EventPlus_Helpers_Token::isPending( $registraionToken );
        if( $isPending === false ) {
            wp_die(__("Couldn't proceed! registration already processed.", 'evrplus_language'));
            return;
        }

        $event_id = $_REQUEST['event_id'];

        // Get attendee data
        $atndTable = get_option( 'evr_attendee' );
        $sql = "SELECT * FROM " . $atndTable . " WHERE token = '" . esc_sql( $registraionToken ) . "'";
        $attendeeRow = $wpdb->get_row( $sql, ARRAY_A );

        $payment_status = EventPlus_Models_Payments::PAYMENT_FAILED;
        $amountPaid = 0;
        $txn_id = '';

        $stripeToken    = $_REQUEST['stripeToken'];
        $stripeEmail    = $_REQUEST['stripeEmail'];
        $itemName       = $_REQUEST['item_name'];
        $itemDesc       = $_REQUEST['item_description'];
        $currency       = $_REQUEST['item_currency'];
        $amount         = $_REQUEST['amount'];
        $formatedAmt    = $_REQUEST['item_amount'];

        $headers = array(
            "authorization" => 'Bearer ' . $company_options['secret_key'],
        );

        // Stripe request args
        $request = array(
            'receipt_email' => $stripeEmail,
            // name args will give invalid param error and payment will be fail
            //'name' => $itemName,
            'description' => $itemDesc,
            'source' => $stripeToken,
            'currency' => $currency,
            'amount' => $formatedAmt
        );

        // Stripe API Request
        $response = wp_safe_remote_post(
           'https://api.stripe.com/v1/charges',
            array(
                'method'  => 'POST',
                'headers' => $headers,
                'httpversion' => '1.1',
                'body'    => $request,
                'timeout' => 30,
            )
        );

        if( is_wp_error($response) ) {
            $payment_status = EventPlus_Models_Payments::PAYMENT_FAILED;
            $amountPaid = 0;
        } else {
            $data = json_decode( wp_remote_retrieve_body($response) );
            if( isset($data->paid) && $data->paid == '1' ) {
                $payment_status = EventPlus_Models_Payments::PAYMENT_SUCCESS;
                $amountPaid = $amount;
                $txn_id = $data->id;
            } else {
                $payment_status = EventPlus_Models_Payments::PAYMENT_FAILED;
                $amountPaid = 0;
            }
        }

        $payment_date = date( 'Y-m-d G:i:s', time() );

        $wpdb->query( $wpdb->prepare("UPDATE " . $atndTable . " SET payment_status = '" . esc_sql($payment_status) . "', amount_pd = '" . esc_sql($amountPaid) . "', payment_date = '" . esc_sql($payment_date) . "' WHERE id = %d", $attendeeRow['id']) );

        $sqlParams = array(
            'payer_id' => $attendeeRow['id'],
            'event_id' => $event_id,
            'payment_date' => $payment_date,
            'payer_email' => $stripeEmail,
            'txn_id' => $txn_id,
            'mc_gross' => $amountPaid,
            'payment_type' => 'full',
            'payment_status' => $payment_status,
            'txn_type' => EventPlus_Models_Payments::STRIPE
        );

        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');
        $wpdb->insert( get_option('evr_payment'), $sqlParams, $sql_data );

        EventPlus_Helpers_Token::delete( $event_id );

        $emailData = array(
            'payer_id' => $attendeeRow['id'],
            'attendee_id' => $attendeeRow['id'],
            'event_id' => $event_id,
            'payment_date' => $payment_date,
            'payment_status' => $payment_status,
            'txn_data' => array(
                "payer_email" => $stripeEmail,
                "amount" => $amountPaid,
                "txn_id" => $txn_id,
                'payment_status' => $payment_status,
                'payment_date' => $payment_date,
                'txn_type' => EventPlus_Models_Payments::STRIPE
            )
        );

        $oEmailPayment = new EventPlus_Helpers_Mail_Payment( $emailData );
        $oEmailPayment->send();

        $urlToGo = evrplus_permalink($company_options['evrplus_page_id']) . '?event_id=' . $event_id . '&action=confirmation&eventplus_token=' . $registraionToken;
        echo'<script>window.location.href="' . $urlToGo . '";</script>';
        exit;
    }

}
