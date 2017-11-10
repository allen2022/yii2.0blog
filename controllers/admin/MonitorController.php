<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/11
 * Time: 10:30
 */
namespace app\controllers\admin;
use Yii;
use yii\web\Controller;
use app\commontool\CommonTool;
use app\models\Createarticle;
use yii\helpers\Url;

class MonitorController extends Controller{
      //初始化
      public function init()
      {
           /*session不存在则无法登录后台*/
          $session=Yii::$app->session->get(Yii::$app->params['admin_session_name']);
          if(empty($session) || !isset($session)){
              return $this->redirect(['/index/index']);
          }
      }
}