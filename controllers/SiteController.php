<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;

//网页的首页

class SiteController extends Controller {

	public $enableCsrfValidation = false;
	public $defaultAction        = 'index';

	public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','index','login'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    //1级管理员权限控制
                    [
                        'actions' => ['logout','index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->status == 1;
                        }
                    ],
                    //2级管理员权限控制
                    [
                        'actions' => ['logout','index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->status == 2;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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

	public function actionLogin()
	{
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		}
		return $this->renderPartial('login', [
			'model' => $model,
		]);
	}
	public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
	}

	public function actionTest() {
		return $this->renderPartial('post');
	}
}
