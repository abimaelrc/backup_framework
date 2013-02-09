<?php
class Notes_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('index');
        $this->view->headTitle()
             ->setSeparator(' - ')
             ->prepend('Notas');
    }

    public function indexAction()
    {
        $qry  = new Notes_Model_Queries();
        $form = new Notes_Form_Notes();
        $form->setAction($this->view->url(array(), 'notes'));
        $this->view->form    = $form;
        $messages            = $this->_helper->FlashMessenger->getMessages();
        $this->view->notes   = $qry->getActiveNotesQry();
        $this->view->message = (empty($messages[0]) === false)
                             ? $messages[0]
                             : null;
        if (empty($messages[1]) === false) {
            $form->populate($messages[1]);
        }

        if ($this->getRequest()->isPost() === true) {
            if ($form->isValid($this->getRequest()->getPost()) === true) {
                $qry->setParams($form->getValues());
                
                if ($qry->indexQry() === true) {
                    $this->_helper->FlashMessenger('Nota añadida');
                }

                exit($this->_redirect($this->view->url(array(), 'notes')));
            }
        }
    }
}

