const app = getApp();
Page({
  data: {
    params: null,
    is_show_open_setting: false,
    auth: 'scope.userLocation',
    cache_key: app.data.cache_userlocation_key,
  },

  onLoad: function (params) {
    this.setData({ params: params });
  },

  onShow() {
    this.init();
  },

  // 获取权限
  init() {
    var self = this;
    tt.getSetting({
      success(res) {
        if (!res.authSetting[self.data.auth]) {
          tt.authorize({
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

  // 打开设置方法
  opne_setting_event(e) {
    var self = this;
    tt.openSetting({
      success: (res) => {
        if (res.authSetting[self.data.auth]) {
          self.setData({ is_show_open_setting: false });
          self.choose_location();
        }
      }
    });
  },

  // 打开位置服务
  choose_location() {
    tt.chooseLocation({
      success: res => {
        var position = app.map_gcj_to_bd(res.longitude, res.latitude);
        res.longitude = position.lng;
        res.latitude = position.lat;
        tt.setStorageSync(this.data.cache_key, res);
        tt.navigateBack();
      },
      fail: (res) => {
        tt.navigateBack();
      }
    });
  },
});