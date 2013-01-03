<?php
class Db_Db
{
	public static function conn()
	{
		/**
		 * Db_Config::config() must be an array with the following data
		 *
		 * array( 'host' => 'localhost',
		 *        'port' => '3306',
		 *        'username' => '<USERNAME>',
		 *        'password' => '<PASSWORD>',
		 *        'dbname' => 'test',
		 *        'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8;'), )
		 */
		return new Zend_Db_Adapter_Pdo_Mysql(Db_Config::config());
	}
}