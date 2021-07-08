<view qq:if="{{(data || null) != null}}">
  <!-- 搜索 -->
  <block qq:if="{{(data.is_header || 0) == 1}}">
    <view  class="search wh-auto">
      <view class="search-content bg-white oh">
        <icon type="search" size="16" />
        <input type="text" confirm-type="search" placeholder="其实搜索很简单^_^！" class="wh-auto cr-888" bindconfirm="search_input_event" confirm-type="search" />
      </view>
    </view>
  </block>

  <!-- 拖拽模式、引入拖拽数据模块 -->
  <component-layout prop-data="{{layout_data}}"></component-layout>

  <!-- 结尾 -->
  <block qq:if="{{(data.is_footer || 0) == 1}}">
    <import src="/pages/common/bottom_line.qml" />
    <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
  </block>
</view>
<view qq:else>
  <import src="/pages/common/nodata.qml" />
  <template is="nodata" data="{{status: data_list_loding_status, msg: data_list_loding_msg}}"></template>
</view>