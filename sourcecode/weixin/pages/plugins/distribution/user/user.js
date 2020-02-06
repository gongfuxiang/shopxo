const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_base: null,
    user_level: null,
    extraction: null,
    avatar: app.data.default_user_head_src,
    nickname: "用户名",
    submit_disabled_status: false,

    // 导航
    nav_list: [],
  },

  onLoad(params) {
    this.setData({ nav_list: this.nav_list_data() });
  },

  onShow() {
    this.init();
  },

  init(e) {
    var user = app.get_user_info(this, "init"),
      self = this;
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        wx.showModal({
          title: '温馨提示',
          content: '绑定手机号码',
          confirmText: '确认',
          cancelText: '暂不',
          success: (result) => {
            wx.stopPullDownRefresh();
            if (result.confirm) {
              wx.navigateTo({
                url: "/pages/login/login?event_callback=init"
              });
            }
            self.setData({
              avatar: ((self.data.avatar || null) == null) ? (user.avatar || app.data.default_user_head_src) : self.data.avatar,
              nickname: user.nickname || '用户名',
            });
          },
        });
      } else {
        self.setData({
          avatar: ((self.data.avatar || null) == null) ? (user.avatar || app.data.default_user_head_src) : self.data.avatar,
          nickname: user.nickname || '用户名',
        });
        
        self.get_data();
      }
    }
  },

  // 导航数据
  nav_list_data() {
    return [
      {
        icon: "/images/plugins/distribution/user-center-order-icon.png",
        title: "分销订单",
        url: "/pages/plugins/distribution/order/order",
      },
      {
        icon: "/images/plugins/distribution/user-center-profit-icon.png",
        title: "收益明细",
        url: "/pages/plugins/distribution/profit/profit",
      },
      {
        icon: "/images/plugins/distribution/user-center-team-icon.png",
        title: "我的团队",
        url: "/pages/plugins/distribution/team/team",
      },
      {
        icon: "/images/plugins/distribution/user-center-poster-icon.png",
        title: "推广返利",
        url: "/pages/plugins/distribution/poster/poster",
      },
      {
        icon: "/images/plugins/distribution/user-center-statistics-icon.png",
        title: "数据统计",
        url: "/pages/plugins/distribution/statistics/statistics",
      }
    ];
  },

  // 获取数据
  get_data() {
    var self = this;
    wx.request({
      url: app.get_request_url("index", "user", "distribution"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          var data_base = data.base || null;
          var user_level = data.user_level || null;
          self.setData({
            data_base: data_base,
            user_level: user_level,
            extraction: data.extraction || null,
            avatar: (user_level == null || (user_level.images_url || null) == null) ? self.data.avatar : user_level.images_url,
            data_list_loding_msg: '',
            data_list_loding_status: 0,
            data_bottom_line_status: false,
          });

          // 导航
          var temp_data_list = self.nav_list_data();

          // 等级介绍
          if (data_base != null && (data_base.is_show_introduce || 0) == 1)
          {
            temp_data_list.push({
              icon: "/images/plugins/distribution/user-center-introduce-icon.png",
              title: "等级介绍",
              url: "/pages/plugins/distribution/introduce/introduce",
            });
          }
          self.setData({ nav_list: temp_data_list });
        } else {
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
          if (app.is_login_check(res.data, self, 'get_data')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        wx.stopPullDownRefresh();
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

  // 头像查看
  preview_event() {
    if (app.data.default_user_head_src != this.data.avatar) {
      wx.previewImage({
        current: this.data.avatar,
        urls: [this.data.avatar]
      });
    }
  },

  // 头像加载错误
  user_avatar_error(e) {
    this.setData({ avatar: app.data.default_user_head_src });
  },
});