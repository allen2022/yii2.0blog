admin�û���
create table yii_adminuser(
adminuser_id int auto_increment not null primary key,
nick_name char(8) not null UNIQUE comment'admin�û��ǳ�Ψһ',
admin_email char(30) not null UNIQUE comment'admin�û���¼����Ψһ',
admin_passwd char(32) not null  comment '��¼����',
profession char(25) not null comment '�������ó�ʲô',
article smallint unsigned not null   comment'�����������������ο�',
total_agree   smallint unsigned not null   comment '���������������ο�',
total_noagree smallint unsigned not null comment '����û�а���������,̫�����Ϊ��ͨ�û�,�����ͽ����ο�',
total_visit   MEDIUMINT unsigned not null comment '�����������',
level_name tinyint not null default '6' comment '�ȼ�����߼�����1��Ĭ�ϳ���6',
check_apply tinyint not null default '0' comment '�������user,0�����,1��ͨ��',
reg_time int unsigned not null comment'ע��ʱ��',
ip int unsigned not null comment'ע��ʱip',
zhifubaocode char(80) not null default '' comment '֧������ά��',
weixincode   char(80) not null default '' comment '΢�Ŷ�ά��', 
index(admin_passwd),
index idx_article_check_apply(article,check_apply)
)engine=myisam charset=utf8;



����blog��
create table yii_article(
article_id  int not null auto_increment primary key,
title char(45) not null comment '���±���',
description char(135) not null comment '����������������ץȡ',
content text not null comment'��������',
authors  char(8) not null comment '����',
class_name char(15) not null comment '���·���',
opinion_num  smallint unsigned not null default'0' comment '������������',
level_name  tinyint not null comment'���ߵȼ�',
agree smallint unsigned not null default'0' comment '��������',
noagree smallint unsigned not null default'0' comment 'û�а���',
visit  mediumint unsigned  not null default'0' comment '�����',
create_time int not null comment '��������ʱ��',
che_ck tinyint not null default'0' comment'���0�������,1ͨ��,2δͨ��',
index idx_authors_che_ck(authors,che_ck),
index (create_time)
)engine=innodb charset=utf8;


�����
create table yii_createclass(
class_id tinyint unsigned not null auto_increment primary key,
class_name char(15) not null comment '������',
inventor char(8) not null comment '������',
create_time int not null comment 'ʱ��',
unique(class_name)
)engine=innodb charset=utf8;


�����۱�A�û���������Ϊ�����ۣ�
create table yii_comment(
comment_id int unsigned not null auto_increment primary key,
commener char(8) not null default '' comment'������',
comment_content varchar(100) not null default '' comment '��������',
article_id int unsigned not null default '0'comment '��������ƪ����',
comment_time int  unsigned not null default '0' comment '����ʱ��',
index idx_article_id_article_id(article_id,comment_time)
)engine=myisam charset=utf8;

�����۱��ظ�A�û����۵�Ϊ�����ۣ�
(
�ظ������������������ֶ�
1.�ظ���ƪ����ID��
2.�ظ����������û���ID��
3.parent_replay_id 
)
create table yii_replay(
replay_id int unsigned not null auto_increment primary key,
replayer  char(8) not null default '' comment'�ظ���',
replayr_who char(8) not null default '' comment '�ظ�˭',
article_id int not null default '0' comment '��ƪ�����»ظ���',
comment_id int not null default '0' comment '�ظ�������ƪ��������ID',
replay_content char(80)  not null default '' comment '�ظ�����',
parent_replay_id int unsigned not null default '0' comment '�ظ���������id,�����0���������������',
replay_time int unsigned not null default '0' comment 'ʱ��',
index(article_id)
)engine=innodb charset=utf8;


parent_replay_id int unsigned not null default '0' comment '�ظ���������id,�����0���������������',

��ͨ�û���  //name��email�����ظ����û�ע������ݴ�����redis����֤��redis�С�
create table yii_customer(
customer_id int auto_increment primary key,
cus_name char(8)  not null default '',
cus_email char(32)  not null default '',
cus_passwd char(32) not null default '',
reg_time int not null default '0',
index idx_cus_email_cus_passwd(cus_email,cus_passwd)
)engine=myisam charset=utf8;

�������ӱ�:
create table yii_friend(
friend_id int auto_increment not null primary key,
title char(10) not null comment'����',
link  char(25) not null comment'��������'
)engine=myisam charset=utf8;

�û��ȼ����ݱ�
create table yii_adminlevel(
level_id int auto_increment not null primary key,
level_name char(12) not null comment'�ȼ�����',
index(level_name)
)engine=myisam charset=utf8;

