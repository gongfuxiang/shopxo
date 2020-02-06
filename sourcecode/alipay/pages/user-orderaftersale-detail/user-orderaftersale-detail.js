const app = getApp();
Page({
  data: {
    price_symbol: app.data.price_symbol,
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,
    popup_delivery_status: false,

    // 接口数据
    editor_path_type: '',
    order_data: null,
    new_aftersale_data: null,
    step_data: null,
    returned_data: null,
    return_only_money_reason: [],
    return_money_goods_reason: [],
    aftersale_type_list: [],
    reason_data_list: [],
    return_goods_address: null,
    
    // 售后基础信息
    panel_base_data_list: [
      {
        name: '退款类型',
        field: 'type_text',
      },
      {
        name: '当前状态',
        field: 'status_text',
      },
      {
        name: '申请原因',
        field: 'reason',
      },
      {
        name: '退货数量',
        field: 'number',
      },
      {
        name: '退款金额',
        field: 'price',
      },
      {
        name: '退款说明',
        field: 'msg',
      },
      {
        name: '退款方式',
        field: 'refundment_text',
      },
      {
        name: '拒绝原因',
        field: 'refuse_reason',
      },
      {
        name: '申请时间',
        field: 'apply_time_time',
      },
      {
        name: '确认时间',
        field: 'confirm_time_time',
      },
      {
        name: '退货时间',
        field: 'delivery_time_time',
      },
      {
        name: '审核时间',
        field: 'audit_time_time',
      },
      {
        name: '取消时间',
        field: 'cancel_time_time',
      },
      {
        name: '添加时间',
        field: 'add_time_time',
      },
      {
        name: '更新时间',
        field: 'upd_time_time',
      }
    ],

    // 快递信息
    panel_express_data_list: [
      {
        name: '快递名称',
        field: 'express_name',
      },
      {
        name: '快递单号',
        field: 'express_number',
      },
      {
        name: '退货时间',
        field: 'delivery_time_time',
      }
    ],

    // 表单数据
    form_button_disabled: false,
    form_type: null,
    form_reason_index: null,
    form_price: '',
    form_msg: '',
    form_number: 0,
    form_images_list: [],
    form_express_name: '',
    form_express_number: '',
  },

  onLoad(params) {
    this.setData({
      params: params,
      popup_delivery_status: ((params.is_delivery_popup || 0) == 1),
    });
    this.init();
  },

  onShow() {
    my.setNavigationBar({ title: app.data.common_pages_title.user_orderaftersale_detail });
  },

  init() {
    var self = this;
    my.showLoading({content: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    my.request({
      url: app.get_request_url("aftersale", "orderaftersale"),
      method: "POST",
      data: {
        oid: this.data.params.oid,
        did: this.data.params.did
      },
      dataType: "json",
      success: res => {
        my.hideLoading();
        my.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_list_loding_status: 3,
            data_bottom_line_status: true,
            data_list_loding_msg: '',

            editor_path_type: data.editor_path_type || '',
            order_data: data.order_data || null,
            new_aftersale_data: ((data.new_aftersale_data || null) == null || data.new_aftersale_data.length <= 0) ? null : data.new_aftersale_data,
            step_data: data.step_data || null,
            returned_data: data.returned_data || null,
            return_only_money_reason: data.return_only_money_reason || [],
            return_money_goods_reason: data.return_money_goods_reason || [],
            aftersale_type_list: data.aftersale_type_list || [],
            return_goods_address: data.return_goods_address || null,

            form_price: (data.returned_data || null != null) ? data.returned_data.refund_price : 0,
          });
        } else {
          self.setData({
            data_list_loding_status: 0,
            data_bottom_line_status: false,
            data_list_loding_msg: res.data.msg,
          });
          if (app.is_login_check(res.data, self, 'init')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        my.hideLoading();
        my.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_bottom_line_status: false,
          data_list_loding_msg: '服务器请求出错',
        });

        app.showToast("服务器请求出错");
      }
    });
  },

  // 类型选择
  form_type_event(e) {
    var value = e.target.dataset.value;
    this.setData({
      form_type: value,
      form_reason_index: (this.data.form_type == value) ? this.data.form_reason_index : null,
      reason_data_list: (value == 0) ? this.data.return_only_money_reason : this.data.return_money_goods_reason,
      form_number: (value == 0) ? 0 : this.data.returned_data.returned_quantity,
    });
  },

  // 原因选择
  form_reason_event(e) {
    this.setData({
      form_reason_index: e.detail.value
    });
  },

  // 商品件数
  form_number_event(e) {
    this.setData({
      form_number: e.detail.value
    });
  },

  // 退款金额
  form_price_event(e) {
    this.setData({
      form_price: e.detail.value
    });
  },

  // 退款说明
  form_msg_event(e) {
    this.setData({
      form_msg: e.detail.value
    });
  },

  // 快递名称
  form_express_name_event(e) {
    this.setData({
      form_express_name: e.detail.value
    });
  },

  // 快递单号
  form_express_number_event(e) {
    this.setData({
      form_express_number: e.detail.value
    });
  },

  // 上传图片预览
  upload_show_event(e) {
    my.previewImage({
      current: e.target.dataset.index,
      urls: this.data.form_images_list,
    });
  },

  // 图片删除
  upload_delete_event(e) {
    var self = this;
    my.confirm({
      title: '温馨提示',
      content: '删除后不可恢复、继续吗？',
      success(res) {
        if (res.confirm) {
          var list = self.data.form_images_list;
          list.splice(e.target.dataset.index, 1);
          self.setData({
            form_images_list: list,
          });
        }
      }
    });
  },

  // 文件上传
  file_upload_event(e) {
    var self = this;
    my.chooseImage({
      count: 3,
      success(res) {
        var success = 0;
        var fail = 0;
        var length = res.tempFilePaths.length;
        var count = 0;
        self.upload_one_by_one(res.tempFilePaths, success, fail, count, length);
      }
    });
  },

  // 采用递归的方式上传多张
  upload_one_by_one(img_paths, success, fail, count, length) {
    var self = this;
    if (self.data.form_images_list.length < 3) {
      my.uploadFile({
        url: app.get_request_url("index", "ueditor"),
        filePath: img_paths[count],
        fileName: 'upfile',
        fileType: 'image',
        formData: {
          action: 'uploadimage',
          path_type: self.data.editor_path_type
        },
        success: function (res) {
          success++;
          if (res.statusCode == 200) {
            var data = (typeof (res.data) == 'object') ? res.data : JSON.parse(res.data);
            if (data.code == 0 && (data.data.url || null) != null) {
              var list = self.data.form_images_list;
              list.push(data.data.url);
              self.setData({ form_images_list: list });
            } else {
              app.showToast(data.msg);
            }
          }
        },
        fail: function (e) {
          fail++;
        },
        complete: function (e) {
          count++; // 下一张
          if (count >= length) {
            // 上传完毕，作一下提示
            //app.showToast('上传成功' + success +'张', 'success');
          } else {
            // 递归调用，上传下一张
            self.upload_one_by_one(img_paths, success, fail, count, length);
          }
        }
      });
    }
  },

  // 售后表单提交
  form_submit_event(e) {
    // 表单数据
    var form_data = {
      order_id: this.data.params.oid,
      order_detail_id: this.data.params.did,
      type: this.data.form_type,
      reason: this.data.reason_data_list[this.data.form_reason_index],
      number: (this.data.form_type == 0) ? 0 : this.data.form_number,
      price: this.data.form_price,
      msg: this.data.form_msg,
      images: (this.data.form_images_list.length > 0) ? JSON.stringify(this.data.form_images_list) : '',
    }

    // 防止金额大于计算的金额
    if (form_data['price'] > this.data.returned_data['refund_price'])
    {
      form_data['price'] = this.data.returned_data['refund_price'];
    }

    // 防止数量大于计算的数量
    if (form_data['number'] > this.data.returned_data['returned_quantity']) {
      form_data['number'] = this.data.returned_data['returned_quantity'];
    }

    // 数据校验
    var validation = [
      { fields: "type", msg: "请选择操作类型", is_can_zero: 1 },
      { fields: "reason", msg: "请选择原因" },
      { fields: "msg", msg: "请填写退款说明" }
    ];
    if (form_data['type'] == 1)
    {
      validation.push({ fields: "number", msg: "请选择退货数量" });
    }

    // 校验参数并提交
    if (app.fields_check(form_data, validation)) {
      var self = this;
      my.showLoading({content: "处理中..." });
      self.setData({ form_button_disabled: true });
      my.request({
        url: app.get_request_url("create", "orderaftersale"),
        method: "POST",
        data: form_data,
        dataType: "json",
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: res => {
          my.hideLoading();
          if (res.data.code == 0) {
            app.showToast(res.data.msg, "success");
            setTimeout(function () {
              self.setData({ form_button_disabled: false });
              self.init();
            }, 1000);
          } else {
            self.setData({ form_button_disabled: false});
            app.showToast(res.data.msg);
          }
        },
        fail: () => {
          my.hideLoading();
          self.setData({ form_button_disabled: false });
          app.showToast("服务器请求出错");
        }
      });
    }
  },

  // 退货开启弹层
  delivery_submit_event(e) {
    this.setData({ popup_delivery_status: true });
  },

  // 退货弹层关闭
  popup_delivery_close_event(e) {
    this.setData({ popup_delivery_status: false });
  },

  // 退货表单
  form_delivery_submit_event(e) {
    // 表单数据
    var form_data = {
      id: this.data.new_aftersale_data.id,
      express_name: this.data.form_express_name,
      express_number: this.data.form_express_number,
    }

    // 数据校验
    var validation = [
      { fields: "express_name", msg: "请填写快递名称" },
      { fields: "express_number", msg: "请填写快递单号" },
    ];

    // 校验参数并提交
    if (app.fields_check(form_data, validation)) {
      var self = this;
      my.showLoading({content: "处理中..." });
      self.setData({ form_button_disabled: true });
      my.request({
        url: app.get_request_url("delivery", "orderaftersale"),
        method: "POST",
        data: form_data,
        dataType: "json",
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: res => {
          my.hideLoading();
          self.setData({ popup_delivery_status: false});
          if (res.data.code == 0) {
            app.showToast(res.data.msg, "success");
            setTimeout(function () {
              self.setData({ form_button_disabled: false });
              self.init();
            }, 1000);
          } else {
            self.setData({ form_button_disabled: false });
            app.showToast(res.data.msg);
          }
        },
        fail: () => {
          my.hideLoading();
          self.setData({ form_button_disabled: false });
          app.showToast("服务器请求出错");
        }
      });
    }
  },

  // 凭证图片预览
  images_view_event(e) {
    my.previewImage({
      current: e.target.dataset.index,
      urls: this.data.new_aftersale_data.images,
    });
  },

  // 查看售后数据
  show_aftersale_event(e) {
    my.navigateTo({
      url: "/pages/user-orderaftersale/user-orderaftersale?keywords=" + this.data.new_aftersale_data.order_no
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

});
