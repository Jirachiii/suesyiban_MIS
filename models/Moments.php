<?php

namespace app\models;
use app\controllers\DbFactory;
use app\models\UserTb;
use Yii;
header("Content-Type: application/json;charset=utf-8");
/**
 * This is the model class for table "moments".
 *
 * @property integer $id
 * @property string $XH_ID
 * @property string $Time
 * @property string $Content
 * @property integer $like_Num
 *
 * @property UserTb $xH
 */

class Moments extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'moments';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['XH_ID', 'Time', 'Content'], 'required'],
			[['like_Num'], 'integer'],
			[['XH_ID'], 'string', 'max'   => 10],
			[['Time'], 'string', 'max'    => 20],
			[['Content'], 'string', 'max' => 32]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'       => 'ID',
			'XH_ID'    => 'Xh  ID',
			'Time'     => 'Time',
			'Content'  => 'Content',
			'like_Num' => 'Like  Num',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getXH() {
		return $this->hasOne(UserTb::className(), ['XH_ID' => 'XH_ID']);
	}

	public function adminGetMoment() {
		$dbFactory          = DbFactory::getinstance();
		$sql                = 'SELECT * FROM moments';
		$content            = $dbFactory->findBySql($sql);
		$XH                 = $content[0]['XH_ID'];
		$user               = new UserTb();
		$username           = $user->getName($XH);
		$content[0]['name'] = $username;
		return $content;
	}
}
