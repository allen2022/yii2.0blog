<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/7
 * Time: 15:57
 */
namespace app\vendor\resetcode;
use Yii;
class Resetcode extends \yii\captcha\CaptchaAction{

    public  function validate($input, $caseSensitive)
    {
        $code = $this->getVerifyCode();
        $valid = $caseSensitive ? ($input === $code) : strcasecmp($input, $code) === 0;
        $session = Yii::$app->getSession();
        $session->open();
        $name = $this->getSessionKey() . 'count';
        $session[$name] = $session[$name] + 1;
          /*避免ajax验证错误后重写刷新服务器验证码*/
//        if ($valid || $session[$name] > $this->testLimit && $this->testLimit > 0) {
//            $this->getVerifyCode(true);
//        }
        return $valid;
    }
}

?>