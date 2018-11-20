const app = getApp();
Page({
  data: {
    tab_active: 0,
    tab_active_text_color: '#d2364c',
    tab_active_line_color: '#d2364c',
    tabs: [],
    content: [],
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    data_list: [],
    params: null,
  },

  onLoad(params) {
    this.setData({params: params});
    this.init();
  },

  onShow() {
    my.setNavigationBar({title: app.data.common_pages_title.goods_category});
  },

  // 获取数据
  init() {
    // 加载loding
    this.setData({
      data_list_loding_status: 1,
    });

    // 加载loding
    my.httpRequest({
      url: app.get_request_url("GoodsCategory", "Resources"),
      method: "POST",
      data: this.data.params,
      dataType: "json",
      success: res => {
        if (res.data.code == 0) {
            var data = res.data.data;
            
            // tabs
            var temp_tabs = [];
            for(var i in data) {
              temp_tabs[i] = {"title": data[i]['name']};
            }

            this.setData({
              tabs: temp_tabs,
              content: data.length == 0 ? [] : data[0],
              data_list: data,
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

  // tab改变
  change_event(index) {
    this.setData({
      tab_active: index,
      content: this.data.data_list[index],
    });

    console.log('onChange', index, this.data.content);
  },
});