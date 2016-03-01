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
        $updatememberkb=new Zhibantable();
        $result=$updatememberkb->updatememberkb();
        if($result==true){
            $mes = '{"ifsuccess":true,"msg":"更新成功"}';
        }else{
            $mes = '{"ifsuccess":false，"msg":"更新失败：没有成员"}';
        }
        echo $mes;
    }

    /**
     * 查询一周的安排（左边的视图）
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
     * 搜索有空值班的学生(右边的视图)
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
        $page=$request->get('page');
        $aa=new Zhibantable();
        $content=$aa->getemptyst($whichweek,$weekday,$zhibantime);
        $session=YII::$app->session;
        $session['whichweek']=$whichweek;
        $session['weekday']=$weekday;
        $session['zhibantime']=$zhibantime;
        if(!empty($content)){
            $content=$aa->showselectanpai($content,$whichweek,$weekday,$zhibantime,$page,6);
        }else{
            $content='{"success":false,"msg":"没有找到有空的学生"}';
        }
        echo $content;
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
        if(empty($stname)||empty($xh)||empty($whichweek)||empty($weekday)||empty($zhibantime)){
            echo '{"success":false,"msg":"安排值班失败！"}';
        }else{
            $bb=new Zhibantable();
            $year_xq=$bb->xuenianxueqi();
            $result=$bb->managest($stname,$xh,$whichweek,$weekday,$zhibantime,$year_xq);
            echo $result;
        }

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

    public function actionSearchorder(){
        $nowuser    = \Yii::$app->user->identity->XH_ID;
        $request=Yii::$app->request;
        $session=Yii::$app->session;
        $whichweek=$request->get('whichweek_user');
        $weekday=$request->get('weekday_user');
        $session['whichweek_2']=$whichweek;
        $session['weekday_2']=$weekday;
        $anpaitable=new Zhibantable();
        $year_xq=$anpaitable->xuenianxueqi();
        $content=$anpaitable->findanpaidata_2($session['whichweek_2'],$session['weekday_2'],$year_xq,$nowuser);
        echo '{"success":true,"anpai":'.json_encode($content,JSON_UNESCAPED_UNICODE).'}';
    }
}