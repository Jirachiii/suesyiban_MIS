<?php
namespace app\controllers;
use app\models\articles;
use app\models\Files;
use app\models\Itemdetails;
use app\models\Items;
use app\models\OwnTodos;

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
		$usertb         = new UserTb();
		$usertb->XH_ID  =trim($_POST["classmark"]);
		$usertb->XH_PW  = md5('123456');
		$usertb->Name   = trim($_POST["name"]);
		$usertb->phone  = trim($_POST["phone"]);
		$usertb->status = 2;
		if($usertb->save()){
			echo '{"success":true,"msg":"用户：'.$_POST["name"].' 信息保存成功！"}';
		}else{
			echo '{"success":false,"msg":"字符超出限制"}';

		}
		//TODO: 获取POST表单数据并保存到数据库
		//		$user['XH_ID']  = $_POST["classmark"];
		//		$user['XH_PW']  = md5('123456');
		//		$user['Name']   = $_POST["name"];
		//		$user['phone']  = $_POST["phone"];
		//		$user['status'] = 2;
		//		$usertb         = new UserTb();
		//		$usertb->insertMomentData($user);
		//提示保存成功
	}
	//管理员搜索用户
	public function actionAdminsearchuser() {
		if (!isset($_GET["searchuser"]) || empty($_GET["searchuser"])) {
			echo '{"success":false,"msg":"你输入了空值"}';
			return;
		}
		$XH_ID  = $_GET["searchuser"];
		$result = (new Query())
			->from('user_tb')
			->where(['or', ['like', 'XH_ID', $XH_ID], ['like', 'Name', $XH_ID]])
			->all();
		//TODO: 获取GET表单数据并搜索数据库

		$result = '{"success":true,"users":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';
		echo $result;
	}
	//获取所有状态
	public function actionAdminstatusgetitems() {
		$status = $_GET['status'];
		$items  = new items();
		$result = $items->AdminSearchAllItems($status, 1, 5);
		if ($result) {
			$msg = '<thead><tr><td>编号</td><td>学号</td><td>姓名</td><td>项目名</td><td>时间</td><td>通过|不通过|详细</td></tr></thead><tbody>';
			foreach ($result as $key => $value) {
				$msg .= '<tr><td>'.($key+1).'</td><td>'.$value['XH_ID'].'</td><td>'.$value['username'].'</td><td>'.$value['Item_Name'].'</td><td>'.$value['Date'].'</td><td><div class=\"Set_dele glyphicon glyphicon-ok\" onclick=\"ItemPass('.$value['Item_Id'].')\"></div>｜<div class=\"Set_dele glyphicon glyphicon-remove\" onclick=\"ItemFail('.$value['Item_Id'].')\"></div>｜<div class=\"Set_dele glyphicon glyphicon-eye-open\" onclick=\"ItemDescribe('.$value['Item_Id'].')\"></div></td></tr>';
			}
			echo '{"success":true,"msg":"'.$msg.'"}';
		} else {
			echo '{"success":true,"msg":"获取不到"}';
		}
	}
	//
	public function actionChangeitemstatus() {
		$id                  = $_POST['Item_Id'];
		$arrUpdate['Status'] = $_POST['status'];
		$items               = new items();
		$result              = $items->updateStatus($id, $arrUpdate);
		if ($result) {
			echo '{"success":true}';
		} else {
			echo '{"success":false}';
		}
	}

	//用户获取项目
	public function actionGetitembystatus() {
		$status = $_GET['status'];
		switch ($status) {
			case 2:
				//以后改进
				$items  = new items();
				$result = $items->searchAllItems($status);
				if ($result) {
					foreach ($result as $value) {
						$msg .= '<div onclick=\"detailShow('.$value['Item_Id'].')\" id=\"'.$value['Item_Id'].'\" class=\"item_show\" style=\"background-image: url(images/itemImg.jpeg);\"><h3 class=\"item_showtit\">'.$value['Item_Name'].'</h3></div>';
					}
					echo '{"success":true,"msg": "'.$msg.'"}';
				} else {
					echo '{"success":true, "msg":"没有项目"}';
				}
				break;
			default:
				$items  = new items();
				$result = $items->searchAllItems($status);
				if ($result) {
					foreach ($result as $value) {
						$msg .= '<div onclick=\"detailShow('.$value['Item_Id'].')\" id=\"'.$value['Item_Id'].'\" class=\"item_show\" style=\"background-image: url(images/itemImg.jpeg);\"><h3 class=\"item_showtit\">'.$value['Item_Name'].'</h3></div>';
					}
					echo '{"success":true,"msg": "'.$msg.'"}';
				} else {
					echo '{"success":true, "msg":"没有项目"}';
				}
				break;
		}
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
			$result = '{"success":true,"msg":"<div data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$content['Num'].'\" class=\"mission_type\" draggable=\"true\" ondragstart=\"drag(event)\"><span class=\"mission_SpDes\">'.$content['content'].'</span><span class=\"mission_SpDate\">'.$content['CreateDate'].'</span>';
			$result .= '<div onclick=\"Urgenthandle(this,1)\" class=\"mission_SpUrgent normal\" data-Num=\"'.$content['Num'].'\"></div><div onclick=\"Urgenthandle(this,2)\" class=\"mission_SpUrgent urgenter\" data-Num=\"'.$content['Num'].'\"></div><div onclick=\"Urgenthandle(this,3)\" class=\"mission_SpUrgent urgentest\" data-Num=\"'.$content['Num'].'\"></div></div>"}';
			echo $result;
		} else {
			echo '{"success":false}';
		}
	}
	//改变todo的状态，分为非常紧急、紧急、正常和完成这四种
	public function actionChangetodostatus() {
		$Num        = $_POST['Num'];
		$CreateDate = $_POST['CreateDate'];
		$urgentLev  = $_POST['urgentLev'];
		$owntodo    = new OwnTodos();
		$result     = $owntodo->changeStatus($Num, $CreateDate, $urgentLev);
		if ($result == true) {
			echo '{"success":true}';
		} else {
			echo '{"success":false}';
		}
	}

	//测试改变状态
	public function actionChangestatus() {
		$itemdetails = new Itemdetails();
		$id          = $_POST['id'];
		$status      = $_POST['status'];
		$result      = $itemdetails->changeStatus($id, $status);
		if ($result) {
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
				$result .= '<div data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\" class=\"mission_type\" draggable=\"true\" ondragstart=\"drag(event)\"><span class=\"mission_SpDes\">'.$value['content'].'</span><span class=\"mission_SpDate\">'.$value['CreateDate'].'</span></div>';
			}
			$result .= '"}';
			echo $result;
		} else {
			echo '{"success":true,"msg":"没有任务"}';
		}
	}
	public function handleLength($data, $maxNum) {
		if (strlen($data) > $maxNum) {
			$data = substr($data, 0, $maxNum);
			$data .= '...';
		}
		return $data;
	}
	//插入项目
	public function actionInsertitem() {
		$arr['XH_ID']      = \Yii::$app->user->identity->XH_ID;
		$arr['Item_Name']  = $_POST["ItemName"];
		$arr['Item_Intro'] = $_POST["ItemIntro"];
		$arr['Status']     = 1;
		$arr['Date']       = date('y-m-d');
		$item              = new Items();
		if ($item->insertItem($arr)) {
			echo '{"success": true}';
		} else {
			echo '{"success": false}';
		}
	}
	//todo近一周
	public function actionTodopastoneweek() {
		$oneWeek  = date('Y-m-d', strtotime("-1 week"));
		$today    = date('Y-m-d', strtotime("-1 day"));
		$ownTodos = new OwnTodos();
		$result   = $ownTodos->dateSearch($oneWeek, $today);
		if ($result) {
			$msg = '{"success":true,"msg":"';
			foreach ($result as $value) {
				$msg .= '<div data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\" class=\"mission_type\" draggable=\"true\" ondragstart=\"drag(event)\"><span class=\"mission_SpDes\">'.$value['content'].'</span><span class=\"mission_SpDate\">'.$value['CreateDate'].'</span></div>';
			}
			$msg .= '"}';
			echo $msg;
		} else {
			echo '{"success": false, "msg":"没有任务"}';
		}
	}
	//所有未完成
	public function actionTodowillhandle() {
		$ownTodos = new OwnTodos();
		$result   = $ownTodos->willHandle();
		$result   = $this->TodoGetItWithOrder($result);
		if ($result) {
			$result = '{"success":true,"msg":"'.$result.'"}';
		} else {
			$result = '{"success":false,"msg":"没有任务"}';
		}
		echo $result;
	}
	//TODO排序
	private function TodoGetItWithOrder($query) {
		$urgentLev1 = '';
		$urgentLev2 = '';
		$urgentLev3 = '';
		foreach ($query as $key => $value) {
			switch ($value['urgentLev']) {
				case '1':
					$urgentLev1 .= '<div id=\"mission'.$value['Num'].$value['CreateDate'].'\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\" class=\"mission_type\" draggable=\"true\" ondragstart=\"drag(event)\"><span class=\"mission_SpDes\">'.$value['content'].'</span><span class=\"mission_SpDate\">'.$value['CreateDate'].'</span></div>';
					break;
				case '2':
					$urgentLev2 .= '<div id=\"mission'.$value['Num'].$value['CreateDate'].'\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\" class=\"mission_type UrgenterBorder\" draggable=\"true\" ondragstart=\"drag(event)\"><span class=\"mission_SpDes\">'.$value['content'].'</span><span class=\"mission_SpDate\">'.$value['CreateDate'].'</span></div>';
					break;
				case '3':
					$urgentLev3 .= '<div id=\"mission'.$value['Num'].$value['CreateDate'].'\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\" class=\"mission_type UrgentestBorder\" draggable=\"true\" ondragstart=\"drag(event)\"><span class=\"mission_SpDes\">'.$value['content'].'</span><span class=\"mission_SpDate\">'.$value['CreateDate'].'</span></div>';
					break;
				case '4':
					break;
			}
		}
		return $urgentLev3.$urgentLev2.$urgentLev1;
	}
	//项目细节展示
	public function actionDetailshow() {
		$id         = $_GET['id'];
		$itemdetail = new Itemdetails();
		$result     = $itemdetail->detailAll($id);
		if ($result) {
			$result = $this->itemDetailGetItWithOrder($result);
			echo $result;
		} else {
			echo '{"success":true, "msg1":"没有任务"}';
		}
	}
	//显示项目任务的一个细节（附件之类）
	public function actionOnedetailshow() {
		$id         = $_GET['id'];
		$itemdetail = new Itemdetails();
		$result     = $itemdetail->detailDet($id);
		if ($result) {
			$msg = '<span onclick=\"closeModel()\" class=\"glyphicon glyphicon-remove delete_span\"></span><div class=\"\" id=\"detailmodel_Main\"><textarea name=\"\" id=\"detail_text\" cols=\"30\" rows=\"3\" class=\"detail_Maintext\">'.$result[0]['discribe'].'</textarea><button class=\"detailmodel_btn\" id=\"\" onclick=\"addInTodo()\">添加入今日任务</button><button onclick=\"changeDetail('.$result[0]['ItemDetail_Id'].')\" class=\"detailmodel_btn\" id=\"\">修改</button></div>';
			echo '{"success":true, "msg":"'.$msg.'"}';
		} else {
			echo '{"success":false,"msg":"系统错误"}';
		}
	}
	//项目细节排序
	private function itemDetailGetItWithOrder($query) {
		$Lev1 = '';
		$Lev2 = '';
		foreach ($query as $value) {
			switch ($value['status']) {
				case '1':
					$Lev1 .= '<div class=\"Thedetail\" onclick=\"detailDet('.$value['ItemDetail_Id'].')\" draggable=\"true\" id=\"Detail'.$value['ItemDetail_Id'].'\" data-id=\"'.$value['ItemDetail_Id'].'\" ondragstart=\"drag(event)\"><p class=\"Thedetail_p\">'.$value['discribe'].'</p></div>';
					break;
				case '2':
					$Lev2 .= '<div class=\"Thedetail\" onclick=\"detailDet('.$value['ItemDetail_Id'].')\" draggable=\"true\" id=\"Detail'.$value['ItemDetail_Id'].'\" data-id=\"'.$value['ItemDetail_Id'].'\" ondragstart=\"drag(event)\"><p class=\"Thedetail_p\">'.$value['discribe'].'</p></div>';
					break;
			}
		}
		return '{"success":true, "msg1":"'.$Lev1.'", "msg2":"'.$Lev2.'","msg3":"'.$query[0]['item_id'].'"}';
	}
	//显示细节并且渲染
	public function actionDetaildetshow() {
		$id           = $_GET['id'];
		$itemdetail   = new Itemdetails();
		$detail       = $itemdetail->detailDet($id);
		$file         = new Files();
		$filelocation = $file->fileLocation($id);
		if ($filelocation) {
			echo '{"success":true,"msg":"请输查询入内容"}';
		} else {
			echo '{"success":true,"msg":"请输查询入内容"}';
		}
	}
	//插入
	public function actionInsertdetail() {
		$arr['item_id']  = $_POST['item_id'];
		$arr['discribe'] = $_POST['discribe'];
		$arr['status']   = 1;
		$arr['Date']     = date('y-m-d');
		$arr['Time']     = date('H:i:s');
		$itemdetail      = new Itemdetails();
		$result          = $itemdetail->insertDetail($arr);
		if ($result) {
			echo '{"success":true}';
		} else {
			echo '{"success":false}';
		}
	}
	//修改项目任务细节内容
	public function actionChangediscribe() {
		$ItemDetail_Id = $_POST['ItemDetail_Id'];
		$discribe      = $_POST['discribe'];
		$itemdetail    = new Itemdetails();
		$result        = $itemdetail->ChangeDetail($ItemDetail_Id, $discribe);
		if ($result) {
			echo '{"success":true}';
		} else {
			echo '{"success":false}';
		}
	}
	//管理员审核项目
	public function actionAdminshowitem() {
		$item_id = $_GET['Item_Id'];
		$item_id = 1;
		$item    = new Items();
		$result  = $item->searchItemsDetail($item_id);
		if ($result) {
			$msg = '<span onclick=\"closeModel()\" class=\"glyphicon glyphicon-remove delete_span\"></span><div class=\"\" id=\"detailmodel_Main\"><textarea name=\"\" cols=\"30\" rows=\"3\" class=\"detail_Maintext\">'.$result[0]['Item_Name'].'</textarea><textarea cols=\"30\" rows=\"3\" class=\"detail_Maintext\">'.$result[0]['Item_Intro'].'</textarea></div>';
			echo '{"success":true, "msg":"'.$msg.'"}';
		} else {
			echo '{"success":false}';
		}
	}




	//库存管理
	/**
	 * 库存分页
	 */
	public function  actionArticlepagchange(){
		$request=\Yii::$app->request;
		$page=$request->get("page");
		$article=new Articles();
		$result=$article->showarticledata($page,6);
		return $result;
	}
	/**
	 * 搜索库存
	 */

	public function actionAdminsearcharticle() {
		if (!isset($_GET["searcharticle"]) || empty($_GET["searcharticle"])) {
			echo '{"success":false,"msg":"请输查询入内容"}';
			return;
		}
		$Art_Name = $_GET["searcharticle"];
		$page=$_GET['page'];
		$artice=new Articles();
		$result=$artice->searcharticle($Art_Name,1,6);
		echo $result;
	}

	/**
	 * 搜索的分页
	 */
	public function actionAdminsearcharticlefenye(){
		$Art_Name = $_GET["article"];
		$page=$_GET['page'];
		$artice=new Articles();
		$result=$artice->searcharticle($Art_Name,$page,6);
		echo $result;

	}
	/**
	 * 状态筛选
	 */
	public function actionAdminselectarticle() {
		if (!isset($_GET["searcharticle"]) || empty($_GET["searcharticle"])) {
			echo '{"success":false,"msg":"请选择查询状态"}';
			return;
		}
		$status=$_GET["searcharticle"];
		$page=$_GET['page'];
		$article=new Articles();
		$result=$article->searcharticlebystatus($status,1,6);
		echo $result;
	}
	/**
	 * 状态筛选分页
	 */
	public function actionArticlepagchangesel(){
		$request=\Yii::$app->request;
		$page=$request->get("page");
		$status=$request->get("status");
		$article=new Articles();
		$result=$article->searcharticlebystatus($status,$page,6);
		echo $result;
	}

	/**
	 * 插入库存
	 */
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
		$article->save();
		echo '{"success":true,"msg":"用户：'.$_POST["itemname"].' 信息保存成功！"}';
	}

	/**
	 * 删除库存
	 */
	public function actionDeletearticle() {
		$art_id  = $_POST['art_id'];
		$article = Articles::findOne($art_id);
		$article->delete();
		echo '{"success":true}';
	}

	/**
	 * 添加库存数量
	 */
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

	/**
	 * 减少库存数量
	 */
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

	/**
	 * 更改库存上架状态
	 */
	public function actionAdminupdatearticle3() {
		//判断信息是否填写完全
		if ((empty($_POST["changeart_sel"]) || !isset($_POST["changeart_sel"]))) {
			echo '{"success":false,"msg":"请选择"}';
			return;
		}
		$aimarticle = Articles::findOne($_POST['id']);
		if ($_POST['changeart_sel'] == "1") {
			($aimarticle->Art_Num > 0)?$aimarticle->status = 1:$aimarticle->status = 2;
		} else if ($_POST['changeart_sel'] == "2") {
			$aimarticle->status = 3;
		}

		$aimarticle->save(false);
		//		$a=$aimarticle->status;
		echo '{"success":true,"msg":"更改成功！"}';
	}
}