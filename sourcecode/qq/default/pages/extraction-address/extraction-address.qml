<view class="page">
  <view qq:if="{{data_list.length > 0}}">
    <view class="item bg-white spacing-mb" qq:for="{{data_list}}" qq:key="key">
      <view bindtap="address_conent_event" data-index="{{index}}">
        <view qq:if="{{(item.logo || null) != null}}" class="fl oh margin-right-lg">
          <image class="dis-block address-logo" src="{{item.logo}}" mode="widthFix" />
        </view>
        <view class="oh">
          <view class="base oh">
            <text qq:if="{{(item.alias || null) != null}}" class="address-alias">{{item.alias}}</text>
            <text>{{item.name}}</text>
            <text class="fr">{{item.tel}}</text>
          </view>
          <view class="address oh">
            <image class="item-icon fl" src="/images/user-address.png" mode="widthFix" />
            <view class="text fr">{{item.province_name || ''}}{{item.city_name || ''}}{{item.county_name || ''}}{{item.address || ''}}</view>
          </view>
        </view>
      </view>
      <view class="operation br-t oh">
        <view qq:if="{{(item.distance_value || null) != null && (item.distance_unit || null) != null}}" class="fl margin-top-lg">
          <text class="cr-888">距离</text>
          <text class="cr-666">{{item.distance_value}}</text>
          <text class="cr-888">{{item.distance_unit}}</text>
        </view>
        <button qq:if="{{(item.lng || null) != null && (item.lat || null) != null && item.lng != 0 && item.lat != 0}}" class="fr cr-666 map-submit br" type="default" size="mini" bindtap="address_map_event" data-index="{{index}}" hover-class="none">查看地图</button>
      </view>
    </view>
  </view>

  <view qq:if="{{data_list.length == 0}}">
    <import src="/pages/common/nodata.qml" />
    <template is="nodata" data="{{status: data_list_loding_status}}"></template>
  </view>
    
  <import src="/pages/common/bottom_line.qml" />
  <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
</view>