<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
?>
<div id="list_art">
    <table>
        <thead>
        <tr>
            <th class="tit">标题</th>
            <th>评论</th>
            <th>点赞</th>
            <th>没帮助</th>
            <th>分类</th>
            <th class="tim">发布时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
          foreach ($model as $k=>$v){
        ?>
        <tr>
            <td  class="tit">
                <a href="<?=\yii\helpers\Url::toRoute(['showarticle/details','article_id'=>$v['article_id']])?>">
                    <?=$v['title']?>
                </a>
            </td>
            <!--评论数-->
            <td><?=$v['opinion_num']?></td>
            <!--点赞数-->
            <td><?=$v['agree']?></td>
            <!--没帮助数-->
            <td class="noagree"><?=$v['noagree']?></td>
            <!--所属分类-->
            <td><?=$v['class_name']?></td>
            <!--发布时间-->
            <td class="tim"><?=$v['create_time']?></td>
            <td><a href="<?=Url::toRoute(['admin/article/updateart','atc_id'=>$v['article_id']])?>">修改</a></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?=LinkPager::widget
(
        [
                'pagination'=>$Page,
                'firstPageLabel' => '首页',
                'lastPageLabel'  => '尾页',
                'prevPageLabel'  => '上一页',
                'nextPageLabel'  => '下一页',

        ]
)
?>