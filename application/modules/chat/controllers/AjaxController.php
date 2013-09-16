<?php

class Chat_AjaxController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout()->disableLayout();
        if ($this->getRequest()->isXmlHttpRequest() === false) {
            $this->_forward('index');
        }
    }

    public function indexAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function countAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $qry = new Chat_Model_QueriesAjax();
        $this->getResponse()->setBody($qry->countQry($this->getRequest()->getPost('chat_type')));
    }

    public function messagesAction()
    {
        $qry                         = new Chat_Model_QueriesAjax();
        $this->view->getChatMessages = $qry->getChatMessagesQry($this->getRequest()->getPost('chat_type'));
    }

    public function addAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $qry = new Chat_Model_QueriesAjax();

        $qry->addQry($this->getRequest()->getPost());
    }

    public function addPrivateUserAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $qry = new Chat_Model_QueriesAjax();

        $qry->addPrivateUserQry($this->getRequest()->getPost());
    }

    public function availableUsersAction()
    {
        $qry = new Chat_Model_QueriesAjax();
        $this->view->availableUsers = $qry->availableUsers();
    }

    public function closeAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $qry = new Chat_Model_QueriesAjax();

        $qry->closeQry($this->getRequest()->getPost());
    }
}