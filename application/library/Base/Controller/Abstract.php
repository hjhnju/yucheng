<?php
/**
 * 所有controller的基类
 * 命名不以controller结尾防止Ap识别
 * @author hejunhua@baidu.com
 * @since 2014-07 重构
 */
class Base_Controller_Abstract extends Ap_Controller_Abstract
{
    public function init(){
        Base_Log::notice(array(
            'controller' => Base_Util_Request::filterXss($this->getRequest()->getControllerName()),
            'action'     => Base_Util_Request::filterXss($this->getRequest()->getActionName()),
        ));
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
