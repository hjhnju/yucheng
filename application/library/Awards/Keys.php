<?php
class Awards_Keys {

    const NXKEY_RECEIVE = 'awards_userid_%s_ownid_%s';

    public static function getReceiveNxKey($userid, $ownerid){
        return sprintf(self::NXKEY_RECEIVE, $userid, $ownerid);
    }

}
