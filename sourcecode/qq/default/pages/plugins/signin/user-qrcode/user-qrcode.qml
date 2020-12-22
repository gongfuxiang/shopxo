<scroll-view scroll-y="{{true}}" class="scroll-box" bindscrolltolower="scroll_lower" lower-threshold="30">
  <view class="data-list">
    <block qq:if="{{data_list.length > 0}}">
      <view class="item bg-white spacing-mb" qq:for="{{data_list}}" qq:key="key">
        <view class="base oh br-b">
          <text class="cr-666">{{item.add_time}}</text>
        </view>
        <navigator url="/pages/plugins/signin/user-qrcode-detail/user-qrcode-detail?id={{item.id}}" hover-class="none">
          <view class="content">
            <view class="single-text">
              <text class="title cr-666">是否启用</text>
              <text class="value">{{item.is_enable_name}}</text>
            </view>
            <view class="single-text">
              <text class="title cr-666">邀请人奖励积分</text>
              <text class="value">{{item.reward_master}}</text>
            </view>
            <view class="single-text">
              <text class="title cr-666">受邀人奖励积分</text>
              <text class="value">{{item.reward_invitee}}</text>
            </view>
          </view>
        </navigator>
        <view class="operation tr br-t-dashed">
          <button class="cr-666 br" type="default" size="mini" hover-class="none" data-value="{{item.id}}" bindtap="show_event">查看</button>
          <button qq:if="{{(data_base.is_team_show_coming_user || 0) == 1}}" class="cr-666 br" type="default" size="mini" hover-class="none" data-value="{{item.id}}" bindtap="coming_event">签到</button>
          <button class="cr-666 br" type="default" size="mini" hover-class="none" data-value="{{item.id}}" bindtap="edit_event">编辑</button>
        </view>
      </view>
    </block>

    <block qq:else>
      <view qq:if="{{(data_base || null) != null && (data_base.is_team || 0) == 1}}" class="user-team-container tc">
          <button type="default" hover-class="none" bindtap="team_event">组队签到</button>
          <view>组队分享让更多人参与签到、获得更多积分奖励</view>
      </view>
      <view qq:else>
        <import src="/pages/common/nodata.qml" />
        <template is="nodata" data="{{status: data_list_loding_status}}"></template>
      </view>
    </block>

    <import src="/pages/common/bottom_line.qml" />
    <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
  </view>
</scroll-view>