<?php

/**
 * Designed to allow for mod_rewrite-like functionality using pure PHP structures
 */
class EventPlus_Router extends EventPlus_Abstract_Router {

    /**
     * Processes a request and sets its controller and action. 
     * Also, sets all the indexers keys as router params
     *
     * @uses  clsKeysIndexer - Route indexer
     * @return void
     */
    public function run() {
        $request = $this->getRequest();

        $core_url = $request->get(EVENT_PLUS_URI_KEY);

        if ($core_url == '') {
            return false;
        }

        $this->prepareRouteParams($core_url);
        $this->fillRequest($request);
    }

    private function fillRequest($request) {
        $request->setController($this->getParam('controller'))
                ->setAction($this->getParam('method'))
                ->setSlug($this->getParam('slug'))
                ->setCode($this->getParam('code'));
    }

    private function prepareRouteParams($core_url) {
        $uri = ltrim($core_url, '/');
        $uri = preg_replace('#//+#', '/', $core_url);
        $explode_uri = explode('/', $uri);

        $keys = array(
            'controller', 'method', 'slug', 'code'
        );

        foreach ($explode_uri as $index => $param) {
            if (isset($keys[$index])) {
                $this->setParam($keys[$index], $param);
            }
        }
    }

}
