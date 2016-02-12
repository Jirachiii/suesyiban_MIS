<?php

namespace app\models;
use app\controllers\DbFactory;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "zhibantable".
 *
 * @property integer $id
 * @property string $stname
 * @property string $stid
 * @property string $date_zhoushu
 * @property string $date_weekday
 * @property string $date_turn
 * @property string $conflict_class
 * @property string $year_xq
 */
class Zhibantable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zhibantable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stname', 'stid', 'date_zhoushu', 'date_weekday', 'date_turn', 'year_xq'], 'required'],
            [['stname', 'stid', 'date_zhoushu', 'date_weekday', 'date_turn', 'year_xq'], 'string', 'max' => 20],
            [['conflict_class'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stname' => 'Stname',
            'stid' => 'Stid',
            'date_zhoushu' => 'Date Zhoushu',
            'date_weekday' => 'Date Weekday',
            'date_turn' => 'Date Turn',
            'conflict_class' => 'Conflict Class',
            'year_xq' => 'Year Xq',
        ];
    }

    public function getemptyst($whichweek,$weekday,$zhibantime){
        switch($zhibantime){
            case "1":
                $sql="SELECT Name,XH_ID FROM user_tb where XH_ID NOT IN(select xh FROM kbmember where xqj='$weekday' AND (qsz<='$whichweek' AND jsz>='$whichweek') AND (ksxj<=5 AND jsxj>=6))";
                $content=Yii::$app->db->createCommand($sql)->queryAll();
                break;
            case "2":
                $sql="SELECT Name,XH_ID FROM user_tb where XH_ID NOT IN(select xh FROM kbmember where xqj='$weekday' AND (qsz<='$whichweek' AND jsz>='$whichweek') AND ((ksxj<=5 AND jsxj>=6) OR (ksxj<=7 AND jsxj>=8)))";
                $content=Yii::$app->db->createCommand($sql)->queryAll();
                break;
            case "3":
                $sql="SELECT Name,XH_ID FROM user_tb where XH_ID NOT IN(select xh FROM kbmember where xqj='$weekday' AND (qsz<='$whichweek' AND jsz>='$whichweek') AND ((ksxj<=9 AND jsxj>=10) OR (ksxj<=11 AND jsxj>=12)))";
                $content=Yii::$app->db->createCommand($sql)->queryAll();
                break;
        }
        return $content;
    }

    /**
     * 删除安排
     * @param $aim
     * @return string
     * @throws \Exception
     */
    public function delanpai($aim){
        $aimanpi=Zhibantable::findOne($aim);
        if($aimanpi->delete()){
            $result='{"success":true}';
        }else{
            $result='{"success":false,"msg":"删除出现错误"}';
        }
        return $result;

    }

    /**
     * 获取学期学年
     * @return string
     */
    public function xuenianxueqi(){
        $year=date(date('Y'));
        if(date('m')>=3&&date('m')<9){
            $xueqi=2;
            $xuenian=($year-1).'-'.$year;
        }elseif(date('m')>=9){
            $xueqi=1;
            $xuenian=$year.'-'.($year+1);
        }elseif(date('m')<=2){
            $xueqi=1;
            $xuenian=($year-1).'-'.$year;
        }
        $year_xq=$xuenian.'-'.$xueqi;
        return $year_xq;

    }
    public function findanpaidata($whichweek,$weekday,$year_xq){
        $sql="select id,stname,stid,date_turn,conflict_class from zhibantable WHERE date_zhoushu='$whichweek' AND date_weekday='$weekday' AND year_xq='$year_xq'";
        $content=Yii::$app->db->createCommand($sql)->queryAll();
        return $content;
    }

    public function genamemanual(){
        if($content=(new Query())->select(['Name','XH_ID'])->from('user_tb')->all()){
            $content = '{"success":true,"name":'.json_encode($content, JSON_UNESCAPED_UNICODE).'}';
            return $content;
        }else{
            $content = '{"success":false,"name":"用户未找到"}';
            return $content;
        }

    }
    public function insertanpaimanual ($stname,$xh,$whichweek,$weekday,$zhibantime){
        $year_xq=$this->xuenianxueqi();
        //判断该学生这班是否有空
        $content=self::getemptyst($whichweek,$weekday,$zhibantime);
        $num=0;
        foreach($content as $key=>$value){
            if(in_array($xh,$value)){
                $num=1;
            }else{
                continue;
            }
        }
        //如果课程不冲突
        if($num==1){
            $newzhiban=new Zhibantable();
            $newzhiban->stname=$stname;
            $newzhiban->stid=$xh;
            $newzhiban->date_zhoushu=$whichweek;
            $newzhiban->date_weekday=$weekday;
            $newzhiban->date_turn=$zhibantime;
            $newzhiban->year_xq=$year_xq;
            //判断该学生是否已经这一天这一班有安排了
            $sql1="select * from zhibantable WHERE stname='$stname' AND stid='$xh' AND date_zhoushu='$whichweek' AND date_weekday='$weekday' AND date_turn='$zhibantime'AND year_xq='$year_xq'";
            //判断这一天这一班是否满了
            $sql2="select COUNT(*) from zhibantable WHERE   date_zhoushu='$whichweek' AND date_weekday='$weekday' AND date_turn='$zhibantime'AND year_xq='$year_xq'";
            if(Yii::$app->db->createCommand($sql1)->queryOne()){
                $result='{"success":false,"msg":"该学生已经安排过此日期的排班了"}';
            }else if(Yii::$app->db->createCommand($sql2)->queryScalar()>=2){
                $result='{"success":false,"msg":"这一班已经安排满了"}';
            }else{
                $newzhiban->save(false);
                $result='{"success":true,"msg":"安排值班成功"}';
            }
            return $result;
        }else{
            $newzhiban=new Zhibantable();
            $newzhiban->stname=$stname;
            $newzhiban->stid=$xh;
            $newzhiban->date_zhoushu=$whichweek;
            $newzhiban->date_weekday=$weekday;
            $newzhiban->date_turn=$zhibantime;
            $newzhiban->year_xq=$year_xq;
            $newzhiban->conflict_class="有课程冲突";
            //判断该学生是否已经这一天这一班有安排了
            $sql1="select * from zhibantable WHERE stname='$stname' AND stid='$xh' AND date_zhoushu='$whichweek' AND date_weekday='$weekday' AND date_turn='$zhibantime'AND year_xq='$year_xq'";
            //判断这一天这一班是否满了
            $sql2="select COUNT(*) from zhibantable WHERE   date_zhoushu='$whichweek' AND date_weekday='$weekday' AND date_turn='$zhibantime'AND year_xq='$year_xq'";
            if(Yii::$app->db->createCommand($sql1)->queryOne()){
                $result='{"success":false,"msg":"该学生已经安排过此日期的排班了"}';
            }else if(Yii::$app->db->createCommand($sql2)->queryScalar()>=2){
                $result='{"success":false,"msg":"这一班已经安排满了"}';
            }else{
                $newzhiban->save(false);
                $result='{"success":true,"msg":"安排值班成功(有课程冲突)"}';
            }
            return $result;
        }
    }
}
