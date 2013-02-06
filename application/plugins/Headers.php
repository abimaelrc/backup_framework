<?php
class Application_Plugin_Headers extends Zend_Controller_Plugin_Abstract
{
    /**
     * @param Zend_Controller_Request_Abstract $request
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $response = $this->getResponse();
        $opts     = Extras_Config::getOptionsToArray(new Zend_Config_Ini(APPLICATION_PATH . '/configs/headers.ini'));
        $opts     = (is_array($opts) === true && array_key_exists('headers', $opts) === true)
                  ? $opts['headers']
                  : array();

        foreach ($opts as $k => $v) {
            if ($v == 'gmdate') {
                $response->setHeader($k, gmdate('D, d M Y H:i:s') . ' GMT', true);
            } else {
                $response->setHeader($k, $v, true);
            }
        }

        $this->setResponse($response);
    }
}