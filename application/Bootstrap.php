<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initSession()
	{
		Zend_Session::start();
	}




	protected function _initDateDefaultTimezoneSet()
	{
		date_default_timezone_set("America/Puerto_Rico");
	}




	protected function _initConfig()
	{
		Zend_Registry::set('additionalParams', $this->getOption('additionalParams'));
	}




	protected function _initFront()
	{
		$this->bootstrap('frontController');
		return $this->getResource('frontController');
	}




	protected function _initLayoutView()
	{
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		return $layout->getView();
	}




	protected function _initSetEscape()
	{
		$view = $this->getResource('layoutView');
		$view->setEscape( array( new Extras_Escape(), 'escape' ) );
	}




	protected function _initHeaders()
	{
		$config = new Zend_Config_Ini( APPLICATION_PATH . '/configs/headers.ini' );
		$this->getResource('front')->registerPlugin( new Application_Plugin_Headers($config) );
	}




	protected function _initRoutes()
	{
		$routes = new Application_Model_Url();
		$routes->fetchRouterRoute();
	}




	protected function _initViewHelpers()
	{
		$view = $this->getResource('layoutView');
		$view->doctype('XHTML1_TRANSITIONAL');
		$view->headTitle('Test');
		$view->headMeta()->appendHttpEquiv( 'Content-Type', 'text/html;charset=utf-8' );
	}




	protected function _initTranslate()
	{
		$translate = new Zend_Translate( 'array', APPLICATION_PATH . '/configs/languages/es.php', 'es_ES' );
		Zend_Form::setDefaultTranslator($translate); 
	}
}