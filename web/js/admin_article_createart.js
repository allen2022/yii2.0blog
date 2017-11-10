/**
 * Created by root on 2017/9/30.
 */
$(function () {
    //富文本编辑器
    var E = window.wangEditor;
    var editor = new E('#editor');
    editor.create();
    /*点击发布文章时将div的content写入到textarea内容中，富文本编辑器是div*/
    $(".create_article_btn").click(function () {
        /*获取div的id=editor的内容*/
        var content=editor.txt.html();
        /*向隐藏的文本域中写入内容*/
        $("#editor_textarea").val(content);
    });
    //修改文章，将文章内容从textarea中获取
    var content=$("#editor_textarea").val();
    $(".w-e-text").html(content);

});
