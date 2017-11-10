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
    <h2>普通用户登录</h2>
    <?php
    $form=ActiveForm::begin([
        'method'=>'post',
        'id'=>"login-form",
        'fieldConfig'=>[
            'template'=>'<div>{label}</div><div>{input}{error}</div>'
        ]
    ])
    ?>
    <?=$form->field($model,'cus_email')  ->textInput(['class'=>'form_input'])?>
    <?=$form->field($model,'cus_passwd') ->passwordInput(['class'=>'form_input'])?>
    <?=Html::submitInput('登录',['class'=>'form_input_sub'])?>
    <?php
    ActiveForm::end();
    ?>
</div>