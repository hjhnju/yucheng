<?php
class ApiController extends Base_Controller_Api{
    
    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }
    
    /**
     * 为用户添加天使接口
     */
	public function addAction() {
	    $angelCode = isset($_REQUEST['code'])?$_REQUEST['code']:'';
	    $logic     = new Awards_Logic_Invite();
	    $intUserid = $logic->decode($angelCode);
	    $objUser = User_Api::getUserObject($intUserid);
	    $userid = $objUser->userid;
	    if(empty($userid)){
	        return $this->ajaxError(Angel_RetCode::ANGEL_CODE_WRONG,Angel_RetCode::getMsg(Angel_RetCode::ANGEL_CODE_WRONG));
	    }
        $objAngel = new Angel_Object_Angel();
        $objAngel->userid     = $this->userid;
        $objAngel->angelid    = $objUser->userid;
        $objAngel->angelcode  = $angelCode;
        $objAngel->angelname  = $objUser->name;
        $objAngel->angelimage = $objUser->headurl;
        $bRet = $objAngel->save();
        if($bRet){
            return $this->ajax();
        }
        return $this->ajaxError();
	}
	
	/**
	 * 查询用户天使接口
	 */
	public function listAction(){
	    $objAngel = new Angel_List_Angel();
	    $objAngel->setFilter(array("userid"=>$this->userid));
	    $arrRet = $objAngel->getData();
	    if(!empty($arrRet)){
    	    $loan = new Loan_Object_Loan();
    	    $loan->fetch(array('status'=>Loan_Type_LoanStatus::LENDING));
    	    $url = $this->webroot."/invest/angeldetail?id=$loan->id";
    	    foreach ($arrRet as $key => $val){
    	        $arrRet[$key]['url'] = $url;
    	    }
	    }
	    $this->ajax($arrRet);
	}
}
