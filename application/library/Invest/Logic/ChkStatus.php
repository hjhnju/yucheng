<?php
class Invest_Logic_ChkStatus {

    /**
     * 在redis 中设置投标状态
     * @param [type] $orderId [description]
     * @param [type] $bolSucc [description]
     */
    public static function setInvestStatus($orderId, $bolSucc){
        $intSt = $bolSucc ? 1 : 2;      
        Base_Redis::getInstance()->setex(Invest_Keys::getStatusKey($orderId), 10000, $intSt);
        Base_Log::debug(array( 
            'key'   => Invest_Keys::getStatusKey($orderId), 
            'value' => $intSt, 
        ));
        return true;           
    }   


    /**
     * 获取投标状态
     * @return $res true|false|null 表示没有状态
     */
    public static function getInvestStatus($orderId){
        $intSt   = Base_Redis::getInstance()->get(Invest_Keys::getStatusKey($orderId));
        $intSt   = intval($intSt);
        $bolSucc = null;     
        if($intSt === 1){
            $bolSucc = true;
        }elseif ($intSt === 2) {        
            $bolSucc = false;  
        }
        Base_Log::debug(array(
            'key'   => Invest_Keys::getStatusKey($orderId), 
            'value' => $intSt,
        ));
        return $bolSucc;
    }
}
