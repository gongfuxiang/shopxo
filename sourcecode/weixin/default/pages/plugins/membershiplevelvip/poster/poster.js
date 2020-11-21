const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    data_list_loding_msg: '加载中...',
    data_bottom_line_status: false,
    user_share_qrode: null,
    user_share_url: null,
  },

  onLoad() {
    this.init();
  },

  init() {
    var self = this;
    wx.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    wx.request({
      url: app.get_request_url("index", "poster", "membershiplevelvip"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            user_share_qrode: data.user_share_qrode || null,
            user_share_url: data.user_share_url || null,
            data_list_loding_status: 3,
            data_bottom_line_status: true,
            data_list_loding_msg: '',
          });

          // 是否全部没数据
          if (self.data.user_share_qrode == null && self.data.user_share_url == null)
          {
            self.setData({
              data_list_loding_status: 0,
              data_bottom_line_status: false,
            });
          }
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

  // 二维码事件
  qrcode_event(e) {
    if ((this.data.user_share_qrode || null) != null) {
      wx.previewImage({
        current: this.data.user_share_qrode,
        urls: [this.data.user_share_qrode]
      });
    } else {
      app.showToast('二维码地址有误');
    }
  },

  // url事件
  url_event(e) {
    if ((this.data.user_share_url || null) != null) {
      wx.setClipboardData({
        data: this.data.user_share_url,
        success(res) {
          app.showToast('复制成功', 'success');
        }
      })
    } else {
      app.showToast('链接地址有误');
    }
  },
});