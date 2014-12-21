<?php
/**
 * 用户页面controller基础类
 * @author jiangsongfang
 */
class Base_Controller_Response extends Base_Controller_Abstract {
    protected $ajax = false;
    
    protected $outputView = 'output.phtml';

    public function init () {
        parent::init();

        $webroot = Base_Config::getConfig('web.root');
        $this->getView()->assign('webroot', $webroot);

        //打日志
        $this->baselog();
    }

    public function redirect($url){
        parent::redirect($url);
    }
    
    /**
     * 获取登录用户的ID
     * @return number
     */
    public function getUserId() {
        return 1;
    }

    /**
     * log for every page
     */
    protected function baselog(){         
        //解析du串
        Base_Log::notice(array(
            'controller' => $this->getRequest()->getControllerName(),
            'action'     => $this->getRequest()->getActionName(),
            //'userid'     => $this->_userid,
            'type'       => 'page',
        ));
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
        Yaf_Dispatcher::getInstance()->disableView();
        @header("Content-Type: application/json; charset=UTF-8");

        $arrRtInfo = array();
        $arrRtInfo['status'] = $status;
        $arrRtInfo['statusInfo'] = $errorMsg;
        $arrRtInfo['data']= $arrData;
        
        $output = json_encode($arrRtInfo);
        $output = str_replace("<","&lt;",$output);
        $output = str_replace(">","&gt;",$output);
        $this->_response->setBody($output);
    }
    
	//将转义后的字符进行decode处理，输出未转义原样
	public function ajaxDecode($arrData = array(), $errorMsg = '', $status = 0){
        Yaf_Dispatcher::getInstance()->disableView();
        @header("Content-Type: application/json; charset=UTF-8");

        $arrRtInfo = array();
        $arrRtInfo['status'] = $status;
        $arrRtInfo['statusInfo'] = $errorMsg;
        $arrRtInfo['data']= $arrData;
        
        $output = json_encode($arrRtInfo);
        $output = str_replace("&lt;","<",$output);
        $output = str_replace("&gt;",">",$output);
        $this->_response->setBody($output);
    }

    public function ajaxRaw($arrData){
        Yaf_Dispatcher::getInstance()->disableView();
        @header("Content-Type: application/json; charset=UTF-8");
        $output = json_encode($arrData);
        $output = str_replace("<","&lt;",$output);
        $output = str_replace(">","&gt;",$output);
        $this->_response->setBody($output);
    }
    
    /**
     * ajax输出 支持HTML格式
     * @param array $arrData
     */
    public function ajaxHTML($arrData){
        Yaf_Dispatcher::getInstance()->disableView();
        @header("Content-Type: application/json; charset=UTF-8");

        $arrRtInfo = array();
        $arrRtInfo['status'] = 0;
        $arrRtInfo['statusInfo'] = '';
        $arrRtInfo['data']= $arrData;
        
        $output = json_encode($arrRtInfo);
        $this->_response->setBody($output);
    }

    public function jsonp($callback = '', $arrData = array(), $errorMsg = '', $status = 0){
        Yaf_Dispatcher::getInstance()->disableView();
        @header("Content-Type: application/javascript; charset=UTF-8");
        $arrRtInfo = array();
        $arrRtInfo['status'] = $status;
        $arrRtInfo['statusInfo'] = $errorMsg;
        $arrRtInfo['data']= $arrData;
        
        $strJsonRet = json_encode($arrRtInfo);
        $strJsonRet = str_replace("<","&lt;",$strJsonRet);
        $strJsonRet = str_replace(">","&gt;",$strJsonRet);
        if($callback){
            $this->_response->setBody($callback.'('.$strJsonRet.');');
        }else{
            $this->_response->setBody($strJsonRet);
        }
    }
   
    public function ajaxError($errorCode = Base_RetCode::UNKNOWN_ERROR, $errorMsg = '', $arrData = array()) {
        Yaf_Dispatcher::getInstance()->disableView();
        @header("Content-Type: application/json; charset=UTF-8");
        $arrRtInfo = array();
        $arrRtInfo['status'] = $errorCode;
        $arrRtInfo['statusInfo'] = empty($errorMsg) ? Base_RetCode::getMsg($errorCode) : $errorMsg;
        $arrRtInfo['data']= $arrData;

        $output = json_encode($arrRtInfo);
        $this->_response->setBody($output);
    }

    public function jsonpError($callback = '', $errorCode = Base_RetCode::UNKNOWN_ERROR, $errorMsg = '', $arrData = array()) {
        Yaf_Dispatcher::getInstance()->disableView();
        @header("Content-Type: application/javascript; charset=UTF-8");
        $arrRtInfo = array();
        $arrRtInfo['status'] = $errorCode;
        $arrRtInfo['statusInfo'] = empty($errorMsg) ? Base_RetCode::getMsg($errorCode) : $errorMsg;
        $arrRtInfo['data']= $arrData;
        $strJsonRet = json_encode($arrRtInfo);
        if($callback){
            $this->_response->setBody($callback.'('.$strJsonRet.');');
        }else{
            $this->_response->setBody($strJsonRet);
        }
    }
}
