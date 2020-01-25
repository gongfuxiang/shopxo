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

  onShow() { },

  init() {
    var self = this;
    wx.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    wx.request({
      url: app.get_request_url("detail", "cash", "wallet"),
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
              { name: "提现单号", value: data.data.cash_no || '' },
              { name: "提现状态", value: data.data.status_name || '' },
              { name: "提现金额", value: data.data.money + ' 元' || '' },
              { name: "转账平台", value: data.data.bank_name || '' },
              { name: "转账姓名", value: data.data.bank_username || '' },
              { name: "转账账户", value: data.data.bank_accounts || '' },
              { name: "打款金额", value: (data.data.pay_money <= 0) ? '' : (data.data.pay_money + ' 元' || '') },
              { name: "打款时间", value: data.data.pay_time_time || '' },
              { name: "备注", value: data.data.msg || '' },
              { name: "申请时间", value: data.data.add_time_time || '' },
              { name: "更新时间", value: data.data.upd_time_time || '' },
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