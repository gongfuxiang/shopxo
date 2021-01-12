const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    data_list: [],
    data_page_total: 0,
    data_page: 1,
    params: null,
    post_data: {},
    is_show_popup_form: false,
    popup_form_loading_status: false,
    search_nav_sort_list: [
      { name: "综合", field: "default", sort: "asc", "icon": null },
      { name: "销量", field: "sales_count", sort: "asc", "icon": "default" },
      { name: "热度", field: "access_count", sort: "asc", "icon": "default" },
      { name: "价格", field: "min_price", sort: "asc", "icon": "default" },
      { name: "最新", field: "id", sort: "asc", "icon": "default" }
    ],

    // 基础配置
    currency_symbol: app.data.currency_symbol,

    // 搜素条件
    search_map_info: [],
    brand_list: [],
    category_list: [],
    screening_price_list: [],
    goods_params_list: [],
    goods_spec_list: [],
    map_fields_list: {
      "brand_list": {"height":"100rpx", "default":"100rpx", "form_key":"brand_ids"},
      "category_list": {"height":"82rpx", "default":"82rpx", "form_key":"category_ids"},
      "screening_price_list": {"height":"82rpx", "default":"82rpx", "form_key":"screening_price_values"},
      "goods_params_list": {"height":"82rpx", "default":"82rpx", "form_key":"goods_params_values"},
      "goods_spec_list": {"height":"82rpx", "default":"82rpx", "form_key":"goods_spec_values"}
    },
  },

  onLoad(params) {
    // 启动参数处理
    params = app.launch_params_handle(params);

    // 初始参数
    this.setData({
      params: params,
      post_data: {
        wd: params.keywords || ''
      }
    });
  },

  onShow() {
    tt.setNavigationBarTitle({title: app.data.common_pages_title.goods_search});

    // 数据加载
    this.init();

    // 初始化配置
    this.init_config();
  },

  // 初始化配置
  init_config(status) {
    if((status || false) == true) {
      this.setData({
        currency_symbol: app.get_config('currency_symbol'),
      });
    } else {
      app.is_config(this, 'init_config');
    }
  },

  // 获取数据
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
    tt.showLoading({title: "加载中..." });

    // 参数
    var post_data = this.request_map_handle();

    // 获取数据
    tt.request({
      url: app.get_request_url("index", "search"),
      method: "POST",
      data: post_data,
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        tt.hideLoading();
        tt.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;

          // 仅首次请求赋值条件数据
          if(this.data.data_list_loding_status == 1)
          {
            this.setData({
              search_map_info: data.search_map_info || [],
              brand_list: data.brand_list || [],
              category_list: data.category_list || [],
              screening_price_list: data.screening_price_list || [],
              goods_params_list: data.goods_params_list || [],
              goods_spec_list: data.goods_spec_list || [],
            });
          }

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
            data_list_loding_status: 0
          });
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        tt.hideLoading();
        tt.stopPullDownRefresh();

        this.setData({
          data_list_loding_status: 2
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 搜索条件处理
  request_map_handle() {
    var params = this.data.params;
    var post_data = this.data.post_data;
    post_data['page'] = this.data.data_page;

    // 指定分类、品牌
    post_data['category_id'] = params['category_id'] || 0;
    post_data['brand_id'] = params['brand_id'] || 0;

    // 搜索条件
    var data = this.data;
    for(var i in data.map_fields_list)
    {
      if((data[i] != null) != null && data[i].length > 0)
      {
        var temp = {};
        var index = 0;
        for(var k in data[i])
        {
          if((data[i][k]['active'] || 0) == 1)
          {
            switch(i)
            {
              // 价格
              case 'screening_price_list' :
                temp[index] = data[i][k]['min_price']+'-'+data[i][k]['max_price'];
                  break;

              // 属性、规格
              case 'goods_params_list' :
              case 'goods_spec_list' :
                temp[index] = data[i][k]['value'];
                break;

                // 默认取值id
                default :
                temp[index] = data[i][k]['id'];
            }
            index++;
          }
        }
        post_data[data.map_fields_list[i]['form_key']] = (app.get_length(temp) > 0) ? JSON.stringify(temp) : '';
      }
    }

    return post_data;
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
    this.setData({ post_data: e.detail.value, data_page: 1});
    this.popup_form_event_close();
    this.get_data_list(1);
  },

  // 筛选条件关闭
  popup_form_event_close(e) {
    this.setData({ is_show_popup_form: false});
  },

  // 筛选条件开启
  popup_form_event_show(e) {
    this.setData({ is_show_popup_form: true });
  },

  // 排序事件
  nav_sort_event(e) {
    var index = e.currentTarget.dataset.index || 0;
    var temp_post_data = this.data.post_data;
    var temp_search_nav_sort = this.data.search_nav_sort_list;
    var temp_sort = (temp_search_nav_sort[index]['sort'] == 'desc') ? 'asc' : 'desc';
    for (var i in temp_search_nav_sort) {
      if(i != index) {
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
      data_page: 1,
    });
    this.get_data_list(1);
  },

  // 条件-更多数据展示事件
  more_event(e) {
    var value = e.currentTarget.dataset.value || null;
    var temp_more = this.data.map_fields_list;
    if(value != null && (temp_more[value] || null) != null)
    {
      temp_more[value]['height'] = (temp_more[value]['height'] == 'auto') ? temp_more[value]['default'] : 'auto';
      this.setData({map_fields_list: temp_more});
    }
  },

  // 条件-选择事件
  map_item_event(e) {
    var index = e.currentTarget.dataset.index;
    var field = e.currentTarget.dataset.field;
    var data = this.data;
    if((data[field] || null) != null && (data[field][index] || null) != null)
    {
      data[field][index]['active'] = ((data[field][index]['active'] || 0) == 0) ? 1 : 0;
      this.setData(data);
    }
  },
  
  // 条件-清空
  map_remove_event(e) {
    var data = this.data;
    // 关键字
    data['post_data']['wd'] = '';

    // 品牌、分类、价格、属性、规格
    for(var i in data.map_fields_list)
    {
      if((data[i] != null) != null && data[i].length > 0)
      {
        for(var k in data[i])
        {
          data[i][k]['active'] = 0;
        }
      }
    }

    // 关闭条件弹层
    data['is_show_popup_form'] = false;

    // 分页恢复1页、重新获取数据
    data['data_page'] = 1;
    this.setData(data);
    this.get_data_list(1);
  },

  // 自定义分享
  onShareAppMessage() {
    var user_id = app.get_user_cache_info('id', 0) || 0;
    var category_id = this.data.params['category_id'] || 0;
    var brand_id = this.data.params['brand_id'] || 0;
    var keywords = this.data.params['keywords'] || '';
    return {
      title: app.data.application_title,
      desc: app.data.application_describe,
      path: '/pages/goods-search/goods-search?referrer=' + user_id+'&category_id='+category_id+'&brand_id='+brand_id+'&keywords='+keywords
    };
  },
});
