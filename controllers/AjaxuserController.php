<?php
namespace app\controllers;
use app\models\articles;
use app\models\Files;
use app\models\Itemdetails;
use app\models\Itempersons;
use app\models\Items;
use app\models\Moments;
use app\models\OwnTodos;
use app\models\UserTb;
use YII;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only'  => ['admininsertuser', 'userpagechange','adminsearchuser', 'changeuserstatus', 'adminstatusgetitems','updateuserpassword'
					, 'changeitemstatus', 'getitembystatus','searchitem', 'resetpass', 'deleteone', 'inserttodo', 'changetodostatus','deleteownertodo'
					, 'getdonemask', 'handleLength', 'insertitem', 'todopastoneweek', 'todowillhandle', 'todogetitwithorder'
					, 'detaildetshow','detailshow', 'onedetailshow','insertitemperson','deleteitem', 'insertdetail', 'changediscribe', 'adminshowitem', 'gettopmoment','articlepagchange'
					, 'adminsearcharticle', 'adminsearcharticlefenye', 'adminselectarticle', 'articlepagchangesel', 'admininsertarticle'
					, 'deletearticle', 'adminupdatearticle', 'adminupdatearticle2', 'adminupdatearticle3','getitemuser','changestatus'],
				'rules' => [
					[
						'allow'   => true,
						'actions' => ['login'],
						'roles'   => ['?'],
					],
					//1级管理员有权限
					[
						'actions' =>  ['admininsertuser', 'userpagechange','adminsearchuser', 'changeuserstatus', 'adminstatusgetitems','updateuserpassword'
							, 'changeitemstatus', 'getitembystatus','searchitem', 'resetpass', 'deleteone', 'inserttodo', 'changetodostatus','deleteownertodo'
							, 'getdonemask', 'handleLength', 'insertitem', 'todopastoneweek', 'todowillhandle', 'todogetitwithorder'
							, 'detaildetshow','detailshow', 'onedetailshow','insertitemperson','deleteitem', 'insertdetail', 'changediscribe', 'adminshowitem', 'gettopmoment','articlepagchange'
							, 'adminsearcharticle', 'adminsearcharticlefenye', 'adminselectarticle', 'articlepagchangesel', 'admininsertarticle'
							, 'deletearticle', 'adminupdatearticle', 'adminupdatearticle2', 'adminupdatearticle3','getitemuser','changestatus'],
						'allow'         => true,
						'roles'         => ['@'],
						'matchCallback' => function ($rule, $action) {
							return Yii::$app->user->identity->status == 1;
						}
					],
					//2
					[
						'actions'       => ['gettopmoment','inserttodo','changetodostatus','deleteownertodo','getdonemask','todopastoneweek','todowillhandle'
						,'detailshow','getitemuser','onedetailshow','changestatus','getitembystatus'],
						'allow'         => true,
						'roles'         => ['@'],
						'matchCallback' => function ($rule, $action) {
							return Yii::$app->user->identity->status == 2;
						}
					],

				],
			],
			'verbs'    => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}
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
		$usertb->XH_ID  = trim($_POST["classmark"]);
		$usertb->XH_PW  = md5('123456');
		$usertb->Name   = trim($_POST["name"]);
		$usertb->phone  = trim($_POST["phone"]);
		$usertb->status = 2;
		if ($usertb->save()) {
			echo '{"success":true,"msg":"用户：'.$_POST["name"].' 信息保存成功！"}';
		} else {
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

	/**
	 * 	用户管理分页
	 */
	public function actionUserpagechange() {
		$page    = $_GET['page'];
		$usertb  = new UserTb();
		$allpage = $usertb->userallpage(6);
		if ($allpage < $page || $page < 1) {
			$result = '{"success":false,"allPage":1,"msg":"页码出错"}';
		} else {
			$result = $usertb->getPageUserWithOrder($page, 6);
			$result = '{"success":true,"users":'.json_encode($result, JSON_UNESCAPED_UNICODE).',"allPage":"'.$allpage.'"}';
		}

		return $result;
	}
	//管理员搜索用户
	public function actionAdminsearchuser() {
		if (!isset($_GET["searchuser"]) || empty($_GET["searchuser"])) {
			echo '{"success":false,"msg":"请输入查询内容"}';
			return;
		}
		$page    = $_GET['page'];
		$content = $_GET["searchuser"];

		$usertb  = new UserTb();
		$allpage = $usertb->userallpage_s(6, $content);
		if ($allpage < $page || $page < 1) {
			$result = '{"success":false,"allPage":1,"msg":"页码出错"}';
		} else {
			$result = $usertb->getSearchUserWithPage($content, $page, 6);
			$result = '{"success":true,"allPage":"'.$allpage.'","users":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';
		}
		echo $result;
	}
	/**
	 * 更改用户权限
	 */
	public function actionChangeuserstatus() {
		//判断信息是否填写完全
		if ((empty($_POST["status"]) || !isset($_POST["status"]))) {
			echo '{"success":false,"msg":"请选择"}';
			return;
		}
		$aimuser         = UserTb::findOne($_POST['id']);
		$aimuser->status = $_POST["status"];
		$aimuser->save(false);
		//		$a=$aimarticle->status;
		echo '{"success":true,"msg":"更改成功！"}';
	}
	//
	public function actionUpdateuserpassword() {
		$id       = \Yii::$app->user->identity->XH_ID;
		$password = $_POST['password'];
		$usertb   = new UserTb();
		$result   = $usertb->updatePassword($id, $password);
		if ($result) {
			echo '{"success": true}';
		} else {
			echo '{"success": false}';
		}
	}
	//获取所有状态
	public function actionAdminstatusgetitems() {
		$status = $_GET['status'];
		$page=$_GET['page'];
		$items  = new items();
		$countpage=$items->getItempages_sel($status,5);
		$result = $items->AdminSearchAllItems($status, $page, 5);
		if ($result) {
			$msg = '<thead><tr><td>编号</td><td>学号</td><td>姓名</td><td>项目名</td><td>时间</td><td>通过|不通过|详细</td></tr></thead><tbody>';
			foreach ($result as $key => $value) {
				$msg .= '<tr><td>'.($key+1).'</td><td>'.$value['XH_ID'].'</td><td>'.$value['username'].'</td><td>'.$value['Item_Name'].'</td><td>'.$value['Date'].'</td><td><div class=\"Set_dele glyphicon glyphicon-ok\" onclick=\"ItemPass('.$value['Item_Id'].')\"></div>｜<div class=\"Set_dele glyphicon glyphicon-remove\" onclick=\"ItemFail('.$value['Item_Id'].')\"></div>｜<div class=\"Set_dele glyphicon glyphicon-eye-open\" onclick=\"ItemDescribe('.$value['Item_Id'].')\"></div></td></tr>';
			}
			echo '{"success":true,"msg":"'.$msg.'","allpage":"'.$countpage.'"}';
		} else {
			echo '{"success":true,"msg":"没有项目","allpage":"'.$countpage.'"}';
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
		$msg    = '';
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

	//搜索项目
	function actionSearchitem(){
		$request=yii::$app->request;
		$page=$request->get('page');
		$content=$request->get('content');
		$items  = new items();
		$countpage=$items->getItempages_s($content,5);
		$result = $items->AdminSearchItems($content, $countpage,$page, 5);
		if ($result) {
			$msg = '<thead><tr><td>编号</td><td>状态</td><td>姓名</td><td>项目名</td><td>时间</td><td>通过|不通过|详细</td></tr></thead><tbody>';
			foreach ($result as $key => $value) {
				$msg .= '<tr><td>'.($key+1).'</td><td>'.$this->adminStatusThatHumanCanRead($value['Status']).'</td><td>'.$value['username'].'</td><td>'.$value['Item_Name'].'</td><td>'.$value['Date'].'</td><td><div class=\"Set_dele glyphicon glyphicon-ok\" onclick=\"ItemPass('.$value['Item_Id'].')\"></div>｜<div class=\"Set_dele glyphicon glyphicon-remove\" onclick=\"ItemFail('.$value['Item_Id'].')\"></div>｜<div class=\"Set_dele glyphicon glyphicon-eye-open\" onclick=\"ItemDescribe('.$value['Item_Id'].')\"></div></td></tr>';
			}
			echo '{"success":true,"msg":"'.$msg.'","allpage":"'.$countpage.'"}';
		} else {
			echo '{"success":true,"msg":"没有项目","allpage":"'.$countpage.'"}';
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
		//		$content['content']    = $_POST['content'];
		$content['content']   = $_POST['content'];
		$content['urgentLev'] = 1;
		if ($owntodos->insertTodoData($content)) {
			$result = '{"success":true,"msg":"<div data-createDate=\"'.$content['CreateDate'].'\" data-Num=\"'.$content['Num'].'\" class=\"mission_type\" draggable=\"true\" ondragstart=\"drag(event)\"><span class=\"mission_SpDes\">'.$content['content'].'</span><span class=\"mission_SpDate\">'.$content['CreateDate'].'</span>';
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
	//删除个人todo
	public function actionDeleteownertodo() {
		$Num        = $_POST['Num'];
		$CreateDate = $_POST['CreateDate'];
		$owntodo    = new OwnTodos();
		$result     = $owntodo->deleteTodo($Num, $CreateDate);
		if ($result) {
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
	/**
	 *
	 */
	public function actionInsertitem() {
		$arr['XH_ID']      = \Yii::$app->user->identity->XH_ID;
		$arr['Item_Name']  = $_POST["ItemName"];
		$arr['Item_Intro'] = $_POST["ItemIntro"];
		$arr['Status']     = 1;
		$arr['Date']       = date('y-m-d');
		$item              = new Items();
		$item->XH_ID       = $arr['XH_ID'];
		$item->Item_Name   = $arr['Item_Name'];
		$item->Item_Intro  = $arr['Item_Intro'];
		$item->Status      = $arr['Status'];
		$item->Date        = $arr['Date'];
		$result            = $item->save(false);
		if ($result) {
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
		$item       = new Items();
		$XH_ID      = $item->getClassmark($id);
		$result     = $itemdetail->detailAll($id);
		$rightNowUserId = Yii::$app->user->identity->XH_ID;
		if ($XH_ID == $rightNowUserId) {
			$missionSt = 1;
		} else {
			$missionSt = 2;
		}
		if ($result) {
			$result = $this->itemDetailGetItWithOrder($result, $missionSt);
			echo $result;
		} else {
			$usertb = new UserTb();
			$status = $usertb->getAuthority($rightNowUserId);
			echo '{"success":true, "authority":"'.$status.'", "msg1":"没有任务","missionSt":"'.$missionSt.'"}';
		}
	}
	//显示项目任务的一个细节（附件之类）
	public function actionOnedetailshow() {
		$id             = $_GET['id'];
		$itemdetail     = new Itemdetails();
		$result         = $itemdetail->detailDet($id);
		$rightNowUserId = Yii::$app->user->identity->XH_ID;
		$usertb         = new UserTb();
		$status         = $usertb->getAuthority($rightNowUserId);
		if ($result) {
			if ($status == 1) {
				$msg = '<span onclick=\"closeModel()\" class=\"glyphicon glyphicon-remove delete_span\"></span><div class=\"\" id=\"detailmodel_Main\"><textarea name=\"\" id=\"detail_text\" cols=\"30\" rows=\"3\" class=\"detail_Maintext\">'.$result[0]['discribe'].'</textarea><button class=\"detailmodel_btn\" id=\"\" onclick=\"addInTodo()\">添加入今日任务</button><button onclick=\"changeDetail('.$result[0]['ItemDetail_Id'].')\" class=\"detailmodel_btn\" id=\"\">修改</button></div>';
			} else {
				$msg = '<span onclick=\"closeModel()\" class=\"glyphicon glyphicon-remove delete_span\"></span><div class=\"\" id=\"detailmodel_Main\"><p name=\"\" id=\"detail_text\" cols=\"30\" rows=\"3\" class=\"detail_Maintext\">'.$result[0]['discribe'].'</p><br><button class=\"detailmodel_btn \" id=\"\" onclick=\"addInTodo()\">添加入今日任务</button>';
			}
			echo '{"success":true, "msg":"'.$msg.'"}';
		} else {
			echo '{"success":false,"msg":"系统错误"}';
		}
	}
	//项目细节排序
	private function itemDetailGetItWithOrder($query, $missionSt) {
		$rightNowUserId = Yii::$app->user->identity->XH_ID;
		$usertb = new UserTb();
		$status = $usertb->getAuthority($rightNowUserId);
		$Lev1   = '';
		$Lev2   = '';
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
		return '{"success":true, "authority":"'.$status.'","msg1":"'.$Lev1.'", "msg2":"'.$Lev2.'","msg3":"'.$query[0]['item_id'].'","missionSt":"'.$missionSt.'"}';
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

	public function actionInsertitemperson() {
		if(empty($_POST['person'])||empty($_POST['Item_Id'])||!isset($_POST['Item_Id'])||!isset($_POST['person'])){
			echo '{"success":false,"msg":"选择有误"}';
			return;
		}
		$arr['XH_ID']   = $_POST['person'];
		$arr['Item_Id'] = $_POST['Item_Id'];
		$itemperson     = new Itempersons();
		$result         = $itemperson->insertperson($arr);
		if ($result) {
			echo '{"success":true,"msg":"组员添加成功"}';
		} else {
			echo '{"success":false,"msg":"该组员已参加此项目"}';
		}
	}

	public function actionDeleteItem() {
		$Item_Id = $_POST['Item_Id'];
		$item    = new Item();
		$result  = $item->deleteItem($Item_Id);
		if ($result) {
			echo '{"success":true}';
		} else {
			echo '{"success": false}';
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
		//		$result          = $itemdetail->insertDetail($arr);
		$itemdetail->item_id  = $arr['item_id'];
		$itemdetail->discribe = $arr['discribe'];
		$itemdetail->status   = $arr['status'];
		$itemdetail->Date     = $arr['Date'];
		$itemdetail->Time     = $arr['Time'];
		$result               = $itemdetail->save();
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
		//		$item_id = 1;
		$item   = new Items();
		$result = $item->searchItemsDetail($item_id);
		if ($result) {
			echo '{"success":true,"msg":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';
		} else {
			echo '{"success":false}';
		}
	}

	//item跳转管理员界面
	function actionHrefadmin() {
		$status = Yii::$app->user->identity->status;
		return $status;
	}
	//置顶动态获取
	public function actionGettopmoment() {
		$moments = new Moments();
		$usertb  = new UserTb();
		$result  = $moments->getAllTopMoment();
		$msg     = '';
		$name    = '';
		foreach ($result as $value) {
			$name = $usertb->getName($value['XH_ID']);
			$msg .= '<div class=\"moment_Sty\"><div class=\"moment_Owner\"><p class=\"centerMomentName\">'.$name.'</p></div><div class=\"moment_Content\"><p class=\"centerMomentName\">'.$value['Content'].'</p></div><div class=\"moment_Date\"><p class=\"centerMomentName\">'.$value['Mdate'].'</p></div></div>';
		}
		if ($msg) {
			echo '{"success" :true , "msg":"'.$msg.'"}';
		} else {
			echo '{"success" :false , "msg":"没有置顶动态"}';
		}

	}
	//库存管理
	/**
	 * 库存分页
	 */
	public function actionArticlepagchange() {
		$request = \Yii::$app->request;
		$page    = $request->get("page");
		$article = new Articles();
		$result  = $article->showarticledata($page, 6);
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
		$page     = $_GET['page'];
		$artice   = new Articles();
		$result   = $artice->searcharticle($Art_Name, 1, 6);
		echo $result;
	}

	/**
	 * 搜索的分页
	 */
	public function actionAdminsearcharticlefenye() {
		$Art_Name = $_GET["article"];
		$page     = $_GET['page'];
		$artice   = new Articles();
		$result   = $artice->searcharticle($Art_Name, $page, 6);
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
		$status  = $_GET["searcharticle"];
		$page    = $_GET['page'];
		$article = new Articles();
		$result  = $article->searcharticlebystatus($status, 1, 6);
		echo $result;
	}
	/**
	 * 状态筛选分页
	 */
	public function actionArticlepagchangesel() {
		$request = \Yii::$app->request;
		$page    = $request->get("page");
		$status  = $request->get("status");
		$article = new Articles();
		$result  = $article->searcharticlebystatus($status, $page, 6);
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
		if (empty($_POST["changeart_inp"]) || !isset($_POST["changeart_inp"])) {
			echo '{"success":false,"msg":"信息填写错误"}';
			return;
		}
		if (!is_numeric($_POST["changeart_inp"])) {
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
		if (empty($_POST["changeart_inp"]) || !isset($_POST["changeart_inp"])) {
			echo '{"success":false,"msg":"信息填写错误"}';
			return;
		}
		if (!is_numeric($_POST["changeart_inp"])) {
			echo '{"success":false,"msg":"数量填写错误"}';
			return;
		}
		if ($_POST["total"] < 0) {
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

	private function adminStatusThatHumanCanRead($status) {
		switch ($status) {
			case 1:
				return '待审核';
				break;
			case 2:
				return '审核通过';
				break;
			case 3:
				return '已完成';
				break;
			case 4:
				return '未通过';
				break;
		}
	}
	public function actionGetitemuser() {
		$itemid=$_GET['itemid'];
		$item = new Items();
		$content = $item->getitemuser($itemid);
		return $content;
	}
}
