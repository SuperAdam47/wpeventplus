<?php

/**
Stripe Payment
*/
class EventPlus_Payments_Stripe extends EventPlus_Payments {

    private $jsSrc = 'https://checkout.stripe.com/checkout.js';

    protected function valid() {
        $valid = false;
        if( trim($this->companyOptions['secret_key']) != '' &&
            trim($this->companyOptions['publishable_key']) != '' ) {
            $valid = true;
        }
        return $valid;
    }

    function submit() {
    	
    	global $wpdb;

        ob_start(); ?>

		<!-- 
		Remove action, ad $post data does not retrived there.
		<form action="<?php echo $this->fields['stripe_process_url']; ?>" method="POST">
		-->
		<form action="" method="POST">
			<div>
				<?php
				$currency = $this->companyOptions['default_currency'];
				if( $this->fields['currency_code'] != '' ) {
					$currency = $this->fields['currency_code'];
				}

				$amount = number_format( $this->fields['amount'], 2 );
				$formatedAmt = str_replace( array(',', '.'), array('', ''), $amount );

				$lang = get_locale(); ?>

				<script src="<?php echo $this->jsSrc; ?>" class="stripe-button" 
					data-locale="<?php echo $lang; ?>"
					data-key="<?php echo $this->companyOptions['publishable_key']; ?>"  
					data-amount="<?php echo $formatedAmt; ?>" 
					data-currency = "<?php echo "$currency"; ?>" />
				</script>

				<input type="submit" id="registration_stripe_button" class="btn btn-sma77 btn-gr3y btn-ic0n paymen8" value="<?php _e('Pay Now', 'evrplus_language'); ?>"> 

				<input type="hidden" value="<?php echo $this->fields['desc']; ?>" name="item_name" />
				<input type="hidden" value="<?php echo $this->fields['desc']; ?>" name="item_description" />
				<input type="hidden" value="<?php echo $currency; ?>" name="item_currency" />

				<input type="hidden" name="amount" value="<?php echo $amount; ?>" />
				<input type="hidden" name="item_amount" value="<?php echo $formatedAmt; ?>" />
				<input type="hidden" name="token" value="<?php echo $this->fields['token']; ?>" />
				<input type="hidden" name="event_id" value="<?php echo $this->fields['event_id']; ?>" />
				
				<!--
				// No need this for now
				<input type="hidden" name="evrplus_stripe_payments" value="1" />
				 -->
			</div>
		</form>

        <style>
            .stripe-button-el{ display: none !important; }
            .stripe-button-el span{ display: none !important; }
        </style>
        <?php
        return ob_get_clean();
    }
}
