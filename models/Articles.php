<?php

namespace app\models;
use app\controllers\DbFactory;
use Yii;

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
}
