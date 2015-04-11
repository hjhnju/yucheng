<?php
/**
 * 统计模块API接口
 * @author hejunhua
 *
 */
class Stats_Api {

    /**单笔投资排行*/
    public static function perInvestRanking($page, $pageSize, 
        $startTime = 0, $endTime = 0){
        $list = new Invest_List_Invest();
        $list->setPage($page);
        $list->setPagesize($pageSize);

        $arrWhere = array();
        if($startTime > 0){
            $arrWhere[] = ' create_time > ' . $startTime;
        }
        if($endTime > 0){
            $arrWhere[] = ' create_time < ' . $endTime;
        }
        if(!empty($arrWhere)){
            $where = implode(' and ', $arrWhere);
            $list->setFilterString($where);
        }

        $list = $list->toArray();
        return $list;
    }



}
