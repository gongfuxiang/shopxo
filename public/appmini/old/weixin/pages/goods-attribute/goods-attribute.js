const app = getApp();
Page({
  data: {
    goods_attribute: [],
  },
  onLoad(params) {
    if((params.data || null) == null)
    {
      wx.alert({
        title: '温馨提示',
        content: '属性数据有误',
        buttonText: '返回',
        success: () => {
          wx.navigateBack();
        }
      });
    } else {
      this.setData({goods_attribute: JSON.parse(params.data)});
    }
  },

  onShow() {
    wx.setNavigationBarTitle({title: app.data.common_pages_title.goods_attribute});
  },
});
