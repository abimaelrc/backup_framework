<?php

class Statistics_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        // ini_set('memory_limit','512M');
        // set_time_limit(300);
        $this->_helper->layout->setLayout('index');
        $this->view->headTitle()
            ->setSeparator(' - ')
            ->prepend('Estadísticas y Reportes');
    }




    public function indexAction()
    {
        $values = $this->getRequest()->getParams();

        unset($values['module']);
        unset($values['controller']);
        unset($values['action']);

        $qry                   = new Statistics_Model_Queries();
        $messages              = $this->_helper->FlashMessenger->getMessages();
        $this->view->message   = implode(PHP_EOL, $messages);
		$this->view->from      = ($this->getRequest()->getParam('from') == '0000-00-00') ? null : $this->getRequest()->getParam('from');
		$this->view->to        = ($this->getRequest()->getParam('to') == '0000-00-00') ? null : $this->getRequest()->getParam('to');
		$this->view->usersId   = $this->getRequest()->getParam('users_id');
		$this->view->fromHour  = (empty($values['from_hour']) === true) ? 0 : $values['from_hour'];
		$this->view->toHour    = (empty($values['to_hour']) === true) ? 23 : $values['to_hour'];
        $this->view->employees = $qry->getEmployeesQry();
        $validate              = new Zend_Validate();
        $validate->addValidator(new Statistics_Model_Validate_Validate());

        /**
         * Callback demo
         */
        //$validate = new Zend_Validate_Callback(new Statistics_Model_Validate_ValidateCallback());

        if ($this->getRequest()->isGet()) {
            if (empty($values) === false) {
                if ($validate->isValid($values)) {
                    $this->_helper->layout()->disableLayout();
                    $this->_helper->viewRenderer->setNoRender(true);

                    $qry->setParams($values);
                    $values = $qry->indexQry();

                    Extras_Headers::csv($values['fileName']);

                    $this->getResponse()->setBody($values['info']);
                } else {
                    $this->view->message .= implode(PHP_EOL, $validate->getMessages());
                }
            }
        }
    }
}