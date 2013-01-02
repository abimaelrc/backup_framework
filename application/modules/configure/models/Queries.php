<?php
class Configure_Model_Queries extends Qry_Queries
{
	private function _restoreUserQry()
	{
		$dbQuery 						= 'SELECT users_id FROM users WHERE num_empl = ' . $this->_db->quote($this->_chkParam('num_empl'));
		$usersId 						= $this->_db->fetchOne($dbQuery);

		$post 							= $this->_filterXss($this->_params, false, true);
		$post['pwd'] 					= crypt($post['pwd'], $this->_params['additionalParams']['salt']);
		$post['change_pwd'] 			= 1;
		$post['block_access'] 			= 0;
		$post['deleted_account'] 		= 0;
		$post['updated_by'] 			= $this->getSpecificUserInfo('users_id');
		$post['updated_datetime']		= date('Y-m-d H:i:s');
		$post['updated_by_remote_addr'] = Zend_Controller_Front::getInstance()->getRequest()->getServer('REMOTE_ADDR');
		unset($post['hashConfigureUsers']);

		$this->_db->update('users', $post, 'users_id = ' . $this->_db->quote($usersId));
		$this->setMessage('Cuenta restaurada. Ya existía una cuenta con ese número de empleado y había sido borrada anteriormente');
		return true;
	}




	public function getConfigureUserInfoQry()
	{
		return $this->_db->fetchRow('SELECT * FROM users WHERE users_id = ' . $this->_db->quote($this->getSpecificUserInfo('users_id')));
	}




	public function configureAddUsersQry()
	{
		$post = $this->_filterXss($this->_params);
		unset($post['hashConfigureUsers']);
		unset($post['additionalParams']);

		$dbQuery = 'SELECT COUNT(*) FROM users WHERE num_empl = ' . $this->_db->quote($post['num_empl']);
		if($this->_db->fetchOne($dbQuery) > 0){
			$dbQuery = 'SELECT deleted_account FROM users WHERE num_empl = ' . $this->_db->quote($post['num_empl']);
			if($this->_db->fetchOne($dbQuery) == 1){
				return $this->_restoreUserQry();
			}
			return false;
		}

		$post['created_by']				= $this->getSpecificUserInfo('users_id');
		$post['pwd'] 					= crypt($this->_chkParam('pwd'), $this->_params['additionalParams']['salt']);
		$post['created_datetime'] 		= date('Y-m-d H:i:s');
		$post['created_by_remote_addr'] = $_SERVER['REMOTE_ADDR'];
		$post['in_charge'] 				= ( empty($post['in_charge']) === false ) ? $post['in_charge'] : null;

		$this->_db->insert('users', $post);
		return true;
	}




	public function configureUpdateQry()
	{
		$post = $this->_filterXss($this->_params);
		unset($post['submit']);
		unset($post['confirmPwd']);
		unset($post['oldPwd']);
		unset($post['none']);
		unset($post['hashConfigure']);
		unset($post['additionalParams']);

		$post['change_pwd'] 			= 0;
		$post['updated_by'] 			= $this->getSpecificUserInfo('users_id');
		$post['updated_datetime'] 		= date('Y-m-d H:i:s');
		$post['updated_by_remote_addr'] = $_SERVER['REMOTE_ADDR'];
		if(array_key_exists('pwd', $post)){
			$post['pwd'] = crypt($post['pwd'], $this->_params['additionalParams']['salt']);
		}

		$this->_db->update('users', $post, 'users_id = ' . $this->_db->quote($post['updated_by']));

		$rowUserInfo = $this->_db->fetchRow('SELECT * FROM users WHERE users_id = ' . $this->_db->quote($post['updated_by']));

		$authAdapter = new Zend_Auth_Adapter_DbTable($this->_db);
		$authAdapter->setTableName('users')
					->setIdentityColumn('num_empl')
					->setCredentialColumn('pwd');
		$authAdapter->setIdentity($rowUserInfo['num_empl'])
					->setCredential($rowUserInfo['pwd']);
		$this->_auth->authenticate($authAdapter);
		$userInfo    = $authAdapter->getResultRowObject();
		$this->_auth->getStorage()->write($userInfo);
	}




	public function usersQry()
	{
		$dbQuery = 'SELECT u.*
					FROM users u INNER JOIN users_role r ON r.role = u.role
					WHERE ( u.deleted_account IS NULL OR u.deleted_account = 0 )
						  AND u.users_id != 1
					      AND u.users_id != ' . $this->_db->quote($this->getSpecificUserInfo('users_id'))
					. ' ORDER BY r.role_order, u.name';
		return $this->_db->fetchAll($dbQuery);
	}




	public function editUsersQry()
	{
		$usersId = filter_var($this->_chkParam('users_id'), FILTER_VALIDATE_INT)
			? $this->_chkParam('users_id')
			: null;

		if(is_null($usersId)){
			return false;
		}

		$dbQuery = 'SELECT * FROM users
					WHERE ( deleted_account IS NULL OR deleted_account = 0 )
						  AND users_id != 1
						  AND users_id != ' . $this->_db->quote($this->getSpecificUserInfo('users_id'))
						  . ' AND users_id = ' . $this->_db->quote($usersId);
		return $this->_db->fetchRow($dbQuery);
	}




	public function updateUserInfoQry()
	{
		$post = $this->_filterXss($this->_params, false, true);
		unset($post['hashConfigureUsersEdit']);
		unset($post['additionalParams']);

		foreach($post as $k => $v){
			if(empty($v) && filter_var($v, FILTER_VALIDATE_INT) === false){
				unset($post[$k]);
			}
		}

		if(!empty($post['pwd'])){
			$post['pwd'] = crypt($post['pwd'], $this->_params['additionalParams']['salt']);
		}
		$post['updated_by'] 				= $this->getSpecificUserInfo('users_id');
		$post['updated_datetime'] 			= date('Y-m-d H:i:s');
		$post['updated_by_remote_addr'] 	= Zend_Controller_Front::getInstance()->getRequest()->getServer('REMOTE_ADDR');
		$post['in_charge'] 				    = ( empty($post['in_charge']) === false ) ? $post['in_charge'] : null;

		if(!empty($post['block_access'])){
			$post['block_by'] 			  = $this->getSpecificUserInfo('users_id');
			$post['block_datetime'] 	  = date('Y-m-d H:i:s');
			$post['block_by_remote_addr'] = Zend_Controller_Front::getInstance()
													->getRequest()
													->getServer('REMOTE_ADDR');
		}

		$this->_db->update('users', $post, 'users_id = ' . $this->_db->quote($post['users_id']));
	}




	public function deleteUserQry()
	{
		$usersId 						= filter_var($this->_chkParam('users_id'), FILTER_VALIDATE_INT)
										  ? $this->_chkParam('users_id')
										  : 0;
		$post['deleted_account'] 		= 1;
		$post['deleted_by']				= $this->getSpecificUserInfo('users_id');
		$post['deleted_datetime'] 		= date('Y-m-d H:i:s');
		$post['deleted_by_remote_addr'] = Zend_Controller_Front::getInstance()->getRequest()->getServer('REMOTE_ADDR');

		$this->_db->update('users', $post, 'users_id = ' . $this->_db->quote($usersId));
	}
}