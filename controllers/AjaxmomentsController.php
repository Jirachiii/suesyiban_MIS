<?php
namespace app\controllers;
use app\models\Moments;
use app\models\MomentTop;
use app\models\UserTb;
use Faker\Provider\DateTime;
use yii\web\Controller;
use yii\db\Query;

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: application/json;charset=utf-8");

/**
 * 管理员界面专用!!
 * ajax接口获取所有信息，并对其进行业务处理
 */

class AjaxmomentsController extends Controller {

	public $enableCsrfValidation = false;
	//删除一条记录
	public function actionDeleteonemoment() {
		$id     = $_POST['moment'];
		$moment = new Moments();
//		$momenttop=new MomentTop();
//		$thetop=MomentTop::find(['moment_id'=>$id]);
//		$thetop->delete();
		if($thetop=MomentTop::find()->where(['moment_id'=>$id])->one()){
			$thetop->delete();
		}
		$moment->deleteOneMoment($id);
		echo '{"success":true}';
	}
	/**
	 * 分页
	 * @return string
	 */
	public function actionPagechange() {
		$page   = $_POST['page'];
		$moment = new Moments();
		$result = $moment->getPageMomentWithOrder($page,6);
		$allPage = $moment->getAllPage(6);
		if ($result == false) {
			return '{"success":false}';
		} else {
			$result = '{"success":true,"moments":'.json_encode($result, JSON_UNESCAPED_UNICODE).',"allPage":"'.$allPage.'"}';
			echo $result;
		}
	}
	//页码
//	public function actionGetyema(){
//
//	}
	/**
	 * 搜索的分页
	 * @return string搜索的分页
	 */
	public function actionPagechange_s() {
		$page   = $_POST['page'];
		$input   = $_POST['input'];
		$moment = new Moments();
		$result = $moment->getPageMomentWithOrder_2($input,$page,6);
		$allPage = $moment->getAllPage_s(6,$input);
		echo $result;
	}
//搜索动态
	public function actionAdminsearchmoment() {
		if (!isset($_GET["searchmoment"]) || empty($_GET["searchmoment"])) {
			echo '{"success":false,"msg":"请输查询入内容"}';
			return;
		}
		$input = $_GET["searchmoment"];
		$moments=new Moments();
		$result=$moments->getPageMomentWithOrder_2($input,1, 6);
		echo $result;
	}
	//动态修改
	public function actionAdminupdatemoment() {
		//判断信息是否填写完全
		if ((empty($_POST["moment_content"]) ||!isset($_POST["moment_content"]))||
			(empty($_POST["momentid"]) || !isset($_POST["momentid"]))) {
			echo '{"success":false,"msg":"内容填写错误"}';
			return;
		}
		$aimmoment         = Moments::findOne($_POST['momentid']);
		$aimmoment->Content = $_POST['moment_content'];
		$aimmoment->save(false);
		echo '{"success":true,"msg":"添加成功！"}';
	}
	//添加动态
	public function actionAdminupdatemoment_1() {
		//判断信息是否填写完全
		if ((empty($_POST["moment_content"]) ||!isset($_POST["moment_content"]))) {
			echo '{"success":false,"msg":"内容填写错误"}';
			return;
//			exit();
		}
		$newmoment=new Moments();
		$newmoment->Content=$_POST['moment_content'];
		$newmoment->XH_ID=\Yii::$app->user->identity->XH_ID;
		$newmoment->Mdate=date('y-m-d');
		$newmoment->Time=date('H:i:s');
		$newmoment->like_Num=0;
		$newmoment->save(false);
		echo '{"success":true,"msg":"添加成功！"}';
	}
	//状态置顶方法
	public function actionAdminupdatemoment_2() {
		//判断信息是否填写完全
		if ((empty($_POST["momentid"]) || !isset($_POST["momentid"]))||
			(empty($_POST["status"]) || !isset($_POST["status"]))) {
			echo '{"success":false,"msg":"选项错误"}';
			return;
		}
		if($_POST['status']=="1"){
			if(MomentTop::find()->count()>=5){
				echo'{"success":false,"msg":"置顶上限已满！！"}';
				exit();
			}
			$newtop=new MomentTop();
			$newtop->moment_id=$_POST['momentid'];
			$newtop->status=1;
			$newtop->save();
		}else if($_POST['status']=="2"){
			$deltop=MomentTop::find()->where(['moment_id' =>$_POST["momentid"]])->one();
			$deltop->delete();
		}else{
			echo'{"success":false,"msg":"出现错误！"}';
		}
		echo '{"success":true,"msg":"添加成功！"}';
	}


}

