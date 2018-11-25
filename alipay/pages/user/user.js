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
    user_order_status_list: [
      { name: "待付款", status: 1, count: 0 },
      { name: "待发货", status: 2, count: 0 },
      { name: "待收货", status: 3, count: 0 },
      { name: "已完成", status: 4, count: 0 },
    ],
    lists: [
      {
        url: "user-order",
        icon: "user-nav-order-icon",
        is_show: 1,
        name: "我的订单",
      },
      {
        url: "user-address",
        icon: "user-nav-address-icon",
        is_show: 1,
        name: "我的地址"
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

          // 订单数量处理
          var temp_user_order_status_list = this.data.user_order_status_list;
          if ((data.user_order_status || null) != null && data.user_order_status.length > 0) {
            for (var i in temp_user_order_status_list) {
              for (var t in data.user_order_status) {
                if (temp_user_order_status_list[i]['status'] == data.user_order_status[t]['status']) {
                  temp_user_order_status_list[i]['count'] = data.user_order_status[t]['count'];
                  break;
                }
              }
            }
          }

          this.setData({
            user_order_status_list: temp_user_order_status_list,
            customer_service_tel: data.customer_service_tel || null,
            common_user_center_notice: data.common_user_center_notice || null,
            avatar: (data.avatar != null) ? data.avatar : this.data.avatar,
            nickname: (data.nickname != null) ? data.nickname : this.data.nickname,
            integral: (data.integral != null) ? data.integral : this.data.integral,
          });
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
