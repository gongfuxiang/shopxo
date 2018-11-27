## 0.4.3

`2018-11-07`

- **Enhancement**
  - `tabs`新增`tabBarCls`tabBar自定义样式class
  - `tabs`新增`duration`控制滑动动画时长
  - `calendar`date参数兼容IOS格式要求

## 0.4.2

`2018-10-31`

- **Enhancement**
  - `amount-input`组件type属性新增`digit`类型
  - `vtabs`新增`activeTab`，`onTabClick`和`onChange`属性([#125](https://github.com/ant-mini-program/mini-antui/issues/125))

## 0.4.1

`2018-10-29`

- **Enhancement**
  - `notice`新增`enableMarquee`和`marqueeProps`属性([#140](https://github.com/ant-mini-program/mini-antui/issues/140))

- **Bug Fix**
  - 修复`message`type为`fail`时的白屏问题([#152](https://github.com/ant-mini-program/mini-antui/issues/152))

## 0.4.0

`2018-10-23`

- **Feature**
  - 新增`am-checkbox`组件
  - 新增`badge`组件

- **Enhancement**
  - `calendar`组件`tabs`属性新增`disable`字段，新增`onSelectHasDisableDat`属性([#108](https://github.com/ant-mini-program/mini-antui/issues/108))

- **Bug Fix**
  - 修复`vtabs`在安卓下出现滚动误差的问题
  - 修复`tabs`在`tabs`属性变化时没有重新计算宽度导致的滚动不正常问题

## 0.3.13

`2018-10-18`

- **Bug Fix**
  - 修复`swipe-action`在didUpdate时陷入死循环的问题
  - 修复`vtabs`tabs数据变化没有响应的问题

## 0.3.12

`2018-10-12`

- **Enhancement**
  - `vtabs`新增`badgeType`和`badgeText`属性([#92](https://github.com/ant-mini-program/mini-antui/issues/92))

## 0.3.11

`2018-10-10`

- **Bug Fix**
  - 修复`search-bar`在IPhone X下面出现滚动的问题([#113](https://github.com/ant-mini-program/mini-antui/issues/113))
  - 修复`stepper`在重置初始值时操作按钮状态不改变的bug([#111](https://github.com/ant-mini-program/mini-antui/issues/111))

- **Enhancement**
  - `page-result`图标升级到最新版本
  - `input-item`增大清除icon点击响应范围

## 0.3.10

`2018-10-08`

- **Enhancement**
  - 解决`list`，`input-item`在安卓下线条较粗的问题

## 0.3.9

`2018-09-27`

- **Bug Fix**
  - 修复`input-item`在失去焦点时清除按钮仍旧显示的问题

## 0.3.8

`2018-09-26`

- **Bug Fix**
  - 修复`filter`组件单选时需要反选取消选择的问题

- **Feature**
  - 新增`picker-item`组件

- **Enhancement**
  - `tabs`新增`activeCls`属性，用来表示激活tabbar的自定义class([#87](https://github.com/ant-mini-program/mini-antui/issues/87))
  - `input-item`新增`clear`、`onClear`属性，组件内支持清除输入功能([#84](https://github.com/ant-mini-program/mini-antui/issues/84))
  - `list-item` onClick回调新增target参数，用来支持自定义dataset([#85](https://github.com/ant-mini-program/mini-antui/issues/85))

## 0.3.7

`2018-09-25`

- **Bug Fix**
  - 修复了`input-item`组件在失去焦点等事件中无dataset的问题([#66](https://github.com/ant-mini-program/mini-antui/issues/66))
  - 修复`popup`组件mask定位为absolut导致的页面滚动时mask跟着滚动的bug

- **Enhancement**
  - `popup`新增disableScroll属性以适应不同业务场景
  - 完善`swipe-action`的示例代码
  - 文档更新，添加体验二维码

## 0.3.6

`2018-09-13`

- **Enhancement**
  - 新增tips组件的类型

## 0.3.5

`2018-08-29`

- **Bug Fix**
  - 修复`search-bar`点击icon无效的bug
  - 修复`search-bar`苹果输入法中间态无法清除placeholder的bug

- **Enhancement**
  - 优化`list`组件样式

## 0.3.4

`2018-08-16`

- **Enhancement**
  - 优化`tabs`组件闪烁问题
  - `face-detection`组件增加最小旋转角度属性

## 0.3.3

`2018-08-10`

- **Feature**
  - `tabs`组件新增`activeTab`属性，用来指定当前激活tab

## 0.3.2

`2018-08-07`

- **Feature**
  - 新增`popup`弹出菜单组件
  - `face-detection`组件新增活体检测功能

## 0.3.1

`2018-07-27`

- **Feature**
  - `face-detection`组件新增`appName`和`serviceName`字段

## 0.3.0

`2018-07-26`

- **Feature**
  - 新增`face-detection`组件
  - 新增`footer`组件
  - `page-result`组件增加slot，方便开发者个性化定制区域内容

- **Enhancement**
  - 优化`calendar`组件在初次渲染时的闪烁问题
  - 优化`swipe-action`右侧按钮宽度自适应文本内容


## 0.2.0

`2018-07-11`

- **Feature**

  - 新增`vtab组件`

- **Enhancement**

  - 优化`swipe-action`组件性能
  - 解决`tabs`组件在初次渲染时的页面闪烁问题

## 0.1.0

`2018-06-21`


- **Feature**

  - 新增`steps`、`popover`、`amount-input`、`calendar`组件；
  - `tabs`组件`tabs`属性新增`badgeType`属性、新增`showPlus`、`onPlusClick`属性
  - `modal`组件新增`closeType`属性，以适应不同的背景颜色

- **Bug Fix**

  - 修复`grid`、`modal`、`input-item`组件样式问题


## 0.0.13

`2018-05-09`

首次发布小程序版antui组件库
