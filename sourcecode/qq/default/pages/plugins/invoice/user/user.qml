<!-- 导航 -->
<view qq:if="{{nav_list.length > 0 && (data_base || null) != null}}" class="nav oh bg-white">
  <block qq:for="{{nav_list}}" qq:key="key">
    <navigator url="{{item.url}}" hover-class="none">
      <view class="item fl tc">
        <image src="{{item.icon}}" mode="scaleToFill" class="dis-block" />
        <view class="title">{{item.title}}</view>
      </view>
    </navigator>
  </block>
</view>

<!-- 通知  -->
<view qq:if="{{(data_base.invoice_desc || null) != null && data_base.invoice_desc.length > 0}}" class="tips-container spacing-mt">
  <view class="tips">
    <view qq:for="{{data_base.invoice_desc}}" qq:key="key" class="item">
      {{item}}
    </view>
  </view>
</view>