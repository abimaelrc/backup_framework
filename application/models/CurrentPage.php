<?php
class Application_Model_CurrentPage
{
	public static function current($value, $defaultValue = null)
	{
		$class 	  = is_null($defaultValue)
					? array()
					: array($defaultValue);
		$front 	  = Zend_Controller_Front::getInstance();
		$request  = $front->getRequest();
		$resource = '/' . $request->getModuleName()
					. '/' . $request->getControllerName()
					. '/' . $request->getActionName();
		if($resource == $value){
			$class[] = 'current';
		}
		return !empty($class) ? 'class="' . implode(' ', $class) . '"' : null;
	}
}