<?php
class Finance_Keys {
    
    //concurrency key
    CONST PREFIX = 'cckey_';
    
    public static function getBgCallKey($cmdId, $orderId, $respCode){
        return self::PREFIX.$cmdId.$orderId.$respCode;
    }
}
