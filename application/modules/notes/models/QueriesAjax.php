<?php
class Notes_Model_QueriesAjax extends Qry_Queries
{
    public function setInactiveNotesQry()
    {
        $data = array(
            'active' => 0,
            'updated_by' => $this->userInfo->users_id,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->db->update('notes', $data, 'active = 1');
    }
}