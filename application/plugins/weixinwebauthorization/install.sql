# 用户
ALTER TABLE `{PREFIX}user` add `weixin_web_openid` char(60) NOT NULL DEFAULT '' COMMENT '微信web用户openid' after `weixin_openid`;