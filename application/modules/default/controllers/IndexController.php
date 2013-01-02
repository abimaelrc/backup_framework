<?php
class IndexController extends Zend_Controller_Action
{
	public function init()
	{
		$this->_helper->layout->setLayout('index');
		$this->view->headTitle()
			 ->setSeparator(' - ')
			 ->prepend('Inicio');
	}

	public function indexAction()
	{
		$qry                 = new Default_Model_Queries;
		$this->view->notes   = $qry->indexQry();
		$this->view->message = implode('', $this->_helper->FlashMessenger->getMessages());
	}
}