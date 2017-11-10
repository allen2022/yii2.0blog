<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/11
 * Time: 12:23
 */
namespace app\component;
//redis类
class  Yiiredis{

    public function Redis(){
        $Redis= new \Redis();
        $Redis->connect('121.54.189.68',6379);
        return  $Redis;
    }
}