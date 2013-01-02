<?php
class Statistics_IndexController extends Zend_Controller_Action
{
    public function init()
    {
		ini_set('memory_limit','512M');
		//set_time_limit(300);
        $this->_helper->layout->setLayout('index');
		$this->view->headTitle()
			->setSeparator(' - ')
			->prepend('Estadísticas y Reportes');
    }




    public function indexAction()
    {
		$qry                   = new Statistics_Model_Queries();
		$values                = $this->getRequest()->getParams();
		$this->view->message   = implode(PHP_EOL, $this->_helper->FlashMessenger->getMessages());
		$this->view->from      = ( empty($values['from']) === true || $values['from'] == '0000-00-00' )
		                       ? null
							   : $values['from'];
		$this->view->to        = ( empty($values['to']) === true || $values['to'] == '0000-00-00' )
		                       ? null
							   : $values['to'];
		$this->view->usersId   = ( empty($values['users_id']) === true ) ? null : $values['users_id'];
		$this->view->employees = $qry->getEmployeesQry();
		$this->view->fromHour  = ( empty($values['from_hour']) === true ) ? 0 : $values['from_hour'];
		$this->view->toHour    = ( empty($values['to_hour']) === true ) ? 23 : $values['to_hour'];
		$validate              = new Zend_Validate_Callback(new Statistics_Model_Validate_Validate());

		if( $this->getRequest()->isGet() === true ){
			if( $validate->isValid($values) === true ){
				$this->_helper->layout()->disableLayout();
				$this->_helper->viewRenderer->setNoRender(true);

				$qry->setParams($values);
				$values = $qry->indexQry();

				Extras_Headers::csv($values['fileName']);

				$this->getResponse()->setBody($values['info']);

				// $this->_helper->redirector->gotoSimpleAndExit('index', 'index', 'statistics');
			}
		}

		$session             = new Zend_Session_Namespace('ccmdesk.validate.statistics');
		$this->view->message .= implode(PHP_EOL, $session->alert);
		Zend_Session::namespaceUnset('ccmdesk.validate.statistics');
	}
}