<?php

namespace app\models;
use app\controllers\DbFactory;
use Yii;

/**
 * This is the model class for table "Items".
 *
 * @property integer $Item_Id
 * @property string $XH_ID
 * @property string $Item_Name
 * @property string $Item_Intro
 * @property integer $Status
 * @property integer $ShowPublic
 *
 * @property UserTb $xH
 */

class Items extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'Items';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['XH_ID', 'Item_Name', 'Status', 'ShowPublic'], 'required'],
			[['Status'], 'integer'],
			[['XH_ID'], 'string', 'max'      => 10],
			[['Item_Name'], 'string', 'max'  => 16],
			[['Item_Intro'], 'string', 'max' => 64],
			[['Date'], 'string', 'max'       => 8],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'Item_Id'    => 'Item  ID',
			'XH_ID'      => 'Xh  ID',
			'Item_Name'  => 'Item  Name',
			'Item_Intro' => 'Item  Intro',
			'Status'     => 'Status',
			'Date'       => 'Date',
		];
	}

	public function insertItem($arr) {
		$Dbfactory = DbFactory::getinstance();
		return $Dbfactory->insertIntoDb('items', $arr);
	}

	public function searchAllItems() {
		$XH_ID     = \Yii::$app->user->identity->XH_ID;
		$sql       = 'SELECT Item_Id,Item_Name FROM items WHERE XH_ID = \''.$XH_ID.'\' and Status = 2';
		$Dbfactory = DbFactory::getinstance();
		$query     = $Dbfactory->doQuery($sql);
		return $Dbfactory->findAll($query);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getXH() {
		return $this->hasOne(UserTb::className(), ['XH_ID' => 'XH_ID']);
	}
}
