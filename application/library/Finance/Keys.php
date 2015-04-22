<?php
class Finance_Keys {

    const BGCALL_KEY = 'bgcall_%s_%s_%s';

    const TRANS_KEY = 'trans_%s_%s';

    public static function getBgCallKey($cmdId, $orderId, $respCode){
    	return sprintf(self::BGCALL_KEY, $cmdId, $orderId, $respCode);
    }

    public static function getTransKey($orderId, $extId){
    	return sprintf(self::TRANS_KEY, $orderId, $extId);
    }
}
