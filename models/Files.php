<?php

namespace app\models;
use app\controllers\DbFactory;
use Yii;

/**
 * This is the model class for table "files".
 *
 * @property integer $id
 * @property string $XH_ID
 * @property string $name
 * @property string $location
 * @property string $Time
 */

class Files extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'files';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['id', 'XH_ID', 'name', 'location', 'Time'], 'required'],
			[['id'], 'integer'],
			[['XH_ID'], 'string', 'max'    => 10],
			[['name'], 'string', 'max'     => 32],
			[['location'], 'string', 'max' => 64],
			[['Time'], 'string', 'max'     => 8]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'       => 'ID',
			'XH_ID'    => 'Xh  ID',
			'name'     => 'Name',
			'location' => 'Location',
			'Time'     => 'Time',
		];
	}

	public function fileLocation($id) {
		$Dbfactory = DbFactory::getinstance();
		$sql       = 'SELECT name,location FROM files WHERE ItemDetail_Id = \''.$id.'\'';
		$query     = $Dbfactory->doQuery($sql);
		return $Dbfactory->findAll($query);
	}
}
