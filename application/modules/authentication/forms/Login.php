<?php
class Authentication_Form_Login extends Zend_Form
{
	public function init()
	{
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
		$hash = new Zend_Form_Element_Hash('hashLogin');
		$hash->setSalt('login')
			->setTimeout(1800)
			->setDecorators( array( array('Errors', array('class'=>'errorsHash', 'escape'=>false)),
									'ViewHelper', ));




		/***************************
		 *                      num_empl
		 **************************/
		$numEmpl = new Zend_Form_Element_Text('num_empl');
		$numEmpl->setLabel('Número Empleado:')
			->setOptions(array('size' => 30))
			->setRequired(true)
			->setDecorators( array( 'Description',
									'Errors',
									'ViewHelper',
									'Label', ))
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addFilter(new Filter_MbStrToUpper());




		/***************************
		 *                      pwd
		 **************************/
		$pwd = new Zend_Form_Element_Password('pwd');
		$pwd->setLabel('Contraseña:')
			->setOptions(array( 'size' => 30, ))
			->setRequired(true)
			->setDecorators( array( 'Description',
									'Errors',
									'ViewHelper',
									'Label', ))
			->addFilter('StripTags')
			->addFilter('StringTrim');




		/***************************
		 *                      submit
		 **************************/
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Log In')
			->setOptions(array('class' => 'submit'))
			->setDecorators( array( 'ViewHelper',
									array('HtmlTag', array('tag'=>'div', 'class'=>'marginTop10px alignCenter')), ));




		/***************************
		 *                      addElements
		 **************************/
		$this->addElements(
			array(
				$hash,
				$numEmpl,
				$pwd,
				$submit
			)
		);
	}
}