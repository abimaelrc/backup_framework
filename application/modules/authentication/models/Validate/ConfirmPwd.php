<?phpclass Authentication_Model_Validate_ConfirmPwd extends Zend_Validate_Abstract{	const MATCH_CONFIRM_PWD = 'matchConfirmPwd';	protected $_messageTemplates = array(		self::MATCH_CONFIRM_PWD => 'Error al confirmar la contraseña',	);	public function isValid($value, $context = null)	{		if(is_array($context)){			$bool = array();			if($context['pwd'] != $context['confirmPwd'])			{				$this->_error(self::MATCH_CONFIRM_PWD);				return false;			}		}		return true;	}}