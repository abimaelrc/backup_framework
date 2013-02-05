<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Setup some params in registry
     */
    protected function _initConfig()
    {
        Zend_Registry::set('additionalParams', $this->getOption('additionalParams'));
    }

    /**
     * Session start
     */
    protected function _initSession()
    {
        Extras_Session::sessionNamespace(Extras_Config::getOption('session'));
    }

    /**
     * Setting timezone
     */
    protected function _initDateDefaultTimezoneSet()
    {
        date_default_timezone_set("America/Puerto_Rico");
    }

    /**
     * Getting the frontController in the bootstrap
     */
    protected function _initFront()
    {
        $this->bootstrap('frontController');
        return $this->getResource('frontController');
    }

    /**
     * Getting the view in the bootstrap
     */
    protected function _initLayoutView()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        return $layout->getView();
    }

    /**
     * Customize the escape method
     */
    protected function _initSetEscape()
    {
        $view = $this->getResource('layoutView');
        $view->setEscape(array(new Extras_Escape(), 'escape'));
    }

    /**
     * Setup some headers
     */
    protected function _initHeaders()
    {
        $this->getResource('front')->registerPlugin(new Application_Plugin_Headers());
    }

    /**
     * Setup routes
     */
    protected function _initRoutes()
    {
        $this->getResource('front')->registerPlugin(new Application_Plugin_Url());
    }

    /**
     * Setup common helper directory
     */
    protected function _initCommonHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPrefix('Common_Helper');
    }

    /**
     * Setup view layout structure
     */
    protected function _initViewHelpers()
    {
        $view = $this->getResource('layoutView');
        $view->doctype('XHTML1_TRANSITIONAL');
        $view->headTitle('Test');
        $view->headMeta()
             ->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
    }

    /**
     * Setup custome alerts to spanish
     */
    protected function _initTranslate()
    {
        $translate = new Zend_Translate('array', APPLICATION_PATH . '/configs/languages/es.php', 'es_ES');
        Zend_Form::setDefaultTranslator($translate); 
    }
}