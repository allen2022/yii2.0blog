<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/9
 * Time: 14:44
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div id="seek_pass">
    <h2>新密码设置</h2>
<?php
  $form=ActiveForm::begin([
      'id'=>'seekpass',
      'method'=>'post',
      'fieldConfig'=>[
          'template'=>'<div class="label_get_pass">{label}</div><div>{input}{error}</div>'
      ]
  ])
?>
  <?=$form->field($model,'admin_passwd')    ->passwordInput(['placeholder'=>"请输入新密码",'class'=>'input_pass'])?>
  <?=$form->field($model,'repeatpwd') ->passwordInput(['placeholder'=>"请再次输入密码",'class'=>'input_pass'])?>
  <?=Html::submitInput('提交',['class'=>"btn_seekpass"])?>
<?php ActiveForm::end();?>
</div>
