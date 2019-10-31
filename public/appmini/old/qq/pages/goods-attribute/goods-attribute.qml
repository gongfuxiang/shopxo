<view qq:if="{{goods_attribute.length > 0}}" class="goods-attribute bg-white">
  <view qq:for="{{goods_attribute}}" class="item br-b oh">
    <view class="title fl br-r">{{item.name}}</view>
    <view class="content cr-888 fl">
      <text qq:for="{{item.find}}" qq:for-index="keys" qq:for-item="items">
        <text>{{items.name}}</text>
        <text qq:if="{{keys < item.find.length-1}}"> , </text>
      </text>
    </view>
  </view>
</view>

<view class="nav-back tc wh-auto">
  <navigator url="/pages/goods-detail/goods-detail" open-type="navigateBack" hover-class="none">
    <button type="default" size="mini" class="cr-888" hover-class="none">返回</button>
  </navigator>
</view>
