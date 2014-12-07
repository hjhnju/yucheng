<?php 
/**
 * ip相关处理
 * @author hejunhua <hejunhua@baidu.com>
 * @since 2013-12-03
 */
class Base_Util_Ip{

    /**
     * 获取真实客户端Ip
     */
    public static function getClientIp(){
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        }else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            $ip = getenv("REMOTE_ADDR");
        }else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
            $ip = $_SERVER['REMOTE_ADDR'];
        }else {
            $ip = "unknown";
        }
        return $ip;
    }

    /**
     * 是否内网Ip
     * @param  [type]  $strIp [description]
     * @return boolean        [description]
     */
    public static function isInternal($strIp){
        $ip = ip2long($strIp); 
        //A类网预留ip的网络地址
        $net_a = ip2long('10.255.255.255') >> 24;
        //B类网预留ip的网络地址 
        $net_b = ip2long('172.31.255.255') >> 20; 
        //C类网预留ip的网络地址
        $net_c = ip2long('192.168.255.255') >> 16;

        return $ip >> 24 === $net_a || $ip >> 20 === $net_b || $ip >> 16 === $net_c; 
    }

    /**
     * 根据IP判断地域
     * @param  [type] $strIp [description]
     * @return string $strLocation, e.g.北京市
     */
    public static function getLocation($strIp){
        $url = sprintf('http://opendata.onlineb.bae.baidu.com/api.php?query=%s&resource_name=ip&format=json&ie=utf-8&tn=baidu', $strIp);
        $ret = Utils_Http::instance($url)->exec();
        $ret = iconv('gbk', 'utf-8', $ret);
        $ret = json_decode($ret, true);
        if($ret['status'] != '0'){
            return false;
        }
        $strLocation = isset($ret['data'][0]['location']) ? $ret['data'][0]['location'] : false;
        if ($strLocation) {
            $arrLoc = explode(' ', $strLocation);
            $strLocation = $arrLoc[0];
        }
        return $strLocation;
    }

}
