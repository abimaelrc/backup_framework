<?php
class Extras_Session
{
	const NS = 'test';




	public static function sessionNamespace()
	{
		$session = new Zend_Session_Namespace(self::NS);
		return $session;
	}




	public static function storageNamespace()
	{
		$session = new Zend_Auth_Storage_Session(self::NS);
		return $session;
	}
}