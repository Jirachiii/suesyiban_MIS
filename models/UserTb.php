<?php
namespace app\models;
use app\controllers\DbFactory;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
header("Content-Type: application/json;charset=utf-8");

/**
 * This is the model class for table "user_tb".
 *
 * @property string $XH_ID			用户主键（学号）
 * @property string $XH_PW			用户密码
 * @property string $Name 			姓名
 * @property string $phone 			联系方式
 * @property integer $status 		权限
 * @property integer $department 	部门
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
	//插入数据，并在插入之前检验数据是否会对数据库有害
	public function insertMomentData($arr) {
		$Dbfactory = DbFactory::getinstance();
		foreach ($arr as $value) {
			$value = $Dbfactory->dbSqlProtected($value);
		}
		$Dbfactory->insertIntoDb('user_tb', $arr);
	}
	//删除用户
	public function deleteOneUser($id) {
		$Dbfactory = DbFactory::getinstance();
		$Dbfactory->deleteOneRecord('user_tb', 'XH_ID', $id);
	}
	//重置密码为123456
	public function resetPass($id) {
		$Dbfactory          = DbFactory::getinstance();
		$arrUpdate['XH_PW'] = md5(123456);
		return $Dbfactory->updateTheDbRecord('user_tb', 'XH_ID', $id, $arrUpdate);
	}
	//用户重置密码
	public function updatePassword($id, $password) {
		$Dbfactory          = DbFactory::getinstance();
		$arrUpdate['XH_PW'] = md5($password);
		return $Dbfactory->updateTheDbRecord('user_tb', 'XH_ID', $id, $arrUpdate);
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

	/**
	 * 获取用户
	 * @param $page
	 * @param $number
	 * @return array
	 */
	public function getPageUserWithOrder($page, $number) {
		$front     = ($page-1)*$number;
		$sql       = 'SELECT * FROM user_tb ORDER BY status,Name LIMIT '.$front.','.$number;
		$Dbfactory = DbFactory::getinstance();
		$users     = $Dbfactory->findBySql($sql);
		return $users;
	}

	/**
	 * 统计总的页数
	 */
	public function userallpage($numbers) {
		$result = UserTb::find()->count();
		return (ceil($result/$numbers));
	}

	/**
	 * 搜索用户的分页
	 * @param $content
	 * @param $page
	 * @param $numbers
	 * @return array
	 */
	public function getSearchUserWithPage($content, $page, $numbers) {
		$index  = ($page-1)*$numbers;
		$result = (new Query())
			->from('user_tb')
			->where(['or', ['like', 'XH_ID', $content], ['like', 'Name', $content]])
			->limit($numbers)
			->offset($index)
			->all();
		return $result;
	}
	/**
	 * 统计总的页数(搜索)
	 */
	public function userallpage_s($numbers, $content) {
		$result = UserTb::find()->where("XH_ID like '%$content%' or Name like '%$content%'")->count();
		return (ceil($result/$numbers));
	}

}
