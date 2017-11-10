<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div id="addClass">
    <h2>增加分类</h2>
    <?php
    $form=ActiveForm::begin([
        'method'=>'post',
        'fieldConfig'=>[
            'template'=>"<div>{label}</div><div>{input}{error}</div>"
        ]
    ])
    ?>
    <?=$form->field($model,'class_name')->textInput(['class'=>'add_class_input','placeholder'=>"类名不超过15个字符",'maxlength'=>15])?>
    <?=Html::submitInput('添加分类',['class'=>'add_btn'])?>
    <?php ActiveForm::end() ?>
</div>