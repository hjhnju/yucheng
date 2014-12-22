<?php
/**
 * 所有controller的基类
 * 命名不以controller结尾防止Yaf识别
 * @author hejunhua@baidu.com
 * @since 2014-07 重构
 * @author jiangsongfang
 * @since 2014-12 output
 */
class Base_Controller_Abstract extends Yaf_Controller_Abstract
{

    protected $ajax        = false;
    
    protected $outputView  = 'output.phtml';

    //User_Object实例
    protected $objUser     = null;
    
    //子类增加的base日志字段
    protected $addBaseLogs = array();
    
    public function init(){

        $this->baselog();
        $this->objUser = User_Api::checkLogin();
    }

    private function baselog(){
        $userid    = is_object($this->objUser) ? $this->objUser->userid : 0;
        $logParams = array(
            'module'     => $this->getRequest()->getModuleName(),
            'controller' => $this->getRequest()->getControllerName(),
            'action'     => $this->getRequest()->getActionName(),
            'userid'     => $userid,
        );
        $logParams = array_merge($logParams, $this->addBaseLogs);
        Base_Log::notice($logParams);
    }

    /**
     * 添加base日志里的字段
     * 继承类可以在parent::init前调用以增加日志字段
     * @param $arrParams
     * @return  true
     */
    protected function addBaseLogs($arrParams){
        if(!empty($arrParams)){
            $this->addBaseLogs = array_merge($this->addBaseLogs, $arrParams);
        }
    }
    
    protected function checkParam($param, $data) {
        foreach ($param as $key => $msg) {
            if (empty($data[$key])) {
                $this->outputError(Base_RetCode::PARAM_ERROR, $msg);
                return false;
            }
        }
        return true;
    }
    
    protected function isAjax() {
        if ($this->ajax == true) {
            return true;
        }
        if (!empty($_REQUEST['ajax'])) {
            return true;
        }
        return false;
    }
    
    public function output($arrData = array(), $errorMsg = '', $status = 0){
        if ($this->isAjax()) {
            $this->ajax($arrData, $errorMsg, $status);
        } else {
            $this->_view->assign('output', 1);
            $arrRtInfo = array();
            $arrRtInfo['status'] = $status;
            $arrRtInfo['statusInfo'] = $errorMsg;
            $arrRtInfo['data']= $arrData;
            $this->_view->assign('result', $arrRtInfo);
            
            Yaf_Dispatcher::getInstance()->disableView();
            //$this->_view->render($this->outputView);
            $this->_response->setBody($this->_view->render($this->outputView));
        }
    }
    
    public function outputError($errorCode = Base_RetCode::UNKNOWN_ERROR, $errorMsg = '', $arrData = array()) {
        if ($this->isAjax()) {
            $this->ajaxError($errorCode, $errorMsg, $arrData);
        } else {
            $this->_view->assign('output', 1);
            $arrRtInfo = array();
            $arrRtInfo['status'] = $errorCode;
            $arrRtInfo['statusInfo'] = $errorMsg;
            $arrRtInfo['data']= $arrData;
            $this->_view->assign('result', $arrRtInfo);
            
            Yaf_Dispatcher::getInstance()->disableView();
            //$this->_view->render($this->outputView);
            $this->_response->setBody($this->_view->render($this->outputView));
        }
    }
    
    public function ajax($arrData = array(), $errorMsg = '', $status = 0){
        header("Content-Type: application/json; charset=UTF-8");

        $arrRtInfo = array();
        $arrRtInfo['status'] = $status;
        $arrRtInfo['statusInfo'] = $errorMsg;
        $arrRtInfo['data']= $arrData;
        
        $output = json_encode($arrRtInfo);
        $output = str_replace("<","&lt;",$output);
        $output = str_replace(">","&gt;",$output);
        echo  $output;//htmlspecialchars($objJsonFormat->getResult(),ENT_NOQUOTES);
        die();
    }
    
	//将转义后的字符进行decode处理，输出未转义原样
	public function ajaxDecode($arrData = array(), $errorMsg = '', $status = 0){
        header("Content-Type: application/json; charset=UTF-8");

        $arrRtInfo = array();
        $arrRtInfo['status'] = $status;
        $arrRtInfo['statusInfo'] = $errorMsg;
        $arrRtInfo['data']= $arrData;
        
        $output = json_encode($arrRtInfo);
        $output = str_replace("&lt;","<",$output);
        $output = str_replace("&gt;",">",$output);
        echo  $output;
        die();
    }

    public function ajaxRaw($arrData){
        header("Content-Type: application/json; charset=UTF-8");
        $output = json_encode($arrData);
        $output = str_replace("<","&lt;",$output);
        $output = str_replace(">","&gt;",$output);
        echo  $output;
        die();
    }
    
    /**
     * ajax输出 支持HTML格式
     * @param array $arrData
     */
    public function ajaxHTML($arrData){
        header("Content-Type: application/json; charset=UTF-8");

        $arrRtInfo = array();
        $arrRtInfo['status'] = 0;
        $arrRtInfo['statusInfo'] = '';
        $arrRtInfo['data']= $arrData;
        
        $output = json_encode($arrRtInfo);
        echo $output;
        die();
    }

    public function jsonp($callback = '', $arrData = array(), $errorMsg = '', $status = 0){

        header("Content-Type: application/javascript; charset=UTF-8");
        $arrRtInfo = array();
        $arrRtInfo['status'] = $status;
        $arrRtInfo['statusInfo'] = $errorMsg;
        $arrRtInfo['data']= $arrData;
        
        $strJsonRet = json_encode($arrRtInfo);
        $strJsonRet = str_replace("<","&lt;",$strJsonRet);
        $strJsonRet = str_replace(">","&gt;",$strJsonRet);
        if($callback){
            echo $callback.'('.$strJsonRet.');';
        }else{
            echo $strJsonRet;
        }
        die();
    }
   
    public function ajaxError($errorCode = Base_RetCode::UNKNOWN_ERROR, $errorMsg = '', $arrData = array()) {

        header("Content-Type: application/json; charset=UTF-8");
        $arrRtInfo = array();
        $arrRtInfo['status'] = $errorCode;
        $arrRtInfo['statusInfo'] = empty($errorMsg) ? Base_RetCode::getMsg($errorCode) : $errorMsg;
        $arrRtInfo['data']= $arrData;

        $output = json_encode($arrRtInfo);
        echo $output;
        die();
    }

    public function jsonpError($callback = '', $errorCode = Base_RetCode::UNKNOWN_ERROR, $errorMsg = '', $arrData = array()) {

        header("Content-Type: application/javascript; charset=UTF-8");
        $arrRtInfo = array();
        $arrRtInfo['status'] = $errorCode;
        $arrRtInfo['statusInfo'] = empty($errorMsg) ? Base_RetCode::getMsg($errorCode) : $errorMsg;
        $arrRtInfo['data']= $arrData;
        $strJsonRet = json_encode($arrRtInfo);
        if($callback){
            echo $callback.'('.$strJsonRet.');';
        }else{
            echo $strJsonRet;
        }
        die();
    }
}
