<!-- 头部 -->
<view class="head-box bg-main">
  <view class="head-item oh tc">
    <image bindtap="preview_event" binderror="user_avatar_error" class="avatar" src="{{avatar}}" mode="widthFix" />
    <text class="item-name dis-block cr-fff">{{nickname}}</text>
  </view>

  <!-- 副导航 -->
  <view qq:if="{{common_app_is_head_vice_nav == 1 && head_nav_list.length > 0}}" class="head-nav oh wh-auto">
    <block qq:for="{{head_nav_list}}" qq:key="key">
      <navigator url="/pages/{{item.url}}/{{item.url}}" hover-class="none">
        <view class="head-nav-item tc fl">
          <view>{{item.count}}</view>
          <view>{{item.name}}</view>
        </view>
      </navigator>
    </block>
  </view>

  <!-- 右上角 -->
  <view class="message-nav">
    <navigator url="/pages/message/message" hover-class="none">
      <image src="/images/user-head-message-icon.png" mode="aspectFill" />
      <text>消息</text>
      <text>{{message_total}}</text>
    </navigator>
  </view>
</view>

<!-- 导航 -->
<view class="nav-box bg-white">
  <block qq:for="{{navigation}}" qq:key="ckey">
    <!-- 主导航 -->
    <view data-value="{{item.event_value}}" data-type="{{item.event_type}}" bindtap="navigation_event" class="nav-item br-b" >
      <view class="arrow-right">
        <image src="{{item.images_url}}" class="item-icon" mode="widthFix" />
        <text class="item-name">{{item.name}}</text>
        <text qq:if="{{(item.desc || null) != null}}" class="item-desc fr tr single-text cr-ccc">{{item.desc}}</text>
      </view>
    </view>

    <!-- 订单自定义副导航 -->
    <view qq:if="{{item.event_value == '/pages/user-order/user-order' && user_order_status_list.length > 0}}" class="items-list br-b oh">
      <block qq:for="{{user_order_status_list}}" qq:key="key" qq:for-item="items">
        <navigator url="{{items.url}}" hover-class="none">
          <view class="items fl tc">
            <view class="badge-icon">
              <component-badge prop-number="{{items.count}}"></component-badge>
            </view>
            <image src="/images/user-index-nav-order-icon-{{items.status}}.png" class="items-icon" mode="aspectFill" />
            <view class="items-name">{{items.name}}</view>
          </view>
        </navigator>
      </block>
    </view>
  </block>

  <view class="nav-item br-b" bindtap="clear_storage">
    <image src="/images/user-nav-cache-icon.png" class="item-icon" mode="widthFix" />
    <text class="item-name">安全退出</text>
  </view>

  <view qq:if="{{customer_service_tel != null}}" class="nav-item" bindtap="call_event">
    <image src="/images/user-nav-customer-service-icon.png" class="item-icon" mode="widthFix" />
    <text class="item-name">客服电话 </text>
    <text class="item-name cr-blue">{{customer_service_tel}}</text>
  </view>
</view>

<!-- 用户中心公告 -->
<view qq:if="{{common_user_center_notice != null}}" class="user-notice">
    <view class="tips">{{common_user_center_notice}}</view>
</view>

<!-- 在线客服 -->
<view qq:if="{{common_app_is_online_service == 1}}">
  <import src="/pages/lib/online-service/content.qml" />
  <template is="online_service"></template>
</view>

<!-- 版权 -->
<import src="/pages/common/copyright.qml" />
<template is="copyright"></template>
