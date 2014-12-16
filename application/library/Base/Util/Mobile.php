<?php
/**
 * 移动端相关工具类
 * @author hejunhua <hejunhua@baidu.com>
 * @since 2014-08-16
 */
class Base_Util_Mobile{

    /**
     * 判断是非是移动端
     * @return boolean
     */
    public function isMobile() { 
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        } 
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) { 
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        } 
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array (
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            ); 
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            } 
        } 
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) { 
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            } 
        } 
        return false;
    } 

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
