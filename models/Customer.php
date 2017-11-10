<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/13
 * Time: 10:21
 */
namespace app\models;
use yii\base\Model;
use Yii;
use yii\db\ActiveRecord;
use app\commontool\CommonTool;
class Customer extends ActiveRecord{
    //初始化字段名
    public $ajaxurl;
    public static function tableName(){
        return "{{%customer}}";
    }
    public function rules()
    {
        return [
             [['cus_email','cus_name','cus_passwd'],'required','message'=>'{attribute}必须填写','on'=>['reg']],
             ['cus_email','email','message'=>'邮箱格式不正确','on'=>['reg','login']],
             [['cus_email','cus_passwd'],'required','message'=>'{attribute}必须填写','on'=>['login']],
             //UniqueEmail，UnqiueName自定义验证
             ['cus_email','UniqueEmail','on'=>['reg']],
             ['cus_name','UnqiueName','on'=>['reg']],
        ];
    }

    public function attributeLabels(){
          return [
              'cus_email'=>"邮箱",
              'cus_passwd'=>"密码",
              'cus_name'=>'用户名'
          ];
    }
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->cus_passwd=CommonTool::PassMd5($this->cus_passwd,$this->cus_email);
            $this->reg_time=time();
        }
        return true;
    }
    /*php验证email是否存在*/
    public function UniqueEmail($attribute){
           if(CommonTool::ActRedisDataSET('two','sismember',Yii::$app->params['SADD_customer_email_exist'],$this->cus_email)){
               return $this->addError($attribute,'邮箱已经存在');
           }
    }
    /*php验证用户名是否存在*/
    public function UnqiueName($attribute){
        if(CommonTool::ActRedisDataSET('two','sismember',Yii::$app->params['SADD_customer_name_exist'],$this->cus_name)){
            return $this->addError($attribute,'用户名已经存在');
        }
    }


}

?>