<?php
namespace app\controllers;
use app\models\Kb;
use app\models\Kbmember;
use app\models\Zhibantable;
use yii\web\Controller;
use yii\db\Query;
use yii;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: application/json;charset=utf-8");

/**
 * 空课表值班安排
 *
 */

class EmptyclassController extends Controller {
    public $enableCsrfValidation = false;
    /**
     * 更新成员们的课表,从kb存到memberkb
     */
    public function actionUpdatememberkb(){
        //判断学期 学年
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
        //获取用户表
        $member   = (new Query())
            ->select(['XH_ID', 'Name','status'])
            ->from('user_tb')
            ->orderBy('status DESC')
            ->all();
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

        $mes = '{"ifsuccess":true}';
        echo $mes;
    }

    /**
     * 查询安排
     */
    public function actionSearchanpai(){
        $request=Yii::$app->request;
        $session=Yii::$app->session;
        $whichweek=$request->get('whichweek_2');
        $weekday=$request->get('weekday_2');
        $session['whichweek_2']=$whichweek;
        $session['weekday_2']=$weekday;
        $anpaitable=new Zhibantable();
        $year_xq=$anpaitable->xuenianxueqi();
        $content=$anpaitable->findanpaidata($session['whichweek_2'],$session['weekday_2'],$year_xq);
        echo '{"success":true,"anpai":'.json_encode($content,JSON_UNESCAPED_UNICODE).'}';
    }
    /**
     * 搜索有空值班的学生
     */
    public function actionSearchemptyclass(){
        $request=Yii::$app->request;
        if(empty($request->get('whichweek'))||empty($request->get('zhibantime'))||empty($request->get('weekday'))){
            echo '{"success":false,"msg":"请完成筛选条件"}';
            return;
        }
        $whichweek=$request->get('whichweek');
        $zhibantime=$request->get('zhibantime');
        $weekday=$request->get('weekday');
        $aa=new Zhibantable();
        $content=$aa->getemptyst($whichweek,$weekday,$zhibantime);
        $session=YII::$app->session;
        $session['whichweek']=$whichweek;
        $session['weekday']=$weekday;
        $session['zhibantime']=$zhibantime;
        //学期 学年
        $year_xq=$aa->xuenianxueqi();
        //用于显示已被安排的学生
        foreach($content as $key=>$value){
            $stname=$value['Name'];
            $xh=$value['XH_ID'];
            $sql2="select * from zhibantable WHERE stname='$stname' AND stid='$xh' AND date_zhoushu='$whichweek' AND date_weekday='$weekday' AND date_turn='$zhibantime'AND year_xq='$year_xq'";
            if($aim=Yii::$app->db->createCommand($sql2)->queryOne()){
                $content[$key]['status']=1;
                $content[$key]['anpai_id']=$aim['id'];
            }
        }


        $content = '{"success":true,"kongkebiao":'.json_encode($content, JSON_UNESCAPED_UNICODE).'}';
        echo $content;
//        print_r($content);
    }

    /**
     * 安排排班，插入数据库
     */
    public function actionManagest(){
        $request=yii::$app->request;
        $stname=$request->post('stname');
        $xh=$request->post('xh');
        $whichweek=$request->post('whichweek');
        $weekday=$request->post('weekday');
        $zhibantime=$request->post("zhibantime");

        //判断学期 学年
        $bb=new Zhibantable();
        $year_xq=$bb->xuenianxueqi();
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
        $xhxm=$bb->getemptyst($whichweek,$weekday,$zhibantime);
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
            $result='{"success":true,"msg":"这一班已经安排满了！(请在左边栏确认是否有手动排班)"}';
        }else{
            $newzhiban->save(false);
            $result='{"success":true,"msg":"安排值班成功"}';
        }
        echo $result;
    }

    /**
     * 删除安排
     */
    public function actionDelanpai(){
        $request=YII::$app->request;
        $anpai_id=$request->post('anpai_id');
        $zhibantable=new Zhibantable();
        $result=$zhibantable->delanpai($anpai_id);
        echo $result;

    }

    /**
     * 获取学生姓名学号（人工排班）
     * @return array|string
     */
    public function actionGetnamemanual(){
        $getname=new Zhibantable();
        $content=$getname->genamemanual();
        return $content;
    }

    /**
     * 人工排班插入数据库
     */
    public function actionInsertmanual(){
        $request=yii::$app->request;
        $stname=$request->post('stname');
        $xh=$request->post('xh');
        $whichweek=$request->post('whichweek');
        $weekday=$request->post('weekday');
        $zhibantime=$request->post("zhibantime");
        $insertmanual=new Zhibantable();
        $result=$insertmanual->insertanpaimanual($stname,$xh,$whichweek,$weekday,$zhibantime);
        echo $result;
    }
}