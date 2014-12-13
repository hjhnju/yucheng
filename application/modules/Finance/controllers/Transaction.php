<?php
/**
 * 交易类Controller层
 *
 *
 */
class TransactionController extends Base_Controller_Api{

    /**
     * 主动投标controller层
     *
     *
     */
    public function initiativeTenderAction(){
        $version = $_REQUEST['version'];
        $merCustId = $_REQUEST['merCustId'];
        $transAmt = $_REQUSET['transAmt'];
        //需要其余模块的参数
        //转入Logic层 Logic层封装了汇付API
    
    }



}
