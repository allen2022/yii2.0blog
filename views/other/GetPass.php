<?php
 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
?>
<div id="getpass">
      <h2>找回密码</h2>
    <?php
      $form=ActiveForm::begin([
          'id'=>"get_pass_form",
          'method'=>'post',
          'fieldConfig'=>[
              'template'=>'<div class="label_get_pass">{label}</div><div>{input}{error}</div>'
          ]
      ])
    ?>
    <?=$form->field($model,'admin_email')->textInput(['maxlength'=>30,'placeholder'=>'注册邮箱','class'=>"get_pass_email"])?>
    <?=Html::submitInput('提交',['class'=>"submit_email"])?>
    <?php ActiveForm::end() ?>
</div>