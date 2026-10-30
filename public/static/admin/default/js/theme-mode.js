/**
 * 外观模式：light / dark / system（后台 / 多商户等共用）
 * 与配色（--color-main）正交；localStorage 同步 iframe
 * 存储键 theme_mode；兼容读取/回写 admin_theme_mode
 */
(function (window, document) {
    var STORAGE_KEY = 'theme_mode';
    var STORAGE_KEY_LEGACY = 'admin_theme_mode';
    var EVENT_KEY = 'theme_mode_change';
    var EVENT_KEY_LEGACY = 'admin_theme_mode_change';
    var ALLOWED = { light: 1, dark: 1, system: 1 };
    var UEDITOR_STYLE_ID = 'admin-theme-ueditor-style';
    var UEDITOR_DIALOG_STYLE_ID = 'admin-theme-ueditor-dialog-style';
    var UEDITOR_DARK_CSS =
        'html,body{background:#161b24!important;color:#e8eaed!important;}' +
        'body{margin:8px;}' +
        'a{color:#2a94ff;}' +
        'table,td,th{border-color:#2f3643!important;}' +
        'blockquote,pre,code{background:#1c212b!important;border-color:#3d4656!important;color:#e8eaed!important;}' +
        'html{scrollbar-color:#3d4656 #1c212b;scrollbar-width:thin;}' +
        '::-webkit-scrollbar{width:8px;height:8px;}' +
        '::-webkit-scrollbar-thumb,::-webkit-scrollbar-thumb:vertical{background:#3d4656!important;border-radius:4px;}' +
        '::-webkit-scrollbar-thumb:hover,::-webkit-scrollbar-thumb:vertical:hover{background:#4a5568!important;}' +
        '::-webkit-scrollbar-track,::-webkit-scrollbar-track:vertical,::-webkit-scrollbar-track-piece,::-webkit-scrollbar-corner{background:#1c212b!important;border:none!important;}';
    var UEDITOR_DIALOG_DARK_CSS =
        'html,body{background:#1c212b!important;color:#e8eaed!important;}' +
        'body{margin:0;}' +
        'a{color:#2a94ff;}' +
        '.wrapper,.tabbody,.tabbody .panel,.tabbody .panel.focus,#tabPanel,#tabContent,#tabBodys,.tabhead,.alignBar,.content,.queueList,.statusBar,#upload .filelist li.filePickerBlock,#online li,#online,#online #imageList{background:#1c212b!important;color:#e8eaed!important;border-color:#2f3643!important;}' +
        '#online #leftCategory{background:#1c212b!important;border:1px solid #2f3643!important;color:#e8eaed!important;}' +
        '#online #leftCategory ul,#online #leftCategory li{background:transparent!important;color:#e8eaed!important;border-color:#2f3643!important;}' +
        '#online #leftCategory li.active{color:#2a94ff!important;}' +
        '#online #leftCategory .category-search-input,#online #leftCategory .category-search-submit{background:#161b24!important;color:#e8eaed!important;border:1px solid #3d4656!important;}' +
        '#upload .queueList,#upload .queueList .placeholder,#upload .placeholder,#upload .filelist,#upload .statusBar,.tabbody #upload.panel,.tabbody #upload.panel.focus{background:#1c212b!important;border-color:#2f3643!important;}' +
        '#upload .queueList .draging{background:#243044!important;border-color:#2a94ff!important;color:#2a94ff!important;}' +
        '#upload .filelist li p.imgWrap.notimage{background:#161b24!important;border-color:#2f3643!important;}' +
        /* 插入右侧预览 / 视频左侧预览 */
        '#remote #preview,#preview{background:#161b24!important;border:1px solid #2f3643!important;}' +
        '#preview .previewMsg{background:#242a36!important;}' +
        '#remote .top,#remote .left,#remote .right,#videoInfo,fieldset,.edui-video-wrapper fieldset{background:#1c212b!important;border-color:#2f3643!important;color:#e8eaed!important;}' +
        /* 扫码上传下方内容框 */
        '#scan,#scan .qrcode-url{background:transparent!important;color:#e8eaed!important;}' +
        '#scan .qrcode-images-list{background:#161b24!important;border:1px solid #2f3643!important;color:#e8eaed!important;}' +
        '#scan .qrcode-images-list table,#scan .qrcode-images-list table thead,#scan .qrcode-images-list table tbody,#scan .qrcode-images-list table tr,#scan .qrcode-images-list table th,#scan .qrcode-images-list table td{background:#161b24!important;color:#e8eaed!important;border-color:#2f3643!important;}' +
        '#scan .qrcode-images-list .no-data{color:#9aa0a6!important;}' +
        '#scan .qrcode-url .text-copy-submit{background:#242a36!important;border:1px solid #3d4656!important;color:#e8eaed!important;}' +
        '.tabhead span,.tabhead .tab{background:#242a36!important;color:#9aa0a6!important;border-color:#2f3643!important;}' +
        '.tabhead span.focus,.tabhead .tab.focus,.menuFocus{background:#2a3140!important;color:#e8eaed!important;}' +
        '.menuDefault,#tabContent{background:#1c212b!important;}' +
        '.filelist li,.filePickerBlock,#upload .filelist li,#online li,#online .clearfix,.queueList,.statusBar,.btns .uploadBtn,.btns .webuploader-pick,#remote input.text,.content input,#scan .qrcode-images-list table thead th,#scan .qrcode-images-list table tbody td{border-color:#2f3643!important;}' +
        'input,select,textarea,button,.btn,.webuploader-pick,#remote input.text,.content input,#videoUrl,fieldset input.txt{background:#161b24!important;color:#e8eaed!important;border:1px solid #3d4656!important;box-shadow:none!important;outline:none!important;}' +
        'input:focus,select:focus,textarea:focus,#remote input.text:focus,.content input:focus,#videoUrl:focus,fieldset input.txt:focus{border:1px solid #2a94ff!important;box-shadow:0 0 0 1px rgba(42,148,255,.35)!important;outline:none!important;}' +
        'button:hover,.btn:hover,.webuploader-pick-hover,.uploadBtn:hover{background:#2a3140!important;}' +
        'table,td,th,tr,li,.upload-file-item,.file-panel,.queueList,.statusBar,.progress{background:#1c212b!important;color:#e8eaed!important;border-color:#2f3643!important;}' +
        'img.review{background:#242a36!important;border-color:#3d4656!important;}' +
        'html{scrollbar-color:#3d4656 #1c212b;scrollbar-width:thin;}' +
        '::-webkit-scrollbar{width:8px;height:8px;}' +
        '::-webkit-scrollbar-thumb{background:#3d4656!important;border-radius:4px;}' +
        '::-webkit-scrollbar-track,::-webkit-scrollbar-track-piece,::-webkit-scrollbar-corner{background:#1c212b!important;}';
    var MY97_STYLE_ID = 'admin-theme-my97-style';
    var MY97_DARK_CSS =
        'html,body{background:#1c212b!important;color:#e8eaed!important;margin:0;}' +
        '.WdateDiv{background:#1c212b!important;border:1px solid #2f3643!important;color:#e8eaed!important;}' +
        '.WdateDiv .yminput,.WdateDiv .yminputfocus,.WdateDiv #dpTime input{background:#161b24!important;color:#e8eaed!important;border:1px solid #3d4656!important;}' +
        '.WdateDiv .menuSel,.WdateDiv .menu,.WdateDiv .MMenu,.WdateDiv .YMenu,.WdateDiv .hhMenu,.WdateDiv .mmMenu,.WdateDiv .ssMenu{background:#1c212b!important;color:#e8eaed!important;box-shadow:0 2px 8px rgba(0,0,0,.3)!important;}' +
        '.WdateDiv .menuOn,.WdateDiv .WdayOn,.WdateDiv .WwdayOn,.WdateDiv .WotherDayOn{background:#243044!important;color:#2a94ff!important;}' +
        '.WdateDiv .MTitle{background:#242a36!important;color:#9aa0a6!important;}' +
        '.WdateDiv .Wday,.WdateDiv .Wwday,.WdateDiv .WotherDay,.WdateDiv .WdayTable,.WdateDiv .WdayTable td{color:#e8eaed!important;border-color:#2f3643!important;}' +
        '.WdateDiv .Wwday{color:#f56c6c!important;}' +
        '.WdateDiv .Wselday{background:#2a94ff!important;color:#fff!important;}' +
        '.WdateDiv .Wtoday{color:#2a94ff!important;}' +
        '.WdateDiv .WinvalidDay,.WdateDiv .invalidMenu{color:#7a8088!important;}' +
        '.WdateDiv .Wweek{background:#243044!important;border-color:#2f3643!important;}' +
        '.WdateDiv .dpButton{background:#2a94ff!important;color:#fff!important;}';

    function get_pref() {
        var mode = '';
        try {
            mode = localStorage.getItem(STORAGE_KEY) || localStorage.getItem(STORAGE_KEY_LEGACY) || '';
        } catch (e) {}
        if (!ALLOWED[mode]) {
            mode = 'light';
        }
        return mode;
    }

    function system_theme() {
        try {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                return 'dark';
            }
        } catch (e) {}
        return 'light';
    }

    function resolve_theme(mode) {
        mode = ALLOWED[mode] ? mode : 'light';
        return mode === 'system' ? system_theme() : mode;
    }

    /** 写入 UEDITOR 默认 initialStyle，避免 iframe 首帧白底 */
    function set_ueditor_initial_style(theme) {
        try {
            if (!window.UEDITOR_CONFIG) {
                return;
            }
            window.UEDITOR_CONFIG.initialStyle = theme === 'dark' ? UEDITOR_DARK_CSS : '';
        } catch (e) {}
    }

    function apply_ueditor_doc(doc, theme, is_dialog) {
        if (!doc) {
            return;
        }
        try {
            var style_id = is_dialog ? UEDITOR_DIALOG_STYLE_ID : UEDITOR_STYLE_ID;
            var css = is_dialog ? UEDITOR_DIALOG_DARK_CSS : UEDITOR_DARK_CSS;
            var style = doc.getElementById(style_id);
            if (!style) {
                style = doc.createElement('style');
                style.id = style_id;
                (doc.head || doc.documentElement || doc.body).appendChild(style);
            }
            style.innerHTML = theme === 'dark' ? css : '';
            if (doc.documentElement) {
                doc.documentElement.setAttribute('data-theme', theme);
            }
            if (doc.body) {
                var root_style = window.getComputedStyle(document.documentElement);
                var bg = is_dialog
                    ? (root_style.getPropertyValue('--surface-color').trim() || '#1c212b')
                    : (root_style.getPropertyValue('--input-bg-color').trim() || '#161b24');
                var fg = root_style.getPropertyValue('--text-color').trim() || '#e8eaed';
                doc.body.style.backgroundColor = theme === 'dark' ? bg : '';
                doc.body.style.color = theme === 'dark' ? fg : '';
            }
        } catch (e) {}
    }

    function bind_iframe_theme(iframe, theme, is_dialog) {
        if (!iframe) {
            return;
        }
        var apply = function () {
            try {
                apply_ueditor_doc(
                    iframe.contentDocument || (iframe.contentWindow && iframe.contentWindow.document),
                    theme,
                    is_dialog
                );
            } catch (e) {}
        };
        apply();
        if (!iframe.__adminThemeLoadBound) {
            iframe.__adminThemeLoadBound = true;
            iframe.addEventListener('load', function () {
                apply_ueditor_doc(
                    iframe.contentDocument || (iframe.contentWindow && iframe.contentWindow.document),
                    resolve_theme(get_pref()),
                    is_dialog
                );
            });
        }
    }

    function sync_ueditor_theme(theme) {
        theme = theme || resolve_theme(get_pref());
        set_ueditor_initial_style(theme);
        try {
            if (window.UE && UE.instants) {
                for (var key in UE.instants) {
                    if (!Object.prototype.hasOwnProperty.call(UE.instants, key)) {
                        continue;
                    }
                    var editor = UE.instants[key];
                    if (editor && editor.document) {
                        apply_ueditor_doc(editor.document, theme, false);
                    }
                }
            }
        } catch (e1) {}
        try {
            var editor_iframes = document.querySelectorAll('.edui-editor-iframeholder iframe');
            for (var i = 0; i < editor_iframes.length; i++) {
                bind_iframe_theme(editor_iframes[i], theme, false);
            }
        } catch (e2) {}
        try {
            var dialog_iframes = document.querySelectorAll(
                '.edui-dialog iframe, .edui-popup iframe, .edui-default .edui-for-emotion iframe'
            );
            for (var j = 0; j < dialog_iframes.length; j++) {
                bind_iframe_theme(dialog_iframes[j], theme, true);
            }
        } catch (e3) {}
    }

    function is_my97_doc(doc) {
        if (!doc) {
            return false;
        }
        try {
            if (doc.querySelector && doc.querySelector('.WdateDiv, #dpTitle, #dpControl')) {
                return true;
            }
            var links = doc.querySelectorAll ? doc.querySelectorAll('link[href*="datepicker"]') : [];
            return links && links.length > 0;
        } catch (e) {
            return false;
        }
    }

    function apply_my97_doc(doc, theme) {
        if (!doc) {
            return;
        }
        try {
            var style = doc.getElementById(MY97_STYLE_ID);
            if (!style) {
                style = doc.createElement('style');
                style.id = MY97_STYLE_ID;
                (doc.head || doc.documentElement || doc.body).appendChild(style);
            }
            style.innerHTML = theme === 'dark' ? MY97_DARK_CSS : '';
            if (doc.documentElement) {
                doc.documentElement.setAttribute('data-theme', theme);
            }
            if (doc.body) {
                var root_style = window.getComputedStyle(document.documentElement);
                var bg = root_style.getPropertyValue('--surface-color').trim() || '#1c212b';
                var fg = root_style.getPropertyValue('--text-color').trim() || '#e8eaed';
                doc.body.style.backgroundColor = theme === 'dark' ? bg : '';
                doc.body.style.color = theme === 'dark' ? fg : '';
            }
        } catch (e) {}
    }

    function bind_my97_iframe(iframe, theme) {
        if (!iframe) {
            return;
        }
        var apply = function () {
            try {
                var doc = iframe.contentDocument || (iframe.contentWindow && iframe.contentWindow.document);
                if (is_my97_doc(doc)) {
                    apply_my97_doc(doc, theme);
                }
            } catch (e) {}
        };
        apply();
        if (!iframe.__adminThemeMy97Bound) {
            iframe.__adminThemeMy97Bound = true;
            iframe.addEventListener('load', function () {
                try {
                    var doc = iframe.contentDocument || (iframe.contentWindow && iframe.contentWindow.document);
                    if (is_my97_doc(doc)) {
                        apply_my97_doc(doc, resolve_theme(get_pref()));
                    }
                } catch (e) {}
            });
        }
    }

    function sync_my97_theme(theme) {
        theme = theme || resolve_theme(get_pref());
        try {
            var iframes = document.querySelectorAll('iframe');
            for (var i = 0; i < iframes.length; i++) {
                bind_my97_iframe(iframes[i], theme);
            }
        } catch (e) {}
        try {
            if (window.$dp && $dp.dd) {
                var dd = $dp.dd;
                var ifr = dd.querySelector ? dd.querySelector('iframe') : null;
                if (ifr) {
                    bind_my97_iframe(ifr, theme);
                }
            }
        } catch (e2) {}
    }

    function patch_ueditor_get_editor() {
        if (!window.UE || typeof UE.getEditor !== 'function' || UE.__adminThemePatched) {
            return;
        }
        UE.__adminThemePatched = true;
        var original = UE.getEditor;
        UE.getEditor = function () {
            var editor = original.apply(this, arguments);
            try {
                if (editor && typeof editor.addListener === 'function' && !editor.__adminThemeReadyBound) {
                    editor.__adminThemeReadyBound = true;
                    editor.addListener('ready', function () {
                        sync_ueditor_theme(resolve_theme(get_pref()));
                    });
                }
            } catch (e) {}
            return editor;
        };
    }

    function observe_ueditor_iframes() {
        if (!window.MutationObserver) {
            return;
        }
        var timer = null;
        var timer_my97 = null;
        try {
            var obs = new MutationObserver(function (mutations) {
                var need = false;
                var need_my97 = false;
                for (var i = 0; i < mutations.length; i++) {
                    var nodes = mutations[i].addedNodes;
                    for (var j = 0; j < nodes.length; j++) {
                        var n = nodes[j];
                        if (n.nodeType !== 1) {
                            continue;
                        }
                        if (
                            (n.tagName === 'IFRAME' &&
                                n.closest &&
                                (n.closest('.edui-editor-iframeholder') ||
                                    n.closest('.edui-dialog') ||
                                    n.closest('.edui-popup'))) ||
                            (n.classList &&
                                (n.classList.contains('edui-editor') ||
                                    n.classList.contains('edui-editor-iframeholder') ||
                                    n.classList.contains('edui-dialog') ||
                                    n.classList.contains('edui-popup'))) ||
                            (n.querySelector &&
                                n.querySelector(
                                    '.edui-editor-iframeholder iframe, .edui-dialog iframe, .edui-popup iframe'
                                ))
                        ) {
                            need = true;
                        }
                        if (n.tagName === 'IFRAME' || (n.querySelector && n.querySelector('iframe'))) {
                            need_my97 = true;
                        }
                    }
                }
                if (need) {
                    if (timer) {
                        clearTimeout(timer);
                    }
                    timer = setTimeout(function () {
                        sync_ueditor_theme(resolve_theme(get_pref()));
                    }, 0);
                }
                if (need_my97) {
                    if (timer_my97) {
                        clearTimeout(timer_my97);
                    }
                    timer_my97 = setTimeout(function () {
                        sync_my97_theme(resolve_theme(get_pref()));
                    }, 30);
                }
            });
            obs.observe(document.documentElement, { childList: true, subtree: true });
        } catch (e) {}
    }

    function apply_theme(mode, skip_storage) {
        mode = ALLOWED[mode] ? mode : 'system';
        var effective = resolve_theme(mode);
        var root = document.documentElement;
        root.setAttribute('data-theme', effective);
        root.setAttribute('data-theme-mode', mode);
        set_ueditor_initial_style(effective);
        if (!skip_storage) {
            try {
                localStorage.setItem(STORAGE_KEY, mode);
                localStorage.setItem(STORAGE_KEY_LEGACY, mode);
            } catch (e) {}
            try {
                var detail = { mode: mode, theme: effective };
                window.dispatchEvent(new CustomEvent(EVENT_KEY, { detail: detail }));
                window.dispatchEvent(new CustomEvent(EVENT_KEY_LEGACY, { detail: detail }));
            } catch (e2) {}
        }
        update_ui(mode, effective);
        sync_ueditor_theme(effective);
        sync_my97_theme(effective);
        return effective;
    }

    function update_ui(mode, effective) {
        var icon = document.getElementById('admin-theme-mode-icon');
        if (icon) {
            var icon_map = { light: 'icon-light', dark: 'icon-dark', system: 'icon-theme' };
            icon.className = 'iconfont ' + (icon_map[mode] || 'icon-light');
        }
        var items = document.querySelectorAll('[data-admin-theme-mode]');
        for (var i = 0; i < items.length; i++) {
            var el = items[i];
            var val = el.getAttribute('data-admin-theme-mode');
            if (val === mode) {
                el.classList.add('am-active');
            } else {
                el.classList.remove('am-active');
            }
        }
    }

    function bind_nav() {
        var links = document.querySelectorAll('[data-admin-theme-mode]');
        for (var i = 0; i < links.length; i++) {
            links[i].addEventListener('click', function (e) {
                e.preventDefault();
                var mode = this.getAttribute('data-admin-theme-mode');
                apply_theme(mode);
            });
        }
    }

    function bind_system_change() {
        try {
            if (!window.matchMedia) {
                return;
            }
            var mql = window.matchMedia('(prefers-color-scheme: dark)');
            var handler = function () {
                if (get_pref() === 'system') {
                    apply_theme('system', true);
                }
            };
            if (typeof mql.addEventListener === 'function') {
                mql.addEventListener('change', handler);
            } else if (typeof mql.addListener === 'function') {
                mql.addListener(handler);
            }
        } catch (e) {}
    }

    function bind_storage() {
        window.addEventListener('storage', function (e) {
            if (e.key === STORAGE_KEY || e.key === STORAGE_KEY_LEGACY) {
                apply_theme(e.newValue || 'light', true);
            }
        });
    }

    function init() {
        patch_ueditor_get_editor();
        apply_theme(get_pref(), true);
        bind_nav();
        bind_system_change();
        bind_storage();
        observe_ueditor_iframes();
        var on_theme_event = function (e) {
            var theme = (e && e.detail && e.detail.theme) || resolve_theme(get_pref());
            sync_ueditor_theme(theme);
            sync_my97_theme(theme);
        };
        window.addEventListener(EVENT_KEY, on_theme_event);
        window.addEventListener(EVENT_KEY_LEGACY, on_theme_event);
        // 打开日期控件后再补一次（My97 iframe 延迟生成）
        document.addEventListener(
            'click',
            function (e) {
                var t = e.target;
                if (!t) {
                    return;
                }
                if (
                    (t.classList && t.classList.contains('Wdate')) ||
                    (t.getAttribute && t.getAttribute('onclick') && String(t.getAttribute('onclick')).indexOf('WdatePicker') !== -1)
                ) {
                    setTimeout(function () {
                        sync_my97_theme(resolve_theme(get_pref()));
                    }, 50);
                    setTimeout(function () {
                        sync_my97_theme(resolve_theme(get_pref()));
                    }, 200);
                }
            },
            true
        );
    }

    var api = {
        get: get_pref,
        apply: apply_theme,
        resolve: resolve_theme,
        syncEditor: sync_ueditor_theme,
    };
    window.ThemeMode = api;
    window.AdminThemeMode = api;

    // 尽早写入 initialStyle（本文件若晚于 ueditor.config，仍可赶在多数 getEditor 前）
    set_ueditor_initial_style(resolve_theme(get_pref()));
    patch_ueditor_get_editor();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})(window, document);
