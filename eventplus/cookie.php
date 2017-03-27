<?php

class EventPlus_Cookie {

    /**
     * @var  string  Magic salt to add to the cookie
     */
    public static $salt = 'plusevent';

    /**
     * @var  integer  Number of seconds before the cookie expires
     */
    public static $expiration = 0;

    /**
     * @var  string  Restrict the path that the cookie is available to
     */
    public static $path = '/';

    /**
     * @var  string  Restrict the domain that the cookie is available to
     */
    public static $domain = NULL;

    /**
     * @var  boolean  Only transmit cookies over secure connections
     */
    public static $secure = FALSE;

    /**
     * @var  boolean  Only transmit cookies over HTTP, disabling Javascript access
     */
    public static $httponly = TRUE;

    /**
     * Gets the value of a signed cookie. Cookies without signatures will not
     * be returned. If the cookie signature is present, but invalid, the cookie
     * will be deleted.
     *
     *     // Get the "theme" cookie, or use "blue" if the cookie does not exist
     *     $theme = self::get('theme', 'blue');
     *
     * @param   string  cookie name
     * @param   mixed   default value to return
     * @return  string
     */
    public static function get($key, $default = NULL) {
        if (!isset($_COOKIE[$key])) {
            // The cookie does not exist
            return $default;
        }

        // Get the cookie value
        $cookie = $_COOKIE[$key];

        // Find the position of the split between salt and contents
        $split = strlen(self::salt($key, NULL));

        if (isset($cookie[$split]) AND $cookie[$split] === '~') {
            // Separate the salt and the value
            list ($hash, $value) = explode('~', $cookie, 2);

            if (self::salt($key, $value) === $hash) {
                // Cookie signature is valid
                return $value;
            }

            // The cookie signature is invalid, delete it
            self::delete($key);
        }

        return $default;
    }

    /**
     * Sets a signed cookie. Note that all cookie values must be strings and no
     * automatic serialization will be performed!
     *
     *     // Set the "theme" cookie
     *     self::set('theme', 'red');
     *
     * @param   string   name of cookie
     * @param   string   value of cookie
     * @param   integer  lifetime in seconds
     * @return  boolean
     * @uses    self::salt
     */
    public static function set($name, $value, $expiration = 0) {
        if ($expiration === NULL) {
            // Use the default expiration
            $expiration = self::$expiration;
        }

        if ($expiration !== 0) {
            // The expiration is expected to be a UNIX timestamp
            $expiration += time();
        }

        // Add the salt to the cookie value
        $value = self::salt($name, $value) . '~' . $value;

        return setcookie($name, $value, (int)$expiration, self::$path, self::$domain, self::$secure, self::$httponly);
    }

    /**
     * Deletes a cookie by making the value NULL and expiring it.
     *
     *     self::delete('theme');
     *
     * @param   string   cookie name
     * @return  boolean
     * @uses    self::set
     */
    public static function delete($name) {
        // Remove the cookie
        unset($_COOKIE[$name]);

        // Nullify the cookie and make it expire
        return setcookie($name, NULL, -86400, self::$path, self::$domain, self::$secure, self::$httponly);
    }

    /**
     * Generates a salt string for a cookie based on the name and value.
     *
     *     $salt = self::salt('theme', 'red');
     *
     * @param   string   name of cookie
     * @param   string   value of cookie
     * @return  string
     */
    public static function salt($name, $value) {
        // Require a valid salt
        if (!self::$salt) {
            throw new Exception('A valid cookie salt is required. Please set ' . self::$salt . '');
        }

        // Determine the user agent
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : 'unknown';

        return sha1($agent . $name . $value . self::$salt);
    }

}

// End cookie
