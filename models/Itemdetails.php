<?php

namespace app\models;
use app\controllers\DbFactory;
use Yii;

/**
 * This is the model class for table "Itemdetails".
 *
 * @property integer $ItemDetail_Id
 * @property integer $item_id
 * @property string $discribe
 * @property integer $status
 * @property string $Date
 * @property string $Time
 */

class Itemdetails extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'Itemdetails';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['item_id', 'discribe', 'status', 'Date', 'Time'], 'required'],
			[['item_id', 'status'], 'integer'],
			[['discribe'], 'string', 'max'     => 128],
			[['Date', 'Time'], 'string', 'max' => 8]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'ItemDetail_Id' => 'Item Detail  ID',
			'item_id'       => 'Item ID',
			'discribe'      => 'Discribe',
			'status'        => 'Status',
			'Date'          => 'Date',
			'Time'          => 'Time',
		];
	}
	//所有项目细节
	public function detailAll($id) {
		$sql       = 'SELECT ItemDetail_Id, item_id, discribe, Date, status FROM Itemdetails WHERE item_id = \''.$id.'\' ORDER BY Date DESC, Time DESC';
		$Dbfactory = DbFactory::getinstance();
		$query     = $Dbfactory->doQuery($sql);
		return $Dbfactory->findAll($query);
	}
	//查询项目单个细节
	public function detailDet($id) {
		$Dbfactory = DbFactory::getinstance();
		return $Dbfactory->findAllThings('Itemdetails', 'ItemDetail_Id', $id);
	}
	//插入项目细节
	public function insertDetail($arr) {
		$Dbfactory = DbFactory::getinstance();
		return $Dbfactory->insertIntoDb('Itemdetails', $arr);
	}
	//修改项目内容
	public function ChangeDetail($ItemDetail_Id, $discribe) {
		$Dbfactory             = DbFactory::getinstance();
		$arrUpdate['discribe'] = $discribe;
		return $Dbfactory->updateTheDbRecord('Itemdetails', 'ItemDetail_Id', $ItemDetail_Id, $arrUpdate);
	}
	//改变状态
	public function changeStatus($id, $status) {
		$Dbfactory           = DbFactory::getinstance();
		$arrUpdate['status'] = $status;
		return $Dbfactory->updateTheDbRecord('Itemdetails', 'ItemDetail_Id', $id, $arrUpdate);
	}
}
