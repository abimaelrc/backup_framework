<?php
class Filter_Xss
{
    /**
     * Remove XSS attacks that came in the input
     *
     * Function taken from:
     * http://quickwired.com/smallprojects/php_xss_filter_function.php
     * and alter to use in application
     *
     * @param string $value The value to filter
     * @return string 
     */
    public function filterXss($params, $returnStr = false)
    {
        $params = (is_array($params) === true)
                ? $params
                : array($params);

        foreach ($params as $key => $val) {
            if (is_array($val) === false) {
                /**
                 * remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
                 * this prevents some character re-spacing such as <java\0script>
                 * note that you have to handle splits with \n, \r, and \t later since
                 * they *are* allowed in some inputs
                 */
                $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);

                /**
                 * straight replacements, the user should never need these since they're normal characters
                 * this prevents like 
                 * <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
                 */
                $search = 'abcdefghijklmnopqrstuvwxyz';
                $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $search .= '1234567890!@#$%^&*()';
                $search .= '~`";:?+/={}[]-_|\'\\';
                for($i = 0; $i < strlen($search); $i++){
                    /**
                     * ;? matches the ;, which is optional
                     * 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
                     */

                    /**
                     * &#x0040 @ search for the hex values
                     */
                    $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
                    /**
                     * &#00064 @ 0{0,7} matches '0' zero to seven times
                     */
                    $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
                }

                /**
                 * now the only remaining whitespace attacks are \t, \n, and \r
                 */
                $ra1 = array(
                    'javascript', 'vbscript', 'expression', 'applet', 'meta'  , 'xml'  , 'blink'   , 'link',
                    'style'     , 'script'  , 'embed'     , 'object', 'iframe', 'frame', 'frameset', 'ilayer',
                    'layer'     , 'bgsound' , 'title'     , 'base'
                );
                $ra2 = array(
                    'onabort'         , 'onactivate'        , 'onafterprint'      , 'onafterupdate'    , 'onbeforeactivate',
                    'onbeforecopy'    , 'onbeforecut'       , 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste',
                    'onbeforeprint'   , 'onbeforeunload'    , 'onbeforeupdate'    , 'onblur'           , 'onbounce',
                    'oncellchange'    , 'onchange'          , 'onclick'           , 'oncontextmenu'    , 'oncontrolselect',
                    'oncopy'          , 'oncut'             , 'ondataavailable'   , 'ondatasetchanged' , 'ondatasetcomplete',
                    'ondblclick'      , 'ondeactivate'      , 'ondrag'            , 'ondragend'        , 'ondragenter',
                    'ondragleave'     , 'ondragover'        , 'ondragstart'       , 'ondrop'           , 'onerror',
                    'onerrorupdate'   , 'onfilterchange'    , 'onfinish'          , 'onfocus'          , 'onfocusin',
                    'onfocusout'      , 'onhelp'            , 'onkeydown'         , 'onkeypress'       , 'onkeyup',
                    'onlayoutcomplete', 'onload'            , 'onlosecapture'     , 'onmousedown'      , 'onmouseenter',
                    'onmouseleave'    , 'onmousemove'       , 'onmouseout'        , 'onmouseover'      , 'onmouseup',
                    'onmousewheel'    , 'onmove'            , 'onmoveend'         , 'onmovestart'      , 'onpaste',
                    'onpropertychange', 'onreadystatechange', 'onreset'           , 'onresize'         , 'onresizeend',
                    'onresizestart'   , 'onrowenter'        , 'onrowexit'         , 'onrowsdelete'     , 'onrowsinserted',
                    'onscroll'        , 'onselect'          , 'onselectionchange' , 'onselectstart'    , 'onstart',
                    'onstop'          , 'onsubmit'          , 'onunload',
                );
                $ra = array_merge($ra1, $ra2);

                /**
                 * Keep replacing as long as the previous round replaced something
                 */
                $found = true;

                while ($found === true) {
                    $val_before = $val;
                    for($i = 0; $i < sizeof($ra); $i++){
                        $pattern = '/';
                        for($j = 0; $j < strlen($ra[$i]); $j++){
                            if($j > 0){
                                $pattern .= '(';
                                $pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
                                $pattern .= '|(&#0{0,8}([9][10][13]);?)?';
                                $pattern .= ')?';
                            }
                            $pattern .= $ra[$i][$j];
                        }
                        $pattern .= '/i';
                        $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
                        $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
                        if($val_before == $val){
                            /**
                             * no replacements were made, so exit the loop
                             */
                            $found = false;
                        }
                    }
                }
            }

            $params[$key] = (is_array($val) === true)
                          ? $this->filterXss($val)
                          : $val;
        }

        return ($returnStr === true)
            ? $params[0]
            : $params;
    }

    /**
     * Remove XSS attacks and remove tags and extra white spaces
     * that came in the input before and after
     * 
     * @param string|array $params The value to filter
     * @param bool Set this if you want to return string value
     * @return string|array
     */
    public function realEscapeString($params, $returnStr = false, $charset = 'UTF-8')
    {
        /**
         * Check for XSS atacks
         */
        $params = $this->filterXss($params, $returnStr);

        $params = (is_array($params) === true)
                ? $params
                : array($params);

        foreach ($params as $k => $v) {
            /**
             * Recursive, re-send all values that are arrays
             */
            if (is_array($v) === true) {
                $params[$k] = $this->realEscapeString($v, false, $charset);
                continue;
            }
            /**
             * Decode all hexadecimal values (urldecode and html_entity_decode)
             * Clean up all values (strip_tags)
             * Erase all white space before and after string (trim)
             */
            $params[$k] = urldecode($v);
            $params[$k] = html_entity_decode($params[$k], ENT_COMPAT, $charset);
            $params[$k] = strip_tags($params[$k]);
            $params[$k] = trim($params[$k]);
        }

        return ($returnStr === true)
            ? $params[0]
            : $params;
    }
}