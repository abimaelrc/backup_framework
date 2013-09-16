<?php
class Chat_Model_QueriesAjax extends Qry_Queries
{
    public function addQry(array $values)
    {
        if (empty($values['chat']) === true) {
            return false;
        }

        foreach ($values as $k => $v) {
            $v          = urldecode($v);
            $v          = strip_tags($v);
            $v          = preg_replace("/(:?\r\n){2,}|\r{2,}|\n{2,}/", "\r\r", $v);
            $v          = trim($v);
            $values[$k] = $v;
        }

        $insertValues = array(
            'message'          => $values['chat'],
            'chat_type'        => $values['chat_type'],
            'created_by'       => $this->getSpecificUserInfo('users_id'),
            'created_datetime' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('chat', $insertValues);
    }

    public function countQry($type)
    {
        $dbQuery = 'SELECT COUNT(*) FROM chat WHERE chat_type = ' . $this->db->quote($type);

        return $this->db->fetchOne($dbQuery);
    }

    public function getChatMessagesQry($type)
    {
        $dbQuery = 'SELECT c.message, u.name created_by,
                    IF(DATE(c.created_datetime) = DATE(NOW()),
                       DATE_FORMAT(c.created_datetime, "%h:%i %p"),
                       DATE_FORMAT(c.created_datetime, "%b %d, %Y %h:%i %p")
                    ) created_datetime
                    FROM chat c INNER JOIN users u ON u.users_id = c.created_by
                    WHERE chat_type = ' . $this->db->quote($type) . '
                    ORDER BY c.chat_id DESC
                    LIMIT 100';

        return $this->db->fetchAll($dbQuery);
    }

    public function addPrivateUserQry(array $values)
    {
        if (empty($values['chat_type']) === true || empty($values['users_id']) === true) {
            return false;
        }

        $dbQuery = 'SELECT COUNT(*) FROM chat_private_users
                    WHERE users_id = ' . $this->db->quote($values['users_id'])
                          . ' AND chat_type = ' . $this->db->quote($values['chat_type']);

        if ($this->db->fetchOne($dbQuery) == 0) {
            $values['leader'] = (empty($values['leader']) === true)
                              ? 0
                              : (int) $values['leader'];

            $insertValues     = array(
                'users_id'         => $values['users_id'],
                'chat_type'        => $values['chat_type'],
                'leader'           => $values['leader'],
                'created_datetime' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('chat_private_users', $insertValues);
        }
    }

    public function availableUsers()
    {
        $dbQuery = 'SELECT * FROM users
                    WHERE users_id != ' . $this->getSpecificUserInfo('users_id')
                          . ' AND users_id != 1 '
                          . ' AND (deleted_account IS NULL OR deleted_account = "")';

        return $this->db->fetchAll($dbQuery);
    }

    public function closeQry(array $values)
    {
        $updateValues = array(
            'active'          => 0,
            'closed_datetime' => date('Y-m-d H:i:s'),
        );
        $updateWhere = ' chat_type = ' . $this->db->quote($values['chat_type'])
                     . ' AND users_id = ' . $this->db->quote($this->getSpecificUserInfo('users_id'));
        $this->db->update('chat_private_users', $updateValues, $updateWhere);
    }
}