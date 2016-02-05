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

	public function detailAll($id) {
		$sql       = 'SELECT ItemDetail_Id, item_id, discribe, Date, status FROM Itemdetails WHERE item_id = \''.$id.'\' ORDER BY Date DESC, Time DESC';
		$Dbfactory = DbFactory::getinstance();
		$query     = $Dbfactory->doQuery($sql);
		return $Dbfactory->findAll($query);
	}

	public function detailDet($id) {
		$Dbfactory = DbFactory::getinstance();
		return $Dbfactory->findAllThings('Itemdetails', 'ItemDetail_Id', $id);
	}
}
