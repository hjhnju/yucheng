<?php
/**
 *
 * 财务模块公共逻辑层
 *
 */
class Finance_Logic_Base {

    protected static $instance = NULL;
    
    public function init(){
    
    }
 
    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new Finance_Logic_Pay();
        }
        return self::$instance;
    }

    private function __construct(){

    }

    /**
     *
     * 生成订单号Order_id YmdHis+随机数
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




