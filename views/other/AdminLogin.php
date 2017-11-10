<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\helpers\Url;
?>
<div id="admin_login">
    <h3>Admin用户登录</h3>
    <div class="login_form">
          <?php
            $form=ActiveForm::begin([
                'id'=>'admin_login_form',
                'method'=>'post',
                'fieldConfig'=>[
                    'template'=>'<div class="label_admin_login">{label}</div>{input}{error}'
                ],
            ])
          ?>
             <?=$form->field($model,'admin_email')->textInput(['class'=>'admin_login_input'])?>
             <?=$form->field($model,'admin_passwd')     ->passwordInput(['id'=>'admin_login_pass','class'=>'admin_login_input','onpaste'=>'return false'])?>
        <?= $form->field($model, 'checkcode'
        )->widget(Captcha::className(),[
            'options' => ['placeholder' => '请输入验证码','id'=>'checkcode','ajax-check'=>$ajaxcheck],///*ajax验证验证码*/
            'captchaAction'=>'other/captcha',
            'template' => "<div class='input_img'>{input}{image}</div>",
            //验证码配置
            'imageOptions' =>
                [
                    'title' => '点击刷新',
                    'id' => 'code',
                    /*ajax换图片链接，默认为空。*/
                    'src' => '',
                    /*ajax刷新验证码路径*/
                    'base-path'=>$basepath,
                    /*data-api标签路径ajax无刷新更换验证码使用*/
                    'data-api' =>  Url::toRoute(['other/captcha']),
                ],
        ])->label(false); ?>
             <?=Html::submitInput('登录',['class'=>"submit_disable"])?>
             <div class="submit_error"></div>
          <?php
            ActiveForm::end();
          ?>
          <div class="forget_add"><span><a href="<?=Url::toRoute(['other/forgetpass'])?>">忘记密码?</a></span><span><a href="<?=Url::toRoute(['other/adminuser'])?>">加入admin用户组</a></span></div>
    </div>
</div>