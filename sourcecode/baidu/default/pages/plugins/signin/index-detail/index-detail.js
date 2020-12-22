const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    params: null,
    user: null,
    data_base: null,
    data: null,
    team_signin_data: null,
    user_signin_data: null,
    is_already_coming: 0,
    is_success_tips: 0,
    coming_integral: 0
  },

  onReady() {},

  onLoad(params) {
    //params['id'] = 1;
    this.setData({
      params: params,
      user: app.get_user_cache_info()
    });
  },

  onShow() {
    this.get_data();
  },

  // 获取数据
  get_data() {
    var self = this;
    swan.request({
      url: app.get_request_url("detail", "index", "signin"),
      method: "POST",
      data: {
        id: this.data.params.id || 0
      },
      dataType: "json",
      success: res => {
        swan.stopPullDownRefresh();

        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_base: data.base || null,
            data: data.data || null,
            team_signin_data: data.team_signin_data || null,
            user_signin_data: data.user_signin_data || null,
            is_already_coming: (data.user_signin_data || null) != null && (data.user_signin_data.integral || 0) > 0 ? 1 : 0,
            data_list_loding_msg: '',
            data_list_loding_status: 0,
            data_bottom_line_status: true
          });
        } else {
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg
          });
        }
      },
      fail: () => {
        swan.stopPullDownRefresh();
        self.setData({
          data_bottom_line_status: false,
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错'
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.get_data();
  },

  // 初始化
  init() {
    var user = app.get_user_info(this, "init"),
        self = this;

    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        swan.showModal({
          title: '温馨提示',
          content: '绑定手机号码',
          confirmText: '确认',
          cancelText: '暂不',
          success: result => {
            swan.stopPullDownRefresh();

            if (result.confirm) {
              swan.navigateTo({
                url: "/pages/login/login?event_callback=init"
              });
            }
          }
        });
      } else {
        return true;
      }
    }

    return false;
  },

  // 签到
  coming_event(e) {
    if (this.data.is_already_coming != 1 && this.init()) {
      var self = this;
      swan.showLoading({
        title: "处理中..."
      });
      swan.request({
        url: app.get_request_url("coming", "index", "signin"),
        method: "POST",
        data: {
          id: this.data.data.id
        },
        dataType: "json",
        success: res => {
          swan.hideLoading();

          if (res.data.code == 0) {
            this.setData({
              is_already_coming: 1,
              is_success_tips: 1,
              coming_integral: res.data.data
            });
            this.get_data();
          } else {
            if (app.is_login_check(res.data, self, 'team_request')) {
              app.showToast(res.data.msg);
            }
          }
        },
        fail: () => {
          swan.hideLoading();
          app.showToast("服务器请求出错");
        }
      });
    }
  },

  // 签到成功提示关闭
  coming_success_close_event(e) {
    this.setData({
      is_success_tips: 0
    });
  },

  // 组队事件
  team_event(e) {
    if (this.init()) {
      var self = this;
      swan.showLoading({
        title: "处理中..."
      });
      swan.request({
        url: app.get_request_url("team", "userqrcode", "signin"),
        method: "POST",
        data: {},
        dataType: "json",
        success: res => {
          swan.hideLoading();

          if (res.data.code == 0) {
            switch (res.data.data.status) {
              // 组队成功
              case 0:
                // 设置签到码id
                var temp_params = this.data.params;
                temp_params['id'] = res.data.data.qrcode_id;
                this.setData({
                  params: temp_params
                }); // 重新拉取数据

                this.get_data();
                break;
              // 需要填写联系人信息

              case 1:
                swan.navigateTo({
                  url: '/pages/plugins/signin/user-qrcode-saveinfo/user-qrcode-saveinfo?is_team=1'
                });
                break;
            }
          } else {
            if (app.is_login_check(res.data, self, 'team_request')) {
              app.showToast(res.data.msg);
            }
          }
        },
        fail: () => {
          swan.hideLoading();
          app.showToast("服务器请求出错");
        }
      });
    }
  },

  // 图片事件
  right_images_event(e) {
    if ((this.data.data.right_images_url || null) != null) {
      swan.navigateTo({
        url: this.data.data.right_images_url
      });
    }
  },

  // 自定义分享
  onShareAppMessage() {
    var user_id = app.get_user_cache_info('id', 0) || 0;
    return {
      title: this.data.data.seo_title || '签到 - ' + app.data.application_title,
      desc: this.data.data.seo_desc || '签到获得积分奖励 - ' + app.data.application_describe,
      path: '/pages/plugins/signin/index-detail/index-detail?id=' + this.data.data.id + '&referrer=' + user_id
    };
  },

  // 分享朋友圈
  onShareTimeline() {
    var user_id = app.get_user_cache_info('id', 0) || 0;
    return {
      title: this.data.data.seo_title || '签到 - ' + app.data.application_title,
      query: 'id=' + this.data.data.id + '&referrer=' + user_id,
      imageUrl: this.data.data.right_images || ''
    };
  }

});