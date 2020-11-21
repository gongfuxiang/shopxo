const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    nav_active_index: 0,
    data_list: [],
    data_content: null,

    // 基础配置
    category_show_level: 3,
  },

  onShow() {
    swan.setNavigationBarTitle({ title: app.data.common_pages_title.goods_category });

    // 数据加载
    this.init();

    // 初始化配置
    this.init_config();
  },

  // 初始化配置
  init_config(status) {
    if((status || false) == true) {
      this.setData({
        category_show_level: app.get_config('config.category_show_level'),
      });
    } else {
      app.is_config(this, 'init_config');
    }
  },

  // 获取数据
  init() {
    // 加载loding
    this.setData({
      data_list_loding_status: 1
    });

    // 加载loding
    swan.request({
      url: app.get_request_url("category", "goods"),
      method: "POST",
      data: {},
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        swan.stopPullDownRefresh();
        if (res.data.code == 0) {
          var category = res.data.data.category;
          var data_content = [];
          var index = this.data.nav_active_index || 0;
          if (category.length > 0) {
            category[index]['active'] = 'nav-active';
            data_content = category[index];
          }
          this.setData({
            data_list: category,
            data_content: data_content,
            data_list_loding_status: category.length == 0 ? 0 : 3,
            data_bottom_line_status: true
          });
        } else {
          this.setData({
            data_list_loding_status: 0,
            data_bottom_line_status: true
          });
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        swan.stopPullDownRefresh();
        this.setData({
          data_list_loding_status: 2,
          data_bottom_line_status: true
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  },

  // 导航事件
  nav_event(e) {
    var index = e.currentTarget.dataset.index;
    var temp_data = this.data.data_list;
    for (var i in temp_data) {
      temp_data[i]['active'] = index == i ? 'nav-active' : '';
    }
    this.setData({
      data_list: temp_data,
      data_content: temp_data[index],
      nav_active_index: index
    });
  },

  // 事件
  category_event(e) {
    swan.navigateTo({ url: '/pages/goods-search/goods-search?category_id=' + e.currentTarget.dataset.value });
  },

  // 自定义分享
  onShareAppMessage() {
    var user = app.get_user_cache_info() || null;
    var user_id = user != null && (user.id || null) != null ? user.id : 0;
    return {
      title: app.data.application_title,
      desc: app.data.application_describe,
      path: '/pages/goods-category/goods-category?referrer=' + user_id
    };
  }
});