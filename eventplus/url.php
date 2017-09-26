<?php

class EventPlus_Url {

    protected $site_url = '';
    protected $assets_url = '';
    protected $menu_slug = '';

    function __construct(array $params = array('site_url' => '', 'assets_url' => '')) {
        $this->site_url = $params['site_url'];
        $this->admin_url = admin_url() . 'admin.php';
        $this->assets_url = $params['assets_url'];
        $this->menu_slug = $params['menu_slug'];
    }

    function getAssetsUrl() {
        return $this->assets_url;
    }

    function prepareUri($slug) {
        return $this->menu_slug . '_' . $slug;
    }

    function formatUri($uri) {
        return trim(str_replace($this->menu_slug, '', $uri), '_');
    }

    /**
     * Generate URL from given params
     *
     * @param array $params Named URL parts
     * @param array $queryParams Named querystring URL parts (optional)
     * @return string
     */
    function admin($core_uri, $queryParams = array()) {
        $url = $this->admin_url;

        // HTTPS Secure URL?
        if (is_ssl()) {
            $url = str_replace('http://', 'https://', $url);
        }

        $core_uri_parts = explode('/', $core_uri);
        $controller = $core_uri_parts[0];

        $url = $url . '?page=' . $this->prepareUri($controller);

        if (isset($core_uri_parts[1]) && $core_uri_parts[1] != '') {
            $url = $url . '&method=' . $core_uri_parts[1];
        }

        if (isset($core_uri_parts[2]) && $core_uri_parts[2] != '') {
            $url = $url . '&slug=' . $core_uri_parts[2];
        }


        // Is there query string data?
        $queryString = '';
        if (count($queryParams) > 0 && is_array($queryParams)) {
            $queryString .= http_build_query($queryParams, '', '&');
            $url = $url . '&' . $queryString;
        }

        // Return fully assembled URL
        $url = str_replace('///', '/', $url);

        return rtrim($url, '/');
    }

    function assembleAdminMenuUri() {

        $core_uri = '';
        $page = $_GET['page'];

        if (is_admin()) {
            $core_uri = $page;
            if ($core_uri != '' && isset($_GET['method'])) {
                if ($_GET['method'] != '') {
                    $core_uri = $core_uri . '/' . urldecode($_GET['method']);
                }
            }
        }

        return $core_uri;
    }

}
