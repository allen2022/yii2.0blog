<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/9/21
 * Time: 10:39
 */
namespace app\controllers;
use app\models\Adminuser;
use app\models\Article;
use app\models\Createclass;
use app\models\Friend;
use yii\web\Controller;
use app\commontool\CommonTool;
//use yii\captcha\CaptchaAction;
use app\vendor\resetcode\Resetcode;
use yii\helpers\Url;
use Yii;
Class OtherController extends Controller{
    public $layout='header_footer';
    public function actions()
      {
          //验证码配置
         return[
             'error'=>[
                 'class'=>'yii\web\ErrorAction',
             ],
             'captcha'=>[
                 //   'class' => 'yii\captcha\CaptchaAction',
                 /*Yii中ajax验证验证码有小问题*/
                 /*重写验证码源码validate方法*/
                'class'=>'app\vendor\resetcode\Resetcode',
                 /*最多和最少6位验证码*/
                 "minLength"=>6,
                 "maxLength"=>6
             ]
         ];
      }
      /***css和js文件前缀是代表控制器,比如other_***/

      /*申请成为admin用户页面*/
      public function actionAdminuser(){
          CommonTool::web_css_js_bread('other_admin_user.css','other_admin_user.js','>>admin用户组');
          $Model = new Adminuser();
          $Model->scenario="adminUser";
          if($Model->load(Yii::$app->request->post()) && $Model->validate()){
                    if($Model->save(false)){
                        /*储存用户名和邮箱,验证邮箱是否存在*/
                          CommonTool::ActRedisDataSET('two','sadd',Yii::$app->params['SADD_customer_name_exist'],$Model->nick_name);
                          CommonTool::ActRedisDataSET('two','sadd',Yii::$app->params['SADD_customer_email_exist'],$Model->admin_email);
                          CommonTool::Prompt('admin用户申请成功，请耐心等候');
                          return    $this->redirect(Url::toRoute('promptpage/prompt'));
                    }
          }

          return $this->render('AdminUser',['model'=>$Model]);
      }
      /*关于共享博客*/
      public function actionAbout(){
          CommonTool::web_css_js_bread('other_about.js','other_about.css','>>关于共享博客');
          return $this->render('AboutBlog');
      }
      /*admin用户登录*/
      public function actionAdminlogin(){
          $cookie=CommonTool::GetSessionOrCookie();
          //普通用户不能同时登陆成为admin用户
          if($cookie){
              CommonTool::Prompt('请先退出普通用户');
              return $this->redirect(Url::toRoute('promptpage/prompt'));
          }
          CommonTool::web_css_js_bread('other_adminlogin.css','other_adminlogin.js','>>admin登录');
          $Model = new Adminuser();
          $Model->scenario='adminLogin';
          if($Model->load(Yii::$app->request->post()) && $Model->validate()){
                   /*获取admin用户名是否存在数据库中*/
                   if($ModeData=Adminuser::find()
                       ->select('nick_name,admin_email,check_apply,admin_passwd')
                       ->where('admin_email=:admin_email',[':admin_email'=>$Model->admin_email])
                       ->one()){
                       /*核对密码是否正确*/
                       $formPass=CommonTool::PassMd5($Model->admin_passwd,$ModeData->nick_name);
                      if($ModeData->admin_passwd==$formPass){
                          /*admin用户审核是否通过：0审核中，1通过，2不通过*/
                          if($ModeData->check_apply==1){
                              /*设置session*/
                              Yii::$app->session->set(Yii::$app->params['admin_session_name'],$ModeData->nick_name);
                              return $this->redirect(Url::toRoute('index/index'));
                          }else{
                              $Model->addError('admin_email','正在审核,24小时内将发送结果到您邮箱.');
                          }
                        }else{
                          $Model->addError('admin_email','用户名不存在或者密码有误');
                          $Model->addError('admin_passwd','用户名不存在或者密码有误');
                      }
                   }else{
                       $Model->addError('admin_email','用户名不存在或者密码有误');
                       $Model->addError('admin_passwd','用户名不存在或者密码有误');
                   }

          }
          /*ajax验证码服务器验证路径*/
          $ajax_path=CommonTool::PathController();
          return $this->render("AdminLogin",
              [
                   //user数据表
                   'model'=> $Model,
                   //当前域名
                   'basepath'=>Yii::$app->request->hostInfo,
                  /*ajax验证码服务器验证路径*/
                   'ajaxcheck'=>$ajax_path,
              ]
          );
      }
    //显示分类下的文章
    public function actionShowclassatc(){
        //获取数据库分类ID是否存在
        $class_id=(int)Yii::$app->request->get('id');
        if($result=Createclass::find()->select('class_name')->where('class_id=:class_id',[':class_id'=>$class_id])->asArray()->one()){
               //查找分类下的文章
               $result_atc=Article::find()
                   ->select('article_id,title')
                   ->where(['class_name'=>$result['class_name']])->asArray()->all();
               if(!empty($result_atc)){
                   //文章数据不为空
                   CommonTool::web_css_js_bread('other_classatc.css','other_classatc.js',">>{$result['class_name']}");
                   return $this->render('classatc',['info'=>['num'=>1,'result'=>$result_atc]]);

               }else{
                   //文章数据空
                   CommonTool::web_css_js_bread('other_classatc.css','other_classatc.js',">>{$result['class_name']}");
                   return $this->render('classatc',['info'=>['num'=>0,'result'=>'抱歉,暂时没有相关文章']]);
               }
               //等于0查看更多的分类
        }elseif($class_id===0){
            //缓存显示所有的分类.
            CommonTool::web_css_js_bread('other_classatc.css','other_classatc.js','>>More');
            return $this->render('classatc',['info'=>['num'=>3]]);
        }else{
            $this->redirect(['index/index']);
        }
    }
      /*邮箱找到回密码*/
      public function actionForgetpass(){
          CommonTool::web_css_js_bread('other_forget.css','other_forget.js',">>找回密码");
          $GetPassModel = new Adminuser();
          $GetPassModel->scenario='getemailPass';
             if($GetPassModel->load(Yii::$app->request->post()) && $GetPassModel->validate()){
                 $ModelData=Adminuser::find()->select('admin_email,nick_name')
                     ->where('admin_email = :admin_email',[':admin_email'=>$GetPassModel->admin_email])->one();
                 /*邮箱存在则发送密码找回邮件*/
                 if($ModelData){
                     $mailer=Yii::$app->mailer->compose(
                          /*mail目录下getpass.php*/
                         'getpass',[
                             /*传入getpass.php参数如下*/
                             'adminuser'=>$ModelData->nick_name,
                             'time'=>time(),
                             /*seekpass控制器验证链接是否正确*/
                             /*昵称 + 时间 + 密码盐*/
                             'token'=>md5($ModelData->nick_name.time().Yii::$app->params['salt'])
                         ]
                     );
                     /*发送人*/
                     $mailer->setFrom('allp1963@126.com');
                     /*收件人*/
                     $mailer->setTo($ModelData->admin_email);
                     /*主题*/
                     $mailer->setSubject('密码找回');
                     if($mailer->send()){
                         CommonTool::Prompt('邮件发送成功，请去收件箱或者垃圾箱中查找。');
                         return    $this->redirect(Url::toRoute('promptpage/prompt'));
                     }
                 }else{
                     $GetPassModel->addError('admin_email','该邮箱不存在');
                 }
             }
          return $this->render("GetPass",['model'=> $GetPassModel]);
      }
    /*邮箱找回密码链接页面*/
    public function actionSeekpass(){
        CommonTool::web_css_js_bread('other_seekpass.css','other_seekpass.js','>>找回密码');
        $SeekModel=new Adminuser();
        $SeekModel->scenario='seekpass';
        //获取发送邮件的时间
        $time=Yii::$app->request->get('nowstamp');
        //获取用户名
        $username=Yii::$app->request->get('username');
        //获取token密钥
        $url_token=Yii::$app->request->get('token');
        $my_token=md5($username.$time.Yii::$app->params['salt']);
         /*如果链接$url_token与初始化$my_token不相等，则说明不是正确的访问路径*/
        if($url_token !=$my_token){
            return $this->redirect(Url::toRoute('index/index'));
        }
        /*邮件时间超过十分钟失效*/
        if(time() - $time > 600){
            CommonTool::Prompt('时间已经超过10分钟了，链接失效。');
            return    $this->redirect(Url::toRoute('promptpage/prompt'));
        }
        /*修改密码*/
        if($SeekModel->load(Yii::$app->request->post()) && $SeekModel->validate()){
               $updatePass=CommonTool::PassMd5($SeekModel->admin_passwd,$username);
               $result=Adminuser::updateAll(
                   /*根据用户名修改密码字段*/
                   ['admin_passwd'=>$updatePass], 'nick_name = :username', [':username'=>$username]
               );
               /*密码是否修改成功*/
               $result? CommonTool::Prompt('密码修改成功') : CommonTool::Prompt('密码修改失败');
               return    $this->redirect(Url::toRoute('promptpage/prompt'));
        }
        return $this->render('SeekPass',['model'=>$SeekModel]);
    }
    /*验证码ajax验证，输入的验证码是否正确*/
    public function actionCheckcode(){
        /*判断是否是ajax请求*/
        if(Yii::$app->request->isAjax){
           /*获取客户端输入的验证码*/
            $data=Yii::$app->request->get();
            /*直接实例化验证码类*/
            $captcha_validate  = new Resetcode('captcha', $this);
            /*判断验证码存在，然后将验证码传入Yii验证类与服务器对比*/
            if (isset($data['checkcode']) && $captcha_validate->validate($data['checkcode'], false)){
                //验证成功！
                echo 'checkcode_success';
            }else{
                echo 'checkcode_fail';
            }
        }
    }
     /*admin用户退出登录*/
    public  function actionLoginout(){
             if(Yii::$app->session->remove(Yii::$app->params['admin_session_name'])){
                 return $this->redirect(Url::toRoute('index/index'));
             }
    }
    /*友情链接页面*/
    public function actionFriend(){
        CommonTool::web_css_js_bread('other_friend.css','other_friend.js','>>友情链接');
        $Model = new Friend();
        $Model->scenario='friend';
        if($Model->load(Yii::$app->request->post()) &&  $Model->validate()){
            //save传参false表示无需再validate
            if($Model->save(false)){
                CommonTool::Prompt('友情链接申请成功，请耐心等候');
                return $this->redirect(Url::toRoute('promptpage/prompt'));
            }
        }
        /*ajax验证码，服务器验证路径*/
        $ajax_path=CommonTool::PathController();
        return $this->render(
            'Friend',
            [
                'model'=> $Model,
                'basepath'=>Yii::$app->request->hostInfo,
                'ajaxcheck'=>$ajax_path
            ]);
    }
}