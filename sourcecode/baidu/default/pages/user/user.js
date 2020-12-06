const app = getApp();
Page({
  data: {
    avatar: app.data.default_user_head_src,
    nickname: "用户名",
    message_total: 0,
    head_nav_list: [
      { name: "订单总数", url: "user-order", count: 0 },
      { name: "商品收藏", url: "user-faovr", count: 0 },
      { name: "我的足迹", url: "user-goods-browse", count: 0 },
      { name: "我的积分", url: "user-integral", count: 0 },
    ],
    user_order_status_list: [
      { name: "待付款", status: 1, count: 0, url: "/pages/user-order/user-order?status=1" },
      { name: "待发货", status: 2, count: 0, url: "/pages/user-order/user-order?status=2" },
      { name: "待收货", status: 3, count: 0, url: "/pages/user-order/user-order?status=3" },
      { name: "已完成", status: 4, count: 0, url: "/pages/user-order/user-order?status=4" },
      { name: "退款/售后", status: 101, count: 0, url: "/pages/user-orderaftersale/user-orderaftersale" },
    ],
    nav_lists: [{
      url: "user-order",
      icon: "user-nav-order-icon",
      name: "我的订单"
    }],

    // 远程自定义导航
    navigation: [],

    // 基础配置
    common_app_customer_service_tel: null,
    common_user_center_notice: null,
    common_app_is_online_service: 0,
    common_app_is_head_vice_nav: 0,
  },

  onShow() {
    swan.setNavigationBarTitle({ title: app.data.common_pages_title.user });

    // 数据加载
    this.init();

    // 初始化配置
    this.init_config();
  },

  // 初始化配置
  init_config(status) {
    if((status || false) == true) {
      this.setData({
        common_app_customer_service_tel: app.get_config('config.common_app_customer_service_tel'),
        common_user_center_notice: app.get_config('config.common_user_center_notice'),
        common_app_is_online_service: app.get_config('config.common_app_is_online_service'),
        common_app_is_head_vice_nav: app.get_config('config.common_app_is_head_vice_nav'),
      });
    } else {
      app.is_config(this, 'init_config');
    }
  },

  // 获取数据
  init(e) {
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
          success: (result) => {
            swan.stopPullDownRefresh();
            if(result.confirm) {
              swan.navigateTo({
                url: "/pages/login/login?event_callback=init"
              });
            }
            self.setData({
              avatar: user.avatar || app.data.default_user_head_src,
              nickname: user.user_name_view || user.nickname || '用户名',
            });
          },
        });
      } else {
        self.setData({
          avatar: user.avatar || app.data.default_user_head_src,
          nickname: user.user_name_view || user.nickname || '用户名',
        });

        self.get_data();
      }
    }
  },

  // 获取数据
  get_data() {
    swan.request({
      url: app.get_request_url("center", "user"),
      method: "POST",
      data: {},
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        swan.stopPullDownRefresh();
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
          temp_head_nav_list[0]['count'] = (data.user_order_count || 0) == 0 ? 0 : data.user_order_count;
          temp_head_nav_list[1]['count'] = (data.user_goods_favor_count || 0) == 0 ? 0 : data.user_goods_favor_count;
          temp_head_nav_list[2]['count'] = (data.user_goods_browse_count || 0) == 0 ? 0 : data.user_goods_browse_count;
          temp_head_nav_list[3]['count'] = (data.integral || 0) == 0 ? 0 : data.integral;

          this.setData({
            user_order_status_list: temp_user_order_status_list,
            avatar: (data.avatar || null) != null ? data.avatar : (this.data.avatar || null) == null ? app.data.default_user_head_src : this.data.avatar,
            nickname: ((data.user_name_view || null) != null) ? data.user_name_view : this.data.nickname,
            message_total: (data.common_message_total || 0) == 0 ? 0 : data.common_message_total,
            head_nav_list: temp_head_nav_list,
            navigation: data.navigation || [],
          });

          // 导航购物车处理
          var cart_total = data.common_cart_total || 0;
          if (cart_total <= 0) {
            app.set_tab_bar_badge(2, 0);
          } else {
            app.set_tab_bar_badge(2, 1, cart_total);
          }
        } else {
          if(app.is_login_check(res.data, this, 'get_data'))
          {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        swan.stopPullDownRefresh();
        app.showToast("服务器请求出错");
      }
    });
  },

  // 清除缓存
  clear_storage(e) {
    // 获取uuid重新存储缓存，一定情况下确保用户的uuid不改变
    var uuid = swan.getStorageSync(this.data.cache_user_uuid_key) || null;

    // 清除所有缓存
    swan.clearStorage();
    app.showToast("清除缓存成功", "success");

    // 重新存储用户uuid缓存
    if(uuid != null) {
      swan.setStorage({
        key: app.data.cache_user_uuid_key,
        data: uuid
      });
    }
  },

  // 客服电话
  call_event() {
    if (this.data.common_app_customer_service_tel == null) {
      app.showToast("客服电话有误");
    } else {
      app.call_tel(this.data.common_app_customer_service_tel);
    }
  },

  // 下拉刷新
  onPullDownRefresh(e) {
    this.init(e);
  },

  // 头像查看
  preview_event() {
    if (app.data.default_user_head_src != this.data.avatar) {
      swan.previewImage({
        current: this.data.avatar,
        urls: [this.data.avatar]
      });
    }
  },

  // 头像加载错误
  user_avatar_error(e) {
    this.setData({ avatar: app.data.default_user_head_src });
  },

  // 远程自定义导航事件
  navigation_event(e) {
    app.operation_event(e);
  }
});