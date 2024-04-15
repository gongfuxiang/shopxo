// url选择表单初始化
FromInit('form.form-validation-url-choice');

// url选择
var $url_event_obj = null;
var $url_choice_popup = $('#url-choice-popup');
// 商品搜索popup容器   
var $popup_themedata_goods = $('#themedata-goods-popup');
// 文章搜索popup容器   
var $popup_themedata_article = $('#themedata-article-popup');
// 公共弹窗和指定数据列表
var $manual_mode_data_container_tag = null;
var $manual_mode_data_container = $('ul.manual-mode-data-container');
var $data_type_auto_container = $('.data-type-auto-container');
var $data_type_appoint_container = $('.data-type-appoint-container');

// url确认回调处理
function ThemeDataUrlChoiceBackHandle(data)
{
    // 去除空值
    for(var i in data)
    {
        if(data[i] == '')
        {
            delete data[i];
        }
    }

    // 赋值数据给事件对象属性
    $url_event_obj.find('input').val(encodeURIComponent(CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(data)))));

    // 提示文字处理
    $url_event_obj.find('span').text(Object.keys(data).length == 0 ? $url_choice_popup.data('not-choice-text') : $url_choice_popup.data('already-choice-text'));

    // 关闭弹窗
    $url_choice_popup.modal('close');
}

