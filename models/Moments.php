<?php
namespace app\models;
use app\controllers\DbFactory;
use app\models\UserTb;
use yii\db\Query;
use Yii;
date_default_timezone_set("PRC");
header("Content-Type: application/json;charset=utf-8");
/**
 * This is the model class for table "moments".
 *
 * @property integer $id        动态主键
 * @property string $XH_ID        发送人（外键）
 * @property string $Time        发送时间
 * @property string $Mdate        发送日期
 * @property string $Content    发送内容
 * @property integer $like_Num    点赞数？
 *
 * @property UserTb $xH
 */
class Moments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'moments';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['XH_ID', 'Time', 'Content'], 'required'],
            [['like_Num'], 'integer'],
            [['XH_ID'], 'string', 'max' => 10],
            [['Time'], 'string', 'max' => 20],
            [['Mdate'], 'string', 'max' => 8],
            [['Content'], 'string', 'max' => 32]
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'XH_ID' => 'Xh  ID',
            'Time' => 'Time',
            'Mdate' => 'Mdate',
            'Content' => 'Content',
            'like_Num' => 'Like  Num',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXH()
    {
        return $this->hasOne(UserTb::className(), ['XH_ID' => 'XH_ID']);
    }
    public function adminGetMoment()
    {
        $dbFactory = DbFactory::getinstance();
        $sql = 'SELECT * FROM moments';
        $content = $dbFactory->findBySql($sql);
        $XH = $content[0]['XH_ID'];
        $user = new UserTb();
        $username = $user->getName($XH);
        $content[0]['name'] = $username;
        return $content;
    }
    public function userGetMoment()
    {
        $dbFactory = DbFactory::getinstance();
    }
    //获取当前时间
    public function getDateAndTime()
    {
        $RightNow['date'] = date('y-m-d');
        $RightNow['time'] = date('H:i:s');
        return $RightNow;
    }
    private function processthemessage($message)
    {
        $Dbfactory = DbFactory::getinstance();
        $message = $Dbfactory->dbSqlProtected($message);
        $message = \yii\helpers\HtmlPurifier::process($message);
        return $message;
    }
    //插入数据，并在插入之前检验数据是否会对数据库有害
    public function insertMomentData($arr)
    {
        $Dbfactory = DbFactory::getinstance();
        $this->processthemessage($arr['Content']);
        $Dbfactory->insertIntoDb('moments', $arr);
    }
    public function getPageMomentWithOrder($page, $number)
    {
        if ($this->decideGetMomentContinue($page, $number)) {
            $front = ($page - 1) * $number;
            $sql=" SELECT moments.id,moments.XH_ID,moments.Content,moments.Mdate FROM moments RIGHT JOIN moment_top ON moments.id=moment_top.moment_id";
            $Dbfactory = DbFactory::getinstance();
            $Moments1 = $Dbfactory->findBySql($sql);
            $counttop=MomentTop::find()->count();
            $num=$number-$counttop;
            $idcount="";
            foreach($Moments1 as $key=>$value){
                $idcount.=$value['id'].',';
            }
            if ($page == 1) {
                $idcount=rtrim($idcount, ",");
                $sql2="SELECT id,XH_ID,Content,Mdate FROM moments WHERE id NOT IN ($idcount) ORDER BY Mdate DESC ,TIME DESC LIMIT $num";
                $Moments2=Yii::$app->db->createCommand($sql2)->queryAll();
                $Moments=array_merge($Moments1,$Moments2);
            }else{
                $num=$front-$counttop;
                $idcount=rtrim($idcount, ",");
                $sql2="SELECT id,XH_ID,Content,Mdate FROM moments WHERE id NOT IN ($idcount) ORDER BY Mdate DESC ,TIME DESC LIMIT $num,$number";
                $Moments=Yii::$app->db->createCommand($sql2)->queryAll();
//                $Moments=array_merge($Moments1,$Moments2);

            }
            $user = new UserTb();
            foreach ($Moments as $key => $value) {
                $XH = $value['XH_ID'];
                $username = $user->getName($XH);
                $Moments[$key]['username'] = $username;
            }
            $istop = MomentTop::find()->all();
            foreach ($Moments as $key => $value) {
                foreach ($istop as $key1 => $value1) {
                    if ($value1['moment_id'] == $value['id']) {
                        $Moments[$key]['status'] = $value1['status'];
                    }
                }
            }

            return $Moments;
        } else {
            return false;
        }
    }
    /**
     * 分页（搜索）
     * @param $input
     * @param $page
     * @param $number
     * @return array|string
     */
    public function getPageMomentWithOrder_2($input,$page, $number){
        $front = ($page - 1) * $number;
        if($page==1){
//            $sql="SELECT tabbledesc.id,tabbledesc.XH_ID,Content,Mdate FROM (SELECT * FROM moments ORDER BY Mdate DESC,Time desc)AS tabbledesc LEFT JOIN moment_top ON tabbledesc.id=moment_top.moment_id  WHERE tabbledesc.Content like '%$input%' OR tabbledesc.XH_ID like '%$input%' LIMIT $number";
            $sql="SELECT id,XH_ID,Content,Mdate from moments WHERE Content like '%$input%' OR XH_ID like '%$input%' ORDER BY Mdate DESC ,TIME DESC LIMIT $number";
        }else{
            $sql="SELECT id,XH_ID,Content,Mdate from moments WHERE Content like '%$input%' OR XH_ID like '%$input%' ORDER BY Mdate DESC ,TIME DESC LIMIT $front,$number";

//            $count=Yii::$app->db->createCommand($sql1)->queryAll();
//            $idcount="";
//            foreach($count as $key=>$value){
//                $idcount.=$value['id'].',';
//            }
//            $idcount=rtrim($idcount, ",");
////                print_r($idcount);
////                exit();
//            $sql="SELECT id,XH_ID,Content,Mdate FROM Moments WHERE id NOT IN($idcount) AND  (Content like '%$input%' OR XH_ID LIKE '%$input%') ORDER BY Mdate DESC,Time DESC LIMIT $number;";
        }
        $result=yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($result)){
            $istop = MomentTop::find()->all();
            foreach($result as $key=>$value){
                foreach($istop as $key1=>$value1){
                    if($value1['moment_id']==$value['id']){
                        $result[$key]['status']=$value1['status'];
                    }
                }
//			$value['username']=UserTb::findOne($value['XH_ID'])->Name;
                $result[$key]['username']=UserTb::findOne($value['XH_ID'])->Name;
            }
            $allPage=$this->getAllPage_s(6,$input);
            $result = '{"success":true,"moments":'.json_encode($result, JSON_UNESCAPED_UNICODE).',"allPage_s":"'.$allPage.'"}';
            return $result;
        }else{
            $result='{"success":false,"msg":"没有找到相关动态","allPage_s":"1"}';
            return$result;
        }
    }
    public function deleteOneMoment($id)
    {
        $Dbfactory = DbFactory::getinstance();
        $Dbfactory->deleteOneRecord('moments', 'id', $id);
    }
    private function decideGetMomentContinue($page, $number)
    {
        $frontCount = ($page - 1) * $number;
        $Count = $this->getMomentCount();
        if ($frontCount >= $Count) {
            return false;
        } else {
            return true;
        }
    }
    public function getMomentCount()
    {
        $Dbfactory = DbFactory::getinstance();
        return $Dbfactory->tableCount('Moments');
    }
    public function getAllPage($countEveryPage)
    {
        $count = $this->getMomentCount();
        return ceil($count / $countEveryPage);
    }
    public function getAllPage_s($countEveryPage,$input)
    {
        $count=(new Query())
//			->select('COUNT(*)')
            ->from('moments')
            ->where(['or',['like','XH_ID',$input],['like','Content',$input]])
            ->orderBy('Mdate DESC','Time DESC')
            ->count();
        return ceil($count / $countEveryPage);
    }
}