const app = getApp();
Page({
  data: {
    data_bottom_line_status: false,
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    data_list: [],
    data_default: null,
    data_base: null,
  },

  onShow() {
    // 数据加载
    this.init();
  },

  // 获取数据
  init() {
    this.get_data_list();
  },

  // 获取数据
  get_data_list() {
    var self = this;
    wx.showLoading({ title: "加载中..." });
    if (self.data.data_list.length <= 0)
    {
      self.setData({
        data_list_loding_status: 1
      });
    }

    wx.request({
      url: app.get_request_url("index", "index", "exchangerate"),
      method: "POST",
      data: {},
      dataType: "json",
      success: res => {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          var status = ((data.data.data || []).length > 0);
          this.setData({
            data_base: data.base || null,
            data_default: data.data.default || null,
            data_list: data.data.data || [],
            data_list_loding_msg: '',
            data_list_loding_status: status ? 3 : 0,
            data_bottom_line_status: status,
          });
        } else {
          self.setData({
            data_bottom_line_status: false,
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg,
          });
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        wx.hideLoading();
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

  // 选择事件
  selected_event(e) {
    // 参数处理
    var index = e.currentTarget.dataset.index;
    var temp_list = this.data.data_list;
    var data = temp_list[index] || null;
    if(data == null)
    {
      app.showToast('数据有误');
      return false;
    }

    // id与当前默认一致则不处理
    if (data.id != this.data.data_default.id)
    {
      var self = this;
      wx.showLoading({ title: "处理中..." });
      wx.request({
        url: app.get_request_url("setcurrency", "index", "exchangerate"),
        method: "POST",
        data: { "currency": data.id },
        dataType: "json",
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        success: res => {
          wx.hideLoading();
          if (res.data.code == 0) {
            app.showToast(res.data.msg, "success");
            self.setData({ data_default: data });
            // 重新初始化配置
            app.init_config();

            // 返回上一页
            setTimeout(function () {
              wx.navigateBack();
            }, 1500);
          } else {
            app.showToast(res.data.msg);
          }
        },
        fail: () => {
          wx.hideLoading();
          app.showToast("服务器请求出错");
        }
      });
    }
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.get_data_list();
  },

});
