<?php
class Authentication_Model_Queries extends Qry_Queries
{
	/**
	 * @return bool
	 */
	public function loginQry()
	{
		$authAdapter = new Zend_Auth_Adapter_DbTable($this->_db);
		$authAdapter->setTableName('users')
					->setIdentityColumn('num_empl')
					->setCredentialColumn('pwd');

		$authAdapter->setIdentity($this->_chkParam('num_empl'))
					->setCredential(crypt($this->_chkParam('pwd'), $this->_params['additionalParams']['salt']));

		$result = $this->_auth->authenticate($authAdapter);

		if($result->isValid()){
			$userInfo = $authAdapter->getResultRowObject();
			$this->_auth->getStorage()->write($userInfo);

			return true;
		}
		$this->setMessage('Usuario o contraseña incorrecta. Por favor trata nuevamente');

		return false;
	}




	/**
	 * @return bool
	 */
	public function currentAuthInfoQry()
	{
		$authAdapter = new Zend_Auth_Adapter_DbTable($this->_db);
		$authAdapter->setTableName('users')
					->setIdentityColumn('num_empl')
					->setCredentialColumn('pwd');

		$authAdapter->setIdentity($this->_chkParam('num_empl'))
					->setCredential($this->_chkParam('pwd'));

		$result = $this->_auth->authenticate($authAdapter);

		if($result->isValid()){
			$userInfo = $authAdapter->getResultRowObject();
			$this->_auth->getStorage()->write($userInfo);

			return true;
		}

		return false;
	}
}