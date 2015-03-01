<?php
class Loan_Logic_Counter {

    public static function caculate($userid){
        $userid = intval($userid);
        if($userid <= 0){
            return false;
        }

        $arrCnt = array('userid' => $userid);
        $db = Base_Db::getInstance('xjd');

        //成功借款次数，累计借款，还清笔数，已还本息，待还本息，
        //success, total, finished, refund, rest, 
        
        $sql   = "SELECT count(distinct loan_id) as success, sum(capital) as total ";
        $sql  .= "FROM loan_refund WHERE user_id = $userid";
        $row   = $db->fetchRow($sql);
        $arrCnt['success'] = intval($row['success']);
        $arrCnt['total']   = $row['total'];

        $sql   = "SELECT count(distinct loan_id) as finished, sum(amount) as refund FROM loan_refund 
                WHERE user_id = $userid AND status = " . Loan_Type_Refund::REFUNDED;
        $row   = $db->fetchRow($sql);
        $arrCnt['finished'] = intval($row['finished']);
        $arrCnt['refund']   = floatval($row['refund']);

        $sql   = "SELECT sum(amount) as rest FROM loan_refund 
                WHERE user_id = $userid AND status != " . Loan_Type_Refund::REFUNDED;
        $row   = $db->fetchRow($sql);
        $arrCnt['rest'] = floatval($row['rest']);

        $counter = new Loan_Object_Counter($arrCnt);
        $ret = $counter->save();
        Base_Log::notice(array(
            'msg' => '更新借款统计',
            'ret' => $ret,
        ));
        return $ret;
    }
    
}