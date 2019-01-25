const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    data_list_loding_msg: '处理错误',

    form_submit_loading: false,
  },

  onLoad() {},

  onShow() {
    my.setNavigationBar({title: app.data.common_pages_title.answer_form});
    this.init();
  },

  // 初始化
  init() {
    var user = app.GetUserInfo(this, "init");
    if (user != false) {
      // 用户未绑定用户则转到登录页面
      if ((user.mobile || null) == null) {
        my.redirectTo({
          url: "/pages/login/login?event_callback=init"
        });

        this.setData({
          data_list_loding_status: 2,
          data_list_loding_msg: '请先绑定手机号码',
        });
        return false;
      } else {
        this.setData({
          data_list_loding_status: 0,
          data_list_loding_msg: '',
        });
      }
    } else {
      this.setData({
        data_list_loding_status: 2,
        data_list_loding_msg: '请先授权用户信息',
      });
    }
  },

  /**
   * 表单提交
   */
  formSubmit(e)
  {              
    // 数据验证
    var validation = [
      {fields: 'name', msg: '请填写联系人'},
      {fields: 'tel', msg: '请填写联系电话'},
      {fields: 'content', msg: '请填写内容'}
    ];
    if(app.fields_check(e.detail.value, validation))
    {
      my.showLoading({content: '提交中...'});
      this.setData({form_submit_loading: true});

      // 网络请求
      my.httpRequest({
        url: app.get_request_url('add', 'answer'),
        method: 'POST',
        data: e.detail.value,
        dataType: 'json',
        success: (res) => {
          my.hideLoading();

          if(res.data.code == 0)
          {
            my.showToast({
              type: 'success',
              content: res.data.msg
            });
            setTimeout(function()
            {
              my.redirectTo({
                url: "/pages/user-answer-list/user-answer-list"
              });
            }, 2000);
          } else {
            this.setData({form_submit_loading: false});
            
            my.showToast({
              type: 'fail',
              content: res.data.msg
            });
          }
        },
        fail: () => {
          my.hideLoading();
          this.setData({form_submit_loading: false});

          my.showToast({
            type: 'fail',
            content: '服务器请求出错'
          });
        }
      });
    }
  },

});
