<?php
/**
 * @name ErrorController
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 * @author root
 */
class ErrorController extends Base_Controller_Page {

	public function init(){
		$this->setNeedLogin(false);
		parent::init();
	}

    public function errorAction($exception){
        $code    = $exception->getCode();
        $message = $exception->getMessage();
        Base_Log::notice(array('code'=>$code, 'message'=>$message));

        if(ENVIRON !== 'product'){
            $this->getView()->assign("code", $exception->getCode());
            $this->getView()->assign("message", $exception->getMessage());
        }
    }
}
