<?php

namespace app\models;
use app\controllers\DbFactory;
use app\models\UserTb;
use Yii;
date_default_timezone_set("PRC");
header("Content-Type: application/json;charset=utf-8");
/**
 * This is the model class for table "moments".
 *
 * @property integer $id
 * @property string $XH_ID
 * @property string $Time
 * @property string $Mdate
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
			[['Mdate'], 'string', 'max'   => 8],
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
			'Mdate'    => 'Mdate',
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

	public function userGetMoment() {
		$dbFactory = DbFactory::getinstance();

	}

	//获取当前时间
	public function getDateAndTime() {
		$RightNow['date'] = date('y-m-d');
		$RightNow['time'] = date('h:i:s');
		return $RightNow;
	}

	private function processthemessage($message) {
		$Dbfactory = DbFactory::getinstance();
		$message   = $Dbfactory->dbSqlProtected($message);
		$message   = \yii\helpers\HtmlPurifier::process($message);
		return $message;
	}
	//插入数据，并在插入之前检验数据是否会对数据库有害
	public function insertMomentData($arr) {
		$Dbfactory = DbFactory::getinstance();
		$this->processthemessage($arr['Content']);
		$Dbfactory->insertIntoDb('moments', $arr);
	}

	public function getPageMomentWithOrder($page, $number) {
		if ($this->decideGetMomentContinue($page, $number)) {
			$front     = ($page-1)*$number;
			$sql       = 'SELECT id,XH_ID,Content,like_Num FROM Moments ORDER BY Mdate DESC,Time DESC LIMIT '.$front.','.$number;
			$Dbfactory = DbFactory::getinstance();
			$Moments   = $Dbfactory->findBySql($sql);
			$user      = new UserTb();
			foreach ($Moments as $key => $value) {
				$XH                        = $value['XH_ID'];
				$username                  = $user->getName($XH);
				$Moments[$key]['username'] = $username;
			}
			return $Moments;
		} else {
			$Moments[0] = '超出此长度';
			return $Moments;
		}
	}

	public function deleteOneMoment($id) {
		$Dbfactory = DbFactory::getinstance();
		$Dbfactory->deleteOneRecord($this->tableName, 'id', $id);
	}

	private function decideGetMomentContinue($page, $number) {
		$frontCount = ($page-1)*$number;
		$Count      = $this->getMomentCount();
		if ($frontCount >= $Count) {
			return false;
		} else {
			return true;
		}
	}

	public function getMomentCount() {
		$Dbfactory = DbFactory::getinstance();
		return $Dbfactory->tableCount('Moments');
	}
}
