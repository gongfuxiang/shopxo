const app = getApp();
Page({
  data: {
    web_url: null,
  },
  onLoad(option) {
    this.setData({
      web_url: option.url || null,
    })
  }
});