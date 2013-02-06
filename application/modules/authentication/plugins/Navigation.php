<?php
class Authentication_Plugin_Navigation extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Acl
     */
    private $acl;

    /**
     * @var Zend_Auth
     */
    private $auth;

    /**
     * @var Zend_View
     */
    private $view;

    /**
     * @var Zend_Navigation
     */
    private $navigation;

    /**
     * @var boolean
     */
    private $checkFullPath;

    /**
     * @param Zend_Acl $acl
     * @param Zend_Auth $auth
     * @param Zend_View $view
     * @param Zend_Navigation $navigation
     * @param boolean $checkFullPath
     */
    public function __construct(
        Zend_Acl $acl,
        Zend_Auth $auth,
        Zend_View $view,
        Zend_Navigation $navigation,
        $checkFullPath = true
    ){
        $this->acl           = $acl;
        $this->auth          = $auth;
        $this->view          = $view;
        $this->navigation    = $navigation;
        $this->checkFullPath = $checkFullPath;
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $uriModule     = null;
        $uriController = null;
        $uriAction     = null;
        $resource      = '/' . $request->getModuleName()
                       . '/' . $request->getControllerName()
                       . '/' . $request->getActionName();
        $pages         = new RecursiveIteratorIterator( $this->navigation, RecursiveIteratorIterator::SELF_FIRST );

        foreach($pages as $page){
            $uriResource = $page->getResource();

            if ( empty($uriResource) === false ) {
                list($uriModule, $uriController, $uriAction) = explode(':', $uriResource);
            }

            if (
                ($this->checkFullPath === true && $page->getHref() == $resource)
                || ( $this->checkFullPath === false
                     && empty($uriModule) === false 
                     && ( $uriModule . $uriController ) == ( $request->getModuleName() . $request->getControllerName() )
                   )
            ) {
                $page->setActive(true);
                $page->setClass('current');
                break;
            }
        }

        $role = ( $this->auth->hasIdentity() )
              ? $this->auth->getIdentity()->role
              : 'guest';

        $this->view->navigation($this->navigation)
             ->setAcl($this->acl)
             ->setRole($role);

    }
}