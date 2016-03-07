<?php
namespace app\controllers;
use yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * 用户界面的集合
 */

class UserController extends Controller {

	public $enableCsrfValidation = false;
	public $defaultAction        = 'item';

	//用户首页
	public function actionIndex() {
		return $this->renderPartial('index.html');
	}

	public function actionItem() {
		return $this->renderPartial('item.html');
	}

	public function actionItemdetail() {
		return $this->renderPartial('ItemDetail.html');
	}

	public function actionGetmess() {
		return $this->renderPartial('message.html');
	}

	public function actionHomepage() {
		return $this->renderPartial('homepage.html');
	}
	public function actionOrder() {
		return $this->renderPartial('stOrderclass.html');
 	}
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only'  => ['index','item','itemdetail','getmess','homepage','order'],
				'rules' => [
					//1级管理员权限控制
					[
						'actions'       => ['index','item','itemdetail','getmess','homepage','order'],
						'allow'         => true,
						'roles'         => ['@'],
						'matchCallback' => function ($rule, $action) {
							return Yii::$app->user->identity->status == 1;
						}
					],
					//2级管理员权限控制
					[
						'actions'       => [],
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