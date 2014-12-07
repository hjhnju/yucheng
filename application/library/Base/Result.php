<?php 

/**
 * 统一返回格式---简单格式
 * @format [retcode,retmsg,result]
 *
 */
class Base_Result{
    
    protected $_intRetCode = 0;
    protected $_strRetMsg = '';
    protected $_mixResult = null;

    public function getRetCode() {
        return $this->_intRetCode;
    }
    
    /**
     * @param intRetCode int
     * @param strRetMsg string|null
     * @return Base_Result
     */
    public function setRetCode($intRetCode,$strRetMsg = null){
        if($strRetMsg === null){
            $this->_strRetMsg = Logic_Retcode::getMsg($intRetCode);
        }else{
            $this->_strRetMsg = $strRetMsg;
        }
        
        $this->_intRetCode = $intRetCode;

        return $this;
    }
    
    public function setRetMsg($strErrMsg){
        $this->_strRetMsg = $strErrMsg;
    }

    public function getRetMsg() {
        if (empty($this->_strRetMsg)) {
            $this->_strRetMsg = Logic_Retcode::getMsg($this->_intRetCode);
        }
        return $this->_strRetMsg;
    }
    
    public function setResult($mixResut){
        $this->_mixResult = $mixResut;
    }

    public function getResult() {
        return $this->_mixResult;
    }
    
    public function format(){
        return array(
            'retcode' => $this->_intRetCode,
            'retmsg'  => $this->_strRetMsg,
            'result'  => $this->_mixResult,
        );
    }
}

