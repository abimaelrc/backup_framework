<?php
class Configure_Form_Configure extends Zend_Form
{
    private $name;
    private $numEmpl;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setNumEmpl($numEmpl)
    {
        $this->numEmpl = $numEmpl;
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
         *                      addElement
         **************************/
        $this->addElement(
            'hidden',
            'readonlyInfo',
            array(
                'description' => ('<span>' . $this->name . '</span> - <span>' . $this->numEmpl . '</span>'),
                'decorators' => array(
                    array('Description', array('tag'=>'div', 'class'=>'configureInfo', 'escape'=>false,))
                ),
                'belongsTo' => 'none',
            )
        );




        /***************************
         *                      hash
         **************************/
        $hash = new Zend_Form_Element_Hash('hashConfigure');
        $hash->setSalt('configure')
             ->setTimeout(Extras_Config::getOption('hashTimeout', 'additionalParams', true))
             ->setDecorators(
                 array(
                     array('Errors', array('class'=>'errorsHash', 'escape'=>false)),
                     'ViewHelper',
                 )
             );
        $this->addElement($hash);




        /***************************
         *                      oldPwd
         **************************/
        $oldPwd = new Zend_Form_Element_Password('oldPwd');
        $oldPwd->setLabel('Contraseña Actual*:')
               ->setDecorators(
                   array(
                       'Errors',
                       'ViewHelper',
                       array('HtmlTag', array('tag' => 'div')),
                       array('Label', array('tag' => 'div')),
                   )
               )
               ->setOptions(array('size' => 30, 'maxlength' => 100))
               ->setRequired(true)
               ->addFilter('StringTrim')
               ->addValidator(new Configure_Model_Validate_OldPwd());
        $this->addElement($oldPwd);




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
            ->setOptions(array('size' => 30, 'maxlength' => 100))
            ->addFilter('StringTrim')
            ->addValidator(new Configure_Model_Validate_ConfirmPwd());
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
                   ->setOptions(array('size' => 30, 'maxlength' => 100))
                   ->addFilter('StringTrim')
                   ->addValidator(new Configure_Model_Validate_ConfirmPwd());
        $this->addElement($confirmPwd);




        /***************************
         *                      fieldset
         **************************/
        $this->addDisplayGroup(
            array(
                'readonlyInfo',
                'oldPwd',
                'pwd',
                'confirmPwd'
            ),
            'configureForm'
        );
        $this->getDisplayGroup('configureForm')
             ->setDecorators( array( 'FormElements',
                                     array( 'Fieldset', array( 'class'=>'fieldsetDisplayGroupEntireWidth' ) ), ));




        /***************************
         *                      submit
         **************************/
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Actualizar')
               ->setDecorators(
                   array(
                       'ViewHelper',
                       array('HtmlTag', array('tag' => 'div')),
                   )
               )
               ->setOptions(array('class' => 'submit'));
        $this->addElement($submit);
    }
}