<?php
namespace app\models;
use app\controllers\DbFactory;
header("Content-Type: application/json;charset=utf-8");

/**
 * This is the model class for table "user_tb".
 *
 * @property string $XH_ID		用户主键（学号）
 * @property string $XH_PW		用户密码
 * @property string $Name 		姓名
 * @property string $phone 		联系方式
 * @property integer $status 	权限
 */

class UserTb extends \yii\db\ActiveRecord {

	public $username;
	public $password;
	public $loginError;
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'user_tb';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['XH_ID', 'XH_PW', 'Name', 'status'], 'required'],
			[['status'], 'integer'],
			[['XH_ID'], 'string', 'max' => 10],
			[['XH_PW'], 'string', 'max' => 128],
			[['Name'], 'string', 'max'  => 8],
			[['phone'], 'string', 'max' => 16]
		];
	}
	//取出权限
	public function getAuthority($id) {
		$Dbfactory = DbFactory::getinstance();
		$id        = $Dbfactory->dbSqlProtected($id);
		$password  = $Dbfactory->findOnlyOne('status', 'user_tb', 'XH_ID', $id);
		return $password;
	}
	//取出联系方式
	public function getPhone($id) {
		$Dbfactory = DbFactory::getinstance();
		$id        = $Dbfactory->dbSqlProtected($id);
		$password  = $Dbfactory->findOnlyOne('phone', 'user_tb', 'XH_ID', $id);
		return $password;
	}
	//取出姓名
	public function getName($id) {
		$Dbfactory = DbFactory::getinstance();
		$id        = $Dbfactory->dbSqlProtected($id);
		$name      = $Dbfactory->findOnlyOne('Name', 'user_tb', 'XH_ID', $id);
		return $name;
	}
	//登录逻辑
	public function handleLogin($username, $password) {
		$this->getLoginMess($username, $password);
		//过滤
		$this->processtheLoginmessage();
		//与数据库交换
		$password = $this->getpassword();
		//登录
		$this->handlepassword($password);
		//loginError
		if ($this->loginError == '') {
			$authority = $this->getAuthority($username);
			//存入session，登录成功
			\Yii::$app->session->set('username', $this->username);
			\Yii::$app->session->set('authority', $authority);
			\Yii::$app->session->close();
			return $this->loginError;
		} else {
			return $this->loginError;
		}
	}
	private function getLoginMess($username, $password) {
		$this->username   = $username;
		$this->password   = $password;
		$this->loginError = '';
	}
	private function processtheLoginmessage() {
		$Dbfactory = DbFactory::getinstance();
		//一者为空，就不通过
		if ($this->username == '' || $this->password == '') {
			$this->loginError = '用户名或密码为空';
		} else {
			$this->username = $Dbfactory->dbSqlProtected($this->username);
			$this->password = $Dbfactory->dbSqlProtected($this->password);
		}
	}
	private function processthemessage($message) {
		$Dbfactory = DbFactory::getinstance();
		$message   = $Dbfactory->dbSqlProtected($message);
		return $message;
	}
	private function getpassword() {
		$Dbfactory = DbFactory::getinstance();
		$password  = $Dbfactory->findOnlyOne('XH_PW', 'user_tb', 'XH_ID', $this->username);
		return $password;
	}
	private function handlepassword($password) {
		if ($password == '') {
			//用户不存在
			$this->loginError = '用户不存在，是否注册';
		} else {
			if (md5($this->password) !== $password) {
				$this->loginError = '密码有误';
			}
		}
	}

}
