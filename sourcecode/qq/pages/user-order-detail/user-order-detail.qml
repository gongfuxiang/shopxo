<view qq:if="{{detail != null}}">
  <!-- 地址 -->
  <view qq:if="{{detail.order_model == 0 || detail.order_model == 2}}" class="address bg-white spacing-mb">
      <view class="address-base oh">
          <text qq:if="{{(detail.address_data.alias || null) != null}}" class="address-alias">{{detail.address_data.alias}}</text>
          <text>{{detail.address_data.name}}</text>
          <text class="fr">{{detail.address_data.tel}}</text>
      </view>
      <view class="address-detail oh">
          <image class="icon fl" src="/images/user-address.png" mode="widthFix" />
          <view class="text fr">
            {{detail.address_data.province_name}}{{detail.address_data.city_name}}{{detail.address_data.county_name}}{{detail.address_data.address}}
            <!-- <text qq:if="{{detail.order_model == 2 && (detail.address_data.lng || 0) > 0 && (detail.address_data.lat || 0) > 0}}" class="cr-666 br address-map-submit" bindtap="address_map_event">查看位置</text> -->
          </view>
      </view>
  </view>

  <!-- 商品列表 -->
  <view class="goods bg-white spacing-mb">
      <view qq:for="{{detail.items}}" qq:key="item" class="goods-item br-b-dashed oh">
        <navigator url="/pages/goods-detail/goods-detail?goods_id={{item.goods_id}}" hover-class="none">
          <image class="goods-image fl" src="{{item.images}}" mode="aspectFill" />
          <view class="goods-base">
            <view class="goods-title multi-text">{{item.title}}</view>
            <block qq:if="{{item.spec != null}}">
              <view class="goods-attribute cr-888" qq:for="{{item.spec}}" qq:key="spec" qq:for-item="spec">
                {{spec.type}}:{{spec.value}}
              </view>
            </block>
          </view>
          <view class="oh goods-price">
            <text class="sales-price">{{price_symbol}}{{item.price}}</text>
            <text qq:if="{{item.original_price > 0}}" class="original-price">{{price_symbol}}{{item.original_price}}</text>
            <text class="buy-number">x{{item.buy_number}}</text>
          </view>
        </navigator>
      </view>
      <view class="order-describe">{{detail.describe}}</view>
  </view>

  <!-- 虚拟销售数据 -->
  <view qq:if="{{detail.order_model == 3 && detail.pay_status == 1 && (detail.status == 3 || detail.status == 4)}}" class="panel-item spacing-mt site-fictitious">
    <view class="panel-title">{{site_fictitious.title || '密钥信息'}}</view>
    <view class="panel-content bg-white oh">
      <view qq:if="{{(site_fictitious.tips || null) != null}}" class="tips-value">
        <rich-text nodes="{{site_fictitious.tips}}"></rich-text>
      </view>
      <view qq:for="{{detail.items}}" qq:key="item" class="item br-b-dashed oh">
        <image class="left-image br fl" src="{{item.images}}" mode="aspectFill" />
        <view class="right-value fr">
          <rich-text qq:if="{{(item.fictitious_goods_value || null) != null}}" nodes="{{item.fictitious_goods_value}}"></rich-text>
          <text qq:else class="cr-888">未配置数据</text>
        </view>
      </view>
    </view>
  </view>

  <!-- 自提信息 -->
  <view qq:if="{{detail.order_model == 2 && (detail.status == 2 || detail.status == 3) && (detail.extraction_data || null) != null}}" class="panel-item spacing-mt site-extraction">
    <view class="panel-title">取货信息</view>
    <view class="panel-content bg-white oh">
      <view>
        <text>取货码：</text>
        <text class="code">{{detail.extraction_data.code || '取货码不存在、请联系管理员'}}</text>
      </view>
      <image qq:if="{{(detail.extraction_data.images || null) != null}}" class="br qrcode" src="{{detail.extraction_data.images}}" mode="aspectFill" />
    </view>
  </view>

  <!-- 订单基础数据 -->
  <view qq:if="{{detail_list.length > 0}}" class="panel-item spacing-mt">
    <view class="panel-title">基础数据</view>
    <view class="panel-content bg-white">
      <view qq:for="{{detail_list}}" qq:key="item" class="item br-b oh">
        <view class="title fl">{{item.name}}</view>
        <view class="content cr-888 fl br-l">{{item.value}}</view>
      </view>
    </view>
  </view>

  <!-- 扩展数据 -->
  <view qq:if="{{extension_data.length > 0}}" class="panel-item spacing-mt extension-list">
    <view class="panel-title">扩展数据</view>
    <view class="panel-content bg-white">
      <view qq:for="{{extension_data}}" qq:key="item" class="item br-b oh">
      <text class="title">{{item.name}}</text>
      <text class="content cr-888 br-l">{{item.tips}}</text>
    </view>
    </view>
  </view>

  <import src="/pages/common/bottom_line.qml" />
  <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
</view>

<view qq:if="{{detail == null}}">
    <import src="/pages/common/nodata.qml" />
    <template is="nodata" data="{{status: data_list_loding_status, msg: data_list_loding_msg}}"></template>
</view>

<view class="nav-back tc wh-auto">
  <navigator open-type="navigateBack" hover-class="none">
    <button type="default" size="mini" class="cr-888 br" hover-class="none">返回</button>
  </navigator>
</view>