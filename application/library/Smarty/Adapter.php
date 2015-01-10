<?php
class Smarty_Adapter implements Yaf_View_Interface {
    /**
     * Smarty object
     * @var Smarty
     */
    public $_smarty;
    
    /**
     * Constructor
     *
     * @param string $tmplPath            
     * @param array $extraParams            
     * @return void
     */
    public function __construct($tmplPath = null, $extraParams = array()) {
        require "Smarty.class.php";
        $this->_smarty = new Smarty();
        
        if (null !== $tmplPath) {
            $this->_smarty->template_dir = $tmplPath;
        }
        foreach ($extraParams as $key => $value) {
            if ($key == 'plugins_dir') {
                $this->_smarty->addPluginsDir($value);
            } else {
                $this->_smarty->$key = $value;
            }
        }
    }
    
    /**
     * Assign variables to the template
     *
     * Allows setting a specific key to the specified value, OR passing
     * an array of key => value pairs to set en masse.
     *
     * @see __set()
     * @param string|array $spec
     *            The assignment strategy to use (key or
     *            array of key => value pairs)
     * @param mixed $value
     *            (Optional) If assigning a named variable,
     *            use this as the value.
     * @return void
     */
    public function assign($spec, $value = null) {
        if (is_array($spec)) {
            $this->_smarty->assign($spec);
            return;
        }
        
        $this->_smarty->assign($spec, $value);
    }
    
    /**
     * Processes a template and returns the output.
     *
     * @param string $name
     *            The template to process.
     * @return string The output.
     */
    public function render($name, $tpl_vars = NULL) {
        if (defined('ENVIRON') && ENVIRON == 'dev' && @$_GET['smarty'] == 1) {
            $vars = $this->_smarty->getTemplateVars();
            echo "<!--\n";
            print_r($vars);
            echo "\n-->\n";
        }
        return $this->_smarty->fetch($name);
    }
    
    public function display($name, $tpl_vars = NULL) {
        return $this->_smarty->display($name);
    }
    
    public function setScriptPath($view_dir) {
        return $this->_smarty->template_dir = $view_dir;
    }
    
    public function getScriptPath() {
        return $this->_smarty->template_dir;
    }
}
?>
