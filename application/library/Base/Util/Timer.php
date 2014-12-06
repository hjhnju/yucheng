<?php

class Base_Util_Timer {
    private static $_arrTimerSet = array();
    
    /**
     * 返回当前时间戳
     *
     * @return int
     */
    private static function getMicrotime() {
        list($usec, $sec) = explode(" ", microtime());
        $usec = (float)$usec;
        $usec = $usec * 1000;
        
        $sec = (float)$sec;
        $sec = ($sec % 10000) * 1000;
        
        $v = intval($usec + $sec);
        return $v;
    }
    
    /**
     * 开始计时
     *
     * @param string name
     * @return null
     */
    public static function start($name) {
        $name = trim($name);
        if (strlen($name)<1) {
            throw new Base_Exception_Runtime("time name is empty");
        }
        
        self::$_arrTimerSet[$name] = self::getMicrotime();
    }

    /**
     * 结束计时
     *
     * @param string name
     * @return integer ms
     */
    public static function stop($name) {
        $name = trim($name);
        if (strlen($name)<1) {
            throw new Base_Exception_Runtime("time name is empty");
        }
        if (!array_key_exists($name, self::$_arrTimerSet)) {
            return 0;
        }
        $current = self::getMicrotime();
        $old = self::$_arrTimerSet[$name];
        unset(self::$_arrTimerSet[$name]);
        return $current - $old;
    }
}
