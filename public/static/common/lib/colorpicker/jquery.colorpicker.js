/**
 * jQuery插件：颜色拾取器
 * 
 * @author  Karson
 * @url     http://blog.iplaybus.com
 * @name    jquery.colorpicker.js
 * @since   2012-6-4 15:58:41
 */
(function($) {
    // 默认语言
    var lang = (window['lang_multilingual_default_code'] || 'zh-cn') == 'en' ? 'en' : 'zh-cn';
    var lang_list = {
        'zh-cn': {
            confirm: '确认',
            close: '关闭',
            clear: '清除'
        },
        'en': {
            confirm: 'confirm',
            close: 'close',
            clear: 'clear'
        }
    };
    var lang_data = lang_list[lang] || lang_list['zh-cn'];
    var ColorHex=new Array('00','33','66','99','CC','FF');
    var SpColorHex=new Array('FF0000','00FF00','0000FF','FFFF00','00FFFF','FF00FF');
    $.fn.colorpicker = function(options) {
        var opts = jQuery.extend({}, jQuery.fn.colorpicker.defaults, options);
        var index = parseInt(Math.random()*1000001);
        initColor();
        return this.each(function(){
            var obj = $(this);
            obj.bind(opts.event,function(){
                // 上、左距离、浮动类型处理
                var top_inc = parseInt($(this).data('top-inc')) || 0;
                var top_dec = parseInt($(this).data('top-dec')) || 0;
                var left_inc = parseInt($(this).data('left-inc')) || 0;
                var left_dec = $(this).data('left-inc') == undefined ? 1 : parseInt($(this).data('left-dec')) || 0;
                var position = $(this).data('position') || 'absolute';

                // 定位
                var ttop  = $(this).offset().top;  //控件的定位点高
                var thei  = $(this).outerHeight();  //控件本身的高
                var tleft = $(this).offset().left+$(this).outerWidth()-232;  //控件的定位点宽
                $('#colorpanel'+index).css({
                    "top":ttop+thei+top_inc-top_dec,
                    "left":tleft+left_inc-left_dec,
                    "position":position
                }).show();
                var target = opts.target ? $(opts.target) : obj;
                if(target.data('color') == null){
                    target.data('color',target.css('color'));
                }
                if(target.data('value') == null){
                    target.data('value',target.val());
                }
          
                $('#_creset'+index).bind('click',function(){
                    target.css('color', target.data('color')).val(target.data('value'));
                    $('#colorpanel'+index).hide();
                    opts.reset(obj);
                });

                $('#_determine'+index).bind('click',function(){
                    var color = $('#HexColor'+index).val();
                    target.css('color', color);
                    $('#colorpanel'+index).hide();
                    opts.success(obj,color);
                }).css({
                    "padding-left":"8px"
                });
          
                $('#CT'+index+' tr td').unbind('click').mouseover(function(){
                    var color=$(this).css("background-color");
                    $('#DisColor'+index).css("background",color);
                    $('#HexColor'+index).val($(this).attr("rel"));
                }).click(function(){
                    var color=$(this).attr("rel");
                    color = opts.ishex ? color : getRGBColor(color);
                    if(opts.fillcolor) target.val(color);
                    target.css('color',color);
                    $('#colorpanel'+index).hide();
                    $('#_creset'+index).unbind('click');
                    $('#_determine'+index).unbind('click');
                    opts.success(obj,color);
                });
          
            });
        });
    
        function initColor(){
            $('body').append('<div id="colorpanel'+index+'" style="position: absolute; display: none;z-index:10000;"></div>');
            var colorTable = '';
            var colorValue = '';
            for(i=0;i<2;i++){
                for(j=0;j<6;j++){
                    colorTable=colorTable+'<tr height=12>'
                    colorTable=colorTable+'<td width=11 rel="#000000" style="background-color:#000000">'
                    colorValue = i==0 ? ColorHex[j]+ColorHex[j]+ColorHex[j] : SpColorHex[j];
                    colorTable=colorTable+'<td width=11 rel="#'+colorValue+'" style="background-color:#'+colorValue+'">'
                    colorTable=colorTable+'<td width=11 rel="#000000" style="background-color:#000000">'
                    for (k=0;k<3;k++){
                        for (l=0;l<6;l++){
                            colorValue = ColorHex[k+i*3]+ColorHex[l]+ColorHex[j];
                            colorTable=colorTable+'<td width=11 rel="#'+colorValue+'" style="background-color:#'+colorValue+'">'
                        }
                    }
                }
            }
            colorTable='<table width=232 border="0" cellspacing="0" cellpadding="0" style="border:1px solid #333;height: 24px;line-height: 21px;">'
            +'<tr><td colspan=21 bgcolor=#cccccc>'
            +'<table cellpadding="0" cellspacing="1" border="0" style="border-collapse: collapse">'
            +'<tr><td width="3"><td><input type="text" data-is-clearout="0" id="DisColor'+index+'" size="3" disabled style="border:solid 1px #000000;background-color:#000000;padding:0;"></td>'
            +'<td width="3"><td><input type="text" data-is-clearout="0" id="HexColor'+index+'" style="border:inset 1px;font-family:Arial;width:58px;" value="#000000"><a href="javascript:void(0);" id="_determine'+index+'">'+lang_data.confirm+'</a> | <a href="javascript:void(0);" id="_cclose'+index+'">'+lang_data.close+'</a> | <a href="javascript:void(0);" id="_creset'+index+'">'+lang_data.clear+'</a></td></tr></table></td></table>'
            +'<table id="CT'+index+'" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse;border-color:#333;border-width:0 1px 1px 1px;border-style:solid;">'
            +colorTable+'</table>';
            $('#colorpanel'+index).html(colorTable);
            $('#_cclose'+index).on('click',function(){
                $('#colorpanel'+index).hide();
                $('#_creset'+index).unbind('click');
                $('#_determine'+index).unbind('click');
                return false;
            });
        }
        
        function getRGBColor(color) {
            var result;
            if ( color && color.constructor == Array && color.length == 3 )
                color = color;
            if (result = /rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(color))
                color = [parseInt(result[1]), parseInt(result[2]), parseInt(result[3])];
            if (result = /rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(color))
                color =[parseFloat(result[1])*2.55, parseFloat(result[2])*2.55, parseFloat(result[3])*2.55];
            if (result = /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(color))
                color =[parseInt(result[1],16), parseInt(result[2],16), parseInt(result[3],16)];
            if (result = /#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(color))
                color =[parseInt(result[1]+result[1],16), parseInt(result[2]+result[2],16), parseInt(result[3]+result[3],16)];
            return "rgb("+color[0]+","+color[1]+","+color[2]+")";
        }
    };
    jQuery.fn.colorpicker.defaults = {
        ishex : true, //是否使用16进制颜色值
        fillcolor:false,  //是否将颜色值填充至对象的val中
        target: null, //目标对象
        event: 'click', //颜色框显示的事件
        success:function(){}, //回调函数
        reset:function(){}
    };
})(jQuery);