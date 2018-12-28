<?php

/**
*
Abstract Payment Class
**/

abstract class EventPlus_Payments {

    protected $method = null;
    public $companyOptions = array();

    function __construct() {
        $this->companyOptions = EventPlus_Models_Settings::getSettings();
    }

    public $fields = array();

    function add_field($field, $value) {

        $this->fields[$field] = $value;
    }

    /*Final*/
    final function isValid() {

        if (EventPlus_Models_Payments::isActive($this->method)) {
            return false;
        }

        return $this->valid();
    }

    /*Abstract Method*/
    abstract protected function valid();

    function dump_fields($print = 1) {

        $dumpStr = '';

        $fields = $this->fields;
        if (count($fields) > 0) {

            ksort($fields);

            if (strtoupper($this->companyOptions['use_sandbox']) == "Y" && $this->method == EventPlus_Models_Payments::PAYPAL) {
                $dumpStr .= '
        <p style="color:#ff0000; margin-bottom:0px; padding:0px; font-size:12px;">' . __('PayPal Sandbox Mode Is Active', 'evrplus_language') . ' </p>
            <small style="font-size:11px;">' . __('Payments will not be processed', 'evrplus_language') . '</small>
         ';
            }

            $dumpStr .= "
<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" style='font-size:11px;'>
  <tbody>
  <tr>
    <td bgcolor=\"gray\"><b><font color=\"white\">Field Name</font></b></td>
    <td bgcolor=\"gray\"><b><font color=\"white\">Value</font></b></td>
  </tr> ";

            foreach ($fields as $key => $value) {
                if (trim($value) != '') {
                    $dumpStr .= "<tr>
    <td>$key</td>
    <td>" . urldecode($value) . "&nbsp;</td>
  </tr>";
                }
            }
            $dumpStr .= "</tbody></table>";
        }

        if ($print) {
            echo $dumpStr;
        } else {
            return $dumpStr;
        }
    }
}
