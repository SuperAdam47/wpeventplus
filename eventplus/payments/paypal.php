<?php
/**
 * Paypal payment method
 */
class EventPlus_Payments_Paypal extends EventPlus_Payments {

    var $last_error;                 // holds the last error encountered
    var $ipn_log;                    // bool: log IPN results to text file?
    var $ipn_log_file;               // filename of the IPN log
    var $ipn_response;               // holds the IPN response from paypal   
    var $ipn_data = array();         // array contains the POST values for IPN
    var $fields = array();           // array holds the fields to submit to paypal
    var $paypal_url = '';           // array holds the fields to submit to paypal

    function __construct() {
        parent::__construct();

        $this->last_error = '';
        $this->ipn_log_file = '.ipn_results.log';
        $this->ipn_log = true;
        $this->ipn_response = '';
        $this->add_field('rm', '2');          // Return method = POST
        $this->add_field('cmd', '_xclick');
        $this->add_field('charset', 'utf-8');

        $this->method = EventPlus_Models_Payments::PAYPAL;

        if ($this->companyOptions['use_sandbox'] == "Y") {
            $this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // testing paypal url
        } else {
            $this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url
        }
    }

    protected function valid() {

        $valid = false;

        if (EventPlus::factory('Validate')->email($this->companyOptions['payment_vendor_id'])) {
            $valid = true;
        }

        return $valid;
    }

    function submit() {
        ob_start(); ?>
        <style>
            .eventplus_paypal_payment_button{background-size:cover; background-position:center center; border:0; border: 0 none;}
        </style>
        <form method="post" name="paypal_form" action="<?php echo $this->paypal_url ?>">
            <?php
            foreach ($this->fields as $name => $value) {
                echo "<input type=\"hidden\" name=\"$name\" value='" . esc_html($value) . "'/>";
            }
            ?>
            <div class="rowdiv">
                <input type="submit" id="registration_payment_button" class="eventplus_paypal_payment_button btn btn-sma77 btn-gr3y btn-ic0n paymen8" value="<?php _e('Pay Now', 'evrplus_language'); ?>">  
            </div>
        </form>
        <?php
        return ob_get_clean();
    }

    function validateIpn() {
        $url_parsed = parse_url($this->paypal_url);
        $post_string = '';
        foreach ($_POST as $field => $value) {
            $this->ipn_data["$field"] = $value;
            $post_string .= $field . '=' . urlencode(stripslashes($value)) . '&';
        }
        $post_string.="cmd=_notify-validate"; // append ipn command

        $fp = fsockopen($url_parsed[host], "80", $err_num, $err_str, 30);

        if (!$fp) {

            $this->last_error = "fsockopen error no. $errnum: $errstr";
            $this->log_ipn_results(false);
            return false;
        } else {
            fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
            fputs($fp, "Host: $url_parsed[host]\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: " . strlen($post_string) . "\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $post_string . "\r\n\r\n");
            while (!feof($fp)) {
                $this->ipn_response .= fgets($fp, 1024);
            }
            fclose($fp); // close connection
        }
        if (eregi("VERIFIED", $this->ipn_response)) {
            $this->log_ipn_results(true);
            return true;
        } else {

            $this->last_error = 'IPN Validation Failed.';
            $this->log_ipn_results(false);
            return false;
        }
    }

    function log_ipn_results($success) {
        if (!$this->ipn_log) {
            return;
        } // is logging turned off?      
        // Timestamp
        $text = '[' . date('m/d/Y g:i A') . '] - ';

        if ($success) {
            $text .= "SUCCESS!\n";
        } else {
            $text .= 'FAIL: ' . $this->last_error . "\n";
        }
        // Log the POST variables
        $text .= "IPN POST Vars from Paypal:\n";
        foreach ($this->ipn_data as $key => $value) {
            $text .= "$key=$value, ";
        }
        // Log the response from the paypal server
        $text .= "\nIPN Response from Paypal Server:\n " . $this->ipn_response;
        $fp = fopen($this->ipn_log_file, 'a');
        fwrite($fp, $text . "\n\n");
        fclose($fp);  // close file
    }

    function validatePdt($paypal_transaction_token, $auth_token) {

        /*         Part - 1 */
        $req = 'cmd=_notify-synch';
        $req .= "&tx=$paypal_transaction_token&at=" . $auth_token;  // test key

        /*         Part - 2 */
        $paypal_url = parse_url($this->paypal_url);

        $ipnexec = curl_init();
        curl_setopt($ipnexec, CURLOPT_URL, "https://" . $paypal_url['host'] . "/webscr&");
        curl_setopt($ipnexec, CURLOPT_HEADER, 0);
        curl_setopt($ipnexec, CURLOPT_USERAGENT, 'Server Software: ' . @$_SERVER['SERVER_SOFTWARE'] . ' PHP Version: ' . phpversion());
        curl_setopt($ipnexec, CURLOPT_REFERER, $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . @$_SERVER['QUERY_STRING']);
        curl_setopt($ipnexec, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ipnexec, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ipnexec, CURLOPT_POST, 1);
        curl_setopt($ipnexec, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ipnexec, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ipnexec, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ipnexec, CURLOPT_TIMEOUT, 30);
        $ipnresult = trim(curl_exec($ipnexec));
        $ipnresult = "status=" . $ipnresult;
        curl_close($ipnexec);

        /*         Part - 3 */
        $parameter_value_array = explode("\n", $ipnresult);

        $ipnData = array();

        foreach ($parameter_value_array as $key => $value) {
            $key_values = explode("=", $value);
            $ipnData[trim($key_values[0])] = trim($key_values[1]);
        }

        return $ipnData;
      
    }
  
}
