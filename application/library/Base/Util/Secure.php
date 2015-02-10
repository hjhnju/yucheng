<?php

class Base_Util_Secure {
    /**
     * 加密密码
     *
     * @param $strSecKey string 密钥
     * @param $strData string 加密的数据
     * @param int $intTm false则生成时间, 时间用于增加密码的随机性
     *
     * @return array('key'=>int, 'auth'=>'charn') 返回加密时使用的时间戳和加密后的密文
     */
    
    const PASSWD_KEY = 'fjwwjo123977!2318';   //对passwd加密的密钥  
    
    public static function encodeSand($strSecKey, $strData, $intTm = false) {
        
        $intTm = ($intTm ? $intTm : time());        
        $strBase64 = base64_encode($strData);
        $intDataLen = strlen($strBase64);
        $strMd5Part1 = md5($strSecKey . $strBase64 . $intTm);
        while ($intDataLen>strlen($strMd5Part1)) {
            $strMd5Part1 .= $strMd5Part1;
        }
        $strMd5Part1 = substr($strMd5Part1, 0, $intDataLen);
        $strMd5Part2 = md5($strSecKey . $strMd5Part1 . $intTm);
        $arrMd5Part2 = str_split($strMd5Part2);
        for ($i = 0;$i < $intDataLen;$i++) {
            $pos = ord($strMd5Part1[$i]);
            array_splice($arrMd5Part2, $pos % 32, 0, $strBase64[$i]);
        }
        $strMd5Part2 = implode('', $arrMd5Part2);
        $strAuth = $strMd5Part1 . $strMd5Part2;
        return array('key' => $intTm, 'auth' => trim($strAuth));
    }

    /**
     * 解密密码
     *  
     * @param $strSecKey string 密钥
     * @param $strEnc string 加密密码， 对应encodeSand返回值的auth字段
     * @param $intTm int 时间戳， 对应encodeSand返回值的key字段
     *
     * @return string/false 如果正确则返回解密后的结果，否则返回false
     */
    public static function decodeSand($strSecKey, $strEnc, $intTm) {
        $intAuthLen = strlen($strEnc);
        $intTmLen = ($intAuthLen - 32) / 2;
        $intPart1Len = $intTmLen;
        $intPart2Len = $intTmLen;
        $strMd5Part1 = substr($strEnc, 0, $intPart1Len);
        $strMd5Part2 = substr($strEnc, $intPart1Len);
        $arrMd5Part2 = str_split($strMd5Part2);
        
        //得到密码base64
        $strBase64 = '';
        for ($i = $intPart1Len - 1;$i >= 0;$i--) {
            $pos = ord($strMd5Part1[$i]) % 32;
            $strBase64 = $arrMd5Part2[$pos] . $strBase64;
            array_splice($arrMd5Part2, $pos, 1);
        }
        $strRawPwd = base64_decode($strBase64);
        $arrChk = self::encodeSand($strSecKey, $strRawPwd, $intTm);
        
        if (strcmp($arrChk['auth'], $strEnc) == 0) {
            return base64_decode($strBase64);
        }
        return false;
    }

    /**
     * uclogin内部使用的加密算法
     * 
     * @param string $strKey
     * @param string $strInit
     *
     * @return string $strEncoded
     */
    public static function encryptForUuap($strKey, $strInit) {
        $intBlockSize = mcrypt_get_block_size('des', 'ecb');
        $intPad = $intBlockSize - (strlen($strInit) % $intBlockSize);
        $strInput = $strInit . str_repeat(chr($intPad), $intPad);
        $objTd = mcrypt_module_open('des', '', 'ecb', '');
        $objIv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($objTd), MCRYPT_RAND);
        @mcrypt_generic_init($objTd, $strKey, $objIv);
        $strData = mcrypt_generic($objTd, $strInput);
        mcrypt_generic_deinit($objTd);
        mcrypt_module_close($objTd);
        $strData = base64_encode($strData);
        $strData = str_replace('/', '-', $strData);
        return $strData;
    }

    /**
     * uclogin内部使用的解密算法
     *
     * @param string $strKey
     * @param string $strEncoded
     *
     * @return string $strDecoded
     */
    public static function decryptForUuap($strKey, $strEncoded) {
        $strEncrypted = str_replace('/', '-', $strEncoded);
        $strEncrypted = base64_decode($strEncrypted);
        $objTd = mcrypt_module_open('des', '', 'ecb', '');
        $objIv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($objTd), MCRYPT_RAND);
        @mcrypt_generic_init($objTd, $strKey, $objIv); 
        $strDecrypted = mdecrypt_generic($objTd, $strEncrypted);
        mcrypt_generic_deinit($objTd);
        mcrypt_module_close($objTd);

        $intLen = strlen($strDecrypted);
        $intPad = ord($strDecrypted{$intLen - 1});
        if ($intPad > $intLen) {
            return false;
        }
        $intSpn = strspn($strDecrypted, chr($intPad), $intLen - $intPad);
        if ($intSpn != $intPad) {
            return false;
        }

        return substr($strDecrypted, 0, -1 * $intPad);
    }

    /** 
     * 32位int加密
     * @param int32 uid
     * @param int32 seed
     * @return int32
     */
    public static function digitEncode($intNum, $intSeed) {
        $sid = ($intNum & 0x0000ff00) << 16; 
        $sid += ($intNum & 0xff000000) >> 8;
        $sid += ($intNum & 0x000000ff) << 8;
        $sid += ($intNum & 0x00ff0000) >> 16; 
        $sid ^= $intSeed; 
        return $sid;
    }

    /** 
     * 32位int解密
     * @param int32 uid
     * @param int32 seed
     * @return int32
     */
    public static function digitDecode($intEncode, $intSeed) {
        $intEncode ^= $intSeed; 
        $sid = ($intEncode & 0x0000ff00) >> 8; 
        $sid += ($intEncode & 0xff000000) >> 16;
        $sid += ($intEncode & 0x000000ff) << 16;
        $sid += ($intEncode & 0x00ff0000) << 8; 
        return $sid;
    }

    /**
     *  对密码字符串进行加密
     *  @param string $strPasswd
     */
    public static  function encrypt($strPasswd){
        $ret = md5(self::PASSWD_KEY.$strPasswd);
        return $ret;
    }  
    
    /**
     * 隐藏资料中间的详细信息
     * 会得到类似于 学校 => 学***校 的转换结果
     * @param string $str
     * @return string
     */
    public static function hideDetail($str) {
        if (mb_strlen($str) > 1) {
            $first = mb_substr($str, 0, 1);
            $last = mb_substr($str, -1);
            $str = $first . str_repeat('*', 3) . $last;
        }
        return $str;
    }
}
