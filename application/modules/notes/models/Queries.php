<?php
class Notes_Model_Queries extends Qry_Queries
{
	public function indexQry()
	{
		$form = array(
			'content' => mb_substr($this->_chkParam('notes'), 0, 3000, 'UTF-8'),
			'created_by' => $this->_userInfo->users_id,
			'created_datetime' => date('Y-m-d H:i:s')
		);

		/**
		 * Clean up variables in case of Sql-Injection
		 */
		$form = $this->_filterXss($form);

		$dbQuery = 'SELECT COUNT(*) counter FROM notes
					WHERE active = 1 AND (UNIX_TIMESTAMP(created_datetime) + 15) > "' . time() . '"';
		if($this->_db->fetchOne($dbQuery) > 0){
			return false;
		}

		$formUpdate = array(
			'active' => 0,
			'updated_by' => $this->_userInfo->users_id,
			'updated_datetime' => date('Y-m-d H:i:s')
		);
		$this->_db->update('notes', $formUpdate, 'active = 1');

		$this->_db->insert('notes', $form);

		return true;
	}




	/**
	 * @return bool | array
	 */
	public function getActiveNotesQry()
	{
		$dbQuery = 'SELECT n.*, u.name
					FROM notes n LEFT JOIN users u ON u.users_id = n.created_by
					WHERE n.active = 1
					ORDER BY n.created_datetime DESC';
		return $this->_db->fetchRow($dbQuery);
	}




	/**
	 * @return void
	 */
	public function setInactiveNotesQry()
	{
		$data = array(
			'active' => 0,
			'updated_by' => $this->_userInfo->users_id,
			'updated_datetime' => date('Y-m-d H:i:s')
		);
		$this->_db->update('notes', $data, 'active = 1');
	}
}