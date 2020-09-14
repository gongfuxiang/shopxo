const app = getApp();
Component({
  data: {
    popup_status: false,
    data: [],
  },
  methods: {
    // 弹层开启
    quick_open_event(e) {
      var data = app.get_config('quick_nav') || [];
      this.setData({ popup_status: true, data: data });
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
