const app = getApp();
Page({
  data: {
    avatar: app.data.default_user_head_src,
    nickname: "用户名",
    integral: 0,
    deadline: 0,
    agreement_url: '',
    customer_service_tel: null,
    common_user_center_notice: null,
    
    lists: [
      {
        url: "user-address",
        icon: "user-nav-address-icon",
        is_show: 1,
        name: "我的地址"
      },
      {
        url: "user-order",
        icon: "user-nav-booking-order-icon",
        is_show: 1,
        name: "我的订单"
      },
      {
        url: "user-faovr",
        icon: "user-nav-faovr-icon",
        is_show: 1,
        name: "我的收藏"
      },
      {
        url: "user-answer-list",
        icon: "user-nav-answer-icon",
        is_show: 1,
        name: "我的留言"
      }
    ]
  },

  onShow() {
    my.setNavigationBar({title: app.data.common_pages_title.user});
    this.init();
  },

  init(e) {
    var user = app.GetUserInfo(this, "init"),
      self = this;
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if ((user.mobile || null) == null) {
        my.confirm({
          title: '温馨提示',
          content: '绑定手机号码',
          confirmButtonText: '确认',
          cancelButtonText: '暂不',
          success: (result) => {
            if(result.confirm) {
              my.navigateTo({
                url: "/pages/login/login?event_callback=init"
              });
            }
            self.setData({
              avatar: user.avatar,
              nickname: user.nickname,
            });
            self.get_data();
          },
        });
      } else {
        self.get_data();
      }
    }
  },

  // 获取数据
  get_data() {
    my.httpRequest({
      url: app.get_request_url("Center", "User"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        my.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          this.setData({
            customer_service_tel: data.customer_service_tel || null,
            common_user_center_notice: data.common_user_center_notice || null,
          });
          if(data.avatar != null)
          {
            this.setData({avatar: data.avatar});
          }
          if(data.nickname != null)
          {
            this.setData({nickname: data.nickname});
          }
          if(data.integral != null)
          {
            this.setData({integral: data.integral});
          }
        } else {
          my.showToast({
            type: "fail",
            content: res.data.msg
          });
        }
      },
      fail: () => {
        my.stopPullDownRefresh();
        my.showToast({
          type: "fail",
          content: "服务器请求出错"
        });
      }
    });
  },

  // 清除缓存
  clear_storage(e) {
    my.clearStorage()
    my.showToast({
      type: "success",
      content: "清除缓存成功"
    });
  },

  // 客服电话
  call_event() {
    if(this.data.customer_service_tel == null)
    {
      my.showToast({
        type: "fail",
        content: "客服电话有误"
      });
    } else {
      my.makePhoneCall({ number: this.data.customer_service_tel });
    }
  },

  // 下拉刷新
  onPullDownRefresh(e) {
    this.init(e);
  },

  // 头像查看
  preview_event() {
    if(app.data.default_user_head_src != this.data.avatar)
    {
      my.previewImage({
        current: 0,
        urls: [this.data.avatar]
      });
    }
  },

  // 头像加载错误
  user_avatar_error(e) {
    this.setData({avatar: app.data.default_user_head_src});
  },
});
