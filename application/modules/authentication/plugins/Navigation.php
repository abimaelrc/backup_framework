<?php
class Authentication_Plugin_Navigation extends Zend_Controller_Plugin_Abstract
{
	private $_acl;
	private $_auth;
	private $_view;
	private $_navigation;
	private $_checkFullPath;




	public function __construct(Zend_Acl $acl, Zend_Auth $auth, Zend_View $view, Zend_Navigation $navigation, $checkFullPath = true){
		$this->_acl 	      = $acl;
		$this->_auth 	      = $auth;
		$this->_view 	      = $view;
		$this->_navigation    = $navigation;
		$this->_checkFullPath = $checkFullPath;
	}




	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$uriModule     = null;
		$uriController = null;
		$uriAction     = null;
		$resource      = '/' . $request->getModuleName()
				       . '/' . $request->getControllerName()
				       . '/' . $request->getActionName();
		$pages         = new RecursiveIteratorIterator( $this->_navigation, RecursiveIteratorIterator::SELF_FIRST );

		foreach($pages as $page){
			$uriResource = $page->getResource();

			if( empty($uriResource) === false ){
				list($uriModule, $uriController, $uriAction) = explode(':', $uriResource);
			}

			if( ( $this->_checkFullPath === true && $page->getHref() == $resource )
			    || ( $this->_checkFullPath === false
				     && empty($uriModule) === false 
				     && ( $uriModule . $uriController ) == ( $request->getModuleName() . $request->getControllerName() )
			       )
			){
				$page->setActive(true);
				$page->setClass('current');
				break;
			}
		}

		$role = ( $this->_auth->hasIdentity() )
		      ? $this->_auth->getIdentity()->role
		      : 'guest';

		$this->_view->navigation($this->_navigation)
			 ->setAcl($this->_acl)
			 ->setRole($role);

	}
}