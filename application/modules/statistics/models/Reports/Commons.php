<?php
class Statistics_Model_Reports_Commons
{
    public static function getContent(array $values = array(), $db = null, $dbQuery = null)
    {
        $content = null;
        $values = (!empty($values))
                ? $values
                : ((($db instanceof Zend_Db_Adapter_Pdo_Mysql || $db instanceof Zend_Db_Adapter_Pdo_Oci)
                     && !empty($db)
                     && !empty($dbQuery))
                    ? $db->fetchAll($dbQuery)
                    : array());

        if (!empty($values)) {
            foreach ($values as $k => $v) {
                /**
                 * Set titles
                 */
                if($k == 0){
                    $content .= '"' . implode('","', array_keys($v)) . '"' . PHP_EOL;
                }

                /**
                 * Delete all returned characters and change double quotes for single quotes
                 */
                foreach($v as $kk => $vv){
                    $v[$kk] = str_replace(array('"', "\n", "\r", PHP_EOL), array("'", '/'), $vv);
                }

                /**
                 * Set values
                 */
                $content .= '"' . implode('","', $v) . '"' . PHP_EOL;
            }
        }

        return $content;
    }
}