<?php 
/**
 * url相关处理
 * @author hejunhua <hejunhua@baidu.com>
 * @since 2014-08-10
 */
class Base_Util_Url{

    /**
     * 获取baseurl
     * @param  string $url
     * e.g.  scheme://user:pass@host:port/path?query。
     * @return string
     * e.g.  scheme://user:pass@host:port/path
     */
    public static function baseUrl($url){
        $arrUrl = parse_url($url);
        $baseUrl = isset($arrUrl['scheme']) ? $arrUrl['scheme'] : 'http';
        $baseUrl .= '://';
        $baseUrl .= isset($arrUrl['user']) ? $arrUrl['user'] : '';
        $baseUrl .= isset($arrUrl['pass']) ? ':' . $arrUrl['pass'] : '';
        $baseUrl .= isset($arrUrl['user']) ? '@' : '';
        $baseUrl .= isset($arrUrl['host']) ? $arrUrl['host'] : '';
        $baseUrl .= isset($arrUrl['port']) ? ':' . $arrUrl['port'] : '';
        $baseUrl .= isset($arrUrl['path']) ? rtrim($arrUrl['path'],'/') : '';
        return $baseUrl;
    }

    /**
     * 获取主域
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    public static function getDomain($url){
        $arrUrl = parse_url($url);
        $strDomain = isset($arrUrl['host']) ? $arrUrl['host'] : '';
        return $strDomain;
    }

    /**
     * L_Util_Query 
     * Query词处理逻辑: 供检索侧和建库侧共同使用
     * @copyright Copyright (c) 2014 Baidu.com, Inc. All Rights Reserved
     * @author 雷果国<leiguoguo@baidu.com> 
     */

    // 检查是否URL的正则
    const PATTERN_ISURL = ';^(?:https?://)?[\w-]++(?:\.[\w-]++)++.*$;i';
    // 滤掉开始的http://的正则
    const PATTERN_STRIPHTTP = ';^https?://;i';

    // 变换的字符表
    public static $trTable = array(
        ' '  => '', // 半角空格, 水平制表, 回车, 换行
        "\t" => '', 
        "\r" => '', 
        "\n" => '', 
        '　' => ' ', // 全角符号 U+3000
        '！' => '!', // 全角符号 U+FF01 - U+FF10
        '＂' => '"', 
        '＃' => '#', 
        '＄' => '$', 
        '％' => '%', 
        '＆' => '&', 
        '＇' => '\'', 
        '（' => '(', 
        '）' => ')', 
        '＊' => '*', 
        '＋' => '+', 
        '，' => ',', 
        '－' => '-', 
        '．' => '.', 
        '／' => '/', 
        '０' => '0', 
        '１' => '1', // 全角符号 U+FF11 - U+FF20
        '２' => '2', 
        '３' => '3', 
        '４' => '4', 
        '５' => '5', 
        '６' => '6', 
        '７' => '7', 
        '８' => '8', 
        '９' => '9', 
        '：' => ':', 
        '；' => ';', 
        '＜' => '<', 
        '＝' => '=', 
        '＞' => '>', 
        '？' => '?', 
        '＠' => '@', 
        'Ａ' => 'A', // 全角符号 U+FF21 - U+FF30
        'Ｂ' => 'B', 
        'Ｃ' => 'C', 
        'Ｄ' => 'D', 
        'Ｅ' => 'E', 
        'Ｆ' => 'F', 
        'Ｇ' => 'G', 
        'Ｈ' => 'H', 
        'Ｉ' => 'I', 
        'Ｊ' => 'J', 
        'Ｋ' => 'K', 
        'Ｌ' => 'L', 
        'Ｍ' => 'M', 
        'Ｎ' => 'N', 
        'Ｏ' => 'O', 
        'Ｐ' => 'P', 
        'Ｑ' => 'Q', // 全角符号 U+FF31 - U+FF40
        'Ｒ' => 'R', 
        'Ｓ' => 'S', 
        'Ｔ' => 'T', 
        'Ｕ' => 'U', 
        'Ｖ' => 'V', 
        'Ｗ' => 'W', 
        'Ｘ' => 'X', 
        'Ｙ' => 'Y', 
        'Ｚ' => 'Z', 
        '［' => '[', 
        '＼' => '\\', 
        '］' => ']', 
        '＾' => '^', 
        '＿' => '_', 
        '｀' => '`', 
        'ａ' => 'a', // 全角符号 U+FF41 - U+FF50
        'ｂ' => 'b', 
        'ｃ' => 'c', 
        'ｄ' => 'd', 
        'ｅ' => 'e', 
        'ｆ' => 'f', 
        'ｇ' => 'g', 
        'ｈ' => 'h', 
        'ｉ' => 'i', 
        'ｊ' => 'j', 
        'ｋ' => 'k', 
        'ｌ' => 'l', 
        'ｍ' => 'm', 
        'ｎ' => 'n', 
        'ｏ' => 'o', 
        'ｐ' => 'p', 
        'ｑ' => 'q', // 全角符号 U+FF51 - U+FF5E
        'ｒ' => 'r', 
        'ｓ' => 's', 
        'ｔ' => 't', 
        'ｕ' => 'u', 
        'ｖ' => 'v', 
        'ｗ' => 'w', 
        'ｘ' => 'x', 
        'ｙ' => 'y', 
        'ｚ' => 'z', 
        '｛' => '{', 
        '｜' => '|', 
        '｝' => '}', 
        '～' => '~', 
    );

    /**
     * queryTransform 
     * query词变换处理
     * @param string $query 变换前的query词
     * @static
     * @access public
     * @return string 变换后的query词
     */
    public static function queryTransform($query) {
        return strtr($query, self::$trTable);
    }

    /**
     * 转换小写
     * @param $data 原始数据
     * @return string 转换后数据
     */
    public static function getStrtolower($data){
        return strtolower($data);
    }

    /**
     * isUrl 
     * 检查输入是否是url
     * @param string $url 
     * @static
     * @access public
     * @return bool 是否url
     */
    public static function isUrl($url){
        return preg_match(self::PATTERN_ISURL, $url);
    }

    /**
     * 对url进行格式归一化 去掉http https
     * @param url url
     * @return 处理过的url
     */
    public static function stripHttp($url){
        if (self::isUrl($url)){
            return preg_replace(self::PATTERN_STRIPHTTP, '', $url);
        }else{
            return $url;
        }
    }

    /**
     * 归一化处理 全角半角 https http 大小写处理 空格
     * @param $data 原始数据
     * @return 处理后的数据
     */
    public static function normalizationData($data){
        return self::stripHttp(self::getStrtolower(self::queryTransform($data)));
    }


}
