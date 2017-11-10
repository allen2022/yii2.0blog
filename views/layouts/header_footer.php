<?php
use yii\helpers\Url;

use app\models\Comment;
use app\models\Article;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=Yii::$app->params['web_title']?></title>
    <!--Reset.css初始化-->
    <link href=<?=Yii::$app->params['web_css_path'].'cssReset.css'?> rel="stylesheet">
    <link href=<?=Yii::$app->params['web_css_path'].'header_footer.css'?> rel="stylesheet">
    <link href=<?=Yii::$app->params['web_css_path'].'index.css'?> rel="stylesheet">
    <link href=<?=Yii::$app->params['web_css_path'].'all_form_input_submit.css'?> rel="stylesheet">
    <?php
     /*view下的html页面,加载控制器中的css*/
     if(isset($this->params['css'])){
         $css_path=Yii::$app->params['web_css_path'].$this->params['css'];
         echo "<link href='{$css_path}' rel='stylesheet'>";
     }
    ?>
    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="<?=Yii::$app->params['web_js_path'].'header_footer.js'?>"></script>
    <?php
    /*view下的html页面,加载控制器中的js*/
    if(isset($this->params['js'])){
        $js=Yii::$app->params['web_js_path'].$this->params['js'];
        echo "<script src='{$js}'></script>";
    }
    ?>

</head>
<body>
<!--header*****start****-->
<div id="header">
 <div id="top">
     <div class="top_header">
         <ul>
             <li><a href="<?=Url::toRoute(['other/about']);?>">关于共享博客</a></li>
             <li><a href="<?=Url::toRoute(['other/friend']);?>">友情链接</a></li>
             <?php
                /*显示admin用户*/
                $session=Yii::$app->session->get(Yii::$app->params['admin_session_name']);
                if(empty($session)){
                    echo  '<li><a href='.Url::toRoute(['other/adminuser']).' id="get_admin">加入admin组</a></li>';
                    echo  '<li><a href='.Url::toRoute(['other/adminlogin']).'>Admin登录</a></li>';
                }else{
                    echo  '<li><a href='.Url::toRoute(['admin/admin/index']).'>后台操作</a></li>';
                    echo  '<li><a href='.Url::toRoute(['other/loginout']).'>退出登录</a></li>';
                    echo  "<li>欢迎admin用户:{$session}</li>";
                }
             ?>
         </ul>
         <ul class="reg_login">
         <?php
            /*显示普通用户*/
            $cookie=Yii::$app->request->cookies;
            if($cookie->has(Yii::$app->params['blog_cookie_name'])){
                $cookie=$cookie->get(Yii::$app->params['blog_cookie_name'])->value;
                echo  '<li><a href='.Url::toRoute(['customer/loginout']).'>退出登录</a></li>';
                echo  "<li>亲,欢迎光临:{$cookie}</li>";
            }else{
                echo '<li><a href='.Url::toRoute(['customer/reg']).'>普通注册</a></li>';
                echo '<li><a href='.Url::toRoute(['customer/login']).'>普通登录</a></li>';
            }
         ?>
         </ul>
     </div>
 </div>
 <div id="top_show">
      <div id="top_list">
          <div class="top_person fl">
              <div class="top_explain"><a href="<?=Url::toRoute(['index/index']);?>">共享博客</a></div>
          </div>
          <div class="top_tag fl">
              <ul>
                  <li class="top_tag_select"><a href="<?=Url::toRoute(['index/index']);?>">Index</a></li>
                  <?php
                    //获取分类名称
                    //获取缓存键class_header_show中3个分类名称
                    $result=Yii::$app->classify->Classify(3,'class_header_show');
                    if(!empty($result)){
                       foreach ($result as $v){
                  ?>
                  <li><a href="<?=Url::toRoute(['other/showclassatc','id'=>$v['class_id']])?>"><?=$v['class_name']?></a></li>
                        <?php } ?>
                       <?php } ?>
                  <li><a href="<?=Url::toRoute(['other/showclassatc','id'=>0])?>">More</a></li>
              </ul>
          </div>
      </div>
 </div>
