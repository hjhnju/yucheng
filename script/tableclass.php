<?php
$host = 'xingjiaodai.mysql.rds.aliyuncs.com';
$user = 'xingjiaodai';
$pass = 'xingjiaodai';
$dbname = 'xjd';
$tb_pre = 'user';
$tb_class = 'User_Object_';
//$save_path = '/home/work/user/hejh/yucheng/application/library/User';
$save_path = '/Users/hejunhua/Dev/yucheng/application/library/User';
$author = 'hejunhua';

if(!file_exists($save_path)){
    mkdir($save_path, 0775); 
}
if(!file_exists($save_path . '/Object')){
    mkdir($save_path . '/Object', 0775); 
}
if(!file_exists($save_path . '/List')){
    mkdir($save_path . '/List', 0775); 
}



mysql_connect($host, $user, $pass);
mysql_select_db($dbname);
mysql_query("set names utf8");

function getAll($sql) {
	$result = mysql_query($sql);
	$data = array();
	while ($row = mysql_fetch_array($result)) {
		$data[] = $row;
	}
	return $data;
}

$types = array(
	'int' => 'integer',
	'varchar' => 'string',
	'tinyint' => 'integer',
	'bigint' => 'integer',
	'smallint' => 'integer',
	'decimal' => 'number',
	'text' => 'string',
	'timestamp' => 'string',
	'date' => 'string',
);

$sql = "select * from information_schema.tables where table_schema='$dbname'";
if (!empty($tb_pre)) {
	$sql .= " and table_name like '%$tb_pre%'";
}
$tables = getAll($sql);
//mysql_select_db('information_schema');
foreach($tables as $table) {
	$tbname = $table['TABLE_NAME'];
	$sql = "select * from information_schema.columns where table_schema='$dbname' and table_name='$tbname'";
	$columns = getAll($sql);
	$tb = str_replace($tb_pre . '_', '', $tbname);
	dumpClass($table, $tb, $columns);
	dumpList($table, $tb, $columns);
}

function dumpClass($table, $tb, $columns) {
	global $save_path, $tb_pre, $tb_class, $types, $author;
	$tbname = $table['TABLE_NAME'];
	$tbclass = $tb_class . ucfirst($tb);
	$content = '<?php';
	$content .= "\n";
	$content .= "/**\n";
	$content .= " * $table[TABLE_COMMENT]\n";
	$content .= " * @author $author\n";
	$content .= " */\n";
	$content .= "class $tbclass extends Base_Object {\n";
	$colary = array();
	$prop = array();
	$intary = array();
	$maxlen = 0;
	foreach ($columns as $col) {
		if (strlen($col['COLUMN_NAME']) > $maxlen) {
			$maxlen = strlen($col['COLUMN_NAME']);
		}
	}
	
	foreach ($columns as $col) {
		$colary[] = $col['COLUMN_NAME'];
		if ($col['COLUMN_KEY'] == 'PRI') {
			$prikey = $col['COLUMN_NAME'];
		}
		$colname = getprop($col['COLUMN_NAME']);
		$tabs = gettabs($maxlen, strlen($col['COLUMN_NAME']));
		$prop[] = "        '$col[COLUMN_NAME]'$tabs=> '$colname',\n";
		
		$type = $col['COLUMN_TYPE'];
		$type = preg_replace('/\(.*?\)/', '', $type);
		$type = $types[$type];
		if ($type == 'integer') {
			$intary[] = "        '$col[COLUMN_NAME]'$tabs=> 1,\n";
		}
	}
	$props = implode("", $prop);
	
	$content .= "    /**\n";
	$content .= "     * 数据表名\n";
	$content .= "     * @var string\n";
	$content .= "     */\n";
	$content .= "    protected \$table = '$tbname';\n";
	$content .= "\n";
	
	$content .= "    /**\n";
	$content .= "     * 主键\n";
	$content .= "     * @var string\n";
	$content .= "     */\n";
	$content .= "    protected \$prikey = '$prikey';\n";
	$content .= "\n";
	
	$content .= "    /**\n";
	$content .= "     * 类名\n";
	$content .= "     * @var string\n";
	$content .= "     */\n";
	$content .= "    const CLASSNAME = '$tbclass';\n";
	$content .= "\n";
	
	$colstr = implode("', '", $colary);
	$content .= "    /**\n";
	$content .= "     * 对象包含的所有字段\n";
	$content .= "     * @var array\n";
	$content .= "     */\n";
	$content .= "    protected \$fields = array('$colstr');\n";
	$content .= "\n";
	
	$content .= "    /**\n";
	$content .= "     * 字段与属性隐射关系\n";
	$content .= "     * @var array\n";
	$content .= "     */\n";
	$content .= "    public \$properties = array(\n$props    );\n";
	$content .= "\n";
	
	$intstr = implode("", $intary);
	$content .= "    /**\n";
	$content .= "     * 整数类型的字段\n";
	$content .= "     * @var array\n";
	$content .= "     */\n";
	$content .= "    protected \$intProps = array(\n$intstr    );\n";
	$content .= "\n";
	
	$content .= "    /**\n";
	$content .= "     * @param array \$data\n";
	$content .= "     * @return $tbclass\n";
	$content .= "     */\n";
	$content .= "    public static function init(\$data) {\n";
	$content .= "        return parent::initObject(self::CLASSNAME, \$data);\n";
	$content .= "    }\n";
	$content .= "\n";
	
	$example = array();
	foreach ($columns as $col) {
		$type = $col['COLUMN_TYPE'];
		$type = preg_replace('/\(.*?\)/', '', $type);
		$type = $types[$type];
		$colname = getprop($col['COLUMN_NAME']);
		
		$content .= "    /**\n";
		$content .= "     * $col[COLUMN_COMMENT]\n";
		$content .= "     * @var $type\n";
		$content .= "     */\n";
		//var_dump($col);
		$content .= "    public \$$colname;\n";
		$content .= "\n";
		
		$val = "''";
		if ($type == 'integer') {
			$val = 0;
		}
		if ($col['COLUMN_DEFAULT'] != null) {
			$val = "'" . $col['COLUMN_DEFAULT'] . "'";
		}
		$example[] = "'$col[COLUMN_NAME]' => $val,\n";
	}
	$content .= "}\n";
	
	$filename = $save_path . '/Object/' . ucfirst($tb) . ".php";
	file_put_contents($filename, $content);
	//var_dump($filename);
	echo $content;
	echo "@examples\n";
	echo implode("", $example);
}

