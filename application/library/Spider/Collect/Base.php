<?php
/**
 * Class Spider_Collect_base
 * 提取网页内容的基本类
 */
class Spider_Collect_Base{
    //需要解析的文件夹位置
    static $_path;

    //需要解析的文件夹名的后缀
    static $_separator;

    //当前类的实例对象
    private static $_instance;

    private $except = array('.', '..');

    /**
     * @param null
     * @return 返回当前类的实例，并且初始化变量
     */
    public static function getInstance(){
        if (empty(self::$_instance)){
            self::$_instance = new self();
            //获取文件夹的位置
            $graber = new Spider_Grab_Base();
            self::$_path     = $graber->_path;
            self::$_separator = $graber->_separator;
        }

        return self::$_instance;
    }

    /**
     * @param $path 文件路径
     * @return 返回路径下的文件列表
     */
    public function parseFolder(){
        $folders = $this->parseOneFolder(self::$_path);
        $dirs  = $folders['dirs'];
        if (!empty($dirs)){
            foreach ($dirs as $key=>$item){
                $current = $this->getCurrentFolderName($item);
                if(is_dir($item)) {
                    $next_dirs = $this->parseOneFolder($item);
                    foreach ($next_dirs['dirs'] as $k=>$i){
                        $read = $this->parseOneFolder($i);
                        $next = $this->getCurrentFolderName($i);
                        $output[$current][$next] = $read['files'];
                    }
                }
            }
        }

        return $output;
    }

    /**
     * @param $path 需要解析的路径
     * @return 返回当前路径的文件夹名字
     */
    public function getCurrentFolderName($path){
        $dirs = explode('/', $path);

        return end($dirs);
    }

    /**
     * @param $path   解析单个路径
     * @return 返回单个路径下的文件
     */
    public function parseOneFolder($path){
        if (substr($path, -1) == '/'){
            $path = substr($path, 0, strlen($path)-1);
        }
        $returns = array(
            'dirs'   => array(),
            'files' => array()
        );
        $files = scandir($path);
        foreach ($files as $name){
            if (in_array($name, $this->except)){
                continue;
            }
            $name = $path.'/'.$name;
            if (is_dir($name)){
                $returns['dirs'][] = $name;
            }

            if (is_file($name) && is_readable($name)){
                $returns['files'][] = $name;
            }
        }

        return $returns;
    }

    /**
     * @param $province 省份
     * @param $type     学校类型
     * @return 返回解析过后的数据，以数组的形式,如果给了省份，则返回所有省份的学校，如果给了类型，那么会返回
     * 该省份下，该类型的学校
     */
    public function readAllFiles($province='', $type=''){
        $final = array();
        $lists = $this->parseFolder();
        if ($province != ''){
            if ($type != ''){
                foreach ($lists[$province][$type] as $file){
                    $content = $this->readOneFile($file);
                    $final[$province][$type][] = $content;
                }
            }else {
                foreach ($lists[$province] as $type=>$schools){
                    foreach ($schools as $file){
                        $content = $this->readOneFile($file);
                        $final[$province][$type][] = $content;
                    }
                }
            }

        }else {
            foreach ($lists as $province=>$list){
                foreach ($list as $type=>$schools){
                    foreach ($schools as $file){
                        $content = $this->readOneFile($file);
                        $final[$province][$type][] = $content;
                    }
                }
            }
        }

        return $final;
    }

    /**
     * @param $filename 读取一个文件的内容
     * @return 返回该文件内容
     */
    public function readOneFile($filename){
        $content = file_get_contents($filename);

        return (array)json_decode($content);
    }
}