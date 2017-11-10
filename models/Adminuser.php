<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/9/25
 * Time: 19:03
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use app\commontool\CommonTool;
class  Adminuser  extends ActiveRecord{
     public  $checkcode;        //验证码
     public  $repeatpwd;       //重复密码
     public static function tableName()
     {
       return "{{%adminuser}}";
     }

     public function attributeLabels(){
         return[
           'nick_name'=>'昵称',
           'admin_email'=>"Email",
           'admin_passwd'=>"密码",
           'coder'=>"验证码",
           'profession'=>"擅长",
           'repeatpwd'=>'重复密码',
           'checkcode'=>''
         ];
     }
     public function rules()
     {
        return [
            ['admin_email','ValidateEmail','on'=>['adminUser']],
            ['nick_name','ValidateName','on'=>['adminUser']],
            [['nick_name','admin_email','admin_passwd','profession'],'required','message'=>"{attribute}必须填写",'on'=>['adminUser']],
            ['admin_email','email','message'=>"{attribute}格式错误",'on'=>['adminUser','adminLogin','getemailPass']],
            [['admin_passwd','repeatpwd'],'required','message'=>"{attribute}必须填写",'on'=>['seekpass']],
            ['admin_email','required','message'=>"{attribute}必须填写",'on'=>['getemailPass','adminLogin']],
            ['repeatpwd','compare','compareAttribute'=>'admin_passwd','message'=>"两次密码不一致",'on'=>['seekpass']],
            ['admin_passwd','required','message'=>'{attribute}必须填写','on'=>['adminLogin']],
            ['checkcode','required','message'=>'{attribute}必须填写','on'=>['adminLogin']],
            ['checkcode','captcha', 'captchaAction'=>'other/captcha','message'=>"{attribute}错误",'on'=>['adminLogin']]

        ];
     }
     public function beforeSave($insert)
     {
        if(parent::beforeSave($insert)){
            $this->reg_time=time();
            /*long2ip($ip)输出,ip在数据库中int保存*/
            $this->ip=ip2long(Yii::$app->request->userIP);
            $this->browser=Yii::$app->request->userAgent;
            /*密码组成：表单输入密码 + 配置文件变量salt + 用户名称*/
            $this->admin_passwd=CommonTool::PassMd5($this->admin_passwd,$this->nick_name);
        }
        return true;
     }
     //admin用户验证邮箱是否存在
    public function ValidateEmail($attribute){
          if(CommonTool::ActRedisDataSET('two','sismember',Yii::$app->params['SADD_customer_email_exist'],$this->admin_email)){
              $this->addError($attribute,'邮箱已经存在');
          }
    }
    //验证admin用户是否存在
    public function ValidateName($attribute){
        if(CommonTool::ActRedisDataSET('two','sismember',Yii::$app->params['SADD_customer_name_exist'],$this->nick_name)){
            $this->addError($attribute,'用户名已经存在');
        }
    }
}
?>