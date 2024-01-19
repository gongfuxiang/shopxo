/*! amazeui-dialog v0.0.2 | by Amaze UI Team | (c) 2016 AllMobilize, Inc. | Licensed under MIT | 2016-06-22T10:19:33+0800 */ 
(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(_dereq_,module,exports){
(function (global){
/**
 * Created by along on 15/8/12.
 */

'use strict';

var $ = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null);
var UI = (typeof window !== "undefined" ? window['AMUI'] : typeof global !== "undefined" ? global['AMUI'] : null);

var dialog = dialog || {};

dialog.alert = function(options) {
  options = options || {};
  options.style = options.style || '';
  options.content_style = options.content_style || '';
  options.class = options.class || '';
  options.title = options.title || null;
  options.content = options.content || '提示内容';
  options.confirmText = options.confirmText || window['lang_confirm_name'] || '确定';
  options.isClose = options.isClose || false;
  options.isBtn = options.isBtn || false;
  options.config = options.config || {};
  options.onConfirm = options.onConfirm || function() {};
  options.onClose = options.onClose || function() {};
  options.onOpen = options.onOpen || function() {};
  var html = [];
  html.push('<div class="am-modal am-modal-alert '+options.class+'" tabindex="-1">');
  html.push('<div class="am-modal-dialog am-radius am-nbfc" style="'+options.style+'">');
  if(options.title !== null || options.isClose === true)
  {
    html.push('<div class="am-modal-hd">');
    if(options.title !== null)
    {
      html.push('<span>'+options.title+'</span>');
    }
    if(options.isClose === true)
    {
      html.push('<a href="javascript: void(0)" class="am-close" data-am-modal-close>&times;</a>');
    }
    html.push('</div>');
  }
  // 是否iframe
  if((options.iframe || null) != null)
  {
    html.push(options.iframe);

  // 是否url模式
  } else if((options.url || null) != null)
  {
    html.push('<iframe src="'+options.url+'" class="am-block" style="width:100%;height:calc(100% - 4.5rem);"></iframe>');

    // 默认内容
  } else {
    html.push('<div class="am-modal-bd" style="'+options.content_style+'">' + options.content + '</div>');
  }
  if(options.isBtn)
  {
    html.push('<div class="am-modal-footer"><span class="am-modal-btn">'+options.confirmText+'</span></div>');
  }
  html.push('</div>');
  html.push('</div>');
  var $alert = $(html.join('')).appendTo('body').modal(options.config);
  $alert.on('opened.modal.amui', function() {
    options.onOpen();
  });

  $alert.on('closed.modal.amui', function() {
    options.onConfirm();
    options.onClose();
    var $this = $(this);
    setTimeout(function()
    {
      $this.remove();
    }, 1000);
  });
};

dialog.confirm = function(options) {
  options = options || {};
  options.title = options.title || window['lang_operate_params_error'] || '提示';
  options.content = options.content || '提示内容';
  options.cancelText = options.cancelText || window['lang_cancel_name'] || '取消';
  options.confirmText = options.confirmText || window['lang_confirm_name'] || '确定';
  options.onConfirm = options.onConfirm || function() {};
  options.onCancel = options.onCancel || function() {};

  var html = [];
  html.push('<div class="am-modal am-modal-confirm" tabindex="-1">');
  html.push('<div class="am-modal-dialog am-radius am-nbfc">');
  html.push('<div class="am-modal-hd">' + options.title + '</div>');
  html.push('<div class="am-modal-bd"><div class="am-padding-horizontal-xl am-padding-vertical-xs am-text-sm">' + options.content + '</div></div>');
  html.push('<div class="am-modal-footer">');
  html.push('<span class="am-modal-btn am-text-danger" data-am-modal-cancel>'+options.cancelText+'</span>');
  html.push('<span class="am-modal-btn" data-am-modal-confirm>'+options.confirmText+'</span>');
  html.push('</div>');
  html.push('</div>');
  html.push('</div>');

  return $(html.join('')).appendTo('body').modal({
    onConfirm: function(e) {
      options.onConfirm(e);
    },
    onCancel: function() {
      options.onCancel();
    }
  }).on('closed.modal.amui', function() {
    var $this = $(this);
      setTimeout(function()
      {
        $this.remove();
      }, 1000);
  });
};

dialog.loading = function(options) {
  if(options == 'close') {
    $('#my-modal-loading').modal('close');
  } else {
    options = options || {};
    options.title = options.title || window['lang_loading_tips'] || '正在载入...';

    if($('#my-modal-loading').length > 0)
    {
      $('#my-modal-loading .am-modal-bd .am-margin-left-xs').text(options.title);
    } else {
      var html = [];
      html.push('<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="my-modal-loading">');
      html.push('<div class="am-modal-dialog am-radius am-nbfc">');
      html.push('<div class="am-modal-bd am-padding-vertical-0">');
      html.push('<div class="am-padding-horizontal-sm am-padding-vertical-sm">');
      html.push('<span class="am-icon-spinner am-icon-spin am-vertical-align-middle"></span>');
      html.push('<span class="am-margin-left-xs am-vertical-align-middle">' + options.title + '</span>');
      html.push('</div>');
      html.push('</div>');
      html.push('</div>');
      html.push('</div>');

      return $(html.join('')).appendTo('body').modal().on('closed.modal.amui', function() {
          $(this).remove();
        });
    }
  }
};

dialog.actions = function(options) {
  options = options || {};
  options.title = options.title || window['lang_operate_params_error'] || '您想整咋样?';
  options.cancelText = options.cancelText || window['lang_cancel_name'] || '取消';
  options.items = options.items || [];
  var html = [];
  html.push('<div class="am-modal-actions">');
  html.push('<div class="am-modal-actions-group am-radius am-nbfc">');
  html.push('<ul class="am-list">');
  html.push('<li class="am-modal-actions-header">' + options.title + '</li>');
  options.items.forEach(function(item, index) {
    html.push('<li class="am-padding-sm" index="' + index + '">' + item.content + '</li>');
  });
  html.push('</ul>');
  html.push('</div>');
  html.push('<div class="am-modal-actions-group">');
  html.push('<button class="am-btn am-btn-secondary am-btn-block am-radius" data-am-modal-close>'+options.cancelText+'</button>');
  html.push('</div>');
  html.push('</div>');

  var $acions = $(html.join('')).appendTo('body').modal();
  if((options.onSelected || null) != null && typeof(options.onSelected) == 'function') {
    $acions.find('.am-list>li').bind('click', function(e) {
      options.onSelected($(this).attr('index'), $(this), $acions);
    });
  }

  return {
    show: function() {
      $acions.modal('open');
    },
    close: function() {
      $acions.modal('close');
    }
  };
};

dialog.popup = function(options) {
  options = options || {};
  options.title = options.title || null;
  options.content = options.content || '正文';
  options.class = options.class || '';
  options.onClose = options.onClose || function() {};
  options.onOpen = options.onOpen || function() {};

  var html = [];

  // 是否存在标题
  if(options.title != null)
  {
    html.push('<div class="am-popup am-radius '+options.class+'">');
    html.push('<div class="am-popup-inner am-radius">');
    html.push('<div class="am-popup-hd">');
    html.push('<h4 class="am-popup-title">' + options.title + '</h4>');
    html.push('<span data-am-modal-close  class="am-close">&times;</span>');
    html.push('</div>');
    html.push('<div class="am-popup-bd">' + options.content + '</div>');
  } else {
    html.push('<div class="am-popup am-radius '+options.class+' popup-not-title">');
    html.push('<div class="am-popup-inner am-radius">');
    html.push('<span data-am-modal-close class="am-close am-close-alt">&times;</span>');
    html.push(options.content);
  }
  html.push('</div> ');
  html.push('</div>');
  var $popup = $(html.join('')).appendTo('body').modal();
  $popup.on('opened.modal.amui', function() {
    options.onOpen();
  });

  $popup.on('closed.modal.amui', function() {
    options.onClose();
    var $this = $(this);
    setTimeout(function()
    {
      $this.remove();
    }, 1000);
  });
};

dialog.offcanvas = function(options) {
  options = options || {};
  options.content = options.content || '正文';
  options.class = options.class || '';
  options.isClose = options.isClose || false;
  options.onClose = options.onClose || function() {};
  options.width = ((options.width || 0) == 0) ? '' : 'width:'+options.width+'px;';
  var random = 'am-offcanvas-' + Math.random().toString(36).substring(2);
  var html = [];
  html.push('<div id="'+random+'" class="am-offcanvas am-offcanvas-popup '+options.class+'">');
  html.push('<div class="am-offcanvas-bar am-offcanvas-bar-flip" style="'+options.width+'">');
  // 是否需要关闭按钮
  if(options.isClose){
      html.push('<a class="am-close am-fr am-text-lg">&times;</a>');
  }
  // 是否url模式
  if((options.url || null) != null)
  {
    html.push('<iframe src="'+options.url+'" class="am-block" style="width:100%;height:100%;"></iframe>');
  } else {
    html.push('<div class="am-offcanvas-content">' + options.content + '</div>');
  }
  html.push('</div>');
  html.push('</div>');
  if(options.isClose){
    $(document).on('click', '#' + random + ' .am-offcanvas-bar > .am-close', function(){
      $('#' + random).offCanvas('close');
    });
  }
  return $(html.join('')).appendTo('body').offCanvas().on('closed.offcanvas.amui', function() {
    
      var $this = $(this);
      setTimeout(function()
      {
        $this.remove();
      }, 1000);
      options.onClose();
    });
};

module.exports = UI.dialog = dialog;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}]},{},[1]);
