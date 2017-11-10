/**
 * Created by root on 2017/9/27.
 */
$(function () {
     // $(".admin_login_btn").bind('click',function () {
     //     /*adminlogin.php页面密码框禁止黏贴，获取onpaste，查看是否有修改onpaste*/
     //     /*如果有修改就禁止提交*/
     //         var onpaste = $("#adminuser-passwd").attr('onpaste');
     //         if(onpaste!='return false'){
     //             /*onpaste='return false'，在html删除，再黏贴，然后添加上去，就可以点击提交，无法彻底防御*/
     //             $(".submit_error").html('页面被篡改,请按ctrl+F5刷新,或者重新打开页面');
     //             $(this).attr('disabled',"disabled");
     //             return false;
     //         }else{
     //             /*禁止用户点击后因网络延迟而多次点击submit*/
     //             $(this).val('正在处理...').attr('disabled',"disabled");
     //         }
     // })
     // /*监听密码框是否有键盘输入*/
     // $('#admin_login_pass').keyup(function () {
     //     if($(this).length>0){
     //         //有键盘输入代表非机器人,撤销disable。
     //         $(".submit_disable").removeAttr('disabled').removeClass('submit_disable').addClass('admin_login_btn');
     //     }
     // })
/*ajax刷新验证码*/
    $("#code").click(function () {
        //验证码刷新路径，路径带refresh，表示刷新服务器验证码
        var url=$(this).attr('data-api')+'&refresh';
        var base=$(this).attr('base-path');
        $.ajax({
            url:url,
            type:'GET',
            async:false,
            success:function (data) {
                //获取服务器更新的验证码路径，替换src的值。
                $("#code").attr('src',base+data['url']);
            }
        })
    });
/*ajax验证验证码*/
    $("#checkcode").keyup(function () {
        var code=$(this).val();
        if(code.length>5){
            /*验证码类路径*/
            var ajaxcheck=$(this).attr('ajax-check')+'other/checkcode';
            $.ajax({
                url:ajaxcheck,
                data:{"checkcode":code},
                /*注意TYPE以get方式传送，post方式需要带上_csrf，或者控制器init()中关闭_csrf验证*/
                type:'GET',
                async:false,
                success:function (data) {
                    /*php返回文本*/
                    if(data=="checkcode_success"){
                        $('.input_img').next().html('<span class="color_success">验证码正确</span>');
                        $('.submit_disable').css('background','#28a745');
                    }else if(data=='checkcode_fail'){
                        $('.input_img').next().html('<span class="color_fail">验证码错误</span>');
                        $('.submit_disable').css('background','#ccc');
                    }
                }
            });
        }else{
            $('.input_img').next().html('<span></span>');
            $('.submit_disable').css('background','#28a745');
        }
    });

    /*input_img的label标签不显示*/
    $('.input_img').prev().css('display','none');
    /*如果span标签class有color_fail验证码错误，无法提交表单*/
    $('.submit_disable').click(function () {
        if($('.input_img').next().children().hasClass('color_fail')){
            alert('警告验证码错误！！！');
            return false;
        }
    });
});