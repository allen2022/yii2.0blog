<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/11
 * Time: 18:01
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<!--评论、浏览数字显示-->
<div id="seek_comment">
    <span>评论:<?=$model['opinion_num']?></span>
    <span>等级:<?=$model['level_name']?></span>
    <span>作者:<au id="article_authors"><?=$model['authors']?></au></span>
</div>
<!--标题-->
<div id="title">
    <h2 class="title_h2"><?=$model['title']?></h2>
</div>
<hr>
<!--文章内容-->
<div id="article_content">
    <?=$model['content']?>
</div>
<!--点赞-->
<div id="feedback">
    <?php
      //如果用户名已经存在article_id集合中，说明已经点击“有帮助或者没帮助”，就显示点击的结果。
      if($result_article_id){
    ?>
         <!--已经点击-->
    <div class="agree">
        <span class="good">有帮助</span>
        <!--判断文章点击的总数是多少，为空这显示0-->
        (<span class="total_num_options"><?=$agree_total_num ? $agree_total_num:0?></span>)
    </div>
    <div class="noagree">
        <span class="bad">没帮助</span>
        <!--判断文章点击的总数是多少，为空这显示0-->
        (<span class="total_num_options"><?=$noagree_total_num ? $noagree_total_num:0?></span>)
    </div>
    <?php
      }else{
    ?>
          <!--未点击“有帮助或者没帮助”--star-->
    <div class="agree">
        <span class="good">有帮助</span>
        <span class="total_num_options">
            <!--判断文章点击的总数是多少，为空这显示0-->
            (<?=$agree_total_num ? $agree_total_num:0?>)
        </span>
    </div>
    <div class="noagree">
        <span class="bad">没帮助</span>
        <span class="total_num_options">
            <!--判断文章点击的总数是多少，为空这显示0-->
            (<?=$noagree_total_num ? $noagree_total_num:0?>)
        </span>
    </div>
          <!--未点击“有帮助或者没帮助”--end-->
    <?php
      }
    ?>
<!--    <div class="reward">test</div>-->
<!--    <div class="erweima"></div>-->
</div>
<!--发布评论-->
<div id="comment">
    <!--
    1.回复哪篇文章ID，
    2.回复评论文章用户的ID，
    3.parent_replay_id
    -->
    <!--文章评论-->
       <?php
             $cookie=Yii::$app->request->cookies;
             $session=Yii::$app->session->get(Yii::$app->params['admin_session_name']);
             //用户登陆后，显示评论框。
             if($cookie->has(Yii::$app->params['blog_cookie_name']) || !empty($session)){
                 echo '<h3>发表评论</h3>';
                 $form=ActiveForm::begin([
                     'id'=>'form-id',
                     'fieldConfig'=>[
                         'template'=>'<div>{input}{error}</div>'
                     ]
                 ]);
                 /*评论内容*/
                 echo $form->field($ModelComment,'comment_content')->textarea(['rows'=>8, 'cols'=>"100",'class'=>'comment_text','maxlength'=>100]);
                 echo "<div class='comment_btn'>评论</div>";
//                 echo Html::submitInput('提交',['class'=>'comment_btn']);
                 /*谁评论的？*/
                 if($cookie->has(Yii::$app->params['blog_cookie_name'])){
                     echo $form->field($ModelComment,'commener')   ->hiddenInput(['value'=>$cookie->get(Yii::$app->params['blog_cookie_name']),'class'=>'commener'])->label(false);
                 }else{
                     echo $form->field($ModelComment,'commener')   ->hiddenInput(['value'=>$session,'class'=>'commener'])->label(false);
                 }
                 /*评论了哪篇文章,文章id标志*/
                 echo $form->field($ModelComment,'article_id')     ->hiddenInput(['value'=>$atc_id,'class'=>'article_id'])->label(false);
                /*ajax发送评论路径*/
                 echo $form->field($ModelComment,'ajaxurl')        ->hiddenInput(['value'=>$ajax_url,'class'=>'ajaxurl'])->label(false);
                 ActiveForm::end();
             }else{
                 echo '<div id="reg_log_comment"><a href='.Url::toRoute(['customer/login']).' class="login">登录评论</a><a href='. Url::toRoute(['customer/reg']).' class="reg">注册</a></div>';

             }
              echo "<span class='ajax_path'>{$ajax_url}</span>";
              echo "<span class='ajax_atc_id'>{$atc_id}</span>";

       ?>
