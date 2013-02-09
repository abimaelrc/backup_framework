<?php

class Extras_Encoding
{
    /**
     * Set encoding
     *
     * @param string $data
     * @param string $setEncoding Default: UTF-8//TRANSLIT
     * @return string
     */
    public static function setEncoding($data, $setEncoding = 'UTF-8//TRANSLIT')
    {
        if (function_exists('mb_detect_encoding') === false) {
            throw new BadFunctionCallException('mbstring library is not available');
        }

        $encoding = mb_detect_encoding($data);

        if ($encoding !== false && strpos($setEncoding, $encoding) === false) {
            $data = iconv($encoding, $setEncoding, trim($data));
        }

        return $data;
    }
}