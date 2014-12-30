<?php

/** 
 * 日志类。
 *
 * 隐式行为：
 *      1. 将调用者传入的strMsg中的tab替换为空格
 *      2. 在调用者传入的strMsg后添加换行符
 */

class Base_Log {

    /**
     * log级别
     *
     * @var integer
     */
    protected static $_intLevel;

    /**
     * log路径, eg. /home/work/var/xxx,会自动在后面加上日期
     *
     * @var string
     */
    protected static $_strLogDir;

    /**
     * 当前登录者ucid
     *
     * @var integer
     */
    protected static $_intUcid;

    /**
     * 日志内容file字段里忽略该字符串
     *
     * @var string
     */
    protected static $_strIgnorePath;


    /**
     * 写handler。
     * 
     * 可选值：_fwrite, _file_put_contents, _write_with_buf
     * 若多次写，且system open files不是瓶颈，_write_with_buf较好；
     * 若多次写，且system open files不是瓶颈，且较关注内存使用率，_fwrite较好；
     * 否则可使用_file_put_contents 
     * 
     * @var string
     */
    protected static $_strWriteHandler = '_fwrite'; 

    /**
     * log缓冲区
     * 
     * @var array
     */
    protected static $_arrBuf;

    /**
     * log缓冲区最大条数（不对内存使用进行限制，简单一些）
     *
     * @var integer
     */
    protected static $_intBufMaxNum;

    /**
     * 当前缓冲区数目
     *
     * @var integer
     */
    protected static $_intBufCnt = 0;

    /**
     * log目录是否存在
     * 
     * @var bool
     */
    protected static $_arrCheckDir = array();


    const DELEMITER = "\t";
    const EMPTYFILED = '-';

    const NONE = 0; // 不记录任何日志
    const ERROR = 1;
    const WARN  = 2;
    const NOTICE = 4;
    const DEBUG = 8;
    const ALL   = 15; // ERROR | WARN | NOTICE | DEBUG

    /**
     * 仅提供一个，配置的入口。
     *
     * 会抛出异常，在配置阶段，由应用层捕获
     *  
     * @param arrConfig array 配置项，kv结构
     * @return bool 是否设置成功
     *
     * @exsample
     * array(
     *  'level'=>Base_Log::ALL & ~Base_Log::DEBUG, // 线上关闭Debug
     *  'logdir'=>LOG_PATH,
     *  'writehandler' => '_write_with_buf',
     *  'ucid' => $intUcid,
     *  'ignorepath'=>dirname(dirname(__FILE__)).'/'),
     * );
     *
     */
    public static function setConfigs(array $arrConfig) {/*{{{*/
        if (array_key_exists('level', $arrConfig)) {
            if (!is_numeric($arrConfig['level'])) {
                throw new Base_Exception_Runtime("arrConfig['level'] error: use ".__CLASS__."::ALL or ::ERROR to set");
            }
            self::$_intLevel = $arrConfig['level'];
        } else {
            self::$_intLevel = self::ALL & ~self::DEBUG;
        }

        if (array_key_exists('logdir', $arrConfig)) {
            if (!is_string($arrConfig['logdir']) || strlen($arrConfig['logdir']) <= 3) {
                throw new Base_Exception_Runtime("arrConfig['logdir'] error, must be string and more than 3 letters");
            }
            self::$_strLogDir = $arrConfig['logdir'];
        } else {
                throw new Base_Exception_Runtime("arrConfig['logdir'] error, must be string and more than 3 letters");
        }

        $arrWriteHandlers = array(
                '_fwrite',
                '_file_put_contents',
                '_write_with_buf',
                );
        if (array_key_exists('writehandler', $arrConfig)) {
            if(!in_array($arrConfig['writehandler'], $arrWriteHandlers)) {
                throw new Base_Exception_Runtime("Write handler: ".implode(',', $arrWriteHandlers));
            }
            self::$_strWriteHandler = $arrConfig['writehandler'];
        } else {
            throw new Base_Exception_Runtime("Write handler: ".implode(',', $arrWriteHandlers));
        }

        if (array_key_exists('ucid', $arrConfig)) {
            self::$_intUcid = intval($arrConfig['ucid']);
        } else {
            self::$_intUcid = 0;
        }
         
        if (array_key_exists('ignorepath', $arrConfig)) {
            self::$_strIgnorePath = trim($arrConfig['ignorepath']);
        } else {
            self::$_strIgnorePath = null;
        }

        if (array_key_exists('writebufmax', $arrConfig) && ($arrConfig['writebufmax'] = intval($arrConfig['writebufmax'])) > 0 ) {
            self::$_intBufMaxNum = $arrConfig['writebufmax'];
        } else {
            self::$_intBufMaxNum = 20;
        }

        return true;
    }/*}}}*/

