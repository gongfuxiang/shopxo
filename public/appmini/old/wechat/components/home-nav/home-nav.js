const app = getApp();
Component({
  mixins: [],
  props: {},
  data: {
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    data_list: [],
  },
  didMount() {
    this.init();
  },
  didUpdate(){},
  didUnmount(){},
  methods:{
    init() {
      // 加载loding
      this.setData({
        data_list_loding_status: 1,
      });

      // 加载loding
      wx.request({
        url: app.get_request_url("index", "navigation"),
        method: "POST",
        data: {},
        dataType: "json",
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: res => {
          if (res.data.code == 0) {
            var data = res.data.data;
            this.setData({
              data_list: data,
              data_list_loding_status: data.length == 0 ? 0 : 3,
              data_bottom_line_status: true,
            });
          } else {
            this.setData({
              data_list_loding_status: 0,
              data_bottom_line_status: true,
            });

            wx.showToast({
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

          wx.showToast({
            type: "fail",
            content: "服务器请求出错"
          });
        }
      });
    },

    // 操作事件
    nav_event(e) {
      app.operation_event(e);
    },
  }
});
