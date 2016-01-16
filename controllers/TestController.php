<?php
namespace app\controllers;
use app\models\Articles;
use app\models\Moments;
use app\models\UserTb;
use yii\web\Controller;

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: application/json;charset=utf-8");

/**
 * 测试专用!!
 */

class TestController extends Controller {

	public function actionAllUser() {
		$usertb  = new UserTb();
		$article = new Articles();
		$moment  = new Moments();
	}

	public function actionArticlechange() {
		$arr['Art_Num'] = 350;
		$article        = new Articles();
		$article->updateArticles(2, $arr);
	}

}