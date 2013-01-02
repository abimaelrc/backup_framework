<?php
class Statistics_Model_Queries extends Qry_Queries
{
	/**
	 * @return string | null
	 */
	private function _getContent(array $values = array(), $db = null, $dbQuery = null, $unsetValues = array(), $encoding = 'ISO-8859-1')
	{
		$content   = null;
		$firstTime = true;
		$values    = ( ( !empty($values) )
				   ? $values
				   : ( ( ( $db instanceof Zend_Db_Adapter_Pdo_Mysql
					     || $db instanceof Zend_Db_Adapter_Pdo_Oci )
					   && !empty($db)
					   && !empty($dbQuery) )
				     ? $db->fetchAll($dbQuery)
				     : array() ));

		if( empty($values) === false ){
			foreach($values as $k => $v){
				if( empty($unsetValues) === false ){
					foreach($unsetValues as $key => $val){
						if( array_key_exists($val, $v) === true ){
							unset($v[$val]);
						}
					}
				}

				/**
				 * Set titles
				 */
				if( $firstTime === true ){
					$content .= '"' . implode('","', array_keys($v)) . '"' . PHP_EOL;
					$firstTime = false;
				}

				/**
				 * Delete all returned characters and double quote change it to single
				 */
				foreach($v as $k => $vv){
					$v[$k] = str_replace(array('"', PHP_EOL, "\n", "\r", ), array("'", '/'), $vv);
				}

				/**
				 * Set values
				 */
				$content .= '"' . implode('","', $v) . '"' . PHP_EOL;
			}
		}

		return $content;
	}




	/**
	 * Set encoding
	 *
	 * @return string
	 */
	private function _setEncoding($data, $setEncoding = 'UTF-8//TRANSLIT')
	{
		$encoding = mb_detect_encoding($data);

		$encoding = ( ( $encoding !== false ) ? $encoding : 'ISO-8859-1' );

		if( strpos($setEncoding, $encoding) === false ){
			$data = iconv($encoding, $setEncoding, trim($data));
		}

		return $data;
	}




	/**
	 * @return array filename and data
	 */
	public function indexQry()
	{
		/**
		 * Filenames
		 */
		$tabName     = array( 'test1' => 'Test 1', );

		/**
		 * Filter for XSS attacks
		 */
		$values      = $this->_filterXss($this->_params, false, true);

		/**
		 * if name of type do not exists just return empty values
		 */
		if( array_key_exists($values['type'], $tabName) === false ){
			return array( 'fileName' => '', 'info' => '', );
		}

		/**
		 * Set user name and employee number
		 */
		$nameNumEmpl = null;
		$user        = null;

		if( empty($values['users_id']) === false ){
			$dbQuery     = 'SELECT name, num_empl FROM users WHERE users_id = ' . $this->_db->quote($values['users_id']);
			$user        = $this->_db->fetchRow($dbQuery);
			$nameNumEmpl = ' - ' . $user['name'] . '(' . $user['num_empl'] . ')';
		}

		/**
		 * Set part of the header with the selected days and if from and to got the same date
		 * dispaly only 1 time the date
		 */
		$nameDates       = ( empty($values['to']) === false && $values['to'] != $values['from'] )
						 ? ( $values['from'] . ' - ' . $values['to'] )
						 : $values['from'];
		

		/**
		 * Name of the document at the beginning of the file
		 */
		$info = ( ( array_key_exists($values['type'], $tabName) === true )
			  ? $tabName[ $values['type'] ]
			  : null )
			  . ' ' . $nameNumEmpl . $nameDates . PHP_EOL;

		/**
		 * Unset "to" if is empty or if is the same as from to create the correct query
		 */
		if( empty($values['to']) === true || $values['from'] == $values['to'] ){
			unset($values['to']);
		}

		/**
		 * Set for specific or range dates and times
		 */
		$dates           = ( ( empty($values['to']) === true )
			             ? ( ' = ' . $this->_db->quote($values['from']) )
						 : ( ' BETWEEN ' . $this->_db->quote($values['from']) . ' AND ' . $this->_db->quote($values['to']) ));
		$timeStart 		 = intval($values['from_hour']);
		$timeEnd   		 = intval($values['to_hour']);

		/**
		 * Set filename
		 */
		$fileName 		 = str_replace(' ', '_', $tabName[ $values['type'] ]) . '_'
						 . ( ( empty($values['to']) === true )
						   ? $values['from']
						   : ( $values['from'] . '_' . $values['to'] ) )
						 . ( ( empty($user) === false )
						   ? str_replace(' ', '_', ( $user['name'] . '_' . $user['num_empl'] ) )
						   : null );









		/**
		 * Data for "Test 1"
		 */
		if( $values['type'] == 'test1' ){
			goto skip;

			$dbQuery = 'SELECT * FROM table
						WHERE DATE_FORMAT(created_datetime, "%Y-%m-%d") ' . $dates
						      . ' AND DATE_FORMAT(created_datetime, "%H") >= ' . $timeStart
						      . ' AND DATE_FORMAT(created_datetime, "%H") <= ' . $timeEnd
						      . ( !empty($values['users_id']) ? ( ' AND created_by = ' . $this->_db->quote($values['users_id']) ) : '' );
			$rows    = $this->_db->fetchAll($dbQuery);

			$info    .= $this->_getContent(array(), $this->_db, $rows);
		}









		skip:

		return array( 'fileName' => $fileName, 'info' => $this->_setEncoding($info, 'ISO-8859-1//TRANSLIT'), );
	}




	public function getEmployeesQry()
	{
		$dbQuery = 'SELECT users_id, name, num_empl FROM users
					WHERE ( deleted_account = 0 OR deleted_account IS NULL ) AND role = "user" 
					ORDER BY name';
		return $this->_db->fetchAll($dbQuery);
	}
}