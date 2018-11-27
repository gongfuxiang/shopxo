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
  options.title = options.title || '提示';
  options.content = options.content || '提示内容';
  options.onConfirm = options.onConfirm || function() {
    };
  var html = [];
  html.push('<div class="am-modal am-modal-alert" tabindex="-1">');
  html.push('<div class="am-modal-dialog">');
  html.push('<div class="am-modal-hd">' + options.title + '</div>');
  html.push('<div class="am-modal-bd">' + options.content + '</div>');
  html.push('<div class="am-modal-footer"><span class="am-modal-btn">确定</span></div>');
  html.push('</div>');
  html.push('</div>');

  return $(html.join(''))
    .appendTo('body')
    .modal()
    .on('closed.modal.amui', function() {
      options.onConfirm();
      $(this).remove();
    });
};

dialog.confirm = function(options) {
  options = options || {};
  options.title = options.title || '提示';
  options.content = options.content || '提示内容';
  options.onConfirm = options.onConfirm || function() {
    };
  options.onCancel = options.onCancel || function() {
    };

  var html = [];
  html.push('<div class="am-modal am-modal-confirm" tabindex="-1">');
  html.push('<div class="am-modal-dialog">');
  html.push('<div class="am-modal-hd">' + options.title + '</div>');
  html.push('<div class="am-modal-bd">' + options.content + '</div>');
  html.push('<div class="am-modal-footer">');
  html.push('<span class="am-modal-btn" data-am-modal-cancel>取消</span>');
  html.push('<span class="am-modal-btn" data-am-modal-confirm>确定</span>');
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
    $(this).remove();
  });
};

dialog.loading = function(options) {
  options = options || {};
  options.title = options.title || '正在载入...';

  var html = [];
  html.push('<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="my-modal-loading">');
  html.push('<div class="am-modal-dialog">');
  html.push('<div class="am-modal-hd">' + options.title + '</div>');
  html.push('<div class="am-modal-bd">');
  html.push('<span class="am-icon-spinner am-icon-spin"></span>');
  html.push('</div>');
  html.push('</div>');
  html.push('</div>');

  return $(html.join('')).appendTo('body').modal()
    .on('closed.modal.amui', function() {
      $(this).remove();
    });
};

dialog.actions = function(options) {
  options = options || {};
  options.title = options.title || '您想整咋样?';
  options.items = options.items || [];
  options.onSelected = options.onSelected || function() {
      $acions.close();
    };
  var html = [];
  html.push('<div class="am-modal-actions">');
  html.push('<div class="am-modal-actions-group">');
  html.push('<ul class="am-list">');
  html.push('<li class="am-modal-actions-header">' + options.title + '</li>');
  options.items.forEach(function(item, index) {
    html.push('<li index="' + index + '">' + item.content + '</li>');
  });
  html.push('</ul>');
  html.push('</div>');
  html.push('<div class="am-modal-actions-group">');
  html.push('<button class="am-btn am-btn-secondary am-btn-block" data-am-modal-close>取消</button>');
  html.push('</div>');
  html.push('</div>');

  var $acions = $(html.join('')).appendTo('body');

  $acions.find('.am-list>li').bind('click', function(e) {
    options.onSelected($(this).attr('index'), this);
  });

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
  options.title = options.title || '标题';
  options.content = options.content || '正文';
  options.onClose = options.onClose || function() {
    };

  var html = [];
  html.push('<div class="am-popup">');
  html.push('<div class="am-popup-inner">');
  html.push('<div class="am-popup-hd">');
  html.push('<h4 class="am-popup-title">' + options.title + '</h4>');
  html.push('<span data-am-modal-close  class="am-close">&times;</span>');
  html.push('</div>');
  html.push('<div class="am-popup-bd">' + options.content + '</div>');
  html.push('</div> ');
  html.push('</div>');
  return $(html.join('')).appendTo('body').modal()
    .on('closed.modal.amui', function() {
      $(this).remove();
      options.onClose();
    });
};

module.exports = UI.dialog = dialog;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}]},{},[1]);
