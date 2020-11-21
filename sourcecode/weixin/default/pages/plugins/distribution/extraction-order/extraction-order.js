const app = getApp();
Page({
  data: {
    data_list: [],
    data_page_total: 0,
    data_page: 1,
    data_list_loding_status: 1,
    data_bottom_line_status: false,
    params: null,

    nav_status_list: [
      { name: "全部", value: "-1" },
      { name: "待处理", value: "0" },
      { name: "已处理", value: "1" }
    ],
    nav_status_index: 0,

    is_show_take_popup: false,
    extraction_value: null,
    extraction_code: '',
    form_submit_disabled_status: false,

    is_show_search_popup: false,
    search_keywords_value: '',
  },

  onLoad(params) {
    // 是否指定状态
    var nav_status_index = 0;
    if (params.status != undefined) {
      for (var i in this.data.nav_status_list) {
        if (this.data.nav_status_list[i]['value'] == params.status) {
          nav_status_index = i;
          break;
        }
      }
    }

    this.setData({
      params: params,
      nav_status_index: nav_status_index,
    });
    
    this.init();
  },

  onShow() { },

  init() {
    var user = app.get_user_info(this, 'init');
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        wx.redirectTo({
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
    wx.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    // 参数
    var status = ((this.data.nav_status_list[this.data.nav_status_index] || null) == null) ? -1 : this.data.nav_status_list[this.data.nav_status_index]['value'];

    // 获取数据
    wx.request({
      url: app.get_request_url("order", "extraction", "distribution"),
      method: "POST",
      data: {
        page: this.data.data_page,
        status: status || 0,
        keywords: this.data.search_keywords_value || '',
      },
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
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
        wx.hideLoading();
        wx.stopPullDownRefresh();

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

  // 导航事件
  nav_event(e) {
    this.setData({
      nav_status_index: e.currentTarget.dataset.index || 0,
      data_page: 1,
    });
    this.get_data_list(1);
  },

  // 取件码弹层-开启
  list_submit_take_event(e) {
    this.setData({
      is_show_take_popup: true,
      extraction_code: '',
      extraction_value: {
        index: e.currentTarget.dataset.index,
        oid: e.currentTarget.dataset.oid,
        uid: e.currentTarget.dataset.uid
      },
    });
  },

  // 取件码弹层-关闭
  take_popup_event_close() {
    this.setData({ is_show_take_popup: false });
  },

  // 取件码输入事件
  extraction_code_input_event(e) {
    this.setData({ extraction_code: e.detail.value || ''});
  },

  // 取件提交
  form_submit_take_event(e) {
    var self = this;
    // 参数
    if ((self.data.extraction_code || null) == null)
    {
      app.showToast('请输入取件码');
      return false;
    }
    if ((self.data.extraction_value || null) == null) {
      app.showToast('操作数据有误');
      return false;
    }

    // 提交表单
    var data = {
      id: self.data.extraction_value.oid,
      user_id: self.data.extraction_value.uid,
      extraction_code: self.data.extraction_code,
    };
    self.setData({ form_submit_disabled_status: true });
    wx.showLoading({ title: "处理中..." });
    wx.request({
      url: app.get_request_url("take", "extraction", "distribution"),
      method: "POST",
      data: data,
      dataType: "json",
      success: res => {
        self.setData({ form_submit_disabled_status: false });
        wx.hideLoading();
        if (res.data.code == 0) {
          var temp_data_list = this.data.data_list;
          var index = self.data.extraction_value.index;
          temp_data_list[index]['status'] = 1;
          temp_data_list[index]['status_name'] = '已处理';
          self.setData({
            is_show_take_popup: false,
            data_list: temp_data_list,
          });
          app.showToast(res.data.msg, "success");
        } else {
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        self.setData({ form_submit_disabled_status: false });
        wx.hideLoading();
        app.showToast("服务器请求出错");
      }
    });
  },

  // 搜索弹层-开启
  drag_event(e) {
    this.setData({ is_show_search_popup: true});
  },

  // 搜索弹层-关闭
  search_popup_event_close() {
    this.setData({ is_show_search_popup: false });
  },

  // 搜索关键字输入事件
  search_input_keywords_event(e) {
    this.setData({ search_keywords_value: e.detail.value || '' });
  },

  // 搜索确认事件
  search_submit_event(e) {
    this.setData({
      is_show_search_popup: false,
      data_page: 1,
    });
    this.get_data_list(1);
  },
});