<?php

/**
 * HTTP请求，封装CURL
 *
 * 支持GET/POST请求
 * 
 * 可通过static setOption方法设置curl_setopt_array的各参数
 * 并提供常用option设置的封装：
 *  post
 *  timeout(连接超时时间)
 *  nossl
 *  auth
 *
 * 若网络交互出错，可通过errMsg/errno方法获取具体错误原因和错误码
 */
class Base_Network_Http {

    /**
     * 常量：失败后不再重连
     * @const
     */
    const CONNECT_ONCE = true;

    /**
     * curl可接受的配置
     * @var
     */
    protected static $_arrConf;

    /**
     * 请求服务器信息
     * @var array
     */
    protected $_arrServerInfor;

    /**
     * 当前连接使用的url，主要用于日志记录
     * @var string
     */
    protected $_strCurUrl; 

    /**
     * 连接服务器curl对象的句柄
     * @object 
     */
    protected $_objHandle;

    /**
     * 构造函数
     *
     * @param array $arrServers
     * @example
     *  $arrServers = array(
     *      'flag' => 0,
     *      'hosts' => array(
     *          '10.23.247.6:8608',
     *          '10.23.247.22:8608',
     *       ),
     *      'path' => '/services/LoginService.php',
     *      'remote' => array(
     *          'http://10.23.247.6:8608/services/LoginService.php',
     *          'http://10.23.247.22:8608/services/LoginService.php',
     *       ),
     *  );
     */
    protected function __construct($arrServers = null) {/*{{{*/
        if (isset($arrServers)) {
            $this->_arrServerInfor = $arrServers;
        } else {
            $this->_arrServerInfor = array();
        }

        $this->_objHandle = curl_init();
        curl_setopt($this->_objHandle, CURLOPT_RETURNTRANSFER, 1);
        if (isset(self::$_arrConf)){
            curl_setopt_array($this->_objHandle, self::$_arrConf);
        }
    }/*}}}*/

    /**
     * 析构函数
     *
     */
     public function __destruct() {/*{{{*/
        curl_close($this->_objHandle);
        unset($this->_objHandle);
        unset($this->_arrServerInfor);
    }/*}}}*/

    /**
     * __construct的封装，非单例
     * 便于串联多个调用
     *
     * @return Base_Network_Http
     */
    public static function instance($arrServers = null) {/*{{{*/
        return new self($arrServers);
    }/*}}}*/

    /**
     * 默认的设置，curl_setopt_array
     *
     * @param array $arrConf
     */
    public static function setOption(array $arrConf) {/*{{{*/
        self::$_arrConf = $arrConf;
    }/*}}}*/

    /**
     * 执行请求
     *
     * @param int $intMaxtry        重试次数; 传引用，返回为执行下标，从0开始。若多次调用，每次需初始化
     * @param bool $bolConnectOnce  连接成功后，若因为其他原因失败，不再重试
     *
     * @return string | false
     */
    public function exec($intMaxtry = 1, $bolConnectOnce = false) {/*{{{*/
        // 检查是否需要服务降级，一旦降级，则不再进行远程交互
        Base_Flag::isDegrade($this->_arrServerInfor);
        // 检查重试次数
        if ($intMaxtry <= 0){
            throw new Base_Exception_Runtime("Maxtry fail: $intMaxtry");
        }
        // 读取URL信息
        $strRet = false;
        $bolFromHosts = false;
        $arrHosts = null;
        if (array_key_exists('hosts', $this->_arrServerInfor)) {
            $arrHosts = &$this->_arrServerInfor['hosts'];
        }
        $strPath = null;
        if (array_key_exists('path', $this->_arrServerInfor)) {
            $strPath = $this->_arrServerInfor['path'];
        }
        $arrRemote = null;
        if (array_key_exists('remote', $this->_arrServerInfor)) {
            $arrRemote = &$this->_arrServerInfor['remote'];
        }
        for ($intRetry = 0; $intRetry < $intMaxtry; $intRetry++) {
            // 获取访问的URL
            if (is_array($arrHosts) && count($arrHosts) > 0) {
                $intIndex = array_rand($arrHosts);
                $this->_strCurUrl = $arrHosts[$intIndex] . $strPath;
                $bolFromHosts = true;
            } else if (is_array($arrRemote) && count($arrRemote) > 0) {
                $intIndex = array_rand($arrRemote);
                $this->_strCurUrl = $arrRemote[$intIndex];
                $bolFromHosts = true;
            } else {
                throw new Base_Exception_Runtime("url not init");
            }
            
            // 设置访问的URL
            curl_setopt($this->_objHandle, CURLOPT_URL, $this->_strCurUrl);
            // 开始计时
            Base_Util_Timer::start('http');
            // 执行调用
            $strRet = curl_exec($this->_objHandle);
            // 结束计时
            $intTime = Base_Util_Timer::stop("http");
            // 记录日志
            Base_Log::api('', '', $this->_strCurUrl, '', $intTime, '');
            // 访问成功后退出
            if ($strRet !== false) {
                break;
            }
            Base_Log::warn(array(
                'url' => $this->_strCurUrl,
                'try' => $intRetry + 1,
                'msg' => $this->errno() . " : " . $this->errMsg(),
            ));
             
            if ($this->errno() <= CURLE_COULDNT_CONNECT || $this->errno() == 28) {
                /* 无法连接服务器 */
                if ($bolFromHosts) {
                    unset($arrHosts[$intIndex]);
                } else {
                    unset($arrRemote[$intIndex]);
                }

                // 若已没有可用host，不再重试
                if (count($arrHosts) <= 0 && count($arrRemote) <= 0) {
                    Base_Log::error(array(
                        'msg' => 'all host down'
                    ));
                    break;
                }
            } elseif($bolConnectOnce){
                /* 链接成功后其它错误，不再重试 */
                break;
            }
        }

        $intMaxtry = $intRetry;
        return $strRet;
    }/*}}}*/

