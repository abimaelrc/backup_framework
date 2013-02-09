<?php
class Default_Model_Queries extends Qry_Queries
{
    /**
     * @return boolean|string
     */
    public function indexQry()
    {
		$dbQuery = 'SELECT n.content, u.name created_by, n.created_datetime, n.created_datetime altTime
                    FROM notes n INNER JOIN users u ON u.users_id = n.created_by
                    WHERE active = 1';
        $row = $this->db->fetchRow($dbQuery);

        if ($row !== false) {
            $row['altTime'] = Extras_DateTimes::getRelativeTimeFormat($row['altTime']);
        }

		return $row;
    }
}