<?php
class Invest_Keys {

    const TENDER_STATUS_KEY   = 'tender';
    const TENDER_STATUS_FIELD = 'orderid_%s';

    public static function getStatusKey(){
        return self::TENDER_STATUS_KEY;
    }

    public static function getStatusField($orderId){
        return sprintf(self::TENDER_STATUS_FIELD, $orderId);
    }
}
