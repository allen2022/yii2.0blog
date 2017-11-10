<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/9/25
 * Time: 9:59
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
class Friend extends ActiveRecord{
     //初始化字段
     public $checkcode;
     public static function tableName(){
         return "{{%friend}}";
     }
     /*规则*/
     public function rules()
     {
        return [
            [['title','link'],'required','message'=>"{attribute}不能为空!",'on'=>['friend']],
            ['checkcode', 'required','message'=>'验证码不能为空','on'=>['friend']],
              /*******注意服务器验证码问题*****/
              /*
               * ajax验证，需要继承yii的CaptchaAction,将这个validate方法重写
                 ajax验证后表单并没有提交，但是服务器会生成新的验证码，再次提交表单就会出错。
                 这个方法须重写！
                 去除validate方法中的下面if判断
                 if ($valid || $session[$name] > $this->testLimit && $this->testLimit > 0) {
                   $this->getVerifyCode(true);
                   }
              */
              /*服务器端验证。主要用于浏览器屏蔽js后ajax验证失效，php端会验证对错。*/
            [ 'checkcode','captcha', 'captchaAction'=>'other/captcha','message'=>"验证码错误",'on'=>['friend']]

        ];
     }
    /*label标签*/
    public function attributeLabels()
     {
        return [
          'title'=>'标题',
          'link'=>'链接',
          'checkcode'=>'验证码'
        ];
     }

}