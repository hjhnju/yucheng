<?php
class Finance_Keys {

    const TENDER_STATUS_KEY   = 'tenderstat';
    const TENDER_STATUS_FIELD = 'orderid_%s';

    public static function getTenderStKey(){
        return self::TENDER_STATUS_KEY;
    }

    public static function getTenderStField($orderId){
        return sprintf(self::TENDER_STATUS_FIELD, $orderId);
    }
}