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
    //params['id'] = 4;
    this.setData({ params: params });
    this.init();
  },

  onShow() { },

  init() {
    var self = this;
    qq.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    qq.request({
      url: app.get_request_url("detail", "recharge", "wallet"),
      method: "POST",
      data: {
        id: this.data.params.id
      },
      dataType: "json",
      success: res => {
        qq.hideLoading();
        qq.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            detail: data.data,
            detail_list: [
              { name: "充值单号", value: data.data.recharge_no || '' },
              { name: "充值状态", value: data.data.status_name || '' },
              { name: "充值金额", value: data.data.money+' 元' || '' },
              { name: "支付金额", value: (data.data.pay_money <= 0) ? '' : (data.data.pay_money + ' 元'|| '') },
              { name: "支付方式", value: data.data.payment_name || '' },
              { name: "创建时间", value: data.data.add_time_time || '' },
              { name: "支付时间", value: data.data.pay_time_time || '' },
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
        qq.hideLoading();
        qq.stopPullDownRefresh();
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