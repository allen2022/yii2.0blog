<?php
 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
?>
<div id="what_admin">
    <h3>什么是admin用户组?</h3>
    <pre>
        博客用户分三组，General、Admin、Root。
        General：普通用户，无法进入后台，只能页面查看文章和留言回复。
        Admin：可以进入后台操作自己发布的文章（增删改查），其他的无权限。
        Root：网站管理员。
        申请不一定会通过,需审核。
    </pre>
    <hr>
    <h3>申请加入admin组</h3>
    <!--admin组的用户名和普通组的用户名不能一致。身份不能是普通用户和admin用户重叠-->
    <!--主要从事IT哪方面？申请邮箱，申请用户名-->
    <div id="form_apply_admin">
        <?php
           $form=ActiveForm::begin([
               'method'=>'post',
               'id'=>'admin_user_form',
               'fieldConfig'=>[
                   'template'=>"<div class='input_label'>{label}</div>{input}{error}"
               ]
           ]);
        ?>
        <?= $form->field($model,'nick_name')   ->textInput(['maxlength'=>8,'placeholder'=>"最多8个字符","class"=>"admin_user_input"]); ?>
        <?= $form->field($model,'admin_email') ->textInput(['maxlength'=>30,'placeholder'=>'最多30个字符请输入常用Email',"class"=>"admin_user_input"]);?>
        <?= $form->field($model,'admin_passwd')      ->passwordInput(['placeholder'=>"只输入一次密码请勿忘记","class"=>"admin_user_input"]);?>
        <?= $form->field($model,'profession')  ->textInput(['maxlength'=>25,'placeholder'=>"25个字符描述擅长IT哪方面？","class"=>"admin_user_input admin_profession "])?>
        <?= Html::submitInput('申请',['class'=>'submit_admin_user'])?>
        <?php ActiveForm::end(); ?>
    </div>
</div>