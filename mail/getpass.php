<p>尊敬的admin用户:<?=$adminuser?></p>
<p>您找回密码连接如下:</p>
<?php
$url=Yii::$app->urlManager->createAbsoluteUrl(
    [
        'other/seekpass',
        'nowstamp'=>$time,
        'username'=>$adminuser,
        'token'=>$token
    ]
)
?>
<p><a href="<?=$url?>"><?=$url?></a></p>
<p>该邮件10分钟内有效，请勿传递他人！</p>
<p>该邮件系统发送，请勿回复</p>
