const app = getApp();
Page({
  data: {
    price_symbol: app.data.price_symbol,
    params: null,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_bottom_line_status: false,

    // 接口数据
    data_list: [],
    data_page_total: 0,
    data_page: 1,
    form_keyword_value: '',

    // 导航
    // 状态（0待确认, 1待退货, 2待审核, 3已完成, 4已拒绝, 5已取消）
    nav_status_list: [
      { name: "全部", value: "-1" },
      { name: "待确认", value: "0" },
      { name: "待退货", value: "1" },
      { name: "待审核", value: "2" },
      { name: "已完成", value: "3" },
      { name: "已拒绝", value: "4" },
      { name: "已取消", value: "5" },
    ],
    nav_status_index: 0,
  },

  onLoad(params) {
    // 是否指定状态
    var nav_status_index = 0;
    if ((params.status || null) != null) {
      for (var i in this.data.nav_status_list) {
        if (this.data.nav_status_list[i]['value'] == params.status) {
          nav_status_index = i;
          break;
        }
      }
    }

    this.setData({
      params: params,
      form_keyword_value: params.keywords || '',
      nav_status_index: nav_status_index,
    });
    this.init();
  },

  onShow() {
    wx.setNavigationBarTitle({ title: app.data.common_pages_title.user_orderaftersale });
  },

  init() {
    var user = app.get_user_info(this, "init");
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

    wx.request({
      url: app.get_request_url("index", "orderaftersale"),
      method: "POST",
      data: {
        page: this.data.data_page,
        keywords: this.data.form_keyword_value || "",
        status: status,
        is_more: 1,
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
              data_list_loding_msg: '没有相关数据',
              data_list: [],
              data_bottom_line_status: false,
            });
          }
        } else {
          this.setData({
            data_list_loding_status: 0,
            data_list_loding_msg: res.data.msg,
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
          data_list_loding_msg: '服务器请求出错',
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

  // 输入框事件
  input_event(e) {
    this.setData({ form_keyword_value: e.detail.value });
  },

  // 取消
  cancel_event(e) {
    wx.showModal({
      title: "温馨提示",
      content: "取消后不可恢复，确定继续吗?",
      confirmText: "确认",
      cancelText: "不了",
      success: result => {
        if (result.confirm) {
          // 参数
          var id = e.currentTarget.dataset.value;
          var index = e.currentTarget.dataset.index;

          // 加载loding
          wx.showLoading({ title: "处理中..." });

          wx.request({
            url: app.get_request_url("cancel", "orderaftersale"),
            method: "POST",
            data: { id: id },
            dataType: "json",
            success: res => {
              wx.hideLoading();
              if (res.data.code == 0) {
                var temp_data_list = this.data.data_list;
                temp_data_list[index]['status'] = 5;
                temp_data_list[index]['status_text'] = '已取消';
                this.setData({ data_list: temp_data_list });

                app.showToast(res.data.msg, "success");
              } else {
                if (app.is_login_check(res.data)) {
                  app.showToast(res.data.msg);
                } else {
                  app.showToast('提交失败，请重试！');
                }
              }
            },
            fail: () => {
              wx.hideLoading();
              app.showToast("服务器请求出错");
            }
          });
        }
      }
    });
  },

  // 退货
  delivery_event(e) {
    var oid = e.currentTarget.dataset.oid || 0;
    var did = e.currentTarget.dataset.did || 0;
    if (oid == 0 || did == 0) {
      app.showToast("参数有误");
      return false;
    }

    // 进入售后页面
    wx.navigateTo({
      url: "/pages/user-orderaftersale-detail/user-orderaftersale-detail?oid=" + oid + "&did=" + did +"&is_delivery_popup=1"
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.setData({
      data_page: 1
    });
    this.get_data_list(1);
  },

});
