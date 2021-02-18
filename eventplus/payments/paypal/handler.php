<?php
/**
Paypal handler
*/
class EventPlus_Payments_Paypal_Handler {

    function handleResponse(){
		if (isset($_REQUEST['eventplus_pm']) && isset($_REQUEST['tx'])) {
			
			
			
            if (strtolower($_REQUEST['eventplus_pm']) == 'paypal') {
                if (isset($_REQUEST['eventplus_token']) == false) {
                    wp_die("Invalid paypal request.");
                }

                $validActions = array('re7urn' => 'do_return', 'canc3l' => 'do_cancel');

                if (isset($_REQUEST['eventplus_pm']) == false) {
                    wp_die("Oops! Invalid paypal action.");
                }

                $_REQUEST['eventplus_pm_action'] = trim(strtolower($_REQUEST['eventplus_pm_action']));

                if (isset($validActions[$_REQUEST['eventplus_pm_action']]) == false) {
                    wp_die("Invalid paypal action.");
                }
                
                $method = $validActions[$_REQUEST['eventplus_pm_action']];
			
                $this->$method();
            }
        }

    }

    /*Handle return*/
    function do_return() {
         

        global $wpdb;

        $oPayPal = new EventPlus_Payments_Paypal();

        $eventplus_token = $_REQUEST['eventplus_token'];
        $isPending = EventPlus_Helpers_Token::isPending($eventplus_token);
        if ($isPending === false) {
            wp_die(__("Couldn't proceed. Registration already processed.", 'evrplus_language'));
            return;
        }

        $company_options = EventPlus_Models_Settings::getSettings();

        $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE token = '" . esc_sql($eventplus_token) . "' LIMIT 1";
        $attendeeRow = $wpdb->get_row($sql, ARRAY_A);

        $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . (int) $attendeeRow['event_id'] . " LIMIT 1";
        $eventRow = $wpdb->get_row($sql, ARRAY_A);
        if ($eventRow['id'] <= 0) {
            wp_die(__("Invalid request", 'evrplus_language'));
        }


        if ($attendeeRow['payment_status'] == EventPlus_Models_Payments::PAYMENT_SUCCESS) {
            wp_die(__("Already processed", 'evrplus_language'));
        }


        $event_id = intVal($eventRow['id']);

        $payment_status = EventPlus_Models_Payments::PAYMENT_FAILED;
        $amountPaid = 0;
        $txn_id = '';
        $payment_date = date('Y-m-d G:i:s', time());
        $first_name = '';
        $last_name = '';
        $payer_email = '';
        $mc_gross = 0;
        $mc_currency = '';

        $pdtData = $oPayPal->validatePdt($_REQUEST['tx'], $company_options['paypal_pdt_token']);

        $txn_data = array_merge($_REQUEST, $pdtData);

        $amount_pd = 0;
        if (isset($txn_data['txn_id'])) {
            $txn_id = trim($txn_data['txn_id']);
            $first_name = $txn_data['first_name'];
            $last_name = $txn_data['last_name'];
            $payer_email = $txn_data['payer_email'];
            $mc_gross = $txn_data['mc_gross'];
            $mc_currency = $txn_data['mc_currency'];
            $amount_pd = $mc_gross;
        }

        $sql = "SELECT txn_id,payer_id FROM " . get_option('evr_payment') . " WHERE txn_id = '" . esc_sql(trim($txn_id)) . "' AND payer_id=" . (int) $attendeeRow['id'] . " LIMIT 1";
        $_paymentRow = $wpdb->get_row($sql, ARRAY_A);

        if ($_paymentRow['payer_id'] > 0) {
            wp_die(__("Payment already processed", 'evrplus_language'));
        }

        $pdt_payment_status = strtoupper($txn_data['payment_status']);

        if ($pdt_payment_status == 'FAIL') {
            $payment_status = EventPlus_Models_Payments::PAYMENT_FAILED;
        } else if ($pdt_payment_status == 'COMPLETED') {
            $payment_status = EventPlus_Models_Payments::PAYMENT_SUCCESS;
            $amountPaid = $amount_pd;
        } else {
            $payment_status = $pdt_payment_status;
        }


        $wpdb->query($wpdb->prepare("UPDATE " . get_option('evr_attendee') . " SET payment_status = '" . esc_sql($payment_status) . "', amount_pd = '" . esc_sql($amountPaid) . "', payment_date = '" . esc_sql($payment_date) . "' WHERE id = %d", $attendeeRow['id']));

        $sqlParams = array(
            'payer_id' => $attendeeRow['id'],
            'event_id' => $event_id,
            'payment_date' => $payment_date,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'payer_email' => $payer_email,
            'txn_id' => $txn_id,
            'mc_gross' => $mc_gross,
            'mc_currency' => $mc_currency,
            'payment_type' => 'full',
            'payment_status' => $payment_status,
            'pending_reason' => '' . $txn_data['pending_reason'] . '',
            'payer_status' => '' . $txn_data['payer_status'] . '',
            'payment_type' => '' . $txn_data['payment_type'] . '',
            'reason_code' => '' . $txn_data['reason_code'] . '',
            'txn_type' => EventPlus_Models_Payments::PAYPAL
        );

        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');
        $wpdb->insert(get_option('evr_payment'), $sqlParams, $sql_data);

        EventPlus_Helpers_Token::delete($event_id);


        $emailData = array(
            'payer_id' => $attendeeRow['id'],
            'attendee_id' => $attendeeRow['id'],
            'event_id' => $event_id,
            'payment_status' => $payment_status,
            'txn_data' => array(
                "payer_email" => $payer_email,
                "amount" => $mc_gross,
                "txn_id" => $txn_data['txn_id'],
                'payment_status' => $payment_status,
                'mc_currency' => $mc_currency,
                'payment_date' => $payment_date,
                'txn_type' => EventPlus_Models_Payments::PAYPAL
            )
        );

        $oEmailPayment = new EventPlus_Helpers_Mail_Payment($emailData);
        $oEmailPayment->send();

        $urlToGo = evrplus_permalink($company_options['evrplus_page_id']) . '?event_id=' . $event_id . '&action=confirmation&eventplus_token=' . $attendeeRow['token'];
        echo'<script>window.location.href="' . $urlToGo . '";</script>';
        exit;
    }
	
    /*Handle cancel*/
    function do_cancel() {

        global $wpdb;
		
	
        $eventplus_token = $_REQUEST['eventplus_token'];

        $company_options = EventPlus_Models_Settings::getSettings();

        $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE token = '" . esc_sql($eventplus_token) . "' LIMIT 1";
        $attendeeRow = $wpdb->get_row($sql, ARRAY_A);

        $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . (int) $attendeeRow['event_id'] . " LIMIT 1";
        $eventRow = $wpdb->get_row($sql, ARRAY_A);
        if ($eventRow['id'] <= 0) {
            wp_die(__("Invalid request", 'evrplus_language'));
        }

        $event_id = $eventRow['id'];


        $returnUrl = evrplus_permalink($company_options['evrplus_page_id']) . "?action=confirmation&eventplus_token=" . $eventplus_token . "&event_id=" . $event_id;

        echo'<script>window.location.href="' . $returnUrl . '";</script>';
        exit;
    }
}
