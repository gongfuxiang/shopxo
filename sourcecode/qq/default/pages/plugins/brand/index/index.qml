<view qq:if="{{(data_base || null) != null}}">
  <!-- 分类 -->
  <scroll-view qq:if="{{(brand_category_list || null) != null && brand_category_list.length > 0}}" class="nav-list bg-white tc oh" scroll-x="true">
    <view class="item cr-888 {{nav_active_value == 0 ? 'active' : ''}}" bindtap="nav_event" data-value="0">全部</view>
    <block qq:for="{{brand_category_list}}" qq:key="key">
      <view class="item cr-888 {{nav_active_value == item.id ? 'active' : ''}}" bindtap="nav_event" data-value="{{item.id}}">{{item.name}}</view>
    </block>
  </scroll-view>

  <!-- 品牌列表 -->
  <view qq:if="{{(brand_list || null) != null && brand_list.length > 0}}" class="data-list oh spacing-mt">
    <block qq:for="{{brand_list}}" qq:key="key">
      <view qq:if="{{(item.is_not_show || 0) == 0}}" class="items bg-white">
        <navigator url="/pages/goods-search/goods-search?brand_id={{item.id}}" hover-class="none">
          <image src="{{item.logo}}" mode="aspectFit" />
          <view class="base br-t-dashed">
            <view class="single-text name tc">{{item.name}}</view>
            <view class="multi-text desc">{{item.describe}}</view>
            </view>
        </navigator>
      </view>
    </block>
  </view>

  <!-- 结尾 -->
  <import src="/pages/common/bottom_line.qml" />
  <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
</view>
<view qq:if="{{data_list_loding_status != 3}}">
  <import src="/pages/common/nodata.qml" />
  <template is="nodata" data="{{status: data_list_loding_status, msg: data_list_loding_msg}}"></template>
</view>