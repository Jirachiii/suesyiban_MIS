<?php
namespace app\controllers;
use app\models\Moments;
use app\models\UserTb;
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

	public function actionGetmomentdata() {
		$moments = new Moments();
		$content = $moments->getPageMomentWithOrder(1, 5);
		$content = '{"moments":'.json_encode($content, JSON_UNESCAPED_UNICODE).'}';
		echo $content;
	}

	public function actionGetuserdata() {
		$usertb = new UserTb();
		$result = $usertb->getPageMomentWithOrder(1, 10);
		$result = '{"users":'.json_encode($result, JSON_UNESCAPED_UNICODE).'}';
		echo $result;
	}

	//这下面都不属于这边，以后更换位置
	public function actionAddmoment() {
		$content = '123456';
		$XH_ID   = \Yii::$app->session->get('username');
		\Yii::$app->session->close();
		$moment             = new Moments();
		$RightNow           = $moment->getDateAndTime();
		$momentMsg['XH_ID'] = '031513217';
		// $momentMsg['XH_ID'] = $XH_ID;
		$momentMsg['Mdate'] = $RightNow['date'];
		$momentMsg['Time']  = $RightNow['time'];
		//以后解决，request过来的值
		$momentMsg['Content']  = 'addmomentadded';
		$momentMsg['like_Num'] = 0;
		echo $moment->insertMomentData($momentMsg);
	}
	public function actionGetmoments() {
		$moment    = new Moments();
		$momentMsg = $moment->getPageMomentWithOrder(1, 5);
		// if ($momentMsg !== '') {
		// 	$momentMsg = json_encode($momentMsg, JSON_UNESCAPED_UNICODE);
		// }
		print_r($momentMsg);
	}

	public function actionTest() {
		$Dbfactory = DbFactory::getinstance();
		$count     = $Dbfactory->TableCount('Moments');
		echo $count;
	}

}