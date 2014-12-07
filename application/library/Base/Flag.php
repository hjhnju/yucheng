<?php
/**
 * 检查是否开启了服务降级。
 * 需要配置格式的支持。
 */

class Base_Flag {
    const ACTIVE = 0; // 服务正常开启
    const CLOSED = 1; // 服务关闭
    const BACKUP = 2; // 切换至本地或产品备案
    
    /**
     * 检查是否开启了服务降级。
     * 若发生服务降级，则抛异常。
     *
     * @param arrConfig array('flag' => 0|1|2, ...)
     * @returns bool
     * @throw Base_Exception_Runtime
     */
    public static function isDegrade($arrConfig) {
        // 松散格式检查
        if (!is_array($arrConfig) || !isset($arrConfig['flag'])) {
            return true;
        }

        if (self::ACTIVE == $arrConfig['flag']) {
            return true;
        }

        throw new Base_Exception_Degrade();
    }
}

