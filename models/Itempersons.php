<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "itempersons".
 *
 * @property integer $ItemPerson_Id
 * @property integer $Item_Id
 * @property string $XH_ID
 */

class Itempersons extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'itempersons';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['Item_Id', 'XH_ID'], 'required'],
			[['Item_Id'], 'integer'],
			[['XH_ID'], 'string', 'max' => 10]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'ItemPerson_Id' => 'Item Person  ID',
			'Item_Id'       => 'Item  ID',
			'XH_ID'         => 'Xh  ID',
		];
	}

	public function searchItemJoin($XH_ID) {
		$Dbfactory = DbFactory::getinstance();
		$sql       = 'SELECT * FROM itempersons WHERE XH_ID = \''.$XH_ID.'\'';
		$result    = $Dbfactory->findBySql($sql);
	}
}
