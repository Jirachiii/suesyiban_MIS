<?php
namespace app\controllers;
use yii\web\Controller;

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: application/json;charset=utf-8");

/**
 * 管理员界面专用!!
 * ajax接口获取所有信息，并对其进行业务处理
 */

class AjaxuserController extends Controller {

	//user logal
	public function actionAllUser() {

	}
	public function actionUser1() {

	}
	public function actionUser2() {

	}
	public function actionUser3() {

	}
	public function actionUser4() {

	}

}