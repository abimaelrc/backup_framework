<?php
class Extras_Escape
{
	public function escape($string)
	{
		$string = urldecode($string);
		$string = html_entity_decode($string);
		$string = strip_tags($string);
		$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
		$string = trim($string);
		return $string;
	}
}