<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/9/30
 * Time: 15:09
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<!--富文本编辑器-->
<script src="<?=Yii::$app->params['web_js_path'].'Editor.min.js'?>"></script>
<div id="create_state">
    <h2>修改文章</h2>
    <hr>
</div>
<div id="create_article">
    <?php
      $form=ActiveForm::begin([
          'method'=>'post',
          'options'=>['class'=>'article_content'],
          'fieldConfig'=>[
              'template'=>'<div class="label_div">{label}</div><div>{input}{error}</div>'
          ]
      ])
    ?>
    <?=$form->field($model,'title')        ->textInput(['class'=>'article_input','maxlength'=>45,'placeholder'=>'标题45个字符.','value'=>$data['title']])?>
    <?=$form->field($model,'description')  ->textInput(['class'=>'article_input','maxlength'=>135,'placeholder'=>'描述您遇到的问题,或者贴出代码报错等其他关键词,方便搜索引擎抓取,135字符.','value'=>$data['description']])?>
    <?=$form->field($model,'content',['template'=>'<div class="label_div">{label}</div><div id="content_info">{input}{error}</div>'])->textarea(['id'=>'editor_textarea','rows'=>'3','name'=>"content",'value'=>$data['content']])?>
    <?=Html::submitInput('修改文章',['class'=>'create_article_btn'])?>
    <!--富文本编辑器id-->
    <div id="editor"></div>
    <?php ActiveForm::end(); ?>
</div>
