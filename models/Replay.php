<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/17
 * Time: 13:23
 */
namespace app\models;
use yii\db\ActiveRecord;
class Replay extends ActiveRecord{

    public static function tableName()
    {
       return "{{%replay}}";
    }
    public function rules()
    {
        return [
          [['replay_content'],'string','min'=>'11','message'=>'评论不少于11个字符或请刷新页面再评论','on'=>['replay','replay_replayer']],
          [['replayr_who','article_id','comment_id'],'required','message'=>"",'on'=>['replay']],
          [['parent_replay_id','article_id'],'integer','on'=>['replay_replayer']],
          ['replayr_who','safe','on'=>['replay_replayer']],
        ];
    }
}