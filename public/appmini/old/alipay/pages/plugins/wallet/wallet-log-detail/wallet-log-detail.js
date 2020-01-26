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
    //params['id'] = 3;
    this.setData({ params: params });
    this.init();
  },

  onShow() { },

  init() {
    var self = this;
    my.showLoading({ content: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    my.request({
      url: app.get_request_url("detail", "walletlog", "wallet"),
      method: "POST",
      data: {
        id: this.data.params.id
      },
      dataType: "json",
      success: res => {
        my.hideLoading();
        my.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            detail: data.data,
            detail_list: [
              { name: "业务类型", value: data.data.business_type_name || '' },
              { name: "操作类型", value: data.data.operation_type_name || '' },
              { name: "金额类型", value: data.data.money_type_name || '' },
              { name: "操作金额", value: data.data.operation_money + ' 元' || '' },
              { name: "原始金额", value: data.data.original_money + ' 元' || '' },
              { name: "最新金额", value: data.data.latest_money+' 元' || '' },
              { name: "变更说明", value: data.data.msg || '' },
              { name: "操作时间", value: data.data.add_time_time || '' },
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

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },
});