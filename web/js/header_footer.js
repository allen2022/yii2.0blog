/**
 * Created by root on 2017/9/21.
 */
$(function () {
   /*tag标签移动背景颜色发生改变*/
   $(".top_tag ul li").mouseover(function () {
         $(this).addClass('top_tag_select').siblings().removeClass();
   });
   /*header_footer.php页面右边栏目，留言，评论，热门三个div切换*/
   $(".comment_hot_message_area ul li").mouseover(function () {
         /*获取当前是第几个li* */
         var index=$(this).index();
         $('.change_tab').each(function (k,v) {
            if(index==k){
                /*选择其他的li时，其他的li的背景颜色初始化*/
                $(".comment_hot_message_area ul li").eq(index).siblings().css('background','white');
                $(v).css('display','block');
                /*鼠标离开选中的li后改变背景颜色*/
                $(".comment_hot_message_area ul li").eq(index).css('background','deepskyblue');
            }else{
                $(v).css('display','none');
            }
         })
   });
   /*底部时间设置*/
    var str = new Date().getFullYear();
    $("#footer_year").html(str);
  //  /*获取滑动距离,右边栏宽度和高度暂时没有设置好*/
  // $(window).scroll(function (e) {
  //       var top=$(document).scrollTop();
  //       if(top>139){
  //           $("#right_list").css({"position":'fixed',"margin-left":"46%","margin-top":"-8%"})
  //       }else{
  //           $("#right_list").css({"margin-top":"0%"})
  //       }
  //   })
    /*form表单input键盘事件key_code_check*/

    /*
    * @param explain
    * #input_class:input框classname
    * #code_claass:验证码div的classname
    * #str_num:设置input框length
    * */
    /*******暂时不考虑******/
    // function key_code_check(input_class,code_class,str_num) {
    //     /*如果当前页面传入input框classname存在再执行代码*/
    //     if($("."+input_class).length>0){
    //             var displayer='';
    //             $("."+input_class).keyup(function () {
    //                 //标题或者网址最少有三位。
    //                 if( $(this).val().length>str_num){
    //                     /*如果大于3，是用键盘按下的字符，用ajax发送到php页面，改变php的flag，说明不是机器人,跳过验证码*/
    //                     /*写到验证码的时候再写*/
    //                     // $.ajax({
    //                     //     /*验证码页面*/
    //                     //     url:'codexxx.php',
    //                     //     data:{'length':str_lenght},
    //                     //     success:function (data) {
    //                     //         //返回block或者none;
    //                     //         display='block';
    //                     //     }
    //                     // });
    //                     displayer='block';
    //                     alert(displayer);
    //                 }
    //                 /*php无法获取val的length后显示验证码*/
    //                 /*1.屏蔽了js后无法获取值。2.未使用键盘输入。*/
    //                 if(displayer == 'block'){
    //                     $("."+code_class).css('display',displayer);
    //                 }
    //             });
    //     }
    // }
     // /*检查friend.php是否使用验证码验证*/
    //key_code_check('friend_input','field-friend-coder',3);
     // /*检查adminuser.php页面验证码*/
    //key_code_check('admin_user_input','code_admin_user',3);
    /*******end*****/
    /*验证码正确或者错误后submit按钮的变化*/
    /*
     *
     * @param prompt_class (string)：span标签class,php返回的验证错误提示.
     * @param sumit_class_name  (string):sumit的classname
     * @param back_color  (string):改变sumit的背景颜色
     * @param false_true  (string):返回真还是假，假则无法点击submit提交
     * */
    // function code_submit(prompt_class,sumit_class_name,back_color,false_true) {
    //     if($('.'+prompt_class)){
    //         $('.'+sumit_class_name).css('background',back_color).click(function () {
    //             return false_true;
    //         })
    //     }
    // }


});
