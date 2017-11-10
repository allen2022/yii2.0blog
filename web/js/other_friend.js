/**
 * Created by root on 2017/9/25.
 */
$(function () {
    var base_parh;            //ajax验证码控制器路径
    var code_img_dir;         //验证码图片路径
    /*点击input框后边框改变颜色*/
    $(".friend_input").click(function () {
        $(this).removeClass().addClass('change_input_border').mouseleave(function () {
            $(this).removeClass().addClass('friend_input');
        });
    });
    /*ajax刷新验证码*/
    $("#code").click(function () {
        /*图片路径*/
        code_img_dir=$(this).attr('data-api');
        /*网站域名*/
        base_parh=$(this).attr('base-path');
        /*YII2的验证码路径带有refresh默认为ajax刷新*/
        var url=base_parh+code_img_dir+'&refresh';
        /*注意TYPE以get方式传送，post方式需要带上表单中隐藏的_csrf，或者控制器init()中关闭_csrf验证（不建议）*/
        /*如下
        * var csrfToken = $('meta[name="csrf-token"]').attr("content");
        * data: {_csrf:csrfToken},
        * */
        $.ajax({
            url:url,
            type:'GET',
            async:false,
            success:function (data) {
                /*src在表单中默认是空，用服务器返回的验证码图片路径替换*/
                $("#code").attr('src',base_parh+data['url']);
            }
        })
    });
    /*ajax不提交表单直接验证code*/
    /*如果用ajax验证需要去m重写YII的验证码方法*/
    $("#checkcode").keyup(function () {
        var code=$(this).val();
        if(code.length>5){
            /*验证码类路径*/
            var ajaxcheck=$(this).attr('ajax-check')+'other/checkcode';
            $.ajax({
                url:ajaxcheck,
                data:{"checkcode":code},
                /**注意TYPE以get方式传送，post方式需要带上_csrf，或者控制器public function init(){}中关闭_csrf验证**/
                type:'GET',
                success:function (data) {
                    /*php返回文本*/
                    if(data=="checkcode_success"){
                        $('.input_img').next().html('<span class="color_success">验证码正确</span>');
                        $('.btn_friend').css('background','#28a745');
                    }else if(data=='checkcode_fail'){
                        $('.input_img').next().html('<span class="color_fail">验证码错误</span>');
                        $('.btn_friend').css('background','#ccc');
                    }
                }
            });
        }else{
            $('.input_img').next().html('<span></span>');
            $('.btn_friend').css('background','#28a745');
        }
    });

    /*input_img的label标签不显示,占位置了*/
    $('.input_img').parent().prev().css('display','none');
    /*如果span标签class有color_fail表示验证码错误，无法提交表单*/
    $('.btn_friend').click(function () {
        if($('.input_img').next().children().hasClass('color_fail')){
             alert('警告验证码错误！！！');
             return false;
        }else{
            /*如果有span标签class有color_success验证码错误，无法提交表单*/
            if(!($('.input_img').next().children().hasClass('color_success'))){
                alert('请刷新页面');
                return false;
            }
        }

    });
});
