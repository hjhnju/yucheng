<?php
/**
 * logic层抛出的异常
 *  
 * 该异常的getCode、getMessage可直接传递给下游或展现给用户
 */


class Base_Exception_Logic extends Exception {
    /**
     * 额外的错误信息
     * @var array
     */
    protected $arrInfo;
    
    /**
     * 设置额外的错误信息
     * @param arrInfo array
     */
    public function setInfo($arrInfo) {/*{{{*/
        $this->arrInfo = $arrInfo;
    }/*}}}*/

    /**
     * 获取额外的错误信息
     * @return array
     */
    public function getInfo(){/*{{{*/
        return (empty($this->arrInfo)? array(): $this->arrInfo);
    }/*}}}*/

}
