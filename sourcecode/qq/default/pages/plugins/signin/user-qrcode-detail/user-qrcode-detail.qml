<view qq:if="{{detail != null}}">
  <!-- 基础信息 -->
  <view qq:if="{{detail_list.length > 0}}" class="panel-item">
    <view class="panel-content bg-white">
      <view class="panel-title">基础信息</view>
      <view qq:for="{{detail_list}}" qq:key="item" class="item br-b oh">
        <view class="title fl">{{item.name}}</view>
        <view class="content fl br-l">{{item.value}}</view>
      </view>
    </view>
  </view>

  <!-- 连续签到翻倍奖励配置 -->
  <view qq:if="{{(detail.continuous_rules || null) != null && detail.continuous_rules.length > 0}}" class="panel-item spacing-mt">
    <view class="panel-content bg-white">
      <view class="panel-title">连续签到翻倍奖励配置</view>
      <view qq:for="{{detail.continuous_rules}}" qq:key="item" class="item br-b oh">
        <view class="content fl br-l">连续{{item.number}}天、翻{{item.value}}倍</view>
      </view>
    </view>
  </view>

  <!-- 指定时段额外奖励 -->
  <view qq:if="{{(detail.specified_time_reward || null) != null && (detail.specified_time_reward.time_start || null) != null && (detail.specified_time_reward.time_end || null) != null && (detail.specified_time_reward.value || null) != null}}" class="panel-item spacing-mt">
    <view class="panel-content bg-white">
      <view class="panel-title">指定时段额外奖励</view>
      <view class="item br-b oh">
        <view class="content fl br-l">时段 {{detail.specified_time_reward.time_start}} ~ {{detail.specified_time_reward.time_end}}、额外奖励 {{detail.specified_time_reward.value}}</view>
      </view>
    </view>
  </view>

  <import src="/pages/common/bottom_line.qml" />
  <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
</view>

<view qq:if="{{detail == null}}">
  <import src="/pages/common/nodata.qml" />
  <template is="nodata" data="{{status: data_list_loding_status, msg: data_list_loding_msg}}"></template>

  <view qq:if="{{data_list_loding_status != 1}}" class="nav-back tc wh-auto">
    <navigator open-type="navigateBack" hover-class="none">
      <button type="default" size="mini" class="cr-888 br" hover-class="none">返回</button>
    </navigator>
  </view>
</view>