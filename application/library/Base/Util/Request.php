<?php
/**
 * 对http请求参数进行过滤
 */
class Base_Util_Request {

    /**
    * 去除XSS（跨站脚本攻击）
    * @param string $var, 字符串参数，可能包含恶意的脚本代码如<script language="javascript">alert("hello world");</script>
    * @return string, 处理后的字符串
    * @Recoded By Androidyue
    **/
    public static function filterXss($var) {
       // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed  
       // this prevents some character re-spacing such as <java\0script>  
       // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs  
       $var = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $var);  
     
       // straight replacements, the user should never need these since they're normal characters  
       // this prevents like <IMG SRC=@avascript:alert('XSS')>  
       $search = 'abcdefghijklmnopqrstuvwxyz'; 
       $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';  
       $search .= '1234567890!@#$%^&*()'; 
       $search .= '~`";:?+/={}[]-_|\'\\'; 
       for ($i = 0; $i < strlen($search); $i++) { 
          // ;? matches the ;, which is optional 
          // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars 
     
          // @ @ search for the hex values 
          $var = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $var); // with a ; 
          // @ @ 0{0,7} matches '0' zero to seven times  
          $var = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $var); // with a ; 
       } 
     
       // now the only remaining whitespace attacks are \t, \n, and \r 
       $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base'); 
       $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'); 
       $ra = array_merge($ra1, $ra2); 
     
       $found = true; // keep replacing as long as the previous round replaced something 
       while ($found == true) { 
          $val_before = $var; 
          for ($i = 0; $i < sizeof($ra); $i++) { 
             $pattern = '/'; 
             for ($j = 0; $j < strlen($ra[$i]); $j++) { 
                if ($j > 0) { 
                   $pattern .= '(';  
                   $pattern .= '(&#[xX]0{0,8}([9ab]);)'; 
                   $pattern .= '|';  
                   $pattern .= '|(&#0{0,8}([9|10|13]);)'; 
                   $pattern .= ')*'; 
                } 
                $pattern .= $ra[$i][$j]; 
             } 
             $pattern .= '/i';  
             $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag  
             $var = preg_replace($pattern, $replacement, $var); // filter out the hex tags  
             if ($val_before == $var) {  
                // no replacements were made, so exit the loop  
                $found = false;  
             }  
          }  
       }  
       $var = htmlspecialchars($var);
       return $var;  
    } 

    /**
     * 过滤url参数
     * @param string $url
     * @param boolean $fixScheme, 无http开头是否补全
     * @return mixed urlstring|false
     */
    public static function filterUrl($url, $fixScheme=true) {
        //补充http schema
        if($fixScheme && !preg_match('/^(http|https|ftp):\/\//', $url)){
            $url = 'http://'.$url;
        }
        
        return $url;
    }

    /**
     * 过滤email
     * @param  string $email
     * @return mixed emailstring|false
     */
    public static function filterEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * 过滤SQL注入
     * @param  [type] $sql [description]
     * @return [type]      [description]
     */
    public static function filterSql($sql) {
        return mysql_escape_string($sql);
    }

    /**
     * 生成请求唯一ID
     * 可作为与其他系统通信时的唯一ID、日志ID等，唯一标识一次请求。
     *
     * @return unsigned int32 requestid
     */
    public static function getReqID() {/*{{{*/
        static $reqID;
        if (!$reqID && array_key_exists('UC_NGX_LOGID', $_SERVER)) {
            $reqID = $_SERVER['UC_NGX_LOGID'];
        }
        if (!$reqID){
            $arr = gettimeofday();
            $reqID = mt_rand(1, 9999)+(((($arr['sec']*100000 + $arr['usec']/10) & 0x7FFFFFFF) | 0x80000000) - 10000);
        }
        return $reqID;
    }/*}}}*/

}
