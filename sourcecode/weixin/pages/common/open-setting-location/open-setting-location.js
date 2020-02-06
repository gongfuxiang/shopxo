const app = getApp();
Page({
  data: {
    params: null,
    is_show_open_setting: false,
    auth: 'scope.userLocation',
    cache_key: 'cache_userlocation_key',
  },

  onLoad: function (params) {
    this.setData({ params: params });
    this.init();
  },

  // 获取权限
  init() {
    var self = this;
    wx.getSetting({
      success(res) {
        if (!res.authSetting[self.data.auth]) {
          wx.authorize({
            scope: self.data.auth,
            success(res) {
              self.choose_location();
            },
            fail: (res) => {
              self.setData({ is_show_open_setting: true });
            }
          })
        } else {
          self.choose_location();
        }
      },
      fail: (res) => {
        app.showToast("请先获取授权");
      }
    });
  },

  // 位置服务回调方法
  setting_callback_event(e) {
    var self = this;
    if (e.detail.authSetting[self.data.auth]) {
      self.setData({ is_show_open_setting: false });
      self.choose_location();
    }
  },

  // 打开位置服务
  choose_location() {
    wx.chooseLocation({
      success: res => {
        wx.setStorageSync(this.data.cache_key, res);
        wx.navigateBack();
      },
      fail: (res) => {
        wx.navigateBack();
      }
    });
  },
});