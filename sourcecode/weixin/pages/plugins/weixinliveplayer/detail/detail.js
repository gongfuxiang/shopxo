const app = getApp();
Page({
  data: {
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,
    detail: null,
  },

  onLoad(params) {
    // 启动参数处理
    params = app.launch_params_handle(params);

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
      url: app.get_request_url("detail", "search", "weixinliveplayer"),
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
            data_list_loding_status: 3,
            data_bottom_line_status: true,
            data_list_loding_msg: '',
          });

          // 标题
          wx.setNavigationBarTitle({ title: data.data.name });
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

  // 详情图片查看
  detail_images_view_event(e) {
    var value = e.currentTarget.dataset.value || null;
    if (value != null) {
      wx.previewImage({
        current: value,
        urls: [value]
      });
    }
  },

  // 进入直播
  player_event(e) {
    var params = encodeURIComponent(JSON.stringify({type: 'detail'}));
    wx.navigateTo({
      url: `plugin-private://wx2b03c6e691cd7370/pages/live-player-plugin?room_id=${this.data.detail.id}&custom_params=${params}`
    });
  },

  // 海报分享
  share_poster_event() {
    wx.showLoading({ title: '生成中...' });
    wx.request({
      url: app.get_request_url('poster', 'index', 'weixinliveplayer'),
      method: 'POST',
      data: { "id": this.data.detail.id },
      dataType: 'json',
      success: (res) => {
        wx.hideLoading();
        if (res.data.code == 0) {
          wx.previewImage({
            current: res.data.data,
            urls: [res.data.data]
          });
        } else {
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        wx.hideLoading();
        app.showToast("服务器请求出错");
      }
    });
  },

  // 自定义分享
  onShareAppMessage() {
    var user = app.get_user_cache_info() || null;
    var user_id = (user != null && (user.id || null) != null) ? user.id : 0;
    if ((this.data.detail || null) != null)
    {
      var did = this.data.detail.id;
      var name = this.data.detail.name;
    } else {
      var did = 0;
      var name = app.data.application_title;
    }
    return {
      title: name,
      desc: app.data.application_describe,
      path: '/pages/plugins/weixinliveplayer/detail/detail?id=' + did + '&referrer=' + user_id
    };
  },
});