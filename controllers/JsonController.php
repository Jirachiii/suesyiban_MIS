<?php
namespace app\controllers;
use app\models\Moments;
use yii\web\Controller;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: application/json;charset=utf-8");

/**
 * summary
 */

class JsonController extends Controller {

	function actionGetmomentdata() {
		$moments = new Moments();
		$content = $moments->adminGetMoment();
		if ($content !== '') {
			$msg = '{"success":true,"getcontent":"'.$content[0]['Content'].'","Createname":"'.$content[0]['name'].'","like":"'.$content[0]['like_Num'].'"}';
		} else {
			$msg = '{"success":false}';
		}
		echo $msg;
	}

}