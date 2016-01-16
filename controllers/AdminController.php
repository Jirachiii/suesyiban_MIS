<?php
namespace app\controllers;
use app\models\UserTb;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * 管理员界面的集合
 */

class AdminController extends Controller {

	public $enableCsrfValidation = false;
	public $defaultAction        = 'index';

	//管理员首页
	public function actionIndex() {
		return $this->renderPartial('index');
		// $authority = $this->SureAuthority();
		// if ($authority == '1') {
		// 	return $this->renderPartial('index');
		// } else {
		// 	echo "<script>window.location.href='index.php?r=site/index'</script>";
		// }
	}
	public function actionEmptyclassshow() {
		return $this->renderPartial('emptyclassshow');
	}
	public function actionItemshow() {
		return $this->renderPartial('itemshow');
	}
	public function actionItem() {
		return $this->renderPartial('item');
	}
	public function actionItemcreate() {
		return $this->renderPartial('itemcreate');
	}
	public function actionSigninmene() {
		return $this->renderPartial('signinmene');
	}
	public function actionMomentsmene() {
		return $this->renderPartial('momentsmene');
	}
	//插入学生
	public function actionInsertPeople() {
		$login = new UserTb();

	}
	//修改权限
	public function actionChangeStatus() {

	}
	//确定权限
	private function SureAuthority() {
		$username  = \Yii::$app->session->get('username');
		$authority = \Yii::$app->session->get('authority');
		\Yii::$app->session->close();
		if ($username !== '') {
			return $authority;
		} else {
			return '';
		}
	}

	public function behaviors() {
		return [
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
}