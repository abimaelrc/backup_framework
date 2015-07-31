<?php
class Statistics_Model_Reports_ADemo
{
    public function __invoke(array $params = array())
    {
        $info    = '';
        $dbQuery = '';
        $info   .= Statistics_Model_Reports_Commons::getContent(array(), $params['db'], $dbQuery);
        return $info;
    }
}