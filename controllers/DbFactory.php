<?php
namespace app\controllers;
header("Content-Type: application/json;charset=utf-8");

/**
 * 自己编的yii2框架扩展工厂类
 */

class DbFactory {

	private static $_instance;

	private function __construct() {}
	private function __clone() {}
	//单例
	public static function getinstance() {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	//操作有误，错误代码打印
	public function err($error) {
		die("对不起，您的操作有误，错误原因为：".$error);
	}
	/*
	连接数据库代码
	 */
	public function DatabaseConnection() {
		$connection = \Yii::$app->db;
		$host       = substr($connection->dsn, 10, 10);
		$host       = substr($host, 1, 10);
		$dbname     = substr($connection->dsn, 28);
		$username   = $connection->username;
		$password   = $connection->password;
		$con        = mysqli_connect($host, $username, $password, $dbname);
		if (mysqli_connect_error($con)) {
			return "Failed to connect to database: ".mysqli_connect_error();
		}
		return $con;
	}
	//sql注入防范(基本)，传入想要校验的数据
	public function dbSqlProtected($value) {
		$connection = $this->DatabaseConnection();
		$value      = mysqli_real_escape_string($connection, $value);
		$value      = $this->xssChecked($value);
		mysqli_close($connection);
		return $value;
	}
	/*
	xss攻击的防范，传入想要校验的数据
	 */
	public function xssChecked($checkValue) {
		$checked = \yii\helpers\HtmlPurifier::process($checkValue);
		return $checked;
	}
	/*
	通过sql进行搜索，在之前进行sql注入的判断
	 */
	public function findBySql($sql) {
		$sql        = $this->dbSqlProtected($sql);
		$connection = \Yii::$app->db;
		$command    = $connection->createCommand($sql)->queryAll();
		return $command;
	}
	//数量
	public function tableCount($table) {
		$connection = \Yii::$app->db;
		$command    = $connection->createCommand('SELECT COUNT(*) FROM '.$table);
		$Count      = $command->queryScalar();
		return $Count;
	}
	/*
	 *通用通过主键查找数据条的单个属性内容，删除数据，tables为表，thekey是主键的名称，findid为主键值
	 */
	public function deleteOneRecord($tables, $theKey, $findId) {
		$findId     = $this->dbSqlProtected($findId);
		$connection = \Yii::$app->db;
		$sql        = 'DELETE FROM '.$tables.' WHERE '.$theKey.' = \''.$findId.'\'';
		$command    = $connection->createCommand($sql)->execute();
		return true;
	}
	/*
	 *通用通过主键查找数据条的单个属性内容，返回给command，findWhichOne为要查的什么属性，tables为表，thekey是主键的名称，findid为主键值
	 */
	public function findOnlyOne($findWhichOne, $tables, $theKey, $findId) {
		$findId     = $this->dbSqlProtected($findId);
		$connection = \Yii::$app->db;
		$sql        = 'SELECT '.$findWhichOne.' FROM '.$tables.' WHERE '.$theKey.' = \''.$findId.'\'';
		$command    = $connection->createCommand($sql)->queryColumn();
		return $command['0'];
	}
	//找到表中一个主键对应所有的数据，tables为表，thekey是主键的名称，findid为主键值
	public function findAllThings($tables, $theKey, $findId) {
		$findId     = $this->dbSqlProtected($findId);
		$connection = \Yii::$app->db;
		$sql        = 'SELECT * FROM '.$tables.' WHERE '.$theKey.' = '.$findId;
		$command    = $connection->createCommand($sql)->queryAll();
		return $command;
	}
	//插入数据，table为表名，为传入的数组
	public function insertIntoDb($table, $arr) {
		foreach ($arr as $key => $value) {
			$value      = $this->dbSqlProtected($value);
			$keyArr[]   = '`'.$key.'`';
			$keyValue[] = '\''.$value.'\'';
		}
		$keys       = implode(',', $keyArr);
		$values     = implode(',', $keyValue);
		$connection = \Yii::$app->db;
		$sql        = 'insert into '.$table.'('.$keys.') values('.$values.')';
		return $this->doQuery($sql);
	}
	//更新数据
	public function updateTheDbRecord($table, $KeyName, $id, $arrUpdate) {
		foreach ($arrUpdate as $key => $value) {
			$value            = $this->dbSqlProtected($value);
			$keyAndvalueArr[] = "`".$key."`='".$value."'";
		}
		$keyAndValue = implode(',', $keyAndvalueArr);
		$sql         = 'update '.$table.' set '.$keyAndValue.' where '.$KeyName.' = '.$id;
		$query       = $this->doQuery($sql);
		return $query;
	}
	//query查询
	public function doQuery($sql) {
		$connection = $this->DatabaseConnection();
		if (!($query = mysqli_query($connection, $sql))) {//使用mysql_query函数执行sql语句
			$this->err($sql."<br />".mysqli_error($connection));//mysql_error 报错
		} else {
			return $query;
		}
	}
	/**
	 *列表
	 *
	 *@param source $query sql语句通过mysql_query 执行出来的资源
	 *@return array   返回列表数组
	 **/
	function findAll($query) {
		while ($rs = mysqli_fetch_array($query, MYSQL_ASSOC)) {//mysql_fetch_array函数把资源转换为数组，一次转换出一行出来
			$list[] = $rs;
		}
		return isset($list)?$list:"";
	}
}