<?php
class EventPlus_Validate extends ArrayObject {

    /**
     * Sets the unique "any field" key and creates an ArrayObject from the
     * passed array.
     *
     * @param   array   array to validate
     * @return  void
     */
    public function __construct(array $array) {
        parent::__construct($array, ArrayObject::STD_PROP_LIST);
    }

    
    /**
     * Checks if a field is not empty.
     *
     * @return  boolean
     */
    public function not_empty($value) {
        if (is_object($value) AND $value instanceof ArrayObject) {
            // Get the array from the ArrayObject
            $value = $value->getArrayCopy();
        }

        // Value cannot be NULL, FALSE, '', or an empty array
        return !in_array($value, array(NULL, FALSE, '', array()), TRUE);
    }

    /**
     * Checks a field against a regular expression.
     *
     * @param   string  value
     * @param   string  regular expression to match (including delimiters)
     * @return  boolean
     */
    public function regex($value, $expression) {
        return (bool) preg_match($expression, (string) $value);
    }

    /**
     * Check an email address for correct format.
     *
     * @link  http://www.iamcal.com/publish/articles/php/parsing_email/
     * @link  http://www.w3.org/Protocols/rfc822/
     *
     * @param   string   email address
     * @param   boolean  strict RFC compatibility
     * @return  boolean
     */
    public function email($email, $strict = FALSE) {
        $email = trim($email);

        if ($strict === TRUE) {
            $qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
            $dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
            $atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
            $pair = '\\x5c[\\x00-\\x7f]';

            $domain_literal = "\\x5b($dtext|$pair)*\\x5d";
            $quoted_string = "\\x22($qtext|$pair)*\\x22";
            $sub_domain = "($atom|$domain_literal)";
            $word = "($atom|$quoted_string)";
            $domain = "$sub_domain(\\x2e$sub_domain)*";
            $local_part = "$word(\\x2e$word)*";

            $expression = "/^$local_part\\x40$domain$/D";
        } else {
            $expression = '/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD';
        }

        return (bool) preg_match($expression, (string) $email);
    }

    /**
     * Validate the domain of an email address by checking if the domain has a
     * valid MX record.
     *
     * @link  http://php.net/checkdnsrr  not added to Windows until PHP 5.3.0
     *
     * @param   string   email address
     * @return  boolean
     */
    public function email_domain($email) {
        // Check if the email domain has a valid MX record
        return (bool) checkdnsrr(preg_replace('/^[^@]++@/', '', $email), 'MX');
    }

