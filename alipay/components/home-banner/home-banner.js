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
    banner_list: [],
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
        url: app.get_request_url("Banner", "Index"),
        method: "POST",
        data: {},
        dataType: "json",
        success: res => {
          if (res.data.code == 0) {
            var data = res.data.data;
            this.setData({
              banner_list: data,
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

    // 轮播图事件
    banner_event(e) {
      var value = e.target.dataset.value || null;
      var type = parseInt(e.target.dataset.type);
      if (value != null) {
        switch(type) {
          // web
          case 0 :
            my.navigateTo({url: '/pages/web-view/web-view?url='+value});
            break;

          // 内部页面
          case 1 :
            my.navigateTo({url: value});
            break;

          // 跳转到外部小程序
          case 2 :
            my.navigateToMiniProgram({appId: value});
            break;
        }
      }
    },
  },
});
