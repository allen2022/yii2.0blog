<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/2
 * Time: 11:29
 */
namespace app\models;
use yii\db\ActiveRecord;
class Createclass extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%createclass}}";
    }

    public function rules()
    {
/*
 *        'skipOnEmpty'=>false 自定义验证rules需要加非空不能跳过验证，不是判断为空，则不需要加验证。
 *        [['inventor'],'validatename','skipOnError' => false, 'skipOnEmpty'=>false]
 * */
        return[
          ['class_name','ValidateClassname','skipOnEmpty'=>false,'on'=>['classify']],
          ['class_name','required','message'=>'类名必须填写','on'=>['addclass']],
          ['class_name','unique','message'=>'类名不能重复','on'=>['addclass']]
        ];
    }
    public function attributeLabels()
    {
        return[
            'class_name'=>'',
        ];
    }
    /*自定义验证select*/
    public function ValidateClassname($attribute){
        /*默认value为空，选择必须不是空*/
        if($this->$attribute===''){
            return $this->addError($attribute,'类必须选择');
        }
    }
}

?>