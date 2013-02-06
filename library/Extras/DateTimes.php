<?php
class Extras_DateTimes
{
    /**
     * @param string $datetime
     */
    public static function getRelativeTimeFormat($datetime)
    {
        $date    = new DateTime($datetime);
        $secs    = time() - $date->getTimestamp();
        $mins    = floor($secs / 60);
        $hours   = floor($secs / 60 / 60);
        $formats = array('hora', 'minuto', 'segundos');

        return ($hours > 23)
            ? $date->format('d M')
            : (($hours > 0)
              ? ($hours . ' ' . $formats[0] . ($hours > 1 ? 's' : null))
              : (( $mins > 0)
                ? ($mins . ' ' . $formats[1] . ($mins > 1 ? 's' : null))
                : ($secs . ' ' . $formats[2])
              )
            );
    }




    /**
     * @param string $datetime
     */
    public static function agingFromNow($datetime)
    {
        $date = new DateTime($datetime);
        $dateNow = new DateTime();
        $interval = $dateNow->diff($date);
        return $interval->format('%a');
    }




    /**
     * @param string $datetime
     * @param string $format
     */
    public static function getDt($datetime, $format = 'd/M/Y')
    {
        $date = new DateTime($datetime);
        $date = $date->format($format);
 
        return $date;
    }
}