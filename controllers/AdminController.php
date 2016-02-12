<?php
namespace app\controllers;
use app\models\UserTb;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * 管理员界面的集合
 */

class AdminController extends Controller {
	/**
	 * 权限设置，添加新方法需在这里“注册”权限
	 * @return array
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only'  => ['logout', 'index', 'login', 'emptyclassshow,', 'itemshow', 'itemcreate', 'articles', 'signinmene', 'momentsmene'],
				'rules' => [
					[
						'allow'   => true,
						'actions' => ['login'],
						'roles'   => ['?'],
					],
					//只有1级管理员有权限
					[
						'actions'       => ['logout', 'index', 'emptyclassshow,', 'itemshow', 'itemcreate', 'articles', 'signinmene', 'momentsmene'],
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

	public $enableCsrfValidation = false;
	public $defaultAction        = 'index';

	//管理员首页
	public function actionIndex() {
		return $this->renderPartial('index.html');
		// $authority = $this->SureAuthority();
		// if ($authority == '1') {
		// 	return $this->renderPartial('index');
		// } else {
		// 	echo "<script>window.location.href='index.php?r=site/index'</script>";
		// }
	}
	public function actionEmptyclassshow() {
		return $this->renderPartial('emptyclassshow.html');
	}
	public function actionItemshow() {
		return $this->renderPartial('itemshow.html');
	}
	public function actionArticles() {
		return $this->renderPartial('articles.html');
	}
	public function actionItemcreate() {
		return $this->renderPartial('itemcreate.html');
	}
	public function actionSigninmene() {
		return $this->renderPartial('signinmene.html');
	}
	public function actionMomentsmene() {
		return $this->renderPartial('momentsmene.html');
	}
	//插入学生
	public function actionInsertPeople() {
		$login = new UserTb();

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

}