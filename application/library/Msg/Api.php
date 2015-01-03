<?php 
class Msg_Api {
    /**
     * 发送系统消息
     * @param integer $fromid
     * @param integer $toid
     * @param string  $title
     * @param string  $content
     * @param string  $type：消息类型
     * @return true|false 成功true, 失败 false
     */
    public static function sendmsg($fromid, $toid, $type,$title,$content) {
        $objMsg = new Msg_Object_Msg();
        $objMsg->sender    = $fromid;
        $objMsg->receiver  = $toid;
        $objMsg->type      = $type;
        $objMsg->title     = $title;
        $objMsg->content   = $content;
        $ret = $objMsg->save();
        Base_Log::notice(array(
        	'msg'    => 'msg send',
        	'fromid' => $fromid,
        	'toid'   => $toid,
        	'title'  => $title,
        ));
        return $ret;
    }
}