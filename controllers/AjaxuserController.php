<?php
namespace app\controllers;

use app\models\UserTb;
use yii\web\Controller;

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

	public function actionAllUser() {

	}
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

}