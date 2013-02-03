<?php
class Notes_AjaxController extends Zend_Controller_Action
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

    public function setInactiveNotesAction()
    {
        $qry = new Notes_Model_QueriesAjax();
        $qry->setInactiveNotesQry();
    }
}