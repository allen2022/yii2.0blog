<?php
use yii\helpers\Url;
?>
<div class="pic_show">
    <img src="<?=Yii::$app->params['web_img_path'].'one.jpg';?>">
</div>
<!--首页文章列表--***start--->
<?php
  foreach ($data as $v){
?>
<div id="article_list">
     <div class="article_title"><h4><a href="<?=Url::toRoute(['showarticle/details','article_id'=>$v['article_id']])?>" target="_blank"><?=$v['title']?></a></h4></div>
     <div class="article_desc"><?=$v['description']?></div>
</div>
<?php
  }
?>

<!--首页文章列表--***end--->
