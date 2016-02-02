<?php

namespace app\models;
use app\controllers\DbFactory;
use Yii;
date_default_timezone_set("PRC");

/**
 * This is the model class for table "ownTodos".
 *
 * @property string $XH_ID
 * @property string $CreateDate
 * @property integer $number
 * @property string $content
 * @property integer $urgentLev
 */

class OwnTodos extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'ownTodos';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['XH_ID', 'CreateDate', 'number', 'content', 'urgentLev'], 'required'],
			[['number', 'urgentLev'], 'integer'],
			[['XH_ID'], 'string', 'max'      => 10],
			[['CreateDate'], 'string', 'max' => 8],
			[['content'], 'string', 'max'    => 16]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'XH_ID'      => 'Xh  ID',
			'CreateDate' => 'Create Date',
			'number'     => 'Number',
			'content'    => 'Content',
			'urgentLev'  => 'Urgent Lev',
		];
	}

	//取出数据
	public function findTodayMission() {
		$rightNowUserId = Yii::$app->user->identity->XH_ID;
		$today          = date('Y-m-d');
		$Dbfactory      = DbFactory::getinstance();
		$sql            = 'SELECT CreateDate,Num,content,urgentLev FROM ownTodos WHERE XH_ID = \''.$rightNowUserId.'\' and CreateDate = \''.$today.'\'';
		$query          = $Dbfactory->doQuery($sql);
		return $Dbfactory->findAll($query);
	}
	//数据个数
	public function findTodayMissionCount() {
		$rightNowUserId = Yii::$app->user->identity->XH_ID;
		$today          = date('Y-m-d');
		$Dbfactory      = DbFactory::getinstance();
		$sql            = 'SELECT COUNT(*) FROM ownTodos WHERE XH_ID = \''.$rightNowUserId.'\' and CreateDate = \''.$today.'\'';
		$count          = $Dbfactory->doQuery($sql);
		$count          = $Dbfactory->findAll($count);
		return $count['0']['COUNT(*)'];
	}
	//插入数据，并在插入之前检验数据是否会对数据库有害
	public function insertTodoData($arr) {
		$Dbfactory = DbFactory::getinstance();
		$this->processthemessage($arr['Content']);
		return $Dbfactory->insertIntoDb('OwnTodos', $arr);
	}
	//数据过滤
	private function processthemessage($message) {
		$Dbfactory = DbFactory::getinstance();
		$message   = $Dbfactory->dbSqlProtected($message);
		$message   = \yii\helpers\HtmlPurifier::process($message);
		return $message;
	}
	//修改状态
	public function changeStatus($Num, $urgentLev) {
		$rightNowUserId = Yii::$app->user->identity->XH_ID;
		$CreateDate     = date('Y-m-d');
		$sql            = 'update OwnTodos set `urgentLev`=\''.$urgentLev.'\' WHERE XH_ID = \''.$rightNowUserId.'\' and CreateDate = \''.$CreateDate.'\' and Num = '.$Num;
		$Dbfactory      = DbFactory::getinstance();
		return $Dbfactory->doQuery($sql);
	}
	//获取今日已完成内容
	public function findTodayDoneMission() {
		$rightNowUserId = Yii::$app->user->identity->XH_ID;
		$CreateDate     = date('Y-m-d');
		$sql            = 'SELECT CreateDate,Num,content,urgentLev FROM OwnTodos WHERE XH_ID = \''.$rightNowUserId.'\' and CreateDate = \''.$CreateDate.'\' and urgentLev = 4';
		$Dbfactory      = DbFactory::getinstance();
		$query          = $Dbfactory->doQuery($sql);
		return $Dbfactory->findAll($query);
	}
}
