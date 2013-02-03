<?php
class Notes_Form_Notes extends Zend_Form
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
        $hash = new Zend_Form_Element_Hash('hashNotes');
        $hash->setSalt('notesAppClosing')
            ->setTimeout(Extras_Config::getOption('hashTimeout', 'additionalParams', true))
            ->setDecorators(
                array(
                    array('Errors', array('class'=>'errorsHash', 'escape'=>false)),
                    'ViewHelper',
                )
            );




        /***************************
         *                      notes
         **************************/
        $notes = new Zend_Form_Element_Textarea('notes');
        $notes->setLabel('Notas: <span id="counterNotesContent">3000</span>')
            ->setDecorators(
                array(
                    'Errors',
                    'ViewHelper',
                     array('HtmlTag', array('tag' => 'div', 'class' => 'paddingFormElement')),
                     array('Label', array('tag' => 'div', 'escape' => false)), 
                )
            )
            ->setOptions(array('cols' => 110, 'rows' => 15,))
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addFilter('StripTags');




        /***************************
         *                      submit
         **************************/
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Añadir')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('HtmlTag', array('tag' => 'div', 'class' => 'paddingFormElement marginSubmit')),
                )
            )
            ->setOptions(array('class' => 'submit'));




        /***************************
         *                      addElements
         **************************/
        $this->addElements(
            array(
                $hash,
                $notes
            )
        );




        /***************************
         *                      fieldset
         **************************/
        $this->addDisplayGroup(
            array('notes'),
            'noteInfo'
        );
        $this->getDisplayGroup('noteInfo')
            ->setDecorators(
                array(
                    'FormElements',
                    array('Fieldset', array('class'=>'fieldsetDisplayGroupEntireWidth')),
                    array('HtmlTag', array('tag'=>'div', 'class'=>'displayGroup')),
                )
            );




        /***************************
         *                      addElement
         **************************/
        $this->addElement($submit);
    }
}