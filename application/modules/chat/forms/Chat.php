<?php
class Chat_Form_Chat extends Zend_Form
{
    private $createdBy = 0;

    protected function setCreatedBy ($createdBy = 0) {
        $this->createdBy = $createdBy;
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
         *                      created_by
         **************************/
        $createdBy = new Zend_Form_Element_Hidden('created_by');
        $createdBy->setDecorators(
                array(
                    'ViewHelper',
                )
            )
            ->setValue($this->createdBy)
            ->addFilter('StringTrim')
            ->addFilter('StripTags');
        $this->addElement($createdBy);

        /***************************
         *                      chat_type
         **************************/
        $chatType = new Zend_Form_Element_Hidden('chat_type');
        $chatType->setDecorators(
                array(
                    'ViewHelper',
                )
            )
            ->setValue('public')
            ->addFilter('StringTrim')
            ->addFilter('StripTags');
        $this->addElement($chatType);

        /***************************
         *                      chat
         **************************/
        $chat = new Zend_Form_Element_Textarea('chat');
        $chat->setDecorators(
                array(
                    'Errors',
                    'Label',
                    'ViewHelper',
                    array('HtmlTag', array('tag' => 'div',)),
                )
            )
            ->addFilter('StringTrim')
            ->addFilter('StripTags');
        $this->addElement($chat);

        /***************************
         *                      submit
         **************************/
        $submit = new Zend_Form_Element_Button('submit');
        $submit->setLabel('Añadir')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('HtmlTag', array('tag' => 'div', 'class' => 'paddingFormElement marginSubmit')),
                )
            )
            ->setOptions(array('class' => 'submit'));
        $this->addElement($submit);
    }
}