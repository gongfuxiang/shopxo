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
      { name: "待生效", value: "0" },
      { name: "生效中", value: "1" },
      { name: "待结算", value: "2" },
      { name: "已结算", value: "3" },
      { name: "已失效", value: "4" },
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
      nav_status_index: nav_status_index,
    });
    this.init();
  },

  onShow() {},

  init() {
    var user = app.get_user_info(this, 'init');
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if (app.user_is_need_login(user)) {
        tt.redirectTo({
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
    tt.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    // 参数
    var status = ((this.data.nav_status_list[this.data.nav_status_index] || null) == null) ? -1 : this.data.nav_status_list[this.data.nav_status_index]['value'];

    // 获取数据
    tt.request({
      url: app.get_request_url("index", "profit", "excellentbuyreturntocash"),
      method: "POST",
      data: {
        page: this.data.data_page,
        status: status,
        is_more: 1,
      },
      dataType: "json",
      success: res => {
        tt.hideLoading();
        tt.stopPullDownRefresh();
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
        tt.hideLoading();
        tt.stopPullDownRefresh();

        this.setData({
          data_list_loding_status: 2,
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 订单详情
  list_submit_order_event(e) {
    var oid = e.currentTarget.dataset.oid || null;
    if (oid != null)
    {
      tt.navigateTo({
        url: "/pages/user-order-detail/user-order-detail?id="+oid
      });
    } else {
      app.showToast('订单id有误');
    }
  },

  // 立即结算事件
  list_submit_settlement_event(e) {
    var index = e.currentTarget.dataset.index || 0;
    var self = this;

    // 提交数据
    tt.showLoading({ title: "处理中..." });
    tt.request({
      url: app.get_request_url("auto", "profit", "excellentbuyreturntocash"),
      method: "POST",
      data: { id: self.data.data_list[index]['id']},
      dataType: "json",
      success: res => {
        tt.hideLoading();
        if (res.data.code == 0) {
          var temp_data_list = this.data.data_list;
          temp_data_list[index]['status'] = 3;
          temp_data_list[index]['status_name'] = '已结算';
          self.setData({
            data_list: temp_data_list,
          });
          app.showToast(res.data.msg, "success");
        } else {
          app.alert({ msg: res.data.msg, is_show_cancel: 0});
        }
      },
      fail: () => {
        tt.hideLoading();
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
});