<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/9/21
 * Time: 10:39
 */
namespace app\controllers;
use yii\web\Controller;
use app\models\Article;
use Yii;
use app\commontool\CommonTool;


Class IndexController extends Controller{
    /*使用layout中的header.php,公用header*/
      public $layout='header_footer';
      public function actionIndex(){
           $data=Article::find()
               ->select('article_id,title,description,opinion_num')
               ->where('create_time<:time',[':time'=>time()])
               ->asArray()
               ->limit(10)
               ->all();
           return $this->render('Index',['data'=>$data]);
      }


}