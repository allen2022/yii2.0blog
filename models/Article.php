<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/9/30
 * Time: 15:00
 */
namespace app\models;
use yii\db\ActiveRecord;
use Yii;
class Article extends  ActiveRecord{
    public static function tableName()
    {
        return "{{%article}}";
    }
    public function attributeLabels(){
         return[
             'title'=>'',
             'description'=>"",
             'art_type'=>"",
             'content'=>'编辑器插入代码，请点击右边倒数第三个图标 >_ ，请注释系统软件版本，环境配置，插件，代码成功的结果等因素，非常感谢。'
         ];
    }
    public function rules()
    {
        return[
//             [['title','description'],'required','message'=>'{attribute}必须填写','on'=>['createart']]
                [['title','class_name'],'required','message'=>'标题必须填写','on'=>['createart']],
                ['description','required','message'=>'描述必须填写','on'=>['createart']],
                ['content','string','min'=>16,'tooShort'=>"文章内容长度必须大于16个字符",'on'=>['createart']],
                /*文章修改验证*/
                [['title'],'required','message'=>'标题必须填写','on'=>['updateart']],
                ['description','required','message'=>'描述必须填写','on'=>['updateart']],
                ['content','string','min'=>16,'tooShort'=>"文章内容长度必须大于16个字符",'on'=>['updateart']]
        ];
    }
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->create_time=time();
            $this->authors=Yii::$app->session->get('admin_session_name');

        }
        return true;
    }

}