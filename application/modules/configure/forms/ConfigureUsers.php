<?php
class Configure_Form_ConfigureUsers extends Zend_Form
{
    public function init()
    {
        /***************************
         *                      Db_Db::conn()
         **************************/
        $db = Db_Db::conn();




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
        $hash = new Zend_Form_Element_Hash('hashConfigureUsers');
        $hash->setSalt('configureUsers')
            ->setTimeout(Extras_Config::getOption('hashTimeout', 'additionalParams', true))
            ->setDecorators(
                array(
                    array('Errors', array('class'=>'errorsHash', 'escape'=>false)),
                    'ViewHelper',
                )
            );
        $this->addElement($hash);




        /***************************
         *                      name
         **************************/
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Nombre*')
             ->setDecorators(
                 array(
                     'Errors',
                     'ViewHelper',
                     array('Label', array('tag' => 'div')),
                     array('HtmlTag', array('tag' => 'div')),
                 )
            )
            ->setOptions(
                array(
                    'maxlength' => 100,
                )
            )
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addFilter(new Filter_MbStrToLower())
            ->addFilter(new Filter_UcWords())
            ->addValidator('regex', false,
                array(
                    'pattern'       => '/^[a-záéíóúü\s]+$/i',
                    'messages'      => array( 'regexInvalid' => "'%value%' solo se permiten valores alpha",
                    'regexNotMatch' => "'%value%' solo se permiten valores alpha", ),
                )
            );
        $this->addElement($name);




        /***************************
         *                      num_empl
         **************************/
        $numEmpl = new Zend_Form_Element_Text('num_empl');
        $numEmpl->setLabel('Número de Empleado*')
            ->setDecorators(
                array(
                    'Errors',
                    'ViewHelper',
                    array('Label', array('tag' => 'div')),
                    array('HtmlTag', array('tag' => 'div')),
                )
            )
            ->setOptions(
                array(
                    'maxlength' => 10
                )
            )
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addFilter(new Filter_MbStrToUpper())
            ->addValidator('regex', false,
                array(
                    'pattern'       => '/^\w+$/i',
                    'messages'      => array( 'regexInvalid' => "'%value%' solo se permiten valores alphanuméricos",
                    'regexNotMatch' => "'%value%' solo se permiten valores alphanuméricos", ),
                )
            );
        $this->addElement($numEmpl);




        /***************************
         *                      pwd
         **************************/
        $pwd = new Zend_Form_Element_Text('pwd');
        $pwd->setLabel('Contraseña')
            ->setDecorators(
                array(
                    'Errors',
                    'ViewHelper',
                    array('Label', array('tag' => 'div')),
                    array('HtmlTag', array('tag' => 'div')),
                )
            )
            ->setOptions(
                array(
                    'maxlength' => 10
                )
            )
            ->setRequired(true)
            ->setValue('claro123')
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        $this->addElement($pwd);




        /***************************
         *                      email
         **************************/
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email:')
            ->setDecorators(
                array(
                    'Errors',
                    'ViewHelper',
                    array('HtmlTag', array('tag' => 'div')),
                    array('Label', array('tag' => 'div')),
                )
            )
            ->setOptions(
                array(
                    'maxlength' => 100
                )
            )
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('EmailAddress', false);
        $this->addElement($email);




        /***************************
         *                      role
         **************************/
        $role = new Zend_Form_Element_Select('role');
        $role->setLabel('Rol')
             ->setDecorators(
                 array(
                     'Errors',
                     'ViewHelper',
                     array('Label', array('tag' => 'div')),
                     array('HtmlTag', array('tag' => 'div')),
                 )
             )
             ->setRequired(true);
        $roleArray = array();
        $where     = (Zend_Auth::getInstance()->getStorage()->read()->role == 'admin')
                   ? ''
                   : ' WHERE role != "admin" ';
        $rows      = $db->fetchAll('SELECT * FROM users_role ' . $where . ' ORDER BY role_order DESC');
        foreach ($rows as $v) {
            $roleArray[$v['role']] = $v['role_name'];
        }
        $role->addMultiOptions($roleArray);
        $this->addElement($role);




        /***************************
         *                      in_charge
         **************************/
        $inCharge = new Zend_Form_Element_Select('in_charge');
        $inCharge->setLabel('Gerente/Supervisor')
            ->setDecorators(
                array(
                    'Errors',
                    'ViewHelper',
                    array('Label', array('tag' => 'div')),
                    array('HtmlTag', array('tag' => 'div')),
                )
            );
        $inChargeArray = array('' => '[ Seleciona Gerente/Supervisor ]');
        $dbQuery       = 'SELECT users_id, name FROM users
                          WHERE users_id != 1 AND ( role = "supervisor" or role = "admin" )
                          ORDER BY name';
        foreach ($db->fetchAll($dbQuery) as $v) {
            $inChargeArray[$v['users_id']] = $v['name'];
        }
        $inCharge->addMultiOptions($inChargeArray);
        $this->addElement($inCharge);




        /***************************
         *                      fieldset
         **************************/
        $this->addDisplayGroup(
            array('name', 'num_empl', 'pwd', 'email', 'role', 'in_charge'),
            'configureForm'
        );
        $this->getDisplayGroup('configureForm')
             ->setDecorators(
                 array(
                     'FormElements',
                     array('Fieldset', array('class'=>'fieldsetDisplayGroupEntireWidth')),
                 )
             );




        /***************************
         *                      submit
         **************************/
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Añadir')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('HtmlTag', array('tag' => 'div', 'class' => 'submit_div')),
                )
            )
            ->setOptions(array('class' => 'submit'));
        $this->addElement($submit);
    }
}