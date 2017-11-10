<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;

?>
<div id="friend_apply"><h2>友情链接申请</h2></div>
<div id="friend_form">
    <?php
       $form=ActiveForm::begin(
           [
               'method'=>'post',
               'id'=>'friend',
               'fieldConfig'=>['template'=>'<div class="label_tem">{label}</div><div class="friender">{input}{error}</div>']
           ]
       )
    ?>
    <?= $form->field($model,'title') ->textInput(['maxlength'=>10,'placeholder' =>"最多10个字符",'class'=>'friend_input'])?>
    <?= $form->field($model,'link')  ->textInput(['maxlength'=>25, 'placeholder'=>"最多25个字符",'class'=>'friend_input'])?>
    <?= $form->field($model, 'checkcode'
    )->widget(Captcha::className(),[
        'options' => ['placeholder' => '请输入验证码','id'=>'checkcode','ajax-check'=>$ajaxcheck],/*路径用于ajax验证码*/
        'captchaAction'=>'other/captcha',
        'template' => "<div class='input_img'>{input}{image}</div>",
        'imageOptions' =>
            [
                'title' => '点击刷新',
                'id' => 'code',
                /*ajax换图片链接，默认为空。*/
                'src' => '',
                /*用于ajax验证码*/
                'base-path'=>$basepath,
                /*data-api标签路径用于ajax无刷新更换验证码使用*/
                'data-api' =>  Url::toRoute(['other/captcha'])
            ],
    ])->label(false); ?>
    <?= Html::submitInput('提交',['class'=>'btn_friend'])?>
    <?php ActiveForm::end();?>
</div>

