const app = getApp();
Page({
  data: {
    price_symbol: app.data.price_symbol,
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    data_list: [],
    data_page_total: 0,
    data_page: 1,
    params: null,
    post_data: {},
    is_show_popup_form: false,
    popup_form_loading_status: false,
    search_nav_sort_list: [{ name: "综合", field: "default", sort: "asc", "icon": null }, { name: "销量", field: "sales_count", sort: "asc", "icon": "default" }, { name: "价格", field: "min_price", sort: "asc", "icon": "default" }]
  },

  onLoad(params) {
    this.setData({ params: params, post_data: params });
    this.init();
  },

  onShow() {
    swan.setNavigationBarTitle({ title: app.data.common_pages_title.goods_search });
  },

  // 初始化
  init() {
    // 获取数据
    this.get_data_list();
  },

  // 搜索
  search_event() {
    this.setData({
      data_list: [],
      data_page: 1
    });
    this.get_data_list(1);
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
    swan.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    // 参数
    var params = this.data.params;
    var post_data = this.data.post_data;
    post_data['page'] = this.data.data_page;
    post_data['category_id'] = params['category_id'] || 0;

    // 获取数据
    swan.request({
        url: app.get_request_url("index", "search"),
        method: "POST",
        data: post_data,
        dataType: "json",
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: res => {
            swan.hideLoading();
            swan.stopPullDownRefresh();
            if (res.data.code == 0) {
                if (res.data.data.data.length > 0) {
                    if (this.data.data_page <= 1) {
                        var temp_data_list = res.data.data.data;
                    } else {
                        var temp_data_list = this.data.data_list;
                        var temp_data = res.data.data.data;
                        for (var i in temp_data) {
                            temp_data_list.push(temp_data[i]);
                        }
                    }
                    this.setData({
                        data_list: temp_data_list,
                        data_total: res.data.data.total,
                        data_page_total: res.data.data.page_total,
                        data_list_loding_status: 3,
                        data_page: this.data.data_page + 1
                    });

                    // 是否还有数据
                    if (this.data.data_page > 1 && this.data.data_page > this.data.data_page_total) {
                        this.setData({ data_bottom_line_status: true });
                    } else {
                        this.setData({ data_bottom_line_status: false });
                    }
                } else {
                    this.setData({
                        data_list_loding_status: 0
                    });
                    if (this.data.data_page <= 1) {
                        this.setData({
                            data_list: [],
                            data_bottom_line_status: false
                        });
                    }
                }

                // 页面信息设置
                if(post_data['page'] == 1) {
                    this.set_page_info();
                }
            } else {
                this.setData({
                data_list_loding_status: 0
                });

                app.showToast(res.data.msg);
            }
        },
        fail: () => {
            swan.hideLoading();
            swan.stopPullDownRefresh();

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
    this.get_data_list(1);
  },

  // 滚动加载
  scroll_lower(e) {
    this.get_data_list();
  },

  // 搜索条件
  form_submit_event(e) {
    this.setData({ post_data: e.detail.value, data_page: 1 });
    this.popup_form_event_close();
    this.get_data_list(1);
  },

  // 筛选条件关闭
  popup_form_event_close(e) {
    this.setData({ is_show_popup_form: false });
  },

  // 筛选条件开启
  popup_form_event_show(e) {
    this.setData({ is_show_popup_form: true });
  },

  // 筛选
  nav_sort_event(e) {
    var index = e.currentTarget.dataset.index || 0;
    var temp_post_data = this.data.post_data;
    var temp_search_nav_sort = this.data.search_nav_sort_list;
    var temp_sort = temp_search_nav_sort[index]['sort'] == 'desc' ? 'asc' : 'desc';
    for (var i in temp_search_nav_sort) {
      if (i != index) {
        if (temp_search_nav_sort[i]['icon'] != null) {
          temp_search_nav_sort[i]['icon'] = 'default';
        }
        temp_search_nav_sort[i]['sort'] = 'desc';
      }
    }

    temp_search_nav_sort[index]['sort'] = temp_sort;
    if (temp_search_nav_sort[index]['icon'] != null) {
      temp_search_nav_sort[index]['icon'] = temp_sort;
    }

    temp_post_data['order_by_field'] = temp_search_nav_sort[index]['field'];
    temp_post_data['order_by_type'] = temp_sort;

    this.setData({
      post_data: temp_post_data,
      search_nav_sort_list: temp_search_nav_sort,
      data_page: 1
    });
    this.get_data_list(1);
  },

  // web页面信息设置
  set_page_info() {
    swan.setPageInfo({
      title: app.data.application_title+' - 商品搜索',
      keywords: app.data.application_describe,
      description: app.data.application_describe,
      image: (this.data.data_list.length == 0) ? [] : this.data.data_list.map(function (v) { return v.images;}).slice(0,3)
    });
  },

  // 自定义分享
  onShareAppMessage() {
    var user = app.get_user_cache_info() || null;
    var user_id = (user != null && (user.id || null) != null) ? user.id : 0;
    return {
      title: app.data.application_title,
      desc: app.data.application_describe,
      path: '/pages/goods-search/goods-search?referrer=' + user_id
    };
  },

});