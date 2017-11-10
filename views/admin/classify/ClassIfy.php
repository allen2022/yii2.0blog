<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div id="classify">
    <?php
     $form=ActiveForm::begin([
             'method'=>'post',
             'fieldConfig'=>[
                     'template'=>"<div>{label}</div><div class='select_input'>{input}{error}</div>"
             ]
     ])
    ?>
    <?=$form->field($model,'class_name')->dropDownList($classdata,['prompt'=>'请选择分类'])?>
    <?=Html::submitInput('确定',['class'=>'sure_btn'])?>
    <div class="add_class"><a href="<?=$path['add_class']?>">添加分类</a></div>
    <?php ActiveForm::end() ?>
</div>