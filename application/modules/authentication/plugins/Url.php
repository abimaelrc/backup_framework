<?php
class Authentication_Plugin_Url extends Zend_Controller_Plugin_Abstract
{
    private $router;
    private $db;
    private $delimiter;

    /**
     * @param string $delimiter
     */
    public function __construct($delimiter = '/')
    {
        $this->router    = Zend_Controller_Front::getInstance()->getRouter();
        $this->db        = Db_Db::conn();
        $this->delimiter = $delimiter;
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        foreach($this->db->fetchAll('SELECT * FROM urls') as $url){
            $params  = (empty($url['params']) === false)
                     ? ('/:' . implode('/:', explode($this->delimiter, trim($url['params']))))
                     : null;

            $resource = ($url['parent'] == 0)
                      ? ('/' . $url['module'])
                      : ('/' . $url['module'] . '/' . $url['controller'] . '/' . $url['action'] . $params);
            $route    = new Zend_Controller_Router_Route(
                $resource,
                array(
                   'module'     => $url['module'],
                   'controller' => $url['controller'],
                   'action'     => $url['action'],
                )
            );
            $this->router->addRoute($url['alias'], $route);
        }
    }
}