<?php
class Filter_MbStrToLower implements Zend_Filter_Interface
{
    public function filter($value, $encoding = 'UTF-8')
    {
        return mb_strtolower($value, $encoding);
    }
}