<p align="center">
  <img width="320" src="https://gw.alipayobjects.com/zos/rmsportal/CsCzHzlOkLDKyyRadsdD.png">
</p>

# mini-antui

## 链接
- [mini-antui官网文档](https://docs.alipay.com/mini/component-ext/overview-ext-common)
- [支付宝小程序](https://mini.open.alipay.com/channel/miniIndex.htm)
- [开发工具](https://docs.alipay.com/mini/ide/overview)

## 特性

- 基于`Advance Design`设计规范
- 使用[支付宝小程序](https://mini.open.alipay.com/channel/miniIndex.htm)开发

## 预览

用[小程序开发者工具](https://docs.alipay.com/mini/ide/overview)打开项目

## 安装

```bash
$ npm install mini-antui --save
```

## 使用

在页面json中文件中进行注册，如card组件的注册如下所示：

```json
{
  "usingComponents": {
    "card": "mini-antui/es/card/index",
  }
}
```

在axml文件中进行调用：
```html
<card
  thumb="{{thumb}}"
  title="卡片标题2"
  subTitle="副标题非必填2"
  onClick="onCardClick"
  info="点击了第二个card"
/>
```

## 贡献

如果你有好的意见或建议，欢迎给我们提[issue](https://github.com/ant-mini-program/mini-antui/issues)。
