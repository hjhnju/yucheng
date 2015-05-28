 <?php
class Awards_Logic_Invite {

    const STARTUP = 999;
    
    /**
     * 获取个人邀请url
     * @param integer $userid
     * @return string $url, such as 'http://www.xingjiaodai.com/i/{$code}'
     */
    public function getInviteUrl($userid) {
        $code = $this->encode($userid);
        return Base_Config::getConfig('web')->root . "/i/$code";
    }

    /**
     * 生成个人邀请码
     * @param int $userid
     * @return string $code
     */
    public function encode($userid){
        $strCode = Base_Util_Number::dec2Any($userid + self::STARTUP, 62);
        return $strCode;
    }

    /**
     * 反解个人邀请码
     * @param string $strCode
     * @param int $userid
     */
    public function decode($strCode){
        $intId = Base_Util_Number::any2Dec($strCode, 62);
        $intId = $intId - self::STARTUP;
        $intId = $intId > 0 ? $intId : 0;
        return $intId;
    }
}

