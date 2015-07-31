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

    

    public function verifyResetTokenPwdQry()
    {
        $dbQuery = 'SELECT users_id FROM users WHERE token_pwd = ' . $this->db->quote($this->chkParam('token_pwd'));
        return $this->db->fetchOne($dbQuery);
    }




    public function resetPwdQry()
    {
        $dbQuery = 'SELECT * FROM users
                    WHERE users_id = ' . $this->db->quote($this->chkParam('users_id'))
                          . ' AND token_pwd = ' . $this->db->quote($this->chkParam('token_pwd'));
        if (($row = $this->db->fetchRow($dbQuery)) === false) {
            return false;
        }

        $updateValues = array(
            'token_pwd'              => null,
            'pwd'                    => crypt($this->chkParam('pwd'), Extras_Config::getOption('salt', 'additionalParams', true)),
            'updated_by'             => $row['users_id'],
            'updated_datetime'       => date('Y-m-d H:i:s'),
            'deleted_account'        => null,
            'deleted_by'             => null,
            'deleted_datetime'       => null,
            'deleted_by_remote_addr' => null,
        );
        $this->db->update('users', $updateValues, ('users_id = ' . $row['users_id']));
        $this->setParams(
            array(
                'num_empl' => $row['num_empl'],
                'pwd'      => $this->chkParam('pwd'),
            )
        );

        return $this->loginQry();
    }




    public function configEmailQry($fromEmail = 'noreply-775desk@claropr.com', $fromName = '775Desk')
    {
        $smtp = new Zend_Mail_Transport_Smtp('10.0.113.211', array('port' => 25));
        Zend_Mail::setDefaultTransport($smtp);
        Zend_Mail::setDefaultFrom($fromEmail, $fromName);

        $mail = new Zend_Mail('UTF-8');
        $mail->addHeader('X-Priority', '1 (Higuest)')
            ->addHeader('X-MSMail-Priority', 'High')
            ->addHeader('Importance', 'High');

        return $mail;
    }




    public function sendResetPwdQry()
    {
        $dbQuery = 'SELECT * FROM users
                    WHERE num_empl = ' . $this->db->quote($this->chkParam('num_empl'));
        if (($row = $this->db->fetchRow($dbQuery)) === false) {
            $this->setMessage('No existe registro con para el número empleado ingresado. Consultar con supervisor.');
            return false;
        }

        if (empty($row['email']) === true) {
            $this->setMessage('No existe un email para el número empleado ingresado. Consultar con supervisor.');
            return false;
        }

        if (empty($row['block_access']) === false) {
            $this->setMessage('Su cuenta ha sido bloqueada o suspendida. Consultar con supervisor.');
            return false;
        }

        $tokenPwd     = md5($row['users_id'] . microtime(true));
        $updateValues = array(
            'token_pwd' => $tokenPwd,
        );
        $this->db->update('users', $updateValues, 'users_id = ' . $row['users_id']);

        $body = '<p>
                Hay una solicitud para restablecer su contraseña en 775Desk.
                Si no has solicitado esto, por favor ignóralo. Expirará en 24 horas. 
            </p>
            <p>
                Para restablecer su contraseña, por favor visita la siguiente página:<br />
                <a href="http://ccmdeskapp:8800/authentication/index/reset-pwd?token_pwd=' . $tokenPwd . '">'
                    . 'http://ccmdeskapp:8800/authentication/index/reset-pwd?token_pwd=' . $tokenPwd
                . '</a>
            </p>';

        $mail = $this->configEmailQry()
            ->addTo($row['email'])
            ->setSubject('Infomación para ingresar a 775Desk')
            ->setBodyHtml($body)
            ->send();
        $this->setMessage('Los detalles para ingresar al 775Desk se han enviado al correo electrónico: ' . $row['email']);
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