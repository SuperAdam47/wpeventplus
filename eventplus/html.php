<?php

class EventPlus_Html {

    function hed($value) {
        return html_entity_decode((string) $value, ENT_QUOTES, 'utf-8');
    }

    /**
     * Convert special characters to HTML entities. All untrusted content
     * should be passed through this method to prevent XSS injections.
     *
     * @param   string   string to convert
     * @param   boolean  encode existing entities
     * @return  string
     */
    function chars($value, $double_encode = TRUE) {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'utf-8', $double_encode);
    }

    /**
     * Convert all applicable characters to HTML entities. All characters
     * that cannot be represented in HTML with the current character set
     * will be converted to entities.
     *
     * @param   string   string to convert
     * @param   boolean  encode existing entities
     * @return  string
     */
    public function entities($value, $double_encode = TRUE) {
        return htmlentities((string) $value, ENT_QUOTES, 'utf-8', $double_encode);
    }

}
