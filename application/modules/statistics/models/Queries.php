<?php
class Statistics_Model_Queries extends Qry_Queries
{
    /**
     * @return array filename and data
     */
    public function indexQry()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(30);

        /**
         * Filter for XSS attacks
         */
        $values      = $this->filterXss($this->params, false, true);

        /**
         * Where clause for query
         */
        $where       = null;

        /**
         * Set user name and employee number
         */
        $nameNumEmpl = null;
        $user        = null;

        if (!empty($values['users_id'])) {
            $dbQuery     = 'SELECT name, num_empl FROM users WHERE users_id = ' . $this->db->quote($values['users_id']);
            $user        = $this->db->fetchRow($dbQuery);
            $nameNumEmpl = ' - ' . $user['name'] . '(' . $user['num_empl'] . ')';
        }

        /**
         * Set part of the header with the selected days and if from and to got the same date
         * dispaly only 1 time the date
         */
        $nameDates = (!empty($values['to']) && $values['to'] != $values['from'])
                   ? ($values['from'] . ' - ' . $values['to'])
                   : $values['from'];
        

        /**
         * Name of the employee at the beginning of the file if selected
         */
        $info = (empty($nameNumEmpl) === true) ? '' : ($nameNumEmpl . PHP_EOL);

        /**
         * Unset "to" if is empty or if is the same as from to create the correct query
         */
        if (empty($values['to']) || $values['from'] == $values['to']) {
            unset($values['to']);
        }

        /**
         * Set for specific or range dates and times
         */
        $dates     = (empty($values['to']))
                   ? (' BETWEEN ' . $this->db->quote($values['from']) . ' AND ' . $this->db->quote($values['from'] . ' 23:59:59'))
                   : (' BETWEEN ' . $this->db->quote($values['from']) . ' AND ' . $this->db->quote($values['to'] . ' 23:59:59'));
        $datesOra  = (empty($values['to']))
                   ? (" = to_date('" . $values['from'] . "', 'YYYY-MM-DD')")
                   : (" BETWEEN to_date('" . $values['from'] . "', 'YYYY-MM-DD') AND to_date('" . $values['to'] . "', 'YYYY-MM-DD')");
        $timeStart = intval($values['from_hour']);
        $timeEnd   = intval($values['to_hour']);

        /**
         * Set filename
         */
        $fileName  = str_replace(' ', '_', $values['type']) . '_'
                   . (( empty($values['to']) )
                       ? $values['from']
                       : ($values['from'] . '_' . $values['to']))
                   . ((!empty($user))
                       ? str_replace(' ', '_', ($user['name'] . '_' . $user['num_empl']))
                       : null);

        $params              = $values;
        $params['db']        = $this->db;
        $params['dates']     = $dates;
        $params['datesOra']  = $datesOra;
        $params['timeStart'] = $timeStart;
        $params['timeEnd']   = $timeEnd;
        $className           = 'Statistics_Model_Reports_' . $values['type'];

        if (class_exists($className) === true) {
            $class = new $className();
            /**
             * Class must have __invoke
             */
            if (is_callable($class) === true) {
                $info .= $class($params);
            } else {
                return array('fileName' => '', 'info' => '',);
            }
        } else {
            return array('fileName' => '', 'info' => '',);
        }




        return array(
            'fileName' => $fileName,
            'info'     => $info,
        );
    }









    public function getEmployeesQry()
    {
        $dbQuery = 'SELECT users_id, name, num_empl FROM users
                    WHERE ( deleted_account = 0 OR deleted_account IS NULL ) AND role = "user"
                    ORDER BY name';
        return $this->db->fetchAll($dbQuery);
    }
}