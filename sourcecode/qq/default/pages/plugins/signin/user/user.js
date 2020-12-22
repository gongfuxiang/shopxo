const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_base: null,
    nav_list: [],
  },

  onLoad(params) {},

  onShow() {
    this.init();
  },

  init(e) {
    var user = app.get_user_info(this, "init"),
      self = this;
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        qq.showModal({
          title: '温馨提示',
          content: '绑定手机号码',
          confirmText: '确认',
          cancelText: '暂不',
          success: (result) => {
            qq.stopPullDownRefresh();
            if (result.confirm) {
              qq.navigateTo({
                url: "/pages/login/login?event_callback=init"
              });
            }
          },
        });
      } else {
        self.get_data();
      }
    }
  },

  // 获取数据
  get_data() {
    var self = this;
    qq.request({
      url: app.get_request_url("center", "user", "signin"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        qq.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          // 是否开启组队
          var temp_nav_list = [
            {
              icon: "/images/plugins/signin/user-signin-icon.png",
              title: "我的签到",
              url: "/pages/plugins/signin/user-signin/user-signin",
            }
          ];
          if((data.base || null) != null && (data.base.is_team || 0) == 1)
          {
            temp_nav_list.push({
              icon: "/images/plugins/signin/user-qrcode-icon.png",
              title: "签到码管理",
              url: "/pages/plugins/signin/user-qrcode/user-qrcode",
            });
          }
          self.setData({
            data_base: data.base || null,
            nav_list: temp_nav_list,
            data_list_loding_msg: '',
            data_list_loding_status: 0,
            data_bottom_line_status: false,
          });
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
        qq.stopPullDownRefresh();
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
});