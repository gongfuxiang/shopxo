<!-- 导航 -->
<view class="nav">
  <block qq:for="{{nav_status_list}}" qq:key="key">
    <view qq:if="{{nav_status_index == index}}" class="item fl tc cr-main" data-index="{{index}}" bindtap="nav_event">{{item.name}}</view>
    <view qq:else class="item fl tc" data-index="{{index}}" bindtap="nav_event">{{item.name}}</view>
  </block>
</view>

<!-- 订单列表 -->
<scroll-view scroll-y="{{true}}" class="scroll-box" bindscrolltolower="scroll_lower" lower-threshold="30">
  <view class="list-content">
    <view class="list-item bg-white spacing-mb" qq:if="{{data_list.length > 0}}" qq:for="{{data_list}}" qq:key="key">
      <view class="item-base oh br-b">
        <text class="cr-666">{{item.add_time_time}}</text>
        <text class="fr cr-main">{{item.status_text}}</text>
      </view>
      <view class="goods-item oh">
        <navigator url="/pages/user-orderaftersale-detail/user-orderaftersale-detail?oid={{item.order_id}}&did={{item.order_detail_id}}" hover-class="none">
          <image class="goods-image fl" src="{{item.order_data.items.images}}" mode="aspectFill" />
          <view class="goods-base">
            <view class="goods-title multi-text" >{{item.order_data.items.title}}</view>
            <block qq:if="{{item.order_data.items.spec != null}}">
              <view class="goods-spec cr-888" qq:for="{{item.order_data.items.spec}}" qq:key="key" qq:for-item="spec">
                {{spec.type}}:{{spec.value}}
              </view>
            </block>
            <view class="orderaftersale-btn-text" catchtap="orderaftersale_event" data-oid="{{item.id}}" data-did="{{item.order_data.items.id}}">{{item.order_data.items.orderaftersale_btn_text}}</view>
          </view>
          <view class="oh goods-price">
            <text class="sales-price">{{price_symbol}}{{item.order_data.items.price}}</text>
            <text qq:if="{{item.order_data.items.original_price > 0}}" class="original-price">{{price_symbol}}{{item.order_data.items.original_price}}</text>
            <text class="buy-number">x{{item.order_data.items.buy_number}}</text>
          </view>
        </navigator>
      </view>
      <view class="item-describe">
        <text class="cr-666">{{item.type_text}}</text>
        <text class="cr-ccc ds">/</text>
        <text class="cr-666">{{item.reason}}</text>
        <text qq:if="{{item.price > 0}}" class="cr-ccc ds">/</text>
        <text qq:if="{{item.price > 0}}" class="sales-price">{{price_symbol}}{{item.price}}</text>
        <text qq:if="{{item.number > 0}}" class="cr-main"> x{{item.number}}</text>
      </view>
      <view qq:if="{{item.status <= 2 || item.status == 4}}" class="item-operation tr br-t">
        <button qq:if="{{item.status != 3 && item.status != 5}}" class="submit-cancel" type="default" size="mini" bindtap="cancel_event" data-value="{{item.id}}" data-index="{{index}}" hover-class="none">取消
        </button>
        <button qq:if="{{item.status == 1 && item.type == 1}}" class="submit-pay cr-666 br" type="default" size="mini" bindtap="delivery_event" data-oid="{{item.order_id}}" data-did="{{item.order_detail_id}}" data-index="{{index}}" hover-class="none">退货</button>
      </view>
    </view>

    <view qq:if="{{data_list.length == 0}}">
      <import src="/pages/common/nodata.qml" />
      <template is="nodata" data="{{status: data_list_loding_status, msg: data_list_loding_msg}}">
      </template>
    </view>

    <import src="/pages/common/bottom_line.qml" />
    <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
  </view>
</scroll-view>