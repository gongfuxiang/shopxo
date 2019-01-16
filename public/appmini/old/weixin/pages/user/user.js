const app = getApp();
Page({
  data: {
    avatar: app.data.default_user_head_src,
    nickname: "用户名",
    customer_service_tel: null,
    common_user_center_notice: null,
    message_total: 0,
    head_nav_list: [
      { name: "订单总数", url: "user-order", count: 0 },
      { name: "商品收藏", url: "user-faovr", count: 0 },
      { name: "我的足迹", url: "user-goods-browse", count: 0 },
      { name: "我的积分", url: "user-integral", count: 0 },
    ],
    user_order_status_list: [
      { name: "待付款", status: 1, count: 0 },
      { name: "待发货", status: 2, count: 0 },
      { name: "待收货", status: 3, count: 0 },
      { name: "已完成", status: 4, count: 0 },
    ],
    nav_lists: [
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
    wx.setNavigationBarTitle({title: app.data.common_pages_title.user});
    this.init();
  },

  init(e) {
    var user = app.get_user_cache_info(this, "init"),
        self = this;
    // 用户未绑定用户则转到登录页面
    var msg = (user == false) ? '授权用户信息' : '绑定手机号码';
    if (user == false || ((user.mobile || null) == null)) {
      wx.showModal({
        title: '温馨提示',
        content: msg,
        confirmText: '确认',
        cancelText: '暂不',
        success: (result) => {
          if(result.confirm) {
            wx.navigateTo({
              url: "/pages/login/login?event_callback=init"
            });
          }
          self.setData({
            avatar: user.avatar || app.data.default_user_head_src,
            nickname: user.nickname || '',
          });
          wx.stopPullDownRefresh();
        },
      });
    } else {
      self.get_data();
    }
  },

  // 获取数据
  get_data() {
    wx.request({
      url: app.get_request_url("center", "user"),
      method: "POST",
      data: {},
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        wx.stopPullDownRefresh();
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

          // 头部导航总数
          var temp_head_nav_list = this.data.head_nav_list;
          temp_head_nav_list[0]['count'] = ((data.user_order_count || 0) == 0) ? 0 : data.user_order_count;
          temp_head_nav_list[1]['count'] = ((data.user_goods_favor_count || 0) == 0) ? 0 : data.user_goods_favor_count;
          temp_head_nav_list[2]['count'] = ((data.user_goods_browse_count || 0) == 0) ? 0 : data.user_goods_browse_count;
          temp_head_nav_list[3]['count'] = ((data.integral || 0) == 0) ? 0 : data.integral;

          this.setData({
            user_order_status_list: temp_user_order_status_list,
            customer_service_tel: data.customer_service_tel || null,
            common_user_center_notice: data.common_user_center_notice || null,
            avatar: (data.avatar != null) ? data.avatar : ((this.data.avatar || null) == null ? app.data.default_user_head_src : this.data.avatar),
            nickname: (data.nickname != null) ? data.nickname : this.data.nickname,
            message_total: ((data.common_message_total || 0) == 0) ? 0 : data.common_message_total,
            head_nav_list: temp_head_nav_list,
            'nav_lists[3].is_show': (data.common_app_is_enable_answer == 1) ? 1 : 0,
          });
        } else {
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        wx.stopPullDownRefresh();
        app.showToast("服务器请求出错");
      }
    });
  },

  // 清除缓存
  clear_storage(e) {
    wx.clearStorage()
    app.showToast("清除缓存成功", "success");
  },

  // 客服电话
  call_event() {
    if(this.data.customer_service_tel == null)
    {
      app.showToast("客服电话有误");
    } else {
      wx.makePhoneCall({ phoneNumber: this.data.customer_service_tel });
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
      wx.previewImage({
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
