<?php

namespace app\models;
use app\controllers\DbFactory;
use Yii;
use yii\db\Query;

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
	//搜索这个项目的所有用户
	public function searchItemJoin($XH_ID) {
		$Dbfactory = DbFactory::getinstance();
		$sql       = 'SELECT * FROM itempersons WHERE XH_ID = \''.$XH_ID.'\'';
		$result    = $Dbfactory->findBySql($sql);
	}
	//插入用户
	public function insertperson($arr) {
		$id = Yii::$app->user->identity->XH_ID;
		$Dbfactory = DbFactory::getinstance();
		$userexit=(new Query())->select(['XH_ID'])->from('itempersons')->where(['and',['Item_Id' => $arr['Item_Id']],['XH_ID' => $arr['XH_ID']]])->all();
		if($userexit){
			return false;
		}elseif ($id==$arr['XH_ID']) {
			return false;
		}else{
			return $Dbfactory->insertIntoDb('itempersons', $arr);
		}

	}
}
