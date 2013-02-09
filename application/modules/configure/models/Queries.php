<?php
class Configure_Model_Queries extends Qry_Queries
{
    /**
     * @return boolean
     */
    private function restoreUserQry()
    {
        $dbQuery                        = 'SELECT users_id FROM users WHERE num_empl = ' . $this->db->quote($this->chkParam('num_empl'));
        $usersId                        = $this->db->fetchOne($dbQuery);
        $post                           = $this->filterXss($this->params, false, true);
        $post['pwd']                    = crypt($post['pwd'], Extras_Config::getOption('salt', 'additionalParams', true));
        $post['change_pwd']             = 1;
        $post['block_access']           = 0;
        $post['deleted_account']        = 0;
        $post['updated_by']             = $this->getSpecificUserInfo('users_id');
        $post['updated_datetime']       = date('Y-m-d H:i:s');
        $post['updated_by_remote_addr'] = Zend_Controller_Front::getInstance()->getRequest()->getServer('REMOTE_ADDR');

        unset($post['hashConfigureUsers']);

        $this->db->update('users', $post, 'users_id = ' . $this->db->quote($usersId));
        $this->setMessage('Cuenta restaurada. Ya existía una cuenta con ese número de empleado y había sido borrada anteriormente');

        return true;
    }

    /**
     * @return array|boolean
     */
    public function getConfigureUserInfoQry()
    {
        return $this->db->fetchRow('SELECT * FROM users WHERE users_id = ' . $this->db->quote($this->getSpecificUserInfo('users_id')));
    }

    /**
     * @return boolean
     */
    public function configureAddUsersQry()
    {
        $post = $this->filterXss($this->params);

        unset($post['hashConfigureUsers']);

        $dbQuery = 'SELECT COUNT(*) FROM users WHERE num_empl = ' . $this->db->quote($post['num_empl']);
        if ($this->db->fetchOne($dbQuery) > 0) {
            $dbQuery = 'SELECT deleted_account FROM users WHERE num_empl = ' . $this->db->quote($post['num_empl']);

            if ($this->db->fetchOne($dbQuery) == 1) {
                return $this->restoreUserQry();
            }

            return false;
        }

        $post['created_by']             = $this->getSpecificUserInfo('users_id');
        $post['pwd']                    = crypt($this->chkParam('pwd'), Extras_Config::getOption('salt', 'additionalParams', true));
        $post['created_datetime']       = date('Y-m-d H:i:s');
        $post['created_by_remote_addr'] = $_SERVER['REMOTE_ADDR'];
        $post['in_charge']              = ( empty($post['in_charge']) === false ) ? $post['in_charge'] : null;

        $this->db->insert('users', $post);

        return true;
    }

    public function configureUpdateQry()
    {
        $post = $this->filterXss($this->params);
        unset($post['submit']);
        unset($post['confirmPwd']);
        unset($post['oldPwd']);
        unset($post['none']);
        unset($post['hashConfigure']);

        $post['change_pwd']             = 0;
        $post['updated_by']             = $this->getSpecificUserInfo('users_id');
        $post['updated_datetime']       = date('Y-m-d H:i:s');
        $post['updated_by_remote_addr'] = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('pwd', $post) === true) {
            $post['pwd'] = crypt($post['pwd'], Extras_Config::getOption('salt', 'additionalParams', true));
        }

        $this->db->update('users', $post, 'users_id = ' . $this->db->quote($post['updated_by']));

        $rowUserInfo = $this->db->fetchRow('SELECT * FROM users WHERE users_id = ' . $this->db->quote($post['updated_by']));

        $authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
        $authAdapter->setTableName('users')
                    ->setIdentityColumn('num_empl')
                    ->setCredentialColumn('pwd');
        $authAdapter->setIdentity($rowUserInfo['num_empl'])
                    ->setCredential($rowUserInfo['pwd']);
        $this->auth->authenticate($authAdapter);
        $userInfo    = $authAdapter->getResultRowObject();
        $this->auth->getStorage()->write($userInfo);
    }

    /**
     * @return array
     */
    public function usersQry()
    {
        $dbQuery = 'SELECT u.*
                    FROM users u INNER JOIN users_role r ON r.role = u.role
                    WHERE ( u.deleted_account IS NULL OR u.deleted_account = 0 )
                          AND u.users_id != 1
                          AND u.users_id != ' . $this->db->quote($this->getSpecificUserInfo('users_id'))
                    . ' ORDER BY r.role_order, u.name';
        return $this->db->fetchAll($dbQuery);
    }

    /**
     * @return array|boolean
     */
    public function editUsersQry()
    {
        $usersId = (filter_var($this->chkParam('users_id'), FILTER_VALIDATE_INT) !== false)
                 ? $this->chkParam('users_id')
                 : null;

        if (is_null($usersId) === true) {
            return false;
        }

        $dbQuery = 'SELECT * FROM users
                    WHERE ( deleted_account IS NULL OR deleted_account = 0 )
                          AND users_id != 1
                          AND users_id != ' . $this->db->quote($this->getSpecificUserInfo('users_id'))
                          . ' AND users_id = ' . $this->db->quote($usersId);
        return $this->db->fetchRow($dbQuery);
    }

    public function updateUserInfoQry()
    {
        $post = $this->filterXss($this->params, false, true);
        unset($post['hashConfigureUsersEdit']);

        foreach ($post as $k => $v) {
            if (empty($v) && filter_var($v, FILTER_VALIDATE_INT) === false) {
                unset($post[$k]);
            }
        }

        if (empty($post['pwd']) === false) {
            $post['pwd'] = crypt($post['pwd'], Extras_Config::getOption('salt', 'additionalParams', true));
        }

        $post['updated_by']             = $this->getSpecificUserInfo('users_id');
        $post['updated_datetime']       = date('Y-m-d H:i:s');
        $post['updated_by_remote_addr'] = Zend_Controller_Front::getInstance()->getRequest()->getServer('REMOTE_ADDR');
        $post['in_charge']              = ( empty($post['in_charge']) === false )
                                        ? $post['in_charge']
                                        : null;

        if (empty($post['block_access']) === false) {
            $post['block_by']             = $this->getSpecificUserInfo('users_id');
            $post['block_datetime']       = date('Y-m-d H:i:s');
            $post['block_by_remote_addr'] = Zend_Controller_Front::getInstance()->getRequest()->getServer('REMOTE_ADDR');
        }

        $this->db->update('users', $post, 'users_id = ' . $this->db->quote($post['users_id']));
    }

    public function deleteUserQry()
    {
        $usersId                        = filter_var($this->chkParam('users_id'), FILTER_VALIDATE_INT)
                                        ? $this->chkParam('users_id')
                                        : 0;
        $post['deleted_account']        = 1;
        $post['deleted_by']             = $this->getSpecificUserInfo('users_id');
        $post['deleted_datetime']       = date('Y-m-d H:i:s');
        $post['deleted_by_remote_addr'] = Zend_Controller_Front::getInstance()->getRequest()->getServer('REMOTE_ADDR');

        $this->db->update('users', $post, 'users_id = ' . $this->db->quote($usersId));
    }
}