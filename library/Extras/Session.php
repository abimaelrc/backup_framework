<?php
class Extras_Session
{
    /**
     * @param array $options
     * @param int $sessionExpire Default 1440 = 24 minutes
     * @return Zend_Session_Namespace
     */
    public static function sessionNamespace(array $options = array(), $sessionExpire = 1440, $sessionNamespace = null)
    {
        Zend_Session::start();

        $sessionOptions = array();
        if (empty($options['production']['session']) === false) {
            $sessionOptions = $options['production']['session'];
            $sessionOptions['strict'] = (isset($sessionOptions['strict']) === true) ? (boolean)$sessionOptions['strict'] : false;

            /**
             * Setup the same gc_maxlifetime
             */
            if (empty($sessionOptions['gc_maxlifetime']) === false) {
                $sessionExpire = $sessionOptions['gc_maxlifetime'];
            }
        }
        Zend_Session::setOptions($sessionOptions);

        $sessionNamespace = (empty($sessionNamespace) === true)
                          ? Extras_Config::getOption('sessionNamespace', 'additionalParams', true)
                          : $sessionNamespace;
        $session = new Zend_Session_Namespace($sessionNamespace);
        $session->setExpirationSeconds($sessionExpire);

        return $session;
    }

    /**
     * @return Zend_Auth_Storage_Session
     */
    public static function storageNamespace($sessionNamespace = null)
    {
        $sessionNamespace = (empty($sessionNamespace) === true)
                          ? Extras_Config::getOption('sessionNamespace', 'additionalParams', true)
                          : $sessionNamespace;
        $session = new Zend_Auth_Storage_Session($sessionNamespace);

        return $session;
    }
}