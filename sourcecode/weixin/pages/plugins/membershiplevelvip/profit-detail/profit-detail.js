const app = getApp();
Page({
  data: {
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,

    detail: null,
    detail_list: [],
  },

  onLoad(params) {
    //params['id'] = 1;
    this.setData({ params: params });
    this.init();
  },

  onShow() {},

  init() {
    var self = this;
    wx.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    wx.request({
      url: app.get_request_url("detail", "profit", "membershiplevelvip"),
      method: "POST",
      data: {
        id: this.data.params.id
      },
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            detail: data.data,
            detail_list: [
              { name: "订单金额", value: data.data.total_price || '' },
              { name: "返佣金额", value: data.data.profit_price || '' },
              { name: "当前级别", value: data.data.level_name || '' },
              { name: "结算状态", value: data.data.status_name || '' },
              { name: "返佣规则", value: data.data.commission_rules || '' },
              { name: "创建时间", value: data.data.add_time_time || '' },
              { name: "更新时间", value: data.data.upd_time || '' },
            ],

            data_list_loding_status: 3,
            data_bottom_line_status: true,
            data_list_loding_msg: '',
          });
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_bottom_line_status: false,
            data_list_loding_msg: res.data.msg,
          });
          if (app.is_login_check(res.data, self, 'init')) {
            app.showToast(res.data.msg);
          }
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