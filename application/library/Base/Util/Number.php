<?php
class Base_Util_Number {

    /**
     * 浮点数比较是否相等
     * @param  float  $f1
     * @param  float  $f2
     * @param  integer $precision
     * @return boolean
     */
    public static function floatIsEqual($f1,$f2,$precision = 2) { 
        $e = pow(10, $precision);  
        $i1 = intval(round($f1 * $e));  
        $i2 = intval(round($f2 * $e));  
        return ($i1 == $i2);  
    }

    /**
     * 浮点数比较是否大于
     * @param  float  $big
     * @param  float  $small
     * @param  integer $precision
     * @return boolean
     */
    public static function floatIsGtr($big,$small,$precision = 2) {   
        $e      = pow(10,$precision);  
        $ibig   = intval(round($big * $e));  
        $ismall = intval(round($small * $e));
        return ($ibig > $ismall);  
    }

    /**
     * 浮点数比较是否大于等于
     * @param  float  $big
     * @param  float  $small
     * @param  integer $precision
     * @return boolean
     */
    public static function floatIsGtre($big,$small,$precision = 2) {
        $e = pow(10,$precision);  
        $ibig   = intval(round($big * $e));  
        $ismall = intval(round($small * $e));
        return ($ibig >= $ismall);  
    }  

   /**
    * 千分位显示金额，默认显示小数点后两位
    * @param $amount
    * @param $degit, default 2
    */
    public static function tausendStyle($amount, $degit=2) {
        return number_format(strval($amount), $degit, '.', ',');
    }

    /**
     * 去除千分号
     */
    public static function rmTausendStyle($amount) {
        return str_replace(',', '', $amount);
    }

   /* 
    * MoXie (SysTem128@GMail.Com) 2010-6-30 17:53:57 
    *  
    * Copyright &copy; 2008-2010 Zoeey.Org . All rights are reserved. 
    * Code license: Apache License  Version 2.0 
    * http://www.apache.org/licenses/LICENSE-2.0.txt 
    * 
    * 返回一字符串，十进制 number 以 radix 进制的表示。 
    * @param dec       需要转换的数字 
    * @param toRadix    输出进制。当不在转换范围内时，此参数会被设定为 2，以便及时发现。 
    * @return    指定输出进制的数字 
    */ 
    public static function dec2Any($dec, $toRadix) { 
        $MIN_RADIX = 2; 
        $MAX_RADIX = 62; 
        $num62 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        if ($toRadix < $MIN_RADIX || $toRadix > $MAX_RADIX) { 
            $toRadix = 2; 
        } 
        if ($toRadix == 10) { 
            return $dec; 
        } 
        // -Long.MIN_VALUE 转换为 2 进制时长度为65 
        $buf = array(); 
        $charPos = 64; 
        $isNegative = $dec < 0; //(bccomp($dec, 0) < 0); 
        if (!$isNegative) { 
            $dec = -$dec; // bcsub(0, $dec); 
        } 

        while (bccomp($dec, -$toRadix) <= 0) { 
            $buf[$charPos--] = $num62[-bcmod($dec, $toRadix)]; 
            $dec = bcdiv($dec, $toRadix); 
        } 
        $buf[$charPos] = $num62[-$dec]; 
        if ($isNegative) { 
            $buf[--$charPos] = '-'; 
        } 
        $_any = ''; 
        for ($i = $charPos; $i < 65; $i++) { 
            $_any .= $buf[$i]; 
        } 
        return $_any; 
    } 

    /** 
    * 返回一字符串，包含 number 以 10 进制的表示。<br /> 
    * fromBase 只能在 2 和 62 之间（包括 2 和 62）。 
    * @param number    输入数字 
    * @param fromRadix    输入进制 
    * @return  十进制数字 
    */ 
    public static function any2Dec($number, $fromRadix) { 
        $num62 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $dec = 0; 
        $digitValue = 0; 
        $len = strlen($number) - 1; 
        for ($t = 0; $t <= $len; $t++) { 
            $digitValue = strpos($num62, $number[$t]); 
            $dec = bcadd(bcmul($dec, $fromRadix), $digitValue); 
        } 
        return $dec; 
    } 
}