$(function()
{
    // url选择事件
    $(document).on('click', '.url-choice-event', function()
    {
        // 记录临时事件对象
        $url_event_obj = $(this);

        // 先赋空值
        $url_choice_popup.find('input').val('');
        // 数据赋值
        var json = $url_event_obj.find('input').val() || null;
        if(json != null)
        {
            json = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8));
        }
        FormDataFill(json, $url_choice_popup);

        // 打开弹窗
        $url_choice_popup.modal();
    });

    // 多图文可拖拽初始化
    $('ul.manytextimages-content-container').dragsort({ dragSelector: 'a.drag-sort-submit', placeHolderTemplate: '<li class="drag-sort-dotted"></li>' });

    // 移除内容容器
    $(document).on('click', '.manytextimages-content-container > li .content-item-remove-submit', function()
    {
        $(this).parents('li').remove();
    });

    // 多图文添加
    $(document).on('click', '.manytextimages-content-add-submit', function()
    {
        var $obj = $('.manytextimages-content-container');
        var not_choice_text = $url_choice_popup.data('not-choice-text');
        var index = parseInt(Math.random() * 1000001);
        var html = `<li>
                <div class="am-panel am-panel-default am-radius">
                    <div class="am-panel-bd am-padding-lg">
                        <div class="content-item">
                            <!-- 图片 -->
                            <div class="images-row-container form-custom-group">
                                <div class="am-form-group am-form-file">
                                    <label class="am-block">`+$obj.data('form-item-images-icon')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <div class="am-form-file-upload-container">
                                            <ul class="plug-file-upload-view images-icon-view-`+index+`" data-form-name="data[`+index+`][images_icon][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                                <li class="plug-file-upload-submit" data-view-tag="ul.images-icon-view-`+index+`">
                                                    <i class="iconfont icon-upload-add"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                                            <input type="hidden" name="data[`+index+`][images_icon][url_data]" value="" />
                                            <span>`+not_choice_text+`</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="am-form-group am-form-file">
                                    <label class="am-block">`+$obj.data('form-item-images-active-icon')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <div class="am-form-file-upload-container">
                                            <ul class="plug-file-upload-view images-active_icon-view-`+index+`" data-form-name="data[`+index+`][images_active_icon][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                                <li class="plug-file-upload-submit" data-view-tag="ul.images-active_icon-view-`+index+`">
                                                    <i class="iconfont icon-upload-add"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                                            <input type="hidden" name="data[`+index+`][images_active_icon][url_data]" value="" />
                                            <span>`+not_choice_text+`</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="am-form-group am-form-file">
                                    <label class="am-block">`+$obj.data('form-item-images-shape')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <div class="am-form-file-upload-container">
                                            <ul class="plug-file-upload-view images-shape-view-`+index+`" data-form-name="data[`+index+`][images_shape][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                                <li class="plug-file-upload-submit" data-view-tag="ul.images-shape-view-`+index+`">
                                                    <i class="iconfont icon-upload-add"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                                            <input type="hidden" name="data[`+index+`][images_shape][url_data]" value="" />
                                            <span>`+not_choice_text+`</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="am-form-group am-form-file">
                                    <label class="am-block">`+$obj.data('form-item-images-cooperate')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <div class="am-form-file-upload-container">
                                            <ul class="plug-file-upload-view images-cooperate-view-`+index+`" data-form-name="data[`+index+`][images_cooperate][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                                <li class="plug-file-upload-submit" data-view-tag="ul.images-cooperate-view-`+index+`">
                                                    <i class="iconfont icon-upload-add"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                                            <input type="hidden" name="data[`+index+`][images_cooperate][url_data]" value="" />
                                            <span>`+not_choice_text+`</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="am-form-group am-form-file">
                                    <label class="am-block">`+$obj.data('form-item-images-title')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <div class="am-form-file-upload-container">
                                            <ul class="plug-file-upload-view images-title-view-`+index+`" data-form-name="data[`+index+`][images_title][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                                <li class="plug-file-upload-submit" data-view-tag="ul.images-title-view-`+index+`">
                                                    <i class="iconfont icon-upload-add"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                                            <input type="hidden" name="data[`+index+`][images_title][url_data]" value="" />
                                            <span>`+not_choice_text+`</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="am-form-group am-form-file">
                                    <label class="am-block">`+$obj.data('form-item-images-background')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <div class="am-form-file-upload-container">
                                            <ul class="plug-file-upload-view images-background-view-`+index+`" data-form-name="data[`+index+`][images_background][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                                <li class="plug-file-upload-submit" data-view-tag="ul.images-background-view-`+index+`">
                                                    <i class="iconfont icon-upload-add"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                                            <input type="hidden" name="data[`+index+`][images_background][url_data]" value="" />
                                            <span>`+not_choice_text+`</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="am-form-group am-form-file">
                                    <label class="am-block">`+$obj.data('form-item-images-detail-icon')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <div class="am-form-file-upload-container">
                                            <ul class="plug-file-upload-view images-detail_icon-view-`+index+`" data-form-name="data[`+index+`][images_detail_icon][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                                <li class="plug-file-upload-submit" data-view-tag="ul.images-detail_icon-view-`+index+`">
                                                    <i class="iconfont icon-upload-add"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                                            <input type="hidden" name="data[`+index+`][images_detail_icon][url_data]" value="" />
                                            <span>`+not_choice_text+`</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="am-form-group am-form-file">
                                    <label class="am-block">`+$obj.data('form-item-images-detail')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <div class="am-form-file-upload-container">
                                            <ul class="plug-file-upload-view images-detail-view-`+index+`" data-form-name="data[`+index+`][images_detail][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                                <li class="plug-file-upload-submit" data-view-tag="ul.images-detail-view-`+index+`">
                                                    <i class="iconfont icon-upload-add"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                                            <input type="hidden" name="data[`+index+`][images_detail][url_data]" value="" />
                                            <span>`+not_choice_text+`</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="am-form-group am-form-file">
                                    <label class="am-block">`+$obj.data('form-item-images-detail-title')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <div class="am-form-file-upload-container">
                                            <ul class="plug-file-upload-view images-detail_title-view-`+index+`" data-form-name="data[`+index+`][images_detail_title][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                                <li class="plug-file-upload-submit" data-view-tag="ul.images-detail_title-view-`+index+`">
                                                    <i class="iconfont icon-upload-add"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                                            <input type="hidden" name="data[`+index+`][images_detail_title][url_data]" value="" />
                                            <span>`+not_choice_text+`</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="am-form-group am-form-file">
                                    <label class="am-block">`+$obj.data('form-item-images-detail-background')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <div class="am-form-file-upload-container">
                                            <ul class="plug-file-upload-view images-detail_background-view-`+index+`" data-form-name="data[`+index+`][images_detail_background][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                                <li class="plug-file-upload-submit" data-view-tag="ul.images-detail_background-view-`+index+`">
                                                    <i class="iconfont icon-upload-add"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                                            <input type="hidden" name="data[`+index+`][images_detail_background][url_data]" value="" />
                                            <span>`+not_choice_text+`</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- 文本 -->
                            <div class="text-row-container form-custom-group">
                                <div class="am-form-group">
                                    <label>`+$obj.data('form-item-text-title')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <input type="text" name="data[`+index+`][text_title][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-title-message')+`" value="" />
                                        <span class="am-input-group-label am-radius">
                                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                                <input type="hidden" name="data[`+index+`][text_title][url_data]" value="" />
                                                <span>`+not_choice_text+`</span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label>`+$obj.data('form-item-text-vice-title')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <input type="text" name="data[`+index+`][text_vice_title][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-vice-title-message')+`" value="" />
                                        <span class="am-input-group-label am-radius">
                                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                                <input type="hidden" name="data[`+index+`][text_vice_title][url_data]" value="" />
                                                <span>`+not_choice_text+`</span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label>`+$obj.data('form-item-text-date')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <input type="text" name="data[`+index+`][text_date][value]" class="am-form-field am-radius Wdate" placeholder="`+$obj.data('form-item-text-date-message')+`" onclick="WdatePicker({firstDayOfWeek:1,dateFmt:'yyyy-MM-dd'})" />
                                        <span class="am-input-group-label am-radius">
                                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                                <input type="hidden" name="data[`+index+`][text_date][url_data]" value="" />
                                                <span>`+not_choice_text+`</span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label>`+$obj.data('form-item-text-more')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <input type="text" name="data[`+index+`][text_more][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-more-message')+`" value="" />
                                        <span class="am-input-group-label am-radius">
                                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                                <input type="hidden" name="data[`+index+`][text_more][url_data]" value="" />
                                                <span>`+not_choice_text+`</span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label>`+$obj.data('form-item-text-btn')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <input type="text" name="data[`+index+`][text_btn][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-btn-message')+`" value="" />
                                        <span class="am-input-group-label am-radius">
                                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                                <input type="hidden" name="data[`+index+`][text_btn][url_data]" value="" />
                                                <span>`+not_choice_text+`</span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label>`+$obj.data('form-item-text-describe')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <input type="text" name="data[`+index+`][text_describe][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-describe-message')+`" value="" />
                                        <span class="am-input-group-label am-radius">
                                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                                <input type="hidden" name="data[`+index+`][text_describe][url_data]" value="" />
                                                <span>`+not_choice_text+`</span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label>`+$obj.data('form-item-text-detail-describe')+`</label>
                                    <div class="am-input-group am-input-group-sm">
                                        <textarea name="data[`+index+`][text_detail_describe][value]" class="am-form-field am-radius" rows="3" placeholder="`+$obj.data('form-item-text-detail-describe-message')+`"></textarea>
                                        <span class="am-input-group-label am-radius">
                                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                                <input type="hidden" name="data[`+index+`][text_detail_describe][url_data]" value="" />
                                                <span>`+not_choice_text+`</span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-flex am-flex-items-center am-gap-32 content-item-operate">
                            <a href="javascript:;" class="am-text-xs am-text-danger content-item-remove-submit am-flex am-flex-items-center">
                                <i class="iconfont icon-delete"></i>
                                <span>`+(window['lang_operate_delete_name'] || '删除')+`</span>
                            </a>
                            <a href="javascript:;" class="am-text-xs drag-sort-submit am-flex am-flex-items-center">
                                <i class="iconfont icon-sort"></i>
                                <span>`+(window['lang_operate_sort_name'] || '排序')+`</span>
                            </a>
                        </div>
                    </div>
                </div>
            </li>`;
        $('.manytextimages-content-container').append(html);
    });
    // 空则自动添加一条
    if($('.manytextimages-content-add-submit').length > 0 && $('.manytextimages-content-container').length > 0 && $('.manytextimages-content-container li').length == 0)
    {
        $('.manytextimages-content-add-submit').trigger('click');
    }



    // 分页初始化
    if($('.popup-page-container').length > 0)
    {
        $('.popup-page-container').html(PageLibrary());
    }

    // 数据类型事件
    $(document).on('change', 'input[name="goods_data_type"], input[name="article_data_type"]', function()
    {
        if(parseInt($(this).val() || 0) == 0)
        {
            $data_type_auto_container.removeClass('am-hide');
            $data_type_appoint_container.addClass('am-hide');
        } else {
            $data_type_auto_container.addClass('am-hide');
            $data_type_appoint_container.removeClass('am-hide');
        }
    });

    // 指定数据列表拖拽
    $('ul.manual-mode-data-container').dragsort({ dragSelector: 'li', placeHolderTemplate: '<li class="drag-sort-dotted"></li>' });

    // 删除列表
    $(document).on('click', '.manual-mode-data-container li button.am-close', function () {
        $(this).parent('li').remove();
    });

    // 弹窗数据列表-搜索
    $(document).on('click', '.forth-selection-container .search-submit, .pagelibrary li a', function () {
        // 分页处理
        var is_active = $(this).data('is-active') || 0;
        if (is_active == 1) {
            return false;
        }
        var page = $(this).data('page') || 1;

        // 请求参数
        var url = $('.forth-selection-container').data('search-url');
        var category_id = $('.forth-selection-form-category').val();
        var keywords = $('.forth-selection-form-keywords').val();
        var data_ids = [];

        // 已选数据
        var $tag = ($manual_mode_data_container_tag == null) ? $manual_mode_data_container : $($manual_mode_data_container_tag);
        $tag.find('input[type="hidden"]').each(function (k, v) {
            data_ids.push($(this).val());
        });

        var $this = $(this);
        $.AMUI.progress.start();
        if ($this.hasClass('search-submit')) {
            $this.button('loading');
        }
        $('.forth-selection-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-spinner am-icon-pulse"></i> ' + ($('.forth-selection-list-container').data('loading-msg')) + '</div>');
        $.ajax({
            url: RequestUrlHandle(url),
            type: 'post',
            data: { "page": page, "category_id": category_id, "keywords": keywords, "data_ids": data_ids },
            dataType: 'json',
            success: function (res) {
                $.AMUI.progress.done();
                $this.button('reset');
                if (res.code == 0) {
                    $('.forth-selection-list-container').attr('data-is-init', 0);
                    $('.forth-selection-list-container ul.am-gallery').html(res.data.data);
                    $('.popup-page-container').html(PageLibrary(res.data.total, res.data.page_size, res.data.page, 4));
                } else {
                    Prompt(res.msg);
                    $('.forth-selection-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-warning"></i> ' + res.msg + '</div>');
                }
            },
            error: function (xhr, type) {
                $.AMUI.progress.done();
                $this.button('reset');
                var msg = HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误');
                Prompt(msg, null, 30);
                $('.forth-selection-list-container ul.am-gallery').html('<div class="table-no"><i class="am-icon-warning"></i> ' + msg + '</div>');
            }
        });
    });

    // 弹窗搜索列表-添加/删除
    $(document).on('click', '.forth-selection-list-container .data-add-submit, .forth-selection-list-container .data-del-submit', function () {
        // 基础参数
        var $this = $(this);
        var type = $this.data('type');
        var icon_html = $this.parents('li').data((type == 'add' ? 'del' : 'add') + '-html');
        var data_id = $this.parents('li').data('id');
        var data_title = $this.parents('li').data('title');
        var data_url = $this.parents('li').data('url');
        var data_img = $this.parents('li').data('img');
        var $tag = ($manual_mode_data_container_tag == null) ? $manual_mode_data_container : $($manual_mode_data_container_tag);
        var index = $tag.data('index') || '';
        if(index != '')
        {
            index = index+'-';
        }
        var form_name = $tag.attr('data-form-name') || '';
        // 商品是否已经添加
        if ($tag.find('.manual-mode-data-item-' + data_id).length > 0) {
            $tag.find('.manual-mode-data-item-' + data_id).remove();
        } else {
            var img_html = ((data_img || null) == null) ? '' : `<img src="`+data_img+`" class="am-border-c am-radius" width="35" height="35">`;
            $tag.append(`<li class="am-padding-left-sm manual-mode-data-item-`+data_id+`">
                    <input type="hidden" name="`+form_name+`[`+data_id+`][data_id]" value="`+data_id+`">
                    <ul class="plug-file-upload-view data-custom-cover-view-`+index+data_id+`" data-form-name="`+form_name+`[`+data_id+`][custom_cover]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                        <li class="plug-file-upload-submit" data-view-tag="ul.data-custom-cover-view-`+index+data_id+`">
                            <i class="iconfont icon-upload-add"></i>
                        </li>
                    </ul>
                    <a href="`+data_url+`" target="_blank" class="am-text-truncate am-flex am-flex-items-center am-gap-1">
                        `+img_html+`
                        <span class="am-flex-1 am-flex-width">`+data_title+`</span>
                    </a>
                    <button type="button" class="am-close am-fr">×</button>
                </li>`);
        }
        $this.parent().html(icon_html);
    });


    // 开启商品弹窗
    $(document).on('click', '.goods-popup-add', function () {
        // 记录当前元素位置
        $manual_mode_data_container_tag = $(this).data('tag') || null;
        // 初始化搜索数据
        $popup_themedata_goods.modal();
        $popup_themedata_goods.find('.search-submit').trigger('click');
    });


    // 开启文章弹窗
    $(document).on('click', '.article-popup-add', function () {
        // 记录当前元素位置
        $manual_mode_data_container_tag = $(this).data('tag') || null;
        // 初始化搜索数据
        $popup_themedata_article.modal();
        $popup_themedata_article.find('.search-submit').trigger('click');
    });








    // 自定义数据添加
    $(document).on('click', '.textimages-custom-add-submit', function()
    {
        var $obj = $('.textimages-custom-container');
        var form_name = $obj.data('form-name');
        var index = parseInt(Math.random() * 1000001);
        var not_choice_text = $url_choice_popup.data('not-choice-text');
        $obj.append(`<li>
                        <ul class="plug-file-upload-view images-icon-`+index+`-view" data-form-name="`+form_name+`[`+index+`][icon]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                            <li class="plug-file-upload-submit" data-view-tag="ul.images-icon-`+index+`-view">
                                <i class="iconfont icon-upload-add"></i>
                            </li>
                        </ul>
                        <input type="text" name="`+form_name+`[`+index+`][name]" placeholder="`+($obj.data('name-text') || '数据名称')+`" class="name am-radius" />
                        <input type="text" name="`+form_name+`[`+index+`][value]" placeholder="`+($obj.data('value-text') || '数据值')+`" class="value am-radius" />
                        <a href="javascript:;" class="am-text-blue am-block url-choice-event">
                            <input type="hidden" name="`+form_name+`[`+index+`][url_data]" value="" />
                            <span>`+not_choice_text+`</span>
                        </a>
                        <button type="button" class="am-close">×</button>
                    </li>`);
    });

    // 自定义数据移除
    $(document).on('click', '.textimages-custom-container > li > button.am-close', function()
    {
        $(this).parent().remove();
    });







    // 商品组可拖拽初始化
    $('ul.data-goods-container').dragsort({ dragSelector: 'a.drag-sort-submit', placeHolderTemplate: '<li class="drag-sort-dotted"></li>' });

    // 商品组合添加
    $(document).on('click', '.goodsgroup-content-add-submit', function()
    {
        var $obj = $('.data-goods-container');
        var not_choice_text = $url_choice_popup.data('not-choice-text');
        var index = parseInt(Math.random() * 1000001);
        var html = `<li class="data-goods-item-container">
                        <div class="am-panel am-panel-default am-radius">
                            <div class="am-panel-bd">`;

        // 图片
        html += `<!-- 图片 -->
            <div class="images-row-container form-custom-group">
                <div class="am-form-group am-form-file">
                    <label class="am-block">`+$obj.data('form-item-images-icon')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <div class="am-form-file-upload-container">
                            <ul class="plug-file-upload-view images-icon-view-`+index+`" data-form-name="data[`+index+`][images_icon][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                <li class="plug-file-upload-submit" data-view-tag="ul.images-icon-view-`+index+`">
                                    <i class="iconfont icon-upload-add"></i>
                                </li>
                            </ul>
                        </div>
                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                            <input type="hidden" name="data[`+index+`][images_icon][url_data]" value="" />
                            <span>`+not_choice_text+`</span>
                        </a>
                    </div>
                </div>
                <div class="am-form-group am-form-file">
                    <label class="am-block">`+$obj.data('form-item-images-active-icon')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <div class="am-form-file-upload-container">
                            <ul class="plug-file-upload-view images-active_icon-view-`+index+`" data-form-name="data[`+index+`][images_active_icon][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                <li class="plug-file-upload-submit" data-view-tag="ul.images-active_icon-view-`+index+`">
                                    <i class="iconfont icon-upload-add"></i>
                                </li>
                            </ul>
                        </div>
                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                            <input type="hidden" name="data[`+index+`][images_active_icon][url_data]" value="" />
                            <span>`+not_choice_text+`</span>
                        </a>
                    </div>
                </div>
                <div class="am-form-group am-form-file">
                    <label class="am-block">`+$obj.data('form-item-images-shape')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <div class="am-form-file-upload-container">
                            <ul class="plug-file-upload-view images-shape-view-`+index+`" data-form-name="data[`+index+`][images_shape][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                <li class="plug-file-upload-submit" data-view-tag="ul.images-shape-view-`+index+`">
                                    <i class="iconfont icon-upload-add"></i>
                                </li>
                            </ul>
                        </div>
                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                            <input type="hidden" name="data[`+index+`][images_shape][url_data]" value="" />
                            <span>`+not_choice_text+`</span>
                        </a>
                    </div>
                </div>
                <div class="am-form-group am-form-file">
                    <label class="am-block">`+$obj.data('form-item-images-background')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <div class="am-form-file-upload-container">
                            <ul class="plug-file-upload-view images-background-view-`+index+`" data-form-name="data[`+index+`][images_background][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                <li class="plug-file-upload-submit" data-view-tag="ul.images-background-view-`+index+`">
                                    <i class="iconfont icon-upload-add"></i>
                                </li>
                            </ul>
                        </div>
                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                            <input type="hidden" name="data[`+index+`][images_background][url_data]" value="" />
                            <span>`+not_choice_text+`</span>
                        </a>
                    </div>
                </div>
            </div>`;

        // 文本
        html += `<!-- 文本 -->
            <div class="text-row-container form-custom-group">
                <div class="am-form-group">
                    <label>`+$obj.data('form-item-text-title')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" name="data[`+index+`][text_title][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-title-message')+`" value="" />
                        <span class="am-input-group-label am-radius">
                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                <input type="hidden" name="data[`+index+`][text_title][url_data]" value="" />
                                <span>`+not_choice_text+`</span>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="am-form-group">
                    <label>`+$obj.data('form-item-text-vice-title')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" name="data[`+index+`][text_vice_title][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-vice-title-message')+`" value="" />
                        <span class="am-input-group-label am-radius">
                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                <input type="hidden" name="data[`+index+`][text_vice_title][url_data]" value="" />
                                <span>`+not_choice_text+`</span>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="am-form-group">
                    <label>`+$obj.data('form-item-text-more')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" name="data[`+index+`][text_more][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-more-message')+`" value="" />
                        <span class="am-input-group-label am-radius">
                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                <input type="hidden" name="data[`+index+`][text_more][url_data]" value="" />
                                <span>`+not_choice_text+`</span>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="am-form-group">
                    <label>`+$obj.data('form-item-text-btn')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" name="data[`+index+`][text_btn][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-btn-message')+`" value="" />
                        <span class="am-input-group-label am-radius">
                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                <input type="hidden" name="data[`+index+`][text_btn][url_data]" value="" />
                                <span>`+not_choice_text+`</span>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="am-form-group">
                    <label>`+$obj.data('form-item-text-describe')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" name="data[`+index+`][text_describe][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-describe-message')+`" value="" />
                        <span class="am-input-group-label am-radius">
                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                <input type="hidden" name="data[`+index+`][text_describe][url_data]" value="" />
                                <span>`+not_choice_text+`</span>
                            </a>
                        </span>
                    </div>
                </div>
            </div>`;

        // 商品类型
        html += `<!-- 数据类型 -->
        <div class="am-form-group am-padding-bottom-0">
            <label class="am-block">`+$obj.data('form-item-goods-data-type')+`</label>
            <div class="am-radio-group">`;
        var json = $obj.data('original-goods-type-list') || null;
        if(json != null)
        {
            var goods_type_list = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8));
            for(var i in goods_type_list)
            {
                html += `<label class="am-radio-inline">
                            <input type="radio" name="data[`+index+`][goods_data_type]" value="`+goods_type_list[i]['value']+`"  data-validation-message="`+$obj.data('form-item-goods-data-type-message')+`" `+((goods_type_list[i]['checked'] || false) ? 'checked' : '')+` class="goods_data_type" data-am-ucheck /> `+goods_type_list[i]['name']+`
                        </label>`;
            }
        }
        html += `</div>
            </div>`;

        // 品牌列表
        html += `<hr data-am-widget="divider" class="am-divider am-divider-dashed" />
            <!-- 数据类型选择容器 -->
            <div class="data-type-container am-padding-bottom-sm">
                <!-- 自动读取 -->
                <div class="data-type-auto-container">
                    <div class="am-form-group">
                        <label class="am-block">`+$obj.data('form-item-goods-brand-ids')+`</label>
                        <select name="data[`+index+`][goods_brand_ids]" class="am-radius chosen-select" multiple data-placeholder="`+$obj.data('please-select-tips')+`" data-validation-message="`+$obj.data('form-item-goods-brand-ids-message')+`">`;
        var json = $obj.data('original-brand-list') || null;
        if(json != null)
        {
            var brand_list = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8));
            for(var i in brand_list)
            {
                html += `<option value="`+brand_list[i]['id']+`">`+brand_list[i]['name']+`</option>`;
            }
        }
        html += `</select>
                </div>`;

        // 商品分类
        html += `<div class="am-form-group">
                <label>`+$obj.data('form-item-goods-category-ids')+`</label>
                <select name="data[`+index+`][goods_category_ids]" class="am-radius chosen-select" multiple  data-placeholder="`+$obj.data('please-select-tips')+`" data-validation-message="`+$obj.data('form-item-goods-category-ids-message')+`">`;
        var json = $obj.data('original-goods-category-list') || null;
        if(json != null)
        {
            var goods_category_list = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8));
            for(var i in goods_category_list)
            {
                html += `<option value="`+goods_category_list[i]['id']+`">`+goods_category_list[i]['name']+`</option>`;
                if((goods_category_list[i]['items'] || null) != null)
                {
                    for(var i2 in goods_category_list[i]['items'])
                    {
                        html += `<option style="padding-left: 20px;" value="`+goods_category_list[i]['items'][i2]['id']+`">`+((goods_category_list[i]['items'] || null) == null ? '├' : '└')+'² '+goods_category_list[i]['items'][i2]['name']+`</option>`;
                        if((goods_category_list[i]['items'][i2]['items'] || null) != null)
                        {
                            for(var i3 in goods_category_list[i]['items'][i2]['items'])
                            {
                                html += `<option style="padding-left: 40px;" value="`+goods_category_list[i]['items'][i2]['items'][i3]['id']+`">`+(i3 == goods_category_list[i]['items'][i2]['items'].length-1 ? '└' : '├')+'³ '+goods_category_list[i]['items'][i2]['items'][i3]['name']+`</option>`;
                            }
                        }
                    }
                }
            }
        }
        html += `</select>
                </div>`;

        // 商品数量
        html += `<div class="am-form-group">
            <label>`+$obj.data('form-item-goods-number')+`<span class="am-form-group-label-tips">`+$obj.data('form-item-goods-number-tips')+`
            </span></label>
            <input type="text" placeholder="`+$obj.data('form-item-goods-number')+`" name="data[`+index+`][goods_number]" maxlength="100" data-validation-message="`+$obj.data('form-item-goods-number-message')+`" class="am-radius" value="" />
        </div>`;

        // 排序类型
        html += `<div class="am-form-group">
            <label class="am-block">`+$obj.data('form-item-goods-order-by-type')+`<span class="am-form-group-label-tips">`+$obj.data('form-item-goods-order-by-type-tips')+`</span></label>
            <div class="am-radio-group">`;
        var json = $obj.data('original-goods-order-by-type-list') || null;
        if(json != null)
        {
            var goods_order_by_type_list = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8));
            for(var i in goods_order_by_type_list)
            {
                html += `<label class="am-radio-inline">
                            <input type="radio" name="data[`+index+`][goods_order_by_type]" value="`+i+`"  data-validation-message="`+$obj.data('form-item-goods-order-by-type-message')+`" `+((goods_order_by_type_list[i]['checked'] || false) ? 'checked' : '')+` data-am-ucheck /> `+goods_order_by_type_list[i]['name']+`
                        </label>`;
            }
        }
        html += `</div>
            </div>`;

        // 排序规则
        html += `<div class="am-form-group am-padding-bottom-0">
            <label class="am-block">`+$obj.data('form-item-goods-order-by-rule')+`<span class="am-form-group-label-tips">`+$obj.data('form-item-goods-order-by-rule-tips')+`</span></label>
            <div class="am-radio-group">`;
        var json = $obj.data('original-order-by-rule-list') || null;
        if(json != null)
        {
            var goods_order_by_rule_list = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8));
            for(var i in goods_order_by_rule_list)
            {
                html += `<label class="am-radio-inline">
                            <input type="radio" name="data[`+index+`][goods_order_by_rule]" value="`+i+`"  data-validation-message="`+$obj.data('form-item-goods-order-by-rule-message')+`" `+((goods_order_by_rule_list[i]['checked'] || false) ? 'checked' : '')+` data-am-ucheck /> `+goods_order_by_rule_list[i]['name']+`
                        </label>`;
            }
        }
        html += `</div>
            </div>
        </div>`;

        // 指定商品
        html += `<!-- 指定商品 -->
                <div class="data-type-appoint-container am-hide">
                    <ul class="manual-mode-data-container manual-mode-data-container-`+index+` am-nbfc am-radius" data-form-name="data[`+index+`][goods_data]" data-index="`+index+`"></ul>
                    <div class="am-margin-top">
                        <span class="business-operations-submit goods-popup-add" data-tag=".manual-mode-data-container-`+index+`">+ `+$obj.data('add-goods-title')+`</span>
                    </div>
                </div>
            </div>`;

        // 操作
        html += `<!-- 操作 -->
                <div class="am-flex am-flex-items-center am-gap-32 am-margin-top content-item-operate">
                    <a href="javascript:;" class="am-text-xs am-text-danger content-item-remove-submit am-flex am-flex-items-center">
                        <i class="iconfont icon-delete"></i>
                        <span>`+(window['lang_operate_delete_name'] || '删除')+`</span>
                    </a>
                    <a href="javascript:;" class="am-text-xs drag-sort-submit am-flex am-flex-items-center">
                        <i class="iconfont icon-sort"></i>
                        <span>`+(window['lang_operate_sort_name'] || '排序')+`</span>
                    </a>
                </div>`;
        
        // 数据结尾
        html += `</div>
            </div>
        </li>`;
        $obj.append(html);

        // 多选插件事件更新
        SelectChosenInit();

        // 单选初始化
        $obj.find('input[type="radio"]').uCheck();
    });

    // 商品组删除
    $(document).on('click', '.data-goods-container .content-item-remove-submit', function()
    {
        $(this).parents('li').remove();
    });

    // 文章数据类型事件
    $(document).on('change', '.data-goods-container input.goods_data_type', function()
    {
        var $obj = $(this).parents('li');
        var $auto = $obj.find('.data-type-auto-container');
        var $appoint = $obj.find('.data-type-appoint-container');
        if(parseInt($(this).val() || 0) == 0)
        {
            $auto.removeClass('am-hide');
            $appoint.addClass('am-hide');
        } else {
            $auto.addClass('am-hide');
            $appoint.removeClass('am-hide');
        }
    });






    // 文章组可拖拽初始化
    $('ul.data-article-container').dragsort({ dragSelector: 'a.drag-sort-submit', placeHolderTemplate: '<li class="drag-sort-dotted"></li>' });

    // 文章组合添加
    $(document).on('click', '.articlegroup-content-add-submit', function()
    {
        var $obj = $('.data-article-container');
        var not_choice_text = $url_choice_popup.data('not-choice-text');
        var index = parseInt(Math.random() * 1000001);
        var html = `<li class="data-article-item-container">
                        <div class="am-panel am-panel-default am-radius">
                            <div class="am-panel-bd">`;

        // 图片
        html += `<!-- 图片 -->
            <div class="images-row-container form-custom-group">
                <div class="am-form-group am-form-file">
                    <label class="am-block">`+$obj.data('form-item-images-icon')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <div class="am-form-file-upload-container">
                            <ul class="plug-file-upload-view images-icon-view-`+index+`" data-form-name="data[`+index+`][images_icon][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                <li class="plug-file-upload-submit" data-view-tag="ul.images-icon-view-`+index+`">
                                    <i class="iconfont icon-upload-add"></i>
                                </li>
                            </ul>
                        </div>
                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                            <input type="hidden" name="data[`+index+`][images_icon][url_data]" value="" />
                            <span>`+not_choice_text+`</span>
                        </a>
                    </div>
                </div>
                <div class="am-form-group am-form-file">
                    <label class="am-block">`+$obj.data('form-item-images-active-icon')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <div class="am-form-file-upload-container">
                            <ul class="plug-file-upload-view images-active_icon-view-`+index+`" data-form-name="data[`+index+`][images_active_icon][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                <li class="plug-file-upload-submit" data-view-tag="ul.images-active_icon-view-`+index+`">
                                    <i class="iconfont icon-upload-add"></i>
                                </li>
                            </ul>
                        </div>
                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                            <input type="hidden" name="data[`+index+`][images_active_icon][url_data]" value="" />
                            <span>`+not_choice_text+`</span>
                        </a>
                    </div>
                </div>
                <div class="am-form-group am-form-file">
                    <label class="am-block">`+$obj.data('form-item-images-shape')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <div class="am-form-file-upload-container">
                            <ul class="plug-file-upload-view images-shape-view-`+index+`" data-form-name="data[`+index+`][images_shape][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                <li class="plug-file-upload-submit" data-view-tag="ul.images-shape-view-`+index+`">
                                    <i class="iconfont icon-upload-add"></i>
                                </li>
                            </ul>
                        </div>
                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                            <input type="hidden" name="data[`+index+`][images_shape][url_data]" value="" />
                            <span>`+not_choice_text+`</span>
                        </a>
                    </div>
                </div>
                <div class="am-form-group am-form-file">
                    <label class="am-block">`+$obj.data('form-item-images-background')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <div class="am-form-file-upload-container">
                            <ul class="plug-file-upload-view images-background-view-`+index+`" data-form-name="data[`+index+`][images_background][value]" data-max-number="1" data-dialog-type="images" data-is-eye="1">
                                <li class="plug-file-upload-submit" data-view-tag="ul.images-background-view-`+index+`">
                                    <i class="iconfont icon-upload-add"></i>
                                </li>
                            </ul>
                        </div>
                        <a href="javascript:;" class="am-input-group-label am-radius am-text-blue url-choice-event">
                            <input type="hidden" name="data[`+index+`][images_background][url_data]" value="" />
                            <span>`+not_choice_text+`</span>
                        </a>
                    </div>
                </div>
            </div>`;

        // 文本
        html += `<!-- 文本 -->
            <div class="text-row-container form-custom-group">
                <div class="am-form-group">
                    <label>`+$obj.data('form-item-text-title')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" name="data[`+index+`][text_title][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-title-message')+`" value="" />
                        <span class="am-input-group-label am-radius">
                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                <input type="hidden" name="data[`+index+`][text_title][url_data]" value="" />
                                <span>`+not_choice_text+`</span>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="am-form-group">
                    <label>`+$obj.data('form-item-text-vice-title')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" name="data[`+index+`][text_vice_title][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-vice-title-message')+`" value="" />
                        <span class="am-input-group-label am-radius">
                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                <input type="hidden" name="data[`+index+`][text_vice_title][url_data]" value="" />
                                <span>`+not_choice_text+`</span>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="am-form-group">
                    <label>`+$obj.data('form-item-text-more')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" name="data[`+index+`][text_more][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-more-message')+`" value="" />
                        <span class="am-input-group-label am-radius">
                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                <input type="hidden" name="data[`+index+`][text_more][url_data]" value="" />
                                <span>`+not_choice_text+`</span>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="am-form-group">
                    <label>`+$obj.data('form-item-text-btn')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" name="data[`+index+`][text_btn][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-btn-message')+`" value="" />
                        <span class="am-input-group-label am-radius">
                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                <input type="hidden" name="data[`+index+`][text_btn][url_data]" value="" />
                                <span>`+not_choice_text+`</span>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="am-form-group">
                    <label>`+$obj.data('form-item-text-describe')+`</label>
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" name="data[`+index+`][text_describe][value]" class="am-form-field am-radius" placeholder="`+$obj.data('form-item-text-describe-message')+`" value="" />
                        <span class="am-input-group-label am-radius">
                            <a href="javascript:;" class="am-text-blue url-choice-event">
                                <input type="hidden" name="data[`+index+`][text_describe][url_data]" value="" />
                                <span>`+not_choice_text+`</span>
                            </a>
                        </span>
                    </div>
                </div>
            </div>`;

        // 数据类型
        html += `<!-- 数据类型 -->
        <div class="am-form-group am-padding-bottom-0">
            <label class="am-block">`+$obj.data('form-item-article-data-type')+`</label>
            <div class="am-radio-group">`;
        var json = $obj.data('original-article-type-list') || null;
        if(json != null)
        {
            var article_type_list = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8));
            for(var i in article_type_list)
            {
                html += `<label class="am-radio-inline">
                            <input type="radio" name="data[`+index+`][article_data_type]" value="`+article_type_list[i]['value']+`"  data-validation-message="`+$obj.data('form-item-article-data-type-message')+`" `+((article_type_list[i]['checked'] || false) ? 'checked' : '')+` class="article_data_type" data-am-ucheck /> `+article_type_list[i]['name']+`
                        </label>`;
            }
        }
        html += `</div>
            </div>`;

        // 数据类型选择容器 开始
        html += `<hr data-am-widget="divider" class="am-divider am-divider-dashed" />
                    <!-- 数据类型选择容器 -->
                    <div class="data-type-container am-padding-bottom-sm">`;

        // 自动读取
        html += `<!-- 自动读取 -->
                <div class="data-type-auto-container">`;

        //文章分类
        html += `<div class="am-form-group">
                <label>`+$obj.data('form-item-article-category-ids')+`</label>
                <select name="data[`+index+`][article_category_ids]" class="am-radius chosen-select" multiple  data-placeholder="`+$obj.data('please-select-tips')+`" data-validation-message="`+$obj.data('form-item-article-category-ids-message')+`">`;
        var json = $obj.data('original-article-category-list') || null;
        if(json != null)
        {
            var article_category_list = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8));
            for(var i in article_category_list)
            {
                html += `<option value="`+article_category_list[i]['id']+`">`+article_category_list[i]['name']+`</option>`;
            }
        }
        html += `</select>
                </div>`;

        // 文章数量
        html += `<div class="am-form-group">
            <label>`+$obj.data('form-item-article-number')+`<span class="am-form-group-label-tips">`+$obj.data('form-item-article-number-tips')+`
            </span></label>
            <input type="text" placeholder="`+$obj.data('form-item-article-number')+`" name="data[`+index+`][article_number]" maxlength="100" data-validation-message="`+$obj.data('form-item-article-number-message')+`" class="am-radius" value="" />
        </div>`;

        // 排序类型
        html += `<div class="am-form-group">
            <label class="am-block">`+$obj.data('form-item-article-order-by-type')+`<span class="am-form-group-label-tips">`+$obj.data('form-item-article-order-by-type-tips')+`</span></label>
            <div class="am-radio-group">`;
        var json = $obj.data('original-article-order-by-type-list') || null;
        if(json != null)
        {
            var article_order_by_type_list = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8));
            for(var i in article_order_by_type_list)
            {
                html += `<label class="am-radio-inline">
                            <input type="radio" name="data[`+index+`][article_order_by_type]" value="`+i+`"  data-validation-message="`+$obj.data('form-item-article-order-by-type-message')+`" `+((article_order_by_type_list[i]['checked'] || false) ? 'checked' : '')+` data-am-ucheck /> `+article_order_by_type_list[i]['name']+`
                        </label>`;
            }
        }
        html += `</div>
            </div>`;

        // 排序规则
        html += `<div class="am-form-group am-padding-bottom-0">
            <label class="am-block">`+$obj.data('form-item-article-order-by-rule')+`<span class="am-form-group-label-tips">`+$obj.data('form-item-article-order-by-rule-tips')+`</span></label>
            <div class="am-radio-group">`;
        var json = $obj.data('original-order-by-rule-list') || null;
        if(json != null)
        {
            var article_order_by_rule_list = JSON.parse(CryptoJS.enc.Base64.parse(decodeURIComponent(json)).toString(CryptoJS.enc.Utf8));
            for(var i in article_order_by_rule_list)
            {
                html += `<label class="am-radio-inline">
                            <input type="radio" name="data[`+index+`][article_order_by_rule]" value="`+i+`"  data-validation-message="`+$obj.data('form-item-article-order-by-rule-message')+`" `+((article_order_by_rule_list[i]['checked'] || false) ? 'checked' : '')+` data-am-ucheck /> `+article_order_by_rule_list[i]['name']+`
                        </label>`;
            }
        }
        html += `</div>
            </div>
        </div>`;


        // 指定文章
        html += `<!-- 指定文章 -->
            <div class="data-type-appoint-container am-hide">
                <ul class="manual-mode-data-container manual-mode-data-container-`+index+` am-nbfc am-radius" data-form-name="data[`+index+`][article_data]" data-index="`+index+`"></ul>
                <div class="am-margin-top">
                    <span class="business-operations-submit article-popup-add" data-tag=".manual-mode-data-container-`+index+`">+ `+$obj.data('add-article-title')+`</span>
                </div>
            </div>`;

        // 数据类型选择容器 结尾
        html += `</div>`;

        // 操作
        html += `<!-- 操作 -->
            <div class="am-flex am-flex-items-center am-gap-32 am-margin-top content-item-operate">
                <a href="javascript:;" class="am-text-xs am-text-danger content-item-remove-submit am-flex am-flex-items-center">
                    <i class="iconfont icon-delete"></i>
                    <span>`+(window['lang_operate_delete_name'] || '删除')+`</span>
                </a>
                <a href="javascript:;" class="am-text-xs drag-sort-submit am-flex am-flex-items-center">
                    <i class="iconfont icon-sort"></i>
                    <span>`+(window['lang_operate_sort_name'] || '排序')+`</span>
                </a>
            </div>`;

        // 数据结尾
        html += `</div>
            </div>
        </li>`;
        $obj.append(html);

        // 多选插件事件更新
        SelectChosenInit();

        // 单选初始化
        $obj.find('input[type="radio"]').uCheck();
    });

    // 文章组删除
    $(document).on('click', '.data-article-container .content-item-remove-submit', function()
    {
        $(this).parents('li').remove();
    });

    // 文章数据类型事件
    $(document).on('change', '.data-article-container input.article_data_type', function()
    {
        var $obj = $(this).parents('li');
        var $auto = $obj.find('.data-type-auto-container');
        var $appoint = $obj.find('.data-type-appoint-container');
        if(parseInt($(this).val() || 0) == 0)
        {
            $auto.removeClass('am-hide');
            $appoint.addClass('am-hide');
        } else {
            $auto.addClass('am-hide');
            $appoint.removeClass('am-hide');
        }
    });
});