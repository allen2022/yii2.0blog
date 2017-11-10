<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/26
 * Time: 15:10
 */


class CrontabTask{
    /****************linux定时从redis中读取数据写入mysql，未完待续*****************************/
        private $Mysql;
        private $Redis;
        public function __construct($RedisHost='127.0.0.1',$RedisPort='6379'){
            //连接数据库
            $DbHost="mysql:dbname=yii;host=121.54.189.68";
            try{
                $DbObj=new PDO($DbHost,'root','123456');
                $this->Mysql=$DbObj;
            }catch(PDOException $e){
                echo "数据库连接失败:",$e->getMessage();
            }
            //文件在linux下直接执行，不能使用Yii注册的redis组件，直接实例化redis对象

            $Redis= new \Redis();
            $Redis->connect($RedisHost,$RedisPort);
            $this->Redis=$Redis;
        }


        //获取“有帮助或没帮助总数量”
        private function RedisOptionsNum($options){

              $result_array_key=$this->Redis->hKeys($options);
              $result_array_value=$this->Redis->hVals($options);
              return array_combine($result_array_key,$result_array_value);

        }
        public function GetResult($option){
            $result=$this->RedisOptionsNum($option);
        }


}
$CrontabTask = new CrontabTask();
$CrontabTask->GetResult('noagree_num_article');

