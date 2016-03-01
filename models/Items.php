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
 *@property string $Date
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
	//删除
	public function deleteItem($id) {
		$Dbfactory = DbFactory::getinstance();
		return $Dbfactory->deleteOneRecord('items', 'Item_Id', $id);
	}

	public function insertItem($arr) {
		$Dbfactory = DbFactory::getinstance();
		return $Dbfactory->insertIntoDb('items', $arr);
	}

	public function searchAllItems($status) {
		$XH_ID     = \Yii::$app->user->identity->XH_ID;
		$sql       = 'SELECT Item_Id,Item_Name FROM items WHERE XH_ID = \''.$XH_ID.'\' and Status = '.$status;
//		$Dbfactory = DbFactory::getinstance();
//		$query     = $Dbfactory->doQuery($sql);
//		return $Dbfactory->findAll($query);
		$result=Yii::$app->db->createCommand($sql)->queryAll();
		return $result;
	}
	//
	public function searchItemsDetail($Item_Id) {
		$sql       = 'SELECT * FROM items WHERE  Item_Id = '.$Item_Id;
		$Dbfactory = DbFactory::getinstance();
		$query     = $Dbfactory->doQuery($sql);
		return $Dbfactory->findAll($query);
	}
	//更新项目状态
	public function updateStatus($id, $arrUpdate) {
		$Dbfactory = DbFactory::getinstance();
		return $Dbfactory->updateTheDbRecord('items', 'Item_Id', $id, $arrUpdate);
	}
	//获取状态项目
	public function AdminSearchAllItems($status, $page, $number) {
		if ($this->decideGetMomentContinue($page, $number)) {
			$front     = ($page-1)*$number;
			$sql       = 'SELECT XH_ID,Item_Name,Item_Intro,Date FROM items WHERE Status = '.$status.' ORDER BY Date DESC LIMIT '.$front.','.$number;
			$Dbfactory = DbFactory::getinstance();
			$items     = $Dbfactory->findBySql($sql);
			$user      = new UserTb();
			foreach ($items as $key => $value) {
				$XH                      = $value['XH_ID'];
				$username                = $user->getName($XH);
				$items[$key]['username'] = $username;
			}
			return $items;
		} else {
			return false;
		}
	}
	//获取所有项目
	public function AdminAllItems($page, $number) {
		if ($this->decideGetMomentContinue($page, $number)) {
			$front     = ($page-1)*$number;
			$sql       = 'SELECT Item_Id,XH_ID,Item_Name,Item_Intro,Date,status FROM items ORDER BY Date DESC LIMIT '.$front.','.$number;
			$Dbfactory = DbFactory::getinstance();
			$items     = $Dbfactory->findBySql($sql);
			$user      = new UserTb();
			foreach ($items as $key => $value) {
				$XH                      = $value['XH_ID'];
				$username                = $user->getName($XH);
				$items[$key]['username'] = $username;
			}
			return $items;
		} else {
			return false;
		}
	}
	private function decideGetMomentContinue($page, $number) {
		$frontCount = ($page-1)*$number;
		$Count      = $this->getCount();
		if ($frontCount >= $Count) {
			return false;
		} else {
			return true;
		}
	}
	//获取个数
	public function getCount() {
		$Dbfactory = DbFactory::getinstance();
		return $Dbfactory->tableCount('items');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getXH() {
		return $this->hasOne(UserTb::className(), ['XH_ID' => 'XH_ID']);
	}
}
