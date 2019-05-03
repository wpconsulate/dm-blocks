$ = jQuery.noConflict();
//        ace.require("ace/ext/language_tools");

if($('#dm_blocks_html_editor').length) {
    var HTMLeditor = ace.edit("dm_blocks_html_editor");
    HTMLeditor.getSession().setMode("ace/mode/html");
    HTMLeditor.setOptions({enableBasicAutocompletion: true, enableLiveAutocompletion: true});
    HTMLeditor.$blockScrolling = Infinity;
    HTMLeditor.getSession().setUseWrapMode(true);
    HTMLeditor.getSession().setValue($('#dm_block_html').val());
    HTMLeditor.getSession().on('change', function () {
        $('#dm_block_html').val(HTMLeditor.getValue());
    });

    $(".dm_blocks_html_editor_container").resizable({
        resize: function (event, ui) {
            HTMLeditor.resize();
        }
    });
}

if($('#dm_blocks_css_editor').length) {
    var CSSeditor = ace.edit("dm_blocks_css_editor");
    CSSeditor.getSession().setMode("ace/mode/css");
    CSSeditor.setOptions({enableBasicAutocompletion: true, enableLiveAutocompletion: true});
    CSSeditor.$blockScrolling = Infinity;
    CSSeditor.getSession().setUseWrapMode(true);
    CSSeditor.getSession().setValue($('#dm_block_css').val());
    CSSeditor.getSession().on('change', function () {
        $('#dm_block_css').val(CSSeditor.getValue());
    });

    $(".dm_blocks_css_editor_container").resizable({
        resize: function (event, ui) {
            CSSeditor.resize();
        }
    });
}

if($('#dm_blocks_js_editor').length) {
    var JSeditor = ace.edit("dm_blocks_js_editor");
    JSeditor.getSession().setMode("ace/mode/javascript");
//JSeditor.setTheme("ace/theme/dawn");
    JSeditor.setOptions({enableBasicAutocompletion: true, enableLiveAutocompletion: true});
    JSeditor.$blockScrolling = Infinity;
    JSeditor.getSession().setUseWrapMode(true);
    JSeditor.getSession().setValue($('#dm_block_js').val());
    JSeditor.getSession().on('change', function () {
        $('#dm_block_js').val(JSeditor.getValue());
    });

    $(".dm_blocks_js_editor_container").resizable({
        resize: function (event, ui) {
            JSeditor.resize();
        }
    });
}
$('#dm_blocks_enable_content .dm_block_tgl-btn').click(function(){
    $('#dm_blocks_enable_content .dm_block_show_post').click();
});
$('#dm_blocks_enable_content .dm_block_show_post').on('change', function(){
    if($('#dm_blocks_enable_content .dm_block_show_post').is(':checked')){
        $('#postdivrich').slideDown();
        $(window).scrollTop($(window).scrollTop() + 1);
        $(window).scrollTop($(window).scrollTop() - 1);
    } else {
        $('#postdivrich').slideUp();
    }
});

(function(b,c){var $=b.jQuery||b.Cowboy||(b.Cowboy={}),a;$.throttle=a=function(e,f,j,i){var h,d=0;if(typeof f!=="boolean"){i=j;j=f;f=c}function g(){var o=this,m=+new Date()-d,n=arguments;function l(){d=+new Date();j.apply(o,n)}function k(){h=c}if(i&&!h){l()}h&&clearTimeout(h);if(i===c&&m>e){l()}else{if(f!==true){h=setTimeout(i?k:l,i===c?e-m:e)}}}if($.guid){g.guid=j.guid=j.guid||$.guid++}return g};$.debounce=function(d,e,f){return f===c?a(d,e,false):a(d,f,e!==false)}})(this);

$(window).scroll($.debounce( 250, true, function(){
    $('.dm_block_mask').addClass('scrolling');
}));
$(window).scroll($.debounce( 250, function(){
    $('.dm_block_mask').removeClass('scrolling');
}));