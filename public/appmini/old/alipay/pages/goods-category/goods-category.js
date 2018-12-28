const app = getApp();
Page({
  data: {
    tab_active: 0,
    tab_active_text_color: '#d2364c',
    tab_active_line_color: '#d2364c',
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    data_list: [],
  },

  onShow() {
    my.setNavigationBar({title: app.data.common_pages_title.goods_category});
    this.init();
  },

  // 获取数据
  init() {
    // 加载loding
    this.setData({
      data_list_loding_status: 1,
    });

    // 加载loding
    my.httpRequest({
      url: app.get_request_url("category", "goods"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        my.stopPullDownRefresh();
        if (res.data.code == 0) {
            var data = res.data.data;
            
            // tabs
            for(var i in data) {
              data[i]['title'] = data[i]['name'];
              data[i]['anchor'] = data[i]['id'];
            }

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

            my.showToast({
              type: "fail",
              content: res.data.msg
            });
          }
      },
      fail: () => {
        my.stopPullDownRefresh();
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

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

  // 处理事件
  handle_event(index) {
    this.setData({
      tab_active: index,
    });
  },

  // tab改变
  change_event(index) {
    this.setData({
      tab_active: index,
    });
  },

  // 事件
  category_event(e) {
    my.navigateTo({url: '/pages/goods-search/goods-search?category_id='+e.target.dataset.value});
  }
});