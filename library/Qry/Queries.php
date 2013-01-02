<?php
class Qry_Queries
{
	protected $_db;
	protected $_params;
	protected $_auth;
	protected $_userInfo;
	protected $_message;

	public function __construct()
	{
		$this->_db 		 = Db_Db::conn();
		$this->_auth 	 = Zend_Auth::getInstance();
		$this->_userInfo = $this->getUserInfo();
	}

	public function setParams(array $params)
	{
		$this->_params = $params;
	}

	/**
	 * @param mixed $key
	 * @param mixed $defaultValue
	 * @param bool $overwriteEmptyValue
	 * @return mixed
	 */
	protected function _chkParam($key, $defaultValue = null, $overwriteEmptyValue = false)
	{
		return ( is_array($this->_params) && array_key_exists($key, $this->_params) && !empty($this->_params[$key])
				 || is_array($this->_params) && array_key_exists($key, $this->_params) && !$overwriteEmptyValue )
				? $this->_params[$key]
				: $defaultValue;
	}

	public function setAuth(Zend_Auth $auth)
	{
		$this->_auth = $auth;
	}

	public function setUserInfo(Zend_Auth $auth)
	{
		$this->_userInfo = $auth->getIdentity();
	}

	public function getUserInfo()
	{
		return $this->_auth->getIdentity();
	}

	public function getSpecificUserInfo($info)
	{
		return ( is_object($this->_userInfo) && array_key_exists($info, $this->_userInfo) )
			? $this->_userInfo->$info
			: null;
	}

	public function setMessage($message)
	{
		$this->_message .= $message;
	}

	public function getMessage()
	{
		return $this->_message;
	}

	protected function _filterXss($params, $returnStr = false, $realEscapeString = false){
		$valuesFilter = new Filter_Xss();
		return ( $realEscapeString === true )
			? $valuesFilter->realEscapeString($params, $returnStr)
			: $valuesFilter->filterXss($params, $returnStr);
	}
}