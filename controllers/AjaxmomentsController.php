<?php
namespace app\controllers;
use app\models\Moments;
use yii\web\Controller;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: application/json;charset=utf-8");

/**
 * 管理员界面专用!!
 * ajax接口获取所有信息，并对其进行业务处理
 */

class AjaxmomentsController extends Controller {

	public $enableCsrfValidation = false;
	//删除一条记录
	public function actionDeleteone() {
		$moment = new Moments();
		$id     = $_POST['moment'];
		$moment->deleteOneMoment($id);
		echo '{"success":true}';
	}

	public function actionPagechange() {
		$page   = $_POST['page'];
		$moment = new Moments();
		$result = $moment->getPageMomentWithOrder($page, 5);
		if ($result == false) {
			return '{"success":false}';
		} else {
			$result = '{"success":true,"moments":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';
			echo $result;
		}
	}

}