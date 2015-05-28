<?php
/**
 * 对外的API接口
 */
class Angel_Api {
    /**
     * 获取标的天使信息
     * @param integer $userid
     * @param integer $investId
     * @return array
     */
    public static function getAngel($userid,$investId){
        $objShare = new Invest_Object_Share();
        $objShare->fetch(array('from_userid'=>$userid,'invest_id'=>$investId));
        if(!empty($objShare->id)){
            $objInvest = new Invest_Object_Invest();
            $objInvest->fetch(array('id'=>$investId,'user_id'=>$userid));
            $objRefund = new Invest_List_Refund();
            $objRefund->setFilter(array("invest_id"=>intval($investId),"user_id"=>$userid));
            $objRefund->setPagesize(PHP_INT_MAX);
            $arrRefund = $objRefund->getData();
            $selfmoney = 0;
            foreach ($arrRefund as $val){
                $selfmoney += $val['interest'];
            }
            return array(
                'angelrate'  => $objShare->rate,
                'angelmoney' => $objShare->income,
                'selfrate'   => $objInvest->interest-$objShare->rate,
                'selfmoney'  => $selfmoney,
            );
        }
        return array();
    }
}
