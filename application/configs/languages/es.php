<?php
return array(  
    Zend_Validate_NotEmpty::IS_EMPTY                => 'El campo no puede estar vacío',
    Zend_Validate_Int::NOT_INT                      => "'%value%' no es numérico",
    Zend_Validate_Float::NOT_FLOAT                  => "'%value%' no es un valor numérico flotante",
    Zend_Validate_StringLength::TOO_LONG            => 'El campo debe contener por lo menos %min% caracteres',
    Zend_Validate_StringLength::TOO_SHORT           => 'El campo debe contener un máximo de %max% caracteres',
    Zend_Validate_EmailAddress::DOT_ATOM            => "'%localPart%' no concuerda con el formato de punto",
    Zend_Validate_EmailAddress::INVALID             => 'La dirección de correo no es válida, debe ser similar a usuario@clarotodo.com',
    Zend_Validate_EmailAddress::INVALID_FORMAT      => 'La dirección de correo no es válida, debe ser similar a usuario@clarotodo.com',
    Zend_Validate_EmailAddress::INVALID_HOSTNAME    => "'%hostname%' no es un nombre de dominio válido",
    Zend_Validate_Hostname::UNKNOWN_TLD             => "'%value%' parece ser un DSN hostname pero no se encuentra en la lista TLD conocida",
    Zend_Validate_Hostname::LOCAL_NAME_NOT_ALLOWED  => "'%value%' parece ser un nombre de conexión local pero no es permitido",
    Zend_Validate_Hostname::INVALID_HOSTNAME_SCHEMA => "'%value%' parece ser un DNS hostname pero no cumple con el esquema hostname TLD '%tld%'",
    Zend_Validate_Hostname::INVALID_LOCAL_NAME      => "'%value%' no parece ser un nombre correcto para una conexión local",
    Zend_Validate_EmailAddress::INVALID_LOCAL_PART  => "'%localPart%' no es una parte local válida",
    Zend_Validate_EmailAddress::INVALID_MX_RECORD   => "'%hostname%' no tiene un dominio de correo asignado",
    Zend_Validate_EmailAddress::INVALID_SEGMENT     => "'%hostname%' no esta en un segmento enrutable con la dirección de correo '%value%'",
    Zend_Validate_EmailAddress::QUOTED_STRING       => "'%localPart%' no concuerda con el formato de comillas",
    Zend_Validate_EmailAddress::LENGTH_EXCEEDED     => "'%value%' excedió el limite establecido",
    Zend_Validate_Regex::ERROROUS                   => 'Error al verificar valor, trate nuevamente',
    Zend_Validate_Identical::NOT_SAME               => 'Error al enviar formulario. Posibles razones: 
        <ul>
            <li>No haz usado el formularios por más de 30 minutos</li>
            <li>No puedes enviar los datos refrescando la ventana</li>
        </ul>',    
    Zend_Validate_Identical::MISSING_TOKEN          => 'Error al enviar formulario. Posibles razones: 
        <ul>
            <li>No haz usado el formularios por más de 30 minutos</li>
            <li>No puedes enviar los datos refrescando la ventana</li>
        </ul>',
);