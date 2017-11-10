<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/13
 * Time: 10:00
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div id="form_php">
    <h2>普通用户注册</h2>
    <?php
    $form=ActiveForm::begin([
        'method'=>'post',
        'fieldConfig'=>[
            'template'=>'<div>{label}</div><div>{input}{error}</div>'
        ]
    ])
    ?>
    <?=$form->field($model,'cus_name')   ->textInput(['maxlength'=>'8','class'=>'form_input','placeholder'=>'不超过八个字'])?>
    <?=$form->field($model,'cus_email')  ->textInput(['maxlength'=>'32','class'=>'form_input','placeholder'=>'请填写常用邮箱'])?>
    <?=$form->field($model,'cus_passwd') ->passwordInput(['class'=>'form_input','placeholder'=>'只填一次,请勿忘记'])?>
    <?=$form->field($model,'ajaxurl')    ->hiddenInput(['value'=>$ajaxurl,'class'=>'ajaxurl'])->label(false)?>
    <?=Html::submitInput('注册',['class'=>'form_input_sub'])?>
    <?php
    ActiveForm::end();
    ?>
</div>