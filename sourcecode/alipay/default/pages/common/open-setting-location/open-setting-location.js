const app = getApp();
Page({
  data: {
    params: null,
    is_show_open_setting: false,
    cache_key: app.data.cache_userlocation_key,
  },

  onLoad: function (params) {
    this.setData({ params: params });
    this.init();
  },

  // 获取权限
  init() {
    this.choose_location();
  },

  // 打开位置服务
  choose_location() {
    my.chooseLocation({
      success: res => {
        var position = app.map_gcj_to_bd(res.longitude, res.latitude);
        res.longitude = position.lng;
        res.latitude = position.lat;
        my.setStorageSync({key: this.data.cache_key, data: res});
        my.navigateBack();
      },
      fail: (res) => {
        my.navigateBack();
      }
    });
  },
});