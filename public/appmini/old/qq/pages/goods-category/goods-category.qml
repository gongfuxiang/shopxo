<view class='left-nav'>
  <block qq:for="{{data_list}}" qq:key="key">
    <view class='items {{item.active || ""}}' data-index="{{index}}" bindtap='nav_event'>
      <text>{{item.name}}</text>
    </view>
  </block>
</view>
<view class='right-content bg-white'>
  <block qq:if="{{data_content.length > 0}}">
    <block qq:for="{{data_content}}" qq:key="keys" qq:for-item="v">
      <view class="content-items" data-value="{{v.id}}" bindtap="category_event">
        <image qq:if="{{(v.icon || null) != null}}" src="{{v.icon}}" mode="aspectFit" class="icon" />
        <view class="text single-text">{{v.name}}</view>
      </view>
    </block>
  </block>
</view>

<view qq:if="{{data_list.length == 0 && data_list_loding_status != 0}}">
  <import src="/pages/common/nodata.qml" />
  <template is="nodata" data="{{status: data_list_loding_status}}">
  </template>
</view>