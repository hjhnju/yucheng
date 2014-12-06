<?php

/**
 * Cookie类，封装对Cookie的读写方法。
 */

class Base_Cookie {

    /**
     * 本地数据缓存
     * 
     * @var array
     */
    private $_arrData;

    /**
     * 构造函数
     */
    public function __construct() {/*{{{*/
        $this->_arrData = array();
    }/*}}}*/
    

    /**
     * 通用读取方法，优先从本地数组，其次从cookie。
     *
     * @param strKey string 
     * @return string
     */
    public function read($strKey) {/*{{{*/
        $strVal = empty($this->_arrData[$strKey]) ? '': $this->_arrData[$strKey];
        if ($strVal) {
            return $strVal;
        } else {
            if (empty($_COOKIE[$strKey])) {
                return '';
            }
            return $_COOKIE[$strKey];
        }
    }/*}}}*/

    /**
     * 通用设置方法，设置本地数组和cookie
     *
     * @param strKey string 
     * @param strVal string
     * @param strDomain string
     * @param strPath string
     * @param intExpire integer
     * @param bCookieSecure bool
     * @param bHttpOnly bool
     *
     * @return bool
     */
    public function write($strKey, $strVal, $intExpire, $strPath, $strDomain, $bCookieSecure, $bHttpOnly) {/*{{{*/
        $this->_arrData[$strKey] = $strVal;

        // 若COOKIE本来就是空的，则不发送清空cookie的response
        // 安全和节省带宽考虑
        if (empty($strVal) && empty($_COOKIE[$strKey])) {
            return true;
        }
         
        return $this->_setcookie($strKey, $strVal, $intExpire, $strPath, $strDomain, $bCookieSecure, $bHttpOnly);
    }/*}}}*/

    protected function _setcookie($strKey, $strVal, $intExpire, $strPath, $strDomain, $bCookieSecure, $bHttpOnly) {
        return setcookie($strKey, $strVal, $intExpire, $strPath, $strDomain, $bCookieSecure, $bHttpOnly);
    }
}
