<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/23
 * Time: 16:40
 */
//显示首页header分类
namespace app\component;
use app\models\Createclass;
use Yii;
//缓存类
class classify{
    /*
     * @param limit (int):大于0,代表首页header只显示部分分类名称,等于0表示more页面显示所有分类名称
     * @param keyname (string):缓存名称
     * */
    public function Classify($limit,$keyname){
        //分类数据使用文件缓存,获取缓存组件
        $cache=Yii::$app->cache;
        //如果获取class_name_db失败
        if(!$cache->get($keyname)){
            //当数据总记录发生了改变的时候,就会重新生成缓存.
            $depen=new \yii\caching\DbDependency(['sql'=>'select count(*) from yii_createclass']);
            //重新获取数据库新记录
            if($limit>0){
                //大于0则显示部分分类名称
                $result=Createclass::find()->limit($limit)->orderBy(['create_time'=>SORT_ASC])->asArray()->all();
            }else{
                //等于0显示全部的分类名称
                $result=Createclass::find()->orderBy(['create_time'=>SORT_ASC])->asArray()->all();
            }
            //缓存失效,同时删除键名,再进行创建新的缓存
            $cache->delete($keyname);
            //$keyname 依赖$depen,只要$depen监听数据库记录发生了改变$keyname就会失效,重新读取数据,0代表缓存一年后失效
            $cache->add($keyname,$result,0,$depen);
        }else{
            //获取缓存
            return $cache->get($keyname);
        }
    }

}
