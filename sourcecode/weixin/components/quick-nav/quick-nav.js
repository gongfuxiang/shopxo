const app = getApp();
Component({
  data: {
    popup_status: false,
    data_list: [],
    is_first: 1,
  },
  pageLifetimes: {
    // 页面被展示
    show: function() {
      this.init_config();

      // 非首次进入则重新初始化配置接口
      if(this.data.is_first == 0) {
        app.init_config();
      }

      // 首次初始化状态
      this.setData({ is_first: 0 });
    },
  },
  methods: {
    // 初始化配置
    init_config(status) {
      if((status || false) == true) {
        this.setData({ data_list: app.get_config('quick_nav') || [] });
      } else {
        app.is_config(this, 'init_config');
      }
    },

    // 弹层开启
    quick_open_event(e) {
      this.setData({popup_status: true, data_list: app.get_config('quick_nav') || []});
    },

    // 弹层关闭
    quick_close_event(e) {
      this.setData({ popup_status: false });
    },

    // 操作事件
    navigation_event(e) {
      app.operation_event(e);
    },
  },
});