    /**
     * 记录error级别日志 追加request uri
     *
     * @param msg mixed 日志具体内容
     * @param strMonitor string 监控字符串
     * @return bool
     */
    public static function error($msg, $strMonitor=null) {/*{{{*/
        if (!self::_isAndCheckConfig(self::ERROR)) {
            return true;
        }
       
        self::_write(__FUNCTION__, $msg, $strMonitor);

        return true;
    }/*}}}*/

    /**
     * 记录warn级别日志 追加request uri
     *
     * @param msg mixed 日志具体内容
     * @param strMonitor string 监控字符串
     * @return bool
     */
    public static function warn($msg, $strMonitor=null) {/*{{{*/
        if (!self::_isAndCheckConfig(self::WARN)) {
            return ;
        }
        self::_write(__FUNCTION__, $msg, $strMonitor);
    }/*}}}*/

    /**
     * 记录notice级别日志
     *
     * @param msg mixed 日志具体内容
     * @param strMonitor string 监控字符串
     * @return bool
     */
    public static function notice($msg, $strMonitor=null) {/*{{{*/
        if (!self::_isAndCheckConfig(self::NOTICE)) {
            return ;
        }
        self::_write(__FUNCTION__, $msg, $strMonitor);
    }/*}}}*/

    /**
     * 记录debug级别日志
     *
     * @param msg mixed 日志具体内容
     * @param strMonitor string 监控字符串
     * @return bool
     */
    public static function debug($msg, $strMonitor=null) {/*{{{*/
        if (!self::_isAndCheckConfig(self::DEBUG)) {
            return ;
        }
        self::_write(__FUNCTION__, $msg, $strMonitor);
    }/*}}}*/


    /**
     * 记录网络交互耗时
     *
     * @param
     * @return
     */
    public static function api($strHost, $intPort, $strPath, $strMethod, $intTime, $intLogid) {/*{{{*/
        $mixMsg = array(
            'key' => 'networktimer',
            'url' => $strPath,
            'time' => $intTime,
            'host' => $strHost,
            'port' => $intPort,
            'method' => $strMethod,
            'logid' => $intLogid,
        );
        return self::notice($mixMsg);
    }/*}}}*/

    public static function setUcid($ucid){
        self::$_intUcid = intval($ucid);
    }


    /**
     * 组装日志内容，格式化，添加换行符等
     *
     * @param strType string 级别
     * @param msg mixed
     * @param strMonitor string 监控字符串
     * @return
     */
    protected static function _write($strType, $msg, $strMonitor) {/*{{{*/
     
        // 获取调用者的文件名称和行号,类名，函数名
        self::_getTraceInfo($strInfo, 2);

        // 格式化用户传入的msg
        $msg = self::_buildMsg($msg);
         
        // error和warn等级，附加requesturi
        if ('error' == $strType || 'warn' == $strType) {
            $msg .= self::DELEMITER.self::_getRequestUri();
        } else {
            $msg .= self::DELEMITER.self::EMPTYFILED;
        }

        if (!$strMonitor) {
            $strMonitor = self::EMPTYFILED;
        }
         
        $_str = sprintf("%s%s%s%s%s:%d:%d%s%s%s%s%s%d%s%s\n\n",
            $strMonitor,
            self::DELEMITER,
            $strInfo,
            self::DELEMITER,
            self::_getUniqID(), self::_getReqID(), self::_getSeqID(),
            self::DELEMITER,
            date("Y-m-d H:i:s"),
            self::DELEMITER,
            self::_getClientIp(),
            self::DELEMITER,
            self::$_intUcid,
            self::DELEMITER,
            $msg
        );

        call_user_func_array(array(__CLASS__, self::$_strWriteHandler), array($strType, $_str));
    }/*}}}*/

