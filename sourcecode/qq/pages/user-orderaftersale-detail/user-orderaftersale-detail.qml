<view qq:if="{{order_data != null}}">
  <!-- 商品 -->
  <view class="goods bg-white spacing-mb">
      <view class="goods-item oh">
        <navigator url="/pages/goods-detail/goods-detail?goods_id={{order_data.items.goods_id}}" hover-class="none">
          <image class="goods-image fl" src="{{order_data.items.images}}" mode="aspectFill" />
          <view class="goods-base">
            <view class="goods-title multi-text">{{order_data.items.title}}</view>
            <block qq:if="{{order_data.items.spec != null}}">
              <view class="goods-attribute cr-888" qq:for="{{order_data.items.spec}}" qq:for-item="spec" qq:key="spec">
                {{spec.type}}:{{spec.value}}
              </view>
            </block>
          </view>
          <view class="oh goods-price">
            <text class="sales-price">{{price_symbol}}{{order_data.items.price}}</text>
            <text qq:if="{{order_data.items.original_price > 0}}" class="original-price">{{price_symbol}}{{order_data.items.original_price}}</text>
            <text class="buy-number">x{{order_data.items.buy_number}}</text>
          </view>
        </navigator>
      </view>
  </view>

  <!-- 基础信息 -->
  <view qq:if="{{new_aftersale_data != null}}" class="detail bg-white">
    <!-- 提示/退货 -->
    <view qq:if="{{new_aftersale_data.status <= 2}}" class="msg-tips spacing-mb">
      <text class="msg-text">{{new_aftersale_data.tips_msg}}</text>
      <text class="msg-a" bindtap="show_aftersale_event">详情查看 >></text>
      <view>
        <button qq:if="{{new_aftersale_data.status == 1 && new_aftersale_data.type == 1 && return_goods_address != null}}" type="primary" size="mini" bindtap="delivery_submit_event">立即退货</button>
      </view>
    </view>

    <!-- 退货地址 -->
    <view qq:if="{{new_aftersale_data.status == 1 && new_aftersale_data.type == 1 && return_goods_address != null}}" class="return-address msg-tips msg-tips-warning spacing-mb">
      <view>
        <text class="address-title">退货地址：</text>
        <text class="address-value">{{return_goods_address}}</text>
      </view>
    </view>

    <!-- 提示 -->
    <view qq:if="{{new_aftersale_data.status >= 3}}" class="msg-tips {{new_aftersale_data.status != 5 ? 'spacing-mb' : ''}} {{new_aftersale_data.status == 3 ? 'msg-tips-success' : (new_aftersale_data.status == 4 ? 'msg-tips-danger' : 'msg-tips-warning')}}">
      <text class="msg-text">{{new_aftersale_data.tips_msg}}</text>
      <text class="msg-a" bindtap="show_aftersale_event">详情查看 >></text>
    </view>

    <!-- 详情 -->
    <view qq:if="{{new_aftersale_data.status != 5}}">
      <!-- 申请信息 -->
      <view class="panel-item">
        <view class="panel-title">申请信息</view>
        <view class="panel-content">
          <view qq:for="{{panel_base_data_list}}" qq:key="item" class="item br-b oh">
            <view class="title fl">{{item.name}}</view>
            <view class="content cr-888 fl br-l">{{new_aftersale_data[item.field] || ''}}</view>
          </view>
        </view>
      </view>

      <!-- 快递信息 -->
      <view qq:if="{{new_aftersale_data.status > 1 && new_aftersale_data.type == 1}}" class="panel-item spacing-mt">
        <view class="panel-title">快递信息</view>
        <view class="panel-content">
          <view qq:for="{{panel_express_data_list}}" qq:key="item" class="item br-b oh">
            <view class="title fl">{{item.name}}</view>
            <view class="content cr-888 fl br-l">{{new_aftersale_data[item.field] || ''}}</view>
          </view>
        </view>
      </view>

      <!-- 凭证 -->
      <view qq:if="{{(new_aftersale_data.images || null) != null && new_aftersale_data.images.length > 0}}" class="panel-item spacing-mt">
        <view class="panel-title">凭证</view>
        <view class="panel-content voucher-data oh">
          <view qq:for="{{new_aftersale_data.images}}" qq:key="item" class="fl item">
            <image src="{{item}}" mode="aspectFill" bindtap="images_view_event" data-index="{{index}}" />
          </view>
        </view>
      </view>
    </view>
  </view>

  <!-- 没有售后数据/售后数据为已取消则可以重新申请售后 -->
  <view qq:if="{{new_aftersale_data == null || new_aftersale_data.status == 5}}" class="spacing-mt">
    <!-- 类型选择 -->
    <view qq:if="{{aftersale_type_list.length > 0}}" class="choose-type bg-white spacing-mb oh">
      <block qq:for="{{aftersale_type_list}}" qq:key="item">
        <view class="choose-item {{index == 0 ? 'fl' : 'fr'}} {{form_type == item.value ? 'choose-item-active' : ''}}" data-value="{{item.value}}" bindtap="form_type_event">
          <view class="choose-name">{{item.name}}</view>
          <view class="choose-desc cr-888">{{item.desc}}</view>
        </view>
      </block>
    </view>

    <!-- 表单 -->
    <view qq:if="{{form_type != null}}" class="form-container spacing-mb oh">
      <view class="form-gorup bg-white">
        <view class="form-gorup-title">退款原因<text class="form-group-tips-must">必选</text></view>
        <picker bindchange="form_reason_event" value="{{form_reason_index}}" range="{{reason_data_list}}">
          <view class="picker {{form_reason_index == null ? 'cr-ccc' : 'cr-666'}} arrow-right">
            {{form_reason_index == null ? '请选择原因' : reason_data_list[form_reason_index]}}
          </view>
        </picker>
      </view>

      <view qq:if="{{form_type == 1}}" class="form-gorup bg-white">
        <view class="form-gorup-title">商品件数<text class="form-group-tips">不能大于{{returned_data.returned_quantity}}数量</text></view>
        <slider bindchange="form_number_event" min="0" max="{{returned_data.returned_quantity}}" step="1" value="{{returned_data.returned_quantity}}" show-value />
      </view>

      <view class="form-gorup bg-white">
        <view class="form-gorup-title">退款金额<text class="form-group-tips">不能大于{{returned_data.refund_price}}元</text></view>
        <input type="digit" bindinput="form_price_event" placeholder-class="cr-ccc" class="cr-666" placeholder="请输入退款金额" value="{{form_price}}" />
      </view>

      <view class="form-gorup bg-white">
        <view class="form-gorup-title">退款说明<text class="form-group-tips-must">必填</text></view>
        <textarea bindinput="form_msg_event" placeholder-class="cr-ccc" class="cr-666" placeholder="退款说明 5~200 个字符之间" maxlength="200" auto-height="{{true}}" value="{{form_msg}}" />
      </view>

      <view class="form-gorup bg-white form-container-upload oh">
        <view class="form-gorup-title">上传凭证<text class="form-group-tips">最多上传3张图片</text></view>
        <view class="form-upload-data fl">
          <block qq:if="{{form_images_list.length > 0}}">
            <view qq:for="{{form_images_list}}" qq:key="item" class="item fl">
              <text class="delete-icon" bindtap="upload_delete_event" data-index="{{index}}">x</text>
              <image src="{{item}}" bindtap="upload_show_event" data-index="{{index}}" mode="aspectFill" />
            </view>
          </block>
        </view>
        <image qq:if="{{form_images_list.length < 3}}" class="upload-icon" src="/images/default-upload-icon.png" mode="aspectFill" bindtap="file_upload_event" />
      </view>
      <view class="form-gorup">
        <button class="bg-main submit-bottom" type="default" bindtap="form_submit_event" hover-class="none" disabled="{{form_button_disabled}}">提交</button>
      </view>
    </view>
  </view>

  <!-- 退货弹层 -->
  <component-popup prop-show="{{popup_delivery_status}}" prop-position="bottom" bindonclose="popup_delivery_close_event">
    <view class="delivery-popup bg-white">
      <view class="close fr oh">
        <view class="fr" catchtap="popup_delivery_close_event">
          <icon type="clear" size="20" />
        </view>
      </view>
      <view class="delivery-popup-content">
        <view class="form-container">
          <view class="form-gorup">
            <view class="form-gorup-title">快递名称<text class="form-group-tips-must">必填</text></view>
            <input type="text" bindinput="form_express_name_event" placeholder-class="cr-ccc" class="cr-666" placeholder="请输入快递名称" value="{{form_express_name}}" />
          </view>
          <view class="form-gorup">
            <view class="form-gorup-title">快递单号<text class="form-group-tips-must">必填</text></view>
            <input type="text" bindinput="form_express_number_event" placeholder-class="cr-ccc" class="cr-666" placeholder="请输入快递单号" value="{{form_express_number}}" />
          </view>
          <view class="form-gorup">
            <button class="bg-main submit-bottom" type="default" bindtap="form_delivery_submit_event" hover-class="none" disabled="{{form_button_disabled}}">提交</button>
          </view>
        </view>
      </view>
    </view>
  </component-popup>
</view>
<view qq:if="{{order_data == null}}">
  <import src="/pages/common/nodata.qml" />
  <template is="nodata" data="{{status: data_list_loding_status, msg: data_list_loding_msg}}"></template>
</view>
<import src="/pages/common/bottom_line.qml" />
<template qq:if="{{new_aftersale_data != null && new_aftersale_data.status != 5}}" is="bottom_line" data="{{status: data_bottom_line_status}}"></template>