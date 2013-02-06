<?php
class Authentication_Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract
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
     * @param Zend_Acl $acl
     * @param Zend_Auth $auth
     */
    public function __construct(Zend_Acl $acl, Zend_Auth $auth)
    {
        $this->acl  = $acl;
        $this->auth = $auth;
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module     = $request->getModuleName();
        $controller = $request->getControllerName();
        $action     = $request->getActionName();
        $resource   = $module . ':' . $controller . ':' . $action;
        $resource   = (( $controller . ':' . $action) == 'error:error')
                    ? 'default:error:error'
                    : $resource;
        $params     = $request->getParams();
        $role       = 'guest';

        if ($resource == 'authentication:index:logout') {
            return;
        }

        if ($this->auth->hasIdentity()) {
            $qry = new Authentication_Model_Queries;
            $qry->setParams(
                array(
                    'num_empl' =>$this->auth->getStorage()->read()->num_empl,
                    'pwd'      =>$this->auth->getStorage()->read()->pwd
                )
            );

            if ($qry->currentAuthInfoQry() === true) {
                $this->auth = Zend_Auth::getInstance();
                $role       = $this->auth->getStorage()->read()->role;
            }

            $userInfo = $this->auth->getIdentity();
        }

        $deleteUserBool = (empty($userInfo) === false && $userInfo->deleted_account == 1);
        $blockUserBool  = (empty($userInfo) === false && $userInfo->block_access == 1);
        $updatePwdBool  = (empty($userInfo) === false && $userInfo->change_pwd == 1);

        if(
            $this->acl->isAllowed($role, $resource, $action) === false
            || $deleteUserBool === true
            || $blockUserBool === true
            || $updatePwdBool === true
        ){
            $session = Extras_Session::getSessionNamespace();

            if ($deleteUserBool === true) {
                $this->auth->clearIdentity();
                $session->flashMessenger = 'Usuario o contraseña incorrecta. Por favor trata nuevamente';
            }

            if ($blockUserBool === true) {
                $this->auth->clearIdentity();
                $session->flashMessenger = 'Cuenta bloqueada. Comunícate con el Administrator';
            }

            if ($this->auth->hasIdentity() === false) {
                $filter = new Filter_Xss;
                $session->requestURL = $filter->realEscapeString($request->getRequestUri(), true);
            }

            if ($updatePwdBool === true && $blockUserBool === false && $deleteUserBool === false) {
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