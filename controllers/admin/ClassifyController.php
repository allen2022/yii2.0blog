<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/2
 * Time: 11:17
 */
namespace app\controllers\admin;
use app\models\Createclass;
use Yii;
use app\commontool\CommonTool;
use yii\web\Controller;

class ClassifyController extends  MonitorController{
    public $layout='admin_header_footer';

    /*发布文章时，先选择类目*/
    public function actionClassify(){
        CommonTool::web_css_js_bread('admin_admin_classify.css','admin_admin_classify.js','>>文章分类');
        $Model = new Createclass();
        $Model->scenario = 'classify';
        $Controller=CommonTool::PathController();
        /*获取分类名*/
       $ClassData=Createclass::find()->select('class_name')->asArray()->all();
       /*$ClassData是二维数组，然后再foreach循环成一维数组且下标k和v是相同，写入到select标签中*/
       $ArrClassData=[];
       foreach ($ClassData as $v){
           //二维数组成一维数组
           $ArrClassData[$v['class_name']]=$v['class_name'];
       }
        if($Model->load(Yii::$app->request->bodyParams) && $Model->validate()){
                    /*如果选择的类目存在才可以跳转到文章页面发布，以免篡改不存在的类目*/
                    if(in_array($Model->class_name,$ArrClassData)){
                         $this->redirect(['admin/article/createart','class_name'=>$Model->class_name]);
                    }
        }
        /*增加分类路径*/
        $Path=['add_class'=>$Controller.'admin/classify/addclass'];
        return $this->render('ClassIfy',['model'=>$Model,'path'=>$Path,'classdata'=>$ArrClassData]);
    }
    /*增加分类*/
    public function actionAddclass(){
        $Model = new Createclass();
        $Model->scenario='addclass';
        /*写入css和js*/
        CommonTool::web_css_js_bread('admin_admin_addclass.css','admin_admin_addclass.js','>>增加分类');
        if($Model->load(Yii::$app->request->bodyParams) && $Model->validate()){
                  //添加创建分类的admin
                  $Model->inventor=CommonTool::GetSessionOrCookie();
                  $Model->create_time=time();
                  if($Model->save()){
                      $this->redirect(['admin/classify/classify']);
                  }
        }
        return $this->render('AddClass',['model'=>$Model]);
    }

}


?>