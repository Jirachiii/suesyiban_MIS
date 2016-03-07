<?php

namespace app\models;
use app\controllers\DbFactory;
use Yii;
use yii\db\Query;

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
	//取出项目创始人
	public function getClassmark($id) {
		$Dbfactory = DbFactory::getinstance();
		$id        = $Dbfactory->dbSqlProtected($id);
		$XH_ID     = $Dbfactory->findOnlyOne('XH_ID', 'items', 'Item_Id', $id);
		return $XH_ID;
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
	//管理员获取自己创建的所有项目
	public function searchAllItems($status) {
		$XH_ID = \Yii::$app->user->identity->XH_ID;
		$sql   = 'SELECT Item_Id,Item_Name FROM items WHERE Status = '.$status.' AND XH_ID = \''.$XH_ID.'\'';
		//		$Dbfactory = DbFactory::getinstance();
		//		$query     = $Dbfactory->doQuery($sql);
		//		return $Dbfactory->findAll($query);
		$result = Yii::$app->db->createCommand($sql)->queryAll();
		return $result;
	}
	//获取部员列表
	public function getitemuser($itemid){
		if($content=(new Query())->select(['Name','XH_ID'])->from('user_tb')->all()){
			//获取该项目已有学生
			$userexit=(new Query())->select(['XH_ID'])->from('itempersons')->where(['Item_Id' => $itemid])->all();
			$userarr=array();
			foreach($userexit as $key=>$value){
				$userarr[]=$value['XH_ID'];
			}
			foreach($content as $key=>$value ){
				if(in_array($value['XH_ID'],$userarr)){
					$content[$key]['isin']="已添加";
				}else{
					$content[$key]['isin']="";
				}
			}
			$content = '{"success":true,"name":'.json_encode($content, JSON_UNESCAPED_UNICODE).'}';
			return $content;
		}else{
			$content = '{"success":false,"name":"用户未找到"}';
			return $content;
		}

	}
	//获取别的管理员添加的项目
	public function searchOtherItems($status) {
		$XH_ID  = \Yii::$app->user->identity->XH_ID;
		$sql    = 'SELECT items.Item_Id,items.Item_Name FROM items WHERE Status = '.$status.' AND Item_Id IN (SELECT Item_Id FROM itempersons WHERE XH_ID =\''.$XH_ID.'\')';
		$result = Yii::$app->db->createCommand($sql)->queryAll();
		return $result;
	}
	//用户获取所有项目细节
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
			$sql       = 'SELECT XH_ID,Item_Name,Item_Intro,Item_Id, Date FROM items WHERE Status = '.$status.' ORDER BY Date DESC LIMIT '.$front.','.$number;
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
	//搜索项目
	public function AdminSearchItems($content, $countpage,$page, $number) {
		if ($page<=$countpage) {
			$index     = ($page-1)*$number;
			$items = (new Query())
//				->select('Item_Id','XH_ID','Item_Name','Item_Intro','Date','status')
				->from('items')
				->where( ['like', 'Item_Name', $content])
				->orderBy('Date DESC')
				->limit($number)
				->offset($index)
				->all();

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
	//获取总的项目数
	public  function  getItempages($number){
		$all=self::find()->count();
		$allpages=ceil($all/$number);
		return $allpages;
	}
	//获取总的项目数(select)
	public  function  getItempages_sel($status,$number){
		$all=self::find()->where(['Status'=>$status])->count();
		$allpages=ceil($all/$number);
		return $allpages;
	}
	//获取总的项目数(search)
	public  function  getItempages_s($content,$number){
		$all=(new Query())
			->from('items')
			->where(['like','Item_Name', $content])
			->count();
		$allpages=ceil($all/$number);
		return $allpages;
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
