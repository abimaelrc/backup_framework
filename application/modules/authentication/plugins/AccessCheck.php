<?php
class Authentication_Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract
{
	private $_acl;
	private $_auth;




	public function __construct(Zend_Acl $acl, Zend_Auth $auth)
	{
		$this->_acl = $acl;
		$this->_auth = $auth;
	}




	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$module 	= $request->getModuleName();
		$controller = $request->getControllerName();
		$action 	= $request->getActionName();
		$resource 	= $module . ':' . $controller . ':' . $action;
		$resource 	= ( ( $controller . ':' . $action ) == 'error:error' )
					  ? 'default:error:error'
					  : $resource;
		$params 	= $request->getParams();
		$role 		= 'guest';

		if($resource == 'authentication:index:logout'){
			return;
		}

		if($this->_auth->hasIdentity()){
			$qry = new Authentication_Model_Queries;
			$qry->setParams( array( 'num_empl'=>$this->_auth->getStorage()->read()->num_empl,
									'pwd'=>$this->_auth->getStorage()->read()->pwd ));
			if( $qry->currentAuthInfoQry() === true ){
				$this->_auth = Zend_Auth::getInstance();
				$role = $this->_auth->getStorage()->read()->role;
			}

			$userInfo = $this->_auth->getIdentity();
		}

		$deleteUserBool = (!empty($userInfo) && $userInfo->deleted_account == 1);
		$blockUserBool  = (!empty($userInfo) && $userInfo->block_access == 1);
		$updatePwdBool  = (!empty($userInfo) && $userInfo->change_pwd == 1);

		if( $this->_acl->isAllowed($role, $resource, $action) === false
			|| $blockUserBool === true
			|| $updatePwdBool === true
			|| $deleteUserBool === true
		){
			$session = Extras_Session::sessionNamespace();

			if( $deleteUserBool === true ){
				$this->_auth->clearIdentity();
				$session->flashMessenger = 'Usuario o contraseña incorrecta. Por favor trata nuevamente';
			}
			if( $blockUserBool === true ){
				$this->_auth->clearIdentity();
				$session->flashMessenger = 'Cuenta bloqueada. Comunícate con el Administrator';
			}
			if( $this->_auth->hasIdentity() === false ){
				$filter = new Filter_Xss;
				$session->requestURL = $filter->realEscapeString($request->getRequestUri(), true);
			}
			if( $updatePwdBool === true && $blockUserBool === false && $deleteUserBool === false ){
				$request->setModuleName('configure')
						->setControllerName('index')
						->setActionName('index')
						->setParams(array());
				return;
			}
			$request->setModuleName('authentication')
					->setControllerName('index')
					->setActionName('index')
					->setParams(array());
		}
	}
}