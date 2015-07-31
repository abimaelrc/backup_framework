<?php
class Authentication_Form_SendResetPwd extends Zend_Form
{
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
         *                      num_empl
         **************************/
		$numEmpl = new Zend_Form_Element_Text('num_empl');
		$numEmpl->setLabel('Número empleado que usa para loguearse:')
			->setOptions(
                array(
                    'size' => 30
                )
            )
			->setRequired(true)
			->setDecorators(
				array(
					'Description',
					'Errors',
					'ViewHelper',
					'Label',
				)
			)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        $this->addElement($numEmpl);

        /***************************
         *                      submit
         **************************/
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Enviar')
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