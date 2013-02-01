<?php

class Extras_Config
{
    /**
     * Zend_Config_Ini object
     */
    private $config;

    /**
     * @param $iniPath string Path to ini
     */
    public function __construct($iniPath = null)
    {
        $iniPath      = (empty($iniPath) === true)
                      ? (APPLICATION_PATH . '/configs/application.ini')
                      : $iniPath;
        $this->config = new Zend_Config_Ini($iniPath);
    }

    /**
     * @return Zend_Config_Ini
     */
    public function getOptions()
    {
        return $this->config;
    }

    /**
     * @return array
     */
    public function getOptionsArray()
    {
        return $this->config->toArray();
    }

    /**
     * @param $key string
     * @return array
     */
    public function getOptionArray($key)
    {
        $options = $this->config->toArray();

        return (array_key_exists($key, $options) === true)
            ? $options[$key]
            : array();
    }

    /**
     * @param $needle string
     * @param $options array
     * @return string
     */
    public function getOptionArrayRecursive(
        array $needle,
        array $options = array()
    ) {
        $selectedValue = null;
        $options       = (empty($options) === true)
                       ? $this->config->toArray()
                       : $options;

        foreach ($needle as $key => $keyValue) {
            $key         = (is_array($keyValue) === true)
                         ? $key
                         : $keyValue;
            $optionValue = (array_key_exists($key, $options) === true)
                         ? $options[$key]
                         : array();
            if (is_array($keyValue) === true) {
                $selectedValue = self::getOptionArrayRecursive($keyValue, $optionValue);
            } else {
                $selectedValue = $optionValue;
            }
        }

        return $selectedValue;
    }

    /**
     * @param $value string
     */
    public function createMultidimensionalArray($value)
    {
        $valueArray = explode('.', $value);
        $func       = function ($values) use (&$func) {
            $fixValue       = array();
            $values         = array_reverse($values);
            $key            = array_pop($values);
            $values         = array_reverse($values);
            $fixValue[$key] = (empty($values) === false) ? $func($values) : $key;

            return $fixValue;
        };
        return $func($valueArray);
    }
}