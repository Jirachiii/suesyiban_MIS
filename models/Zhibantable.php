<?php

namespace app\models;
use app\models\UserTb;
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
    //自动排班用
    private $sqlinsert="INSERT zhibantable(stname,stid,date_zhoushu,date_weekday,date_turn,year_xq)VALUES";

    /**
     * 更新成员们的课表,从kb存到memberkb
     */
    public function updatememberkb(){
        //判断学期 学年
        $year=date(date('Y'));
        if(date('m')>=3&&date('m')<9){
        // 为了测试将第一学期的结束月份设为6月份，正式使用时使用上面的
        // if(date('m')>=6&&date('m')<9){
            $xueqi=2;
            $xuenian=($year-1).'-'.$year;
        }elseif(date('m')>=9){
            $xueqi=1;
            $xuenian=$year.'-'.($year+1);
        }elseif(date('m')<=2){
        // 为了测试将第一学期的结束月份设为6月份，正式使用时使用上面的
        // }elseif(date('m')<=6){
            $xueqi=1;
            $xuenian=($year-1).'-'.$year;
        }
        //获取用户表
        $member   = (new Query())
            ->select(['XH_ID', 'Name','status'])
            ->from('user_tb')
            ->orderBy('status DESC')
            ->all();
        if(!empty($member)){
            //用户学号数组
            $memxh=array();
            foreach($member as $key=>$value){
                $memxh[]=$value['XH_ID'];
            }
            $kb=(new Query())
                ->from('kb')
                ->where(['xh'=>$memxh,'xn'=>$xuenian,'xq'=>$xueqi])
                ->all();

            if(!empty($kb)){
                $emptysql="truncate table kbmember";
                $query=Yii::$app->db->createCommand($emptysql)->execute();
                //    插入到表kbmember
                foreach($kb as $key=>$value){
                    $kbmember=new Kbmember();
                    foreach($value as $key2=>$value2){
                        switch($value2){
                            case -32:
                                $value2=20;
                                break;
                            case -33:
                                $value2=19;
                                break;
                            case -34:
                                $value2=18;
                                break;
                            case -35:
                                $value2=17;
                                break;
                            case -36:
                                $value2=16;
                                break;
                        }
                        $kbmember->$key2=$value2;
                    }
                    if($kbmember->save()){
                    }
                }
            }
        }else{
            return false;
        }
        return true;
    }
    /**
     * 搜索有空值班的学生(右边的视图)
     */
    public function showselectanpai($content,$whichweek,$weekday,$zhibantime,$page,$numbers){
        //学期 学年
        $year_xq=self::xuenianxueqi();
        //用于显示已被安排的学生
        $sum=0;
        foreach($content as $key=>$value){
            $stname=$value['Name'];
            $xh=$value['XH_ID'];
            $sql2="select * from zhibantable WHERE stname='$stname' AND stid='$xh' AND date_zhoushu='$whichweek' AND date_weekday='$weekday' AND date_turn='$zhibantime'AND year_xq='$year_xq'";
            if($aim=Yii::$app->db->createCommand($sql2)->queryOne()){
                $content[$key]['status']=1;
                $content[$key]['anpai_id']=$aim['id'];
                $sum+=1;
            }else{
                $content[$key]['status']=0;
            }
        }
        $allPage=ceil(count($content)/6);
        foreach($content as $index=>$value){
            $status[$index]=$value['status'];
        }
        $content=self::getwithorder($status,$content,$page,$numbers);
        $content = '{"success":true,"allPage":'.$allPage.',"kongkebiao":'.json_encode($content, JSON_UNESCAPED_UNICODE).'}';
        return $content;
    }

    /**
     * 安排值班
     * @return string
     */
    public function managest($stname,$xh,$whichweek,$weekday,$zhibantime,$year_xq){
        //判断学期 学年

        $newzhiban=new Zhibantable();
        $newzhiban->stname=$stname;
        $newzhiban->stid=$xh;
        $newzhiban->date_zhoushu=$whichweek;
        $newzhiban->date_weekday=$weekday;
        $newzhiban->date_turn=$zhibantime;
        $newzhiban->year_xq=$year_xq;

        //防止用户筛选好后，改变select的不规范插入数据，因为星期，周数是靠select获取的
        $session = Yii::$app->session;
        $session->open();
        if(!($session['whichweek']==$whichweek&&$session['weekday']==$weekday&&$session['zhibantime']==$zhibantime)){
            return'{"success":false,"msg":"请按规范操作"}';
        }
        $xhxm=self::getemptyst($whichweek,$weekday,$zhibantime);
        $num=0;
        foreach($xhxm as $key=>$value){
            if(in_array($xh,$value)){
                $num=1;
            }else{
                continue;
            }
        }
        if($num!=1){
            return'{"success":false,"msg":"请按规范操作"}';
        }

        //判断该学生是否已经这一天这一班有安排了
        $sql1="select * from zhibantable WHERE stname='$stname' AND stid='$xh' AND date_zhoushu='$whichweek' AND date_weekday='$weekday' AND date_turn='$zhibantime'AND year_xq='$year_xq'";
        //判断这一天这一班是否满了
        $sql2="select COUNT(*) from zhibantable WHERE   date_zhoushu='$whichweek' AND date_weekday='$weekday' AND date_turn='$zhibantime'AND year_xq='$year_xq'";
        if(Yii::$app->db->createCommand($sql1)->queryOne()){
            $result='{"success":true,"msg":"该学生已经安排过此日期的排班了"}';
        }else if(Yii::$app->db->createCommand($sql2)->queryScalar()>=2){
            $result='{"success":true,"msg":"这一班已经安排满了！(可在左边栏确认)"}';
        }else{
            $newzhiban->save(false);
            $result='{"success":true,"msg":"安排值班成功"}';
        }
        return $result;
    }
    /**
     * 获取有空的学生
     * @param $whichweek
     * @param $weekday
     * @param $zhibantime
     * @return array
     */
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
    //自动排班
    public function insertauto(){
        //查找左右用户和所有的用户已经安排的次数
        $alluser=(new Query)->select(["XH_ID","Name"])->from("user_tb")->all();
        $already_manage=(new Query)->select(["stid","COUNT(stid) AS counts"])->from("zhibantable")->groupBy('stid')->all();
        if(empty($already_manage)){
            foreach ($alluser as $key2 => $value2) {
                    $userarr[$value2['XH_ID']]=0;
                }
        }else{
            foreach ($already_manage as $key => $value) {
                foreach ($alluser as $key2 => $value2) {
                    //统计个数
                    $userarr[$value2['XH_ID']]=0;
                    if($value2['XH_ID']==$value['stid']){
                        $userarr[$value2['XH_ID']]=$value['counts'];
                    }
                }
            }
        }
        $xh_name=array();//'031513218'=>'jin'
        foreach ($alluser as $key => $value) {
            $xh_name[$value['XH_ID']]=$value['Name'];
        }
        // print_r($xh_name);die;
        $year_xq=self::xuenianxueqi();
        $maxaver=22;
        for ($x=1; $x <=22 ; $x++) { 
             for ($y=1; $y <=7 ; $y++) { 
                 for ($z=1; $z <=3; $z++) { 
                    //获取这周这天这个值班时间有空的学生(数组)
                    $idname[$x][$y][$z]=self::getemptyst($x,$y,$z);
                    // 随机获取这周这天这个班的两个学生（数组序号）
                    $sqlThisTurn="select stid from zhibantable WHERE   date_zhoushu='$x' AND date_weekday='$y' AND date_turn='$z'AND year_xq='$year_xq'";
                    $stAlready=Yii::$app->db->createCommand($sqlThisTurn)->queryAll();
                    $stAlready=array_column($stAlready,'stid');
                    $backup=$idname[$x][$y][$z];
                    //次数达到或者该同学这班已被安排一定数量的排除掉
                    foreach($idname[$x][$y][$z] as $key2=>$value2){
                        if( $userarr[$value2['XH_ID']]>$maxaver||self::isInThisTurn($idname[$x][$y][$z][$key2]['XH_ID'],$stAlready)){
                            unset($idname[$x][$y][$z][$key2]);
                        }
                    }
                    //人数不够就撤销上面的操作
                    if(count($idname[$x][$y][$z])<2){
                        $idname[$x][$y][$z]=$backup;
                    }
                    // print_r($idname[$x][$y][$z]);die;
                    shuffle($idname[$x][$y][$z]);
                    //这周已安排人数
                    $sqlThisTurn="select COUNT(*) from zhibantable WHERE   date_zhoushu='$x' AND date_weekday='$y' AND date_turn='$z'AND year_xq='$year_xq'";
                    $thisturn=Yii::$app->db->createCommand($sqlThisTurn)->queryScalar();
                    if($thisturn==1){
                        $st1=self::chooseSt($idname[$x][$y][$z],1,$userarr);
                        $id1=$st1[0];
                        //记录次数
                        $userarr[$id1]+=1;
                         //插入   
                        $name1=$xh_name[$id1];
                        $this->sqlinsert.="('".$name1."','".$id1."','".$x."','".$y."','".$z."','".$year_xq."'),";
                    }elseif($thisturn>=2){
                        continue;
                    }else{
                        $st=self::chooseSt($idname[$x][$y][$z],2,$userarr);
                        $id1=$st[0];
                        $id2=$st[1];
                        //记录次数
                        $userarr[$id1]+=1;
                        $userarr[$id2]+=1;
                        $name1=$xh_name[$id1];
                        $name2=$xh_name[$id2];

                         //插入                      
                        $this->sqlinsert.="('".$name1."','".$id1."','".$x."','".$y."','".$z."','".$year_xq."'),";
                        $this->sqlinsert.="('".$name2."','".$id2."','".$x."','".$y."','".$z."','".$year_xq."'),";
                     }                
                 }
             }
        }
        //22个星期x7天x6人=924
        $this->sqlinsert=rtrim($this->sqlinsert,',');
        $this->sqlinsert.=';';
        if(substr($this->sqlinsert, -7,6)=='VALUES'){
            return '{"success":true,"msg":"更新完成"}';
        }else{
            $content=Yii::$app->db->createCommand($this->sqlinsert)->execute();
        }
        $countall=self::find()->count();
        if($countall==924){
           return '{"success":true,"msg":"更新完成"}';
        }else{
            return '{"success":true,"msg":"更新不完全"}';
        }
    }
    /**
     * 判断该学生这班是否已经安排过了
     * @param  [type]  $value   [学生学号]
     * @param  [type]  $already [这班的学号数组]
     * @return boolean          [description]
     */
    public function isInThisTurn($value,$already){
        return in_array($value,$already)? true: false;
    }
    /**
     * 权重算法权重算法权重算法
     * @param  [type] $users   [description]
     * @param  [type] $num     [description]
     * @param  [type] $userarr [description]
     * @return [type]          [description]
     */
    public function chooseSt($users,$num,$userarr){
        $stChoosed=array();
        $userarr_this=array();// [031413217] => 0
        $userlist=array();// [0] => 101214230 这班的学号
        foreach ($users as $key => $value) {
            $userlist[]=$value['XH_ID'];
        }
        foreach ($userarr as $key => $value) {
            if(in_array($key,$userlist)){
                $userarr_this[$key]=$value;
            }
        }
        shuffle($userlist);
        for($i=1;$i<=count($userarr_this)-$num;$i++){
            $randKey = rand(0, array_sum($userarr_this)*10);
            $radix = 0;
            $one='';
            foreach ($userlist as $key => $value) {
                $radix+=$userarr_this[$value]*10;
                if ($randKey <= $radix)
                {
                    // $stChoosed[]=$key;
                    $a=$key;
                    break;
                }
            }
            unset($userarr_this[$a]);

        }
        foreach ($userarr_this as $key => $value) {
            $stChoosed[]=$key;
        }
        return $stChoosed ;
    }

    /**
     * 分页用
     * @param $content
     */
    public function getwithorder($status,$content,$page,$numbers){
        $index=($page-1)*6;
        array_multisort($status, SORT_DESC, $content);
        $content=array_slice($content,$index,$numbers);
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

    /**
     * 用户界面的值班表
     * @param $whichweek
     * @param $weekday
     * @param $year_xq
     * @return array
     */
    public function findanpaidata_2($whichweek,$weekday,$year_xq,$nowuser){
        $sql="select id,stname,stid,date_turn,conflict_class from zhibantable WHERE stid='$nowuser' AND date_zhoushu='$whichweek' AND date_weekday='$weekday' AND year_xq='$year_xq'";
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
