<?php
class Configure_IndexController extends Zend_Controller_Action
{
	public function init()
	{
        $this->_helper->layout->setLayout('index');
		$this->view->headTitle()
			 ->setSeparator(' - ')
			 ->prepend('Configuración');
	}




	public function indexAction()
	{
		$qry 		 	 	 = new Configure_Model_Queries();
		$rowUserInfo 	  	 = $qry->getConfigureUserInfoQry();
		$form 		 	  	 = new Configure_Form_Configure( array( 'name' => $rowUserInfo['name'],
																	'numEmpl' => $rowUserInfo['num_empl'], ));
		$this->view->form 	 = $form->setAction($this->view->url(array(), 'configure'));
		$this->view->message = implode('', $this->_helper->getHelper('FlashMessenger')->getMessages());
Extras_Config::getOption(Extras_Config::createMultidimensionalArray('test.test2.test3'), 'additionalParams', true);
		if( $this->getRequest()->isPost() === true ){
			if( $form->isValid($this->getRequest()->getPost()) === true ){
				$post = $form->getValues();

				if(empty($post['pwd'])){ unset($post['pwd']); }
				$qry->setParams($post);
				$qry->configureUpdateQry();

				$this->_helper->FlashMessenger('Cuenta editada el ' . date('Y-m-d g:i:s a'));
				$this->_redirect($this->view->url(array(), 'configure'));
			}
		}
	}
}