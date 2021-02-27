<form qq:if="{{data_list_loding_status != 1}}" bindsubmit="formSubmit" qq:if="{{data_list_loding_status == 0}}">
  <view class="content-top bg-white spacing-mb">
    <view>邀请人奖励 <text class="cr-main">{{data.reward_master || data_base.reward_master}}</text> 积分</view>
    <view>受邀人奖励 <text class="cr-main">{{data.reward_invitee || data_base.reward_invitee}}</text> 积分</view>
  </view>
  <view class="form-container spacing-mb oh">
    <view qq:if="{{(data_base.is_qrcode_must_userinfo || 0) == 1}}">
      <view class="form-gorup bg-white">
        <view class="form-gorup-title">联系人姓名<text class="form-group-tips-must">必填</text></view>
        <input type="text" name="name" placeholder-class="cr-ccc" class="cr-666" placeholder="联系人姓名格式 2~30 个字符之间" maxlength="30" value="{{data.name || ''}}" />
      </view>
      <view class="form-gorup bg-white">
        <view class="form-gorup-title">联系人电话<text class="form-group-tips-must">必填</text></view>
        <input type="text" name="tel" placeholder-class="cr-ccc" class="cr-666" placeholder="联系人电话 6~15 个字符" maxlength="15" value="{{data.tel || ''}}" />
      </view>
      <view class="form-gorup bg-white">
        <view class="form-gorup-title">联系人地址<text class="form-group-tips-must">必填</text></view>
        <input type="text" name="address" placeholder-class="cr-ccc" class="cr-666" placeholder="联系人地址、最多230个字符" maxlength="230" value="{{data.address || ''}}" />
      </view>
    </view>
    <view class="form-gorup bg-white">
      <view class="form-gorup-title">备注<text class="form-group-tips">选填</text></view>
      <input type="text" name="note" placeholder-class="cr-ccc" class="cr-666" placeholder="备注最多230个字符" maxlength="60" value="{{data.note || ''}}" />
    </view>
    <view class="form-gorup">
      <button class="bg-main submit-bottom" type="default" formType="submit" hover-class="none" loading="{{form_submit_loading}}" disabled="{{form_submit_loading}}">提交</button>
    </view>
  </view>
</form>
<view qq:if="{{data_list_loding_status != 0}}">
  <import src="/pages/common/nodata.qml" />
  <template is="nodata" data="{{status: data_list_loding_status, msg: data_list_loding_msg}}"></template>
</view>