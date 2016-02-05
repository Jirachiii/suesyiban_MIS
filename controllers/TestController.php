<?php
namespace app\controllers;
use app\models\Articles;
use app\models\Items;
use app\models\Moments;
use app\models\OwnTodos;
use app\models\TestTb;
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

	public $enableCsrfValidation = false;

	public function actionAllUser() {
		$usertb  = new UserTb();
		$article = new Articles();
		$moment  = new Moments();
		$upload  = new upload();
		$item    = new Items();
	}

	public function actionArticlechange() {
		$arr['Art_Num'] = 350;
		$article        = new Articles();
		$article->updateArticles(2, $arr);
	}

	public function actionUpload() {
		$uploadfile = new upload('myFile', 'files');
		$dest       = $uploadfile->uploadFile();
		print_r($dest);
		return $this->renderPartial('index.html');
	}

	public function actionDroptest() {
		$testTb = new TestTb();
		echo $testTb->getPageMomentWithOrder(1, 6);
	}

	public function actionTesttodo() {
		$owntodo = new OwnTodos();
		echo $owntodo->changeStatus(4, 4);
	}

	public function actionTestitem() {
		$item = new Items();
		$item->searchAllItems();
	}
}