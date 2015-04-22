<?php
class Finance_Keys {

    const BGCALL_KEY = 'bgcall_%s_%s_%s';

    public static function getBgCallKey($cmdId, $orderId, $respCode){
    	return sprintf(self::BGCALL_KEY, $cmdId, $orderId, $respCode);
    }
}