function dumpList($table, $tb, $columns) {
	global $save_path, $tb_pre, $tb_class, $types;
	$tbname = $table['TABLE_NAME'];
	$tbclass = ucfirst($tb_pre) . '_List_' . ucfirst($tb);
	$content = '<?php';
	$content .= "\n";
	$content .= "/**\n";
	$content .= " * $table[TABLE_COMMENT] 列表类\n";
	$content .= " * @author $author\n";
	$content .= " */\n";
	$content .= "class $tbclass extends Base_List {\n";
	$colary = array();
	$prop = array();
	$intary = array();
	$maxlen = 0;
	foreach ($columns as $col) {
		if (strlen($col['COLUMN_NAME']) > $maxlen) {
			$maxlen = strlen($col['COLUMN_NAME']);
		}
	}
	foreach ($columns as $col) {
		$colary[] = $col['COLUMN_NAME'];
		if ($col['COLUMN_KEY'] == 'PRI') {
			$prikey = $col['COLUMN_NAME'];
		}
		$colname = getprop($col['COLUMN_NAME']);
		$tabs = gettabs($maxlen, strlen($col['COLUMN_NAME']));
		$prop[] = "        '$col[COLUMN_NAME]'$tabs=> '$colname',\n";
		
		$type = $col['COLUMN_TYPE'];
		$type = preg_replace('/\(.*?\)/', '', $type);
		$type = $types[$type];
		if ($type == 'integer') {
			$intary[] = "        '$col[COLUMN_NAME]'$tabs=> 1,\n";
		}
	}
	$props = implode("", $prop);
	
	$content .= "    /**\n";
	$content .= "     * 数据表名\n";
	$content .= "     * @var string\n";
	$content .= "     */\n";
	$content .= "    protected \$table = '$tbname';\n";
	$content .= "\n";
	
	$content .= "    /**\n";
	$content .= "     * 主键\n";
	$content .= "     * @var string\n";
	$content .= "     */\n";
	$content .= "    protected \$prikey = '$prikey';\n";
	$content .= "\n";
	
	$colstr = implode("', '", $colary);
	$content .= "    /**\n";
	$content .= "     * 对象包含的所有字段\n";
	$content .= "     * @var array\n";
	$content .= "     */\n";
	$content .= "    protected \$fields = array('$colstr');\n";
	$content .= "\n";
	
	$intstr = implode("", $intary);
	$content .= "    /**\n";
	$content .= "     * 整数类型的字段\n";
	$content .= "     * @var array\n";
	$content .= "     */\n";
	$content .= "    protected \$intProps = array(\n$intstr    );\n";
	$content .= "\n";
	
	$content .= "}";
	$filename = $save_path . '/List/' . ucfirst($tb) . ".php";
	file_put_contents($filename, $content);
	//var_dump($filename);
	echo $content;
}

function gettabs($maxlen, $len) {
	$space = ceil($maxlen/4) * 4;
	$rest = $space - $len;
	return str_repeat(' ', $rest);
}

function getprop($colname) {
	$tmp = explode('_', $colname);
	for($i = 1; $i < count($tmp); $i++) {
		$tmp[$i] = ucfirst($tmp[$i]);
	}
	$colname = implode($tmp);
	return $colname;
}
