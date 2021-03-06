<view qq:if="{{(data_base || null) != null}}">
  <!-- 顶部 -->
  <view class="points-user tc">
    <navigator class="points-user-menu-submit" url="/pages/user-integral/user-integral" hover-class="none">我的积分</navigator>
    <block qq:if="{{(user || null) == null}}">
      <button type="default" size="mini" class="login-submit" bindtap="login_event">立即登录</button>
      <view class="user-desc">获知会员积分详情</view>
    </block>
    <block qq:else>
      <view class="avatar">
        <image bindtap="preview_event" src="{{user.avatar || avatar_default}}" mode="widthFix" class="dis-block" />
      </view>
      <view class="user-name">{{user.user_name_view}}</view>
      <view class="user-desc">当前可用 {{user_integral.integral || 0}} 积分</view>
    </block>

    <!-- 按钮组 -->
    <view class="submit-container">
      <button type="default" size="mini" open-type="share" class="share-submit">分享</button>
    </view>
  </view>

  <!-- 广告图片 -->
  <image qq:if="{{(data_base.right_images || null) != null}}" src="{{data_base.right_images}}" class="wh-auto dis-block spacing-mt" mode="widthFix" bindtap="right_images_event" />

  <!-- 公告信息 -->
  <view qq:if="{{(data_base.points_desc || null) != null && data_base.points_desc.length > 0}}" class="tips spacing-mt">
    <view qq:for="{{data_base.points_desc}}" qq:key="key" class="item">
      {{item}}
    </view>
  </view>

  <!-- 商品兑换 -->
  <view qq:if="{{(data_base.goods_exchange_data || null) != null && data_base.goods_exchange_data.length > 0}}" class="spacing-mt">
    <view class="spacing-nav-title">
      <text class="line"></text>
      <text class="text-wrapper">商品兑换</text>
    </view>
    <view class="data-list spacing-10">
      <view class="items bg-white" qq:for="{{data_base.goods_exchange_data}}" qq:key="key">
        <navigator url="/pages/goods-detail/goods-detail?goods_id={{item.goods.id}}" hover-class="none">
          <image src="{{item.goods.images}}" mode="aspectFit" />
          <view class="base">
            <view class="multi-text">{{item.goods.title}}</view>
            <view class="price single-text original-price">{{currency_symbol}}{{item.goods.price}}</view>
            <view class="price single-text">
              <text class="unit">需</text>
              <text class="sales-price">{{item.integral}}</text>
              <text class="unit">积分</text>
            </view>
          </view>
        </navigator>
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