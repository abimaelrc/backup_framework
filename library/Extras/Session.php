<?php
class Extras_Session
{
	const NS = 'test';




	/**
	 * 3600 = 1 hour
	 */
	public static function sessionNamespace($sessionExpire = 3600)
	{
		$session = new Zend_Session_Namespace(self::NS);
		$session->setExpirationSeconds($sessionExpire);

		return $session;
	}




	public static function storageNamespace()
	{
		$session = new Zend_Auth_Storage_Session(self::NS);

		return $session;
	}
}