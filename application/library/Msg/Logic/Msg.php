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
     * 消息未读数
     * @param unknown $uid
     * @return number
     */
    public function getUnread($uid){
        $objMsg = new Msg_List_Msg();
        $num = $objMsg->countAll();
        return $num;
    }
    
    /**
     * 消息标记为已读
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
        $arrObj = $objsMsg->getObjects();
        foreach ($arrObj as $obj){
            $obj->status = Msg_RetCode::MSG_READ;
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
        return $objMsg;
    }
    
    /**
     * 
     * @param unknown $uid
     * @param unknown $intType
     */
    public function getList($uid,$intType){
        $objsMsg = new Msg_List_Msg();
        if(Msg_RetCode::MSG_ALL == $intType){
            $objsMsg->setFilter(array('receiver' => $uid));
        }else{
            $objsMsg->setFilter(array('receiver' => $uid,'status'=>$intType));
        }
        $arrObjs = $objsMsg->getObjects();
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