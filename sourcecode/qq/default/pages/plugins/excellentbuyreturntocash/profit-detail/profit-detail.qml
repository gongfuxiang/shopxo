<view qq:if="{{detail != null}}">
  <view qq:if="{{detail_list.length > 0}}" class="panel-item">
    <view class="panel-content bg-white">
      <view qq:for="{{detail_list}}" qq:key="item" class="item br-b oh">
        <view class="title fl cr-888">{{item.name}}</view>
        <view class="content cr-666 fl br-l">{{item.value}}</view>
      </view>
    </view>
  </view>

  <import src="/pages/common/bottom_line.qml" />
  <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
</view>

<view qq:if="{{detail == null}}">
  <import src="/pages/common/nodata.qml" />
  <template is="nodata" data="{{status: data_list_loding_status, msg: data_list_loding_msg}}"></template>

  <view class="nav-back tc wh-auto">
    <navigator open-type="navigateBack" hover-class="none">
      <button type="default" size="mini" class="cr-888 br" hover-class="none">返回</button>
    </navigator>
  </view>
</view>