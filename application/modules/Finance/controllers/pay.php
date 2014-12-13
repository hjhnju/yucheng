<?php
/**
 *用户财务类相关操作
 */
class PayController extends Base_Controller_Api{

    /**
     *
     * 定义订单类型映射
     */
    const TYPE = array(
        'deposit' => 1,
        'bid' => 2,
        'bid_auto' => 3,
        'bid_cancel' => 4,
    );
    public function init(){
        parent::init();
        $this->payLogic = new Finance_Logic_Pay();
    }

    public function testAction(){
        var_dump($this->payLogic->generateOrderId());
        die;
    }


}

