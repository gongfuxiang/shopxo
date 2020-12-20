const app = getApp();
Page({
  data: {
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,
    detail: null,
    detail_list: [],
    express_data: [],
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
      url: app.get_request_url("detail", "userqrcode", "signin"),
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
              { name: "是否启用", value: data.data.is_enable_name || '' },
              { name: "邀请人奖励积分", value: data.data.reward_master || '' },
              { name: "受邀人奖励积分", value: data.data.reward_invitee || '' },
              { name: "联系人姓名", value: data.data.name || '' },
              { name: "联系人电话", value: data.data.tel || '' },
              { name: "联系人地址", value: data.data.address || '' },
              { name: "创建时间", value: data.data.add_time || '' },
              { name: "更新时间", value: data.data.upd_time || '' },
            ],
            express_data: [
              { name: "快递名称", value: data.data.express_name || '' },
              { name: "快递单号", value: data.data.express_number || '' },
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