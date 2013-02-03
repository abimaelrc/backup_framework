<?php
class Filter_MbStrToUpper implements Zend_Filter_Interface
{
    public function filter($value, $encoding = 'UTF-8')
    {
        return mb_strtoupper($value, $encoding);
    }
}