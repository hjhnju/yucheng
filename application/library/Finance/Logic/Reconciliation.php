<?php 
/**
 * 对账逻辑类
 * @author lilu
 */
class Finance_Logic_Reconciliation extends Finance_Logic_Base {
    
    /**
     * 充值对账(获取用户的充值记录)
     * @param string $beginDate 开始时间
     * @param string $endDate 结束时间
     * @param integer $pageNum 数据所在页号
     * @param integer $pageSize 每页记录数
     * @return array || false
     */
    public function saveReconciliation($beginDate,$endDate,$pageNum,$pageSize) {
        if(!isset($beginDate) || !isset($endDate) || !isset($pageNum) || !isset($pageSize)) {
            Base_Log::error(array(
                'msg'       => '请求参数错误',
                'beginDate' => $beginDate,
                'endDate'   => $endDate,
                'pageNum'   => $pageNum,
                'pageSize'  => $pageSize,
            ));
            return false;
        }
        if(intval($endDate-$beginDate) > 90) {
            Base_log::error(array(
                'msg'       => '请求时间范围错误',
                'beginDate' => $beginDate,
                'endDate'   => $endDate,
            ));
            return false;
        }
        if(intval($pageNum) <= 0) {
            Base_log::error(array(
                'msg'       => '请求参数错误',
                'pageNum'   => $pageNum,
            ));
            return false;
        }
        if(intval($pageSize) <= 0 && intval($pageSize) > 1000 ) {
            Base_log::error(array(
                'msg'       => '请求参数错误',
                'pageSize'  => $pageSize,
            ));
            return false;
        }
        $beginDate = strval($beginDate);
        $endDate = strval($endDate);
        $pageNum = strval($pageNum);
        $pageSize = strval($pageSize);
        $return = $this->chinapnr->saveReconciliation($this->merCustId, $beginDate, $endDate, $pageNum, $pageSize); 
        var_dump($return);die();    
        if(is_null($return)) {
            Base_Log::error(array(
                'msg'       => '请求汇付接口失败',
                'beginDate' => $beginDate,
                'endDate'   => $endDate,
                'pageNum'   => $pageNum,
                'pageSize'  => $pageSize,
            ));
            return false;
        }
        $logParam = array();
        $logParam['beginDate'] = $beginDate;
        $logParam['endDate'] = $endDate;
        $logParam['pageNum'] = $pageNum;
        $logParam['pageSize'] = $pageSize;
        $logParam = array_merge($logParam,$return);
        Base_Log::notice($logParam);
        return $return;
    }
    
    /**
     * 取现对账(获取用户的取现记录)
     * @param string $beginDate 开始时间
     * @param string $endDate 结束时间
     * @param integer $pageNum 数据所在页号
     * @param integer $pageSize 每页记录数
     * @return array || false
     *
     */
    public function cashReconciliation($beginDate,$endDate,$pageNum,$pageSize) {
        if(!isset($beginDate) || !isset($endDate) || !isset($pageNum) || !isset($pageSize)) {
            Base_Log::error(array(
                'msg' => '请求参数错误',
                'beginDate' => $beginDate,
                'endDate'   => $endDate,
                'pageNum'   => $pageNum,
                'pageSize'  => $pageSize,
            ));
            return false;
        }
        
        if(intval($endDate-$beginDate) > 90) {
            Base_Log::error(array(
                'msg'       => '请求时间范围错误',
                'beginDate' => $beginDate,
                'endDate'   => $endDate,
            ));
            return false;
        }
        if(intval($pageNum) <= 0) {
            Base_Log::error(array(
                'msg'     => '请求参数错误',
                'pageNum'   => $pageNum,
            ));
            return false;
        }
        if(intval($pageSize) <= 0 && intval($pageSize) > 1000 ) {
            Base_Log::error(array(
                'msg'     => '请求参数错误',
                'pageSize'  => $pageSize,
            ));
            return false;
        }
        $beginDate = strval($beginDate);
        $endDate = strval($endDate);
        $pageNum = strval($pageNum);
        $pageSize = strval($pageSize);
        $return = $this->chinapnr->cashReconciliation($this->merCustId, $beginDate, $endDate, $pageNum, $pageSize);
        
        if(is_null($return)) {
            Base_Log::error(array(
                'msg'       => '请求汇付接口失败',
                'beginDate' => $beginDate,
                'endDate'   => $endDate,
                'pageNum'   => $pageNum,
                'pageSize'  => $pageSize
            ));
            return false;
        }
        $logParam = array();
        $logParam['beginDate'] = $beginDate;
        $logParam['endDate'] = $endDate;
        $logParam['pageNum'] = $pageNum;
        $logParam['pageSize'] = $pageSize;
        $logParam = array_merge($logParam,$return);
        Base_Log::notice($logParam);
        return $return;
    }
    
