<?php
class Qry_Queries
{
	protected $db;
	protected $params;
	protected $auth;
	protected $userInfo;
	protected $message;

	public function __construct()
	{
		$this->db 		= Db_Db::conn();
		$this->auth 	= Zend_Auth::getInstance();
		$this->auth->setStorage(Extras_Session::storageNamespace());
		$this->userInfo = $this->getUserInfo();
	}

	public function setParams(array $params)
	{
		$this->params = $params;
	}

	/**
	 * @param mixed $key
	 * @param mixed $defaultValue
	 * @param bool $overwriteEmptyValue
	 * @return mixed
	 */
	protected function chkParam($key, $defaultValue = null, $overwriteEmptyValue = false)
	{
		return ( is_array($this->params) && array_key_exists($key, $this->params) && !empty($this->params[$key])
				 || is_array($this->params) && array_key_exists($key, $this->params) && !$overwriteEmptyValue )
				? $this->params[$key]
				: $defaultValue;
	}

	public function setAuth(Zendauth $auth)
	{
		$this->auth = $auth;
	}

	public function setUserInfo(Zendauth $auth)
	{
		$this->userInfo = $auth->getIdentity();
	}

	public function getUserInfo()
	{
		return $this->auth->getIdentity();
	}

	public function getSpecificUserInfo($info)
	{
		return ( is_object($this->userInfo) && array_key_exists($info, $this->userInfo) )
			? $this->userInfo->$info
			: null;
	}

	public function setMessage($message)
	{
		$this->message .= $message;
	}

	public function getMessage()
	{
		return $this->message;
	}

    public function csv($fileName)
    {
        Extras_Headers::csv($fileName);
    }

    public function xls($fileName)
    {
        Extras_Headers::xls($fileName);
    }

	protected function filterXss($params, $returnStr = false, $realEscapeString = false){
		$valuesFilter = new Filter_Xss();

		return ( $realEscapeString === true )
			? $valuesFilter->realEscapeString($params, $returnStr)
			: $valuesFilter->filterXss($params, $returnStr);
	}
}