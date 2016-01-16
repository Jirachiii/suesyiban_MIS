<?php
namespace app\controllers;

use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * 用户界面的集合
 */

class AdminController extends Controller {

	public $enableCsrfValidation = false;
	public $defaultAction        = 'index';

	//用户首页
	public function actionIndex() {
		return $this->renderPartial('index');
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