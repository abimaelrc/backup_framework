<?php
class Filter_UcWords implements Zend_Filter_Interface
{
	public function filter($value)
	{
		return ucwords($value);
	}
}