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
      { name: "未开始", value: "0" },
      { name: "直播中", value: "1" },
      { name: "暂停中", value: "2" },
      { name: "已结束", value: "3" },
    ],
    nav_status_index: 0,
  },

  onLoad(params) {
    // 启动参数处理
    params = app.launch_params_handle(params);

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
    // 获取数据
    this.get_data_list();
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
      url: app.get_request_url("index", "search", "weixinliveplayer"),
      method: "POST",
      data: {
        page: this.data.data_page,
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
              data_list: [],
              data_bottom_line_status: false,
            });
          }
        } else {
          this.setData({
            data_list_loding_status: 0,
          });
          app.showToast(res.data.msg);
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

  // 自定义分享
  onShareAppMessage() {
    var user = app.get_user_cache_info() || null;
    var user_id = (user != null && (user.id || null) != null) ? user.id : 0;
    return {
      title: app.data.application_title,
      desc: app.data.application_describe,
      path: '/pages/plugins/weixinliveplayer/search/search?referrer=' + user_id
    };
  },
});