</div>
<!--评论列表-->
<div id="comment_list">
    <!--ajax加载html在span后-->
    <span id="flag"></span>
    <?php
    /*输出当前文章评论数据*/
    if(!empty($article_comment)){
          date_default_timezone_set("Asia/Shanghai");
          foreach ($article_comment as $v){
    ?>
    <div class="article_comment" id="<?='maowenben_id_'.$v['comment_id']?>">
        <div class="commener">
            <span class="username"><?=$v['commener']?></span>
            <span class="comment_time"><?=date("Y-m-d H:i:s A",$v['comment_time'])?></span>
            <span class="comment_id_span"><?=$v['comment_id']?></span>
            <!--锚文本flag,用于跳转到指定点-->
            <a name="<?=$v['comment_id']?>"></a>
        </div>
        <div class="comment_content" >
            <?=$v['comment_content']?>
            <div class="replay">回复</div>
            <!-----------------------------start-------------------------------->
            <!--回复评论显示-->
            <?php
              if(!empty($replay_comment)){
                  foreach ($replay_comment as $k=>$replay_v){
                      //如果“回复表”的commend_id等于“文章评论表”的comment_id,并且parentid也必须是0,就标识回复的是此文章评论
                      if($v['comment_id']==$replay_v['comment_id'] && $replay_v['parent_replay_id']==0){
            ?>
                    <div class="replay_list">
                            <!--回复者@回复谁-->
                           <span class="replayer"><?=$replay_v['replayer'];?></span><?=$replay_v['replay_content']?>
                           <div class="time_replaytext">
                                   <span class="time_show">
                                         <?php
                                         date_default_timezone_set("Asia/Shanghai");
                                         echo date("Y-m-d H:i:s",$replay_v['replay_time']);
                                         ?>
                                   </span>
                                   <span class="replay_replayer_btn">评论</span>
                           </div>
                        <!--回复评论框-->
                        <div class="replay_replayer_text">
                                <?php
                                  $form=ActiveForm::begin([
                                          'id'=>'form_replay_replayer',
                                          'fieldConfig'=>[
                                                  'template'=>'<div>{input}{error}</div>'
                                          ]
                                  ])
                                ?>
                                <!---回复评论--->
                                <?=$form->field($replay_model,'parent_replay_id')->textarea(['class'=>'form_replay_replayer','maxlength'=>80])?>
                                <?php
                                  ActiveForm::end();
                                ?>
                                 <!--提交回评按钮-->
                                <div class="replay_replayer_comment">
                                    <!--1.replay_id是回复replay表的哪条文章评论--->
                                    <span class="replay_id_list"><?=$replay_v['replay_id'];?></span>
                                    <!--2.回复谁?-->
                                    <span class="replayer_list"><?=$replay_v['replayer'];?></span>
                                    <span class="comment_replayer_btn">评论</span>
                                </div>
                        </div>

                <!-----------------------end------------------------------------->
                <!----------------------------------start------------------>
                <!--回复副评论列answer列出 parent_replay_id字段的回复内容-->
                <?php
                   foreach ($replay_comment as $k_answer=>$v_answer){
                          if($v_answer['parent_replay_id']==$replay_v['replay_id']){
                ?>
                        <div class="answer_replayer">
                            <div class="answer_replayer_content">
                                <span class="answer_replayer_class"><?=$v_answer['replayer']?></span><?=$v_answer['replay_content']?>
                            </div>
                            <div class="answer_replayer_time_replaytext">
                                <span class="answer_replayer_time">
                                    <?php
                                    echo  date('Y-m-d H:i:s',$v_answer['replay_time']);
                                    ?>
                                </span>
                                <span class="replay_answer">回复</span>
                            </div>
                        </div>
                        <div class="answer_replayer_text">
                            <?php
                            $form=ActiveForm::begin([
                                'fieldConfig'=>[
                                    'template'=>'<div>{input}{error}</div>'
                                ]
                            ])
                            ?>
                            <?=$form->field($replay_model,'parent_replay_id')->textarea(['class'=>'answer_replayer_textarea','maxlength'=>80])->label(false)?>
                            <?php
                            ActiveForm::end();
                            ?>
                            <div class="answer_replayer_comment">
                                <!--1.回复哪条评论的replay_id--->
                                <span class="answer_id_list replay_none"><?=$replay_v['replay_id'];?></span>
                                <!--2.回复谁?-->
                                <span class="answer_show_list replay_none"><?=$v_answer['replayer']?></span>
                                <span class="answer_replayer_btn">回复</span>
                            </div>
                        </div>
                              <?php } ?>
                      <?php  }  ?>
                <!----------------------------------end------------------>
            </div>
                   <?php
                      }
                  }
              }
                  ?>
        </div>
    </div>
    <!--回复评论-->
    <div id="replay_form">
                  <?php
                      $form=ActiveForm::begin([
                               'method'=>'post',
                               'id'=>'replay_form_input',
                               'fieldConfig'=>[
                                       'template'=>'<div>{input}{error}</div>'
                               ]
                      ])
                  ?>
         <!--回复文章评论者内容-->
         <?=$form->field($replay_model,'replay_content')->textarea(['class'=>'replay_text','maxlength'=>80])?>
         <?php ActiveForm::end()?>
         <div class="replay_btn fl">
            <!--1.获取文章ID-->
            <span class="replay_article_id replay_none"></span>
            <!--2.回复的是哪个文章评论用户ID-->
            <span class="replay_comment_id replay_none"></span>
            <!--3.回复谁？-->
            <span class="replay_article_comment replay_none"></span>
            <span class="replay_on">发表</span>
            <span class="replay_off">取消</span>
        </div>
    </div>
    <?php
          }
     }
    ?>
</div>

