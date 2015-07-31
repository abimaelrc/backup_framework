<?php
class Authentication_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('login');
        $this->view->headTitle()
             ->setSeparator(' - ')
             ->prepend('Autenticación');
    }

    public function indexAction()
    {
        $session    = Extras_Session::getSessionNamespace();
        $requestURL = (
                        empty($session->requestURL) === false
                        && $session->requestURL != ('/' . $this->_request->module . '/' . $this->_request->controller . '/' . $this->_request->action)
                      )
                    ? $session->requestURL
                    : $this->view->url(array(), 'default-index');

        if (Zend_Auth::getInstance()->hasIdentity() === true) {
            unset($session->requestURL);
            $this->_redirect($requestURL);
        }

        $qry  = new Authentication_Model_Queries();
        $form = new Authentication_Form_Login();
        $form->setAction($this->view->url(array(), 'login'));
        $this->view->form = $form;

        if (empty($session->flashMessenger) === true) {
            $this->view->message = $session->flashMessenger;
            unset($session->flashMessenger);
        }

        if ($this->getRequest()->isPost() === true) {
            if ($form->isValid($this->getRequest()->getPost()) === true) {
                $qry->setParams($form->getValues());
                if ($qry->loginQry() === true) {
                    $this->_redirect($requestURL);
                } else {
                    $this->view->message = nl2br(implode(PHP_EOL, $qry->getMessages()));
                }
            }
        }
    }




    public function sendResetPwdAction()
    {
        $qry  = new Authentication_Model_Queries();
        $form = new Authentication_Form_SendResetPwd();
        $form->setAction('/authentication/index/send-reset-pwd');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $qry->setParams($form->getValues());
                $qry->sendResetPwdQry();
                $this->view->message = nl2br(implode(PHP_EOL, $qry->getMessages()));
            }
        }
    }




    public function resetPwdAction()
    {
        $qry      = new Authentication_Model_Queries();
        $tokenPwd = $this->getRequest()->getQuery('token_pwd');

        $qry->setParams(
            array(
                'token_pwd' => $tokenPwd,
            )
        );

        if (($usersId = $qry->verifyResetTokenPwdQry()) === false) {
            $this->_helper->redirector->gotoSimpleAndExit('index', 'index', 'authentication');
        }

        $form = new Authentication_Form_ResetPwd(
            array(
                'usersId' => $usersId,
            )
        );
        $form->setAction('/authentication/index/reset-pwd?token_pwd=' . $tokenPwd);
        $this->view->form    = $form;
        $this->view->usersId = $usersId;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $values              = $form->getValues();
                $values['token_pwd'] = $tokenPwd;
                $qry->setParams($values);

                if ($qry->resetPwdQry() === true) {
                    $this->_helper->redirector->gotoSimpleAndExit('index', 'index', 'default');
                }
            }
        }
    }




    public function logoutAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        Zend_Auth::getInstance()->clearIdentity();

        $session = Extras_Session::sessionNamespace();
        $session->unsetAll();
        Zend_Session::destroy();

        $this->_helper->redirector->gotoSimpleAndExit('index', 'index', 'default');
    }
}