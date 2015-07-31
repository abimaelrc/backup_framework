<?php
class Authentication_Form_ResetPwd extends Zend_Form
{
    private $usersId;

	public function setUsersId($usersId)
	{
		$this->usersId = $usersId;
	}

	public function init()
	{
		/***************************
		 *                      form
		 **************************/
		$this->setMethod('post')
			->setAttrib('accept-charset', 'utf-8')
			->setDecorators(
				array(
					'FormElements',
					'Form',
				)
			);

		/***************************
		 *                      hash
		 **************************/
		$hash = new Zend_Form_Element_Hash('hash');
		$hash->setSalt('resetPwdeBulletin')
			->setTimeout(1800)
			->setDecorators(
				array(
					array('Errors', array('class'=>'errorsHash', 'escape'=>false)),
					'ViewHelper',
				)
			);
        $this->addElement($hash);

        /***************************
         *                      users_id
         **************************/
        $usersId = new Zend_Form_Element_Hidden('users_id');
        $usersId->setDecorators(
                array(
                    'ViewHelper',
                )
            )
            ->setValue($this->usersId);
        $this->addElement($usersId);

		/***************************
		 *                      pwd
		 **************************/
		$pwd = new Zend_Form_Element_Password('pwd');
		$pwd->setLabel('Nueva Contraseña:')
			->setDecorators(
				array(
					'Errors',
					'ViewHelper',
					array('HtmlTag', array('tag' => 'div')),
					array('Label', array('tag' => 'div')),
				)
			)
            ->setRequired(true)
			->setOptions(array('size' => 30, 'maxlength' => 100))
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
			->addValidator(new Authentication_Model_Validate_ConfirmPwd());
        $this->addElement($pwd);

		/***************************
		 *                      confirmPwd
		 **************************/
		$confirmPwd = new Zend_Form_Element_Password('confirmPwd');
		$confirmPwd->setLabel('Confirmar Contraseña:')
			->setDecorators(
				array(
					'Errors',
					'ViewHelper',
					array('HtmlTag', array('tag' => 'div')),
					array('Label', array('tag' => 'div')),
				)
			)
            ->setRequired(true)
			->setOptions(array('size' => 30, 'maxlength' => 100))
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
			->addValidator(new Authentication_Model_Validate_ConfirmPwd());
        $this->addElement($confirmPwd);

        /***************************
         *                      submit
         **************************/
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Actualizar')
			->setOptions(array('class' => 'submit'))
			->setDecorators(
				array(
					'ViewHelper',
					array('HtmlTag', array('tag'=>'div', 'class'=>'marginTop10px alignCenter')),
				)
			);
        $this->addElement($submit);
	}
}