    /**
     * 写日志。
     * 不作缓存，由系统控制
     * fwrite是原子的，除非超过系统block大小
     *
     * @param strType string 级别
     * @return null
     */
    protected static function _fwrite($strType, $strMsg) {/*{{{*/
        fwrite(self::_getFp($strType), $strMsg);
    }/*}}}*/

    /**
     * 写日志。包含文件打开、关闭操作。
     *
     * @param strType string 级别
     * @return null
     */
    protected static function _file_put_contents($strType, $strMsg) {/*{{{*/
        file_put_contents(self::_filename($strType), $strMsg, FILE_APPEND);
    }/*}}}*/

    /**
     * 写日志。
     * 缓存后批量写出。
     *
     * @param strType string 级别
     * @return null
     */
    protected static function _write_with_buf($strType, $strMsg) {/*{{{*/
        static $bNeedRegister = true;
        if ($bNeedRegister) {
            register_shutdown_function(array(__CLASS__, 'bufwrite_shutdown_handler'));
            $bNeedRegister = false;
        }
         
        self::$_arrBuf[$strType][] = $strMsg;

        // 若缓冲区已满，输出
        if (++self::$_intBufCnt >= self::$_intBufMaxNum) {
            self::bufwrite_shutdown_handler();
        }
    }/*}}}*/

    /**
     * 将缓冲区的内容flush到文件里
     *
     * 由于需要注册为shutdown functions，所以需public
     */
    public static function bufwrite_shutdown_handler() {/*{{{*/
        foreach(self::$_arrBuf as $strType=>$arrMsgs) {
            // You can also specify the data parameter as a single dimension array. This is equivalent to file_put_contents($filename, implode('', $array)).
            file_put_contents(self::_filename($strType), $arrMsgs, FILE_APPEND);
        }
         
        // 清空
        self::$_arrBuf = array();
        self::$_intBufCnt = 0;
    }/*}}}*/

    /**
     * 打开文件描述符。 
     *
     * @param strType string 级别
     * @return FD 文件描述符
     */
    protected static function _getFp($strType) {/*{{{*/
        static $_arrFps;
        if (empty($_arrFps[$strType])) {
            $strFilename = self::_filename($strType);

            /* 不提供fclose封装，进程退出时自动关闭 */
            if(!($_arrFps[$strType] = fopen($strFilename, 'ab'))) {
                // 不抛异常，之后write时，会报php warning，不会导致程序退出
                return false;
            }
        }
        return $_arrFps[$strType];
    }/*}}}*/

    /**
     * 拼装日志文件名，并建立目录。
     *
     * @param strType string 级别
     * @return string 文件名路径
     */
    protected static function _filename($strType) {/*{{{*/
        $strDir = self::$_strLogDir.'/'.date("Ymd");   
 
        /* 每个dir仅检查一遍 by xuquangang20140828 */
        if (!isset(self::$_arrCheckDir[$strDir]) && !is_dir($strDir)) {
            @mkdir($strDir, 0775, true);               
            if (!is_dir($strDir)) {
                return false;
            }

            self::$_arrCheckDir[$strDir] = true;
        }
        
        return $strDir. '/'. $strType . '.log';
    }/*}}}*/

    /**
     * 获得客户请求携带的ip地址
     *
     * @return string ip
     */
    protected static function _getClientIp() {/*{{{*/
        static $onlineip;
        if ($onlineip) {
            return $onlineip;
        }
         
        $onlineip = Base_Util_Ip::getClientIp();
        
        // 取不到ip
        if (!$onlineip) {
            $onlineip = self::EMPTYFILED;
        }
        return $onlineip;
    }/*}}}*/

