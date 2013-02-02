<?php
class Extras_Escape
{
    /**
     * @param string $value
     * @param string $encoding
     * @return string
     */
    public function escape($value, $encoding = 'UTF-8')
    {
        $value = urldecode($value);
        $value = html_entity_decode($value);
        $value = strip_tags($value);
        $value = htmlentities($value, ENT_QUOTES, $encoding);
        $value = trim($value);

        return $value;
    }
}