const app = getApp();
Page({
  data: {
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,

    order_data: null,
    new_aftersale_data: null,
    step_data: null,
    returned_data: null,
    return_only_money_reason: [],
    return_money_goods_reason: [],
    aftersale_type_list: [],
    reason_data_list: [],

    form_type: -1,
    form_reason_index: -1,
    form_price: '',
    form_msg: '',
    form_number: 0,
  },

  onLoad(params) {
    params['oid'] = 4;
    params['did'] = 8;
    this.setData({ params: params });
    this.init();
  },

  onShow() {
    wx.setNavigationBarTitle({ title: app.data.common_pages_title.user_orderaftersale_detail });
  },

  init() {
    var self = this;
    wx.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    wx.request({
      url: app.get_request_url("aftersale", "orderaftersale"),
      method: "POST",
      data: {
        oid: this.data.params.oid,
        did: this.data.params.did
      },
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_list_loding_status: 3,
            data_bottom_line_status: true,
            data_list_loding_msg: '',

            order_data: data.order_data || null,
            new_aftersale_data: data.new_aftersale_data || null,
            step_data: data.step_data || null,
            returned_data: data.returned_data || null,
            return_only_money_reason: data.return_only_money_reason || [],
            return_money_goods_reason: data.return_money_goods_reason || [],
            aftersale_type_list: data.aftersale_type_list || [],

            form_price: (data.returned_data || null != null) ? data.returned_data.refund_price : 0,
          });
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_bottom_line_status: false,
            data_list_loding_msg: res.data.msg,
          });
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
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
    var value = e.currentTarget.dataset.value;
    this.setData({
      form_type: value,
      form_reason_index: (this.data.form_type == value) ? this.data.form_reason_index : -1,
      reason_data_list: (value == 0) ? this.data.return_only_money_reason : this.data.return_money_goods_reason,
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

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

});
