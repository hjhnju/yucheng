<?php
class Invest_Keys {

    const TENDER_STATUS_KEY = 'inv_oid_%s_status';

    public static function getStatusKey($orderId){
        return sprintf(self::TENDER_STATUS_KEY, $orderId);
    }
}
