<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/9/30
 * Time: 11:19
 */
namespace app\commontool;
use yii\captcha\CaptchaAction;
use Yii;
use yii\web\Controller;
class  CommonTool{
    /*后台注入css，js，导航*/
    /*
     * @param css (string):css路径
     * @param js (string):js路径
     * @param bread (string):面包屑导航词
     * */
    public static function web_css_js_bread($css='',$js='',$bread=''){
         Yii::$app->view->params['css']   = $css;
         Yii::$app->view->params['js']    = $js;
         Yii::$app->view->params['bread'] = $bread;

    }
    /*获取网站绝对路径*/
    public static function PathController(){
        /*域名*/
        $domain=Yii::$app->request->hostInfo;
        /*root目录*/
        $root  =Yii::$app->homeUrl;
        $Path=$domain.$root.'?r=';
        return $Path;
    }

    /*设置提示信息*/
    /*
     * @param info_content (string):跳转提示信息
     * */
    public static function Prompt($info_content){
        Yii::$app->getSession()->setFlash('info',$info_content);

    }

    /*密码组成：表单输入密码 + 配置文件salt + email*/
    /*
     * @param formPass (string):表单提交的密码
     * @param Email (string):表单提交的邮箱
     *
     * */
    public static function PassMd5($formPass,$Email){
       return md5($formPass.Yii::$app->params['salt'].$Email);
    }
    /*获取当前的cookie或者session*/
    public static function  GetSessionOrCookie(){
        $cookie=Yii::$app->request->cookies;
        $name=$cookie->has(Yii::$app->params['blog_cookie_name'])?$cookie->get(Yii::$app->params['blog_cookie_name'])->value:Yii::$app->session->get(Yii::$app->params['admin_session_name']);
        return $name;
    }
    /*********
     * redis中的数据操作有五种类型，String、list、Set、Zset、Hash。
     * 可以写成一个方法，但以后可能会出现不同的数据判断。
     * 五种操作方法分开写。
     * 全部写在一个方法中，会越写越复杂。
     ***********/
    /*操作redis中的哈希数据*/
    /*
     *@param ParamsNum (string)：操作redis的参数，如hmget(key)有key一个参数,传“one”,hmget(key,value)传“two”,依次three等
     *@param RedisAction (string)：redis方法比如hmget或者hmset
     *@param TableName (string)：等于数据库的表名
     *@param Key (string)：等于数据表字段,默认空
     *@param Value (string)：等于数据表字段下的值,默认空
     *@param AutoNum (int)：自增值，默认1
     * */
    public static function ActRedisDataHASH($ParamsNum,$RedisAction,$TableName='',$Key='',$Value='',$AutoNum='1'){
         switch ($ParamsNum){
             case 'one':
                 return Yii::$app->Yiiredis->Redis()->$RedisAction($TableName);
                 break;
             case 'two':
                 return Yii::$app->Yiiredis->Redis()->$RedisAction($TableName,$Key);
                 break;
             case 'three':
                 return Yii::$app->Yiiredis->Redis()->$RedisAction($TableName,$Key,$Value);
                break;
             case 'four':
                 return Yii::$app->Yiiredis->Redis()->$RedisAction($TableName,$Key,$Value,$AutoNum);

         }
    }
    //操作redis中set集合
    /*
    *@param ParamsNum (string)：操作redis的参数，如hmget(key)有key一个参数,传“one”,hmget(key,value)传“two”,依次three等
    *@param RedisAction (string)：redis方法比如hmget或者hmset
    *@param Key (string)：等于数据表字段,默认空
    *@param Value (string)：等于数据表字段下的值,默认空
    *@param AutoNum (int)：自增值，默认1
    *@param Tk (string):设置时间使用,默认空
    * */
    public static function ActRedisDataSET($ParamsNum,$RedisAction,$Key='',$value='',$AutoNum='1',$Tk=''){
         switch ($ParamsNum){
             case 'one':
                 return Yii::$app->Yiiredis->Redis()->$RedisAction($Key);
                 break;
             case 'two':
                 //部分操作需要使用TK才能执行，比如移动key到另一个集合中。
                 return $Tk=='TK'?Yii::$app->Yiiredis->Redis()->$RedisAction($Key,$value,$Tk) : Yii::$app->Yiiredis->Redis()->$RedisAction($Key,$value);
                 break;
         }
    }
    //操作redis中string类型
    /*
   *@param ParamsNum (string)：操作redis的参数，如hmget(key)有key一个参数,传“one”,hmget(key,value)传“two”,依次three等
   *@param RedisAction (string)：redis方法比如hmget或者hmset
   *@param Key (string)：等于数据表字段,默认空
   *@param Value (string)：等于数据表字段下的值,默认空
   *@param AutoNum (int)：自增值，默认1
   *@param Tk (string):设置时间使用,默认空
   * */
    public static function ActRedisDataSTRING($ParamsNum,$RedisAction,$Key='',$Value='',$AutoNum='1',$Tk=''){
        switch ($ParamsNum){
            case 'one':
                return Yii::$app->Yiiredis->Redis()->$RedisAction($Key);
                break;
            case 'two':
                //如果等于TK是设置key的过期时间。
                if($Tk=='TK'){
                    return Yii::$app->Yiiredis->Redis()->$RedisAction($Key,$Value,$Tk);
                }else{
                    return Yii::$app->Yiiredis->Redis()->$RedisAction($Key,$Value);
                }
                break;
        }
    }
    //操作redis删除key，统一都是del，只需传key。
    /*
     * @param Key (string):redis储存数据的键名
     * */
    public static function ActRedisDataDEL($Key){
        return Yii::$app->Yiiredis->Redis()->Del($Key);
    }

}