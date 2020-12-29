<view qq:if="{{(data || null) != null}}">
  <!-- 签到 -->
  <view class="coming-container tc">
    <navigator qq:if="{{(data_base.is_user_menu || 0) == 1}}" class="signin-user-menu-submit" url="/pages/plugins/signin/user-signin/user-signin" hover-class="none">我的签到</navigator>
    <view class="coming-submit {{(is_already_coming == 1) ? 'already-coming' : ''}}" bindtap="coming_event">签到</view>
    <block qq:if="{{(user || null) == null}}">
      <view class="coming-title">登录签到获得积分奖励</view>
    </block>
    <block qq:else>
      <!-- 是否已签到 -->
      <block qq:if="{{(user_signin_data || null) != null && (user_signin_data.integral || 0) > 0}}">
        <view class="coming-title">今日已签到，获得{{user_signin_data.integral}}积分，共{{user_signin_data.total}}次</view>
        <view class="coming-tips">请明日继续签到，更多积分奖励</view>
      </block>
      <block qq:else>
        <view class="coming-title am-margin-top-sm">立即签到获得积分奖励</view>
      </block>

      <!-- 队长 -->
      <block qq:if="{{(team_signin_data || null) != null && user.id == data.user_id}}">
        <view class="coming-title">
          <text>今日{{team_signin_data.day}}人签到，共{{team_signin_data.total}}人</text>
          <navigator qq:if="{{(data_base.is_team_show_coming_user || 0) == 1}}" url="/pages/plugins/signin/user-coming-list/user-coming-list?id={{data.id}}" hover-class="none" class="detail-submit">详情 >></navigator>
        </view>
        <view class="coming-tips">分享获得更多奖励</view>
      </block>
    </block>

    <!-- 按钮组 -->
    <view class="submit-container">
      <button qq:if="{{(data_base.is_team) == 1 && (user || null) != null && data.user_id != user.id}}" type="default" size="mini" class="team-submit" bindtap="team_event">组队</button>
      <button qq:if="{{(data_base.is_share) == 1}}" type="default" size="mini" open-type="share" class="share-submit">分享</button>
    </view>
  </view>

  <!-- 广告图片 -->
  <image qq:if="{{(data.right_images || null) != null}}" src="{{data.right_images}}" class="wh-auto dis-block spacing-mt" mode="widthFix" bindtap="right_images_event" />

  <!-- 公告信息 -->
  <view qq:if="{{(data_base.signin_desc || null) != null && data_base.signin_desc.length > 0}}" class="tips spacing-mt">
    <view qq:for="{{data_base.signin_desc}}" qq:key="key" class="item">
      {{item}}
    </view>
  </view>

  <!-- 推荐商品 -->
  <view qq:if="{{(data.goods_list || null) != null && data.goods_list.length > 0}}" class="spacing-mt">
    <view class="spacing-nav-title">
      <text class="line"></text>
      <text class="text-wrapper">推荐商品</text>
    </view>
    <view class="data-list spacing-10">
      <view class="items bg-white" qq:for="{{data.goods_list}}" qq:key="key">
        <navigator url="/pages/goods-detail/goods-detail?goods_id={{item.id}}" hover-class="none">
          <image src="{{item.images}}" mode="aspectFit" />
          <view class="base">
            <view class="multi-text">{{item.title}}</view>
            <view class="price single-text">
              <text class="sales-price">{{currency_symbol}}{{item.price}}</text>
            </view>
          </view>
        </navigator>
      </view>
    </view>
  </view>

  <!-- 签到成功提示信息 -->
  <view qq:if="{{is_success_tips == 1}}" class="coming-tips-container am-text-center">
    <view class="coming-content tc">
      <view class="icon-close-submit" bindtap="coming_success_close_event">
        <icon type="clear" size="20" />
      </view>
      <image src="/images/plugins/signin/coming-success-icon.png" mode="widthFix" />
      <view class="coming-tips-content">
          <text class="coming-tips-text">获得 <text>{{coming_integral}}</text> 积分</text>
      </view>
    </view>
  </view>

  <!-- 结尾 -->
  <import src="/pages/common/bottom_line.qml" />
  <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
</view>
<view qq:else>
  <import src="/pages/common/nodata.qml" />
  <template is="nodata" data="{{status: data_list_loding_status, msg: data_list_loding_msg}}"></template>
</view>