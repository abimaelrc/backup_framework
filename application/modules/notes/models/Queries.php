<?php
class Notes_Model_Queries extends Qry_Queries
{
    /**
     * @return boolean
     */
    public function indexQry()
    {
        $form = array(
            'content' => mb_substr($this->chkParam('notes'), 0, 3000, 'UTF-8'),
            'created_by' => $this->userInfo->users_id,
            'created_datetime' => date('Y-m-d H:i:s')
        );

        /**
         * Clean up variables in case of Sql-Injection
         */
        $form = $this->filterXss($form);

        $dbQuery = 'SELECT COUNT(*) counter FROM notes
                    WHERE active = 1 AND (UNIX_TIMESTAMP(created_datetime) + 15) > "' . time() . '"';
        if($this->db->fetchOne($dbQuery) > 0){
            return false;
        }

        $formUpdate = array(
            'active' => 0,
            'updated_by' => $this->userInfo->users_id,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->db->update('notes', $formUpdate, 'active = 1');

        $this->db->insert('notes', $form);

        return true;
    }




    /**
     * @return boolen|array
     */
    public function getActiveNotesQry()
    {
        $dbQuery = 'SELECT n.*, u.name
                    FROM notes n LEFT JOIN users u ON u.users_id = n.created_by
                    WHERE n.active = 1
                    ORDER BY n.created_datetime DESC';
        return $this->db->fetchRow($dbQuery);
    }
}