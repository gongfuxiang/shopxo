const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,
    data_list: [],
    data_total: 0,
    data_page_total: 0,
    data_page: 1,
    params: null,
    data_base: null,
    shop_category: [],
    nav_active_value: 0,
  },

  onLoad(params) {
    // 启动参数处理
    params = app.launch_params_handle(params);
    this.setData({params: params});

    // 数据加载
    this.get_data();
  },

  // 初始化
  get_data() {
    qq.showLoading({title: "加载中..." });
    var data = {
      "category_id": this.data.nav_tab_value || 0
    };
    qq.request({
      url: app.get_request_url("index", "index", "shop"),
      method: "POST",
      data: data,
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        qq.hideLoading();
        qq.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          this.setData({
            data_base: data.base || null,
            shop_category: data.shop_category || [],
          });

          // 标题名称
          if((this.data.data_base || null) != null && (this.data.data_base.application_name || null) != null)
          {
            qq.setNavigationBarTitle({title: this.data.data_base.application_name});
          }

          // 获取列表数据
          this.get_data_list(1);
        } else {
          this.setData({
            data_list_loding_status: 0,
            data_list_loding_msg: res.data.msg
          });
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        qq.hideLoading();
        qq.stopPullDownRefresh();
        this.setData({
          data_list_loding_status: 2
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 获取数据列表
  get_data_list(is_mandatory) {
    // 分页是否还有数据
    if ((is_mandatory || 0) == 0) {
      if (this.data.data_bottom_line_status == true) {
        return false;
      }
    }

    // 加载loding
    qq.showLoading({title: "加载中..." });

    // 参数
    var data = {
      "category_id": this.data.nav_active_value || 0
    };

    // 获取数据
    qq.request({
      url: app.get_request_url("shoplist", "index", "shop"),
      method: "POST",
      data: data,
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        qq.hideLoading();
        qq.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          // 列表数据处理
          if (data.data.length > 0) {
            if (this.data.data_page <= 1) {
              var temp_data_list = data.data;
            } else {
              var temp_data_list = this.data.data_list;
              var temp_data = data.data;
              for (var i in temp_data) {
                temp_data_list.push(temp_data[i]);
              }
            }
            this.setData({
              data_list: temp_data_list,
              data_total: data.total,
              data_page_total: data.page_total,
              data_list_loding_status: 3,
              data_page: this.data.data_page + 1
            });

            // 是否还有数据
            if (this.data.data_page > 1 && this.data.data_page > this.data.data_page_total)
            {
              this.setData({ data_bottom_line_status: true });
            } else {
              this.setData({data_bottom_line_status: false});
            }
          } else {
            this.setData({
              data_list_loding_status: 0,
            });
            if (this.data.data_page <= 1) {
              this.setData({
                data_list: [],
                data_bottom_line_status: false,
              });
            }
          }
        } else {
          this.setData({
            data_list_loding_status: 0,
            data_list_loding_msg: res.data.msg
          });
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        qq.hideLoading();
        qq.stopPullDownRefresh();
        this.setData({
          data_list_loding_status: 2
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.setData({
      data_page: 1
    });
    this.get_data();
  },

  // 滚动加载
  scroll_lower(e) {
    this.get_data_list();
  },

  // 导航事件
  nav_event(e) {
    this.setData({
      nav_active_value: e.currentTarget.dataset.value || 0,
      data_page: 1
    });

    // 获取列表数据
    this.get_data_list(1);
  },

  // 自定义分享
  onShareAppMessage() {
    var user_id = app.get_user_cache_info('id', 0) || 0;
    return {
      title: this.data.data_base.seo_title || this.data.data_base.application_name || app.data.application_title,
      desc: this.data.data_base.seo_desc || app.data.application_describe,
      path: '/pages/plugins/shop/index/index?referrer=' + user_id
    };
  },
});
