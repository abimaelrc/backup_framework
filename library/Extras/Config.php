<?php

class Extras_Config
{
    /**
     * @param Zend_Config_Ini $options
     * @return array
     */
    public static function getOptionsToArray(Zend_Config_Ini $options)
    {
        return $options->toArray();
    }

    /**
     * @param array|string $keys
     * @param string $registryKey
     * @param boolean $returnFirstValue
     * @return mixed
     */
    public static function getOption($keys, $registryKey = 'additionalParams', $returnFirstValue = false)
    {
        $keys = (is_array($keys) === true) ? $keys : array($keys);
        $registry                = Zend_Registry::get($registryKey);
        $result                  = array();
        $getOptionArrayRecursive = function (array $keys, array $options) use (&$getOptionArrayRecursive) {
            $selectedValue = null;

            foreach ($keys as $key => $keyValue) {
                $key         = (is_array($keyValue) === true)
                             ? $key
                             : $keyValue;
                $optionValue = (array_key_exists($key, $options) === true)
                             ? $options[$key]
                             : null;
                if (is_array($optionValue) === true) {
                    $selectedValue = $getOptionArrayRecursive($keyValue, $optionValue);
                } else {
                    $selectedValue = $optionValue;
                }
            }

            return $selectedValue;
        };

        foreach ($keys as $key => $keyValue) {
            if (array_key_exists($key, $registry) === true && is_array($keyValue) === true) {
                $result[$key] = $getOptionArrayRecursive($keyValue, $registry[$key]);
            } elseif (array_key_exists($keyValue, $registry) === true && is_array($keyValue) === false) {
                $result[$keyValue] = $registry[$keyValue];
            }
        }

        if ($returnFirstValue === true) {
            $result = array_reverse($result);
            $result = array_pop($result);
        }

        return $result;
    }

    /**
     * First time created
     *
     * @param string $value
     * @param mixed $value
     * @param string $delimiter
     * @return array
     * @deprecated
     */
    /**
     * public function createMultidimensionalArray_DEPRECATE($keys, $value = null, $delimiter = '.')
     * {
     *   $keysArray  = explode($delimiter, $keys);
     *   $func       = function ($keys, $value) use (&$func) {
     *       $fixValue       = array();
     *       $keys           = array_reverse($keys);
     *       $key            = array_pop($keys);
     *       $keys           = array_reverse($keys);
     *       $fixValue[$key] = (empty($keys) === false)
     *                       ? $func($keys, $value)
     *                       : ((empty($value) === true) ? $key : $value);
     *
     *       return $fixValue;
     *   };
     *
     *   return $func($keysArray, $value);
     * }
     */

    /**
     * @author dashtrash http://www.forosdelweb.com/miembros/dashtrash/
     * @example http://www.forosdelweb.com/f18/aporte-crear-array-multidimensional-dinamicamente-cadena-1035238/
     * @param string $value
     * @param mixed $value
     * @param string $delimiter
     * @return array
     */
    public static function createMultidimensionalArray($keys, $value = null, $delimiter = '.')
    {
        $keysArrays = explode($delimiter, $keys);
        $result     = array();
        $tmp        =& $result;

        foreach ($keysArrays as $key) {
            $tmp =& $tmp[$key];
        }

        $tmp = (empty($value) === true) ? $key : $value;

        return $result;
    }
}