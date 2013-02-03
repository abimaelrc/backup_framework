<?php
class Application_Plugin_Headers extends Zend_Controller_Plugin_Abstract
{
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $response = $this->getResponse();
        $opts     = Extras_Config::getOptions(new Zend_Config_Ini(APPLICATION_PATH . '/configs/headers.ini'));
        $opts     = array_key_exists('headers', $opts) ? $opts['headers'] : array();
        foreach($opts as $k => $v){
            ( $v == 'gmdate' )
                ? $response->setHeader($k, gmdate('D, d M Y H:i:s') . ' GMT', true)
                : $response->setHeader($k, $v, true);
        }
        $this->setResponse($response);
    }
}