    /**
     * 放还款对账
     * @param string  $beginDate 开始时间
     * @param string  $endDate 结束时间
     * @param integer $pageNum 数据所在页号
     * @param integer $pageSize 每页记录数
     * @param string  $queryTransType 交易查询类型
     * @return array || false
     *
     */
    public function reconciliation($beginDate,$endDate,$pageNum,$pageSize,$queryTransType) {
        if(!isset($beginDate) || !isset($endDate) || !isset($pageNum) || !isset($pageSize) || !isset($queryTransType)) {
            Base_Log::error(array(
                'msg'            => '请求参数错误',
                'beginDate'      => $beginDate,
                'endDate'        => $endDate,
                'pageNum'        => $pageNum,
                'pageSize'       => $pageSize,
                'queryTransType' => $queryTransType,
            ));
            return false;
        }
        if(intval($endDate-$beginDate) > 90) {
            Base_Log::error(array(
                'msg'            => '请求时间范围错误',
                'beginDate'      => $beginDate,
                'endDate'        => $endDate,
            ));
            return false;
        }
        if(intval($pageNum) <= 0) {
            Base_Log::error(array(
                'msg'            => '请求参数错误',
                'pageNum'        => $pageNum,
            ));
            return false;
        }
        if(intval($pageSize) <= 0 && intval($pageSize) > 1000 ) {
            Base_Log::error(array(
                'msg'            => '请求参数错误',
                'pageSize'       => $pageSize,
            ));
            return false;
        }
        if($queryTransType !== 'LOANS' && $queryTransType !== 'REPAYMENT') {
            Base_Log::error(array(
                'msg'     => '请求参数错误',
                'queryTransType' => $queryTransType,
            ));
            return false;
        }
        $beginDate = strval($beginDate);
        $endDate   = strval($endDate);
        $pageNum   = strval($pageNum);
        $pageSize  = strval($pageSize);
        $result    = $this->chinapnr->reconciliation($this->merCustId, $beginDate, $endDate, $pageNum, $pageSize, $queryTransType);
        if(is_null($result)) {
            Base_Log::error(array(
                'msg'            => '请求汇付接口失败',
                'beginDate'      => $beginDate,
                'endDate'        => $endDate,
                'pageNum'        => $pageNum,
                'pageSize'       => $pageSize,
                'queryTransType' => $queryTransType,
            ));
            return false;
        }
        $logParam = array();
        $logParam['beginDate'] = $beginDate;
        $logParam['endDate'] = $endDate;
        $logParam['pageNum'] = $pageNum;
        $logParam['pageSize'] = $pageSize;
        $logParam['queryTransType'] = $queryTransType;
        $logParam = array_merge($logParam,$result);
        Base_Log::notice($logParam);
        return $result;
    }
    
    /**
     * 商户扣款对账
     * @param int beginDate
     * @param int endDate
     * @param int pageNum
     * @param int pageSize
     * @return array || boolean
     */
    public function trfReconciliation($beginDate,$endDate,$pageNum,$pageSize) {
        if(!isset($beginDate) || !isset($endDate) || !isset($pageNum) || !isset($pageSize)) {
            Base_Log::error(array(
                'msg'       => '请求参数错误',
                'beginDate' => $beginDate,
                'endDate'   => $endDate,
                'pageNum'   => $pageNum,
                'pageSize'  => $pageSize,
            ));
            return false;
        }
        if(intval($endDate-$beginDate) > 90) {
            Base_Log::error(array(
                'msg'       => '请求时间范围错误',
                'beginDate' => $beginDate,
                'endDate'   => $endDate,
            ));
            return false;
        }
        if(intval($pageNum) <= 0) {
            Base_Log::error(array(
                'msg'     => '请求参数错误',
                'pageNum' => $pageNum,
            ));
            return false;
        }
        if(intval($pageSize) <= 0 && intval($pageSize) > 1000 ) {
            Base_Log::error(array(
                'msg'     => '请求参数错误',
                'pageSize' => $pageSize,
            ));
            return false;
        }
        $beginDate = strval($beginDate);
        $endDate = strval($endDate);
        $pageNum = strval($pageNum);
        $pageSize = strval($pageSize);
        $return = $this->chinapnr->trfReconciliation($this->merCustId,$beginDate,$endDate,$pageNum,$pageSize);
        if(is_null($return)) {
            Base_Log::error(array(
                'msg' => '请求汇付接口失败',
                'beginDate' => $beginDate,
                'endDate' => $endDate,
                'pageNum' => $pageNum,
                'pageSize' => $pageSize,
            ));
            return false;
        }
        $logParam = array();
        $logParam['beginDate'] = $beginDate;
        $logParam['endDate'] = $endDate;
        $logParam['pageNum'] = $pageNum;
        $logParam['pageSize'] = $pageSize;
        $logParam = array_merge($logParam,$result);
        Base_Log::notice($logParam);
        return $return;
    }
}