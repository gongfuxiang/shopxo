<scroll-view scroll-y="{{true}}" class="scroll-box" bindscrolltolower="scroll_lower" lower-threshold="30">
    <view class="item bg-white spacing-mb" qq:if="{{data_list.length > 0}}" qq:for="{{data_list}}">
        <view class="base br-b-dashed oh">
            <text class="name cr-666">{{item.name}}</text>
            <text class="time fr cr-888">{{item.add_time}}</text>
        </view>
        <view class="content">
            <view class="desc">{{item.content}}</view>
        </view>
        <view qq:if="{{(item.reply || null) != null}}" class="answer br-t">
            <text class="reply-icon bg-main cr-fff">答</text>
            <text class="reply-content cr-888">{{item.reply}}</text>
        </view>
    </view>
    <view qq:if="{{data_list.length == 0}}">
        <import src="/pages/common/nodata.qml" />
        <template is="nodata" data="{{status: data_list_loding_status}}"></template>
    </view>
    
    <import src="/pages/common/bottom_line.qml" />
    <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
</scroll-view>