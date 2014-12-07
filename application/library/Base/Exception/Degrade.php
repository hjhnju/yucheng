<?php

/**
 * 服务降级特定异常
 */
class Base_Exception_Degrade extends Base_Exception_Runtime {

    public function __construct($strMsg = '', $intCode = Base_RetCode::SERVICE_DEGRADED) {
        if (!$strMsg) {
            $strMsg = Base_RetCode::getMsg($intCode);
        }
         
        parent::__construct($strMsg, $intCode);
    }
}
