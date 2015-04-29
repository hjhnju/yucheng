<?php
/**
 * 消息逻辑层
 * @author jiangsongfang
 *
 */
class Msg_Logic_Msg {
    
    public function __construct(){
    }
    
    /**
     * 获取消息未读数
     * @param string $uid
     * @return number|0
     */
    public function getUnread($uid){
        $objsMsg = new Msg_List_Msg();
        $num = 0;
        $arrObj = array();
        $objsMsg->setFilter(array('receiver'=>$uid));
        $objsMsg->setPagesize(PHP_INT_MAX);
        $arrObj = $objsMsg->toArray();
        $arrObj = $arrObj['list'];
        if(empty($arrObj)){
            return $num;
        }
        foreach ($arrObj as $obj){
            if(Msg_RetCode::MSG_UNREAD == $obj['status']){
                $num += 1;
            }
        }
        return $num;
    }
    
    /**
     * 将消息标记为已读
     * @param  $mid
     */
    public function setRead($mid){
        $objMsg = new Msg_Object_Msg();
        $objMsg->fetch(array('mid'=>$mid));
        $objMsg->status = Msg_RetCode::MSG_READ;
        $ret = $objMsg->save();
        return $ret;
    }
    
    /**
     * 
     * @param unknown $uid
     */
    public function setReadAll($uid){
        $objsMsg = new Msg_List_Msg();
        $objsMsg->setFilter(array('receiver'=>$uid));
        $objsMsg->setPagesize(PHP_INT_MAX);
        $arrObj = $objsMsg->getObjects();
        foreach ($arrObj as $obj){
            if(Msg_RetCode::MSG_REMOVE !== $obj->status){
                $obj->status = Msg_RetCode::MSG_READ;
            }
            $ret = $obj->save();
            if(!$ret){
                return $ret;
            }
        }
        return true;
    }
    
    /**
     * 
     * @param unknown $mid
     */
    public function getDetail($mid){
        $objMsg = new Msg_Object_Msg();
        $objMsg->fetch(array('mid'=>$mid));
        if(empty($objMsg)){
            return array();
        }
        $arrRet = array(
            'title'   => $objMsg->title,
        	'content' => $objMsg->content,
        );
        return $arrRet;
    }
    
    /**
     * 
     * @param unknown $uid
     * @param unknown $intType
     */
    public function getList($uid,$intType,$intPage,$intPageSize){
        $objsMsg = new Msg_List_Msg();
        if(Msg_RetCode::MSG_ALL == $intType){
            $objsMsg->setFilterString("receiver = $uid and status != -1");
            $objsMsg->setPage($intPage);
            $objsMsg->setPagesize($intPageSize);
        }else{
            $objsMsg->setFilter(array('receiver' => $uid,'status'=>$intType));
            $objsMsg->setPage($intPage);
            $objsMsg->setPagesize($intPageSize);
        }
        $arrObjs = $objsMsg->toArray();
        //$arrObjs = $objsMsg->getObjects();
        return $arrObjs;
    }
    
    /**
     * 
     * @param unknown $mid
     */
    public function del($mid){
        $objMsg = new Msg_Object_Msg();
        $objMsg->fetch(array('mid'=>$mid));
        $objMsg->status = Msg_RetCode::MSG_REMOVE;
        $ret = $objMsg->save();
        return $ret;
    }
    
    /**
     * 
     * @param unknown $uid
     */
    public function delAll($uid){
        $objsMsg = new Msg_List_Msg();
        $objsMsg->setFilter(array('receiver'=>$uid));
        $arrObj = $objsMsg->getObjects();
        foreach ($arrObj as $obj){
            $obj->status = Msg_RetCode::MSG_REMOVE;
            $ret = $obj->save();
            if(!$ret){
                return $ret;
            }
        }
        return true;
    }
}
