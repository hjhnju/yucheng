<?php
/**
 *
 * 财务模块公共逻辑层
 *
 */
class Finance_Logic_Pay{
    public function init(){
    
    } 
    public function __construct(){
    }

    /**
     *
     * 生成订单号Order_id YmdHis+随机数
     * @param
     * @return
     */
    public function generateOrderId(){

        $now = date("Ymdhis",mktime());
        $numbers = range(0,9);
        shuffle($numbers);
        $no = 6;
        $ranNum = array_slice($numbers,0,$no);
        foreach($ranNum as $key=>$value){
            $now .= $value;
        }
        return $now;
    
    }


}




