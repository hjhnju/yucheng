<?php
/**
 * 阿里云OSS云存储的适配器
 * @author jiangsongfang
 *
 */
class Oss_Adapter {
    
    private static $instance = null;
    
    /**
     * 配置的Oss bucket
     * @var string
     */
    private $bucket = '';
    
    /**
     * 阿里云Oss对象
     * @var ALIOSS
     */
    private $obj = null;
    
    private function __construct() {
        $file = __DIR__ . '/sdk.class.php';
        Yaf_Loader::import($file);
        $config = Base_Config::getConfig('oss', CONF_PATH . '/oss.ini');
        $this->obj = new ALIOSS($config['access']['id'], $config['access']['key'], $config['host']);
        $this->bucket = $config['bucket'];
    }
    
    /**
     * 获取全局唯一的OSS适配器
     * @return Oss_Adapter
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 获取文件对象的内容
     * @param string $object
     * @return string|NULL
     */
    public function getContent($object) {
        $options = array(
            //ALIOSS::OSS_FILE_DOWNLOAD => "d:\\cccccccccc.sh",
        );
        $response = $this->obj->get_object($this->bucket, $object, $options);
        if ($response->status == 200) {
            return $response->body;
        }
        return null;
    }
    
    /**
     * 将文件写入到Oss中
     * @param string $object
     * @param string $filename
     * @return boolean
     */
    public function writeFile($object, $filename) {
        try {
            $response = $this->obj->upload_file_by_file($this->bucket, $object, $filename);
        } catch (Exception $ex) {
            $msg = array(
                'info' => 'oss write file wrong',
                'object' => $object,
                'filename' => $filename,
                'ex'    => $ex->getMessage(),
            );
            Base_Log::error($msg);
            return false;
        }
        if ($response->status == 200) {
            return true;
        }
        return false;
    }
    
    /**
     * 将文件内容写入到Oss中
     * @param string $object
     * @param string $content
     * @return boolean
     */
    public function writeFileContent($object, $content) {
        $upload_file_options = array(
            'content' => $content,
            'length' => strlen($content),
        );
        
        $response = $this->obj->upload_file_by_content($this->bucket, $object, $upload_file_options);
        if ($response->status == 200) {
            return true;
        }
        
        $msg = array(
            'info' => 'oss write file content wrong',
            'object' => $object,
            'content' => $content,
        );
        Base_Log::warn($msg);
        return false;
    }
    
    /**
     * 获取文件的meta信息
     * @param string $object
     * @return string|NULL
     */
    public function getMeta($object) {
        $response = $this->obj->get_object_meta($this->bucket, $object);
        if ($response->status == 200) {
            return $response->body;
        }
        return null;
    }
    
    /**
     * 删除Oss上文件对象
     * @param string $object
     * @return boolean
     */
    public function remove($object) {
        $response = $this->obj->delete_object($this->bucket, $object);
        if ($response->status == 204) {
            return true;
        }
        return false;
    }
    
    /**
     * 判断文件是否存在与Oss
     * @param string $object
     * @return boolean
     */
    public function exists($object) {
        $response = $this->obj->is_object_exist($this->bucket, $object);
        if ($response->status == 200) {
            return true;
        }
        return false;
    }
}