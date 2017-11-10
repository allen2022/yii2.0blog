<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/9/21
 * Time: 10:39
 */
namespace app\controllers\admin;
use Yii;
use yii\helpers\Url;
use app\commontool\CommonTool;
use app\models\Article;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\data\Pagination;
class ArticleController extends  MonitorController {
     public $layout='admin_header_footer';

     /*发布文章*/
     public function actionCreateart(){
         /*将css、js写入layout页面中*/
         CommonTool::web_css_js_bread('admin_article_createart.css','admin_article_createart.js','>>发布文章');
         $Model = new Article();
         $Model->scenario='createart';
         /*获取actionClassify方法传递的类目,并判断是否有值*/
         if(empty(Yii::$app->request->queryParams['class_name']) || !isset(Yii::$app->request->queryParams['class_name'])){
             $this->redirect(Url::toRoute(['admin/classify/classify']));
         }

         /*接受文章数据*/
         if($Model->load(Yii::$app->request->bodyParams)){
              $Model->authors=Yii::$app->session->get(Yii::$app->params['admin_session_name']);
              /*富文本编辑器并不是textarea标签所以提交时js复制文字到另一个textarea中*/
             $Model->content=Yii::$app->request->post('content');
                    if($Model->save()){
                        /*获取新增数据的ID*/
                        $insert_id=Yii::$app->db->getLastInsertID();
                        /*新增ID用于查看文章用于以后跳转查看文章是否存在*/
          CommonTool::ActRedisDataSET('two','sadd',Yii::$app->params['SADD_article_id_exist'],$insert_id);
                        $this->redirect(['admin/admin/index']);
                    }
         }
         return $this->render('CreateArt',['model'=>$Model,'class_name'=>Yii::$app->request->queryParams['class_name']]);
     }
     /*文章列表*/
     public function actionListart(){
         /*将css、js写入layout页面中*/
         CommonTool::web_css_js_bread('admin_article_listart.css','admin_article_listart.js','>>文章列表');
         /*获取文章列表数据*/
         $Data=Article::find()
             ->select('article_id,title,opinion_num,agree,noagree,class_name,create_time')
             ->where('authors = :session',[':session'=>Yii::$app->session->get(Yii::$app->params['admin_session_name'])]);
         //文章分页
         $Page = new Pagination(['totalCount'=>$Data->count(),'pageSize'=>3]);
         $PageModel=$Data->offset($Page->offset)->limit($Page->limit)->asArray()->all();
         foreach ($PageModel as $k=>$v){
             /*时间戳转换*/
             $PageModel[$k]['create_time']=date('Y-m-d H:i',$v['create_time']);
             /*html和js以纯文本的方式输出*/
             $PageModel[$k]['title']=Html::encode($v['title']);
             /*下列是过滤js代码,title可用，可不用*/
//           $Data[$k]['title']=HtmlPurifier::process($v['title']);
         }
         return $this->render('ListArt',['model'=>$PageModel,'Page'=>$Page]);
     }
     //修改文章
     public function actionUpdateart(){
         //修改文章的js和css和发布文章共用
         CommonTool::web_css_js_bread('admin_article_createart.css','admin_article_createart.js','>>修改文章');
         $Model = new Article();
         $Model->scenario='updateart';
         //获取文章ID
         $atc_id=(int)Yii::$app->request->get('atc_id');
         //查询文章是否存在
         if(CommonTool::ActRedisDataSET('two','sismember',Yii::$app->params['SADD_article_id_exist'],$atc_id)){
            //获取文章内容
             $data=Article::find()
                 ->select('title,description,content')
                 ->where('article_id = :atc_id',[':atc_id'=>$atc_id])
                 ->asArray()
                 ->one();
             //表单提交修改
             if($Model->load(Yii::$app->request->post()) && $Model->validate()){
                 /*富文本编辑器并不是textarea标签,所以提交时js复制文字到textarea中*/
                   $Model->content=Yii::$app->request->post('content');
                   Article::updateAll(
                       [
                           'title'=>$Model->title,
                           'description'=>$Model->description,
                           'content'=>$Model->content,'create_time'=>time()
                       ],'article_id=:atc_id',[':atc_id'=>$atc_id]);
                   //http://192.168.1.68/yii/web/index.php?r=showarticle%2Fdetails&article_id=2
                   $this->redirect(['showarticle/details','article_id'=>$atc_id]);
             }
             return $this->render('UpdateArt',['model'=>$Model,'data'=>$data]);
         }else{
             CommonTool::Prompt('文章不存在');
             return $this->redirect(Url::toRoute('promptpage/prompt'));
         }
     }
}