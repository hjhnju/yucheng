<?php
/**
 * 配置管理类
 * 依赖Ap框架
 *
 * 配置格式要求：
 * 1. ini格式允许有分节，如product、dev等
 * 2. array格式需传入数组变量名
 */
class Base_Config {

    /**
     * INI配置文件扩展名
     */
    const TYPE_INI   = '.ini';
    /**
     * TYPE_INI的字符串长度
     */
    const TYPE_INI_LEN = 4;

    /**
     * php数组配置文件扩展名
     */
    const TYPE_ARRAY = '.php';
    /**
     * TYPE_ARRAY的字符串长度
     */
    const TYPE_ARRAY_LEN = 4;

    /**
     * 默认配置,可通过setOption方法修改
     * 一般在框架初始化时设置
     * @var string
     */
    protected static $_defSection = 'dev';

    /**
     * 配置key的分隔符
     * @var string
     */
    protected static $_defDelimiter = '.';

    /**
     * 默认配置管理handler
     * @var Yaf_Config_Abstract
     */
    protected static $_objDefaultConfig;

    /**
     * 配置项的值
     * @var array
     */
    protected  static $_arrConfig = array();

    /**
     * 初始化配置，设置ini的分节等
     * @param $arrOption array('section' => 'product','delimiter' => '.',)
     */
    public static function setOption($arrOption = array()) {
        if (!empty($arrOption['section'])) {
            self::$_defSection = trim($arrOption['section']);
        }
        if (!empty($arrOption['delimiter'])) {
            self::$_defDelimiter = trim($arrOption['delimiter']);
        }
    }

    /**
     * 获取配置项，按需加载，支持ini/array格式
     *
     * @param $strTag string 配置项的key
     * @param $strFile string 配置文件路径，建议使用绝对路径。
     *                          若该路径为空，则使用默认配置
     *
     * @return mixed 配置的值，若不存在返回null
     */
    public static function getConfig($strTag, $strFile = '') {
        $strTag = trim($strTag);
        $strFile = trim($strFile);

        if (!$strTag) {
            return null;
        }

        // 处理默认配置
        if (!$strFile) {
            return self::_getDefaultConfig($strTag);
        }

        // 处理非默认配置
        if (!isset(self::$_arrConfig[$strFile])) {
            // 加载配置文件
            self::_loadFile($strFile);
        }

        // 读取非默认配置值

        if (is_array(self::$_arrConfig[$strFile])) {
            return self::_getConfigFromArray($strTag, $strFile);
        } else {
            $mixRet = self::$_arrConfig[$strFile]->get($strTag);
            if (is_object($mixRet)) {
                return $mixRet->toArray();
            } else {
                return $mixRet;
            }
        }
    }

    /**
     * 从配置数组读取
     * @return mixed, 若没有匹配的值返回null
     */
    protected static function _getConfigFromArray($strTag, $strFile) {
        //字符串tag
        $arrTag = explode(self::$_defDelimiter, $strTag);
        if (!$arrTag) {
            return null;
        }

        // 逐步嵌套，找到最终kv对
        $mixVal = self::$_arrConfig[$strFile];
        foreach ($arrTag as $strKey) {
            if (!is_array($mixVal)) {
                return false;
            }
            $mixVal = isset($mixVal[$strKey])? $mixVal[$strKey]: null;
        }
        return $mixVal;
    }

    /**
     * 获取默认配置项
     *
     * @param $strTag string 配置项的key
     * @return mixed 配置的值，若不存在返回null
     */
    protected static function _getDefaultConfig($strTag) {/*{{{*/
        // 延迟加载Yafconfig对象，为节省资源，不再进行类型检查
        if (!self::$_objDefaultConfig) {
            self::$_objDefaultConfig = Yaf_Application::app()->getConfig();
            if (!self::$_objDefaultConfig) {
                return null;
            }
        }

        $mixRet = self::$_objDefaultConfig->get($strTag);
 
        return $mixRet;
    }/*}}}*/

    /**
     * 按需加载文件
     * @param $strFile string 文件路径
     *
     * @return bool
     * @throw Base_Exception_Runtime
     */
    protected static function _loadFile($strFile) {/*{{{*/
        if (substr($strFile, -self::TYPE_INI_LEN) === self::TYPE_INI) {
            if(!($objConfig = new Yaf_Config_Ini($strFile, self::$_defSection))) {
                throw new Base_Exception_Runtime('load file fail:'.$strFile, Base_RetCode::CONFIG_FAIL);
            }

            self::$_arrConfig[$strFile] = $objConfig;
            Base_Log::Debug('Read from ini file: '.$strFile);
            return true;
        }

        
        if (substr($strFile, -self::TYPE_ARRAY_LEN) === self::TYPE_ARRAY) {
            $arrConfig = include($strFile);
            if ($arrConfig === false) {
                throw new Base_Exception_Runtime('load file fail:'.$strFile, Base_RetCode::CONFIG_FAIL);
            }
            self::$_arrConfig[$strFile] = $arrConfig;
            Base_Log::Debug('Read from php array file: '.$strFile);
            return true;
        }

        throw new Base_Exception_Runtime('unknown file type:'.$strFile, Base_RetCode::CONFIG_FAIL);
    }/*}}}*/
}
