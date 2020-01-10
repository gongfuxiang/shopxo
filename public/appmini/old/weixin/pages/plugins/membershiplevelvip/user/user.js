const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_base: null,
    user_vip: null,
    avatar: app.data.default_user_head_src,
    nickname: "用户名",
    submit_disabled_status: false,

    // 导航
    nav_list: [],
  },

  onLoad(params) {
    this.init();
  },

  onShow() {},

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
              avatar: user.avatar || app.data.default_user_head_src,
              nickname: user.user_name_view || '用户名',
            });
          },
        });
      } else {
        self.setData({
          avatar: user.avatar || app.data.default_user_head_src,
          nickname: user.user_name_view || '用户名',
        });
        
        self.get_data();
      }
    }
  },

  // 获取数据
  get_data() {
    var self = this;
    wx.request({
      url: app.get_request_url("index", "vip", "membershiplevelvip"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          var data_base = data.base || null;
          var user_vip = data.user_vip || null;
          self.setData({
            data_base: data_base,
            user_vip: user_vip,
            avatar: (user_vip != null && (user_vip.icon || null) == null) ? self.data.avatar : user_vip.icon,
            data_list_loding_msg: '',
            data_list_loding_status: 0,
            data_bottom_line_status: false,
          });

          // 导航处理
          var nav_list = [];
          if (data_base != null)
          {
            // 开启会员购买
            if ((data_base.is_user_buy || 0) == 1)
            {
              nav_list.push({
                icon: "/images/plugins/membershiplevelvip/user-center-order-icon.png",
                title: "开通订单",
                url: "/pages/plugins/membershiplevelvip/order/order",
              });
            
              // 开启返佣
              if ((data_base.is_commission || 0) == 1)
              {
                nav_list.push({
                  icon: "/images/plugins/membershiplevelvip/user-center-shouyi-icon.png",
                  title: "收益明细",
                  url: "/pages/plugins/membershiplevelvip/shouyi/shouyi",
                });
              }

              // 开启推广
              if ((data_base.is_propaganda || 0) == 1) {
                nav_list.push({
                  icon: "/images/plugins/membershiplevelvip/user-center-tuandui-icon.png",
                  title: "我的团队",
                  url: "/pages/plugins/membershiplevelvip/tuandui/tuandui",
                });
                nav_list.push({
                  icon: "/images/plugins/membershiplevelvip/user-center-tuiguang-icon.png",
                  title: "推广返利",
                  url: "/pages/plugins/membershiplevelvip/tuiguang/tuiguang",
                });
                nav_list.push({
                  icon: "/images/plugins/membershiplevelvip/user-center-tongji-icon.png",
                  title: "数据统计",
                  url: "/pages/plugins/membershiplevelvip/tongji/tongji",
                });
              }

              nav_list.push({
                icon: "/images/plugins/membershiplevelvip/user-center-shouye-icon.png",
                title: "会员首页",
                url: "/pages/plugins/membershiplevelvip/index/index",
              });
            }
          }
          self.setData({ nav_list: nav_list });
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
        wx.hideLoading();
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

  // 连续开通会员事件
  uservip_renew_event(e) {
    var self = this;
    wx.showModal({
      title: '温馨提示',
      content: '按照原时长和费用续费，确定继续吗？',
      confirmText: '确认',
      cancelText: '暂不',
      success: (result) => {
        if (result.confirm) {
          // 请求生成支付订单
          self.setData({ submit_disabled_status: true });
          wx.showLoading({ title: "处理中..." });
          wx.request({
            url: app.get_request_url("renew", "buy", "membershiplevelvip"),
            method: "POST",
            data: {},
            dataType: "json",
            header: { 'content-type': 'application/x-www-form-urlencoded' },
            success: res => {
              wx.hideLoading();
              self.setData({ submit_disabled_status: false });
              if (res.data.code == 0) {
                // 进入以后会员中心并发起支付
                wx.redirectTo({
                  url: '/pages/plugins/membershiplevelvip/order/order?is_pay=1&order_id=' + res.data.data.id,
                });
              } else {
                if (app.is_login_check(res.data, self, 'uservip_renew_event')) {
                  app.showToast(res.data.msg);
                }
              }
            },
            fail: () => {
              self.setData({ submit_disabled_status: false });
              wx.hideLoading();
              app.showToast("服务器请求出错");
            }
          });
        }
      },
    });
  },

});