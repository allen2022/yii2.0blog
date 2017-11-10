<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin管理</title>
    <link href="<?=Yii::$app->params['web_css_path'].'cssReset.css'?>" rel="stylesheet">
    <link href="<?=Yii::$app->params['web_css_path'].'admin_header_footer.css'?>" rel="stylesheet">
    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
  <!--view下的html页面,加载控制器中的css-->
    <?php
       if(isset($this->params['css'])){
           $css=Yii::$app->params['web_css_path'].$this->params['css'];
           echo "<link href='{$css}' rel='stylesheet'>";
       }
    ?>
    <!--view下的html页面,加载控制器中的js-->
    <?php
       if(isset($this->params['js'])){
           $js=Yii::$app->params['web_js_path'].$this->params['js'];
           echo "<script src='{$js}'></script>";
       }
    ?>
</head>
<body>
<div id="header">
    <span class="bread">
         <a href="<?=Url::toRoute(['admin/admin/index'])?>">后台首页</a>
         <span>
             <!--面包屑-->
             <?php
             if(isset($this->params['bread']) && !empty($this->params['bread'])){
                 echo $this->params['bread'];
             }
             ?>
         </span>
    </span>

    <span class="username fr">
        您好:<?=Yii::$app->session->get(Yii::$app->params['admin_session_name'])?>
         <span class="message">
                 <?php
                       $session=Yii::$app->session->get(Yii::$app->params['admin_session_name']);
                       //获取message中的用户名的值
                       $result=\app\commontool\CommonTool::ActRedisDataSET('one','scard','SADD_CommentMessageTo_'.$session);
                       //如果message表中的用户名的value值是大于0就说明该用户有新的评论或者回复
                       echo $result? "<a href=".Url::toRoute(['admin/person/messageshow']).">消息</a>(".$result.")" : '消息(0)';
                 ?>

         </span>
         <span class="index"><a href="<?=Url::toRoute(['index/index'])?>">网站首页</a></span>
    </span>

</div>
<?=$content?>
</body>
</html>