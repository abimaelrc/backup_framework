<?php
class Extras_Headers
{
    /**
     * @param string $fileName
     */
    public static function csv($fileName)
    {
        $fc = Zend_Controller_Front::getInstance();
        $fc->getResponse()
           ->setHeader('Expires', gmdate('D, d M Y H:i:s', strtotime('-1 week')) . ' GMT')
           ->setHeader('Last-Modified', gmdate('D, d M Y H:i:s', strtotime('-1 day')) . ' GMT')
           ->setHeader('ontent-Description', 'File Transfer')
           ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0')
           ->setHeader('Content-Type', 'application/octet-stream')
           ->setHeader('Pragma', 'no-cache')
           ->setHeader('Accept-Ranges', 'bytes')
           ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '.csv"');

        /**
         * IE header
         */
        if (isset($_SERVER['HTTP_USER_AGENT']) === true && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
            $fc->setHeader('Content-Type', 'application/force-download');
        }
    }




    /**
     * @source 8 This should work for browsers that are not IE || Opera
     * @param string $fileName
     */
    public static function xls($fileName)
    {
        $fc = Zend_Controller_Front::getInstance();
        $fc->getResponse()
           ->setHeader('Expires', gmdate('D, d M Y H:i:s', strtotime('-1 week')) . ' GMT')
           ->setHeader('Last-Modified', gmdate('D, d M Y H:i:s', strtotime('-1 day')) . ' GMT')
           ->setHeader('ontent-Description', 'File Transfer')
           ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0')
           ->setHeader('Content-Type', 'application/x-msexcel')
           ->setHeader('Pragma', 'no-cache')
           ->setHeader('Accept-Ranges', 'bytes')
           ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '.xls"');

        /**
         * IE | Opera header
         */
        if (
            isset($_SERVER['HTTP_USER_AGENT']) === true
            && (
                strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false 
                || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false
            )
        ) {
            $fc->setHeader('Content-Type', 'application/vnd.ms-excel');
        }
    }
}