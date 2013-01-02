<?php
class Application_Model_Url
{
	private $_router;
	private $_db;

	public function __construct()
	{
		$this->_router = Zend_Controller_Front::getInstance()->getRouter();
		$this->_db = Db_Db::conn();
	}

	public function fetchRouterRoute()
	{
		foreach($this->_db->fetchAll('SELECT * FROM urls') as $url){
			$params  = !empty($url['params'])
					   ? '/:' . implode('/:', explode('/', trim($url['params'])))
					   : null;

			$resource = ( $url['parent'] == 0 )
						? '/' . $url['module']
						: '/' . $url['module'] . '/' . $url['controller'] . '/' . $url['action'] . $params;
			$route 	  = new Zend_Controller_Router_Route( $resource,
														  array( 'module' => $url['module'],
																 'controller' => $url['controller'],
																 'action' => $url['action'], ));
			$this->_router->addRoute($url['alias'], $route);
		}
	}
}