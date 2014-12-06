<?php
/**
 * 服务所处上下文
 * 如web、api等
 *
 */
class Base_Env {
    /**
     * 所处上下文标记
     * @var string
     */
    protected static $strEnv='web';

    /**
     * 可用上下文
     * @var array
     */
    protected static $arrEnvs = array('web', 'api');

    /**
     * 设置服务上下文
     * @param $strEnv string
     */
    public static function setEnv($strEnv='web') {/*{{{*/
        if (empty($strEnv)){
            throw new Base_Exception_Runtime("args fail, strEnv empty");
        }
        if (!in_array($strEnv, self::$arrEnvs)){
            throw new Base_Exception_Runtime("args fail: $strEnv");
        }
        self::$strEnv = $strEnv;
    }/*}}}*/

    /**
     * 获取服务上下文
     * @return string
     */
    public static function getEnv() {/*{{{*/
        return self::$strEnv;
    }/*}}}*/

    /**
     * 设置为api环境
     */
    public static function setApi() {/*{{{*/
        return self::setEnv('api');
    }/*}}}*/

    /**
     * 设置为web环境
     */
    public static function setWeb() {/*{{{*/
        return self::setEnv('web');
    }/*}}}*/

    /**
     * 检查是否api环境
     * @return bool
     */
    public static function isApi() {/*{{{*/
        return ('api' == self::getEnv());
    }/*}}}*/
}
