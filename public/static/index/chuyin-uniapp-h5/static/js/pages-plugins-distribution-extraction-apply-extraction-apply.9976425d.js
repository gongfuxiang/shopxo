(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-plugins-distribution-extraction-apply-extraction-apply"],{2444:function(e,t,a){"use strict";var n=a("4ea4");a("7db0"),a("c740"),a("d81d"),a("a434"),a("e25e"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var i=n(a("2909"));a("96cf");var s=n(a("1da1")),r=n(a("5530")),u=a("2f62"),d=n(a("9fc0")),c=n(a("10b9")),l=(a("b116"),{mixins:[c.default],data:function(){return{addressData:{alias:"",name:"",tel:"",province:"",city:"",county:"",address:"",idcard_front:"",idcard_back:"",lng:"",lat:""},multiArray:[["上海"],["上海市"],["黄浦区"]],selectArea:[{name:"",id:0},{name:"",id:0},{name:"",id:0}],user_location:null,isOrder:!1,addressList:[],multiIndex:[0,0,0],addressVisible:!1,value:"",indicatorStyle:"height: ".concat(Math.round(uni.getSystemInfoSync().screenWidth/7.5),"px;")}},computed:(0,r.default)({},(0,u.mapGetters)(["regions"])),onLoad:function(e){var t=this;return(0,s.default)(regeneratorRuntime.mark((function a(){return regeneratorRuntime.wrap((function(a){while(1)switch(a.prev=a.next){case 0:t.initLoadingFn((0,s.default)(regeneratorRuntime.mark((function a(){var n,s,u,c,l,o,m;return regeneratorRuntime.wrap((function(a){while(1)switch(a.prev=a.next){case 0:if(n="取货点信息",!(t.regions.length<=0)){a.next=4;break}return a.next=4,t.getRegions();case 4:if(s=(0,i.default)(t.regions),t.addressList=s,"edit"!==e.type){a.next=24;break}return u={pluginsname:"distribution",pluginscontrol:"extraction",pluginsaction:"applyinfo"},a.next=10,t.loadingFn((0,d.default)("/api/plugins/index","POST",u));case 10:c=a.sent,l=c.data,t.addressData=(0,r.default)({},l),t.user_location={name:"",address:l.address},n="取货点信息",t.multiArray[0]=s.map((function(e){return e.name})),o=s.find((function(e){return e.name==t.addressData["province_name"]})),m=o["items"].find((function(e){return e.name==t.addressData["city_name"]})),t.multiArray[1]=o["items"].map((function(e){return e.name})),t.multiArray[2]=m["items"].map((function(e){return e.name})),t.selectArea=[{name:t.addressData["province_name"],id:t.addressData["province"]},{name:t.addressData["city_name"],id:t.addressData["city"]},{name:t.addressData["county_name"],id:t.addressData["county"]}],t.multiIndex=[t.multiArray[0].findIndex((function(e){return e==t.addressData["province_name"]})),t.multiArray[1].findIndex((function(e){return e==t.addressData["city_name"]})),t.multiArray[2].findIndex((function(e){return e==t.addressData["county_name"]}))],a.next=25;break;case 24:"orderedit"==e.type?(n="编辑收货地址",t.isOrder=!0,t.addressData=JSON.parse(e.data)):(t.multiArray[0]=s.map((function(e){return e.name})),t.multiArray[1]=s[0]["items"].map((function(e){return e.name})),t.multiArray[2]=s[0]["items"][0]["items"].map((function(e){return e.name})));case 25:t.manageType=e.type,uni.setNavigationBarTitle({title:n});case 27:case"end":return a.stop()}}),a)}))));case 1:case"end":return a.stop()}}),a)})))()},onShow:function(){this.user_location_init()},methods:{getRegions:function(){var e=this;return(0,s.default)(regeneratorRuntime.mark((function t(){var a,n;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,e.loadingFn((0,d.default)("/api/region/all","POST",{}));case 2:a=t.sent,n=a.data,e.$store.commit("SET_REGIONS",n);case 5:case"end":return t.stop()}}),t)})))()},bindPickerChange:function(e){for(var t=this,a=0;a<this.multiIndex.length;a++)if(0==a){var n=this.addressList.find((function(e,n){return n==t.multiIndex[a]}));this.selectArea.splice(a,1,{name:n.name,id:n.id})}else if(1==a){n=this.addressList[this.multiIndex[0]]["items"].find((function(e,n){return n==t.multiIndex[a]}));this.selectArea.splice(a,1,{name:n.name,id:n.id})}else if(2==a){n=this.addressList[this.multiIndex[0]]["items"][this.multiIndex[1]]["items"].find((function(e,n){return n==t.multiIndex[a]}));this.selectArea.splice(a,1,{name:n.name,id:n.id})}},bindMultiPickerColumnChange:function(e){switch(this.multiIndex[e.detail.column]=e.detail.value,e.detail.column){case 0:this.multiArray[1]=this.addressList[e.detail.value]["items"].map((function(e){return e.name})),this.multiArray[2]=this.addressList[e.detail.value]["items"][0]["items"].map((function(e){return e.name})),this.multiIndex.splice(1,1,0),this.multiIndex.splice(2,1,0);break;case 1:this.multiArray[2]=this.addressList[this.multiIndex[0]]["items"][e.detail.value]["items"].map((function(e){return e.name})),this.multiIndex.splice(2,1,0);break}this.$forceUpdate()},user_location_init:function(){var e=this;return(0,s.default)(regeneratorRuntime.mark((function t(){var a;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:a=uni.getStorageSync("cache_userlocation_key")||null,null,null!=a&&(e.addressData["lng"]=a["lng"]||null,e.addressData["lat"]=a["lat"]||null,e.user_location={name:a.name||null,address:a.address||null},uni.removeStorageSync("cache_userlocation_key"));case 3:case"end":return t.stop()}}),t)})))()},confirm:function(){var e=this;return(0,s.default)(regeneratorRuntime.mark((function t(){var a,n,i;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(a=e.addressData,a.name){t.next=4;break}return e.$api.msg("请填写收货人姓名"),t.abrupt("return");case 4:if(/(^1[3|4|5|7|8][0-9]{9}$)/.test(a.tel)){t.next=7;break}return e.$api.msg("请输入正确的手机号码"),t.abrupt("return");case 7:if(a.address){t.next=10;break}return e.$api.msg("请填写详细地址"),t.abrupt("return");case 10:if(e.selectArea[0]["id"]&&e.selectArea[1]["id"]&&e.selectArea[2]["id"]){t.next=13;break}return e.$api.msg("请选择所在地区"),t.abrupt("return");case 13:return n={alias:e.addressData["alias"],name:e.addressData["name"],tel:e.addressData["tel"],province:e.selectArea[0]["id"],city:e.selectArea[1]["id"],county:e.selectArea[2]["id"],address:e.addressData["address"],lng:e.addressData["lng"],lat:e.addressData["lat"]},i=(0,r.default)({pluginsname:"distribution",pluginscontrol:"extraction",pluginsaction:"applysave"},n),t.next=17,e.loadingFn((0,d.default)("/api/plugins/index","POST",i));case 17:e.$api.prePage().loadingData(),setTimeout((function(){uni.navigateBack(),e.$api.msg("地址".concat("edit"==e.manageType?"更新":"添加","成功"),2e3,!0,"success")}),1500);case 19:case"end":return t.stop()}}),t)})))()},chooseLocation:function(){},confirmOrder:function(){var e=this,t=this.addressData;t.name?/(^1[3|4|5|7|8][0-9]{9}$)/.test(t.tel)?t.address?(uni.showLoading({title:"加载中"}),(0,d.default)("/UserAddress/EditOrderAddress","POST",this.addressData,"").then((function(t){uni.hideLoading(),200==t.ret?(e.addressData.edit_times=parseInt(e.addressData.edit_times)+1,e.$api.prePage().refreshList(e.addressData,e.manageType),e.$api.msg("收货地址修改成功",2e3),setTimeout((function(){uni.navigateBack()}),500)):uni.showToast({title:t.msg,icon:"none",duration:2e3})})).catch((function(e){uni.hideLoading(),console.log(e)}))):this.$api.msg("请填写详细地址"):this.$api.msg("请输入正确的手机号码"):this.$api.msg("请填写收货人姓名")}}});t.default=l},2909:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=d;var n=u(a("6005")),i=u(a("db90")),s=u(a("06c5")),r=u(a("3427"));function u(e){return e&&e.__esModule?e:{default:e}}function d(e){return(0,n.default)(e)||(0,i.default)(e)||(0,s.default)(e)||(0,r.default)()}},3427:function(e,t,a){"use strict";function n(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}Object.defineProperty(t,"__esModule",{value:!0}),t.default=n},"4cca":function(e,t,a){"use strict";a.r(t);var n=a("f48e"),i=a("e9e9");for(var s in i)"default"!==s&&function(e){a.d(t,e,(function(){return i[e]}))}(s);a("6542");var r,u=a("f0c5"),d=Object(u["a"])(i["default"],n["b"],n["c"],!1,null,"432c33d2",null,!1,n["a"],r);t["default"]=d.exports},6005:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=s;var n=i(a("6b75"));function i(e){return e&&e.__esModule?e:{default:e}}function s(e){if(Array.isArray(e))return(0,n.default)(e)}},6542:function(e,t,a){"use strict";var n=a("be7e"),i=a.n(n);i.a},be7e:function(e,t,a){var n=a("e9ef");"string"===typeof n&&(n=[[e.i,n,""]]),n.locals&&(e.exports=n.locals);var i=a("4f06").default;i("28423ded",n,!0,{sourceMap:!1,shadowMode:!1})},db90:function(e,t,a){"use strict";function n(e){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}a("a4d3"),a("e01a"),a("d28b"),a("a630"),a("d3b7"),a("3ca3"),a("ddb0"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=n},e9e9:function(e,t,a){"use strict";a.r(t);var n=a("2444"),i=a.n(n);for(var s in n)"default"!==s&&function(e){a.d(t,e,(function(){return n[e]}))}(s);t["default"]=i.a},e9ef:function(e,t,a){var n=a("24fb");t=n(!1),t.push([e.i,'@charset "UTF-8";\n/* 页面左右间距 */\n/*远素间距*/\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */uni-page-body[data-v-432c33d2]{background:#f3f3f3}body.?%PAGE?%[data-v-432c33d2]{background:#f3f3f3}',""]),e.exports=t},f48e:function(e,t,a){"use strict";a.d(t,"b",(function(){return i})),a.d(t,"c",(function(){return s})),a.d(t,"a",(function(){return n}));var n={tuiIcon:a("f3ca").default},i=function(){var e=this,t=e.$createElement,a=e._self._c||t;return e.initLoading?e._e():a("v-uni-view",{staticClass:"addr-row"},[a("v-uni-view",{staticClass:"addr-column"},[a("v-uni-view",{staticClass:"row comonLine"},[a("v-uni-text",{staticClass:"tit"},[e._v("别名")]),a("v-uni-input",{staticClass:"input",attrs:{type:"text",placeholder:"别名","placeholder-class":"placeholder"},model:{value:e.addressData.alias,callback:function(t){e.$set(e.addressData,"alias",t)},expression:"addressData.alias"}})],1),a("v-uni-view",{staticClass:"row comonLine",attrs:{required:!0}},[a("v-uni-text",{staticClass:"tit"},[e._v("联系人")]),a("v-uni-input",{staticClass:"input",attrs:{type:"text",placeholder:"收货人姓名","placeholder-class":"placeholder"},model:{value:e.addressData.name,callback:function(t){e.$set(e.addressData,"name",t)},expression:"addressData.name"}})],1),a("v-uni-view",{staticClass:"row comonLine",attrs:{required:!0}},[a("v-uni-text",{staticClass:"tit"},[e._v("联系号码")]),a("v-uni-input",{staticClass:"input",attrs:{type:"number",maxlength:"11",placeholder:"收货人手机号码","placeholder-class":"placeholder"},model:{value:e.addressData.tel,callback:function(t){e.$set(e.addressData,"tel",t)},expression:"addressData.tel"}})],1),e.isOrder?a("v-uni-view",{staticClass:"row comonLine",attrs:{required:!0}},[a("v-uni-text",{staticClass:"tit"},[e._v("所在地区")]),a("v-uni-text",{staticClass:"input"},[e._v(e._s(e.addressData.province_name)+" "+e._s(e.addressData.city_name)+" "+e._s(e.addressData.county_name))])],1):a("v-uni-view",{staticClass:"row comonLine",attrs:{required:!0}},[a("v-uni-text",{staticClass:"tit"},[e._v("所在地区")]),a("v-uni-view",{staticClass:"input"},[a("v-uni-picker",{attrs:{mode:"multiSelector",value:e.multiIndex,range:e.multiArray},on:{change:function(t){arguments[0]=t=e.$handleEvent(t),e.bindPickerChange.apply(void 0,arguments)},columnchange:function(t){arguments[0]=t=e.$handleEvent(t),e.bindMultiPickerColumnChange.apply(void 0,arguments)}}},[a("v-uni-view",{staticClass:"uni-input"},[e._v(e._s(e.selectArea[0]["name"])+"　"+e._s(e.selectArea[1]["name"])+"　"+e._s(e.selectArea[2]["name"]))])],1)],1)],1),a("v-uni-view",{staticClass:"row comonLine",attrs:{required:!0}},[a("v-uni-text",{staticClass:"tit"},[e._v("详细地址")]),a("v-uni-input",{staticClass:"input",attrs:{type:"text",placeholder:"楼号、门牌","placeholder-class":"placeholder"},model:{value:e.addressData.address,callback:function(t){e.$set(e.addressData,"address",t)},expression:"addressData.address"}})],1),a("v-uni-view",{staticClass:"row comonLine",attrs:{required:!0},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.chooseLocation.apply(void 0,arguments)}}},[a("v-uni-text",{staticClass:"tit"},[e._v("地理位置")]),null==e.user_location?a("v-uni-view",{staticClass:"tipMsg flex1"},[e._v("请选择地理位置")]):a("v-uni-view",{staticClass:"tipMsg flex1"},[e._v(e._s(e.user_location.address)+"  "+e._s(e.user_location.name))])],1)],1),e.isOrder?a("v-uni-view",{staticClass:"inv-tips"},[a("v-uni-text",{staticClass:"remark"},[e._v("备注：")]),a("v-uni-view",{staticClass:"flex1 tip"},[e._v("因订单已分配，仅支持修改部分信息，且仅允许修改一次")])],1):e._e(),1==e.addressData.status?a("v-uni-view",{staticClass:"tui-notice-board-extration"},[a("v-uni-view",{staticClass:"tui-icon-bg"},[a("tui-icon",{attrs:{name:"news-fill",size:24,color:"#f54f46"}})],1),a("v-uni-view",{staticClass:"tui-scorll-view"},[a("v-uni-view",[e._v("注意：编辑信息将重新审核后方可生效")])],1)],1):e._e(),e.isOrder?a("v-uni-button",{staticClass:"add-btn",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.confirmOrder.apply(void 0,arguments)}}},[e._v("提交")]):a("v-uni-button",{staticClass:"add-btn",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.confirm.apply(void 0,arguments)}}},[e._v("提交")])],1)},s=[]}}]);