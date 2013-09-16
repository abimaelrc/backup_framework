<?php
class Chat_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('index');
        $this->view->headTitle()
            ->setSeparator(' - ')
            ->prepend('Chat');
    }

    public function indexAction()
    {
        $qry  = new Chat_Model_Queries();
        $form = new Chat_Form_Chat(
            array(
                'createdBy' => $qry->getSpecificUserInfo('users_id'),
            )
        );
        $form->setAction('/chat');
        $this->view->form    = $form;
        $messages            = $this->_helper->FlashMessenger->getMessages();
        $this->view->message = implode('', $messages);
    }
}