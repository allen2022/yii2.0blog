<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/9/21
 * Time: 10:39
 */
namespace app\controllers\admin;
use Yii;
use app\commontool\CommonTool;
use yii\web\Controller;
use app\controllers\admin\MonitorController;

class AdminController extends MonitorController {
     public $layout='admin_header_footer';
     public function actionIndex(){
         /*将css、js注入到layout页面中*/
          CommonTool::web_css_js_bread('admin_admin_index.css','admin_admin_index.js');
         /*admin当前控制器目录绝对路径*/
          $Controller=CommonTool::PathController();
          $Path_dir=[
             /*文章发布*/
             'create_article'=>$Controller.'admin/classify/classify',
             /*文章列表*/
             'list_article'=>$Controller.'admin/article/listart',
             /*个人中心*/
             'person_center'=>$Controller.'admin/person/center',
         ];
         return $this->render('Index',['path'=>$Path_dir]);

     }


}