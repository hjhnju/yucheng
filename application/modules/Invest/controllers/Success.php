<?php
/**
 * 投标成功
 */
class SuccessController extends Base_Controller_Response {
	
	public function indexAction() {
	    $sess = Yaf_Session::getInstance();
	    $amount = $sess->get('invest_amount');
	    $name = isset($_REQUEST['name'])?$_REQUEST['name']:'';
	    $this->_view->assign('amount', Base_Util_Number::tausendStyle($amount));
	    $this->_view->assign('name',$name);
	    
	    if(!empty($name)){
    	    $objAngel = new Angel_Object_Angel();
    	    $objUser  = new User_Object_Login();
    	    $logic     = new Awards_Logic_Invite();
 	    
            $objUser->fetch(array('name'=>$name));
            $user = User_Api::getUserObject($objUser->userid);
            
            $objAngel->fetch(array('userid'=>$this->userid,'angelid'=>$user->userid));
            if(empty($objAngel->id)){    	    
        	    $objAngel->userid     = $this->userid;
        	    $objAngel->angelid    = $user->userid;
        	    $objAngel->angelname  = $user->name;
        	    $objAngel->angelcode  = $logic->encode($user->userid);
        	    $objAngel->angelimage = $user->headurl;
        	    $objAngel->save();
            }
	    }
	} 
}
