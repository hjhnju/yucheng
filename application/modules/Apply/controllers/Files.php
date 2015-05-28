<?php
class FilesController extends Base_Controller_Page{
    protected $needLogin = false;
    public function indexAction() {
        //根据url中的参数获得相应的数据
        $apply_id = $_REQUEST['apply_id'];
        $objApply = new Apply_Logic_Apply();
        $list = $objApply->getApplyList('1', '1', array('id'=>$apply_id));
        $list = $list['list'][0];
        $obj  = new Apply_Logic_Calculate($list['origin']['apply']['amount'], 
                                         $list['origin']['apply']['duration'], 
                                         $list['origin']['apply']['duration_type']);
        $data = array(
            'file_type'         => Apply_Type_File::$names,
            'apply_amount'      => $list['apply']['amount'],
            'apply_duration'    => $list['apply']['duration'],
            'apply_service'     => $obj->getService(),
            'apply_refund'      => $obj->getRefundMonth(),
        );
        
        $this->getView()->assign('data', $data);
    }

    /**
     * 上传图片阿里云
     * @return [type] [返回状态码，成功或者失败]
     */
    public function submitAction() {
        $objUser = User_Api::checkLogin();
        $_POST['userid'] = $objUser->userid;

        $logic = new Apply_Logic_Attach();
        $logic->saveFile($_POST);
    }
}