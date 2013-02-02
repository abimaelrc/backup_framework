<?php
class Extras_DateTimes
{
    /**
     *
     */
    public static function getRelativeTimeFormat($datetime)
    {
        $date = new DateTime($datetime);
        $timestamp = $date->getTimestamp();
        $secs = time() - $timestamp;
        $mins = floor($secs / 60);
        $hours = floor($secs / 60 / 60);

        $formats = array('hora', 'minuto', 'segundos');
        $relativeFormat = ( $hours > 23 )
            ? $date->format('d M')
            : ( ( $hours > 0 )
                ? ( $hours . ' ' . $formats[0] . ($hours > 1 ? 's' : null) )
                : ( ( $mins > 0 )
                    ? ( $mins . ' ' . $formats[1] . ($mins > 1 ? 's' : null) )
                    : ( $secs . ' ' . $formats[2] )
                )
            );

        return $relativeFormat;
    }




    public static function aging(array $values = array())
    {
        $aging = null;

        if(array_key_exists('closed', $values) && $values['closed'] == 0){
            $datetime = new DateTime($values['created_datetime']);
            $aging = round((time() - $datetime->getTimestamp()) / 60 / 60 / 24);
        }
        elseif(array_key_exists('closed', $values) && $values['closed'] == 1){
            $datetime = new DateTime($values['created_datetime']);
            $datetimeUpdate = new DateTime($values['closed_datetime']);
            $aging = round(($datetimeUpdate->getTimestamp() - $datetime->getTimestamp()) / 60 / 60 / 24);
        }

        return $aging;
    }




    public static function agingFromNow($value)
    {
        $datetime = new DateTime($value);
        $datetimeNow = new DateTime();
        $interval = $datetimeNow->diff($datetime);
        return $interval->format('%a');
    }




    public static function getDt(array $values = array())
    {
        $dt = null;

        if(array_key_exists('created_datetime', $values)){
            $dt = new DateTime($values['created_datetime']);
            $dt = $dt->format('d/M/Y');
        }

        return $dt;
    }
}