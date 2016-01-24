<?php
namespace app\controllers;
use app\models\Moments;
use app\models\TestTb;
use app\models\UserTb;
use app\models\Articles;
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
				'only'  => ['logout', 'login', 'getuserdata','getarticledata', 'getmomentdata', 'addmoment', 'getmoment'],
				'rules' => [
					[
						'allow'   => true,
						'actions' => ['login'],
						'roles'   => ['?'],
					],
					//只有1级管理员有权限
					[
						'actions'       => ['logout', 'getuserdata','getarticledata', 'getmomentdata', 'addmoment', 'getmoment'],
						'allow'         => true,
						'roles'         => ['@'],
						'matchCallback' => function ($rule, $action) {
							return Yii::$app->user->identity->status == 1;
						}
					],

				],
			],
			//            emptyclassshow/itemshow/itemcreate/articles/signinmene/momentsmene

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

	public function actionGetmomentdata() {
		$moments = new Moments();
		$content = $moments->getPageMomentWithOrder(1, 5);
		$allPage = $moments->getAllPage(5);
		$content = '{"moments":'.json_encode($content, JSON_UNESCAPED_UNICODE).',"allPage":"'.$allPage.'"}';
		echo $content;
	}


	public function actionGetuserdata() {
		$usertb = new UserTb();
		$result = $usertb->getPageMomentWithOrder(1, 5);
		$result = '{"users":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';
		echo $result;
	}
	//获取article物品
	public function actionGetarticledata() {
		$result=Articles::find()->asArray()->orderBy('status ASC')->all();
		$result = '{"articles":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';
		echo $result;
	}

	public function actionGetitemdetail() {
		$testTb  = new TestTb();
		$content = $testTb->getTestData();
		echo $content;
	}

	//这下面都不属于这边，以后更换位置
	public function actionAddmoment() {
		$content = '测试';
		$XH_ID   = \Yii::$app->session->get('username');
		\Yii::$app->session->close();
		$moment             = new Moments();
		$RightNow           = $moment->getDateAndTime();
		$momentMsg['XH_ID'] = $XH_ID;
		$momentMsg['Mdate'] = $RightNow['date'];
		$momentMsg['Time']  = $RightNow['time'];
		//以后解决，request过来的值
		$momentMsg['Content']  = '测试';
		$momentMsg['like_Num'] = 0;
		echo $moment->insertMomentData($momentMsg);
	}

	public function actionTest() {
		$Dbfactory = DbFactory::getinstance();
		$count     = $Dbfactory->TableCount('Moments');
		echo $count;
	}

}