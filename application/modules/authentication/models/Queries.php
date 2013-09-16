<?php
class Authentication_Model_Queries extends Qry_Queries
{
    /**
     * @return bool
     */
    public function loginQry()
    {
        $authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
        $authAdapter->setTableName('users')
                    ->setIdentityColumn('num_empl')
                    ->setCredentialColumn('pwd');

        $authAdapter->setIdentity($this->chkParam('num_empl'))
                    ->setCredential(crypt($this->chkParam('pwd'), Extras_Config::getOption('salt', 'additionalParams', true)));

        $result = $this->auth->authenticate($authAdapter);

        if ($result->isValid() === true) {
            $userInfo = $authAdapter->getResultRowObject();
            $this->auth->getStorage()->write($userInfo);

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
        $authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
        $authAdapter->setTableName('users')
                    ->setIdentityColumn('num_empl')
                    ->setCredentialColumn('pwd');

        $authAdapter->setIdentity($this->chkParam('num_empl'))
                    ->setCredential($this->chkParam('pwd'));

        $result = $this->auth->authenticate($authAdapter);

        if($result->isValid() === true){
            $userInfo = $authAdapter->getResultRowObject();
            $this->auth->getStorage()->write($userInfo);

            return true;
        }

        return false;
    }




    /**
     * @return bool
     */
    public function currentAuthInfoQry_bck()
    {
        $dbQuery = 'SELECT * FROM users
                    WHERE num_empl = ' . $this->db->quote($this->chkParam('num_empl'))
                          . ' AND pwd = ' . $this->db->quote($this->chkParam('pwd'));
        $row     = $this->db->fetchRow($dbQuery);

        return ($row !== false) ? $row : false;
    }
}