const app = getApp();
Component({
  data: {
    // 基础配置
    currency_symbol: app.data.currency_symbol,

    // 轮播基础配置
    indicator_color: 'rgba(0, 0, 0, .3)',
    indicator_active_color: '#e31c55',
    circular: true,
  },
  props: {
    data: []
  },
  // 页面被展示
  didMount() {
    // 配置初始化
    this.init_config(true);
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