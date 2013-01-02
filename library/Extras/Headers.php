<?php
class Extras_Headers
{
	public static function csv($fileName)
	{
		header('Expires: 0');   
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Description: File Transfer');
		header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0');
		header('Content-Type: application/octet-stream');
		if(isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
			header('Content-Type: application/force-download'); //IE HEADER
		}
		header('Pragma: no-cache'); 
		header('Accept-Ranges: bytes');
		header('Content-Disposition: attachment; filename="' . $fileName . '.csv"');
	}




	public static function xls($fileName)
	{
		header('Expires: 0');   
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Description: File Transfer');
		header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0');
		header('Content-Type: application/octet-stream');
		if(isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
			header('Content-Type: application/force-download'); //IE HEADER
		}
		header('Pragma: no-cache'); 
		header('Accept-Ranges: bytes');
		header('Content-Transfer-Encoding: none'); 
		header('Content-Type: application/vnd.ms-excel');                 // This should work for IE & Opera
		header('Content-Type: application/x-msexcel');                    // This should work for the rest
		header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
	}
}