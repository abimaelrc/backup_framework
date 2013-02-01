<?php
class Extras_Session
{
	const NS = 'test';

	/**
     * @param $options array
     * @param $sessionExpire int
	 *     Default 1440 = 24 minutes
     * @return Zend_Session_Namespace
	 */
	public static function sessionNamespace(array $options = array(), $sessionExpire = 1440)
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

		$session = new Zend_Session_Namespace(self::NS);
		$session->setExpirationSeconds($sessionExpire);

		return $session;
	}

    /**
     * @return Zend_Auth_Storage_Session
     */
	public static function storageNamespace()
	{
		$session = new Zend_Auth_Storage_Session(self::NS);

		return $session;
	}
}