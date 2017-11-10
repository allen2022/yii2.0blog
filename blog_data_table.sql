admin用户表
create table yii_adminuser(
adminuser_id int auto_increment not null primary key,
nick_name char(8) not null UNIQUE comment'admin用户昵称唯一',
admin_email char(30) not null UNIQUE comment'admin用户登录邮箱唯一',
admin_passwd char(32) not null  comment '登录密码',
profession char(25) not null comment '简单描述擅长什么',
article smallint unsigned not null   comment'发布文章数量升级参考',
total_agree   smallint unsigned not null   comment '点赞总数量升级参考',
total_noagree smallint unsigned not null comment '文章没有帮助总数量,太多则成为普通用户,升级和降级参考',
total_visit   MEDIUMINT unsigned not null comment '文章浏览总量',
level_name tinyint not null default '6' comment '等级，最高级别是1，默认初级6',
check_apply tinyint not null default '0' comment '审核申请user,0审核中,1是通过',
reg_time int unsigned not null comment'注册时间',
ip int unsigned not null comment'注册时ip',
zhifubaocode char(80) not null default '' comment '支付宝二维码',
weixincode   char(80) not null default '' comment '微信二维码', 
index(admin_passwd),
index idx_article_check_apply(article,check_apply)
)engine=myisam charset=utf8;



发布blog表
create table yii_article(
article_id  int not null auto_increment primary key,
title char(45) not null comment '文章标题',
description char(135) not null comment '文章描述搜索引擎抓取',
content text not null comment'文章内容',
authors  char(8) not null comment '作者',
class_name char(15) not null comment '文章分类',
opinion_num  smallint unsigned not null default'0' comment '文章评论数量',
level_name  tinyint not null comment'作者等级',
agree smallint unsigned not null default'0' comment '点赞数量',
noagree smallint unsigned not null default'0' comment '没有帮助',
visit  mediumint unsigned  not null default'0' comment '浏览量',
create_time int not null comment '发布文章时间',
che_ck tinyint not null default'0' comment'审核0正在审核,1通过,2未通过',
index idx_authors_che_ck(authors,che_ck),
index (create_time)
)engine=innodb charset=utf8;


分类表
create table yii_createclass(
class_id tinyint unsigned not null auto_increment primary key,
class_name char(15) not null comment '分类名',
inventor char(8) not null comment '创造者',
create_time int not null comment '时间',
unique(class_name)
)engine=innodb charset=utf8;


主评论表（A用户评论文章为主评论）
create table yii_comment(
comment_id int unsigned not null auto_increment primary key,
commener char(8) not null default '' comment'评论者',
comment_content varchar(100) not null default '' comment '评论内容',
article_id int unsigned not null default '0'comment '评论了哪篇文章',
comment_time int  unsigned not null default '0' comment '评论时间',
index idx_article_id_article_id(article_id,comment_time)
)engine=myisam charset=utf8;

副评论表（回复A用户评论的为副评论）
(
回复表单至少有三个隐藏字段
1.回复哪篇文章ID，
2.回复评论文章用户的ID，
3.parent_replay_id 
)
create table yii_replay(
replay_id int unsigned not null auto_increment primary key,
replayer  char(8) not null default '' comment'回复者',
replayr_who char(8) not null default '' comment '回复谁',
article_id int not null default '0' comment '哪篇文章下回复的',
comment_id int not null default '0' comment '回复的是哪篇文章评论ID',
replay_content char(80)  not null default '' comment '回复内容',
parent_replay_id int unsigned not null default '0' comment '回复副评论者id,如果是0代表的是主评论者',
replay_time int unsigned not null default '0' comment '时间',
index(article_id)
)engine=innodb charset=utf8;


parent_replay_id int unsigned not null default '0' comment '回复副评论者id,如果是0代表的是主评论者',

普通用户表  //name和email不能重复，用户注册后数据储存在redis，验证在redis中。
create table yii_customer(
customer_id int auto_increment primary key,
cus_name char(8)  not null default '',
cus_email char(32)  not null default '',
cus_passwd char(32) not null default '',
reg_time int not null default '0',
index idx_cus_email_cus_passwd(cus_email,cus_passwd)
)engine=myisam charset=utf8;

友情链接表:
create table yii_friend(
friend_id int auto_increment not null primary key,
title char(10) not null comment'标题',
link  char(25) not null comment'友情链接'
)engine=myisam charset=utf8;

用户等级数据表
create table yii_adminlevel(
level_id int auto_increment not null primary key,
level_name char(12) not null comment'等级名称',
index(level_name)
)engine=myisam charset=utf8;

