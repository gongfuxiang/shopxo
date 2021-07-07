<!-- 搜索 -->
<view qq:if="{{common_app_is_header_nav_fixed == 1}}" class="search-fixed-seat"></view>
<view qq:if="{{load_status == 1 && common_app_is_enable_search == 1}}" class="search wh-auto {{common_app_is_header_nav_fixed == 1 ? 'search-fixed' : ''}}">
  <view class="search-content bg-white oh">
    <icon type="search" size="16" />
    <input type="text" confirm-type="search" placeholder="其实搜索很简单^_^！" class="wh-auto cr-888" bindconfirm="search_input_event" confirm-type="search" />
  </view>
</view>

<!-- 商城公告 -->
<view qq:if="{{load_status == 1 && (common_shop_notice || null) != null}}">
  <view class="tips">{{common_shop_notice}}</view>
</view>

<!-- 拖拽模式下不展示 -->
<block qq:if="{{home_index_floor_data_type != 2}}">
  <!-- 轮播 -->
  <component-banner prop-data="{{banner_list}}"></component-banner>

  <!-- 导航 -->
  <component-icon-nav prop-data="{{navigation}}"></component-icon-nav>
</block>

<!-- 限时秒杀 -->
<view qq:if="{{plugins_limitedtimediscount_is_valid == 1}}">
  <import src="/pages/lib/limitedtimediscount/home.qml" />
  <template is="limitedtimediscount" data="{{plugins_limitedtimediscount_data: plugins_limitedtimediscount_data, plugins_limitedtimediscount_is_show_time: plugins_limitedtimediscount_is_show_time, plugins_limitedtimediscount_timer_title: plugins_limitedtimediscount_timer_title, currency_symbol: currency_symbol}}"></template>
</view>

<!-- 楼层数据 -->
<block qq:if="{{(data_list || nul) != null && data_list.length > 0}}">
  <!-- 数据模式0,1自动+手动、2拖拽 -->
  <block qq:if="{{home_index_floor_data_type == 2}}">
    <!-- 引入拖拽数据模块 -->
    <component-layout prop-data="{{data_list}}"></component-layout>
  </block>
  <block qq:else>
    <!-- 自动+手动 -->
    <view qq:for="{{data_list}}" qq:key="key" qq:for-item="floor" class="floor spacing-mb">
      <view class="spacing-nav-title">
        <text class="line"></text>
        <text class="text-wrapper">{{floor.name}}</text>
      </view>
      <view class="floor-list">
        <view class="word" style="background-color:{{floor.bg_color || '#eaeaea'}}">
          <view qq:if="{{floor.items.length > 0}}">
            <block qq:for="{{floor.items}}" qq:key="ck" qq:for-index="icx" qq:for-item="icv">
              <navigator class="word-icon" url="/pages/goods-search/goods-search?category_id={{icv.id}}" hover-class="none">
                {{icv.name}}
              </navigator>
            </block>
          </view>
          <view qq:if="{{floor.describe.length > 0}}" class="vice-name">{{floor.describe}}</view>
          <navigator url="/pages/goods-search/goods-search?category_id={{floor.id}}" hover-class="none">
            <image qq:if="{{floor.big_images.length > 0}}" src="{{floor.big_images}}" mode="aspectFit" class="dis-block" />
          </navigator>
        </view>
        <view class="goods-list" qq:if="{{floor.goods.length > 0}}">
          <view qq:for="{{floor.goods}}" qq:key="keys" qq:for-item="goods" class="goods bg-white">
            <navigator url="/pages/goods-detail/goods-detail?goods_id={{goods.id}}" hover-class="none">
              <image src="{{goods.images}}" mode="aspectFit" />
              <view class="goods-base">
                <view class="goods-title multi-text">{{goods.title}}</view>
                <view class="sales-price">{{currency_symbol}}{{goods.min_price}}</view>
              </view>
            </navigator>
          </view>
        </view>
      </view>
    </view>
  </block>
</block>

<!-- 底部购买记录 -->
<view qq:if="{{(plugins_salerecords_data || null) != null && (plugins_salerecords_data.data || null) != null && plugins_salerecords_data.data.length > 0}}" class="spacing-mb plugins-salerecords bg-white">
  <view class="spacing-nav-title br-b">
    <text class="line"></text>
    <text class="text-wrapper">{{plugins_salerecords_data.base.home_bottom_title || '最新购买'}}</text>
  </view>
  <view qq:for="{{plugins_salerecords_data.data}}" qq:key="key" class="item oh br-b padding-sm">
    <view class="item-content single-text fl">
      <image mode="widthFix" src="{{item.user.avatar}}" class="va-m br" />
      <text class="margin-left-sm">{{item.user.user_name_view}}</text>
      <text qq:if="{{(item.user.province || null) != null}}"> - {{item.user.province}}</text>
    </view>
    <view class="item-content fl">
      <navigator url="/pages/goods-detail/goods-detail?goods_id={{item.goods_id}}" hover-class="none" class="single-text">
        <image mode="widthFix" src="{{item.images}}" class="va-m br" />
        <text class="margin-left-sm single-text">{{item.title}}</text>
      </navigator>
    </view>
    <view class="item-content single-text fr tr padding-top-xs">{{item.add_time}}</view>
  </view>
</view>

<view qq:if="{{data_list.length == 0}}">
  <import src="/pages/common/nodata.qml" />
  <template is="nodata" data="{{status: data_list_loding_status}}"></template>
</view>

<!-- 留言 -->
<view qq:if="{{load_status == 1 && common_app_is_enable_answer == 1}}" class="spacing-10">
  <navigator url="/pages/answer-form/answer-form" hover-class="none" class="bg-white">
    <image class="wh-auto" mode="widthFix" src="/images/home-consulting-image.jpg" />
  </navigator>
</view>

<!-- 结尾 -->
<import src="/pages/common/bottom_line.qml" />
<template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>

<!-- 在线客服 -->
<view qq:if="{{common_app_is_online_service == 1}}">
  <import src="/pages/lib/online-service/content.qml" />
  <template is="online_service"></template>
</view>

<!-- 快捷导航 -->
<component-quick-nav></component-quick-nav>

<view qq:if="{{load_status == 1}}">
  <import src="/pages/common/copyright.qml" />
  <template is="copyright"></template>
</view>