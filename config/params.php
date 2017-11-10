<?php
return [
    'web_title'=>'共享博客',
    'bread_name'=>'博客首页',
    /*密码盐*/
    'salt'=>'9%123#@$Q+-P?ZALLENaDp.O7a++7WSalld!!**',
    /*cookie盐*/
    'cookieSalt'=>'1%^^23@$#!&*POSzxss&!985ss+-SSSww',
    //页面显示作者等级
    'level_arr'=>['5'=>'Saber','4'=>'Archer','3'=>'Lancer','2'=>'Rider','1'=>'Caster','0'=>'Assassin'],
    //消息提示缓存数据keyname
    'messageInfo_key_cache_name'=>'messageInfo_key_cache_name',
    //css路径
    'web_css_path'=>'/blog/web/css/',
    //js路径
    'web_js_path'=>'/blog/web/js/',
    //图片路径
    'web_img_path'=>'/blog/web/uploadImg/',
    //cookie_name
    'blog_cookie_name'=>'blog_cookie_name',
    //session_name
    'admin_session_name'=>'admin_session_name',
    /***************************************/
    /************
     * Yii中redis的keyname配置.
     * 1.Yii的keyname为了避免重复发生冲突，取名应该查询是否存在。
     * 2.所有的keyname前加上操作redis的方法，比如是hmset操作keyname，keyname取HMSET_$keyname。
     * 3.操作方法后的字母需要小写，如HMSET_$keyname。
     * ----start*----
     **************/
     /*查询文章ID是否存在*/
     'SADD_article_id_exist'                                  =>'SADD_article_id_exist',
     /*储存用户名和邮箱,用于验证邮箱是否存在*/
     'SADD_customer_name_exist'                               =>'SADD_customer_name_exist',
     'SADD_customer_email_exist'                              =>'SADD_customer_email_exist',
     /*提示用户有多少消息，比如文章评论或者系统消息*/
     'HSET_message_to_user_total_num'                         =>'HSET_message_to_user_total_num',
     /*储存文章被哪些用户点击了*/
     'HSET_article_id_agree_noagree_exits_username'           =>'HSET_article_id_agree_noagree_exits_username',
     /*储存文章的"有帮助"或者"没帮助"的total_number*/
     'HSET_article_id_agree_total_num'                        =>'HSET_article_id_agree_total_num',
     'HSET_article_id_no_agree_total_num'                     =>'HSET_article_id_no_agree_total_num',
     /*redis中ajax查找最新插入数据的标记*/
     'HMSET_comment_last_article_id'                          =>'HMSET_comment_last_article_id',
     'STRING_comment_last_id_exist_time'                      =>'SETEX_comment_last_id_exist_time'


   /************
    * Yii中redis的keyname配置.
    * ----end*----
    **************/
    /***************************************/
];
