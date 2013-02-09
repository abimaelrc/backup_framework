<?php
class Qry_Queries
{
    /**
     * @var Zend_Db
     */
    protected $db;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var Zend_Auth
     */
    protected $auth;

    /**
     * Zend_Auth identity
     *
     * @var object
     */
    protected $userInfo;

    /**
     * @var array
     */
    protected $messages = array();

    public function __construct()
    {
        $this->db   = Db_Db::conn();
        $this->auth = Zend_Auth::getInstance();
        $this->auth->setStorage(Extras_Session::storageNamespace());
        $this->userInfo = $this->getUserInfo();
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @param boolean $overwriteEmptyValue
     * @return mixed
     */
    protected function chkParam($key, $defaultValue = null, $overwriteEmptyValue = false)
    {
        return (
            is_array($this->params) === true
            && array_key_exists($key, $this->params) === true
            && empty($this->params[$key]) === false
            || is_array($this->params) === true
            && array_key_exists($key, $this->params) === true
            && $overwriteEmptyValue === false
        )
            ? $this->params[$key]
            : $defaultValue;
    }

    /**
     * @param object $auth Zend_Auth
     */
    public function setAuth(Zend_Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param object $auth Zend_Auth
     */
    public function setUserInfo(Zend_Auth $auth)
    {
        $this->userInfo = $auth->getIdentity();
    }

    /**
     * @return object
     */
    public function getUserInfo()
    {
        return $this->auth->getIdentity();
    }

    /**
     * @param string $info
     * @return mixed
     */
    public function getSpecificUserInfo($info)
    {
        return (is_object($this->userInfo) && array_key_exists($info, $this->userInfo))
            ? $this->userInfo->$info
            : null;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->messages[] = $message;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param string $fileName
     */
    public function csv($fileName)
    {
        Extras_Headers::csv($fileName);
    }

    /**
     * @param string $fileName
     */
    public function xls($fileName)
    {
        Extras_Headers::xls($fileName);
    }

    /**
     * @param array|string $params
     * @param boolean $returnStr
     * @param boolean $realEscapeString
     * @return mixed
     */
    protected function filterXss($params, $returnStr = false, $realEscapeString = false){
        $valuesFilter = new Filter_Xss();

        return ( $realEscapeString === true )
            ? $valuesFilter->realEscapeString($params, $returnStr)
            : $valuesFilter->filterXss($params, $returnStr);
    }
}