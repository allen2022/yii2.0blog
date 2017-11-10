/**
 * Created by root on 2017/10/12.
 */

$(function () {

    //文章作者查看评论跳转到指点的锚点，改变背景颜色。
    /*start*/
    var maoweben_id=window.location.hash.substr(1);
    $("#maowenben_id_"+maoweben_id).css('background','#66afe9');
    setTimeout(function () {
        //4秒后设置背景颜色white
        $("#maowenben_id_"+maoweben_id).css('background','white');
    },4000);
    /*end*/
    //输入内容时文本边框改变颜色
    $('.comment_text').click(function () {
        $(this).addClass('border_text');
    });

    //文章标题(用于评论提示)
    var title=$('.title_h2').html();
    //文章作者(用于评论提示)
    var article_authors=$("#article_authors").html();
    //文章id
    var atc_id  = $('.article_id').val();
    //控制器路径
    var ajax_controller = $('.ajax_path').html();

    /*ajax文章评论*/
    $('.comment_btn').click(function () {
        //评论者
        var atc_commener = $(".commener").val();
        //评论内容
        var content_text = $('.comment_text').val();
        //post方式发送数据需要获取csrf
        var csrfToken = $('#form-id :input[name="_csrf"]').val();
        $.ajax({
            url:ajax_controller+'showarticle/ajaxcomment',
            type:'POST',
            /*以post传数据,字段格式必须和表单name的格式一致，如：name=Comment[commener],不然Model类无法获取post数据*/
            data:
            {
                /*没有csrf字段就无法post数据*/
                "_csrf":csrfToken,
                "Comment[commener]":atc_commener,
                "Comment[article_id]":atc_id,
                "Comment[comment_content]":content_text,
                "Comment[title]":title,
                "Comment[authors]":article_authors
            },
            success:function (data) {
                         /*字符串转js对象*/
                var js_obj=JSON.parse(data);
                     if(js_obj.success=='评论成功'){
                         $("#comment-comment_content").next('div .help-block').html(js_obj.success).css('color','green');
                         /*
                          * @param js_obj.id：评论表新插入的文章评论id
                          * @param ajax_controller：ajax获取数据方法的控制器
                          * @param atc_id：当前文章ID
                         * */
                         /*获取新增文章评论*/
                         ajax_get_comment(js_obj.id,ajax_controller,atc_id);
                     }else if(js_obj.forb_id==1){
                         /*禁止连续刷评论*/
                         $("#comment-comment_content").next('div .help-block').html(js_obj.forbid_info).css('color','red');
                     }else{
                         $("#comment-comment_content").next('div .help-block').html('网络出现问题，请重新刷新页面');
                     }
            }
        })
    });
    /*ajax获取最新插入的评论数据,ajax新增数据成功后执行此函数*/
   function ajax_get_comment(com_id,ajaxurl,atc_id) {
       $.ajax({
           url:ajaxurl+'showarticle/ajaxshowcomemnt',
           type:'GET',
           data:{comment_id:com_id,article_id:atc_id},
           success:function (data) {
              var js_obj=JSON.parse(data);
              /*显示新增评论*/
              var html='<div class="article_comment">'+
                  '<div class="commener"><span class="username">'+js_obj.commener+'</span><span class="comment_time">'+js_obj.comment_time+'</span></div>'
                   +'<div class="comment_content">'+js_obj.comment_content+'<span class="replay">回复</span></div>'
                   +'</div>';
              //时时显示新增评论
               $('#flag').after(html);
           }
       })
   }
   //评论回复标签显示，当鼠标移动到当前评论时就显示回复,离开就none
   $(".article_comment").mouseover(function () {
       $(this).children('.comment_content').children('div .replay').css('display','block');
   }).mouseleave(function () {
       $(this).children('.comment_content').children('div .replay').css('display','none');
   });

   $(".replay_list .time_replaytext").mouseover(function () {
       $(this).children('.replay_replayer_btn').show();
   }).mouseleave(function () {
       $(this).children('.replay_replayer_btn').hide();
   });
   //answer回复评论显示文本
   $(".answer_replayer").mouseover(function () {
       $(this).find('.replay_answer').show();
   }).mouseleave(function () {
       $(this).find('.replay_answer').hide();
   });

   //点击评论ajax发送评论
   $("#comment_list .replay").click(function () {
       /*@谁？*/
       var username =$(this).parents('.article_comment').find('.username').html();
       //回复评论表
       //1.获取文章ID
       var replay_article_id_var=$(".article_id").val();
       //2.回复的是哪个文章评论用户ID
       var replay_comment_id_var=$(this).parent().siblings('.commener').children('.comment_id_span').html();
       //3.回复谁？
       var replay_username_var=$(this).parent().siblings('.commener').children('.username').html();

       $(this).parents('.article_comment').next('div #replay_form').css('display','block');
       //赋值文章ID
       $(this).parents('.article_comment').next('div #replay_form').find('.replay_article_id').html(replay_article_id_var);
       //赋值文章评论用户ID
       $(this).parents('.article_comment').next('div #replay_form').find('.replay_comment_id').html(replay_comment_id_var);
       //赋值回复谁?
       $(this).parents('.article_comment').next('div #replay_form').find('.replay_article_comment').html(replay_username_var);

       $(this).parents('.article_comment').next('div #replay_form').find('.replay_text').html("@"+username+':');
   });
   /*回复评论框的取消*/
   $(".replay_off").click(function () {
       $(this).parents('#replay_form').css('display','none');
   });

   /*ajax回复评论*/
   $(".replay_on").click(function () {
       /*ajax调用this用于寻找help-block标签提示错误*/
       var this_replay_on_class=this;
       //控制器路径
       var ajax_controller = $('.ajaxurl').val();
       //文章ID
       var ajax_replay_article_id=$(this).siblings('.replay_article_id').html();
       //赋值文章评论用户ID
       var ajax_replay_comment_id=$(this).siblings('.replay_comment_id').html();
       //回复谁？
       var ajax_replay_article_comment=$(this).siblings('.replay_article_comment').html();
       //获取回复评论内容
       var ajax_replay_text =$(this).parent(".replay_btn").siblings("#replay_form_input").find('.replay_text').val();
       //_csrf
       var replay_csrf_Token=$('#replay_form_input :input[name="_csrf"]').val();
       //如果ajax_controller是undefined就代表没有登录。
       /*detail.php中只有cookie或者sssion存在才能加载评论列表，只有评论列表才有ajaxurl这个class*/
       if(ajax_controller === undefined){
           $(this).parents('#replay_form').find('.help-block').html('请先登录').css('color','red');
           return false;
       }
       $.ajax({
           url:ajax_controller+'showarticle/ajaxreplay',
           type:'POST',
           data:{
               '_csrf':replay_csrf_Token,
               'Replay[replay_content]':ajax_replay_text,
               'Replay[article_id]':ajax_replay_article_id,
               'Replay[comment_id]':ajax_replay_comment_id,
               'Replay[replayr_who]':ajax_replay_article_comment
           },
           success:function (data) {
               //字符串转对象
              var js_obj=JSON.parse(data);
              /*1代表评论成功*/
              if(js_obj.info_key==1){
                  $(this_replay_on_class).parents('#replay_form').find('.help-block').html(js_obj.info_value).css('color','green');
                  //调用ajax获取最新回复
                  /*
                  *
                  * @param js_obj.last_id：数据库最新插入的回复评论
                  * @param ajax_controller：当前页面的绝对路径
                  * @param this_replay_on_class：$(".replay_on")
                  * */
                  ajax_get_replay(js_obj.last_id,ajax_controller,this_replay_on_class);
                  //1.8秒后执行隐藏回复form
                  setTimeout(display_replay_form,1800);
                  function display_replay_form() {
                     $(this_replay_on_class).parents("#replay_form").hide(850);
                   }
              /*0代表评论数据有误，比如字符不够,model类验证不通过*/
              }else if(js_obj.info_key==0){
                   $(this_replay_on_class).parents('#replay_form').find('.help-block').html(js_obj.info_value).css('color','red');
              /*2代表评论失败，程序有错或者网络错误*/
              }else if(js_obj.info_key==2){
                   $(this_replay_on_class).parents('#replay_form').find('.help-block').html(js_obj.info_value).css('color','red');
              }
           }
       })
   });
   //ajax获取评论，从redis从获取，也可以不这么做。
   function ajax_get_replay(last_id,ajax_controller,this_replay_class) {
           $.ajax({
               url:ajax_controller+'showarticle/ajaxgetreplay',
               type:'GET',
               data:{last_insert_id:last_id},
               success:function (data) {
                    //转字符串
                   var replay_data=JSON.parse(data);
                   if(replay_data.result==1){
                       var replay_div='<div class="replay_list">'+replay_data.replay.replayer+'<span>'+replay_data.replay.replay_content+'</span></div>';
                       $(this_replay_class).parents('#replay_form').prev('.article_comment').find('div .replay').after(replay_div);
                   }else if(replay_data.result==0){
                       $(this_replay_class).parents('#replay_form').find('.help-block').html(replay_data.replay).css('color','red');
                   }
               }
           })
   }
   //回复评论显示文本
   $('#comment_list .replay_replayer_btn').click(function () {
       /*获取回复谁的评论*/
       var replay_comment = $(this).parents('.replay_list').find('.replayer').html();
       $(this).parents('.time_replaytext').siblings('.replay_replayer_text').toggle();
       //replay_comment 写入textarea
       $(this).parents('.time_replaytext').siblings('.replay_replayer_text').find('.form_replay_replayer').html("@"+replay_comment+"：");
   })
   //ajax回复评论
  $('.comment_replayer_btn').click(function () {
      //ajax使用element对象
      var this_comment_replayer=this;
      //回复谁?
      var replay_replayer=$(this).siblings(".replayer_list").html();
      //评论文章的用户ID
      var replay_replay_id=$(this).siblings(".replay_id_list").html();
      //回评内容
      var replay_text=$(this).parent('.replay_replayer_comment').prev("#form_replay_replayer").find('.form_replay_replayer').val();
      $.ajax({
          //php控制器路径
         url:ajax_controller+'showarticle/ajaxcommentreplay',
         type:'GET',
         data:
             {
                 //回复谁
                 replayr_who:replay_replayer,
                 //回复者的ID
                 parent_replay_id:replay_replay_id,
                 //文章ID
                 article_id:atc_id,
                 //回评内容
                 replay_content:replay_text
             },
         success:function (data) {
              var obj_data=JSON.parse(data);
              //1代表数据写入成功，返回刚刚写入的数据
              if(obj_data.info==1){
                  //时间戳转日期
                  var now_time= new Date(parseInt(obj_data.success.replay_time) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
                  //提示发布成功
                  $(this_comment_replayer).parents('.replay_replayer_comment').prev('#form_replay_replayer').find('.help-block').html('评论成功').css('color','green');
                    //html显示answer回复replay数据
                  var answer_text_html=
                      '<div class="answer_replayer answer_replayer_border">'
                      +'<div class="answer_replayer_content">'
                      +'<span class="answer_replayer_class">'+obj_data.success.replayer+'</span>'+obj_data.success.replay_content
                      +'</div>'
                      +'<div class="answer_replayer_time_replaytext  time_answertext_border">'
                      +'<span class="answer_replayer_time">'+now_time+'</span>'
                      +'<span class="replay_answer">回复</span>'
                      +'</div>'
                      +'</div>';
                  //append到html页面页面
                  $(this_comment_replayer).parents('.replay_replayer_text').after(answer_text_html);
                  //3秒内隐藏回复textarea
                  setTimeout(display_replay_form,1800);
                  function display_replay_form() {
                      $(this_comment_replayer).parents('.replay_replayer_text').hide(850);
                  }
                  //0代表数据写入失败，返回错误信息。
              }else if(obj_data.info==0){
                  $(this_comment_replayer).parents('.replay_replayer_comment').prev('#form_replay_replayer').find('.help-block').html(obj_data.error).css('color','red');
              }
         }
      })
  });
    //answer回复框显示
   $('.replay_answer').bind('click',function () {
       //回复谁?
       var answer_who=$(this).parents('.answer_replayer_time_replaytext').prev('.answer_replayer_content').find('.answer_replayer_class').html();
       $(this).parents(".answer_replayer").next('.answer_replayer_text').toggle();
       $(this).parents(".answer_replayer").next('.answer_replayer_text').find('.answer_replayer_textarea').html('@'+answer_who+'：');
   });
   //ajax发送回复
   $(".answer_replayer_btn").bind('click',function () {
       var this_answer_text=this;
       //获取回复谁?
       var answer_who=$(this).parents('.answer_replayer_comment').find('.answer_show_list').html();
       //回复哪个文章评论replay_id
       var answer_id=$(this).parents('.answer_replayer_comment').find('.answer_id_list').html();
       //回复评论
       var answer_content=$(this).parents('.answer_replayer_comment').prev('form').find(".answer_replayer_textarea").val();
       $.ajax({
           url:ajax_controller+'showarticle/ajaxcommentreplay',
           type:'GET',
           data:
               {
                   replayr_who:answer_who,
                   parent_replay_id:answer_id,
                   //文章ID
                   article_id:atc_id,
                   //回评内容
                   replay_content:answer_content
               },
           success:function (data) {
                        //字符串转对象
                      var obj_answer=JSON.parse(data);
                      //1表示写入数据成功
                      if(obj_answer.info==1){
                          //时间戳转日期
                          var now_time= new Date(parseInt(obj_answer.success.replay_time) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
                          $(this_answer_text).parents(".answer_replayer_comment").prev('form').find('.help-block').html('回复成功').css('color','green');
                          var answer_text_html=
                              '<div class="answer_replayer answer_replayer_border">'
                              +'<div class="answer_replayer_content">'
                              +'<span class="answer_replayer_class">'+obj_answer.success.replayer+'</span>'+obj_answer.success.replay_content
                              +'</div>'
                              +'<div class="answer_replayer_time_replaytext  time_answertext_border">'
                              +'<span class="answer_replayer_time">'+now_time+'</span>'
                              +'<span class="replay_answer">回复</span>'
                              +'</div>'
                              +'</div>';
                          //apeend到html页面
                          $(this_answer_text).parents('.answer_replayer_text').before(answer_text_html);
                            //3秒内隐藏回复textarea
                          setTimeout(display_replay_form,1800);
                          function display_replay_form() {
                              $(this_answer_text).parents('.answer_replayer_text').hide(850);
                          }
                      }else if(obj_answer.info==0){ //0表示写入数据失败
                          $(this_answer_text).parents(".answer_replayer_comment").prev('form').find('.help-block').html(obj_answer.error).css('color','red');
                      }
               }
       })
   });
   //点赞或者否认文章
   /*
   * @params ptions：有帮助或者没有帮助0或者1
   * */
   function agree_noagree(options,this_options_class) {
               //文章ID
             var ajax_atc_id=$('.ajax_atc_id').html();
              $.ajax({
                 url: $(".ajax_path").html()+'showarticle/helper',
                 type:'GET',
                 data:{article_id:ajax_atc_id,options:options},
                 success:function (data) {
                     var obj_data=JSON.parse(data);
                     //“有帮助”或“没帮助”classname
                     var obj_class_text= {
                         remove_class:'agree',
                         add_class:'optioned',
                         total_num_options:'total_num_options'
                     };
                    if(obj_data.out_info.num==1){
                        //1代表点击“有帮助”
                            obj_class_text.find_css_class='good';
                            ChangeOptionCss(this_options_class,obj_class_text,obj_data);
                    }else if(obj_data.out_info.num==2){
                        //2代表点击“没帮助”
                            obj_class_text.find_css_class='bad';
                            ChangeOptionCss(this_options_class,obj_class_text,obj_data);
                        //0错误信息输出
                    }else if(obj_data.out_info.num==0){
                            alert(obj_data.out_info.info_text);
                    }
                 }
              })
       }
       //点击“有帮助”class
   $(".agree").bind('click',function () {
       var this_options_class=this;
       agree_noagree(1,this_options_class);
   });
      //点击“没帮助”class
    $(".noagree").bind('click',function () {
        var this_options_class=this;
        agree_noagree(0,this_options_class);
    });
    /*
    *
    * @param this_class (obj)：this对象
    * @param obj_class_text (obj)：classname集合
    * @param obj_data (obj)：php返回json生成对象
    *
    * */
    //改变“有帮助或者没帮助”的按钮样式
   function ChangeOptionCss(this_class,obj_class_text,obj_data) {
       $(this_class).removeClass(obj_class_text.remove_class).addClass(obj_class_text.add_class);
       //输出提示信息
       $(this_class).find('.'+obj_class_text.find_css_class).html(obj_data.out_info.info_text);
       //输出“有帮助或没帮助”总数量
       $(this_class).find('.'+obj_class_text.total_num_options).html(obj_data.out_info.total_num);
   }
});