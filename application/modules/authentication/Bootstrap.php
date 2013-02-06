<?php
class Authentication_Bootstrap extends Zend_Application_Module_Bootstrap
{
    /**
     * Setup Acl
     */
    protected function _initAcl()
    {
        return new Authentication_Model_Acl();
    }

    /**
     * Setup Zend_Auth
     */
    protected function _initAuth()
    {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(Extras_Session::storageNamespace());

        return $auth;
    }

    /**
     * Setup frontController
     */
    protected function _initFront()
    {
        $this->bootstrap('frontController');

        return $this->getResource('frontController');
    }

    /**
     * Setup layout view
     */
    protected function _initLayoutView()
    {
        $appBootstrap = $this->getApplication();
        $appBootstrap->bootstrap('layout');
        $layout = $appBootstrap->getResource('layout');

        return $layout->getView();
    }

    /**
     * Verify access of current user
     */
    protected function _initAccessCheck()
    {
        $this->getResource('front')->registerPlugin(
            new Authentication_Plugin_AccessCheck($this->getResource('acl'), $this->getResource('auth'))
        );
    }

    /**
     * Zend_Navigation
     */
    protected function _initNavigation()
    {
        $xmlUrl = APPLICATION_PATH . '/configs/navigation.xml';
        $this->getResource('front')
             ->registerPlugin(
                 new Authentication_Plugin_Navigation(
                     $this->getResource('acl'),
                     $this->getResource('auth'),
                     $this->getResource('layoutView'),
                     new Zend_Navigation(new Zend_Config_Xml( $xmlUrl, 'nav' ))
                 )
             );
    }
}