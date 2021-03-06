<?php
namespace app\controllers;
use app\models\Articles;
use app\models\Itempersons;
use app\models\Items;
use app\models\Moments;
use app\models\MomentTop;
use app\models\OwnTodos;
use app\models\TestTb;
use app\models\UserTb;
use yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
date_default_timezone_set("PRC");
header('Content-type: application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: application/json;charset=utf-8");

/**
 * 页面刚进入时通过json获取数据，之后全部为ajax处理
 *
 */

class JsonController extends Controller {
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only'  => [ 'getmomentdata','getuserdata', 'getarticledata','getitemdetail', 'owntodos','getitems','admingetallitems','momentsshow','test'],
				'rules' => [
					//只有1级管理员有权限
					[
						'allow'         => true,
						'actions'       =>[ 'getmomentdata','getuserdata', 'getarticledata','getitemdetail', 'owntodos','getitems','admingetallitems','momentsshow','test'],
						'roles'         => ['@'],
						'matchCallback' => function ($rule, $action) {
							return Yii::$app->user->identity->status == 1;
						}
					],
					//2级管理员有权限
					[
						'allow'         => true,
						'actions'       =>['momentsshow','owntodos','getitems'],
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

	public function actions() {
		return [
			'error'  => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha'          => [
				'class'           => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST?'testme':null,
			],
		];
	}

	/**
	 * 获取动态
	 */
	public function actionGetmomentdata() {
		$rightNowUserId   = Yii::$app->user->identity->XH_ID;
		$rightNowUserName = Yii::$app->user->identity->Name;
		$moments          = new Moments();
		$content          = $moments->getPageMomentWithOrder(1, 6);
		$allPage          = $moments->getAllPage(6);
		//		$content = Moments::find()->asArray()->join('LEFT JOIN','user_tb','moments.XH_ID=user_tb.XH_ID')->orderBy('Mdate DESC,Time DESC')->all();
		//		$content = Moments::find()->asArray()->orderBy('Mdate DESC,Time DESC')->all();
		$istop = MomentTop::find()->all();
		if (!empty($content)) {
			foreach ($content as $key => $value) {
				foreach ($istop as $key1 => $value1) {
					if ($value1['moment_id'] == $value['id']) {
						$content[$key]['status'] = $value1['status'];
					}
				}
				$name                      = UserTb::findOne($value['XH_ID']);
				$content[$key]['username'] = $name['Name'];
			}
		}
		$content = '{"moments":'.json_encode($content, JSON_UNESCAPED_UNICODE).',"allPage":"'.$allPage.'","userIdNow":"'.$rightNowUserId.'","userName":"'.$rightNowUserName.'"}';
		echo $content;
	}

	/**
	 * 获取用户列表
	 */
	public function actionGetuserdata() {
		$rightNowUserId   = Yii::$app->user->identity->XH_ID;
		$rightNowUserName = Yii::$app->user->identity->Name;
		$usertb           = new UserTb();
		$result           = $usertb->getPageUserWithOrder(1, 6);
		$allpage          = $usertb->userallpage(6);
		if (empty($result)) {
			$result = '{"success":false,"msg":"获取失败","users":'.json_encode($result, JSON_UNESCAPED_UNICODE).',"userIdNow":"'.$rightNowUserId.'","userName":"'.$rightNowUserName.'"}';
		} else {
			$result = '{"success":true,"allPage":"'.$allpage.'","users":'.json_encode($result, JSON_UNESCAPED_UNICODE).',"userIdNow":"'.$rightNowUserId.'","userName":"'.$rightNowUserName.'"}';

		}
		echo $result;
	}

	/**
	 * 获取article物品
	 */
	public function actionGetarticledata() {
		$article = new Articles();
		$result  = $article->showarticledata(1, 6);
		echo $result;
	}

	public function actionGetitemdetail() {
		$testTb  = new TestTb();
		$content = $testTb->getTestData();
		echo $content;
	}

	public function actionOwntodos() {
		$owntodos       = new ownTodos();
		$query          = $owntodos->findTodayMission();
		$rightNowUserId = Yii::$app->user->identity->XH_ID;
		$usertb         = new UserTb();
		$name           = $usertb->getName($rightNowUserId);
		if ($query) {
			$msg = $this->getItWithOrder($query);
			echo '{"success":true,"msg":"'.$msg.'","name":"'.$name.'"}';
		} else {
			echo '{"success":false,"msg":"没有任务","name":"'.$name.'"}';
		}
	}

	private function getItWithOrder($query) {
		$urgentLev1 = '';
		$urgentLev2 = '';
		$urgentLev3 = '';
		foreach ($query as $key => $value) {
			switch ($value['urgentLev']) {
				case '1':
					$urgentLev1 .= '<div id=\"mission'.$value['Num'].$value['CreateDate'].'\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\" class=\"mission_type\" draggable=\"true\" ondragstart=\"drag(event)\"><span class=\"mission_SpDes\">'.$value['content'].'</span><span class=\"mission_SpDate\">'.$value['CreateDate'].'</span>';
					$urgentLev1 .= '<div onclick=\"Urgenthandle(this,1)\" class=\"mission_SpUrgent normal\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\"></div><div onclick=\"Urgenthandle(this,2)\" class=\"mission_SpUrgent urgenter\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\"></div><div onclick=\"Urgenthandle(this,3)\" class=\"mission_SpUrgent urgentest\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\"></div></div>';
					break;
				case '2':
					$urgentLev2 .= '<div id=\"mission'.$value['Num'].$value['CreateDate'].'\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\" class=\"mission_type UrgenterBorder\" draggable=\"true\" ondragstart=\"drag(event)\"><span class=\"mission_SpDes\">'.$value['content'].'</span><span class=\"mission_SpDate\">'.$value['CreateDate'].'</span>';
					$urgentLev2 .= '<div onclick=\"Urgenthandle(this,1)\" class=\"mission_SpUrgent normal\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\"></div><div onclick=\"Urgenthandle(this,2)\" class=\"mission_SpUrgent urgenter\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\"></div><div onclick=\"Urgenthandle(this,3)\" class=\"mission_SpUrgent urgentest\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\"></div></div>';
					break;
				case '3':
					$urgentLev3 .= '<div id=\"mission'.$value['Num'].$value['CreateDate'].'\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\" class=\"mission_type UrgentestBorder\" draggable=\"true\" ondragstart=\"drag(event)\"><span class=\"mission_SpDes\">'.$value['content'].'</span><span class=\"mission_SpDate\">'.$value['CreateDate'].'</span>';
					$urgentLev3 .= '<div onclick=\"Urgenthandle(this,1)\" class=\"mission_SpUrgent normal\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\"></div><div onclick=\"Urgenthandle(this,2)\" class=\"mission_SpUrgent urgenter\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\"></div><div onclick=\"Urgenthandle(this,3)\" class=\"mission_SpUrgent urgentest\" data-createDate=\"'.$value['CreateDate'].'\" data-Num=\"'.$value['Num'].'\"></div></div>';
					break;
				case '4':
					break;
			}
		}
		return $urgentLev3.$urgentLev2.$urgentLev1;
	}

	public function actionGetitems() {
		$msg            = '';
		$item           = new Items();
		$itemPerson     = new Itempersons();
		$rightNowUserId = Yii::$app->user->identity->XH_ID;
		$usertb         = new UserTb();
		$status         = $usertb->getAuthority($rightNowUserId);
		$name           = $usertb->getName($rightNowUserId);
		$result         = $item->searchAllItems(2);
		$result2        = $item->searchOtherItems(2);
		if ($result2 || $result) {
			foreach ($result as $value) {
				$msg .= '<div onclick=\"detailShow('.$value['Item_Id'].')\" id=\"'.$value['Item_Id'].'\" class=\"item_show\" style=\"background-image: url(images/itemImg.jpeg);\"><h3 class=\"item_showtit\">'.$value['Item_Name'].'</h3></div>';
			}
			foreach ($result2 as $value) {
				$msg .= '<div onclick=\"detailShow('.$value['Item_Id'].')\" id=\"'.$value['Item_Id'].'\" class=\"item_show\" style=\"background-image: url(images/itemImg.jpeg);\"><h3 class=\"item_showtit\">'.$value['Item_Name'].'</h3></div>';
			}
			echo '{"success":true,"msg": "'.$msg.'","msg1":"'.$status.'","name":"'.$name.'"}';
		} else {
			echo '{"success":false,"msg1":"'.$status.'","name":"'.$name.'"}';
		}
	}
	//管理员审核项目
	public function actionAdmingetallitems() {
		$rightNowUserId   = Yii::$app->user->identity->XH_ID;
		$rightNowUserName = Yii::$app->user->identity->Name;
		$item   = new Items();
		$nowpage=$_GET['nowpage'];
		$allpage=$_GET['allpage'];
		$countallpages=$item->getItempages(5);
		if($allpage>$countallpages||$allpage<0){
			$result='{"success":false,"msg":"查询出错","userIdNow":"'.$rightNowUserId.'","userName":"'.$rightNowUserName.'","allpage":"'.$countallpages.'"}';
			return $result;
		}
		$result = $item->AdminAllItems($nowpage, 5);
		if ($result) {
			$msg = '<thead><tr><td>编号</td><td>状态</td><td>姓名</td><td>项目名</td><td>时间</td><td>通过|不通过|详细</td></tr></thead><tbody>';
			foreach ($result as $key => $value) {
				$msg .= '<tr><td>'.($key+1).'</td><td>'.$this->adminStatusThatHumanCanRead($value['status']).'</td><td>'.$value['username'].'</td><td>'.$value['Item_Name'].'</td><td>'.$value['Date'].'</td><td><div class=\"Set_dele glyphicon glyphicon-ok\" onclick=\"ItemPass('.$value['Item_Id'].')\"></div>｜<div class=\"Set_dele glyphicon glyphicon-remove\" onclick=\"ItemFail('.$value['Item_Id'].')\"></div>｜<div class=\"Set_dele glyphicon glyphicon-eye-open\" onclick=\"ItemDescribe('.$value['Item_Id'].')\"></div></td></tr>';
			}
			echo '{"success":true,"msg":"'.$msg.'","userIdNow":"'.$rightNowUserId.'","userName":"'.$rightNowUserName.'","allpage":"'.$countallpages.'"}';
		} else {
			echo '{"success":true,"msg":"没有项目","userIdNow":"'.$rightNowUserId.'","userName":"'.$rightNowUserName.'","allpage":"'.$countallpages.'"}';
		}

	}
	//管理员项目状态
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
	//
	public function actionMomentsshow() {

		$rightNowUserId   = Yii::$app->user->identity->XH_ID;
		$rightNowUserName = Yii::$app->user->identity->Name;
		$moments          = new Moments();
		$result           = $moments->getPageMomentWithOrder(1, 20);
		$msg              = '';
		foreach ($result as $key => $value) {
			if ($key != 0) {
				$msg .= '<div class=\"moment_Sty\"><div class=\"moment_Owner\"><p class=\"centerMomentName\">'.$value['username'].'</p></div><div class=\"moment_Content\"><p class=\"centerMomentName\">'.$value['Content'].'</p></div><div class=\"moment_Date\"><p class=\"centerMomentName\">'.$value['Mdate'].'</p></div></div>';
			}
		}
		if ($msg) {
			echo '{"success" :true , "msg":"'.$msg.'","userIdNow":"'.$rightNowUserId.'","userName":"'.$rightNowUserName.'"}';
		} else {
			echo '{"success" :false , "msg":"没有动态","userIdNow":"'.$rightNowUserId.'","userName":"'.$rightNowUserName.'"}';

		}
	}
	//这下面都不属于这边，以后更换位置
	//	public function actionAddmoment() {
	//		$content            = '测试';
	//		$moment             = new Moments();
	//		$RightNow           = $moment->getDateAndTime();
	//		$momentMsg['XH_ID'] = Yii::$app->user->identity->XH_ID;
	//		$momentMsg['Mdate'] = $RightNow['date'];
	//		$momentMsg['Time']  = $RightNow['time'];
	//		//以后解决，request过来的值
	//		$momentMsg['Content']  = $_POST['Content'];
	//		$momentMsg['like_Num'] = 0;
	//		echo $moment->insertMomentData($momentMsg);
	//	}

	public function actionTest() {
		$Dbfactory = DbFactory::getinstance();
		$count     = $Dbfactory->TableCount('Moments');
		echo $count;
	}

	//	/**
	//	 * 获取空课表
	//	 */
	//	public function actionGetemptyclass(){
	////		$content = UserTb::find()->asArray()->all();
	//		$content   = (new Query())
	//			->select(['XH_ID', 'Name','status'])
	//			->from('user_tb')
	//			->orderBy('status DESC')
	//			->all();
	//
	//		$content = '{"moments":'.json_encode($content, JSON_UNESCAPED_UNICODE).'}';
	//		echo $content;
	//	}

}
