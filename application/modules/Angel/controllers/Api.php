<?php
class ApiController extends Base_Controller_Api{
    
    const PAGE_SIZE = 11;
    
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
	    $type = $objUser->usertype;
	    if($type != User_Type_Roles::TYPE_ANGEL){
	        return $this->ajaxError(Angel_RetCode::ANGEL_CODE_WRONG,Angel_RetCode::getMsg(Angel_RetCode::ANGEL_CODE_WRONG));
	    }
	    $objAngel = new Angel_Object_Angel();
	    $objAngel->fetch(array('userid'=>$this->userid,'angelid'=>$intUserid));	  
	    $arrRet = $objAngel->toArray();  
	    if(!empty($arrRet['userid'])){
	        return $this->ajaxError(Angel_RetCode::ANGEL_EXSIT,Angel_RetCode::getMsg(Angel_RetCode::ANGEL_EXSIT));
	    }
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
	    $page = isset($_REQUEST['page'])?intval($_REQUEST['page']):0;
	    $objAngel = new Angel_List_Angel();
	    $objAngel->setFilter(array("userid"=>$this->userid));
	    $objAngel->setPage($page);
	    $objAngel->setPagesize(self::PAGE_SIZE);
	    $arrRet = $objAngel->toArray();
	    if(!empty($arrRet['list'])){
    	    $loan = new Loan_Object_Loan();
    	    $loan->fetch(array('status'=>Loan_Type_LoanStatus::LENDING));
    	    $url = $this->webroot."/invest/angeldetail?id=$loan->id";
    	    foreach ($arrRet['list'] as $key => $val){
    	        $arrRet['list'][$key]['url'] = $url."&angel=".$val['angelcode'];
    	    }
	    }
	    $this->ajax($arrRet);
	}
	
	/**
	 * 根据用户名返回对应的包含天使码的投资详情页链接
	 */
	public function getUrlAction(){
	    $name = isset($_REQUEST['name'])?$_REQUEST['name']:'';
	    $objUser = new User_Object_Login();
	    $objUser->fetch(array('name'=>$name));
	    if($objUser->usertype !== User_Type_Roles::TYPE_ANGEL){
	        return $this->ajaxError();
	    }	    
	    $loan = new Loan_Object_Loan();
	    $loan->fetch(array('status'=>Loan_Type_LoanStatus::LENDING));
	    $url   = $this->webroot."/invest/angeldetail?id=$loan->id";
	    $logic = new Awards_Logic_Invite();
	    $code  = $logic->encode($objUser->userid);
	    $url  .= "&angel=".$code;
	    return $this->ajax($url);
	}
}
