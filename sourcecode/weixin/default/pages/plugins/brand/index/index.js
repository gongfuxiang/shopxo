const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    params: null,
    data_base: null,
    brand_list: [],
    brand_category_list: [],
    nav_active_value: 0,
  },

  onLoad(params) {
    this.setData({
      params: params,
    });
  },

  onShow() {
    this.get_data();

    // 显示分享菜单
    app.show_share_menu();
  },

  // 获取数据
  get_data() {
    var self = this;
    wx.request({
      url: app.get_request_url("index", "index", "brand"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            data_base: data.base || null,
            brand_list: data.brand_list || [],
            brand_category_list: data.brand_category_list || [],
            data_list_loding_msg: '',
            data_list_loding_status: ((data.brand_list || []).length > 0) ? 3 : 0,
            data_bottom_line_status: ((data.brand_list || []).length > 0),
          });

          // 选中处理
          self.nav_active_handle();
        } else {
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
        }
      },
      fail: () => {
        wx.stopPullDownRefresh();
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

  // 导航事件
  nav_event(e) {
    this.setData({
      nav_active_value: e.currentTarget.dataset.value || 0,
    });
    this.nav_active_handle();
  },

  // 导航选中处理
  nav_active_handle() {
    var value = this.data.nav_active_value;
    var temp_brand_list = this.data.brand_list;
    var count = 0;
    for(var i in temp_brand_list)
    {
      if(value == 0)
      {
        temp_brand_list[i]['is_not_show'] = 0;
        count++;
      } else {
        var is_not_show = (temp_brand_list[i]['brand_category_ids'].indexOf(value) == -1) ? 1 : 0;
        temp_brand_list[i]['is_not_show'] = is_not_show;
        if(is_not_show == 0)
        {
          count++;
        }
      }
    }
    this.setData({
      brand_list: temp_brand_list,
      data_list_loding_status: (count > 0) ? 3 : 0,
      data_bottom_line_status: (count > 0),
    });
  },

  // 自定义分享
  onShareAppMessage() {
    var user_id = app.get_user_cache_info('id', 0) || 0;
    return {
      title: this.data.data_base.seo_title || '品牌 - '+app.data.application_title,
      desc: this.data.data_base.seo_desc || app.data.application_describe,
      path: '/pages/plugins/brand/index/index?referrer=' + user_id
    };
  },

  // 分享朋友圈
  onShareTimeline() {
    var user_id = app.get_user_cache_info('id', 0) || 0;
    return {
      title: this.data.data_base.seo_title || '品牌 - '+app.data.application_title,
      query: 'referrer=' + user_id,
      imageUrl: this.data.data_base.right_images || ''
    };
  },
});