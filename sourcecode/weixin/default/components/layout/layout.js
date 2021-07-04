const app = getApp();
Component({
  data: {
    // 基础配置
    is_first: 1,
    currency_symbol: app.data.currency_symbol,

    // 轮播基础配置
    indicator_color: 'rgba(0, 0, 0, .3)',
    indicator_active_color: '#e31c55',
    circular: true,
  },
  properties: {
    propData: Array
  },
  lifetimes: {
    // 在组件实例进入页面节点树时执行
    attached: function() {
      this.init_config();
    },
  },
  pageLifetimes: {
    // 页面被展示
    show: function() {
      this.init_config();

      // 非首次进入则重新初始化配置接口
      if(this.data.is_first == 0) {
        app.init_config();
      }
      this.setData({is_first: 0});
    },
  },
  methods: {
    // 初始化配置
    init_config(status) {
      if((status || false) == true) {
        this.setData({
          currency_symbol: app.get_config('currency_symbol') || app.data.currency_symbol
        });
      } else {
        app.is_config(this, 'init_config');
      }
    },

    // 链接地址事件
    layout_url_event(e) {
      app.url_event(e);
    },
  },
});