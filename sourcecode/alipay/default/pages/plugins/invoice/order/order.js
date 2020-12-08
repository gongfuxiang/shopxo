const app = getApp();
Page({
  data: {
    data_base: null,
    data_list: [],
    data_page_total: 0,
    data_page: 1,
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    params: null,
    select_ids: [],
  },

  onLoad(params) {
    this.setData({
      params: params,
    });
  },

  onShow() {
    this.init();
  },

  init() {
    var user = app.get_user_info(this, 'init');
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        my.redirectTo({
          url: "/pages/login/login?event_callback=init"
        });
        return false;
      } else {
        // 获取数据
        this.get_data_list();
      }
    } else {
      this.setData({
        data_list_loding_status: 0,
        data_bottom_line_status: false,
      });
    }
  },

  // 获取数据
  get_data_list(is_mandatory) {
    // 分页是否还有数据
    if ((is_mandatory || 0) == 0) {
      if (this.data.data_bottom_line_status == true) {
        return false;
      }
    }

    // 加载loding
    my.showLoading({ content: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    // 请求数据
    var data = {
      page: this.data.data_page
    };

    // 获取数据
    my.request({
      url: app.get_request_url("index", "order", "invoice"),
      method: "POST",
      data: data,
      dataType: "json",
      success: res => {
        my.hideLoading();
        my.stopPullDownRefresh();
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
              data_base: res.data.data.base || null,
              data_list: temp_data_list,
              data_total: res.data.data.total,
              data_page_total: res.data.data.page_total,
              data_list_loding_status: 3,
              data_page: this.data.data_page + 1,
            });

            // 是否还有数据
            if (this.data.data_page > 1 && this.data.data_page > this.data.data_page_total) {
              this.setData({ data_bottom_line_status: true });
            } else {
              this.setData({ data_bottom_line_status: false });
            }
          } else {
            this.setData({
              data_list_loding_status: 0,
              data_list: [],
              data_bottom_line_status: false,
            });
          }
        } else {
          this.setData({
            data_list_loding_status: 0,
          });
          if (app.is_login_check(res.data, this, 'get_data_list')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        my.hideLoading();
        my.stopPullDownRefresh();

        this.setData({
          data_list_loding_status: 2,
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

  // 选择
  selected_event(e) {
    var value = e.currentTarget.dataset.value;
    var temp_select_ids = this.data.select_ids;
    var index = temp_select_ids.indexOf(value);
    if(index == -1)
    {
      temp_select_ids.push(value);
    } else {
      temp_select_ids.splice(index, 1);
    }
    this.setData({select_ids: temp_select_ids});
  },

  // 合并开票
  invoice_merge_event(e) {
    if(this.data.select_ids.length <= 0)
    {
      app.showToast('请先选择数据');
      return false;
    }
    my.navigateTo({
      url: '/pages/plugins/invoice/invoice-saveinfo/invoice-saveinfo?ids='+this.data.select_ids.join(',')+'&type=order&is_redirect=1',
    });
  },
});