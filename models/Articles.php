<?php

namespace app\models;
use app\controllers\DbFactory;
use Yii;
use yii\db\Query;


/**
 * This is the model class for table "articles".
 *
 * @property integer $Art_Id    库存主键
 * @property string $Art_Name   库存物品名
 * @property integer $Art_Num   物品数量
 * @property string $Art_Time   第一次入库时间
 * @property integer $status    上线状态（有和没有）
 */

class Articles extends \yii\db\ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['Art_Name', 'Art_Num', 'Art_Time', 'status'], 'required'],
			[['Art_Num', 'status'], 'integer'],
			[['Art_Name', 'Art_Time'], 'string', 'max' => 8]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'Art_Id'   => 'Art  ID',
			'Art_Name' => 'Art  Name',
			'Art_Num'  => 'Art  Num',
			'Art_Time' => 'Art  Time',
			'status'   => 'Status',
		];
	}
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'articles';
	}

	//获取名字
	public function getName($id) {
		$Dbfactory = DbFactory::getinstance();
		return $Dbfactory->findOnlyOne('Art_Name', 'articles', 'Art_Id', $id);
	}
	//获取物品数量
	public function getArtNum($id) {
		$Dbfactory = DbFactory::getinstance();
		return $Dbfactory->findOnlyOne('Art_Num', 'articles', 'Art_Id', $id);
	}
	//获取状态
	public function getStatus($id) {
		$Dbfactory = DbFactory::getinstance();
		return $Dbfactory->findOnlyOne('status', 'articles', 'Art_Id', $id);
	}
	//更新物品信息
	public function updateArticles($id, $arr) {
		$Dbfactory = DbFactory::getinstance();
		$Dbfactory->updateTheDbRecord('articles', 'Art_Id', $id, $arr);
	}



	/**
	 * 筛选库存
	 * @param $status
	 * @return array|string|\yii\db\ActiveRecord[]
	 */
	public function searcharticlebystatus($status,$page,$numbers){
		$index=($page-1)*6;
		if ($status == 4) {
			$result = self::find()->asArray()->orderBy('status ASC,Art_Num DESC')->limit($numbers)->offset($index)->all();
			if(!empty($result)){
				$allPage=self::countarticlrpages($numbers);
			}else{
				$allPage=0;
			}
		} else {
			$result = self::find()->where(['status' => $status])->asArray()->orderBy('status ASC,Art_Num DESC')->limit($numbers)->offset($index)->all();
			if(!empty($result)){
				$allPage=self::countarticlrpages_sel($numbers,$status);
			}else{
				$allPage=0;
			}
//
		}
		if($allPage<$page||$page<1){
			$result='{"success":false,"msg":"未找到条目"}';
			return $result;
		}
		$result = '{"success":true,"articles":'.json_encode($result, JSON_UNESCAPED_UNICODE).',"allPage":'.$allPage.'}';
		return $result;
	}

	/**
	 * 所有物品显示
	 * @param $page
	 * @param $numbers
	 * @return array|string
	 */
	public  function  showarticledata($page,$numbers){
		$index=($page-1)*$numbers;
		$result   = (new Query())
			//			->select(['Art_Name', 'status'])
			->from('articles')
			->limit($numbers)
			->offset($index)
			->orderBy([
				'status' => SORT_ASC,
				'Art_Num' => SORT_DESC,
			])
			->all();
		$allPage=self::countarticlrpages($numbers);
		if($allPage<$page||$page<1){
			$result='{"success":false,"msg":"未找到条目"}';
			return $result;
		}
		if(empty($result)){
			$result = '{"success":false,"articles":'.json_encode($result, JSON_UNESCAPED_UNICODE).',"allPage":'.$allPage.',"msg":"未找到条目"}';
		}else {
			$result = '{"success":true,"articles":' . json_encode($result, JSON_UNESCAPED_UNICODE) . ',"allPage":' . $allPage . '}';
		}
		return $result;
	}
	/**
	 * 搜索库存
	 * @param $Art_Name
	 * @return array|string
	 */
	public function searcharticle($Art_Name,$page,$numbers){
		$index=($page-1)*$numbers;
		$result   = (new Query())
			//			->select(['Art_Name', 'status'])
			->from('articles')
			->where(['like', 'Art_Name', $Art_Name])
			->limit($numbers)
			->offset($index)
			->all();
		$allPage=self::countarticlrpages_s($numbers,$Art_Name);
		if($allPage<$page||$page<1){
			$result='{"success":false,"msg":"未找到条目"}';
			return $result;
		}
		if(empty($result)){
			$result = '{"success":false,"articles":'.json_encode($result, JSON_UNESCAPED_UNICODE).',"allPage":'.$allPage.',"msg":"未找到条目"}';
		}else {
			$result = '{"success":true,"articles":'.json_encode($result, JSON_UNESCAPED_UNICODE).',"allPage":' . $allPage . '}';
		}
		return $result;
	}
	public function countarticlrpages($numbers){
		$allpages=self::find()->count();
		return (ceil($allpages/$numbers));
	}
	public function countarticlrpages_sel($numbers,$status){
		$allpages = self::find()->where(['status' => $status])->count();
		return (ceil($allpages/$numbers));
	}
	public function countarticlrpages_s($numbers,$Art_Name){
	$allpages = self::find()->where("Art_Name like '%$Art_Name%'")->count();
	return (ceil($allpages/$numbers));
}

}
