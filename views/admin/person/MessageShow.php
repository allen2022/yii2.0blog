<?php
use app\commontool\CommonTool;
use \yii\helpers\Url;
?>
<div id="messageshow">
    <?php
         //获取session
         $session=CommonTool::GetSessionOrCookie();
         //提示内容
         $info_content=[];
         //拼接redis中的keyname
         $keyname_arr =[];
         //获取新增文章评论ID集合
         $comment_id_set_array=CommonTool::ActRedisDataSET('one','smembers','SADD_CommentMessageTo_'.$session);
                 //获取储存在redis中的提示内容
                 if(!empty($comment_id_set_array)){
                      foreach ($comment_id_set_array as $v){
                           //拼接keyname
                           $keyname_arr[]='HMSET_CommentLastId='.$v.'_MessageTo_'.$session.'_content';
                     }
                           //读取提示内容
                      foreach ($keyname_arr as $value){
                         $info_content[]=CommonTool::ActRedisDataHASH('one','hVals',$value);
                                   //每读取一条数据就删除redis中对应的哈希key内容数据
                                   CommonTool::ActRedisDataDEL($value);
                     }

             //用户已经查看了消息提示，删除在redis中储存的集合ID数据，消息的提示总数量！
             CommonTool::ActRedisDataDEL('SADD_CommentMessageTo_'.$session);
         };
         //如果redis中的数据不为空
         if(!empty($info_content)){
               //用文件缓存从redis中读出的DataArray,缓存600秒，此时redis中数据清空，刷新页面提示就会消失，所以用文件缓存一会。
               //redis设置key的失效时间后数组就变成了字符串，无法读取数据，除非另外建一个时间flag的key，与其这样不如直接文件缓存。
             Yii::$app->Datacache->Datacache(Yii::$app->params['messageInfo_key_cache_name'].$session,'add',$info_content,600);
         }
            //获取缓存数据
         $CacheResultData=Yii::$app->Datacache->Datacache(Yii::$app->params['messageInfo_key_cache_name'].$session,'get');
         if(!empty($CacheResultData)){
             foreach ($CacheResultData as $v){
                 //0.评论的标题
                 //1.评论的ID，数据库新增ID $comment_last_id作为锚点使用
                 //2.谁评论的
                 //3.文章ID
                 /****href中的“#+评论id”作为文章评论锚点，跳转的时候直接跳转到评论内容的div****/
                 echo "<div>
                             <span class='commener'>$v[2]</span>评论您的文章<span class='title'>
                             <a href=".Url::toRoute(['showarticle/details','article_id'=>$v[3]]).'#'.$v[1]." target='_blank'>$v[0]</a>
                             </span>
                      </div>";
             }
         }
    ?>
</div>
