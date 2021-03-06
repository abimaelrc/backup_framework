<?php
class Authentication_Model_Acl extends Zend_Acl
{
    public function __construct()
    {
        $db      = Db_Db::conn();
        $dbQuery = 'SELECT * FROM urls ORDER BY module, urls_id';

        foreach ($db->fetchAll($dbQuery) as $u) {
            if ($u['parent'] == $u['urls_id']) {
                //echo $u['module'] . '<br />';
                //echo $u['module'] . ':' . $u['controller'] . ':' . $u['action'] . ',' . $u['module'] . '<br />';
                $this->add(new Zend_Acl_Resource($u['module']));
                $this->add(new Zend_Acl_Resource($u['module'] . ':' . $u['controller'] . ':' . $u['action']), $u['module']);
            } else {
                //echo $u['module'] . ':' . $u['controller'] . ':' . $u['action'] . ',' . $u['module'] . '<br />';
                $this->add(new Zend_Acl_Resource($u['module'] . ':' . $u['controller'] . ':' . $u['action']), $u['module']);
            }
        }

        $this->addRole(new Zend_Acl_Role('guest'));
        $this->addRole(new Zend_Acl_Role('user'), 'guest');
        $this->addRole(new Zend_Acl_Role('supervisor'), 'user');
        $this->addRole(new Zend_Acl_Role('admin'));

        $this->allow(
            'guest',
            array(
                'authentication:index:index',
                'authentication:index:reset-pwd',
                'authentication:index:send-reset-pwd',
            )
        );
        $this->allow(
            'user',
            array(
                'default',
                'authentication',
                'configure:index:index',
            )
        );
        $this->deny('user', array('authentication:index:index',));

        $this->allow(
            'supervisor', 
            array(
                'statistics',
                'configure:index:index',
            )
        );

        $this->allow('admin');
        /**
         * Hide guide while is not been use
         */
        $this->deny(
            'admin',
            array(
                'authentication:index:index',
            )
        );
    }
}