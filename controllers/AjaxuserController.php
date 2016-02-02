<?php
namespace app\controllers;
use app\models\articles;
use app\models\OwnTodos;
use app\models\TestTb;
use app\models\UserTb;
use yii\db\Query;

use yii\web\Controller;
date_default_timezone_set("PRC");
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
		$usertb=new UserTb();
		$usertb->XH_ID= $_POST["classmark"];
		$usertb->XH_PW=md5('123456');
		$usertb->Name=$_POST["name"];
		$usertb->phone=$_POST["phone"];
		$usertb->status=2;
		$usertb->save();
		//TODO: 获取POST表单数据并保存到数据库
//		$user['XH_ID']  = $_POST["classmark"];
//		$user['XH_PW']  = md5('123456');
//		$user['Name']   = $_POST["name"];
//		$user['phone']  = $_POST["phone"];
//		$user['status'] = 2;
//		$usertb         = new UserTb();
//		$usertb->insertMomentData($user);
		//提示保存成功
		echo '{"success":true,"msg":"用户：'.$_POST["name"].' 信息保存成功！"}';
	}
	//管理员搜索用户
	public function actionAdminsearchuser() {
		if (!isset($_GET["searchuser"]) || empty($_GET["searchuser"])) {
			echo '{"success":false,"msg":"你输入了空值"}';
			return;
		}
		$XH_ID     = $_GET["searchuser"];
		$result   = (new Query())
			->from('user_tb')
			->where(['or',['like', 'XH_ID', $XH_ID],['like', 'Name', $XH_ID]])
			->all();
		//TODO: 获取GET表单数据并搜索数据库

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

	public function actionInserttodo() {
		$owntodos              = new OwnTodos();
		$content['XH_ID']      = \Yii::$app->user->identity->XH_ID;
		$content['CreateDate'] = date('Y-m-d');
		$content['Num']        = intval($owntodos->findTodayMissionCount())+1;
		$content['content']    = $_POST['content'];
		$content['urgentLev']  = 1;
		if ($owntodos->insertTodoData($content)) {
			$result = '{"success":true,"msg":"<div data-Num=\"'.$content['Num'].'\" class=\"mission_type\" draggable=\"true\" ondragstart=\"drag(event)\"><span class=\"mission_SpDes\">'.$content['content'].'</span><span class=\"mission_SpDate\">'.$content['CreateDate'].'</span>';
			$result .= '<div onclick=\"Urgenthandle(this,1)\" class=\"mission_SpUrgent normal\" data-Num=\"'.$content['Num'].'\"></div><div onclick=\"Urgenthandle(this,2)\" class=\"mission_SpUrgent urgenter\" data-Num=\"'.$content['Num'].'\"></div><div onclick=\"Urgenthandle(this,3)\" class=\"mission_SpUrgent urgentest\" data-Num=\"'.$content['Num'].'\"></div></div>"}';
			echo $result;
		} else {
			echo '{"success":false}';
		}
	}
	//改变todo的状态，分为非常紧急、紧急、正常和完成这四种
	public function actionChangetodostatus() {
		$Num       = $_POST['Num'];
		$urgentLev = $_POST['urgentLev'];
		$owntodo   = new OwnTodos();
		$result    = $owntodo->changeStatus($Num, $urgentLev);
		if ($result == true) {
			echo '{"success":true}';
		} else {
			echo '{"success":false}';
		}
	}

	//测试改变状态
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
	//获取今日已完成数据
	public function actionGetdonemask() {
		$owntodo = new OwnTodos();
		$content = $owntodo->findTodayDoneMission();
		if ($content) {
			$result = '{"success":true,"msg":"';
			foreach ($content as $key => $value) {
				$result .= '<div data-Num=\"'.$value['Num'].'\" class=\"mission_type\" draggable=\"true\" ondragstart=\"drag(event)\"><span class=\"mission_SpDes\">'.$value['content'].'</span><span class=\"mission_SpDate\">'.$value['CreateDate'].'</span></div>';
			}
			$result .= '"}';
			echo $result;
		} else {
			echo '{"success":false}';
		}
	}
	public function handleLength($data, $maxNum) {
		if (strlen($data) > $maxNum) {
			$data = substr($data, 0, $maxNum);
			$data .= '...';
		}
		return $data;
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
		$Art_Name = $_GET["searcharticle"];
		$result   = (new Query())
		//			->select(['Art_Name', 'status'])
			->from('articles')
			->where(['like', 'Art_Name', $Art_Name])
			->all();
		$result = '{"success":true,"articles":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';
		echo $result;
	}
	//状态筛选
	public function actionAdminselectarticle() {
		if (!isset($_GET["searcharticle"]) || empty($_GET["searcharticle"])) {
			echo '{"success":false,"msg":"请选择查询状态"}';
			return;
		}
		if ($_GET["searcharticle"] == 4) {
			$result = Articles::find()->asArray()->orderBy('status ASC,Art_Num DESC')->all();
			$result = '{"success":true,"articles":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';
		} else {
			$status = $_GET["searcharticle"];
			$result = Articles::find()->where(['status' => $status])->asArray()->orderBy('status ASC,Art_Num DESC')->all();
			$result = '{"success":true,"articles":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';}
		echo $result;
	}
	//插入库存
	public function actionAdmininsertarticle() {
		//判断信息是否填写完全
		if (!isset($_POST["itemname"]) || empty($_POST["itemname"])
			 || !isset($_POST["number"]) || empty($_POST["number"])) {
			echo '{"success":false,"msg":"信息填写不全"}';
			return;
		}
		if (!is_numeric($_POST["number"])) {
			echo '{"success":false,"msg":"数量填写错误"}';
			return;
		}
		$article = new articles();
		//		$article->Art_Id = $_POST["itemname"];
		$article->Art_Name = $_POST["itemname"];
		$article->Art_Num  = $_POST["number"];
		$article->Art_Time = date("y-m-d", time());
		$article->status   = 1;

		//		$article->email = 'james@example.com';
		$article->save();
		// 等同于 $customer->insert();
		//TODO: 获取POST表单数据并保存到数据库

		//提示保存成功
		echo '{"success":true,"msg":"用户：'.$_POST["itemname"].' 信息保存成功！"}';
	}
	//删除库存
	public function actionDeletearticle() {
		$art_id  = $_POST['art_id'];
		$article = Articles::findOne($art_id);
		$article->delete();
		echo '{"success":true}';
	}

	//添加库存
	public function actionAdminupdatearticle() {
		//判断信息是否填写完全
		if ((empty($_POST["changeart_sel"]) && empty($_POST["changeart_inp"])) ||
			(!empty($_POST["changeart_sel"]) && !empty($_POST["changeart_inp"])) ||
			(!isset($_POST["changeart_sel"]) && !isset($_POST["changeart_inp"]))) {
			echo '{"success":false,"msg":"信息填写错误"}';
			return;
		}
		if (!is_numeric($_POST["changeart_sel"]) && !is_numeric($_POST["changeart_inp"])) {
			echo '{"success":false,"msg":"数量填写错误"}';
			return;
		}
		$aimarticle          = Articles::findOne($_POST['articleid']);
		$aimarticle->Art_Num = $_POST['total'];
		if ($aimarticle->Art_Num > 0 && $aimarticle->status != 3) {
			$aimarticle->status = 1;
		}
		$aimarticle->save(false);
		echo '{"success":true,"msg":"添加成功！"}';
	}
	//减少库存
	public function actionAdminupdatearticle2() {
		//判断信息是否填写完全
		if ((empty($_POST["changeart_sel"]) && empty($_POST["changeart_inp"])) ||
			(!empty($_POST["changeart_sel"]) && !empty($_POST["changeart_inp"])) ||
			(!isset($_POST["changeart_sel"]) && !isset($_POST["changeart_inp"]))) {
			echo '{"success":false,"msg":"信息填写错误"}';
			return;
		}
		if (!is_numeric($_POST["changeart_sel"]) && !is_numeric($_POST["changeart_inp"])) {
			echo '{"success":false,"msg":"数量填写错误"}';
			return;
		}
		if ($_POST['total'] < 0) {
			echo '{"success":false,"msg":"没有这么多库存啦"}';
			return;
		}
		$aimarticle          = Articles::findOne($_POST['articleid']);
		$aimarticle->Art_Num = $_POST['total'];
		if ($aimarticle->Art_Num == 0 && $aimarticle->status != 3) {
			$aimarticle->status = 2;
		}
		$aimarticle->save(false);
		echo '{"success":true,"msg":"扣除成功！"}';
	}
	//更改库存上架状态
	public function actionAdminupdatearticle3() {
		//判断信息是否填写完全
		if ((empty($_POST["changeart_sel"]) || !isset($_POST["changeart_sel"]))) {
			echo '{"success":false,"msg":"请选择"}';
			return;
		}
		$aimarticle = Articles::findOne($_POST['id']);
		if ($_POST['changeart_sel'] == "1") {
			//			if($aimarticle->Art_Num>0){
			//				$aimarticle->status=1;
			//			}else{
			//				$aimarticle->status=2;
			//			}
			($aimarticle->Art_Num > 0)?$aimarticle->status = 1:$aimarticle->status = 2;
		} else if ($_POST['changeart_sel'] == "2") {
			$aimarticle->status = 3;
		}

		$aimarticle->save(false);
		//		$a=$aimarticle->status;
		echo '{"success":true,"msg":"更改成功！"}';
	}
}