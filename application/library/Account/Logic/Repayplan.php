<?php 
/**
 * 获取投标的还款计划
 * @author lilu
 */
class Account_Logic_Repayplan {
    
    /**
     * @param string $investId
     * @return array
     */
    public static function getRepayplan($investId) {        
        $investId = intval($investId);
        $retData  = Invest_Api::getRefunds($investId);       
        $list = array();
        $data = array();
        if(empty($retData)) {
            $ret = array(
                    'invester' => '',
                    'list'     => array(),
                    'data'     => array(),
            );
            return $ret ;
        }
        $objUser  = User_Api::getUserObject($retData[0]['user_id']);
        $invester = $objUser->name;
        foreach ($retData as $key=>$value) {

            $list[$key]['time']            = $value['create_time'];     
            //待收本金      
            $list[$key]['repossPrincipal'] = sprintf('%.2f',$value['capital_rest']);        
            //已收本金      
            $list[$key]['recePrincipal']   = sprintf('%.2f',$value['capital_refund']);      
            //是否已还款     
            if($value['transfer'] === 1) {      
                //待收收益      
                $list[$key]['repossProfit'] = '0.00';       
                //已收收益      
                $list[$key]['receProfit']   = sprintf('%.2f', $value['interest']);      
            }       
            if($value['transfer'] === 0) {      
                //待收收益      
                $list[$key]['repossProfit'] = sprintf('%.2f', $value['interest']);      
                //已收收益      
                $list[$key]['receProfit']   = '0.00';       
            }       
            //还款状态      
            switch ($value['status']) {     
                case 1:     
                    $list[$key]['paymentStatus'] = 0;       
                    break;      
                case 2:     
                    $list[$key]['paymentStatus'] = 1;       
                    break;      
                case 3:     
                    $list[$key]['paymentStatus'] = 2;       
                    break;      
                default:        
                    break;      
            }       
            //罚息        
            $list[$key]['punitive'] = sprintf('%.2f',$value['late_charge']);        
        }
        
        //总计        
        $repossPrincipal = 0.00;        
        $repossProfit    = 0.00;        
        $recePrincipal   = 0.00;        
        $receProfit      = 0.00;        
        $punitive        = 0.00;        
        foreach ($list as $key => $value) {     
            //待收本金  
            $repossPrincipal += floatval($value['repossPrincipal']);   
            //待收收益     
            $repossProfit    += floatval($value['repossProfit']);
            //已收本金       
            $recePrincipal   += floatval($value['recePrincipal']); 
            //已收收益
            $receProfit      += floatval($value['receProfit']); 
            //罚息      
            $punitive        += floatval($value['punitive']);       
        }               
        $data = array(      
            'repossPrincipal' => sprintf('%.2f',$repossPrincipal),      
            'repossProfit'    => sprintf('%.2f',$repossProfit),     
            'recePrincipal'   => sprintf('%.2f',$recePrincipal),        
            'receProfit'      => sprintf('%.2f',$receProfit),       
            'punitive'        => sprintf('%.2f',$punitive),     
        );              
        $ret = array(       
            'invester' => $invester,        
            'list'     => $list,        
            'total'    => $data,        
        );
        return $ret;
    }
}