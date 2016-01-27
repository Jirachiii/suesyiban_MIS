<?php
namespace app\controllers;
use app\models\Moments;
use app\models\UserTb;
use yii\web\Controller;
use yii\db\Query;

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
	public function actionDeleteonemoment() {
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
	//页码
//	public function actionGetyema(){
//
//	}

//搜索动态
	public function actionAdminsearchmoment() {
		if (!isset($_GET["searchmoment"]) || empty($_GET["searchmoment"])) {
			echo '{"success":false,"msg":"请输查询入内容"}';
			return;
		}
		$input = $_GET["searchmoment"];
		$result   = (new Query())
//			->select(['Mdate','XH_ID', 'Content','like_Num'])
			->from('moments')
			->where(['or',['like','XH_ID',$input],['like','Content',$input]])
			->orderBy('Mdate DESC','Time DESC')
			->all();
		foreach($result as $key=>$value){
//			$value['username']=UserTb::findOne($value['XH_ID'])->Name;
			$result[$key]['username']=UserTb::findOne($value['XH_ID'])->Name;
		}
		$result = '{"success":true,"moments":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';
		echo $result;
	}
	//动态修改
	public function actionAdminupdatemoment() {
		//判断信息是否填写完全
		if (empty($_POST["moment_content"]) && empty($_POST["moment_content"])) {
			echo '{"success":false,"msg":"内容填写错误"}';
			return;
		}
		$aimmoment         = Moments::findOne($_POST['momentid']);
		$aimmoment->Content = $_POST['moment_content'];
		$aimmoment->save(false);
		echo '{"success":true,"msg":"添加成功！"}';
	}
}

