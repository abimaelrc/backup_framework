<?php
class Application_Plugin_Headers extends Zend_Controller_Plugin_Abstract
{
	protected $_options = array();
 



	public function __construct($options)
    {
		if( $options instanceof Zend_Config ) {
			$options = $options->toArray();
		}

		if( is_array($options) === false ) {
			throw new InvalidArgumentException('Options must be an array');
		}

		$this->setOptions($options);        
    }




	public function setOptions(array $options)
	{
		$this->_options = $options;
	}




	public function getOptions()
	{
		return $this->_options;
	}




	public function routeStartup(Zend_Controller_Request_Abstract $request)
	{
		$response = $this->getResponse();
		$opts 	  = $this->getOptions();
		$opts 	  = array_key_exists('headers', $opts) ? $opts['headers'] : array();
		foreach($opts as $k => $v){
			( $v == 'gmdate' )
				? $response->setHeader($k, gmdate('D, d M Y H:i:s') . ' GMT', true)
				: $response->setHeader($k, $v, true);
		}
		$this->setResponse($response);
	}
}