    /**
     * 设置请求地址
     *
     * @param $mixHost  array | string，若为array，则exec时，会随机取host使用
     * @param $strPath
     * @param integer @intFlag
     *
     * 建议若传入单个host时，改为使用instance。
     *
     * @example
     * <code>
     *  $arrHost = array(
     *      '127.0.0.1:8000',
     *      '127.0.0.1:8001',
     *  );
     *  $strPath = '/services/LoginService.php';
     *  Base_Network_Http::instance()->url($arrHost, $strPath)->exec();
     * </code>
     *
     * <code>
     *  Base_Network_Http::instance()->url('http://10.23.247.6:8608', 
     *                                      '/services/LoginService.php')->exec();
     * </code>
     *
     * @return Base_Network_Http
     */
    public function url($mixHost, $strPath = '', $intFlag = 0) {/*{{{*/
        if(is_array($mixHost)){
            $this->_arrServerInfor['hosts'] = array_values($mixHost);
            $this->_arrServerInfor['path'] = $strPath;
            unset($this->_arrServerInfor['remote']);
        } else {
            $this->_arrServerInfor['remote'][0] = $mixHost . $strPath;
            unset($this->_arrServerInfor['hosts']);
            unset($this->_arrServerInfor['path']);
        }
        $this->_arrServerInfor['flag'] = $intFlag;
        return $this;
    }/*}}}*/

    /**
     * 设置POST参数
     *
     * @param $arrParam POST参数数组
     *
     * @return Base_Network_Http
     */
    public function post(Array $arrParam = null) {/*{{{*/
        if($arrParam){
            $strParam = http_build_query($arrParam, 'param_');
            curl_setopt($this->_objHandle, CURLOPT_POSTFIELDS, $strParam);
        }
        else{
            curl_setopt($this->_objHandle, CURLOPT_POST, true);
        }
        return $this;
    }/*}}}*/

    /**
     * 设置超时时间
     *
     * @param $mixTime int | float 单位：秒
     *
     * @return Base_Network_Http
     */
    public function timeout($mixTime) {/*{{{*/
        if(is_float($mixTime)){
            curl_setopt($this->_objHandle, CURLOPT_TIMEOUT_MS, (int) $mixTime * 1000);
        } else{
            curl_setopt($this->_objHandle, CURLOPT_TIMEOUT, (int) $mixTime);
        }
        return $this;
    }/*}}}*/

    /**
     * 取消SSL的设置
     *
     * @return Base_Network_Http
     */
     public function nossl() {/*{{{*/
        curl_setopt($this->_objHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->_objHandle, CURLOPT_SSL_VERIFYHOST, FALSE);
        return $this;
    }/*}}}*/

    /**
     * 设置HTTP基本认证
     *
     * @return Base_Network_Http
     */
    public function auth($strUname, $strPwd) {/*{{{*/
        curl_setopt($this->_objHandle, CURLOPT_USERPWD, "$strUname:$strPwd");
        return $this;
    }/*}}}*/

    /**
     * 读取错误消息
     *
     * @return string
     */
    public function errMsg() {/*{{{*/
        return curl_error($this->_objHandle);
    }/*}}}*/

    /**
     * 读取错误消息的代码
     *
     * @return int
     */
    public function errno() {/*{{{*/
        return curl_errno($this->_objHandle);
    }/*}}}*/
}
