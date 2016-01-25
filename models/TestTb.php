<?php

namespace app\models;
use app\controllers\DbFactory;
use Yii;

/**
 * This is the model class for table "test_tb".
 *
 * @property string $id
 * @property string $content
 * @property string $status
 */

class TestTb extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'test_tb';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['id', 'content', 'status'], 'required'],
			[['id', 'content', 'status'], 'string', 'max' => 8]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'      => 'ID',
			'content' => 'Content',
			'status'  => 'Status',
		];
	}

	public function getTestData() {
		return $this->getPageMomentWithOrder(1, 6);
	}

	public function getPageMomentWithOrder($page, $number) {
		$rightNowUserId   = Yii::$app->user->identity->XH_ID;
		$rightNowUserName = Yii::$app->user->identity->Name;
		$front            = ($page-1)*$number;
		$sqlA             = 'SELECT id,Content,status FROM test_tb WHERE status = 1 LIMIT '.$front.','.$number;
		$sqlB             = 'SELECT id,Content,status FROM test_tb WHERE status = 2 LIMIT '.$front.','.$number;
		$Dbfactory        = DbFactory::getinstance();
		$contentOne       = $Dbfactory->findBySql($sqlA);
		$contentTwo       = $Dbfactory->findBySql($sqlB);
		$contentOne       = '"getStarted":'.json_encode($contentOne, JSON_UNESCAPED_UNICODE);
		$contentTwo       = '"Completed":'.json_encode($contentTwo, JSON_UNESCAPED_UNICODE);
		$contents         = '{'.$contentOne.','.$contentTwo.',"userIdNow":"'.$rightNowUserId.'","userName":"'.$rightNowUserName.'"}';
		return $contents;
	}

	public function changeStatus($id, $status) {
		$Dbfactory           = DbFactory::getinstance();
		$arrUpdate['status'] = $status;
		return $Dbfactory->updateTheDbRecord('test_tb', 'id', $id, $arrUpdate);
	}
}