    /**
     * 获取访问的URI
     *
     * @return string requesturi
     */
    protected static function _getRequestUri() {/*{{{*/
        static $requestUri;
        if (!$requestUri) {
            if (!empty($_SERVER['REQUEST_URI'])) {
                $requestUri = trim($_SERVER['REQUEST_URI']);
            } else {
                $requestUri = '-';
            }
        }
        return $requestUri;
    }/*}}}*/

    /**
     * 获取BAIDUID
     *
     * @return string baiduid
     * TODO:get uniqid
     */
    protected static function _getUniqID() {/*{{{*/
       static $uniqID;
       // if (!$uniqID) {
       //     if (!class_exists('Logic_Cookie')) {
       //         $uniqID = self::EMPTYFILED;
       //     } else {
       //         $objCookieHandler = Logic_Cookie::getInstance();
       //         if(!($uniqID = $objCookieHandler->readBaiduId())) {
       //             $uniqID = self::EMPTYFILED;
       //         } else {
       //             $uniqID = str_replace(':', '', $uniqID);
       //         }
       //     }
       // }
        return $uniqID;
    }/*}}}*/
    
    /**
     * 生成请求唯一ID
     *
     * @param string requestid
     */
    protected function _getReqID() {/*{{{*/
        return Base_Util_Request::getReqID();
    }/*}}}*/

    /**
     * 生成请求内日志序列号。
     *
     * @return integer seqid
     */
    protected function _getSeqID() {/*{{{*/
        static $seqID = 0;
        return ++$seqID;
    }/*}}}*/

    /**
     * 检查是否需要记录该级别的日志。
     *
     * @param type integer 级别号
     * @return bool
     */
    protected static function _isAndCheckConfig($type) {/*{{{*/
        // 配置有问题，则都不写
        if(!self::_checkConfig()) {
            return false;
        }
         
        return self::$_intLevel & $type;
    }/*}}}*/

    /**
     * 检查配置是否正确。
     *
     * @return bool
     */
    protected static function _checkConfig() {/*{{{*/
        if (empty(self::$_intLevel)) {
            return false;
        }
        if (empty(self::$_strLogDir)) {
            return false;
        }

        return true;
    }/*}}}*/

    /**
     * 获取日志记录来源信息。
     * 默认由write method调用，故depth=2；若改变调用层级，此处需要随之修改
     * 也可做成跳出该文件 或 过滤不包含log关键词的文件，但考虑到效率问题，不做。
     *
     * @param _strInfo string 传引用，日志记录来源信息
     * @param depth interger 深度
     * @return bool 是否获取来源信息成功
     */
    protected static function _getTraceInfo(&$_strInfo, $depth=2) {/*{{{*/
        // php 5.3 暂不支持limit
        $trace = debug_backtrace();

        if (!$trace || ($depth >= count($trace))) {
            $_strInfo = self::EMPTYFILED;
            return false;
        }

        $_file = $trace[$depth]['file'];
        if (self::$_strIgnorePath) {
            $_file = str_replace(self::$_strIgnorePath, '', $_file);
        }
        $_line = $trace[$depth]['line'];
         
        $_class = (empty($trace[$depth+1]['class'])? self::EMPTYFILED: $trace[$depth+1]['class']);
        $_func = (empty($trace[$depth+1]['function'])? self::EMPTYFILED: $trace[$depth+1]['function']);

        $_strInfo = sprintf("%s:%d:%s:%s", $_file, $_line, $_class, $_func);

        return true;
    }/*}}}*/

    /**
     * 格式化日志。
     *
     * @param mixMsg mixed 日志内容
     * @return string 格式化后的日志内容。
     */
    protected static function _buildMsg($mixMsg) {/*{{{*/
        $strParams = self::EMPTYFILED;

        if (is_array($mixMsg)) {
            foreach ($mixMsg as $key => $value) {
                if(is_null($mixMsg[$key])){
                    $mixMsg[$key] = '-';
                }
            }
            $strParams = http_build_query($mixMsg, 'param_');
            $strParams = urldecode($strParams);
        } elseif (is_string($mixMsg)) {
            $strParams = $mixMsg;
        } 

        // 替换self::DELEMITER
        $strParams = str_replace("\t", ' ', $strParams);

        return $strParams;
    }/*}}}*/
}
