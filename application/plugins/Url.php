<?php
class Application_Plugin_Url extends Zend_Controller_Plugin_Abstract
{
	private $_router;
	private $_db;
	private $_delimiter;
 



	public function __construct($delimiter = '/')
    {
		$this->_router = Zend_Controller_Front::getInstance()->getRouter();
		$this->_db = Db_Db::conn();
		$this->_delimiter = $delimiter;
    }




	public function routeStartup(Zend_Controller_Request_Abstract $request)
	{
		foreach($this->_db->fetchAll('SELECT * FROM urls') as $url){
			$params  = ( empty($url['params']) === false )
					 ? ( '/:' . implode('/:', explode($this->_delimiter, trim($url['params']))) )
					 : null;

			$resource = ( $url['parent'] == 0 )
					  ? ( '/' . $url['module'] )
					  : ( '/' . $url['module'] . '/' . $url['controller'] . '/' . $url['action'] . $params );
			$route 	  = new Zend_Controller_Router_Route( $resource,
														  array( 'module' => $url['module'],
																 'controller' => $url['controller'],
																 'action' => $url['action'], ));
			$this->_router->addRoute($url['alias'], $route);
		}
	}
}