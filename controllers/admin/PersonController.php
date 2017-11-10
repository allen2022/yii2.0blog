<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/10/2
 * Time: 11:17
 */
namespace app\controllers\admin;
use app\models\Createclass;
use Yii;
use app\commontool\CommonTool;
use yii\web\Controller;

class PersonController extends  MonitorController{
    public $layout='admin_header_footer';

    //个人中心
    public function actionCenter(){
       CommonTool::web_css_js_bread('person_center.css','person_center.js','>>个人中心');
       return $this->render('Center');
    }
    //后台“消息(0)”被点击后如果是大于0的则消失
    public function actionMessageshow(){
        CommonTool::web_css_js_bread('person_messageshow.css','person_messageshow.js','>>消息显示');
        return $this->render('MessageShow');
    }
}


?>