const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    params: null,
    user: null,
    data_base: null,
    user_integral: null,
    avatar_default: app.data.default_user_head_src,
  },

  onLoad(params) {
    this.setData({
      params: params,
      user: app.get_user_cache_info(),
    });
  },

  onShow() {
    this.get_data();
  },

  // 获取数据
  get_data() {
    var self = this;
    tt.request({
      url: app.get_request_url("index", "index", "points"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        tt.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_base: data.base || null,
            user_integral: data.user_integral || null,
            data_list_loding_msg: '',
            data_list_loding_status: 0,
            data_bottom_line_status: true,
          });
        } else {
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
        }
      },
      fail: () => {
        tt.stopPullDownRefresh();
        self.setData({
          data_bottom_line_status: false,
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错',
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.get_data();
  },

  // 立即登录
  login_event() {
    var user = app.get_user_info(this, "login_event")
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        tt.showModal({
          title: '温馨提示',
          content: '绑定手机号码',
          confirmText: '确认',
          cancelText: '暂不',
          success: (result) => {
            tt.stopPullDownRefresh();
            if (result.confirm) {
              tt.navigateTo({
                url: "/pages/login/login?event_callback=init"
              });
            }
          },
        });
      }
    }
    this.setData({user: user || null});
  },

  // 图片事件
  right_images_event(e) {
    if((this.data.data_base.right_images_url || null) != null)
    {
      tt.navigateTo({
        url: this.data.data_base.right_images_url,
      });
    }
  },

  // 头像查看
  preview_event() {
    if (app.data.default_user_head_src != this.data.user.avatar) {
      tt.previewImage({
        current: this.data.user.avatar,
        urls: [this.data.user.avatar]
      });
    }
  },

  // 自定义分享
  onShareAppMessage() {
    var user_id = app.get_user_cache_info('id', 0) || 0;
    return {
      title: this.data.data_base.seo_title || '积分商城 - '+app.data.application_title,
      desc: this.data.data_base.seo_desc || '积分抵扣、兑换 - '+app.data.application_describe,
      path: '/pages/plugins/signin/index-detail/index-detail?referrer=' + user_id
    };
  },
});