<view qq:if="{{(data_base || null) != null}}">
  <!-- 分类 -->
  <scroll-view qq:if="{{(shop_category || null) != null && shop_category.length > 0}}" class="nav-list bg-white oh" scroll-x="true">
    <view class="item cr-888 {{nav_active_value == 0 ? 'active' : ''}}" bindtap="nav_event" data-value="0">全部</view>
    <block qq:for="{{shop_category}}" qq:key="key">
      <view class="item cr-888 {{nav_active_value == item.id ? 'active' : ''}}" bindtap="nav_event" data-value="{{item.id}}">{{item.name}}</view>
    </block>
  </scroll-view>

  <!-- 列表 -->
  <scroll-view scroll-y="{{true}}" class="scroll-box" bindscrolltolower="scroll_lower" lower-threshold="30">
    <view qq:if="{{(data_list || null) != null && data_list.length > 0}}" class="data-list oh spacing-mt">
      <block qq:for="{{data_list}}" qq:key="key">
        <view class="item bg-white">
          <navigator url="/pages/plugins/shop/detail/detail?id={{item.id}}" hover-class="none">
            <image src="{{item.logo_long}}" mode="aspectFit" />
            <view class="base br-t-dashed">
              <view class="single-text name tc">{{item.name}}</view>
              <view class="multi-text desc">{{item.describe}}</view>
              <view class="oh margin-top-sm single-text base-bottom">
                <view class="bitem fl cr-888 single-text">商品 {{item.goods_count}}</view>
                <view class="bitem fr cr-888 single-text tr">销量 {{item.goods_sales_count}}</view>
              </view>
            </view>
          </navigator>
        </view>
      </block>
    </view>
    <view qq:else>
      <import src="/pages/common/nodata.qml" />
      <template is="nodata" data="{{status: data_list_loding_status, msg: data_list_loding_msg}}"></template>
    </view>

    <!-- 结尾 -->
    <import src="/pages/common/bottom_line.qml" />
    <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
  </scroll-view>
</view>
<view qq:else>
  <import src="/pages/common/nodata.qml" />
  <template is="nodata" data="{{status: data_list_loding_status, msg: data_list_loding_msg}}"></template>
</view>