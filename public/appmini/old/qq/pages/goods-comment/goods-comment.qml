<!-- 评分 -->
<view qq:if="{{goods_score != null}}" class="score-container oh br-b">
    <view class="score fl tc">
      <view class="cr-666">动态评分</view>
      <view class="value">{{goods_score.avg || '0.0'}}</view>
    </view>
    <view class="progress fr tc">
      <block qq:if="{{goods_score.avg > 0}}">
        <block qq:for="{{goods_score.rating}}" qq:key="key">
          <view qq:if="{{item.portion > 0}}" class="progress-bar {{progress_class[index]}}" style="width: {{item.portion}}%;">{{item.name}}</view>
        </block>
      </block>
      <text qq:else class="cr-888">暂无评分</text>
    </view>
</view>

<!-- 列表 -->
<scroll-view scroll-y="{{true}}" class="scroll-box" bindscrolltolower="scroll_lower" lower-threshold="30">
  <view qq:for="{{data_list}}" qq:key="key" class="comment-item bg-white spacing-mt">
    <view class="oh nav">
      <image class="avatar dis-block fl" src="{{item.user.avatar || '/images/default-user.png'}}" mode="aspectFit"></image>
      <view class="base-nav fr">
        <text>{{item.user.user_name_view}}</text>
        <text class="cr-ccc">评论于</text>
        <text class="cr-666">{{item.add_time_time}}</text>
      </view>
    </view>
    <view class="base-content oh">
      <view class="content cr-666">{{item.content}}</view>
      <view qq:if="{{(item.images || null) != null && item.images.length > 0}}" class="images oh">
        <block qq:for="{{item.images}}" qq:key="iv" qq:for-index="ix" qq:for-item="iv">
          <image class="br" bindtap="images_show_event" data-index="{{index}}" data-ix="{{ix}}" src="{{iv}}" mode="aspectFit"></image>
        </block>
      </view>
      <view qq:if="{{(item.msg || null) != null}}" class="spec">{{item.msg}}</view>
      <view qq:if="{{item.is_reply == 1 && (item.reply || null) != null}}" class="reply br-t-dashed">
        <text class="cr-888">管理员回复：</text>
        <text class="reply-desc">{{item.reply}}</text>
      </view>
    </view>
  </view>

  <view qq:if="{{data_list.length == 0}}">
    <import src="/pages/common/nodata.qml" />
    <template is="nodata" data="{{status: data_list_loding_status}}"></template>
  </view>
    
  <import src="/pages/common/bottom_line.qml" />
  <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
</scroll-view>