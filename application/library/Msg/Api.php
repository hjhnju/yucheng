<?php 
class Msg_Api {
    /**
     * 发送系统消息
     * @param integer $uid
     * @param integer $title
     * @param integer $content
     * @return integer|false 成功 消息ID 失败 false
     */
    public static function sendmsg($uid, $title, $content) {
        return 1;
    }
}