const app = getApp();
Page({
  data: {
    data_list_loding_status: 1,
    data_list_loding_msg: '',
    params: null,

    detail: null,
    editor_path_type: '',
    rating_msg: ['非常差', '差', '一般', '好', '非常好'],
    anonymous_value: 0,
    anonymous_msg_list: ['你写的评论会以匿名的形式展现', '你写的评论会以昵称的形式展现'],

    form_rating_list: [],
    form_images_list: [],
    form_content_list: [],
    form_button_disabled: false
  },

  onLoad(params) {
    this.setData({ params: params });
    this.init();
  },

  onShow() {
    swan.setNavigationBarTitle({ title: app.data.common_pages_title.user_order_comments });
  },

  init() {
    var self = this;
    swan.showLoading({ title: "加载中..." });
    this.setData({
      data_list_loding_status: 1
    });

    swan.request({
      url: app.get_request_url("comments", "order"),
      method: "POST",
      data: {
        id: this.data.params.id
      },
      dataType: "json",
      success: res => {
        swan.hideLoading();
        swan.stopPullDownRefresh();
        if (res.data.code == 0) {
          var data = res.data.data;
          self.setData({
            editor_path_type: data.editor_path_type || '',
            detail: data.data,
            data_list_loding_status: 3,
            data_list_loding_msg: ''
          });
        } else {
          self.setData({
            data_list_loding_status: 2,
            data_list_loding_msg: res.data.msg
          });
          if (app.is_login_check(res.data, self, 'init')) {
            app.showToast(res.data.msg);
          }
        }
      },
      fail: () => {
        swan.hideLoading();
        swan.stopPullDownRefresh();
        self.setData({
          data_list_loding_status: 2,
          data_list_loding_msg: '服务器请求出错'
        });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 上传图片预览
  upload_show_event(e) {
    var index = e.currentTarget.dataset.index;
    var ix = e.currentTarget.dataset.ix;
    swan.previewImage({
      current: this.data.form_images_list[index][ix],
      urls: this.data.form_images_list[index]
    });
  },

  // 图片删除
  upload_delete_event(e) {
    var index = e.currentTarget.dataset.index;
    var ix = e.currentTarget.dataset.ix;
    var self = this;
    swan.showModal({
      title: '温馨提示',
      content: '删除后不可恢复、继续吗？',
      success(res) {
        if (res.confirm) {
          var list = self.data.form_images_list;
          list[index].splice(ix, 1);
          self.setData({
            form_images_list: list
          });
        }
      }
    });
  },

  // 文件上传
  file_upload_event(e) {
    // 数据初始化
    var index = e.currentTarget.dataset.index;
    var temp_list = this.data.form_images_list;
    var length = this.data.detail.items.length;
    for (var i = 0; i < length; i++) {
      if (temp_list[i] == undefined) {
        temp_list[i] = [];
      }
    }
    this.setData({ form_images_list: temp_list });

    // 处理上传文件
    var self = this;
    swan.chooseImage({
      count: 3,
      success(res) {
        var success = 0;
        var fail = 0;
        var length = res.tempFilePaths.length;
        var count = 0;
        self.upload_one_by_one(index, res.tempFilePaths, success, fail, count, length);
      }
    });
  },

  // 采用递归的方式上传多张
  upload_one_by_one(index, img_paths, success, fail, count, length) {
    var self = this;
    if ((self.data.form_images_list[index] || null) == null || self.data.form_images_list[index].length < 3) {
      swan.uploadFile({
        url: app.get_request_url("index", "ueditor"),
        filePath: img_paths[count],
        name: 'upfile',
        formData: {
          action: 'uploadimage',
          path_type: self.data.editor_path_type
        },
        success: function (res) {
          success++;
          if (res.statusCode == 200) {
            var data = typeof res.data == 'object' ? res.data : JSON.parse(res.data);
            if (data.code == 0 && (data.data.url || null) != null) {
              var list = self.data.form_images_list;
              if ((list[index] || null) == null) {
                list[index] = [];
              }
              list[index].push(data.data.url);
              self.setData({ form_images_list: list });
            } else {
              app.showToast(data.msg);
            }
          }
        },
        fail: function (e) {
          fail++;
        },
        complete: function (e) {
          count++; // 下一张
          if (count >= length) {
            // 上传完毕，作一下提示
            //app.showToast('上传成功' + success +'张', 'success');
          } else {
            // 递归调用，上传下一张
            self.upload_one_by_one(index, img_paths, success, fail, count, length);
          }
        }
      });
    }
  },

  // 是否匿名事件
  anonymous_event(e) {
      console.log(e)
    this.setData({ anonymous_value: e.detail.checked == true ? 1 : 0 });
  },

  // 评分事件
  rating_event(e) {
    // 参数
    var index = e.currentTarget.dataset.index;
    var value = e.currentTarget.dataset.value;

    // 数据初始化/赋值
    var temp_list = this.data.form_rating_list;
    var length = this.data.detail.items.length;
    for (var i = 0; i < length; i++) {
      if (temp_list[i] == undefined) {
        temp_list[i] = 0;
      }
      if (index == i) {
        temp_list[i] = value;
      }
    }
    this.setData({ form_rating_list: temp_list });
  },

  // 评论内容
  form_content_event(e) {
    // 参数
    var index = e.currentTarget.dataset.index;
    var value = e.detail.value;

    // 数据初始化/赋值
    var temp_list = this.data.form_content_list;
    var length = this.data.detail.items.length;
    for (var i = 0; i < length; i++) {
      if (temp_list[i] == undefined) {
        temp_list[i] = '';
      }
      if (index == i) {
        temp_list[i] = value;
      }
    }
    this.setData({
      form_content_list: temp_list
    });
  },

  // 表单
  formSubmit(e) {
    // 商品数量
    var length = this.data.detail.items.length;

    // 评分校验
    var count = this.data.form_rating_list.length;
    if (count < length) {
      app.showToast('请评分');
      return false;
    }
    var max = Math.max.apply(null, this.data.form_rating_list);
    var min = Math.min.apply(null, this.data.form_rating_list);
    if (min < 1 || max > 5) {
      app.showToast('评分有误');
      return false;
    }

    // 内容校验
    var count = this.data.form_content_list.length;
    if (count < length) {
      app.showToast('请填写评论内容');
      return false;
    }
    for (var i in this.data.form_content_list) {
      var count = this.data.form_content_list[i].length;
      if (count < 6 || count > 230) {
        app.showToast('评论内容 6~230 个字符之间');
        return false;
      }
    }

    // 图片校验
    if (this.data.form_images_list.length > 0) {
      for (var i in this.data.form_images_list) {
        if (this.data.form_images_list[i].length > 3) {
          app.showToast('每项评论图片不能超过3张');
          return false;
        }
      }
    }

    // 表单数据
    var form_data = e.detail.value;
    form_data['is_anonymous'] = form_data['is_anonymous'] == true ? 1 : 0;
    form_data['id'] = this.data.detail.id;
    form_data['goods_id'] = JSON.stringify(this.data.detail.items.map(function (v) {
      return v.goods_id;
    }));
    form_data['rating'] = JSON.stringify(this.data.form_rating_list);
    form_data['content'] = JSON.stringify(this.data.form_content_list);
    form_data['images'] = this.data.form_images_list.length > 0 ? JSON.stringify(this.data.form_images_list) : '';

    // 提交表单
    var self = this;
    swan.showLoading({ title: "处理中..." });
    self.setData({ form_button_disabled: true });
    swan.request({
      url: app.get_request_url("commentssave", "order"),
      method: "POST",
      data: form_data,
      dataType: "json",
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: res => {
        swan.hideLoading();
        if (res.data.code == 0) {
          app.showToast(res.data.msg, "success");
          setTimeout(function () {
            swan.navigateBack();
          }, 2000);
        } else {
          self.setData({ form_button_disabled: false });
          app.showToast(res.data.msg);
        }
      },
      fail: () => {
        swan.hideLoading();
        self.setData({ form_button_disabled: false });
        app.showToast("服务器请求出错");
      }
    });
  },

  // 下拉刷新
  onPullDownRefresh() {
    this.init();
  }

});