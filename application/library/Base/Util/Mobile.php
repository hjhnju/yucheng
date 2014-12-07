<?php
/**
 * 移动端相关工具类
 * @author hejunhua <hejunhua@baidu.com>
 * @since 2014-08-16
 */
class Base_Util_Mobile{

    /**
     * 判断用户手机的操作系统和版本
     *
     * @return array('mobileType'=>, 'version'=>)
     */
    public function getMobileOsVersion() {
        $arrData               = array();
        $arrData['mobileType'] = 'pc';
        $arrData['version']    = null;
        $useragent = trim(strtolower($_SERVER['HTTP_USER_AGENT']));
        if(strpos($useragent, 'iphone') || strpos($useragent, 'ipad')
            ||strpos($useragent, 'ipod') || strpos($useragent, 'itouch')) {
            $arrData['mobileType']   = 'ios';
            $strmatch                = "/iphone\sos\s([0-9]_[0-9]_[0-9])/i";
            preg_match($strmatch, $useragent, $match);
            if(count($match) > 0){
                $arrData['version']  = str_replace('_','.',$match[1]);
            }
        }else if (strpos($useragent, 'android') || (preg_match('/mqqbrowser/i', $useragent)
            && preg_match('/linux/i', $useragent))){
                $arrData['mobileType'] = "android";
                $strmatch              = "/android\s([0-9]\.[0-9]\.[0-9])/i";
                preg_match($strmatch, $useragent, $match);
                if(count($match) > 0){
                    $arrData['version']= $match[1];
                }
        }
        $arrData['version'] = empty( $arrData['version'] ) ? '0.0.0' : $arrData['version'];
        return $arrData;
    }

    /**
     * 直接唤起轻应用前判断框版本号
     * ios中版本号获取框版本号特别处理
     * @return string
     */
    public function getBaiduAppVersion() {
        $arrData               = array();
        $arrData['mobileType'] = 'pc';
        $arrData['baidubox']   = null;
        $arrData['version']    = null;
        $useragent = trim(strtolower($_SERVER['HTTP_USER_AGENT'])); 
        if(strpos($useragent, 'iphone') || strpos($useragent, 'ipad') 
            || strpos($useragent, 'ipod') || strpos($useragent, 'itouch')) {
            $arrData['mobileType'] = 'ios';
            $strmatch              = "/(baiduboxapp)\/([0-9])_([\d|\.]*)_(.*)/i";
            preg_match($strmatch, $useragent, $match);
            $arrData['baidubox']   = isset($match[1]) ? $match[1] : null;
            $arrData['version']    = isset($match[3]) ? strrev($match[3]) : null;
        } else if(strpos($useragent, 'android') || 
            (preg_match( '/mqqbrowser/i', $useragent) && preg_match('/linux/i', $useragent))){
            $arrData['mobileType'] = "android";
            $strmatch              = "/(baiduboxapp)\/([\d|.]*)/i";
            preg_match($strmatch, $useragent, $match);
            $arrData['baidubox']   = isset($match[1]) ? $match[1] : null;
            $arrData['version']    = isset($match[2]) ? $match[2] : null;
        }
        $arrData['baidubox'] = empty($arrData['baidubox']) ? 'no' : $arrData['baidubox'];
        $arrData['version']  = empty($arrData['version']) ? '0.0.0.0' : $arrData['version'];
        return $arrData;
    }

    /**
     * 获取top几节版本号
     * @param  [type] $strVersion [description]
     * @return [type]             [description]
     */
    public function getTopVersion($strVersion, $intTop) {
        $arrVer = explode('.', $strVersion);
        $arrVer = array_slice($arrVer, 0, $intTop);
        return implode('.', $arrVer);
    }
    
}
