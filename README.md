框架:Yii2.0(LNMP) web端：html、css、jquery、ajax. 服务器端:php、mysql、redis.
*********************************************************************
blog项目没有全部完成,部分功能如下: 1.用户注册 2.邮件发送修改密码 3.发布文章 4.评论文章、回复评论 6.点赞 7.消息提示

主要目录文件作用: 
*********************************************************************
1.component：缓存组件类Classify.php(文件缓存)、Datacache.php(文件缓存)、Yiiredis.php(redis缓存).

2.controllers：ShowarticleController.php(博客详情,点赞,评论)、OtherController.php(admin用户注册,登陆,找回密码,显示文章分类,用户分“普通用户”和“admin用户”,友情链接申请),CustomerController.php(普通用户注册与登陆).

3.controllers/admin：ArticleController.php(发布修改博客文章,显示文章列表),PersonController.php(文章被评论后提示消息,个人中心功能尚未完善),ClassifyController.php(添加文章分类与选择分类).

4.models：目录下的所有文件对应数据表表名,主要验证字段数据.

5.views：目录下的目录名对应控制器名,控制器render加载html页面.

6.web：css,js,uplodimg目录css样式,js文件.

7.runtime：文件缓存(blog主要是文件缓存和redis缓存配合使用).

8.config：params.php(公用变量)web.php(Yii组件注册文件).

9.commontool：CommonTool.php(工具文件,redis调用,密码加密,提示信息,导入js,css文件等),CrontabTask.php(linux下crontab定时执,从reids中读取数据存入mysql,并且删除redis数据,减轻mysql压力).
*********************************************************************
博客地址:http://121.54.189.68/blog/web/index.php?r=index%2Findex     (香港云主机,访问速度有些不稳定,如果慢请稍等会，建议使用火狐或者chrome打开,其他浏览器没有做测试,可能会出现兼容性问题).
*********************************************************************
admin用户:测试账户：789@qq.com,密码:123456789(admin用户可以发布文章).
*********************************************************************
普通用户:测试账户：654321@qq.com,密码:123456
