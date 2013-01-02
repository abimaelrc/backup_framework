<?php
class Db_Db
{
	public static function conn()
	{
		return new Zend_Db_Adapter_Pdo_Mysql(Db_Config::config());
	}
}