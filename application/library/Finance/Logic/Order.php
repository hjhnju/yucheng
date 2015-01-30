<?php 
/**
 * 财务模块订单工具类
 * @author lilu
 */
class Finance_Logic_Order {

    private function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 生成订单号Order_id YmdHis + 微秒 + 随机数
     * 19位
     * @return string
     */
    public static function genOrderId(){
        $arrInfo = self::genOrderInfo();
        return strval($arrInfo['orderId']);
    }

    /**
     * 获取订单日期
     * @param $orderId 订单号
     */
    public static function getOrderDate($orderId){
        return substr(strval($orderId), 0, 4);
    }

    /**
     * 生成订单号Order信息
     * 
     * @return array('orderId'=>, 'date'=>)
     */
    public static function genOrderInfo(){
        $timeStr = $this->getMillisecond();
        
        $time    = substr($timeStr, 0, 10);
        $ms      = substr($timeStr, 10, 3);
        $orderDate    = date("Ymd", $time);
        $orderId = date("YmdHis", $time) . $ms ;
        
        $numbers = range(0, 9);
        shuffle($numbers);
        $no     = 2;
        $ranNum = array_slice($numbers, 0, $no);
        foreach($ranNum as $key=>$value){
            $orderId .= $value;
        }

        $arrInfo = array(
            'date'    => $orderDate,
            'orderId' => $orderId,
        );
        return $arrInfo;
    }
}