/**
 * Created by root on 2017/10/14.
 */
$(function () {
    /*
    * @param id_class (int)：验证字段的input框class或者id
    * @param ajaxurl_id (int)：表单隐藏字段value是当前页面的路径，不含控制。
    * @param controller (int)：php验证控制器
    * @param type_value (int)：验证类型，比如邮箱验证或者用户名验证
    * @param leng_th (int)：验证长度，当input框的值达到一定的长度就开始验证，避免频繁验证。
    * @param info (int):提示字段value数据库已经存在
    * */

    function AjaxNameEmail(id_class,ajaxurl_id,controller,type_value,leng_th,info) {
            /*获取input框长度*/
            var InputLength=$("#"+id_class).val().length;
            if(InputLength>leng_th){
                /*php控制器路径*/
                var AjaxUrl=$('#'+ajaxurl_id).val()+controller;
                /*获取input框value值*/
                var data=$("#"+id_class).val();
                $.ajax({
                    url:AjaxUrl,
                    /*如果是post需要带_csrf的value*/
                    type:'GET',
                    /*
                     check_field:验证数据库字段
                     typename:验证数据的类型是email还是username
                    * */
                    async:false,
                    data:{check_field:data,typename:type_value},
                    success:function (phpdata) {
                      if(phpdata==1){
                          $("#"+id_class).next('div.help-block').html(info).css('color','red');
                      }else{

                          $("#"+id_class).next('div.help-block').html('');
                      }
                    }
                })
            }
    }
    /*验证用户名是否存在*/
    $("#customer-cus_name").keyup(function () {
        AjaxNameEmail('customer-cus_name','customer-ajaxurl','customer/ajaxinput','name',0,'用户已经存在');
    });
    /*验证Email是否存在*/
    $("#customer-cus_email").keyup(function () {
        AjaxNameEmail('customer-cus_email','customer-ajaxurl','customer/ajaxinput','email',6,'Email已经存在');
    })

});