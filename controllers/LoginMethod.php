<?php
namespace app\controllers;
// include 'DbFactory.php';
/**
 * 处理登录业务接口
 */

class loginMethod {

	private $username;
	private $password;
	public $error;
	private $Dbfactory;
	public function __construct($username, $password, $error = '') {
		$this->username  = $username;
		$this->password  = $password;
		$this->error     = $error;
		$this->Dbfactory = DbFactory::getinstance();
		$this->handleLogin($username, $password);
	}
	public function handleLogin($username, $password) {
		//过滤
		$this->processtheLoginmessage();
		//与数据库交换
		$password = $this->getpassword();
		//登录
		$this->handlepassword($password);
		//error
		if ($this->error == '') {
			//存入session，登录成功
			Yii::$app->session->set('username', $this->username);
			Yii::$app->session->close();
		}
	}
	private function processtheLoginmessage() {
		// $Dbfactory = DbFactory::getinstance();
		//一者为空，就不通过
		if ($this->username == '' || $this->password == '') {
			$this->error = '用户名或密码为空';
		} else {
			$this->username = $this->Dbfactory->dbSqlProtected($this->username);
			$this->password = $this->Dbfactory->dbSqlProtected($this->password);
		}
	}
	private function getpassword() {
		// $Dbfactory = DbFactory::getinstance();
		$password = $this->Dbfactory->findOnlyOne('XH_PW', 'user_tb', 'XH_ID', $this->username);
		return $password;
	}
	private function handlepassword($password) {
		if ($password == '') {
			//用户不存在
			$this->error = '用户不存在，是否注册';
		} else {
			if (md5($this->password) !== $password) {
				$this->error = '密码有误';
			}
		}
	}
}
// $error = new loginMethod('031513217', '123456');
// echo $error;