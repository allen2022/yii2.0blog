<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/9/28
 * Time: 12:49
 */
namespace app\controllers;
use yii\web\Controller;
use Yii;
use yii\helpers\Url;
class PromptpageController extends Controller{
     /*跳转页面的提示信息*/
     public function actionPrompt(){
         $this->layout='prompt';
         //如果$info存在提示信息
         if(Yii::$app->getSession()->hasFlash('info')){
             $info=Yii::$app->getSession()->getFlash('info');
             return $this->render('Prompt',['info'=>$info]);
         }else{
             $url=Url::toRoute('index/index');
             return $this->redirect($url);
         }

     }
}