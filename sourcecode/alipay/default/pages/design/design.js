const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    params: null,
    data: null,
    layout_data: []
  },

  onLoad(params) {
    // 启动参数处理
    params = app.launch_params_handle(params);
    this.setData({
      params: params,
    });
  },

  onShow() {
    this.get_data();
  },

  // 获取数据
  get_data() {
    var self = this;
    my.request({
      url: app.get_request_url("index", "design"),
      method: "POST",
      data: {"id": this.data.params.id || 0},
      dataType: "json",
      success: res => {
        my.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data: ((data.data || null) != null && data.data.length != 0) ? data.data : null,
            layout_data: data.layout_data || [],
            data_list_loding_msg: '',
            data_list_loding_status: 0,
            data_bottom_line_status: true,
          });

          // 标题名称
          if((this.data.data || null) != null)
          {
            my.setNavigationBar({title: this.data.data.name});
          }
        } else {
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
        }
      },
      fail: () => {
        my.stopPullDownRefresh();
        self.setData({
          data_bottom_line_status: false,
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错',
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.get_data();
  },

  // 搜索事件
  search_input_event(e) {
    var keywords = e.detail.value || null;
    if (keywords == null) {
      app.showToast('请输入搜索关键字');
      return false;
    }

    // 进入搜索页面
    my.navigateTo({
      url: '/pages/goods-search/goods-search?keywords='+keywords
    });
  },

  // 自定义分享
  onShareAppMessage() {
    var user_id = app.get_user_cache_info('id', 0) || 0;
    return {
      title: this.data.data.name || this.data.data.seo_title || app.data.application_title,
      desc: this.data.data.seo_desc || app.data.application_describe,
      path: '/pages/design/design?id='+this.data.data.id+'&referrer=' + user_id
    };
  },
});