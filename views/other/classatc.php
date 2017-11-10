<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/23
 * Time: 16:29
 */
use yii\helpers\Url;
?>
<div id="classatc">
    <?php
      if($info['num']===1){
         foreach ($info['result'] as $v){
    ?>
             <!--显示文章列表-->
            <div class="article_title">
                <a href="<?=Url::toRoute(['showarticle/details','article_id'=>$v['article_id']])?>" target="_blank"><?=$v['title'];?></a>
            </div>
    <?php
         }
     }else if($info['num']===0){//0代表分类下没有文章
     ?>
             <div class="empty_article"><?=$info['result']?></div>
    <?php
      }else if($info['num']==3){
         //获取全部的分类,数据缓存。
        $result=Yii::$app->classify->Classify(0,'all_class_show');
        if(!empty($result)){
            foreach ($result as $v){
      ?>
            <ul>
                <li class="all_class_li">
                    <!--显示所有的分类-->
                    <a href="<?=Url::toRoute(['other/showclassatc','id'=>$v['class_id']])?>"><?=$v['class_name']?></a>
                </li>
            </ul>
    <?php
            }
        }
      }
    ?>
</div>