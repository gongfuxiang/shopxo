## jQuery plugin to create Range Selector

![jRange Preview](http://i.imgur.com/da8uZwx.png)

[Demo and Documentation](http://nitinhayaran.github.io/jRange/demo/)

以下是一个表格，展示了jRange插件的配置选项：

选项  是否必需    类型  默认值 描述
from    是   Integer 无   滑块的下限
to  是   Integer 无   滑块的上限
step    否   Integer 1   每次移动的步长
scale   否   Array   [from, to]  显示在滑块下方的标签数组
showLabels  否   Boolean true    是否显示滑块顶部的标签
showScale   否   Boolean true    是否显示滑块下方的刻度尺
format  否   String / Function   "%s"    指针上标签的格式
width   否   Integer 300 滑块容器的宽度
theme   否   String  "theme-green"   容器的CSS类名，可用的主题包括"theme-blue", "theme-green"
isRange 否   Boolean false   是否为范围选择器，如果是，则隐藏输入的值将设置为逗号分隔的形式，例如"25,75"
snap    否   Boolean false   是否将滑块吸附到步长值上
disable 否   Boolean false   是否禁用（只读）范围选择器

要使用此插件，请按照以下步骤操作：

在HTML文件中包含jquery.range.js和jquery.range.css文件。
html
<link rel="stylesheet" href="jquery.range.css">
<script src="jquery.range.js"></script>
在您希望显示滑块的位置添加一个隐藏的输入元素。
html
<input type="hidden" class="slider-input" value="23" />
使用jRange方法初始化插件，并传递所需的选项。
javascript
$('.slider-input').jRange({
    from: 1,
    to: 100,
    step: 1,
    // 其他选项...
});


Modification
Change values on runtime
Methods which you can call to dynamically modify current values and range.

Method          Description
setValue            
sets the current value of the slider without changing its range, if you want to update the range as well use updateRange instead.

$('.slider').jRange('setValue', '10,20');
$('.slider').jRange('setValue', '10');
updateRange         
'updateRange' to change (min, max) value and interval after initialized.

$('.slider').jRange('updateRange', '0,100');
$('.slider').jRange('updateRange', '0,100', '25,50');
$('.slider').jRange('updateRange', '0,100', 25);
passing second parameter also sets its current value