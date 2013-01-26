<?php
class Configure_Form_ConfigureUsersEdit extends Zend_Form
{
	public function init()
	{
		/***************************
		 *                      form
		 **************************/
		$db = Db_Db::conn();




		/***************************
		 *                      form
		 **************************/
		$this->setMethod('post')
			 ->setAttrib('accept-charset', 'utf-8')
			 ->setDecorators( array( 'FormElements',
									 'Form', ));




		/***************************
		 *                      hash
		 **************************/
		$hash = new Zend_Form_Element_Hash('hashConfigureUsersEdit');
		$hash->setSalt('configureUsersEdit')
			 ->setTimeout(1800)
			 ->setDecorators( array( array('Errors', array('class'=>'errorsHash', 'escape'=>false)),
									 'ViewHelper', ));
		$this->addElement($hash);




		/***************************
		 *                      users_id
		 **************************/
		$usersId = new Zend_Form_Element_Hidden('users_id');
		$usersId->setRequired(true)
				->setDecorators(array('ViewHelper'))
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('Int');
		$this->addElement($usersId);




		/***************************
		 *                      name
		 **************************/
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Nombre:')
			 ->setDecorators( array( 'Errors',
									 'ViewHelper',
									 array('HtmlTag', array('tag' => 'div')),
									 array('Label', array('tag' => 'div')), ))
			->setOptions(array('size'=>30, 'maxlength'=>100))
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addFilter(new Filter_MbStrToLower())
			->addFilter(new Filter_UcWords())
			->addValidator('regex', false, array( 'pattern' => '/^[a-záéíóúü\s]+$/i',
												  'messages' => array( 'regexInvalid' => "'%value%' solo se permiten valores alpha",
																	   'regexNotMatch' => "'%value%' solo se permiten valores alpha", ), ));
		$this->addElement($name);




		/***************************
		 *                      num_empl
		 **************************/
		$numEmpl = new Zend_Form_Element_Text('num_empl');
		$numEmpl->setLabel('Número de Empleado:')
				->setDecorators( array( 'Errors',
										'ViewHelper',
										array('HtmlTag', array('tag' => 'div')),
										array('Label', array('tag' => 'div')), ))
				->setOptions(array('size'=>10, 'maxlength'=>10))
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addFilter(new Filter_MbStrToUpper())
				->addValidator('regex', false, array( 'pattern' => '/^\w+$/i',
													  'messages' => array( 'regexInvalid' => "'%value%' solo se permiten valores alphanuméricos",
																		   'regexNotMatch' => "'%value%' solo se permiten valores alphanuméricos", ), ))
				->addValidator(new Configure_Model_Validate_NumEmpl());
		$this->addElement($numEmpl);




		/***************************
		 *                      pwd
		 **************************/
		$pwd = new Zend_Form_Element_Text('pwd');
		$pwd->setLabel('Nueva Contraseña (opcional):')
			->setDecorators( array( 'Errors',
									'ViewHelper',
									array('HtmlTag', array('tag' => 'div')),
									array('Label', array('tag' => 'div')), ))
			->setOptions(array('size'=>10, 'maxlength'=>10))
			->addFilter('StripTags')
			->addFilter('StringTrim');
		$this->addElement($pwd);




		/***************************
		 *                      role
		 **************************/
		$role = new Zend_Form_Element_Select('role');
		$role->setLabel('Rol:')
			 ->setDecorators( array( 'Errors',
									 'ViewHelper',
									 array('HtmlTag', array('tag' => 'div')),
									 array('Label', array('tag' => 'div')), ))
			 ->setOptions(array('class'=>'width200px'))
			 ->setRequired(true);
		$roleArray = array();
		$where = ( Zend_Auth::getInstance()->getStorage()->read()->role == 'admin' )
				 ? ''
				 : ' WHERE role != "admin" ';
		$rows  = $db->fetchAll('SELECT * FROM users_role ' . $where . ' ORDER BY role_order DESC');
		foreach($rows as $v){
			$roleArray[$v['role']] = $v['role_name'];
		}
		$role->addMultiOptions($roleArray);
		$this->addElement($role);




		/***************************
		 *                      in_charge
		 **************************/
		$inCharge     = new Zend_Form_Element_Select('in_charge');
		$inCharge->setLabel('Supervisor:')
				 ->setDecorators( array( 'Errors',
										 'ViewHelper',
										 array('Label', array('tag' => 'div')),
										 array('HtmlTag', array('tag' => 'div')), ));
		$inChargeArray = array('' => '[ Seleciona supervisor ]');
		$dbQuery       = 'SELECT users_id, name FROM users
			WHERE users_id != 1 AND ( role = "supervisor" or role = "admin" )
			ORDER BY name';
		foreach($db->fetchAll($dbQuery) as $v){
			$inChargeArray[$v['users_id']] = $v['name'];
		}
		$inCharge->addMultiOptions($inChargeArray);
		$this->addElement($inCharge);




		/***************************
		 *                      change_pwd
		 **************************/
		$changePwd = new Zend_Form_Element_Checkbox('change_pwd');
		$changePwd->setLabel('Cambiar contraseña')
				  ->setDecorators( array( 'ViewHelper',
										  array('Label', array('placement' => 'append')),
										  array('HtmlTag', array('tag' => 'div')), ));
		$this->addElement($changePwd);




		/***************************
		 *                      block_access
		 **************************/
		$blockAccess = new Zend_Form_Element_Checkbox('block_access');
		$blockAccess->setLabel('Cuenta bloqueada')
					->setDecorators( array( 'ViewHelper',
											array('Label', array('placement' => 'append')),
											array('HtmlTag', array('tag' => 'div')), ));
		$this->addElement($blockAccess);




		/***************************
		 *                      fieldset
		 **************************/
		$this->addDisplayGroup( array( 'name'      , 'num_empl'    , 'pwd', 'role', 'in_charge',
		                               'change_pwd', 'block_access', ),
						        'configureForm' );
		$this->getDisplayGroup('configureForm')
			 ->setDecorators( array( 'FormElements',
									 array('Fieldset', array('class'=>'fieldsetDisplayGroupEntireWidth')), ));




		/***************************
		 *                      submit
		 **************************/
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Actualizar')
			   ->setDecorators( array( 'ViewHelper',
									   array('HtmlTag', array('tag' => 'div')), ))
			   ->setOptions(array('class' => 'submit'));
		$this->addElement($submit);
	}
}