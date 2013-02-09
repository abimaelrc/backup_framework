<?phpclass Statistics_Model_Validate_Validate extends Zend_Validate_Abstract{    const FROM_DATE_NOT_EMPTY         = 'fromDateNotEmpty';    const INCORRECT_FORMAT_FROM       = 'incorrectFormatFrom';    const TO_DATE_LESS_THAN_FROM_DATE = 'toDateLessThanFromDate';    const INCORRECT_FORMAT_TO         = 'incorrectFormatTo';    const VALUE_NOT_MATCH_LIST        = 'valueNotMatchList';    protected $_messageTemplates = array(        self::FROM_DATE_NOT_EMPTY         => 'Debes seleccionar una fecha en el campo "Desde"',        self::INCORRECT_FORMAT_FROM       => 'Error con formato de fecha en el campo "Desde", debe ser AAAA-MM-DD',        self::INCORRECT_FORMAT_TO         => 'Error con formato de fecha en el campo "Hasta", debe ser AAAA-MM-DD',        self::TO_DATE_LESS_THAN_FROM_DATE => 'Error con campo "Desde", no puede ser mayor al campo "Hasta"',        self::VALUE_NOT_MATCH_LIST        => 'Debes seleccionar de acuerdo a los empleados mencionados en el menú desplegable',    );    public function isValid($value)    {        /**         * Conection to database         */        $db = Db_Db::conn();        /**         * For return true or false, by default true if something is wrong will be change to false         */        $isValid = true;        /**         * Do not valid the form if from or type are empty         */        if (            empty($value['from']) === true            || $value['from'] == "0000-00-00"            || empty($value['type']) === true        ) {            $this->_error(self::FROM_DATE_NOT_EMPTY);            $isValid = false;        }        /**         * If from is not empty check if got the format YYYY-MM-DD         */        elseif (            empty($value['from']) === false            && preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $value['from']) === false            || empty($value['from']) === false            && $value['from'] == "0000-00-00"        ){            $this->_error(self::INCORRECT_FORMAT_FROM);            $isValid = false;        }        /**         * If to is not empty check if got the format YYYY-MM-DD         */        elseif (empty($value['to']) === false && preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $value['to']) === false) {            $this->_error(self::INCORRECT_FORMAT_TO);            $isValid = false;        }        /**         * Check if from is less than to         */        elseif (empty($value['from']) === false && empty($value['to']) === false) {            $date1 = new Zend_Date($value['from'], 'yyyy-MM-dd');            $date2 = new Zend_Date($value['to'], 'yyyy-MM-dd');            if ($date2->compare($date1) == -1) {                $this->_error(self::TO_DATE_LESS_THAN_FROM_DATE);                $isValid = false;            }        }        /**         * Check if the user exists in the database and only the users that the system display.         * Must be regular users and the account cannot be deleted         */        if (empty($value['users_id']) === false) {            $tmpValid = false;            $dbQuery = 'SELECT users_id FROM users                        WHERE (deleted_account = 0 OR deleted_account IS NULL) AND (role = "user" OR role = "supervisor")';            foreach( $db->fetchAll($dbQuery) as $v ){                if ($v['users_id'] == $value['users_id']) {                    $tmpValid = true;                    break;                }            }            if ($tmpValid === false) {                $this->_error(self::VALUE_NOT_MATCH_LIST);                $isValid = false;            }        }        return $isValid;    }}