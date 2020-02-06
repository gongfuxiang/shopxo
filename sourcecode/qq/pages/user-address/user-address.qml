<view class="page">
  <view qq:if="{{data_list.length > 0}}">
    <view class="item bg-white spacing-mb" qq:for="{{data_list}}" qq:key="key">
      <view bindtap="address_conent_event" data-index="{{index}}">
        <view class="base oh">
          <text>{{item.name}}</text>
          <text class="fr">{{item.tel}}</text>
        </view>
        <view class="address oh">
          <image class="item-icon fl" src="/images/user-address.png" mode="widthFix" />
          <view class="text fr">{{item.province_name}}{{item.city_name}}{{item.county_name}}{{item.address}}</view>
        </view>
      </view>
      <view class="operation br-t oh">
        <view class="default fl" bindtap="address_default_event" data-value="{{item.id}}">
          <image qq:if="{{is_default == item.id}}" class="item-icon" src="/images/default-select-active-icon.png" mode="widthFix" />
          <image qq:else class="item-icon" src="/images/default-select-icon.png" mode="widthFix" />
          <text>设为默认地址</text>
        </view>
        <button class="fr cr-666 delete-submit br" type="default" size="mini" bindtap="address_delete_event" data-index="{{index}}" data-value="{{item.id}}" hover-class="none">删除</button>
        <navigator url="/pages/user-address-save/user-address-save?id={{item.id}}" open-type="navigate" hover-class="none">
          <button class="fr cr-666 br" type="default" size="mini" bindtap="address_edit_event" hover-class="none">编辑</button>
        </navigator>
      </view>
    </view>
  </view>

  <view qq:if="{{data_list.length == 0}}">
    <import src="/pages/common/nodata.qml" />
    <template is="nodata" data="{{status: data_list_loding_status}}"></template>
  </view>
    
  <import src="/pages/common/bottom_line.qml" />
  <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
  
  <navigator url="/pages/user-address-save/user-address-save" open-type="navigate" hover-class="none">
    <button class="submit-fixed submit-bottom" type="default" hover-class="none">新增地址</button>
  </navigator>
</view>