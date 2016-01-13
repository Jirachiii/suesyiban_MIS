<?php
namespace app\controllers;

use app\models\UserTb;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

class SiteController extends Controller {

	public $enableCsrfValidation = false;
	public $defaultAction        = 'index';

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

	public function actionIndex() {
		return $this->renderPartial('index');
	}

	public function actionLogin() {
		$session  = \Yii::$app->session;
		$logornot = $session->get('username');
		$session->close();
		if ($logornot == '') {
			$request  = \Yii::$app->request;
			$username = $request->post('username', '');
			$password = $request->post('password', '');
			$login    = new UserTb();
			$error    = $login->handleLogin($username, $password);
			if ($error == '') {
				$authority = \Yii::$app->session->set('authority', $authority);
				$session->close();
				switch ($authority) {
					case '1':
						echo "<script>window.location.href='index.php?r=admin/index'</script>";
						break;
					case '2':
						echo "2";
						break;
					case '3':
						echo "3";
						break;
					default:
						return $this->renderPartial('index');
						break;
				}
			} else {
				return $this->renderPartial('login');
			}
		} else {
			echo "<script>window.location.href='index.php?r=site/index'</script>";
		}

	}
	public function actionLogout() {
		//登录只要销毁session内的值即可
		$session = \Yii::$app->session;
		if (!($session->isActive)) {
			$session->open();
		}
		$session->remove('username');
		$session->remove('authority');
		$session->close();
		return $this->renderPartial('login');
	}

	public function actionTest() {
		return $this->renderPartial('post');
	}
}
