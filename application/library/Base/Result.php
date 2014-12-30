<?php 
/**
 * 统一返回格式
 * @format [status,statusInfo,data]
 *
 */
class Base_Result{
    
    public $status;

    public $statusInfo;

    public $data;

    public function __construct($status = Base_RetCode::SUCCESS, $data = null,
        $statusInfo = ''){

        $this->data       = $data;
        $this->status     = $status;
        $this->statusInfo = $statusInfo; 
    }
   
    public function format(){
        return array(
            'status'     => $this->status,
            'statusInfo' => $this->statusInfo,
            'data'       => $this->data,
        );
    }
}


