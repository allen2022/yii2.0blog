<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/13
 * Time: 9:58
 */
namespace app\controllers;
use app\models\Customer;
use yii\web\Controller;
use app\commontool\CommonTool;
use Yii;
use yii\helpers\Url;
use yii\web\Cookie;

/*普通用户*/
class CustomerController extends  Controller{
    public $layout='header_footer';

    /*普通用户登录*/
    public function  actionLogin(){
        $session=CommonTool::GetSessionOrCookie();
        /*如果session已经存在就不能登录普通用户*/
        if(!empty($session)){
            CommonTool::Prompt('请先退出admin用户');
            return $this->redirect(Url::toRoute('promptpage/prompt'));
        }
        CommonTool::web_css_js_bread('customer_login.css','customer_login.js','>>用户登录');
        $Model=new Customer();
        $Model->scenario='login';
        if($Model->load(Yii::$app->request->bodyParams) && $Model->validate()){
                /*密码*/
                $Model->cus_passwd=CommonTool::PassMd5($Model->cus_passwd,$Model->cus_email);
                /*redis查找邮箱是否存在,避免邮箱输错后进行mysql查询*/
                if(!CommonTool::ActRedisDataSET('two','sismember',Yii::$app->params['SADD_customer_email_exist'],$Model->cus_email)){
                    /*邮箱不存在*/
                    $Model->addError('cus_email','邮箱或者密码错误');
                    $Model->addError('cus_name','邮箱或者密码错误');
                    return $this->render('Login',['model'=>$Model]);
                }
                /*从数据库中查询*/
                $result=Customer::find()
                    ->select('cus_name,cus_email,cus_passwd,reg_time')
                    ->where('cus_email=:email and cus_passwd=:pass',[':email'=>$Model->cus_email,':pass'=>$Model->cus_passwd])
                    ->one();
                if($result){
                    /*设置cookies*/
                    $cookies=Yii::$app->response->cookies;
                    $cookies->add(new Cookie(['name'=>Yii::$app->params['blog_cookie_name'],'value'=>$result->cus_name]));
                    $this->redirect(['index/index']);
                }else{
                    $Model->addError('cus_email','邮箱或者密码错误');
                    $Model->addError('cus_passwd','邮箱或者密码错误');
                }
        }
        return $this->render('Login',['model'=>$Model]);
    }
    /*普通用户注册*/
    public function  actionReg(){
        CommonTool::web_css_js_bread('customer_reg.css','customer_reg.js','>>用户注册');
        $Model=new Customer();
        $Model->scenario='reg';
        if($Model->load(Yii::$app->request->bodyParams) && $Model->validate()){
                  /*储存用户名和邮箱,主要用于验证邮箱是否存在*/
                  CommonTool::ActRedisDataSET('two','sadd',Yii::$app->params['SADD_customer_name_exist'],$Model->cus_name);
                  CommonTool::ActRedisDataSET('two','sadd',Yii::$app->params['SADD_customer_email_exist'],$Model->cus_email);
                  if($Model->save(false)){
                      //消息flag，回复消息或者其他的提示信息数量keyname初始化，默认0。
                      CommonTool::ActRedisDataHASH('three','hset',Yii::$app->params['HSET_message_to_user_total_num'],$Model->cus_name,0);
                      CommonTool::Prompt('注册成功');
                      return $this->redirect(Url::toRoute('promptpage/prompt'));
                  }
        }
        /*
         *
         * @param ajaxurl (string)：ajax验证用户名是否存在控制器路径
         * */
        return $this->render('Reg',['model'=>$Model,'ajaxurl'=>CommonTool::PathController()]);
    }

    /*用户注册ajax验证邮箱和用户是否存在*/
    public function actionAjaxinput(){
        $typeName=Yii::$app->request->get('typename');
         switch ($typeName){
             //检查用户名是否存在
             case 'name':
                 $cus_name=Yii::$app->request->get('check_field');
                 return CommonTool::ActRedisDataSET('two','sismember',Yii::$app->params['SADD_customer_name_exist'],$cus_name)? 1:0;
                 break;
             //检查邮箱是否存在
             case 'email':
                 $cus_email=Yii::$app->request->get('check_field');
                 return CommonTool::ActRedisDataSET('two','sismember',Yii::$app->params['SADD_customer_email_exist'],$cus_email)? 1:0;
                 break;
         }

    }
    /*退出登录*/
    public function actionLoginout(){
        $cookie=Yii::$app->response->cookies;
        $cookie->remove(Yii::$app->params['blog_cookie_name']);
        $this->redirect(['index/index']);
    }


}




?>