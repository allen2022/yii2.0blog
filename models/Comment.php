<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/13
 * Time: 9:36
 */
namespace app\models;
use yii\db\ActiveRecord;

class Comment extends ActiveRecord{

    public $authors; //评论的谁的文章
    public $title;   //用于消息提醒评论了哪篇文章的标题
    public static function tableName(){
           return "{{%comment}}";
    }
    public function rules()
    {
        /*此处是ajax验证，控制器$Model->getValidators()[0]->message获取错误信息，无法识别{attribute}，所以只能分开写。*/
        /*article_id,commener是隐藏字段不显示错误，如果有人更改说明是有问题的！*/
        return [
            ['comment_content','required','message'=>'评论内容必须填写','on'=>['comment']],
            ['article_id','required','message'=>'页面加载有错,请刷新页面','on'=>['comment']],
            ['commener','required','message'=>'页面加载有错,请刷新页面','on'=>['comment']],
            ['title','required','message'=>'页面加载有错,请刷新页面','on'=>['comment']],
            ['authors','required','message'=>'页面加载有错,请刷新页面','on'=>['comment']],

        ];
    }
    public function attributeLabels(){
          return [

          ];
    }
}
?>