<?php
class DelpicAction extends Yaf_Action_Abstract{
	public function execute(){
		if(isset($_GET['id'])){		
		   //删除借款附件（图片）
		   $attachId = $_GET['id'];
		   $objLoanAtta = new Loan_Object_Attach($attachId);
		   //先逻辑删除然后物理删除
		   $flag1 = $objLoanAtta->remove();
		   $flag2 = $objLoanAtta->erase();
		   if($flag1 && $flag2){
		   	echo "<font color = 'red'>删除成功</font>";
		   }
		}
		//关闭模板自动渲染
		Yaf_Dispatcher::getInstance()->autoRender(FALSE);
	}
}