<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/11
 * Time: 14:02
 */
namespace app\controllers;
use app\models\Comment;
use app\models\Replay;
use yii\web\Controller;
use app\commontool\CommonTool;
use Yii;
use app\models\Article;
use yii\helpers\Url;
use yii\helpers\Html;

class ShowarticleController extends Controller{
       public $layout='header_footer';
       //文章详情
       public function actionDetails(){
           $Model = new Comment();
           $ReplayModel=new Replay();
           $Model->scenario='comment';
           /*获取文章ID*/
           $atc_id=(int)Yii::$app->request->get('article_id');
           //查询redis中是否存在此ID文章，不存在跳转到文章列表页面。
           $result=CommonTool::ActRedisDataSET('two','sismember',Yii::$app->params['SADD_article_id_exist'],$atc_id);
           //文章存在
           if($result){
                /*文章详情*/
                $Data=Article::find()
                    ->select('title,description,content,authors,class_name,opinion_num,level_name,agree,noagree,visit,create_time')
                    ->where(['article_id'=>$atc_id])
                    ->asArray()
                    ->one();
                CommonTool::web_css_js_bread('show_detail.css','show_detail.js','>>'.$Data['class_name'].'>>'.$Data['title']);

                /******php获取文章评论******/
               $article_comment=Comment::find()
                   ->select('comment_id,commener,comment_content,article_id,comment_time')
                   ->where(['article_id'=>$atc_id])
                   //orderby后只能跟commen_time,commen_time和article_id是复合索引,其他的字段mysql会产生filesort
                   ->orderBy(['comment_time'=>SORT_DESC])
                   ->asArray()
                   ->all();
                /*输入代码有js和html却不能过滤，如何防?*/
               //配置文件中数组存放等级,等级不变.直接使用.
               $Data['level_name']=Yii::$app->params['level_arr'][$Data['level_name']];

               /*****获取回复评论*******/
               //1.当前文章ID
               //2.回复的是哪篇文章评论ID
                $replay_comment=Replay::find()
                    ->select('replay_id,replayer,replayr_who,replay_content,parent_replay_id,comment_id,replay_time')
                    ->where(['article_id'=>$atc_id])
                    ->asArray()
                    ->all();
                /************查询用户是否点击过“有帮助”或者“没帮助”*************/
                $CookieSession=CommonTool::GetSessionOrCookie();
                //查询用户是否点击过“有帮助”或者“没帮助
                $redis_set_article_id=CommonTool::ActRedisDataHASH('two','hexists',Yii::$app->params['HSET_article_id_agree_noagree_exits_username'],$CookieSession.$atc_id);
                //获取文章“有帮助”总数
                $agree_total_num=CommonTool::ActRedisDataHASH('two','hget',Yii::$app->params['HSET_article_id_agree_total_num'],$atc_id);
                //获取文章“没帮助”总数
                $noagree_total_num=CommonTool::ActRedisDataHASH('two','hget',Yii::$app->params['HSET_article_id_no_agree_total_num'],$atc_id);
                return $this->render('Detail',
                   [
                       'model'              =>$Data,                               //文章详情数据
                       'ModelComment'       =>$Model,                              //文章评论表
                       'atc_id'             =>$atc_id,                             //文章ID
                       'ajax_url'           =>CommonTool::PathController(),        //ajax路径
                       'article_comment'    =>$article_comment,                    //文章评论数据
                       'replay_model'       =>$ReplayModel,                        //文章回复表
                       'replay_comment'     =>$replay_comment,                     //文章回复数据
                       'result_article_id'  =>$redis_set_article_id,               //查询用户是否点击过“有帮助”或者“没帮助
                       'agree_total_num'    =>$agree_total_num,                    //“有帮助”total_num
                       'noagree_total_num'  =>$noagree_total_num                   //“没帮助”total_num
                   ]
               );
           }else{
               return $this->redirect(Url::toRoute(['admin/article/listart']));
           }
       }
        /*ajax文章评论提交*/
       public function actionAjaxcomment(){
           $Model = new Comment();
           $Model->scenario='comment';
           //评论没有设置验证码，提交评论设置6秒后才能再评论写入数据库，ip作为标识。
           /*获取IP*/
           $ip=ip2long(Yii::$app->request->userIP);
//           /*ip如果存在key中则需要等待六秒才能评论*/
           if(CommonTool::ActRedisDataSTRING('one','exists',$ip)){
                 $send_js=['forb_id'=>'1','forbid_info'=>'亲休息下,等待六秒再评论!'];
                 return json_encode($send_js);
           }
           if($Model->load(Yii::$app->request->post()) && $Model->validate()){
                    $Model->comment_time=time();
                    if($Model->save(false)){
                        $comment_last_id=Yii::$app->db->getLastInsertID();
                        //评论$ip为redis的key并且设置过期时间6秒，6秒内不能连续评论
                        CommonTool::ActRedisDataSTRING('two','setex',$ip,'6','','TK');
                        //更改文章的主评论数量+1
                            Yii::$app->db
                            ->createCommand('UPDATE yii_article SET opinion_num=opinion_num+1 WHERE article_id=:article_id')
                            ->bindValue(":article_id",$Model->article_id)
                            ->execute();
                            /*******当文章有评论的时候需要在后台提示,提示信息数组储存在redis*********/
                            $MessageArr=[
                                $Model->title,      //0.评论的标题
                                $comment_last_id,   //1.评论的ID，数据库新增ID $comment_last_id作为锚点使用
                                $Model->commener,   //2.谁评论的，当前的cookie或者ssisson
                                $Model->article_id, //3.文章ID
                            ];
                            //评论文章，把评论的ID写入到集合中！
                            CommonTool::ActRedisDataSET('two','sadd','SADD_CommentMessageTo_'.$Model->authors,$comment_last_id);
                            //文章作者+最新评论ID作为key名,$MessageArr为value值
//                            CommonTool::ActRedisDataHASH('hmset','HMSET_CommentLastId='.$comment_last_id.'_MessageTo_'.$Model->authors.'_content',$MessageArr);
                            CommonTool::ActRedisDataHASH('two','hmset','HMSET_CommentLastId='.$comment_last_id.'_MessageTo_'.$Model->authors.'_content',$MessageArr);
                            //评论成功后，返回新增的ID给ajax，获取新增评论。也可以直接返回新增评论。
                            $send_js=['id'=>$comment_last_id,'success'=>'评论成功'];


                        return json_encode($send_js);

                   }
           }else{
                   /*$Model->getValidators()获取rules错误信息提示*/
                   return $Model->getValidators()[0]->message;
           }
      }
      /*ajax获取新增评论*/
      public function actionAjaxshowcomemnt(){
           $comment_id = (int)Yii::$app->request->get('comment_id');
           $atc_id     = (int)Yii::$app->request->get('article_id');
           $result=Comment::find()
               ->select('comment_id,commener,comment_content,comment_time,article_id')
               ->where(['comment_id'=>$comment_id,'article_id'=>$atc_id])
               ->asArray()
               ->one();
            date_default_timezone_set("Asia/Shanghai");
            $result['comment_time']=date("Y-m-d H:i:s A",$result['comment_time']);
            return json_encode($result);
      }
      /*ajax发送回复评论*/
      public function actionAjaxreplay(){
          $ReplayModel=new Replay();
          $ReplayModel->scenario='replay';
          /*当前cookie用户或者session*/
          $replayer_name=CommonTool::GetSessionOrCookie();
          if(empty($replayer_name)){
              //如果没有登录就不再执行,ajax提示。
               return false;
          }
          if($ReplayModel->load(Yii::$app->request->post()) && $ReplayModel->validate()){
              //回复评论者
              $ReplayModel->replayer=$replayer_name;
              //回复时间
              $ReplayModel->replay_time=time();
              if($ReplayModel->save(false)){
                  //获取最新回复ID
                  $last_replay_id=Yii::$app->db->getLastInsertID();
                  //redis以数组形式存储评论数据，ajax立刻获取此条数据实时显示在评论框下.也可以直接返回数组。
                  $replay_arr=
                      [
                          $replayer_name,
                          $ReplayModel->replay_content,
                          $ReplayModel->replayr_who,
                          $ReplayModel->replay_time
                      ];
                  //$last_replay_id.Yii::$app->params['HMSET_comment_last_article_id']
                  /*redis中ajax查找最新插入数据的标记*/
                  CommonTool::ActRedisDataHASH('two','hmset',$last_replay_id.Yii::$app->params['HMSET_comment_last_article_id'],$replay_arr);
                  //设置数据key储存时间6秒，ajax传送的$last_replay_id是否存在
                  CommonTool::ActRedisDataSTRING('two','setex',$last_replay_id.Yii::$app->params['STRING_comment_last_id_exist_time'],'6','','TK');
                  $info=['info_key'=>1,'info_value'=>'评论成功','last_id'=>$last_replay_id];
                  return json_encode($info);
              }else{
                  $info=['info_key'=>2,'info_value'=>'网络错误,评论失败.'];
                  return json_encode($info);
              }
          }else{
              $info=['info_key'=>0,'info_value'=>$ReplayModel->getValidators()[0]->message];
              return json_encode($info);
          }
      }
      //ajax发送评论回复
      public function actionAjaxcommentreplay(){
          //文章ID
          $atc_id=(int)Yii::$app->request->get('article_id');
          /*当前cookie用户或者session*/
          $replayer_name=CommonTool::GetSessionOrCookie();
          $ReplayModel=new Replay();
          $ReplayModel->scenario='replay_replayer';
          /****这里需要验证文章id是否存在******/
          $exist_atc_id_redis=CommonTool::ActRedisDataSET('two','sismember',Yii::$app->params['SADD_article_id_exist'],$atc_id);
          if($exist_atc_id_redis){
              /*
               * 假如get是这样的test1=xxx&test2=xxx那么$model-load(yii::$app->request->get(),'')要指定formname为空，
               * 假如searchForm[test1]=xxx&searchForm[test2]=xxx那么如果你的模型名一样 这样才可以不指定formname(formname是数据表名)
               * */
              if($ReplayModel->load(Yii::$app->request->get(),'')){
                   //回评者
                   $ReplayModel->replayer=$replayer_name;
                   $ReplayModel->replay_time=time();
                   //save没有false参数代表需要rules验证
                       if($ReplayModel->save()){
                          $json_arr=[
                               'info'=>1,
                               'success'=>[
                                   'replayer'=>$ReplayModel->replayer,                  //回复者
                                   'replay_time'=>$ReplayModel->replay_time,            //回复时间
                                   'replayr_who'=>$ReplayModel->replayr_who,            //回复谁
                                   'replay_content'=>$ReplayModel->replay_content       //回复内容
                               ]
                          ];
                          return json_encode($json_arr);
                       }else{
                           //数据未通过rules验证
                          return json_encode(['info'=>0,'error'=>$ReplayModel->getValidators()[0]->message]);
                       }
              }
          }else{
              return json_encode(['info'=>0,'error'=>'文章不存在']);
          }
      }
      //ajax返回新插入的评论
      public function actionAjaxgetreplay(){
                 $last_replay_id=(int)Yii::$app->request->get('last_insert_id');
                 //如果最新插入的回复id存储存在
                 if(CommonTool::ActRedisDataSTRING('one','exists',$last_replay_id.Yii::$app->params['STRING_comment_last_id_exist_time'])){
                      date_default_timezone_set("Asia/Shanghai");
                       //获取$last_id.'comment_last_id'value值，返回数组
                       $replay_redis=CommonTool::ActRedisDataHASH('one','hvals',$last_replay_id.Yii::$app->params['HMSET_comment_last_article_id']);
                       $replay_arr=[
                             'replayer'=>$replay_redis[0],
                             'replay_content'=>$replay_redis[1],
                             'replayr_who'=>$replay_redis[2],
                             'replay_time'=> date("Y-m-d H:i:s",$replay_redis[3])
                         ];
                      $arr=['result'=>1,'replay'=>$replay_arr];
                      //删除哈希值,刷新页面后显示的回复是在mysql中读取
                      CommonTool::ActRedisDataDEL($last_replay_id.Yii::$app->params['HMSET_comment_last_article_id']);
                      return json_encode($arr);
                 }else{
                      $arr=['result'=>0,'replay'=>'请刷新页面,重新评论'];
                      return json_encode($arr);
                 }

      }
      //ajax传送文章有帮助或者没帮助
      //crontab定时执行php脚本在凌晨将“有帮助”或者“没帮助”数量写入mysql.
      public function actionHelper(){
              //获取文章ID
              $article_id=(int)Yii::$app->request->get('article_id');
              //当前用户名
              $CookieSession=CommonTool::GetSessionOrCookie();
                      //文章id是否存在
              if(!CommonTool::ActRedisDataSET('two','sismember',Yii::$app->params['SADD_article_id_exist'],$article_id)){
                      //文章不存在不能点赞
                     return json_encode(['out_info'=>['num'=>'0','info_text'=>'亲,文章不存在!']]);
              }elseif(CommonTool::ActRedisDataHASH('two','hexists',Yii::$app->params['HSET_article_id_agree_noagree_exits_username'],$CookieSession.$article_id)){
                     //$CookieSession+$article_id如果存在哈希表中，说明此用户已经投票。
                     return json_encode(['out_info'=>['num'=>'0','info_text'=>'亲,您已经投票了!']]);
              }elseif(CommonTool::GetSessionOrCookie()==''){
                     return json_encode(['out_info'=>['num'=>'0','info_text'=>'亲,请先登录']]);
              }
              //获取ajax传输的options=1或者options=0（1代表有帮助,0表示没帮助）
              $option=(int)Yii::$app->request->get('options');
              //cookie或session存在且点击文本是有帮助
              if($option===1 && !empty($CookieSession)){
                    //有帮助+1
                  $this->RedisOption($article_id,$CookieSession,Yii::$app->params['HSET_article_id_agree_total_num']);
                    //获取文章“有帮助”总数量
                  $total_agree_num=CommonTool::ActRedisDataHASH('two','hget',Yii::$app->params['HSET_article_id_agree_total_num'],$article_id);
                  return json_encode(['out_info'=>['num'=>'1','info_text'=>'谢谢投票','total_num'=>$total_agree_num]]);
              }elseif($option===0 && !empty($CookieSession)){
                     //没帮助+1
                  $this->RedisOption($article_id,$CookieSession,Yii::$app->params['HSET_article_id_no_agree_total_num']);
                     //获取文章“没帮助”总数量
                  $total_no_agree_num=CommonTool::ActRedisDataHASH('two','hget',Yii::$app->params['HSET_article_id_no_agree_total_num'],$article_id);
                  return json_encode(['out_info'=>['num'=>'2','info_text'=>'谢谢投票','total_num'=>$total_no_agree_num]]);
              }
      }
      /*
       * @param article_id (string)：文章ID
       * @param CookieSession (string)：当前用户名
       * @param agree_OR_noagree_HASH_tbale_name (string)：哈希表名
       * */
      private function RedisOption($article_id,$CookieSession,$agree_OR_noagree_HASH_tbale_name){
          //文章被哪些用户点击了储存到哈希表。
          /*
           * @param  $CookieSession.$article_id (string)：keyname
           * */
          CommonTool::ActRedisDataHASH('three','hset',Yii::$app->params['HSET_article_id_agree_noagree_exits_username'],$CookieSession.$article_id,$article_id);
          //储存文章的"有帮助"或者"没帮助"的total_number
          /****判断redis的哈希值$agree_OR_noagree_HASH_tbale_name表是否有存在article_id值的key,存在则直接增加1，不存在则建立一个新的key****/
          if(!CommonTool::ActRedisDataHASH('two','hexists',$agree_OR_noagree_HASH_tbale_name,$article_id)){
               //如果不存在就创建,agree作为表名,$article_id作为字段，默认value值1
              CommonTool::ActRedisDataHASH('three','hset',$agree_OR_noagree_HASH_tbale_name,$article_id,1);
          }else{
               //$article_id的key值value值增加1
              CommonTool::ActRedisDataHASH('two','hincrby',$agree_OR_noagree_HASH_tbale_name,$article_id,1);
          }

      }

}