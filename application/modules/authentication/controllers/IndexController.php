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
                    $this->view->message = implode('', $qry->getMessages());
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

        $this->_redirect($this->view->url(array(), 'login'));
    }
}