</div>
<div id="bread">
    <img src="<?=Yii::$app->params['web_img_path'].'index.jpg'?>">
    <span id="topp"></span><span>
        <a href="<?=Url::toRoute(['index/index']);?>">
         <?=Yii::$app->params['bread_name']?>
        </a>
        <?php
          /*面包屑输出*/
          if(isset($this->params['bread'])){
              echo  $this->params['bread'];
          }
        ?>
    </span>
</div>
<!--header*****end****-->

<!--content*****start-->
<div id="content">
    <div id="left_content" class="fl">
        <?=$content?>
    </div>
    <div id="right_list" class="fl">
        <!--search- start->
<!--        <div class="search">-->
<!--              <form action="" method="post">-->
<!--                <input type="text" name="key_words" class="key_words" value="" placeholder="输入,你所想...">-->
<!--                <input type="submit" value="搜索" class="search_button">-->
<!--              </form>-->
<!--        </div>-->
        <!--search- end->
      <!--文章更新显示列表****start-->
<!--        <div class="update_list">-->
<!--            <div class="lately_update"><h2>最近更新</h2></div>-->
<!--            <div class="update_list_article">-->
<!--                <div class="pic fl"></div>-->
<!--                <div class="title_list fl"><a href="###">白话解析：一致性哈希算法 consistent hashing</a></div>-->
<!--            </div>-->
<!--            <div class="update_list_article">-->
<!--                <div class="pic fl"></div>-->
<!--                <div class="title_list fl"><a href="###">白话解析：一致性哈希算法 consistent hashing</a></div>-->
<!--            </div>-->
<!--            <div class="update_list_article">-->
<!--                <div class="pic fl"></div>-->
<!--                <div class="title_list fl"><a href="###">白话解析：一致性哈希算法 consistent hashing</a></div>-->
<!--            </div>-->
<!--        </div>-->
        <!--文章更新显示列表****end-->
        <!--评论、热门文章、留言排行榜区-->
        <div class="comment_hot_message_area">
              <ul>
                  <li>最近评论</li>
                  <li>热评文章</li>
              </ul>
        </div>
        <!--对应li的三个切换div-->
        <div class="change_tab selected">
            <!--评论-****start---->
            <div class="zhanwei"></div>
            <!--获取最近评论-->
            <?php
              $data=Comment::find()
                  ->select('comment_id,commener,comment_content,article_id')
                  ->where('article_id >=0 and comment_time<:time',[':time'=>time()])
                  ->asArray()
                  //article_id，comment_time是复合索引，一起升序或者降序。
                  ->orderBy('article_id desc,comment_time desc')
                  ->limit(10)
                  ->all();
              foreach ($data as $v){
            ?>
            <div class="line_leave_message">
                <span class="username"><?=$v['commener']?>:</span>
                <a href="<?=Url::toRoute(['showarticle/details','article_id'=>$v['article_id']]).'#'.$v['comment_id']?>" target="_blank"><span class="leave_message"><?=$v['comment_content']?></span></a>
            </div>
            <?php
                  }
              ?>
            <!--评论-****end---->
        </div>

        <!--热评文章-****start-->
        <div class="change_tab">
            <div class="zhanwei"></div>
            <?php
             //获取文章评论和标题
            $data=Article::find()
                ->select('article_id,title,opinion_num')
                ->orderBy(['opinion_num'=>SORT_DESC])
                ->limit(10)
                ->asArray()
                ->all();
            foreach ($data as $v){
            ?>
            <div class="line_hot_comment">
                <span title="<?=$v['title']?>">
                    <a href="<?=Url::toRoute(['showarticle/details','article_id'=>$v['article_id']])?>" target="_blank"><?=$v['title']?></a>
                </span>
                <span class="browse">(<?=$v['opinion_num']?>评论)</span>
            </div>
            <?php
                }
            ?>
        </div>
        <!--热评文章-****end-->
    </div>
</div>
<!--content*****end-->

<div id="footer">
    <p>©<span id="footer_year"></span> qingyun.com 版权所有</p>
    <p>网络文化经营许可证：浙网文[1b2cfd]65d-shd1c号</p>
</div>
</body>
</html>
