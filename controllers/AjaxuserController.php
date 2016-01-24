<?php
namespace app\controllers;
use app\models\TestTb;
use app\models\UserTb;
use app\models\articles;
use yii\web\Controller;
use yii\db\Query;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: application/json;charset=utf-8");

/**
 * 用户界面专用!!
 * ajax接口获取信息，并对其进行业务处理
 */

class AjaxuserController extends Controller {

	public $enableCsrfValidation = false;
	//管理员插入用户
	public function actionAdmininsertuser() {
		//判断信息是否填写完全
		if (!isset($_POST["classmark"]) || empty($_POST["classmark"])
			 || !isset($_POST["name"]) || empty($_POST["name"])
			 || !isset($_POST["phone"]) || empty($_POST["phone"])) {
			echo '{"success":false,"msg":"参数错误，信息填写不全"}';
			return;
		}
		//TODO: 获取POST表单数据并保存到数据库
		$user['XH_ID']  = $_POST["classmark"];
		$user['XH_PW']  = md5('123456');
		$user['Name']   = $_POST["name"];
		$user['phone']  = $_POST["phone"];
		$user['status'] = 2;
		$usertb         = new UserTb();
		$usertb->insertMomentData($user);
		//提示保存成功
		echo '{"success":true,"msg":"用户：'.$_POST["name"].' 信息保存成功！"}';
	}
	//管理员搜索用户
	public function actionAdminsearchuser() {
		if (!isset($_GET["searchuser"]) || empty($_GET["searchuser"])) {
			echo '{"success":false,"msg":"你输入了空值"}';
			return;
		}
		//TODO: 获取GET表单数据并搜索数据库
		$XH_ID     = $_GET["searchuser"];
		$sql       = 'SELECT XH_ID,Name,phone,status FROM user_tb WHERE XH_ID LIKE \'%'.$XH_ID.'%\'';
		$Dbfactory = DbFactory::getinstance();
		$result    = $Dbfactory->doQuery($sql);
		$result    = $Dbfactory->findAll($result);
		$result    = '{"success":true,"users":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';
		echo $result;
	}
	//重置密码
	public function actionResetpass() {
		$user   = new UserTb();
		$XH_ID  = $_POST['XH_ID'];
		$result = $user->resetPass($XH_ID);
		if ($result == true) {
			echo '{"success":true}';
		} else {
			echo '{"success":false}';
		}

	}
	//删除一条记录(删除不了？)
	public function actionDeleteone() {
		$user  = new UserTb();
		$XH_ID = $_POST['XH_ID'];
		// $XH_ID = '031513217';
		$user->deleteOneUser($XH_ID);
		echo '{"success":true}';
	}

	public function actionChangestatus() {
		$testTb = new TestTb();
		$id     = $_POST['id'];
		$status = $_POST['status'];
		$result = $testTb->changeStatus($id, $status);
		if ($result == true) {
			echo '{"success":true}';
		} else {
			echo '{"success":false}';
		}
	}
	/**
	 * 	          库存管理
	 */

//搜索库存

	public function actionAdminsearcharticle() {
		if (!isset($_GET["searcharticle"]) || empty($_GET["searcharticle"])) {
			echo '{"success":false,"msg":"请输查询入内容"}';
			return;
		}
		$Art_Name  = $_GET["searcharticle"];
		$result = (new Query())
//			->select(['Art_Name', 'status'])
			->from('articles')
			->where(['like', 'Art_Name', $Art_Name])
			->all();
		$result    = '{"success":true,"articles":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';
		echo $result;
	}
	public function actionAdmininsertarticle() {
		//判断信息是否填写完全
		if (!isset($_POST["itemname"]) || empty($_POST["itemname"])
			|| !isset($_POST["number"]) || empty($_POST["number"])) {
			echo '{"success":false,"msg":"信息填写不全"}';
			return;
		}
		$article = new articles();
//		$article->Art_Id = $_POST["itemname"];
		$article->Art_Name = $_POST["itemname"];
		$article->Art_Num = $_POST["number"];
		$article->Art_Time = date("y-m-d",time());
		$article->status = 1;

//		$article->email = 'james@example.com';
		$article->save();  // 等同于 $customer->insert();
		//TODO: 获取POST表单数据并保存到数据库

		//提示保存成功
		echo '{"success":true,"msg":"用户：'.$_POST["itemname"].' 信息保存成功！"}';
	}
	//删除库存
	public function actionDeletearticle() {
		$art_id = $_POST['art_id'];
		$article=Articles::findOne($art_id);
		$article->delete();
		echo '{"success":true}';
	}

}