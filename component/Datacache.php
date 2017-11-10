<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/23
 * Time: 16:40
 */
//显示首页header分类
namespace app\component;
use  Yii;
//缓存类
class Datacache{
     /*
      * @param KeyName (string)：缓存key，keyname在配置文件中配置，以免产生重复。
      * @param Mtehod (string)：方法，比如是添加缓存还是获取缓存。
      * @param Data (mix)：缓存的数据
      * @param CacheTime (int)：缓存时间
      *
      * */
     public function Datacache($KeyName,$Method,$Data='',$CacheTime=''){
              //使用缓存组件
              $cache=Yii::$app->cache;
              if($Method=='add'){
                  //添加缓存
                  $cache->add($KeyName,$Data);
                  //设置缓存储存时间
                  return $cache->set($KeyName,$Data,$CacheTime);
              }elseif ($Method=='get'){
                  //获取缓存
                 return $cache->get($KeyName);
              }
     }


}
