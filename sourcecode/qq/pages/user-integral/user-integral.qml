<scroll-view qq:if="{{data_list.length > 0}}" scroll-y="{{true}}" class="scroll-box" bindscrolltolower="scroll_lower" lower-threshold="30">
  <view class="content">
    <view class="data-card bg-white br-b" qq:for="{{data_list}}">
      <view class="data-box oh">
      <import src="/pages/common/nodata.qml" />
        <text class="data-title">{{item.type_name}}</text>
        <text class="data-time fr">{{item.add_time_time}}</text>
      </view>
      <view class="data-value">
        <text class="cr-666">原始 {{item.original_integral}}</text>
        <text class="cr-main">最新 {{item.new_integral}}</text>
      </view>
      <view class="data-detail">{{item.msg}}</view>
    </view>
    <view qq:if="{{data_list.length == 0}}">
      <import src="/pages/common/nodata.qml" />
      <template is="nodata" data="{{status: data_list_loding_status}}"></template>
    </view>
  </view>
  <import src="/pages/common/bottom_line.qml" />
  <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
</scroll-view>

<view qq:if="{{data_list.length == 0}}">
  <import src="/pages/common/nodata.qml" />
  <template is="nodata" data="{{status: data_list_loding_status}}">
  </template>
</view>