<?php
class Default_Model_Queries extends Qry_Queries
{
    /**
     * @return boolean|string
     */
    public function indexQry()
    {
        $dbQuery = 'SELECT content FROM notes WHERE active = 1';

        return $this->db->fetchOne($dbQuery);
    }
}