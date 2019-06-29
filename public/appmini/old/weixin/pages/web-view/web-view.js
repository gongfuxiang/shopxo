const app = getApp();
Page({
  data: {
    web_url: null,
  },
  onLoad(option) {
    // url处理
    var url = decodeURIComponent(option.url) || null;
    if (url != null)
    {
      // token处理
      if (url.indexOf('{token}') >= 0)
      {
        var user = app.get_user_cache_info();
        var token = (user == false) ? null : user.token || null;
        if (token != null)
        {
          url = url.replace(/{token}/ig, token);
        } 
      }     
    }

    this.setData({web_url: url});
  }
});