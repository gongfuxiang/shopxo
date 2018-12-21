const app = getApp();
Component({
  mixins: [],
  data: {
    indicator_dots: false,
    indicator_color: 'rgba(0, 0, 0, .3)',
    indicator_active_color: '#e31c55',
    autoplay: true,
    circular: true,
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    data_list: [],
  },
  props: {},
  didMount() {
    this.init();
  },
  didUpdate() {},
  didUnmount() {},
  methods: {
    // 获取数
    init() {
      // 加载loding
      this.setData({
        data_list_loding_status: 1,
      });

      // 加载loding
      my.httpRequest({
        url: app.get_request_url("HomeBanner", "Resources"),
        method: "POST",
        data: {},
        dataType: "json",
        success: res => {
          if (res.data.code == 0) {
            var data = res.data.data;
            this.setData({
              data_list: data,
              indicator_dots: (data.length > 1),
              autoplay: (data.length > 1),
              data_list_loding_status: data.length == 0 ? 0 : 3,
              data_bottom_line_status: true,
            });
          } else {
            this.setData({
              data_list_loding_status: 0,
              data_bottom_line_status: true,
            });

            my.showToast({
              type: "fail",
              content: res.data.msg
            });
          }
        },
        fail: () => {
          this.setData({
            data_list_loding_status: 2,
            data_bottom_line_status: true,
          });

          my.showToast({
            type: "fail",
            content: "服务器请求出错"
          });
        }
      });
    },

    // 操作事件
    banner_event(e) {
      app.operation_event(e);
    },
  },
});
