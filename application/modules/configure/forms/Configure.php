<?php
class Configure_Form_Configure extends Zend_Form
{
	private $_name;
	private $_numEmpl;

	public function setName($name)
	{
		$this->_name = $name;
	}

	public function setNumEmpl($numEmpl)
	{
		$this->_numEmpl = $numEmpl;
	}

	public function init()
	{
		$n = 1;




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
		$hash = new Zend_Form_Element_Hash('hashConfigure');
		$hash->setSalt('configure')
			 ->setTimeout(1800)
			 ->setDecorators( array( array('Errors', array('class'=>'errorsHash', 'escape'=>false)),
									 'ViewHelper', ));




		/***************************
		 *                      oldPwd
		 **************************/
		$oldPwd = new Zend_Form_Element_Password('oldPwd');
		$oldPwd->setLabel('Contraseña Actual*:')
			   ->setDecorators( array( 'Errors',
									   'ViewHelper',
									   array('HtmlTag', array('tag' => 'div')),
									   array('Label', array('tag' => 'div')), ))
			   ->setOptions(array('size' => 30, 'maxlength' => 100))
			   ->setRequired(true)
			   ->addFilter('StringTrim')
			   ->addValidator(new Configure_Model_Validate_OldPwd());




		/***************************
		 *                      pwd
		 **************************/
		$pwd = new Zend_Form_Element_Password('pwd');
		$pwd->setLabel('Nueva Contraseña:')
			->setDecorators( array( 'Errors',
									'ViewHelper',
									array('HtmlTag', array('tag' => 'div')),
									array('Label', array('tag' => 'div')), ))
			->setOptions(array('size' => 30, 'maxlength' => 100))
			->addFilter('StringTrim')
			->addValidator(new Configure_Model_Validate_ConfirmPwd());




		/***************************
		 *                      confirmPwd
		 **************************/
		$confirmPwd = new Zend_Form_Element_Password('confirmPwd');
		$confirmPwd->setLabel('Confirmar Contraseña:')
				   ->setDecorators( array( 'Errors',
										   'ViewHelper',
										   array('HtmlTag', array('tag' => 'div')),
										   array('Label', array('tag' => 'div')), ))
				   ->setOptions(array('size' => 30, 'maxlength' => 100))
				   ->addFilter('StringTrim')
				   ->addValidator(new Configure_Model_Validate_ConfirmPwd());




		/***************************
		 *                      submit
		 **************************/
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Actualizar')
			   ->setDecorators( array( 'ViewHelper',
									   array('HtmlTag', array('tag' => 'div')), ))
			   ->setOptions(array('class' => 'submit'));




		/***************************
		 *                      addElement
		 **************************/
		$this->addElement('hidden', 'readonlyInfo', array( 'description' => '<span>' . $this->_name . '</span> - <span>' . $this->_numEmpl . '</span>',
														   'decorators' => array( array( 'Description',
																					   array( 'tag'=>'div',
																					          'class'=>'configureInfo',
																							  'escape'=>false, ))),
															'belongsTo' => 'none', ));




		/***************************
		 *                      addElements
		 **************************/
		$this->addElements( array( $hash,
								   $oldPwd,
								   $pwd,
								   $confirmPwd, ));




		/***************************
		 *                      fieldset
		 **************************/
		$this->addDisplayGroup( array( 'readonlyInfo',
									   'oldPwd',
									   'pwd',
									   'confirmPwd' ),
								'configureForm' );
		$this->getDisplayGroup('configureForm')
			 ->setDecorators( array( 'FormElements',
									 array( 'Fieldset', array( 'class'=>'fieldsetDisplayGroupEntireWidth' ) ), ));




		/***************************
		 *                      addElement
		 **************************/
		$this->addElement($submit);
	}
}