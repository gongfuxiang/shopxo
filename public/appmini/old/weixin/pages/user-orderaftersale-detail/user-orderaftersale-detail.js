const app = getApp();
Page({
  data: {
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,

    order_data: [],
    new_aftersale_data: [],
    step_data: [],
    returned_data: [],
    return_only_money_reason: [],
    return_money_goods_reason: [],
    aftersale_type_list: [],
  },

  onLoad(params) {
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

            order_data: data.order_data || [],
            new_aftersale_data: data.new_aftersale_data || [],
            step_data: data.step_data || [],
            returned_data: data.returned_data || [],
            return_only_money_reason: data.return_only_money_reason || [],
            return_money_goods_reason: data.return_money_goods_reason || [],
            aftersale_type_list: data.aftersale_type_list || [],
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

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

});
