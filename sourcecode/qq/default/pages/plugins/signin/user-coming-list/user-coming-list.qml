<scroll-view scroll-y="{{true}}" class="scroll-box" bindscrolltolower="scroll_lower" lower-threshold="30">
  <view class="data-list">
    <view class="item bg-white spacing-mb" qq:if="{{data_list.length > 0}}" qq:for="{{data_list}}" qq:key="key">
      <view class="base oh br-b">
        <image src="{{item.user.avatar}}" class="avatar dis-block fl" mode="widthFix" bindtap="avatar_event" data-value="{{item.user.avatar}}" />
        <text class="fr nickname cr-888">{{item.user.user_name_view || ''}}</text>
      </view>
      <view class="content">
        <view class="single-text">
          <text class="title cr-666">奖励积分</text>
          <text class="value">{{item.integral}}</text>
        </view>
        <view class="single-text">
          <text class="title cr-666">签到时间</text>
          <text class="value">{{item.add_time}}</text>
        </view>
      </view>
    </view>

    <view qq:if="{{data_list.length == 0}}">
      <import src="/pages/common/nodata.qml" />
      <template is="nodata" data="{{status: data_list_loding_status}}">
      </template>
    </view>

    <import src="/pages/common/bottom_line.qml" />
    <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
  </view>
</scroll-view>