    /**
     * Validate a URL.
     *
     * @param   string   URL
     * @return  boolean
     */
    public function url($url) {
        $urlParts = parse_url($url);
        if (in_array(strtolower($urlParts['scheme']), array('http', 'https')) == false) {
            return FALSE;
        }

        // Based on http://www.apps.ietf.org/rfc/rfc1738.html#sec-5
        if (!preg_match(
                        '~^

			# scheme
			[-a-z0-9+.]++://

			# username:password (optional)
			(?:
				    [-a-z0-9$_.+!*\'(),;?&=%]++   # username
				(?::[-a-z0-9$_.+!*\'(),;?&=%]++)? # password (optional)
				@
			)?

			(?:
				# ip address
				\d{1,3}+(?:\.\d{1,3}+){3}+

				| # or

				# hostname (captured)
				(
					     (?!-)[-a-z0-9]{1,63}+(?<!-)
					(?:\.(?!-)[-a-z0-9]{1,63}+(?<!-)){0,126}+
				)
			)

			# port (optional)
			(?::\d{1,5}+)?

			# path (optional)
			(?:/.*)?

			$~iDx', $url, $matches))
            return FALSE;


        // We matched an IP address
        if (!isset($matches[1]))
            return TRUE;

        // Check maximum length of the whole hostname
        // http://en.wikipedia.org/wiki/Domain_name#cite_note-0
        if (strlen($matches[1]) > 253)
            return FALSE;

        // An extra check for the top level domain
        // It must start with a letter
        $tld = ltrim(substr($matches[1], (int) strrpos($matches[1], '.')), '.');
        return ctype_alpha($tld[0]);
    }

    /**
     * Validate an IP.
     *
     * @param   string   IP address
     * @param   boolean  allow private IP networks
     * @return  boolean
     */
    public function ip($ip, $allow_private = TRUE) {
        // Do not allow reserved addresses
        $flags = FILTER_FLAG_NO_RES_RANGE;

        if ($allow_private === FALSE) {
            // Do not allow private or reserved addresses
            $flags = $flags | FILTER_FLAG_NO_PRIV_RANGE;
        }

        return (bool) filter_var($ip, FILTER_VALIDATE_IP, $flags);
    }

    /**
     * Checks if a phone number is valid.
     *
     * @param   string   phone number to check
     * @return  boolean
     */
    public function phone($number, $lengths = NULL) {
        if (!is_array($lengths)) {
            $lengths = array(7, 10, 11, 12);
        }

        // Remove all non-digit characters from the number
        $number = preg_replace('/\D+/', '', $number);

        // Check if the number is within range
        return in_array(strlen($number), $lengths);
    }

    /**
     * Tests if a string is a valid date string.
     *
     * @param   string   date to check
     * @return  boolean
     */
    public function date($str) {
        $strPart = explode(' ', trim($str));

        if ($strPart[0] == '0000-00-00')
            return false;

        return (strtotime($str) !== FALSE);
    }

    /**
     * Checks whether a string consists of alphabetical characters only.
     *
     * @param   string   input string
     * @param   boolean  trigger UTF-8 compatibility
     * @return  boolean
     */
    public function alpha($str, $utf8 = FALSE) {
        $str = (string) $str;

        if ($utf8 === TRUE) {
            return (bool) preg_match('/^\pL++$/uD', EventPlus::factory('html')->hed($str));
        } else {
            return ctype_alpha(EventPlus::factory('html')->hed($str));
        }
    }

    /**
     * Checks whether a string consists of alphabetical characters and numbers only.
     *
     * @param   string   input string
     * @param   boolean  trigger UTF-8 compatibility
     * @return  boolean
     */
    public function alpha_numeric($str, $utf8 = FALSE) {
        if ($utf8 === TRUE) {
            return (bool) preg_match('/^[\pL\pN]++$/uD', EventPlus::factory('html')->hed($str));
        } else {
            return ctype_alnum($str);
        }
    }

    /**
     * Checks whether a string consists of alphabetical characters, numbers, underscores and dashes only.
     *
     * @param   string   input string
     * @param   boolean  trigger UTF-8 compatibility
     * @return  boolean
     */
    public function alpha_dash($str, $utf8 = FALSE) {
        if ($utf8 === TRUE) {
            $regex = '/^[-\pL\pN_]++$/uD';
        } else {
            $regex = '/^[-a-z0-9_]++$/iD';
        }

        return (bool) preg_match($regex, EventPlus::factory('html')->hed($str));
    }

    /**
     * Checks whether a string consists of alphabetical characters and space only.
     *
     * @param   string   input string
     * @param   boolean  trigger UTF-8 compatibility
     * @return  boolean
     */
    public function alpha_space($str, $utf8 = true) {
        if ($utf8 == true) {
            $regex = '/^[-\pL\pN ]++$/uD';
        } else {
            $regex = '#^[a-z0-9\x20]+$#i';
        }


        return preg_match($regex, EventPlus::factory('html')->hed($str));
    }

    /**
     * Checks whether a string consists of digits only (no dots or dashes).
     *
     * @param   string   input string
     * @param   boolean  trigger UTF-8 compatibility
     * @return  boolean
     */
    public function digit($str, $utf8 = FALSE) {
        if ($utf8 === TRUE) {
            return (bool) preg_match('/^\pN++$/uD', $str);
        } else {
            return (is_int($str) AND $str >= 0) OR ctype_digit($str);
        }
    }

    /**
     * Checks whether a string is a valid number (negative and decimal numbers allowed).
     *
     * Uses {@link http://www.php.net/manual/en/function.localeconv.php locale conversion}
     * to allow decimal point to be locale specific.
     *
     * @param   string   input string
     * @return  boolean
     */
    public function numeric($str) {
        // Get the decimal point for the current locale
        list($decimal) = array_values(localeconv());

        // A lookahead is used to make sure the string contains at least one digit (before or after the decimal point)
        return (bool) preg_match('/^-?+(?=.*[0-9])[0-9]*+' . preg_quote($decimal) . '?+[0-9]*+$/D', (string) $str);
    }

    /**
     * Tests if a number is within a range.
     *
     * @param   string   number to check
     * @param   integer  minimum value
     * @param   integer  maximum value
     * @return  boolean
     */
    public function range($number, $min, $max) {
        return ($number >= $min AND $number <= $max);
    }

    /**
     * Checks if a string is a proper decimal format. Optionally, a specific
     * number of digits can be checked too.
     *
     * @param   string   number to check
     * @param   integer  number of decimal places
     * @param   integer  number of digits
     * @return  boolean
     */
    public function decimal($str, $places = 2, $digits = NULL) {
        if ($digits > 0) {
            // Specific number of digits
            $digits = '{' . (int) $digits . '}';
        } else {
            // Any number of digits
            $digits = '+';
        }

        // Get the decimal point for the current locale
        list($decimal) = array_values(localeconv());

        return (bool) preg_match('/^[0-9]' . $digits . preg_quote($decimal) . '[0-9]{' . (int) $places . '}$/D', $str);
    }

    /**
     * Checks if a string is a proper hexadecimal HTML color value. The validation
     * is quite flexible as it does not require an initial "#" and also allows for
     * the short notation using only three instead of six hexadecimal characters.
     *
     * @param   string   input string
     * @return  boolean
     */
    public function color($str) {
        return (bool) preg_match('/^#?+[0-9a-f]{3}(?:[0-9a-f]{3})?$/iD', $str);
    }

    /**
     * --Validate username--
     * Validate username, consist of alpha-numeric (a-z, A-Z, 0-9), underscores, and has minimum 5 character and maximum 20 character. You could change the minimum character
     * and maximum character to any number you like.
     * 
     * @param mixed $username
     * @param mixed $min
     * @param mixed $max
     */
    public function username($username, $min = 2, $max = 20) {
        return (bool) preg_match('/^[a-z\d_.]{' . $min . ',' . $max . '}$/i', EventPlus::factory('html')->hed($username));
    }

    
    /**
     * Copies the current filter/rule/callback to a new array.
     *
     *     $copy = $array->copy($new_data);
     *
     * @param   array   new data set
     * @return  Validation
     * @since   3.0.5
     */
    public function copy(array $array) {
        // Create a copy of the current validation set
        $copy = clone $this;

        // Replace the data set
        $copy->exchangeArray($array);

        return $copy;
    }

    /**
     * Returns the array representation of the current object.
     *
     * @return  array
     */
    public function as_array() {
        return $this->getArrayCopy();
    }
}
