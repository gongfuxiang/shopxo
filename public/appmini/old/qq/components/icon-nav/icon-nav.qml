<view qq:if="{{propData.length > 0}}">
  <view class="data-list">
    <view class="items" qq:for="{{propData}}" qq:key="key">
      <view class="items-content" data-value="{{item.event_value}}" data-type="{{item.event_type}}" bindtap="navigation_event" style="background-color:{{item.bg_color}}">
        <image src="{{item.images_url}}" mode="aspectFit" />
      </view>
      <view class="title">{{item.name}}</view>
    </view>
  </view>
</view>