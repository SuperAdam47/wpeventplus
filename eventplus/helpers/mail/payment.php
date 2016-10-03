<?php

class EventPlus_Helpers_Mail_Payment extends EventPlus_Helpers_Mail {

    protected function bindParams($str) {

        $strBody = parent::bindParams($str);

        $txnData = $this->data['txn_data'];

        $bindParams = array(
            "[payer_email]" => $txnData['payer_email'],
            "[amnt_pd]" => $txnData['amount'],
            "[txn_id]" => $txnData['txn_id'],
        );

        foreach ($bindParams as $searchValues => $replaceValues) {
            $strBody = str_replace($searchValues, $replaceValues, $strBody);
        }

        return $strBody;
    }

    function send() {

        $email_subject = $this->company_options['payment_subj'];
        $email_body = stripslashes($this->company_options['payment_message']);
        $pay_confirm = strtoupper($this->company_options['pay_confirm']);
        $organization = $this->company_options['company'];
        $contact = $this->company_options['company_email'];

        if ($pay_confirm == 'Y' && $this->data['payment_status'] == EventPlus_Models_Payments::PAYMENT_SUCCESS) {
            
            $headers = array(
                "From: " . $organization . " <" . $contact . ">",
                "Reply-To: " . $organization . "  <" . $contact . ">",
                "Content-Type: text/html; charset=UTF-8"
            );

            $email_subject = $this->bindParams($email_subject);
            $email_body = $this->bindParams($email_body);

            $this->send_wp_mail($this->attendeeRow['email'], html_entity_decode($email_subject), html_entity_decode($email_body), $headers);
        }
    }

}
