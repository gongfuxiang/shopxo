-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 172.26.162.62:13306
-- 生成日期： 2024-06-09 16:44:50
-- 服务器版本： 11.4.2-MariaDB-ubu2404
-- PHP 版本： 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `shopxo`
--

-- --------------------------------------------------------

--
-- 表的结构 `sxo_admin`
--

DROP TABLE IF EXISTS `sxo_admin`;
CREATE TABLE `sxo_admin` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '管理员id',
  `username` char(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `login_pwd` char(32) NOT NULL DEFAULT '' COMMENT '登录密码',
  `login_salt` char(6) NOT NULL DEFAULT '' COMMENT '登录密码配合加密字符串',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` char(60) NOT NULL DEFAULT '' COMMENT '邮箱',
  `gender` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '性别（0保密，1女，2男）',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态（0正常, 1无效）',
  `login_total` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录次数',
  `login_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所属角色组',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='管理员';

--
-- 转存表中的数据 `sxo_admin`
--

INSERT INTO `sxo_admin` (`id`, `username`, `login_pwd`, `login_salt`, `mobile`, `email`, `gender`, `status`, `login_total`, `login_time`, `role_id`, `add_time`, `upd_time`) VALUES
(1, 'admin', '659ea4882247e1da66804404bf4b7b30', '100396', '', 'xxx@email.com', 0, 0, 1888, 1717946565, 1, 1481350313, 1659361312);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_app_center_nav`
--

DROP TABLE IF EXISTS `sxo_app_center_nav`;
CREATE TABLE `sxo_app_center_nav` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `platform` char(255) NOT NULL DEFAULT 'pc' COMMENT '所属平台（pc PC网站, h5 H5手机网站, ios 苹果APP, android 安卓APP, alipay 支付宝小程序, weixin 微信小程序, baidu 百度小程序, toutiao 头条小程序, qq QQ小程序, kuaishou 快手小程序）',
  `event_type` tinyint(4) NOT NULL DEFAULT -1 COMMENT '事件类型（0 WEB页面, 1 内部页面(小程序或APP内部地址), 2 外部小程序(同一个主体下的小程序appid), 3 打开地图, 4 拨打电话）',
  `event_value` char(255) NOT NULL DEFAULT '' COMMENT '事件值',
  `images_url` char(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `desc` char(18) NOT NULL DEFAULT '' COMMENT '描述',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `is_need_login` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否需要登录（0否，1是）',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='手机 - 用户中心导航';

--
-- 转存表中的数据 `sxo_app_center_nav`
--

INSERT INTO `sxo_app_center_nav` (`id`, `platform`, `event_type`, `event_value`, `images_url`, `name`, `desc`, `is_enable`, `is_need_login`, `sort`, `add_time`, `upd_time`) VALUES
(1, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/user-order/user-order?id=100', '/static/upload/images/app_center_nav/2019/11/05/1572932149956815.png', '我的订单', '', 1, 0, 0, 1562159178, 1690035019),
(2, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/user-favor/user-favor', '/static/upload/images/app_center_nav/2019/07/03/1562157390405145.png', '我的收藏', '', 1, 1, 0, 1562155833, 1562157399),
(3, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/user-address/user-address', '/static/upload/images/app_center_nav/2019/07/03/1562157391533252.png', '我的地址', '', 1, 1, 0, 1562155871, 1562157408),
(4, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/distribution/user/user', '/static/upload/images/app_center_nav/2019/07/03/1562157391517979.png', '我的分销', '分享赚取佣金', 1, 1, 0, 1562155901, 1678933092),
(5, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/membershiplevelvip/user/user', '/static/upload/images/app_center_nav/2020/02/01/1580558516351420.png', '我的会员', '', 1, 1, 0, 1562159178, 1592661221),
(6, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/wallet/user/user', '/static/upload/images/app_center_nav/2020/02/01/1580558490671574.png', '我的钱包', '', 1, 1, 0, 1562159178, 1580558500),
(7, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/coupon/user/user', '/static/upload/images/app_center_nav/2019/10/16/1571231187362091.png', '我的卡券', '', 1, 1, 0, 1562159178, 1562157435),
(8, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/ask/user-list/user-list', '/static/upload/images/app_center_nav/2019/07/03/1562157391428293.png', '我的留言', '', 1, 1, 0, 1562156178, 1562157435),
(41, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/excellentbuyreturntocash/profit/profit', '/static/upload/images/app_center_nav/2020/02/01/1580558490671574.png', '优购返现', '', 1, 0, 0, 1583745264, 0),
(42, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/invoice/invoice/invoice', '/static/upload/images/app_center_nav/2020/12/08/1607398361522502.png', '我的发票', '', 1, 0, 0, 1607398368, 0),
(47, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/signin/user/user', '/static/upload/images/app_center_nav/2020/12/22/1608608498784252.png', '我的签到', '', 1, 0, 0, 1607398368, 1608608501);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_app_home_nav`
--

DROP TABLE IF EXISTS `sxo_app_home_nav`;
CREATE TABLE `sxo_app_home_nav` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `platform` char(255) NOT NULL DEFAULT 'pc' COMMENT '所属平台（pc PC网站, h5 H5手机网站, ios 苹果APP, android 安卓APP, alipay 支付宝小程序, weixin 微信小程序, baidu 百度小程序, toutiao 头条小程序, qq QQ小程序, kuaishou 快手小程序）',
  `event_type` tinyint(4) NOT NULL DEFAULT -1 COMMENT '事件类型（0 WEB页面, 1 内部页面(小程序或APP内部地址), 2 外部小程序(同一个主体下的小程序appid), 3 打开地图, 4 拨打电话）',
  `event_value` char(255) NOT NULL DEFAULT '' COMMENT '事件值',
  `images_url` char(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `is_need_login` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否需要登录（0否，1是）',
  `bg_color` char(30) NOT NULL DEFAULT '' COMMENT 'css背景色值',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='手机 - 首页导航';

--
-- 转存表中的数据 `sxo_app_home_nav`
--

INSERT INTO `sxo_app_home_nav` (`id`, `platform`, `event_type`, `event_value`, `images_url`, `name`, `is_enable`, `is_need_login`, `bg_color`, `sort`, `add_time`, `upd_time`) VALUES
(1, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/activity/index/index', '/static/upload/images/app_nav/2023/11/08/1699444440845257.png', '活动', 1, 0, '', 0, 1542563498, 1699444475),
(2, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/coupon/index/index', '/static/upload/images/app_nav/2023/11/08/1699444440307617.png', '优惠券', 1, 0, '', 0, 1542613659, 1699444488),
(3, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/blog/index/index', '/static/upload/images/app_nav/2023/11/08/1699444440587695.png', '博文', 1, 0, '', 0, 1542613706, 1699444513),
(4, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/weixinliveplayer/index/index', '/static/upload/images/app_nav/2023/11/08/1699444441652354.png', '直播', 1, 0, '', 0, 1542613752, 1699444526),
(5, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/seckill/index/index', '/static/upload/images/app_nav/2023/11/08/1699444440276337.png', '秒杀', 1, 0, '', 0, 1635418404, 1699444537),
(6, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/signin/detail/detail?id=1', '/static/upload/images/app_nav/2023/11/08/1699444440767151.png', '签到', 1, 0, '', 0, 1678930855, 1699444549),
(7, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/membershiplevelvip/index/index', '/static/upload/images/app_nav/2023/11/08/1699444440657693.png', '会员', 1, 0, '', 0, 1693301691, 1699444560),
(8, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/ask/index/index', '/static/upload/images/app_nav/2023/11/08/1699444440189681.png', '问答', 1, 0, '', 0, 1693301721, 1699444583),
(9, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/points/index/index', '/static/upload/images/app_nav/2023/11/08/1699444440178265.png', '积分', 1, 0, '', 0, 1693301785, 1699444597),
(10, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/brand/index/index', '/static/upload/images/app_nav/2023/11/08/1699444440389285.png', '品牌', 1, 0, '', 0, 1693301825, 1699444634),
(11, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/shop/index/index', '/static/upload/images/app_nav/2023/11/08/1699444440279257.png', '店铺', 1, 0, '', 0, 1693301855, 1699445033),
(12, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/realstore/index/index', '/static/upload/images/app_nav/2023/11/08/1699444440279257.png', '门店', 1, 0, '', 0, 1693301883, 1699444653),
(13, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/plugins/binding/index/index', '/static/upload/images/app_nav/2023/11/08/1699444441697516.png', '组合', 1, 0, '', 0, 1693301911, 1699445004);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_article`
--

DROP TABLE IF EXISTS `sxo_article`;
CREATE TABLE `sxo_article` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `title` char(60) NOT NULL DEFAULT '' COMMENT '标题',
  `describe` char(255) NOT NULL DEFAULT '' COMMENT '描述',
  `article_category_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文章分类',
  `title_color` char(200) NOT NULL DEFAULT '' COMMENT '标题颜色',
  `jump_url` char(255) NOT NULL DEFAULT '' COMMENT '跳转url地址',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `content` longtext DEFAULT NULL COMMENT '内容',
  `cover` char(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `images` text DEFAULT NULL COMMENT '图片数据（一维数组json）',
  `images_count` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片数量',
  `access_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '访问次数',
  `is_home_recommended` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否首页推荐（0否, 1是）',
  `seo_title` char(100) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` char(130) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_desc` char(230) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文章';

--
-- 转存表中的数据 `sxo_article`
--

INSERT INTO `sxo_article` (`id`, `title`, `describe`, `article_category_id`, `title_color`, `jump_url`, `is_enable`, `content`, `cover`, `images`, `images_count`, `access_count`, `is_home_recommended`, `seo_title`, `seo_keywords`, `seo_desc`, `add_time`, `upd_time`) VALUES
(1, '如何注册成为会员', '', 7, '', '', 1, '<p>如何注册成为会员</p><p>如何注册成为会员</p><p>如何注册成为会员</p><p>如何注册成为会员</p>', '', '[]', 0, 267, 1, '', '', '', 1484965691, 1534228456),
(3, '积分细则', '', 7, '#FF0000', '', 1, '<p>积分细则</p><p>积分细则</p><p>积分细则</p><p>积分细则</p><p>积分细则</p><p>积分细则</p><p>积分细则</p>', '', '[]', 0, 111, 1, '', '', '', 1484985139, 1534228496),
(4, '积分兑换说明', '', 17, '', '', 1, '<p>积分兑换说明</p><p>积分兑换说明</p><p>积分兑换说明</p><p>积分兑换说明</p><p>积分兑换说明</p><p>积分兑换说明</p>', '', '[]', 0, 55, 1, '', '', '', 1484989903, 1534228520),
(5, '如何搜索', '', 7, '', '', 1, '<p>如何搜索</p><p>如何搜索</p><p>如何搜索</p><p>如何搜索</p><p>如何搜索</p><p>如何搜索</p><p>如何搜索</p>', '', '[]', 0, 98, 1, '', '', '', 1485064767, 1534228544),
(6, '忘记密码', '', 17, '', '', 1, '<p>忘记密码</p><p>忘记密码</p><p>忘记密码</p><p>忘记密码</p><p>忘记密码</p>', '', '[]', 0, 30, 1, '', '', '', 1485073500, 1534228567),
(7, '如何管理店铺', '', 10, '', '', 1, '<p>如何管理店铺</p><p>如何管理店铺</p><p>如何管理店铺</p><p>如何管理店铺</p><p>如何管理店铺</p><p>如何管理店铺</p>', '', '[]', 0, 86, 1, '', '', '', 1487819252, 1534228589),
(8, '查看售出商品', '', 10, '', '', 1, '<p>查看售出商品</p><p>查看售出商品</p><p>查看售出商品</p><p>查看售出商品</p><p>查看售出商品</p>', '', '[]', 0, 64, 1, '', '', '', 1487819408, 1534228614),
(9, '如何发货', '', 10, '#CC0066', '', 1, '<p>如何发货</p><p>如何发货</p><p>如何发货</p><p>如何发货</p><p>如何发货</p>', '', '', 0, 49, 1, '', '', '', 1487920130, 1545500851),
(10, '商城商品推荐', '', 10, '', '', 1, '<p>商城商品推荐</p><p>商城商品推荐</p><p>商城商品推荐</p><p>商城商品推荐</p><p>商城商品推荐</p>', '', '[]', 0, 24, 1, '', '', '', 1534228650, 1534228650),
(11, '如何申请开店', '', 10, '', '', 1, '<p>如何申请开店</p><p>如何申请开店</p><p>如何申请开店</p><p>如何申请开店</p>', '', '[]', 0, 15, 1, '', '', '', 1534228676, 1534228676),
(12, '分期付款', '', 16, '', '', 1, '<p>分期付款</p><p>分期付款</p><p>分期付款</p><p>分期付款</p><p>分期付款</p>', '', '[]', 0, 48, 1, '', '', '', 1534228694, 1534228694),
(13, '邮局汇款', '', 16, '', '', 1, '<p>邮局汇款</p><p>邮局汇款</p><p>邮局汇款</p><p>邮局汇款</p><p>邮局汇款</p>', '', '[]', 0, 46, 1, '', '', '', 1534228710, 1534228710),
(14, '公司转账', '', 16, '', '', 1, '<p>公司转账</p><p>公司转账</p><p>公司转账</p><p>公司转账</p><p>公司转账</p>', '', '[]', 0, 48, 1, '', '', '', 1534228732, 1534228732),
(15, '如何注册支付宝', '', 16, '', '', 1, '<p>如何注册支付宝</p><p>如何注册支付宝</p><p>如何注册支付宝</p><p>如何注册支付宝</p><p>如何注册支付宝</p>', '', '[]', 0, 35, 1, '', '', '', 1534228748, 1534228748),
(16, '在线支付', '', 16, '', '', 1, '<p>在线支付</p><p>在线支付</p><p>在线支付</p><p>在线支付</p><p>在线支付</p>', '', '[]', 0, 41, 1, '', '', '', 1534228764, 1534228764),
(17, '联系卖家', '', 17, '', '', 1, '<p>联系卖家</p><p>联系卖家</p><p>联系卖家</p><p>联系卖家</p><p>联系卖家</p><p>联系卖家</p>', '', '[]', 0, 11, 1, '', '', '', 1534228781, 1534228781),
(18, '退换货政策', '', 17, '', '', 1, '<p>退换货政策</p><p>退换货政策</p><p>退换货政策</p><p>退换货政策</p><p>退换货政策</p>', '', '[]', 0, 6, 1, '', '', '', 1534228802, 1534228802),
(19, '退换货流程', '', 17, '', '', 1, '<p>退换货流程</p><p>退换货流程</p><p>退换货流程</p><p>退换货流程</p><p>退换货流程</p>', '', '[]', 0, 3, 1, '', '', '', 1534228850, 1534228850),
(20, '返修/退换货', '', 17, '', '', 1, '<p>返修/退换货</p><p>返修/退换货</p><p>返修/退换货</p><p>返修/退换货</p><p>返修/退换货</p>', '', '[]', 0, 37, 1, '', '', '', 1534228867, 1534228867),
(21, '退款申请', '', 17, '', '', 1, '<p>退款申请</p><p>退款申请</p><p>退款申请</p><p>退款申请</p><p>退款申请</p>', '', '[]', 0, 66, 1, '', '', '', 1534228885, 1534228885),
(22, '会员修改密码', '', 18, '', '', 1, '<p>会员修改密码</p><p>会员修改密码</p><p>会员修改密码</p><p>会员修改密码</p>', '', '[]', 0, 78, 1, '', '', '', 1534228900, 1534228900),
(23, '会员修改个人资料', '', 18, '', '', 1, '<p>会员修改个人资料</p><p>会员修改个人资料</p><p>会员修改个人资料</p><p>会员修改个人资料</p><p>会员修改个人资料</p>', '', '[]', 0, 75, 1, '', '', '', 1534228916, 1534228916),
(24, '商品发布', '', 18, '', '', 1, '<p>商品发布</p><p>商品发布</p><p>商品发布</p><p>商品发布</p><p>商品发布</p>', '', '[]', 0, 73, 1, '', '', '', 1534228931, 1534228931),
(25, '修改收货地址', '', 18, '', '', 1, '<p>修改收货地址</p><p>修改收货地址</p><p>修改收货地址</p><p>修改收货地址</p><p>修改收货地址</p>', '', '[]', 0, 68, 1, '', '', '', 1534228948, 1534228948),
(26, '合作及洽谈', '', 24, '', '', 1, '<p>合作及洽谈</p><p>合作及洽谈</p><p>合作及洽谈</p><p>合作及洽谈</p><p>合作及洽谈</p>', '', '[]', 0, 146, 1, '', '', '', 1534228968, 1534228968),
(27, '招聘英才', '', 24, '', '', 1, '<h2 style=\"text-align: center;\"><strong style=\"text-align: center; white-space: normal;\"><span style=\"color: rgb(255, 0, 0); font-size: 36px;\">演示站点、请勿下单支付。</span></strong></h2><h2>PHP工程师</h2><p>岗位描述：</p><p>1.负责项目后端系统的研发和维护工作。</p><p>2.负责跟进平台的运营监控和数据分析工作。</p><p>3.按时保质保量完成项目开发,研究新兴技术，持续优化系统架构，完善基础服务。<br/></p><p>4.思维敏捷,责任心强,能承受工作压力。</p><p><br/></p><p>任职资格：</p><p>1、本科及以上学历，计算机相关专业，3年以上相关开发工作经验。</p><p>2、精通基于LNMP的Web开发技术, 熟悉yii, yaf, ThinkPHP, zend等框架的是用及实现原理。</p><p>3、熟悉mysql、redis等应用开发，精通SQL调优和数据结构设计。</p><p>4、熟悉使用Javascript、Ajax，Html，Div+CSS，Vue等技术。</p><p>5、有大型项目开发经验，系统调优经验者优先。</p><p>6、对LNMP/LAMP架构的部署、搭建、优化、排错等方面有经验者优先。</p><p>7、事业心强，勤奋好学，有团队精神。</p><p><br/></p><h2>前端工程师</h2><p>岗位描述：</p><p>1.配合项目经理和设计师快速实现一流的前端界面，优化代码并保持良好的兼容性，改善用户体验。</p><p>2.根据业务和项目需求，进行技术创新，分析并给出最优的前台技术实现方案。</p><p>3.对前端开发的新技术有敏锐嗅觉，推进前端技术演进。</p><p>4.进行新技术调研，持续对产品前端进行维护和升级。</p><p><br/></p><p>任职资格：</p><p>1、了解Web 标准，熟悉 HTML、CSS、JavaScript 各种前端技术。</p><p>2、熟悉 HTTP 协议。</p><p>3、认真负责，积极主动，有良好的团队合作意识。</p><p>4、了解 Angularjs，前端工程化或者 Node.js 等技术有研究。</p><p>5、有Vue开发经验者优先。</p><p>6、事业心强，勤奋好学，有团队精神。</p><p><br/></p>', '', '', 0, 273, 1, '', '', '', 1534228987, 1627540318),
(28, '联系我们', '', 24, '', '', 1, '<p style=\"padding: 5px; margin-top: 0px; margin-bottom: 0px; clear: both; color: rgb(102, 102, 102); text-align: center;\"><strong style=\"text-align: center; white-space: normal;\"><span style=\"color: rgb(255, 0, 0); font-size: 36px;\">演示站点、请勿下单支付。</span></strong></p><p style=\"padding: 5px; margin-top: 0px; margin-bottom: 0px; clear: both; color: rgb(102, 102, 102); font-family: \">欢迎您对我们的站点、工作、产品和服务提出自己宝贵的意见或建议。我们将给予您及时答复。同时也欢迎您到我们公司来洽商业务。</p><p style=\"padding: 5px; margin-top: 0px; margin-bottom: 0px; clear: both; color: rgb(102, 102, 102); font-family: \"><br/><strong style=\"font-size: 1em;\">公司名称</strong>： ShopXO</p><p style=\"padding: 5px; margin-top: 0px; margin-bottom: 0px; clear: both; color: rgb(102, 102, 102); font-family: \"><strong style=\"font-size: 1em;\">通信地址</strong>： 上海市xxxx 号</p><p style=\"padding: 5px; margin-top: 0px; margin-bottom: 0px; clear: both; color: rgb(102, 102, 102); font-family: \"><strong style=\"font-size: 1em;\">商务洽谈</strong>： 176-8888-8888</p><table class=\"am-table am-table-bordered\"><tbody><tr class=\"firstRow\"><td width=\"301\" valign=\"top\" style=\"word-break: break-all;\"><strong>标题表格演功能示</strong></td><td width=\"301\" valign=\"top\" style=\"word-break: break-all;\"><strong>价格(元)</strong></td><td width=\"301\" valign=\"top\" style=\"word-break: break-all;\"><strong>状态</strong></td></tr><tr><td width=\"301\" valign=\"top\" style=\"word-break: break-all;\">商品名称</td><td width=\"301\" valign=\"top\" style=\"word-break: break-all;\"><span style=\"color: rgb(227, 108, 9);\">￥100.00</span></td><td width=\"301\" valign=\"top\" style=\"word-break: break-all;\"><span style=\"color: rgb(0, 176, 80);\">有效</span></td></tr></tbody></table>', '', '', 0, 311, 1, '联系我们文章详情ShopXO', '联系我们,ShopXO开源商城', '国内领先企业级B2C开源电商系统！', 1534229110, 1657353466),
(29, '关于ShopXO', '', 24, '#FF0000', '', 1, '<p style=\"text-align: center;\"><strong><span style=\"color: rgb(255, 0, 0); font-size: 36px;\">演示站点、请勿下单支付。</span></strong></p><p>ShopXO位于上海市浦东新区，是专业从事生产管理信息化领域技术咨询和软件开发的高新技术企业。公司拥有多名技术人才和资深的行业解决方案专家。</p><p><br/></p><p>公司拥有一支勇于开拓、具有战略眼光和敏锐市场判断力的市场营销队伍，一批求实敬业，追求卓越的行政管理人才，一个能征善战，技术优秀，经验丰富的开发团队。公司坚持按现代企业制度和市场规律办事，在扩大经营规模的同时，注重企业经济运行质量，在自主产品研发及承接软件项目方面获得了很强的竞争力。 我公司也积极参与国内传统企业的信息化改造，引进国际化产品开发的标准，规范软件开发流程，通过提升各层面的软件开发人才的技术素质，打造国产软件精品，目前已经开发出具有自主知识产权的网络商城软件，还在积极开发基于电子商务平台高效能、高效益的管理系统。为今后进一步开拓国内市场打下坚实的基础。公司致力于构造一个开放、发展的人才平台，积极营造追求卓越、积极奉献的工作氛围，把“以人为本”的理念落实到每一项具体工作中，为那些锋芒内敛，激情无限的业界精英提供充分的发展空间，优雅自信、从容自得的工作环境，事业雄心与生活情趣两相兼顾的生活方式。并通过每个员工不断提升自我，以自己的独特价值观对工作与生活作最准确的判断，使我们每一个员工彰显出他们出色的自我品位，独有的工作个性和卓越的创新风格，让他们时刻保持振奋、不断鼓舞内心深处的梦想，永远走在时代潮流前端。公司发展趋势 励精图治，展望未来。公司把发展产业策略与发掘人才策略紧密结合，广纳社会精英，挖掘创新潜能，以人为本，凝聚人气，努力营造和谐宽松的工作氛围，为优秀人才的脱颖而出提供机遇。公司将在深入发展软件产业的同时，通过不懈的努力，来塑造大型软件公司的辉煌形象。</p>', '', '', 0, 134, 1, '1', '2', '333', 1534229221, 1690035157);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_article_category`
--

DROP TABLE IF EXISTS `sxo_article_category`;
CREATE TABLE `sxo_article_category` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '分类id',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id',
  `name` char(30) NOT NULL COMMENT '名称',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '顺序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文章分类';

--
-- 转存表中的数据 `sxo_article_category`
--

INSERT INTO `sxo_article_category` (`id`, `pid`, `name`, `is_enable`, `sort`, `add_time`, `upd_time`) VALUES
(7, 0, '帮助中心', 1, 0, 0, 1545501262),
(10, 0, '店主之家', 1, 0, 0, 1607494981),
(16, 0, '支付方式', 1, 0, 1482840545, 1534228311),
(17, 0, '售后服务', 1, 0, 1482840557, 1605774851),
(18, 0, '客服中心', 1, 0, 1482840577, 1690035171),
(24, 0, '关于我们', 1, 0, 1483951541, 1690020232);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_attachment`
--

DROP TABLE IF EXISTS `sxo_attachment`;
CREATE TABLE `sxo_attachment` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `title` char(160) NOT NULL DEFAULT '' COMMENT '名称',
  `original` char(160) NOT NULL DEFAULT '' COMMENT '原始名称',
  `path_type` char(80) NOT NULL DEFAULT '' COMMENT '路径标记',
  `size` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '大小（单位b）',
  `ext` char(30) NOT NULL DEFAULT '' COMMENT '类型（后缀名）',
  `type` char(30) NOT NULL DEFAULT '' COMMENT '类型（file文件, image图片, scrawl涂鸦, video视频, remote远程抓取文件）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT 'url路径',
  `hash` text DEFAULT NULL COMMENT 'hash值',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='附件';

--
-- 转存表中的数据 `sxo_attachment`
--

INSERT INTO `sxo_attachment` (`id`, `title`, `original`, `path_type`, `size`, `ext`, `type`, `url`, `hash`, `add_time`) VALUES
(6, '1551064260180298.jpeg', '1551064260180298.jpeg', 'brand', 2393, '.jpeg', 'image', '/static/upload/images/brand/2019/02/25/1551064260180298.jpeg', '858f2f5aa01d2cecdc907b93a99765b8dccc6a8d1015a0ab048ad2a46c96e65e', 1561538565),
(7, '1551064277207182.jpeg', '1551064277207182.jpeg', 'brand', 3063, '.jpeg', 'image', '/static/upload/images/brand/2019/02/25/1551064277207182.jpeg', 'b1a4f186af72f1a2bb6cb425c5eff59c10fe11289a91944f016b0156021e55e6', 1561538565),
(8, '1547448705165706.png', '1547448705165706.png', 'common', 8725, '.png', 'image', '/static/upload/images/common/2019/01/14/1547448705165706.png', '3e4a6cf6ea3ed26836a59275cdbe58d441109717dd9b961bd912cbc5f336c556', 1561538565),
(9, '1547448728921121.jpg', '1547448728921121.jpg', 'common', 43556, '.jpg', 'image', '/static/upload/images/common/2019/01/14/1547448728921121.jpg', '829a7f9988db882fbd643ffdbd9a646e169b31f89641b3d4904338758016c2b4', 1561538565),
(10, '1547448748316693.png', '1547448748316693.png', 'common', 21102, '.png', 'image', '/static/upload/images/common/2019/01/14/1547448748316693.png', '2db8ea9424e63d4a2affcd0ac52f8f0e89c417f5725833a97d5afd534809d9b4', 1561538565),
(11, '1554805439263794.jpeg', '1554805439263794.jpeg', 'common', 19885, '.jpeg', 'image', '/static/upload/images/common/2019/04/09/1554805439263794.jpeg', 'f4b731035bac0627508caa101e1f537d25592c5201455647d2f1d1c5b7c3e3c4', 1561538565),
(12, '1558025141249118.png', '1558025141249118.png', 'common', 146965, '.png', 'image', '/static/upload/images/common/2019/05/17/1558025141249118.png', '316b56dec84b3c8dcc01b7672c6dad6eff88a518495f6cf81ccf45e58306bf58', 1561538565),
(13, '1558062481107570.jpg', '1558062481107570.jpg', 'common', 67729, '.jpg', 'image', '/static/upload/images/common/2019/05/17/1558062481107570.jpg', '66feea05a2fb10cb5983f4df2deeec6a35b5453876c14b7fabe62dbb3528d93c', 1561538565),
(14, '1558072588843696.jpg', '1558072588843696.jpg', 'common', 61648, '.jpg', 'image', '/static/upload/images/common/2019/05/17/1558072588843696.jpg', '18739da62d47b0062eef55bff5464809ac18bbcf500c18e5e130661ff40ad223', 1561538565),
(15, '1558073623385520.jpg', '1558073623385520.jpg', 'common', 83272, '.jpg', 'image', '/static/upload/images/common/2019/05/17/1558073623385520.jpg', '49acf5646f5ea6d7daf439fbea4cc9421d2d40f5979203a554e36a1da462ce7f', 1561538565),
(16, '1558073623641199.jpg', '1558073623641199.jpg', 'common', 76591, '.jpg', 'image', '/static/upload/images/common/2019/05/17/1558073623641199.jpg', 'ffdf93c767c65745d2e0cd1694548f689f6f0b9108c5b6d19f499f2bbe7e6417', 1561538565),
(17, '1533779966550231.jpeg', '1533779966550231.jpeg', 'customview', 23584, '.jpeg', 'image', '/static/upload/images/customview/image/2018/08/09/1533779966550231.jpeg', '2aa35a2f5037edba292bee25bc693c30422e969a13ce9d2228224efa7cfc9175', 1561538565),
(18, '20180917104528_logo.png', '20180917104528_logo.png', 'express', 1769, '.png', 'image', '/static/upload/images/express/images/20180917104528_logo.png', 'c3d6d1a36d5f330f94c6135621ccb6d6f9b88ccb5f051aa3b6afc8142e3675b2', 1561538565),
(19, '20180917104538_logo.png', '20180917104538_logo.png', 'express', 2169, '.png', 'image', '/static/upload/images/express/images/20180917104538_logo.png', 'e4ec0945e81303dd2a4da37c355e27d93bd3f1d182f3aedb8fcd806072b2351c', 1561538565),
(20, '20180917104550_logo.png', '20180917104550_logo.png', 'express', 3658, '.png', 'image', '/static/upload/images/express/images/20180917104550_logo.png', '6fa6d28c69cf0a9c6d9c1534746927b2e0d9e474bdcf3f3c1abca90e3cb36972', 1561538565),
(21, '20180917104559_logo.png', '20180917104559_logo.png', 'express', 2250, '.png', 'image', '/static/upload/images/express/images/20180917104559_logo.png', 'd3c077bfe057759182dc820f537a1e119141de61eaf8d2f3caa3830d18868278', 1561538565),
(22, '20180917104616_logo.png', '20180917104616_logo.png', 'express', 2880, '.png', 'image', '/static/upload/images/express/images/20180917104616_logo.png', 'cfa30c53c4f756c48867c0259832ac614aa2f7185c222c4aafc30501c3e6fd1c', 1561538565),
(23, '20180917104631_logo.png', '20180917104631_logo.png', 'express', 2570, '.png', 'image', '/static/upload/images/express/images/20180917104631_logo.png', 'a19bdf1d82804272e70a4203327fc8be94ca95b057e4f08cae0a595629c12164', 1561538565),
(24, '20180917104650_logo.png', '20180917104650_logo.png', 'express', 2919, '.png', 'image', '/static/upload/images/express/images/20180917104650_logo.png', '3ffdacaef8dd508ea1f6638a31fe5cd29312797560bd10fea189bb762255fcb2', 1561538565),
(25, '20180917104707_logo.png', '20180917104707_logo.png', 'express', 1933, '.png', 'image', '/static/upload/images/express/images/20180917104707_logo.png', 'af8093064a780329b3e40e07de199910cd7faa956534b5a897f0bf59b94f4db2', 1561538565),
(26, '20180917104722_logo.png', '20180917104722_logo.png', 'express', 1355, '.png', 'image', '/static/upload/images/express/images/20180917104722_logo.png', '577a12272983bf1d3a951466f9cd431da8f97f0663d668ac5976e15cf0d06130', 1561538565),
(27, '20180917104750_logo.png', '20180917104750_logo.png', 'express', 2232, '.png', 'image', '/static/upload/images/express/images/20180917104750_logo.png', '1f5ed908b5d8b906886503929c0fa718cc963e5ffa90a4efefa5942c5d208f32', 1561538565),
(28, '20180917104757_logo.png', '20180917104757_logo.png', 'express', 1459, '.png', 'image', '/static/upload/images/express/images/20180917104757_logo.png', 'c6379303232e7a785aa5d936235e1332d9f855dc96bf7e4c5fd7303f383a91fe', 1561538565),
(29, '20180917104816_logo.png', '20180917104816_logo.png', 'express', 3380, '.png', 'image', '/static/upload/images/express/images/20180917104816_logo.png', '6eed95dfa6bfa4c5df63674377375f87461c7b89a6e20de6485d4eb810990905', 1561538565),
(30, '20180917104839_logo.png', '20180917104839_logo.png', 'express', 2881, '.png', 'image', '/static/upload/images/express/images/20180917104839_logo.png', '73819350d051a9b0954f46e4d110f5a4f071a34d7c3e3a90117e8e233b756cee', 1561538565),
(31, '20180917104848_logo.png', '20180917104848_logo.png', 'express', 1582, '.png', 'image', '/static/upload/images/express/images/20180917104848_logo.png', 'f937af6d4fbba40a4ec0ac51ec43573cb8271da6e708f71fa3d4fd6de9b0d82e', 1561538565),
(109, '2018112015245128143.jpeg', '2018112015245128143.jpeg', 'goods_category', 31282, '.jpeg', 'image', '/static/upload/images/goods_category/2018/11/20/2018112015245128143.jpeg', '12caf7a29697c489af832d1083120cca3d7266cd3859709836c5034db0c48143', 1561538565),
(110, '2018112015252193663.jpeg', '2018112015252193663.jpeg', 'goods_category', 24188, '.jpeg', 'image', '/static/upload/images/goods_category/2018/11/20/2018112015252193663.jpeg', 'a11205863c0246630200dc4a4170370a1e715c18c4dbdd20090e12586d78c288', 1561538565),
(111, '2018112015255390903.jpeg', '2018112015255390903.jpeg', 'goods_category', 28249, '.jpeg', 'image', '/static/upload/images/goods_category/2018/11/20/2018112015255390903.jpeg', 'df72b0fabda2e3e957c563a375489deb7958fb449dc2b5cb41d1400ad50d6c94', 1561538565),
(112, '2018112015273175122.jpeg', '2018112015273175122.jpeg', 'goods_category', 15581, '.jpeg', 'image', '/static/upload/images/goods_category/2018/11/20/2018112015273175122.jpeg', '83381549505c2af2b2d5a5b16226823203a8f7fc309f4efd6c631fa56f4baed4', 1561538565),
(113, '2018112015441996472.jpeg', '2018112015441996472.jpeg', 'goods_category', 23870, '.jpeg', 'image', '/static/upload/images/goods_category/2018/11/20/2018112015441996472.jpeg', '9ee98d2fcdc54627346522f8ec04f95adb4f93792bea8132f2cf8574ed0bcea7', 1561538565),
(123, '1562157390405145.png', '-收藏.png', 'app_center_nav', 4485, '.png', 'image', '/static/upload/images/app_center_nav/2019/07/03/1562157390405145.png', 'f2c623efc421e9a0a68edc118b57ec73108273677dfd469e93b77a8ca33070b8', 1562157390),
(124, '1562157391428293.png', '问答-蓝.png', 'app_center_nav', 2033, '.png', 'image', '/static/upload/images/app_center_nav/2019/07/03/1562157391428293.png', 'e31e67a0765688899e4dcd75f446220091f766fdd65d4ba744aa38f1094ddf16', 1562157391),
(125, '1562157391533252.png', '我的地址.png', 'app_center_nav', 4557, '.png', 'image', '/static/upload/images/app_center_nav/2019/07/03/1562157391533252.png', 'bf7baeff6f58676ae31afc579b1b904ed6f747101c84d883134789369a94db59', 1562157391),
(127, '1562157391517979.png', '小折-分销.png', 'app_center_nav', 3468, '.png', 'image', '/static/upload/images/app_center_nav/2019/07/03/1562157391517979.png', '2811fe5fe831b8d65b9c3741c8ea1fffbfc4b2d80810abbba27fc3b42b4c75fb', 1562157391),
(246, '1571231187362091.png', '优惠劵.png', 'app_center_nav', 3472, '.png', 'image', '/static/upload/images/app_center_nav/2019/10/16/1571231187362091.png', '775b2ebf349a94fddb90fd6b7f9b9bc5ed4a7099d5e676bef71c2f9aa3e03178', 1571231187),
(249, '1572932149956815.png', '我的订单.png', 'app_center_nav', 3097, '.png', 'image', '/static/upload/images/app_center_nav/2019/11/05/1572932149956815.png', 'c671163407ddc9bcf04d7ead288aa319f16a92b0e9f486b8072d8f3213da33b5', 1572932150),
(299, '1580558490671574.png', '钱包.png', 'app_center_nav', 2953, '.png', 'image', '/static/upload/images/app_center_nav/2020/02/01/1580558490671574.png', 'c13566205e8a1fe0472abd150f84507b45b01fc3e7cc58a6c30d1782cc642c2e', 1580558490),
(300, '1580558516351420.png', '会员.png', 'app_center_nav', 3002, '.png', 'image', '/static/upload/images/app_center_nav/2020/02/01/1580558516351420.png', 'b04cc8fad2bd975c824e3392cc1c022051fdcfef3a41286ff145735e068cb7cc', 1580558516),
(512, '1599806728463641.png', '2018111915461980516.png', 'quick_nav', 730, '.png', 'image', '/static/upload/images/quick_nav/2020/09/11/1599806728463641.png', '52eff07577a7e548179b431713510293e84f28a7de1730f77a6b1bdec3732813', 1599806728),
(514, '1599808001838784.png', '2018111915482687655.png', 'quick_nav', 1430, '.png', 'image', '/static/upload/images/quick_nav/2020/09/11/1599808001838784.png', '86a1e78258600f4bdb822c993025e38daf1bcf06552e4d48a9c93350cbf2a4d8', 1599808001),
(518, '1600321639662998.png', '位置.png', 'quick_nav', 3573, '.png', 'image', '/static/upload/images/quick_nav/2020/09/17/1600321639662998.png', 'a64df8d480c7d0bc47dd5cc0dd59ebf4cd09e833ce80802d857f51c857b76686', 1600321639),
(519, '1600322667732829.png', '电话.png', 'quick_nav', 3842, '.png', 'image', '/static/upload/images/quick_nav/2020/09/17/1600322667732829.png', 'a1038b6b9a2b2a1fee13d306b07f6d44a897911f5f314f1259aa9dba5090c3c7', 1600322667),
(595, '1607398361522502.png', '发票100x100.png', 'app_center_nav', 4645, '.png', 'image', '/static/upload/images/app_center_nav/2020/12/08/1607398361522502.png', 'a3e6a81b9982d32595cef9094d71ea14ae38fff34a8e71ae535d567b635c239b', 1607398361),
(598, '1608608498784252.png', '签到 (1).png', 'app_center_nav', 5724, '.png', 'image', '/static/upload/images/app_center_nav/2020/12/22/1608608498784252.png', 'cbf6976b0a54761334c97f0c46dc21f391bd7cf2862aaa2b1acd4c0d4116e6a8', 1608608498),
(767, '1637564231868321.png', '手机轮播-01.png', 'slide', 350892, '.png', 'image', '/static/upload/images/slide/2021/11/22/1637564231868321.png', '753579fda43dfb6c0877d4c132d2ff6284a9b12366b759077c3ea0b7a5308823', 1637564231),
(768, '1637564273444601.png', '手机轮播-02.png', 'slide', 111636, '.png', 'image', '/static/upload/images/slide/2021/11/22/1637564273444601.png', '51dfb75c953ae2d5779d255e09ee5bbb3b9adb87e4b1d2b998d8e12e6ebda644', 1637564273),
(1442, '1653645476953808.jpg', 'banner-2.jpg', 'slide', 357229, '.jpg', 'image', '/static/upload/images/slide/2022/05/27/1653645476953808.jpg', '17023b096dc23594b66d5222424b4e067703c5dad3079fa24f717c141753353a', 1653645476),
(1444, '1653646660511503.jpg', 'banner-1.jpg', 'slide', 416202, '.jpg', 'image', '/static/upload/images/slide/2022/05/27/1653646660511503.jpg', '9b9c44ec3ea0c4367208a1b93b3ad580097d47e8fcb46f0c581a234d9c6ea173', 1653646660),
(2149, '1688370464496578.png', '登录-1.png', 'common', 844812, '.png', 'image', '/static/upload/images/common/2023/07/03/1688370464496578.png', 'fa03aa7170f8ad207aed0a882c7a8f1e3f32494c7515ab459a34c57cc0cbf64a', 1688370464),
(2150, '1688370477913669.png', '登录-2.png', 'common', 593632, '.png', 'image', '/static/upload/images/common/2023/07/03/1688370477913669.png', '10ef9a867a9752829d45d7f6a59d5f1e972b49d7138c9a7d14c27c7246b76a14', 1688370477),
(2151, '1688712376285299.png', 'banner-01.png', 'common', 97594, '.png', 'image', '/static/upload/images/common/2023/07/07/1688712376285299.png', '7e317a4f823030678e8097a609e65eb43b7c9498b5d51dc50c02d71cd3a493b9', 1688712376),
(2154, '1691819575986269.png', 'left1.png', 'goods_category', 223895, '.png', 'image', '/static/upload/images/goods_category/2023/08/12/1691819575986269.png', '2090e6a6b7888525e997cefeada834c831a94b5a862be2a30f594922620c2ddf', 1691819575),
(2155, '1691819651259940.png', 'left1服饰.png', 'goods_category', 124044, '.png', 'image', '/static/upload/images/goods_category/2023/08/12/1691819651259940.png', '2d6e5806f68873e0a83e74e4d118d2e739b0abe914889b159f66dedd7026a11e', 1691819651),
(2156, '1691819741793450.png', 'left2服饰.png', 'goods_category', 196355, '.png', 'image', '/static/upload/images/goods_category/2023/08/12/1691819741793450.png', '6d1fbb512754d3be1d9cda89288f98e29167c5485c777d2a4c10db99a658bf03', 1691819741),
(2157, '1691820031245372.png', 'lefta2服饰.png', 'goods_category', 176476, '.png', 'image', '/static/upload/images/goods_category/2023/08/12/1691820031245372.png', '0e50bbc20eb7d133e29c8bc0ad1a8f20481cd87a5509c8ddbe4695d14492c94a', 1691820031),
(2158, '1691823127165367.png', '图层 19.png', 'brand', 15867, '.png', 'image', '/static/upload/images/brand/2023/08/12/1691823127165367.png', '6ce047ec8ec9401019e9bc842248973fe5ad4a5baa9902041f97852c21901ca4', 1691823127),
(2160, '1691823431903231.png', '手机4.png', 'goods', 357754, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691823431903231.png', 'abb64a32185ad695c6db8aa964c066e08cb1acaadad6da3068fcca8ce8014b10', 1691823431),
(2161, '1691823902414748.png', '苹果Logo.png', 'brand', 21806, '.png', 'image', '/static/upload/images/brand/2023/08/12/1691823902414748.png', '0002b6964b6df937997a211dfee26f73a9923a6cedba348eca00b5ed86fca8c7', 1691823902),
(2163, '1691824121231788.png', '手机3.png', 'goods', 269470, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691824121231788.png', '77c5bd08596449411821730790242bf7d74081714d34bbda0fd039f4e607c2a3', 1691824121),
(2164, '1691824615829604.png', 'vivo.png', 'brand', 36210, '.png', 'image', '/static/upload/images/brand/2023/08/12/1691824615829604.png', 'd5cd4dd54af7f5bfb836e1f3b0a1db5eb4b96e38cfd22f578ba93b2745fbafe7', 1691824615),
(2166, '1691824925751938.png', '手机2.png', 'goods', 542974, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691824925751938.png', 'ebfc6eb25a946b4f162e946e83ed26696e94b6f0efc535dc47529a9bb35f51a5', 1691824925),
(2167, '1691825240608986.jpg', '607db663667f9992.jpg', 'goods', 113805, '.jpg', 'image', '/static/upload/images/goods/2023/08/12/1691825240608986.jpg', 'c8366936b8744bc2966934df6b750b480026f65e2d754d4f014975a97deea38e', 1691825240),
(2168, '1691825409695125.png', '截屏2023-08-12 15.28.50.png', 'goods', 707600, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691825409695125.png', '78045b1de4ecd4f732b937f8b57324b0732473cb4f5443381fa531c38ba68829', 1691825409),
(2169, '1691825418903590.png', '截屏2023-08-12 15.29.15.png', 'goods', 720353, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691825418903590.png', '2fe76a4a3ed7982896dedae07eeadb27e71aa76e37e56bcc63be805bdf029b73', 1691825418),
(2170, '1691825526466882.jpg', '07803398351cafde.jpg', 'goods', 222107, '.jpg', 'image', '/static/upload/images/goods/2023/08/12/1691825526466882.jpg', 'fbe2648fd2ac1f21670bf8254829f568fd9226059582d625f547e947cedcf837', 1691825526),
(2171, '1691825559410156.jpg', 'e8a05309bd0801ff.jpg', 'goods', 171259, '.jpg', 'image', '/static/upload/images/goods/2023/08/12/1691825559410156.jpg', '4053ecd0d78d8e8b4fadbdcfe9695d05d13fe42e2ce4b29d5250e560e982b1c3', 1691825559),
(2172, '1691825798289880.png', '截屏2023-08-12 15.34.50.png', 'goods', 569162, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691825798289880.png', '8bcdddc5ff486f73d1352cfca02066fc809b94361487126a2731967cdd6fd5a9', 1691825798),
(2173, '1691825808853365.png', '截屏2023-08-12 15.35.03.png', 'goods', 661846, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691825808853365.png', '4517f35ac9f136a6b7f6edbf9903e18620b915358d22b600d88d6c0ac0679e05', 1691825808),
(2174, '1691825817659979.png', '截屏2023-08-12 15.35.17.png', 'goods', 741791, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691825817659979.png', '59e9bb0aa50488a366718706bb2b8b003731168e98e65e21a554f4b78ea89cec', 1691825817),
(2175, '1691825824393923.png', '截屏2023-08-12 15.35.29.png', 'goods', 818430, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691825824393923.png', 'f74bf11c540735c686586cce52542c9a839a1c8fc8368b3eb9510092e2dd7583', 1691825824),
(2176, '1691825831836107.png', '截屏2023-08-12 15.35.42.png', 'goods', 599521, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691825831836107.png', 'b64aa7d3d1999f3f4c28fd20ad78d47bc42814a53510d1a67b833b69b022ec24', 1691825831),
(2177, '1691826204910905.png', '截屏2023-08-12 15.34.50.png', 'goods', 530164, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691826204910905.png', 'a5b1425a8929dac014a0413f386f3dffd631918a65ebd87deb7c377a4a11eec7', 1691826204),
(2178, '1691826211530116.png', '截屏2023-08-12 15.35.03.png', 'goods', 661846, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691826211530116.png', '4517f35ac9f136a6b7f6edbf9903e18620b915358d22b600d88d6c0ac0679e05', 1691826211),
(2179, '1691826216620275.png', '截屏2023-08-12 15.35.17.png', 'goods', 741791, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691826216620275.png', '59e9bb0aa50488a366718706bb2b8b003731168e98e65e21a554f4b78ea89cec', 1691826216),
(2180, '1691826222270713.png', '截屏2023-08-12 15.35.29.png', 'goods', 730627, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691826222270713.png', '6fb4293db4f2b8c63be05eb67434cb7b23623d7112677dafa84687f8f0030b42', 1691826222),
(2181, '1691826226253803.png', '截屏2023-08-12 15.35.42.png', 'goods', 599521, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691826226253803.png', 'b64aa7d3d1999f3f4c28fd20ad78d47bc42814a53510d1a67b833b69b022ec24', 1691826226),
(2183, '1691826776365755.png', '商品.png', 'goods', 727271, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691826776365755.png', '67e4ca21bb8a67dd2e1feb5cb411e1ef937ebdb643f5b0ba055f33c81c41bca4', 1691826776),
(2184, '1691827475272976.png', '截屏2023-08-12 15.53.51.png', 'goods', 393520, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691827475272976.png', '209c88263fab5097823166a8a39236b82b75ad27f7095adddbfb37c87cdb7435', 1691827475),
(2185, '1691827486979998.png', '截屏2023-08-12 15.54.01.png', 'goods', 671241, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691827486979998.png', '75a556b72017613785fd8f0cffda9710c072b7a66fa3b44a8a85e1dac8e3a974', 1691827486),
(2186, '1691827499314526.png', '截屏2023-08-12 15.54.14.png', 'goods', 1029568, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691827499314526.png', 'c3420e2bbfe6a2b473be771b6170351ffebcbd49bd5d37c2df9e6f613fff7a42', 1691827499),
(2187, '1691827507900924.png', '截屏2023-08-12 15.54.26.png', 'goods', 786494, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691827507900924.png', 'c536ebbc3cad91bf3a7355fdc574f3f7f35238adfa3e8eea459ac5e46c124bdd', 1691827507),
(2188, '1691827515845673.png', '截屏2023-08-12 15.55.14.png', 'goods', 554211, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691827515845673.png', '5b32909c9fa81cb106cd33e91c98105fdfc838f3982ff9883373eb75690155ca', 1691827515),
(2189, '1691827555731903.png', '截屏2023-08-12 15.57.47.png', 'goods', 569961, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691827555731903.png', 'ee2a2a1b6d4aa59735be3dba02bab5087c9e0e24580b3540638d1403c1e59fa9', 1691827555),
(2190, '1691828238575330.png', '惠普Logo.png', 'brand', 319157, '.png', 'image', '/static/upload/images/brand/2023/08/12/1691828238575330.png', '3369e77c597cd9c1e0638e9773e0d2dcbe214ca4005c5e59dbddf9816cddae97', 1691828238),
(2192, '1691828468899201.png', '电脑1.png', 'goods', 341424, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691828468899201.png', '71448188a74f60a68cc9ab6b378ba1c9e742ce56f4b47ecb17f4e18dc897de89', 1691828468),
(2193, '1691828836385323.png', '截屏2023-08-12 16.21.53.png', 'goods', 719344, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691828836385323.png', 'ae691ec8f430c849d3f822177743fb1b2fb10c1298999557d383cbe231a88519', 1691828836),
(2194, '1691828844665561.png', '截屏2023-08-12 16.22.06.png', 'goods', 883409, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691828844665561.png', 'b5a5d5e6861774553b9ed543ccec223b419ad708e62643b3adbe35d19f1300be', 1691828844),
(2195, '1691828851964308.png', '截屏2023-08-12 16.22.26.png', 'goods', 889202, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691828851964308.png', '189d5e06bea3557ab3b17e5992a549f895acf7403d8712f3ab7dec9df991da38', 1691828851),
(2196, '1691828857590839.png', '截屏2023-08-12 16.22.51.png', 'goods', 358816, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691828857590839.png', '2534e143ba0e4c9de357f8449f0e3ddfda8a0f908a2b6b1514f38b8bd393a170', 1691828857),
(2197, '1691828863550756.png', '截屏2023-08-12 16.23.05.png', 'goods', 323311, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691828863550756.png', '1fd2ab16220a93fd4e3fb7a09e59c999b410d9d795eefb6cc743042d1e9df151', 1691828863),
(2198, '1691828867505745.png', '截屏2023-08-12 16.23.23.png', 'goods', 594938, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691828867505745.png', '37fe026d6bc312c953ff78d67e12c6f0ca8b05dc0f0f1672a08f0f4472ab15a7', 1691828867),
(2199, '1691828870712232.png', '截屏2023-08-12 16.23.41.png', 'goods', 741756, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691828870712232.png', '81f562c7d91a7c5dc2eab08708392981195f0945685526d8db7a4996216ebe1d', 1691828870),
(2201, '1691829141472866.png', '电脑4.png', 'goods', 393935, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691829141472866.png', '54041fb15079656dbc07662e8fb26cc1d2d499b947fd01202a8e5fe0cee04e69', 1691829141),
(2202, '1691829236843759.jpeg', 'c91987a552115e8a.jpeg', 'goods', 833804, '.jpeg', 'image', '/static/upload/images/goods/2023/08/12/1691829236843759.jpeg', '2fdd30736220608ad766a628f6fa88ca25b1922e0e058f46e58dfacdf876f921', 1691829236),
(2203, '1691829245863546.jpeg', 'f23ebee50fae457c.jpeg', 'goods', 923438, '.jpeg', 'image', '/static/upload/images/goods/2023/08/12/1691829245863546.jpeg', '74984b6f10620ea149fb5e815f44cacceecb82f62f32a2017a3f8a352760ca3c', 1691829245),
(2204, '1691829803316804.png', '截屏2023-08-12 16.40.01.png', 'goods', 586135, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691829803316804.png', '342a1b29fe1008d86203bd427241ebd61bb4fad6d58f329c963a65847ff42f08', 1691829803),
(2205, '1691829808460903.png', '截屏2023-08-12 16.40.09.png', 'goods', 428148, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691829808460903.png', '6ede7f1039f002fec2bb7c98e032e8ef081064fedc36959030c7244887b67f24', 1691829808),
(2206, '1691829813811407.png', '截屏2023-08-12 16.40.19.png', 'goods', 387835, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691829813811407.png', 'e5286acc60134870eb1aac57f977a38eda92e56d4a1f1e2a7d298799a0d8c49b', 1691829813),
(2207, '1691829818501892.png', '截屏2023-08-12 16.41.13.png', 'goods', 324912, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691829818501892.png', 'de29f0c88aace37b1a7e107fd757c8877d6dfdf910217d7c2562aa043d2d5bf3', 1691829818),
(2211, '1691830293182918.png', '电脑6.png', 'goods', 312437, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691830293182918.png', '19cf170f75cd43f83ec9d7f2c2f9ebadce62e66487316ce6e8e0139bbc795ca8', 1691830293),
(2213, '1691830511475725.png', '电脑7.png', 'goods', 246884, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691830511475725.png', '8798e1ed90b8ef1e60032db03b240ffd0fa2fdd28331f4b48925b07d86662e7e', 1691830511),
(2215, '1691831046790794.png', '电脑8.png', 'goods', 413319, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691831046790794.png', 'dbd76fc1977ae0539da408f601dde4652c9a9f30ba5bd80e6ea117151447016c', 1691831046),
(2216, '1691831848237506.png', '联想.png', 'brand', 21634, '.png', 'image', '/static/upload/images/brand/2023/08/12/1691831848237506.png', 'c601c7d1d5ebf015ecc087f6d0c4fea2c4b6a96fc58e6acba396a3fc57d56104', 1691831848),
(2217, '1691832026565424.png', '电脑9.png', 'goods', 371754, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691832026565424.png', '98a1e9e665422762de131b64a2d96c5fab5484bd86baf51f9f83b19e4984e08d', 1691832026),
(2218, '1691832377600902.png', '截屏2023-08-12 17.23.05.png', 'goods', 482652, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691832377600902.png', 'ea4cfc200f3aa9c090b9c7e97c277f2631e5cc9b66d9493508d70a588b08f991', 1691832377),
(2219, '1691832382882584.png', '截屏2023-08-12 17.23.34.png', 'goods', 408634, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691832382882584.png', 'd78cff0606052a99ffb99327299ccee3e7e53691ff24c0a8401fcdc02061f2d2', 1691832382),
(2220, '1691832386387138.png', '截屏2023-08-12 17.23.48.png', 'goods', 401368, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691832386387138.png', 'f272f63adea2b7333f4485c65dcb7248c44c1ef5b76ca4e15fdd6d91c2f559f8', 1691832386),
(2221, '1691832391391925.png', '截屏2023-08-12 17.24.06.png', 'goods', 210985, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691832391391925.png', '20355603bb02dc272c38776475741268947ee6ef79ad341a14af9f2cf41fb230', 1691832391),
(2222, '1691832395282530.png', '截屏2023-08-12 17.24.19.png', 'goods', 421372, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691832395282530.png', 'e51560e29519e6aa5c1a1fc452c428dda3e356d61f2403fbd2ccee6d13ff1596', 1691832395),
(2223, '1691832443984882.png', '电脑9.png', 'goods', 371754, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691832443984882.png', '98a1e9e665422762de131b64a2d96c5fab5484bd86baf51f9f83b19e4984e08d', 1691832443),
(2224, '1691832967621264.png', '花花公子.png', 'brand', 24173, '.png', 'image', '/static/upload/images/brand/2023/08/12/1691832967621264.png', '4463dad65a73b10bb77c0140aa24eae4a012ef8722f7e384c3057bc755a4912a', 1691832967),
(2231, '1691834871558142.png', '截屏2023-08-12 17.54.04.png', 'goods', 247784, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691834871558142.png', '454f1b01e30557d9640e8879763f83c63fd525648e81d52b638381efb9778430', 1691834871),
(2232, '1691834878159970.png', '截屏2023-08-12 17.54.14.png', 'goods', 556497, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691834878159970.png', '01d86e71109163aade566c0ee5cc9e9398b3c73e9a9295d344b15eff7b948cd8', 1691834878),
(2233, '1691834882455325.png', '截屏2023-08-12 17.54.23.png', 'goods', 434909, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691834882455325.png', '7db0f6fa5a57d9efa2c854c58b6f86d153b1d83730c8f8b33db7bec19b90a6c5', 1691834882),
(2234, '1691834887521471.png', '截屏2023-08-12 17.54.34.png', 'goods', 341688, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691834887521471.png', '834a37371588e1678613f9c6bbcfc8a275e48127cf1875fa87b7846b8b9a6779', 1691834887),
(2235, '1691834891837211.png', '截屏2023-08-12 17.54.47.png', 'goods', 147668, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691834891837211.png', 'b32d17bbc352bff640bdc9d2f4abfe3aa53c61f11af71d2fc7e25b1e235c639b', 1691834891),
(2236, '1691834999411165.png', '花花公子衣服.png', 'goods', 787342, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691834999411165.png', '2c8cd7504f436fea4fb796cdaad046a0c8ec256e2a00fc326a6b197836f35020', 1691834999),
(2237, '1691835126265383.png', '南极人Logo.png', 'brand', 80144, '.png', 'image', '/static/upload/images/brand/2023/08/12/1691835126265383.png', 'a3c201ad98ac9f063c11e3ab128b6bd749685dde917d058bb2c5afe6d34627c8', 1691835126),
(2239, '1691835373537126.png', '南极人.png', 'goods', 548974, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691835373537126.png', '6c70b1a1ec7d7eb8b8affb0ced9d5473ca44f9e0286ab3675bc346a18e333452', 1691835373),
(2240, '1691835697559233.png', '截屏2023-08-12 18.17.06.png', 'goods', 174726, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691835697559233.png', '9f40481954c6b7bb88e00339b832f76204c5f8ec27e7d74ac66eead1dbd30f51', 1691835697),
(2241, '1691835704154657.png', '截屏2023-08-12 18.17.17.png', 'goods', 168029, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691835704154657.png', '1ebdd4e08941e0af264464cfe9f9e087df4a974ccd3669e869926158152eb343', 1691835704),
(2242, '1691835708771308.png', '截屏2023-08-12 18.17.27.png', 'goods', 431964, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691835708771308.png', 'e214e3ec4888f363000e9f8faaee53e840ce162493cbda1ea2bd7feca14255a8', 1691835708),
(2243, '1691835712880551.png', '截屏2023-08-12 18.17.39.png', 'goods', 82906, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691835712880551.png', 'cbb0eb76ec02990944ad94f625ae189b5a0f83d2a0cdef71f9f54eb91dd7b504', 1691835712),
(2244, '1691835977239809.png', '斐乐Logo.png', 'brand', 83120, '.png', 'image', '/static/upload/images/brand/2023/08/12/1691835977239809.png', '4b8df5eef191ae961632c45744a16f18ce9acdd7a024ba3ee5cc64ada328b03e', 1691835977),
(2246, '1691836236770925.png', '斐乐.png', 'goods', 467176, '.png', 'image', '/static/upload/images/goods/2023/08/12/1691836236770925.png', 'f08a74aac5cce1f39de7bef7fd9b849a770c90679b5f9595b2cbabffac100099', 1691836236),
(2247, '1691836485950943.jpeg', '50c31866f4514934.jpeg', 'goods', 147697, '.jpeg', 'image', '/static/upload/images/goods/2023/08/12/1691836485950943.jpeg', 'cc7d8e477ed60ba8e88e189c7cd92ac5e7aa0f8ea1b8d287c906a055f7768d9a', 1691836485),
(2248, '1691836493757182.jpeg', '38ba5e598fcbaa86.jpeg', 'goods', 332629, '.jpeg', 'image', '/static/upload/images/goods/2023/08/12/1691836493757182.jpeg', '0f9026dbd630b583adfe52e038d1a8a7f457b875463d7e52886bb3943c7a1f38', 1691836493),
(2249, '1691836499159372.jpeg', 'b59f10cf74dd5d1d.jpeg', 'goods', 194710, '.jpeg', 'image', '/static/upload/images/goods/2023/08/12/1691836499159372.jpeg', 'd65b55a1e96212622c9006a720734350693fd29d33cb2604a161aa3c7987b7c1', 1691836499),
(2250, '1691977709405973.png', '截屏2023-08-14 09.42.05.png', 'brand', 7513, '.png', 'image', '/static/upload/images/brand/2023/08/14/1691977709405973.png', '9636ff063bf9dc0c822d0b94180477a643580b0d2e20033799e43b6b5f1c111d', 1691977709),
(2252, '1691977878897545.png', 'mzo.png', 'goods', 664387, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691977878897545.png', '24a4a5ec496b26bdcf3cdf540d3bc6542a42d4e04224d0a52ec144d13c6a32b2', 1691977878),
(2253, '1691979298102584.png', '截屏2023-08-14 09.53.27.png', 'goods', 425877, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691979298102584.png', 'e729bd3c1f4010281db5624628f9057da7980e6ef3aad789337e2d4db51a1252', 1691979298),
(2254, '1691979306209110.png', '截屏2023-08-14 09.53.43.png', 'goods', 324446, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691979306209110.png', 'd3e721200ff8eea0b7933d2d069c41d404c83c39ecd8abaf095715f31cc28a08', 1691979306),
(2255, '1691979323816227.png', '截屏2023-08-14 09.54.10.png', 'goods', 334781, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691979323816227.png', 'bc5debbc2f32c9621c4c022c38d15cb08c4a848b716b5cd0f387c93634dd2110', 1691979323),
(2256, '1691979330939435.png', '截屏2023-08-14 09.54.27.png', 'goods', 182434, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691979330939435.png', '21af219fe869626faa6fcc03ab76ab4d412bfc24f7a7a8e7d4683c5d1af4fffc', 1691979330),
(2257, '1691979342342357.png', '截屏2023-08-14 09.54.36.png', 'goods', 277730, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691979342342357.png', '0360209714a55f32bee1a251c3a497291080d65bb7c73fc733db4434d32208ab', 1691979342),
(2258, '1691979547580992.png', 'shiroma.png', 'brand', 5975, '.png', 'image', '/static/upload/images/brand/2023/08/14/1691979547580992.png', '2b162640fc013ec9dc1d3e431e0b28152c07d090ba7807a8d59fe80cd055f2d7', 1691979547),
(2261, '1691980079575635.png', 'shiroma商品.png', 'goods', 803229, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691980079575635.png', '3e79e6725397dbaa5408a371721370635c878d5ea34b61517112bb69d4b60bcc', 1691980079),
(2262, '1691980637926632.jpeg', 'afc398a681f799bd.jpeg', 'goods', 100776, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691980637926632.jpeg', '883d15ed57ef3554ccbec1276b760af06458dbe518b3e15d84a481a314becc27', 1691980637),
(2263, '1691980650796377.jpeg', 'b389b005e23687d3.jpeg', 'goods', 49820, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691980650796377.jpeg', '50ed22e9f2082bb95ec0c2a62d5ecc960c325337a7eba4426f56711747628681', 1691980650),
(2264, '1691980658267865.jpeg', '3a6b95cd1422b6bc.jpeg', 'goods', 114535, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691980658267865.jpeg', 'cef9ea0d04680a0804ae6eb9572cb2552bd574031d4ffa61267c759c3b276923', 1691980658),
(2265, '1691980676639236.jpeg', 'c88f33e9ff202283.jpeg', 'goods', 135869, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691980676639236.jpeg', '5cdeb7df16529df92092179bb53d5b9a0e0eb3101af3ca85a396f26caffc1f5a', 1691980676),
(2266, '1691980683406570.jpeg', 'c341c819b70e7d08.jpeg', 'goods', 112039, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691980683406570.jpeg', 'b0c1acc165119c0a0d85e252200ef437aae8b892a2126173a9b157226115d749', 1691980683),
(2267, '1691980688105551.jpeg', 'd4bc071405542688.jpeg', 'goods', 127101, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691980688105551.jpeg', '03aa6960f80a8b1f26b6a57ab1f2f65d5671fb9df8096bfe92871eeaf67ed22c', 1691980688),
(2268, '1691980696623584.jpeg', '46904123c8df65bc.jpeg', 'goods', 154835, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691980696623584.jpeg', '242a12fa83889763ac19524e2af90da8f69b672311c421d2e16e5548f73d773f', 1691980696),
(2269, '1691980703617945.jpeg', '3a6b95cd1422b6bc.jpeg', 'goods', 114535, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691980703617945.jpeg', 'cef9ea0d04680a0804ae6eb9572cb2552bd574031d4ffa61267c759c3b276923', 1691980703),
(2270, '1691980708438566.jpeg', '5d145f272b217693.jpeg', 'goods', 59745, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691980708438566.jpeg', '94874c1d6d9dc5c4e20b7b13d7017e2cfd963456116fd83e592c9cf2ed0f0067', 1691980708),
(2271, '1691981092702792.png', '皮尔卡丹Logo.png', 'brand', 5282, '.png', 'image', '/static/upload/images/brand/2023/08/14/1691981092702792.png', '831b981989b9135cd79c357d1e207a14bac894ad584e9d6e7e159fb08765aac8', 1691981092),
(2272, '1691981199467207.png', '皮尔卡丹服饰.png', 'goods', 912050, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691981199467207.png', 'acdddd887486051b7b8ad0e4643f5dab9ea09abecf23c646d86de95fe7c632b2', 1691981199),
(2273, '1691981231897653.png', '皮尔卡丹服饰.png', 'goods', 912050, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691981231897653.png', 'acdddd887486051b7b8ad0e4643f5dab9ea09abecf23c646d86de95fe7c632b2', 1691981231),
(2274, '1691981552646443.jpeg', 'f0c7c98f0e2d657a.jpeg', 'goods', 167104, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691981552646443.jpeg', '6c3fa2f1af6e6404910e5a49e99efe6b974247aa76f6935119be738f785ec43c', 1691981552),
(2275, '1691981563790985.jpeg', '7d2857a3f7bd3b6e.jpeg', 'goods', 104499, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691981563790985.jpeg', '4cd302e6edf46d766aae124cd268308f65231c0b5e1bd9c18a7c55d4092d204b', 1691981563),
(2276, '1691981572751256.jpeg', '301b42184857f26f.jpeg', 'goods', 117334, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691981572751256.jpeg', '08b640c11bf1df8b1418e46a2cf235dd4fdcca299ace9261de06597f32e6532f', 1691981572),
(2277, '1691981578921879.jpeg', '47ecec2e3b591018.jpeg', 'goods', 60705, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691981578921879.jpeg', 'b7561fe7397f0580bac473c783de2d43047a14de6f17d9ab16edcab1141b65d4', 1691981578),
(2278, '1691981588510583.jpeg', '5c8029697913c145.jpeg', 'goods', 209151, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691981588510583.jpeg', '4996985d73e44f131c41a0f9c5d1a871923857c0a754e12d491538fa03cbe363', 1691981588),
(2279, '1691981594441933.jpeg', 'f70b6ceaf59bd1ad.jpeg', 'goods', 155329, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691981594441933.jpeg', 'cfdb926c0432d9f2c91c641347cda79a6e6fb765e1f4f0eaa50099bca0a5be7c', 1691981594),
(2280, '1691981602797480.jpeg', '8f765f7675e2417f.jpeg', 'goods', 131590, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691981602797480.jpeg', 'f7f0fe5614162a05ff9c106dcc920322711f79bdbc54b41048f98aecfcc0af76', 1691981602),
(2281, '1691981708429646.jpeg', 'b194d13b209d8d51.jpeg', 'goods', 108441, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691981708429646.jpeg', '0a3f1034305adcf9c9a24e4669d7653cd5bf387f0a8fff48c6daa20a68703305', 1691981708),
(2282, '1691982150655928.png', '科罗蒙凯logo.png', 'brand', 38767, '.png', 'image', '/static/upload/images/brand/2023/08/14/1691982150655928.png', '8c169dcf3219630b1ef8e2b374bd07cc6038232a4140dda6344c9e8a07fa9be9', 1691982150),
(2283, '1691982245246201.png', '科罗蒙凯logo.png', 'brand', 38767, '.png', 'image', '/static/upload/images/brand/2023/08/14/1691982245246201.png', '8c169dcf3219630b1ef8e2b374bd07cc6038232a4140dda6344c9e8a07fa9be9', 1691982245),
(2286, '1691982517423442.jpeg', '80bc7902d293e789.jpeg', 'goods', 132136, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691982517423442.jpeg', '68a618327b1a6ec5661ee8b67f38828a26cab48fcdc247c9b3cf348afa0d947d', 1691982517),
(2287, '1691982541729356.jpeg', '26ba0e862c6c51d0.jpeg', 'goods', 79041, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691982541729356.jpeg', '9009c1269d973532009eaa37b9c2cacfd91d1dbce825e7022dc28f6aa6040898', 1691982541),
(2288, '1691982548999591.jpeg', '4415c45dc5d01619.jpeg', 'goods', 151629, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691982548999591.jpeg', 'd05efa208b7ac2b1e311c3362460cca0d476406181f328bcca280d058371732e', 1691982548),
(2289, '1691982552969620.jpeg', '020542b932c93818.jpeg', 'goods', 51704, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691982552969620.jpeg', '3aa7b30e75354487870058e7a668501d8844c5b34bdbcb971c79aa15c6255bbc', 1691982552),
(2290, '1691982558477549.jpeg', '772934dec1af6306.jpeg', 'goods', 82002, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691982558477549.jpeg', 'c0f63999798b7a40cac37debaff69e28842d728be7f139ac9f4f1eae2b2fabae', 1691982558),
(2291, '1691982564622907.jpeg', 'c674657dbf374805.jpeg', 'goods', 153652, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691982564622907.jpeg', '68e7b04502d897dc1071d376b4a6070e261330fcf40a02bcf187e92f30f8a365', 1691982564),
(2292, '1691982569733444.jpeg', '3641063eb694f484.jpeg', 'goods', 56137, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1691982569733444.jpeg', '3dc4c6b80b369e9ab49d7621387f81560ba5990fbe894122f9e7859db04b1cd5', 1691982569),
(2293, '1691982936761169.png', '古驰logo.png', 'brand', 2834, '.png', 'image', '/static/upload/images/brand/2023/08/14/1691982936761169.png', 'd32772001d063926d9f19edd45df908dd0105b797e6ec8815f0669904b008856', 1691982936),
(2296, '1691983213889964.png', 'dae02c427f6d4ed0.png', 'goods', 214160, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691983213889964.png', '8afc90f244f1758d40ff9cbab4319d8647e2e55d2970c6415ee0e7ecda39681d', 1691983213),
(2297, '1691983221350725.png', '3b57b165392bf578.png', 'goods', 156435, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691983221350725.png', 'c745fbaadd17866a44cb513ebdf24af9a062c6e1a07652f96dc75de41cdbf705', 1691983221),
(2300, '1691983247500656.png', 'e12d5e7457909ce7.png', 'goods', 95769, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691983247500656.png', '2eb8c241ea1892dd1ee5c9706f741f624ef52ebb1d4c826818e68c2953121750', 1691983247),
(2319, '1691994279806663.png', '海澜之家logo.png', 'brand', 16406, '.png', 'image', '/static/upload/images/brand/2023/08/14/1691994279806663.png', 'ff58901f3a302fac365dd00db876f58c72b5498f9f9646456dd581ddc44f876e', 1691994279),
(2321, '1691994396257476.png', '海蓝之家商品.png', 'goods', 632087, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691994396257476.png', '7028ca86e9b05f1eceddd9ab0fd49ad73b91520429dd4bbd5cdc87843fdd90b5', 1691994396),
(2322, '1691994898577354.png', '截屏2023-08-14 14.29.06.png', 'goods', 261726, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691994898577354.png', '87afaa887a2de856a8e8729e1eac8ed0e60fe4a43a1e8129c388ad4bf780bcb2', 1691994898),
(2323, '1691994898586882.png', '截屏2023-08-14 14.28.15.png', 'goods', 484263, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691994898586882.png', '3dca03bd4a7b527cfef631033357bef49cc6decaf86bd8ab773f699a919e5039', 1691994898),
(2324, '1691994898727839.png', '截屏2023-08-14 14.28.36.png', 'goods', 495891, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691994898727839.png', '64dd6d1af63f9bb728bc84ff250aec0d4534daa5954aec08cfb2f9903c4e091f', 1691994898),
(2325, '1691994898891000.png', '截屏2023-08-14 14.28.46.png', 'goods', 455943, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691994898891000.png', 'b02dd54102fde2d029b98376d9faa17c2c80ec35ccbe2a543fdba50190526ded', 1691994898),
(2326, '1691994898735499.png', '截屏2023-08-14 14.28.02.png', 'goods', 501818, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691994898735499.png', 'c291b736b074a6b338fc703f7558cfd2a4713423084124d8d7e8e06daf6d7399', 1691994898),
(2327, '1691994917804872.png', '截屏2023-08-14 14.28.15.png', 'goods', 484263, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691994917804872.png', '3dca03bd4a7b527cfef631033357bef49cc6decaf86bd8ab773f699a919e5039', 1691994917),
(2328, '1691994924628415.png', '截屏2023-08-14 14.28.36.png', 'goods', 495891, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691994924628415.png', '64dd6d1af63f9bb728bc84ff250aec0d4534daa5954aec08cfb2f9903c4e091f', 1691994924),
(2329, '1691994931354691.png', '截屏2023-08-14 14.28.46.png', 'goods', 455943, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691994931354691.png', 'b02dd54102fde2d029b98376d9faa17c2c80ec35ccbe2a543fdba50190526ded', 1691994931),
(2330, '1691994935562238.png', '截屏2023-08-14 14.29.06.png', 'goods', 261726, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691994935562238.png', '87afaa887a2de856a8e8729e1eac8ed0e60fe4a43a1e8129c388ad4bf780bcb2', 1691994935),
(2331, '1691995321426107.png', '森马Logo.png', 'brand', 17418, '.png', 'image', '/static/upload/images/brand/2023/08/14/1691995321426107.png', '16e28a1bbdf3d9249ec9d260c9469bc545429719467aff447ff7462c883c5cb0', 1691995321),
(2333, '1691995464796317.png', '森马商品.png', 'goods', 862728, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691995464796317.png', 'ebacba95eeeac077aeed1c85ab108a93ac342e905612a1ce4152cfecf8b221d4', 1691995464),
(2334, '1691995907479445.png', '截屏2023-08-14 14.46.04.png', 'goods', 611970, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691995907479445.png', '97f40bae6aefd0ebc5543aaf62630fc8da7eceb3762ba5bdf6b8357191ffb531', 1691995907),
(2335, '1691995915250299.png', '截屏2023-08-14 14.46.39.png', 'goods', 854577, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691995915250299.png', '54092faf61d10db017c48b9ccfba99f570e64cb67aaeffc18163cd4bb9d292b1', 1691995915),
(2336, '1691995921520538.png', '截屏2023-08-14 14.46.54.png', 'goods', 630134, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691995921520538.png', 'b90fad4c0b08e4356e5bd0878930d658ec5c81897271cf1e229d64085bcd8b8a', 1691995921),
(2337, '1691995926708720.png', '截屏2023-08-14 14.47.02.png', 'goods', 665708, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691995926708720.png', 'b4ed80ea7016bd9bbc876ac5ffff35ce467b9ef8fc6be54980c698d1af41729b', 1691995926),
(2338, '1691995935519352.png', '截屏2023-08-14 14.47.12.png', 'goods', 951352, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691995935519352.png', '267cddaa9251316b5f35d249ea1c03fab480510de8c0e8fd35914129cf890304', 1691995935),
(2339, '1691995941891876.png', '截屏2023-08-14 14.47.21.png', 'goods', 142840, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691995941891876.png', 'a123c16a72eaaa70eb1369324fd203e2c2ebd1e49b51c22f4833eb460e35a444', 1691995941),
(2340, '1691995946950732.png', '截屏2023-08-14 14.47.37.png', 'goods', 111075, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691995946950732.png', '08617d8034f26812f82cd6b172d4be6382bb07dc8af4e06faab694e3ae8732f3', 1691995946),
(2341, '1691996707185755.png', '蔻驰Logo.png', 'brand', 6410, '.png', 'image', '/static/upload/images/brand/2023/08/14/1691996707185755.png', '10956366fc0b502367337ac630976afb07856f40aa2f65c2ff7f868da5a5a977', 1691996707),
(2343, '1691996824307963.png', '蔻驰商品.png', 'goods', 386972, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691996824307963.png', '30053bed42ab5bfbdff3076fccfe187bb3ec969692fb993db0ddc044a1ecd411', 1691996824),
(2344, '1691997191254126.png', '截屏2023-08-14 15.07.54.png', 'goods', 320770, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691997191254126.png', 'fd6eaa3ae93437d5ae02aca6caf9dc15aa59c8ee59b7d17b62f42599db4189f3', 1691997191),
(2345, '1691997198671732.png', '截屏2023-08-14 15.08.06.png', 'goods', 301815, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691997198671732.png', '2b89d9bcb9e3c90150b772152c4469586a0d5993936ad5569f41b048c8da96e8', 1691997198),
(2346, '1691997205743458.png', '截屏2023-08-14 15.08.16.png', 'goods', 103248, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691997205743458.png', '5f6afaf3123f6215cbeeea69594c329639871f75f1cb14c50632dc286dbcd2b5', 1691997205),
(2347, '1691997211355818.png', '截屏2023-08-14 15.08.29.png', 'goods', 392352, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691997211355818.png', '217fbb48882a53370c523a5c3f54e98a251b9abde7425f8bef3a1264327f7d1f', 1691997211),
(2348, '1691997217563755.png', '截屏2023-08-14 15.08.47.png', 'goods', 630497, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691997217563755.png', '507d10d5e1bb5ed062afe9fd433a6ea8d9cbd66c2a4b3e8f2caf6ff7566f1865', 1691997217),
(2349, '1691997220388481.png', '截屏2023-08-14 15.08.57.png', 'goods', 291855, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691997220388481.png', '92af4ef71e045d258806810e37ad32e147cbe0c1622d9538026bf04b391736f7', 1691997220),
(2350, '1691997896419442.png', '新秀丽Logo.png', 'brand', 18948, '.png', 'image', '/static/upload/images/brand/2023/08/14/1691997896419442.png', 'd20a6fb5a9303edc13793f51fa402d13bf13a0933236acc18f5ba26aa549659b', 1691997896),
(2353, '1691998232498590.png', '截屏2023-08-14 15.27.47.png', 'goods', 712582, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691998232498590.png', 'fc2886c26a12fd14098ae60730284ee723913fb6c2bc55e21df6d462e39e2d0c', 1691998232),
(2354, '1691998240310902.png', '截屏2023-08-14 15.28.00.png', 'goods', 810072, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691998240310902.png', 'e13c52105e1814265750a30380e80b4bf32af5206210e03a76c2f85780948fd1', 1691998240),
(2355, '1691998248824258.png', '截屏2023-08-14 15.28.13.png', 'goods', 526422, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691998248824258.png', '5278fd78f26d6a8b69ed97c33e657780fa40554dd1d0324e246d5547304edddd', 1691998248),
(2356, '1691998256740994.png', '截屏2023-08-14 15.28.22.png', 'goods', 584085, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691998256740994.png', 'cd576920243b8f25b5456440446032bd16f634b198a22e83731c85fcea0225ff', 1691998256),
(2357, '1691998264851776.png', '截屏2023-08-14 15.28.32.png', 'goods', 425829, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691998264851776.png', 'ccb5f450ff06bd8c86803f2a32e4cc46bcd1dd65fcf9c0c8f6ceb0bac4c477b6', 1691998264),
(2358, '1691999031675571.png', 'vineylogo.png', 'brand', 4373, '.png', 'image', '/static/upload/images/brand/2023/08/14/1691999031675571.png', 'c3208d698be421ed5ce5c554088e3f3dd3b965643788b42bc176d6e789ef7678', 1691999031),
(2360, '1691999334494128.png', 'vi商品.png', 'goods', 834467, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691999334494128.png', '8a8e28d8a6724e6e3acdb80532435c9c6449fa088a81ec8bb24a188423609d74', 1691999334),
(2361, '1691999752973519.png', '截屏2023-08-14 15.49.32.png', 'goods', 861363, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691999752973519.png', '6bba5a7fa9b3072e83fe399f1aedcf3edd967f273dca35a223fdb47437f1be16', 1691999752),
(2362, '1691999761383333.png', '截屏2023-08-14 15.50.50.png', 'goods', 336170, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691999761383333.png', '52bb16f8b8f663db71a20e58ca1c14592a95d3dbb253e854dc93dfe6bd76eaaf', 1691999761),
(2363, '1691999770935537.png', '截屏2023-08-14 15.51.02.png', 'goods', 695197, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691999770935537.png', '468403fc6be642d5ff2a0ae349f1ca3ba49557102ab9f50bf8efe33cd32f9fb6', 1691999770),
(2364, '1691999780130379.png', '截屏2023-08-14 15.51.09.png', 'goods', 695785, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691999780130379.png', 'eb9b747c7eeba3b54ba76e3e414e692f59fcbcf4dc61cffc286729b37bfb008c', 1691999780),
(2365, '1691999786841156.png', '截屏2023-08-14 15.51.22.png', 'goods', 873475, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691999786841156.png', 'b85309293a6581d8ac6a2f4d04cdab97dc0a975d5a22a9cc2d60da3c37058c5f', 1691999786),
(2366, '1691999792436424.png', '截屏2023-08-14 15.51.36.png', 'goods', 1009868, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691999792436424.png', '4c0855e392efa8eb468d5e4aa597334adeb8a47e381b1aeb82baa7df346cf54f', 1691999792),
(2367, '1691999798629765.png', '截屏2023-08-14 15.51.47.png', 'goods', 783354, '.png', 'image', '/static/upload/images/goods/2023/08/14/1691999798629765.png', 'c481c2c97e1d96f7af793b23b846d2decf74439d36de1a9768a196f3174722f0', 1691999798);
INSERT INTO `sxo_attachment` (`id`, `title`, `original`, `path_type`, `size`, `ext`, `type`, `url`, `hash`, `add_time`) VALUES
(2368, '1692000815979642.png', '圣罗兰logo.png', 'brand', 30542, '.png', 'image', '/static/upload/images/brand/2023/08/14/1692000815979642.png', '6c6d68fc733aafb2fd3818b7fbe9512d954b5f8dd9dd8c1d5e1dc357a13a98b6', 1692000815),
(2370, '1692000963306994.png', '圣罗兰商品.png', 'goods', 447173, '.png', 'image', '/static/upload/images/goods/2023/08/14/1692000963306994.png', 'eac9d12b7f2799d686afec5d176b8c53b68b6680c07e4fe0560789810cc7e75c', 1692000963),
(2371, '1692001124233965.jpeg', '15d0b2885e052ec2.jpeg', 'goods', 94469, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692001124233965.jpeg', '25de4f163b5a80ff72c8b05c4aafb2ee0d3eb4f2b9026966dfe0d46a9aced8a3', 1692001124),
(2372, '1692001157164542.jpeg', '5aa001dda6e8feb8.jpeg', 'goods', 89444, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692001157164542.jpeg', '3648a03f711531e6dd0abf95ec8f7c584505153ce5255ac08a86329992402a32', 1692001157),
(2373, '1692001164283352.jpeg', '847997a3879f7eb1.jpeg', 'goods', 77316, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692001164283352.jpeg', '347e830672b561dd398483ed4a11f8691ca6db3fe42b037a13c375aa98eb348d', 1692001164),
(2374, '1692001172263788.jpeg', '52788c672d19269e.jpeg', 'goods', 67373, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692001172263788.jpeg', '8c0831ba6b3d7e0a2990888ab573a3229bf5aa186f055db6bee853d3e0b44e2a', 1692001172),
(2375, '1692001177919245.jpeg', '9874b3b8ac6c21cd.jpeg', 'goods', 60844, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692001177919245.jpeg', '9b7845b8c30ccb49362a437014b8b1d6f78db5bc12a1aa3666dd6b531465fc04', 1692001177),
(2376, '1692001183695897.jpeg', '45f3a6de7d6f5d9f.jpeg', 'goods', 117252, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692001183695897.jpeg', 'baf8dd2195ac967024bc34339123fd23fcef248e95889b8e311aa5397782bfaf', 1692001183),
(2377, '1692001665676681.png', '圣罗兰Logo1.png', 'brand', 1651, '.png', 'image', '/static/upload/images/brand/2023/08/14/1692001665676681.png', 'a76daf01bff7e50ecb6e9c363c1c573d282225057d7f9801501fede62a6298bb', 1692001665),
(2378, '1692001814915676.png', '圣罗兰11.png', 'brand', 3618, '.png', 'image', '/static/upload/images/brand/2023/08/14/1692001814915676.png', 'e4a0fd59dad1bd2866b997781910a2e7360cfb1b6470d2bf1d2c4f3c0e615372', 1692001814),
(2379, '1692002225149281.png', '纪诗哲Logo.png', 'brand', 13450, '.png', 'image', '/static/upload/images/brand/2023/08/14/1692002225149281.png', '98ad80f80a28f0d9408c3b645b085b8b96199c6f61a486665daa16a3b72a8758', 1692002225),
(2381, '1692002344829640.png', '纪诗哲商品.png', 'goods', 865404, '.png', 'image', '/static/upload/images/goods/2023/08/14/1692002344829640.png', '12d86c6c0c477c49eece6cdd9a72bd405700a1a7e374b1a30c087fd09cd5c5a7', 1692002344),
(2382, '1692002536736863.jpeg', '2e7869813e67269b.jpeg', 'goods', 164824, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692002536736863.jpeg', '21b0b8b6ab91314878c9c42cfe12028659a18185a17835a5df28e4fe0ef3245d', 1692002536),
(2383, '1692002546723224.jpeg', '68bacc6d8cd79bd1.jpeg', 'goods', 194768, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692002546723224.jpeg', 'c85479e69f91d1a2fe9b152941e752c309bf723e92d65fda9c6b4635f9baf9c5', 1692002546),
(2384, '1692002553395716.jpeg', '811e2ba666a99ccf.jpeg', 'goods', 105544, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692002553395716.jpeg', '749c0729fae90df6caa9dde66f761149083d1e22e7fdfc90d7d659b536422108', 1692002553),
(2385, '1692002562225652.jpeg', '19882f9a4511ca20.jpeg', 'goods', 213063, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692002562225652.jpeg', '0d08c423f025dbfbce735c264e61c8fc8e88afa63c886e1244ced92a337e6a30', 1692002562),
(2386, '1692002568893939.jpeg', '896511fd09de3ff3.jpeg', 'goods', 177005, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692002568893939.jpeg', '99dd686aa821d15d0dc2447018a0713813a9754c09cf4afe806e30e3e7a5cc1f', 1692002568),
(2387, '1692002578810253.jpeg', 'b71623404663d055.jpeg', 'goods', 120155, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692002578810253.jpeg', '0312cb9b1eecd42a5f70c2511f48cec4ebc78b83e8505fd8e1e509f5d4e1a557', 1692002578),
(2388, '1692002587392229.jpeg', 'bfcb3bd2daf6474f.jpeg', 'goods', 214308, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692002587392229.jpeg', 'a2e6ddd807806d1522725f444421a890a239af45d329b23f7eb57a496a4ed7ae', 1692002587),
(2389, '1692002594491922.jpeg', 'c4af8cc1d500081b.jpeg', 'goods', 133565, '.jpeg', 'image', '/static/upload/images/goods/2023/08/14/1692002594491922.jpeg', '7df99a5b1ef44eccc1672f720a3ea62c7063db72d0b7a622fa3f72006adfb49c', 1692002594),
(2391, '1692002974459810.png', '蔻驰商品2.png', 'goods', 549979, '.png', 'image', '/static/upload/images/goods/2023/08/14/1692002974459810.png', '43814e5617fd7f4fcaaa827b042c9467234e8357adb48513c56841d4ae720a1e', 1692002974),
(2392, '1692003365184980.png', '截屏2023-08-14 16.50.31.png', 'goods', 291228, '.png', 'image', '/static/upload/images/goods/2023/08/14/1692003365184980.png', 'dd42aea3723686097fa04afc39d50b5bde433f39f62f9782809d2261e2519abc', 1692003365),
(2393, '1692003387395668.png', '截屏2023-08-14 16.50.43.png', 'goods', 35486, '.png', 'image', '/static/upload/images/goods/2023/08/14/1692003387395668.png', '266613b590868718e4a03f5c1439c209969ad81d1a278210e6ff63d064121c03', 1692003387),
(2394, '1692003399805476.png', '截屏2023-08-14 16.50.51.png', 'goods', 478710, '.png', 'image', '/static/upload/images/goods/2023/08/14/1692003399805476.png', 'ba484fe9e844a6a2c1fec6ade4657422f87e8d9a8d3e8031325c3cef5355e74e', 1692003399),
(2395, '1692003420445359.png', '截屏2023-08-14 16.51.00.png', 'goods', 435939, '.png', 'image', '/static/upload/images/goods/2023/08/14/1692003420445359.png', '8b81da683ac9ff4422734456d2f027a5be2819ff2d0fee748d1309e14f77a2f0', 1692003420),
(2396, '1692003445795336.png', '截屏2023-08-14 16.51.09.png', 'goods', 344660, '.png', 'image', '/static/upload/images/goods/2023/08/14/1692003445795336.png', 'b45506c707fd75c62c6b83553373d8e7e06e82cd72c8bf1dba17ae0174f52260', 1692003445),
(2397, '1692003482341718.png', '截屏2023-08-14 16.51.35.png', 'goods', 246072, '.png', 'image', '/static/upload/images/goods/2023/08/14/1692003482341718.png', '01c3159b38943e95ed78e6db4cba9d9c552d62f745549e5e59aeedf08179f9ef', 1692003482),
(2398, '1692064717515871.png', 'left1服饰a.png', 'goods_category', 124044, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692064717515871.png', '2d6e5806f68873e0a83e74e4d118d2e739b0abe914889b159f66dedd7026a11e', 1692064717),
(2399, '1692065078113971.png', 'left1服饰a.png', 'goods_category', 101157, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692065078113971.png', '9a0a7bcd8e68adb74f7b329bec2c99b6b78c6dbb5e8d997e3261b6afcd6d22b5', 1692065078),
(2400, '1692070178969916.png', '包.png', 'goods_category', 148462, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692070178969916.png', '0e1bd7357d256f45b909f3a0f736bfbace4c2d885b9a6c0df68aecd2efa5466c', 1692070178),
(2402, '1692070567769447.png', '圣罗兰商品15.png', 'goods', 445617, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692070567769447.png', '4c52827b854bc93f39cf2c09ef52958e34842c34089390b49359624ab8439240', 1692070567),
(2404, '1692070668885949.png', '新秀丽商品15.png', 'goods', 136383, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692070668885949.png', '6cd086e38cc0745d41f581b2e5450d932169468de79687331bf338ee68e09f59', 1692070668),
(2405, '1692071794165341.png', '珑骧logo.png', 'brand', 1627, '.png', 'image', '/static/upload/images/brand/2023/08/15/1692071794165341.png', '0fc8f8a417ee5b5b697429f59999ad2191b998c693923700071c8955b793167f', 1692071794),
(2406, '1692072099338167.png', '珑骧logo.png', 'brand', 1627, '.png', 'image', '/static/upload/images/brand/2023/08/15/1692072099338167.png', '0fc8f8a417ee5b5b697429f59999ad2191b998c693923700071c8955b793167f', 1692072099),
(2408, '1692072217123569.png', '珑骧商品.png', 'goods', 217516, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692072217123569.png', '4e5d96f1c44945e0720cc42e18a0030760324d90fd2c953d2b6aec4e011d50f8', 1692072217),
(2409, '1692072701981145.png', '截屏2023-08-15 12.04.31.png', 'goods', 190517, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692072701981145.png', 'a31964e749ceced8737d306b6c207064dcaa2d9cdb4ff73d6c17c92fcc2b3efb', 1692072701),
(2410, '1692072710401442.png', '截屏2023-08-15 12.04.46.png', 'goods', 297904, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692072710401442.png', '56f0251b9bb97bd72a3efe5726261fb1d5f62c1815ecae8b27a3fdf507d0b675', 1692072710),
(2411, '1692072719327504.png', '截屏2023-08-15 12.05.47.png', 'goods', 147867, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692072719327504.png', '68fd771aaf5bbd87d3c08132c424fed4c09399a6c29474237cff8c3ee3269a28', 1692072719),
(2412, '1692072725221637.png', '截屏2023-08-15 12.05.55.png', 'goods', 233609, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692072725221637.png', '9c662e8e3e963c48dda3d74d77ef44028480cb085c1306cb82ffbb38d81266e8', 1692072725),
(2413, '1692072732101446.png', '截屏2023-08-15 12.06.08.png', 'goods', 303822, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692072732101446.png', '0adeb53ca43106a8b59a7e42c8d7cfbcfbd049b9c0d547c79903ba726c690065', 1692072732),
(2414, '1692072742531552.png', '截屏2023-08-15 12.06.17.png', 'goods', 212409, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692072742531552.png', 'fa7147bbbb37b652171c266a3cb25685bfe0543596f5db73ee14ace83c60fe4f', 1692072742),
(2415, '1692075641858267.png', 'PRADA.png', 'brand', 19272, '.png', 'image', '/static/upload/images/brand/2023/08/15/1692075641858267.png', 'ce4335be3bad43627dc393ad79b6e23b15f92507b9db99cacffc878a40986618', 1692075641),
(2417, '1692075761195908.png', 'PRADA商品.png', 'goods', 210363, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692075761195908.png', 'dd4c0bb6bed7b957cbaaddf8fe686b3518e8889e8eab503787c32bc42d85daba', 1692075761),
(2418, '1692076179708949.png', '截屏2023-08-15 13.03.17.png', 'goods', 337528, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692076179708949.png', '309d43384b5cf2650ecf418ac990ffe0c488f36dc89a303e7a17f822fdf8b56b', 1692076179),
(2419, '1692076188300709.png', '截屏2023-08-15 13.03.32.png', 'goods', 121119, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692076188300709.png', '453efef3f7efdff44b359606589370a7c95ad1a325c3c0ac1e8f6e290bd1dd73', 1692076188),
(2420, '1692076194697122.png', '截屏2023-08-15 13.03.49.png', 'goods', 35333, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692076194697122.png', '8ea5f56fafbc6306acb2290b7f2ef729d6f77efc94c6ea26f0f18b548eb8903c', 1692076194),
(2421, '1692076200985503.png', '截屏2023-08-15 13.03.58.png', 'goods', 719004, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692076200985503.png', '063a7007679cfc4713f6588a2127007956a7377a7800e7450e10be4df1ee6ef2', 1692076200),
(2423, '1692076213588120.png', '截屏2023-08-15 13.04.38.png', 'goods', 167615, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692076213588120.png', '2bfc9839768849e46cbba60beee9ddd08aea6cae53398372e6c4d591c0e33845', 1692076213),
(2424, '1692076219520642.png', '截屏2023-08-15 13.04.48.png', 'goods', 337444, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692076219520642.png', '0da1ade07c33b980a6d1c64360887e4f0b4b5c12482d1155ea716faddcfb0406', 1692076219),
(2426, '1692078380856633.png', '古驰商品11.png', 'goods', 267524, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692078380856633.png', '646584a056c4ebcdb0658d9131693fd5bff6ecdc96e297e45822791372431529', 1692078380),
(2427, '1692078936329508.png', '服饰banner.png', 'goods_category', 97654, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692078936329508.png', '29d3ac179de7102addc4b31d1ad3ba2f0bb759706e486f30b609b1394b8feaaa', 1692078936),
(2428, '1692079093135992.png', '七匹狼Logo.png', 'brand', 10132, '.png', 'image', '/static/upload/images/brand/2023/08/15/1692079093135992.png', '9140f147d95758bbd2926f4342935a10163b0aad7264c15454a53b1e172d4e74', 1692079093),
(2430, '1692079154656558.png', '七匹狼.png', 'goods', 398379, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692079154656558.png', 'e7f40ef7e1716de35782133a802eb8236b4ec37c6636a9a0b4ca24928cf4ca57', 1692079154),
(2431, '1692079376396702.jpeg', '362ae8e5b9862e3f.jpeg', 'goods', 185287, '.jpeg', 'image', '/static/upload/images/goods/2023/08/15/1692079376396702.jpeg', 'bd285025161596c98f4293e8dc1827af093d9c5da434a27460cadb9ac158183a', 1692079376),
(2432, '1692079383645253.jpeg', '31219b6040aaa923.jpeg', 'goods', 127647, '.jpeg', 'image', '/static/upload/images/goods/2023/08/15/1692079383645253.jpeg', '8c37bb77e9c81582fbb53b95066fbbabc09fd3d2f597748aa6d0906924ca9dbe', 1692079383),
(2433, '1692079396402382.jpeg', '104fd41433eacdb3.jpeg', 'goods', 100347, '.jpeg', 'image', '/static/upload/images/goods/2023/08/15/1692079396402382.jpeg', 'f2ca45affbce734a9606ba7465f291343ab3fa5f9a73fcb87a5edba5d89fbc62', 1692079396),
(2434, '1692079403516194.jpeg', '2119b3d91942a7ab.jpeg', 'goods', 73035, '.jpeg', 'image', '/static/upload/images/goods/2023/08/15/1692079403516194.jpeg', '5ecf5ad3744fc225394d2f43b03bcd4192928c97464c30638031c13c2d0b5c88', 1692079403),
(2435, '1692079408817649.jpeg', '4060f6ee9716eda4.jpeg', 'goods', 177775, '.jpeg', 'image', '/static/upload/images/goods/2023/08/15/1692079408817649.jpeg', '0a993bc68172fe7aef2d27eafd1981755ed7a528d21f8c20e4ddb6102538c570', 1692079408),
(2437, '1692079903152230.png', '鳄鱼Logo.png', 'brand', 6630, '.png', 'image', '/static/upload/images/brand/2023/08/15/1692079903152230.png', '08b1672d97a8702b0c7b7f522c720a50068d8eb425dd449cdc3d9c6ce52ff9a2', 1692079903),
(2439, '1692079963737575.png', '鳄鱼.png', 'goods', 732939, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692079963737575.png', '6e6709f1f4f5e2e468ad3569b95d04b6d5aa39db12fa5769189d2c39625a8904', 1692079963),
(2440, '1692080266261183.png', '截屏2023-08-15 14.14.13.png', 'goods', 803880, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692080266261183.png', 'a395efca1b7b75acb1a06364185ca0245e8b3689b0dc79f57e0422d1b6fbdc9d', 1692080266),
(2441, '1692080272322367.png', '截屏2023-08-15 14.14.26.png', 'goods', 730619, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692080272322367.png', '54d6f4c9e3aaee90961cbc596ba9201024dead9a46f0992936be7bf43b54fbe4', 1692080272),
(2442, '1692080277838680.png', '截屏2023-08-15 14.14.39.png', 'goods', 501349, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692080277838680.png', 'a14f0d05f21b065c4ac58ac4f65d6108e40fa14a8cfffaabfcdd56ce8166c3ac', 1692080277),
(2443, '1692080281756933.png', '截屏2023-08-15 14.15.04.png', 'goods', 394140, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692080281756933.png', '8942909e1807df9ec12c72491983190b00bb231255a556b87d5935ad8d3b1ca1', 1692080281),
(2444, '1692080285850637.png', '截屏2023-08-15 14.15.13.png', 'goods', 759833, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692080285850637.png', 'feb300b03a4d8a10d2091296aaf76f3615fea3b82e090b291ac62a284da9e100', 1692080285),
(2445, '1692080289803545.png', '截屏2023-08-15 14.15.22.png', 'goods', 269631, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692080289803545.png', 'ade3c7d01c4f3aba86be2c21cca216dcc7f5fe6cabb7d26c8b535231f43b756f', 1692080289),
(2447, '1692080737909286.png', '科罗蒙凯商品15.png', 'goods', 274317, '.png', 'image', '/static/upload/images/goods/2023/08/15/1692080737909286.png', '817f492e43023f877fe0698a171eb71d3c6315fe01a3575e306d1082b536fcbf', 1692080737),
(2448, '1692083230862352.png', '数码.png', 'goods_category', 3040, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083230862352.png', '22f1200936e86073aac8cfd65afb01e7c1604d2cd5130c493e64db217730fc4f', 1692083230),
(2449, '1692083246780484.png', '数码的副本.png', 'goods_category', 3197, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083246780484.png', '9c0b307fe26512ded08ba9f4f2483c7db24c7b5167303e80f5bcc15d6363e086', 1692083246),
(2452, '1692083365962236.png', '包包.png', 'goods_category', 3420, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083365962236.png', '4361839ce5c691fc5f0b4bea301ccfdfb79a46a817059ddc634b4d890a332d4f', 1692083365),
(2453, '1692083374446120.png', '包包 (1).png', 'goods_category', 3721, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083374446120.png', '4c1a52dde7fdb842f081897b19e5b1a9835f5ef6c94b6f5365e86d3aac3b629f', 1692083374),
(2454, '1692083394485796.png', '新娘化妆.png', 'goods_category', 3727, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083394485796.png', '8e24d091b36e7509e642fd4487118a98039cb2b9a2234096baa44c342e372691', 1692083394),
(2455, '1692083405644267.png', '新娘化妆 (1).png', 'goods_category', 4040, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083405644267.png', '6a6ac1e8189a613c14486d5b5fafc1086da13b8092ce1ded18a62f108a3e2707', 1692083405),
(2456, '1692083430922728.png', 'zhubaoshoubiao  (1).png', 'goods_category', 3746, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083430922728.png', 'a5e685047b73b002c14b3e5ec47243ae558399ab73c8c81c3a5ce19dd000a0fe', 1692083430),
(2457, '1692083442155794.png', 'zhubaoshoubiao .png', 'goods_category', 4045, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083442155794.png', '38451594c70f7723761700ba168393ced153cbd8e2b334095f4369f2cea3c47d', 1692083442),
(2458, '1692083465166195.png', '健身.png', 'goods_category', 4030, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083465166195.png', 'a2acb8390782ea4814e340efaa4f37886be256879a20c0f52b415e1e1461d5ae', 1692083465),
(2459, '1692083473937545.png', '健身 (1).png', 'goods_category', 4358, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083473937545.png', '0074ea7e581367ed9f163333cb5db1cc887f4befc9744e66004b15427bca84d6', 1692083473),
(2460, '1692083492952738.png', 'qiche (1).png', 'goods_category', 3602, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083492952738.png', 'a0833106095ec0b1e2a3c4b5e105d8c945888b40a4d559eab4adebfacc2ce7f5', 1692083492),
(2461, '1692083505296275.png', 'qiche.png', 'goods_category', 3878, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083505296275.png', 'b44e5a097a27eb44a08324d4bf54371cd1e049b0708c74588509ee01bfeea106', 1692083505),
(2462, '1692083524902686.png', '玩具 (1).png', 'goods_category', 4308, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083524902686.png', '125bd2ef65eb6ab0205be31855ab9aa4358d51fb215c13e4fc0224efb4c37b7b', 1692083524),
(2463, '1692083536757100.png', '玩具.png', 'goods_category', 4611, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083536757100.png', '563318d8fcd008c39c7490ed4d332b3b332944fea9e4f3eea9a78323597901ec', 1692083536),
(2464, '1692083555223187.png', '母婴 (1).png', 'goods_category', 4893, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083555223187.png', 'bef484535fe85e7bad861b02a19056a9b0fbbf8698ecefc0206670e42c8b606e', 1692083555),
(2465, '1692083563885614.png', '母婴.png', 'goods_category', 5288, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083563885614.png', 'f706bc5543b0efce608677b6cf62a4d9cdb9f9fabcf0491d49e9e5472367f4ed', 1692083563),
(2466, '1692083590253616.png', '缴费管理-01 (1).png', 'goods_category', 3426, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083590253616.png', '7bb3c874ae9f6db1aa832ce2e01cd829a7b7898d60dffc8112d396875d83ad62', 1692083590),
(2467, '1692083600747987.png', '缴费管理-01.png', 'goods_category', 3708, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692083600747987.png', 'dbdf452c9a89a0e03ceb5447b9f23afcaa7fa7434fec4d86d61543ae36b379b0', 1692083600),
(2470, '1692085007595445.png', '时尚服饰1.png', 'goods_category', 3057, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692085007595445.png', 'ce4b5ecc9e779aa83a0d9721a48c30c5ad8c5e357f6b111a4b9fc1fa020bebeb', 1692085007),
(2471, '1692085019787788.png', '时尚服饰1a.png', 'goods_category', 3381, '.png', 'image', '/static/upload/images/goods_category/2023/08/15/1692085019787788.png', '311f779acfb884e41c4848afa73f8bd7c5bfa03c27e9a5402e00aafa5db52d78', 1692085019),
(2513, '1692179763831840.jpg', '个护化妆.jpg', 'brand_category', 38683, '.jpg', 'image', '/static/upload/images/brand_category/2023/08/16/1692179763831840.jpg', '377d85320f5799a9c4734d030a5923fa50836adffdc48adeeeaef009285185a9', 1692179763),
(2514, '1692179764824230.jpg', '名品潮包.jpg', 'brand_category', 58839, '.jpg', 'image', '/static/upload/images/brand_category/2023/08/16/1692179764824230.jpg', '08f0c135c81f1a051f7079db30915b16308a5612122a4e8c2f1fab3f585b72f0', 1692179764),
(2515, '1692179764610132.jpg', '母婴用品.jpg', 'brand_category', 34110, '.jpg', 'image', '/static/upload/images/brand_category/2023/08/16/1692179764610132.jpg', 'a2e4ce1e2f0417c3748ae55cb254e32829916478a3fac34152ff05ca03f1eb09', 1692179764),
(2516, '1692179764925617.jpg', '汽车用品.jpg', 'brand_category', 46230, '.jpg', 'image', '/static/upload/images/brand_category/2023/08/16/1692179764925617.jpg', 'b80987ce554e3e500fa8ebcbc7e3aa06e8ed88f8ccf7d77b392c5ed98fb27fe6', 1692179764),
(2517, '1692179764550883.jpg', '生活服务.jpg', 'brand_category', 33975, '.jpg', 'image', '/static/upload/images/brand_category/2023/08/16/1692179764550883.jpg', '587f5182efe58e9d8dbf01b76c6bc5298d055e6e1642227e5ddcaf2b1b0acafd', 1692179764),
(2518, '1692179764631397.jpg', '时尚服饰.jpg', 'brand_category', 7986, '.jpg', 'image', '/static/upload/images/brand_category/2023/08/16/1692179764631397.jpg', '17378fbf6615c0ded5b67efdc66504fd661d37b0ed911ca17b965df4a966ae42', 1692179764),
(2519, '1692179764545573.jpg', '数码办公.jpg', 'brand_category', 43721, '.jpg', 'image', '/static/upload/images/brand_category/2023/08/16/1692179764545573.jpg', '4f711c5cb07fcb7171b8eaed5c81d71ded4d351482c7f0a88b4064c45dd9468d', 1692179764),
(2520, '1692179764975951.jpg', '玩具乐器.jpg', 'brand_category', 51562, '.jpg', 'image', '/static/upload/images/brand_category/2023/08/16/1692179764975951.jpg', '384b2dd4008504bc2911504c8089d17617023202dc3226a107701d93824e22ee', 1692179764),
(2521, '1692179764393600.jpg', '运动健康.jpg', 'brand_category', 25253, '.jpg', 'image', '/static/upload/images/brand_category/2023/08/16/1692179764393600.jpg', '2280c8e7d02398d5b2c130a15e5a13aace757446d8256756ad528c1a690d1bf6', 1692179764),
(2522, '1692179764441916.jpg', '珠宝手表.jpg', 'brand_category', 76863, '.jpg', 'image', '/static/upload/images/brand_category/2023/08/16/1692179764441916.jpg', '87f8274910403deb33fa515933b9a477d1d57a94d90b31ff4423aaa079ca9121', 1692179764),
(2523, '1692267246598639.mp4', '演示视频.mp4', 'goods', 773656, '.mp4', 'video', '/static/upload/video/goods/2023/08/17/1692267246598639.mp4', 'a31173841bfe1d16c2ff48ab24d954892f556eab9ab2b096e27b7a1788c65d3b', 1692267246),
(2524, '1692267725487534.mp4', '演示视频-2.mp4', 'goods', 3385459, '.mp4', 'video', '/static/upload/video/goods/2023/08/17/1692267725487534.mp4', '3a7e945cc356fbe0b0a5c47bccc53a0c38edeb2c10dafd260034e1af8ef6485b', 1692267725),
(2525, '1699440411819595.png', '轮播3.png', 'slide', 355964, '.png', 'image', '/static/upload/images/slide/2023/11/08/1699440411819595.png', 'a8cc228826e6980329924316b8b7e85c90ec0c3adbac4ffe607fd6f754b520ba', 1699440411),
(2526, '1699444440587695.png', '博文.png', 'app_nav', 2315, '.png', 'image', '/static/upload/images/app_nav/2023/11/08/1699444440587695.png', '650fba9f204e6ed828d9ec21cca20c00f5570ed93b31145c92716b3d45a3e912', 1699444440),
(2527, '1699444440279257.png', '店铺.png', 'app_nav', 2765, '.png', 'image', '/static/upload/images/app_nav/2023/11/08/1699444440279257.png', 'c84cd659ed90e6274ab1460c4a4323b865cd7f55c12c8af7fbf58bcc61ce1b7c', 1699444440),
(2528, '1699444440657693.png', '会员.png', 'app_nav', 2789, '.png', 'image', '/static/upload/images/app_nav/2023/11/08/1699444440657693.png', 'af1f2b654d2b51fbddb0a60c2a297df00429c8975f5d07e63e87d0127af64ed3', 1699444440),
(2529, '1699444440845257.png', '活动.png', 'app_nav', 2035, '.png', 'image', '/static/upload/images/app_nav/2023/11/08/1699444440845257.png', '1a6971f1e2b22855647c6097e927c35bbdbca873075ba0a63b7e8bac7b89c65f', 1699444440),
(2530, '1699444440178265.png', '积分.png', 'app_nav', 4363, '.png', 'image', '/static/upload/images/app_nav/2023/11/08/1699444440178265.png', '9334099ab87fb2235d677ea43ac8f6f821b6a6364848ae38c8be96bb27789173', 1699444440),
(2531, '1699444440276337.png', '秒杀.png', 'app_nav', 3175, '.png', 'image', '/static/upload/images/app_nav/2023/11/08/1699444440276337.png', '76ff365286ac660af6cb5e1b2a394d239a6d34e99f99bf34e60e18f9e97655fc', 1699444440),
(2532, '1699444440389285.png', '品牌.png', 'app_nav', 2751, '.png', 'image', '/static/upload/images/app_nav/2023/11/08/1699444440389285.png', '7d7e2ab4feae296dc04115538ca4c38c03f756dc21ff06540b7ececa0b155387', 1699444440),
(2533, '1699444440767151.png', '签到.png', 'app_nav', 2516, '.png', 'image', '/static/upload/images/app_nav/2023/11/08/1699444440767151.png', '52e09b0784517e31ddb4726a0b28db4ec62d3fe2aafd48be3da73d2c2c50ee41', 1699444440),
(2534, '1699444440189681.png', '问答.png', 'app_nav', 3071, '.png', 'image', '/static/upload/images/app_nav/2023/11/08/1699444440189681.png', '9f239728730239ede4cab013542a7bc7507fef097097292af4313f8dc6e38223', 1699444440),
(2535, '1699444440307617.png', '优惠券.png', 'app_nav', 3221, '.png', 'image', '/static/upload/images/app_nav/2023/11/08/1699444440307617.png', 'bac5bafd82fb3c87e524ec49113518122c9a529a439701ae59e76fa78cf08015', 1699444440),
(2536, '1699444441652354.png', '直播.png', 'app_nav', 2580, '.png', 'image', '/static/upload/images/app_nav/2023/11/08/1699444441652354.png', 'edf5660b6d0055ced2137da2122bd8f0f9a2256829adc9e9f758ab34b72c69b9', 1699444441),
(2537, '1699444441697516.png', '组合.png', 'app_nav', 1910, '.png', 'image', '/static/upload/images/app_nav/2023/11/08/1699444441697516.png', '698916625c01fb2edd2a19b36edac5abfee602925b444367d2e516cec1b7e0ee', 1699444441),
(2538, '1699457036179125.png', '个护化妆-实景图.png', 'goods_category', 10185, '.png', 'image', '/static/upload/images/goods_category/2023/11/08/1699457036179125.png', '9babeef42234fda4b416f36d1f8f7330e6de298b9dcdd4548997e5b515847e60', 1699457036),
(2539, '1699457036877667.png', '名品潮包-实景图.png', 'goods_category', 12828, '.png', 'image', '/static/upload/images/goods_category/2023/11/08/1699457036877667.png', 'e1e4ced4cf5213bc5119ff6ab3d86faa19d0d9d6d58f1546dbde963beb52a1cb', 1699457036),
(2540, '1699457036853449.png', '母婴用品-实景图.png', 'goods_category', 13155, '.png', 'image', '/static/upload/images/goods_category/2023/11/08/1699457036853449.png', '868e1b6d9d7c1b7bdde5f58d8ee37f8fea16af193d1880d92a67e8aabdb928b0', 1699457036),
(2541, '1699457036100017.png', '汽车用品-实景图.png', 'goods_category', 13489, '.png', 'image', '/static/upload/images/goods_category/2023/11/08/1699457036100017.png', 'd82b6b3cdc730c908c814182541a139a05ea0d6198056dd16235c8fcd5b09df1', 1699457036),
(2542, '1699457036375930.png', '生活服务-实景图.png', 'goods_category', 11563, '.png', 'image', '/static/upload/images/goods_category/2023/11/08/1699457036375930.png', '59dd8c82165f7b0658087581d280158efca9e37be360a01291b7be4e29f06ae7', 1699457036),
(2543, '1699457036977194.png', '时尚服饰-实景图.png', 'goods_category', 10676, '.png', 'image', '/static/upload/images/goods_category/2023/11/08/1699457036977194.png', 'a013796e043a0cc217679ad758644d71b3b77d1df1ca3db22a17f52bfec868a0', 1699457036),
(2544, '1699457036808561.png', '数码办公-实景图.png', 'goods_category', 8900, '.png', 'image', '/static/upload/images/goods_category/2023/11/08/1699457036808561.png', '4af3cc7820dcd38fca7f7b437ae6960e8cc720e6e09338b30c77b8e03bdf128f', 1699457036),
(2545, '1699457036304450.png', '玩具乐器-实景图.png', 'goods_category', 10812, '.png', 'image', '/static/upload/images/goods_category/2023/11/08/1699457036304450.png', '81b5afc73dbf465c7e830436d79a4d030ea53949f7f8d487e5b3f1e6a37be03b', 1699457036),
(2546, '1699457036206296.png', '运动健康-实景图.png', 'goods_category', 12460, '.png', 'image', '/static/upload/images/goods_category/2023/11/08/1699457036206296.png', '94a822ff52fd5ae6a83badfdf7a7bbef5e1d0af5de1f213277e58794a6bacdc4', 1699457036),
(2547, '1699457036435422.png', '珠宝手表-实景图.png', 'goods_category', 11247, '.png', 'image', '/static/upload/images/goods_category/2023/11/08/1699457036435422.png', '22d02586bd05d1b2cc51862e0e9e5f218c045622d8be2846e61612d5a0068270', 1699457036),
(2548, '1704705821615587.png', 'admin-login-ad.png', 'common', 394160, '.png', 'image', '/static/upload/images/common/2024/01/08/1704705821615587.png', '478d4affb64af172139fa67ac0c5088d8b1ee7aa2cb94006b219bb7c13b5de38', 1704705821);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_brand`
--

DROP TABLE IF EXISTS `sxo_brand`;
CREATE TABLE `sxo_brand` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `logo` char(255) NOT NULL DEFAULT '' COMMENT 'logo图标',
  `name` char(30) NOT NULL COMMENT '名称',
  `describe` char(255) NOT NULL DEFAULT '' COMMENT '描述',
  `website_url` char(255) NOT NULL DEFAULT '' COMMENT '官网地址',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '顺序',
  `seo_title` char(100) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` char(130) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_desc` char(230) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='品牌';

--
-- 转存表中的数据 `sxo_brand`
--

INSERT INTO `sxo_brand` (`id`, `logo`, `name`, `describe`, `website_url`, `is_enable`, `sort`, `seo_title`, `seo_keywords`, `seo_desc`, `add_time`, `upd_time`) VALUES
(1, '/static/upload/images/brand/2019/02/25/1551064260180298.jpeg', '强生', '强生公司一直注重在中国的发展,强生(中国)有限公司1992年1月在上海闵行经济技术开发区注册成立,注册资本超过一亿美元,是强生公司在中国大陆设立的首家独资企业。', 'https://www.johnsonsbaby.com.cn/', 1, 0, '', '', '', 1551064263, 1690034759),
(2, '/static/upload/images/brand/2019/02/25/1551064277207182.jpeg', '佳洁士', '佳洁士是享誉全球的口腔护理品牌,旗下有牙膏,牙刷,牙贴和漱口水等多种产品。上佳洁士官网,了解更多佳洁士产品和口腔保健的资讯。', 'https://www.crest.com.cn/', 1, 0, '', '', '', 1551064299, 1610346851),
(3, '', '阿迪达斯', '阿迪达斯一般指adidas。adidas（阿迪达斯）创办于1949年，是德国运动用品制造商阿迪达斯AG成员公司。以其创办人阿道夫·阿迪·达斯勒（Adolf Adi Dassler）。', 'https://www.adidas.com.cn/', 1, 0, '', '', '', 1610019864, 1610351690),
(8, '/static/upload/images/brand/2023/08/12/1691823127165367.png', '华为', '', '', 1, 0, '', '', '', 1691823135, 0),
(9, '/static/upload/images/brand/2023/08/12/1691823902414748.png', '苹果', '', '', 1, 0, '', '', '', 1691823909, 0),
(10, '/static/upload/images/brand/2023/08/12/1691824615829604.png', 'ViVo', '', '', 1, 0, '', '', '', 1691824649, 0),
(11, '/static/upload/images/brand/2023/08/12/1691828238575330.png', '惠普', '', '', 1, 0, '', '', '', 1691828270, 0),
(12, '/static/upload/images/brand/2023/08/12/1691831848237506.png', '联想', '', '', 1, 0, '', '', '', 1691831867, 0),
(13, '/static/upload/images/brand/2023/08/12/1691832967621264.png', '花花公子', '', '', 1, 0, '', '', '', 1691833083, 0),
(14, '/static/upload/images/brand/2023/08/12/1691835126265383.png', '南极人', '', '', 1, 0, '', '', '', 1691835147, 0),
(15, '/static/upload/images/brand/2023/08/12/1691835977239809.png', '斐乐', '', '', 1, 0, '', '', '', 1691835998, 0),
(16, '/static/upload/images/brand/2023/08/14/1691977709405973.png', 'MZOMXO', '', '', 1, 0, '', '', '', 1691977742, 0),
(17, '/static/upload/images/brand/2023/08/14/1691979547580992.png', 'SHIROMA', '', '', 1, 0, '', '', '', 1691979564, 0),
(18, '/static/upload/images/brand/2023/08/14/1691981092702792.png', '皮尔卡丹', '', '', 1, 0, '', '', '', 1691981114, 0),
(19, '/static/upload/images/brand/2023/08/14/1691982245246201.png', '科罗蒙凯', '', '', 1, 0, '', '', '', 1691982275, 0),
(20, '/static/upload/images/brand/2023/08/14/1691982936761169.png', '古驰', '', '', 1, 0, '', '', '', 1691982949, 0),
(21, '/static/upload/images/brand/2023/08/14/1691994279806663.png', '海蓝之家', '', '', 1, 0, '', '', '', 1691994296, 0),
(22, '/static/upload/images/brand/2023/08/14/1691995321426107.png', '森马', '', '', 1, 0, '', '', '', 1691995356, 0),
(23, '/static/upload/images/brand/2023/08/14/1691996707185755.png', '蔻驰', '', '', 1, 0, '', '', '', 1691996725, 0),
(24, '/static/upload/images/brand/2023/08/14/1691997896419442.png', '新秀丽', '', '', 1, 0, '', '', '', 1691997915, 0),
(25, '/static/upload/images/brand/2023/08/14/1691999031675571.png', 'viney', '女包潮牌', '', 1, 0, '', '', '', 1691999101, 0),
(26, '/static/upload/images/brand/2023/08/14/1692001814915676.png', '圣罗兰', '', '', 1, 0, '', '', '', 1692000843, 1692001820),
(27, '/static/upload/images/brand/2023/08/14/1692002225149281.png', '纪诗哲', '', '', 1, 0, '', '', '', 1692002232, 0),
(28, '/static/upload/images/brand/2023/08/15/1692072099338167.png', '珑骧', '', '', 1, 0, '', '', '', 1692072103, 0),
(29, '/static/upload/images/brand/2023/08/15/1692075641858267.png', 'PRADA', '', '', 1, 0, '', '', '', 1692075664, 0),
(30, '/static/upload/images/brand/2023/08/15/1692079093135992.png', '七匹狼', '', '', 1, 0, '', '', '', 1692079111, 0),
(31, '/static/upload/images/brand/2023/08/15/1692079903152230.png', '鳄鱼', '', '', 1, 0, '', '', '', 1692079921, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_brand_category`
--

DROP TABLE IF EXISTS `sxo_brand_category`;
CREATE TABLE `sxo_brand_category` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '分类id',
  `icon` char(255) NOT NULL DEFAULT '' COMMENT 'icon图标',
  `name` char(30) NOT NULL COMMENT '名称',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '顺序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='品牌分类';

--
-- 转存表中的数据 `sxo_brand_category`
--

INSERT INTO `sxo_brand_category` (`id`, `icon`, `name`, `is_enable`, `sort`, `add_time`, `upd_time`) VALUES
(7, '/static/upload/images/brand_category/2023/08/16/1692179764610132.jpg', '母婴用品', 1, 0, 0, 1692179827),
(10, '/static/upload/images/brand_category/2023/08/16/1692179764393600.jpg', '运动健康', 1, 0, 0, 1692179778),
(17, '/static/upload/images/brand_category/2023/08/16/1692179764441916.jpg', '珠宝手表', 1, 0, 1482840557, 1692179803),
(18, '/static/upload/images/brand_category/2023/08/16/1692179763831840.jpg', '个护化妆', 1, 0, 1482840577, 1692179813),
(25, '/static/upload/images/brand_category/2023/08/16/1692179764545573.jpg', '数码办公', 1, 0, 1535684676, 1692179839),
(26, '/static/upload/images/brand_category/2023/08/16/1692179764631397.jpg', '时尚服饰', 1, 0, 1535684688, 1692179847),
(27, '/static/upload/images/brand_category/2023/08/16/1692179764550883.jpg', '家居生活', 1, 0, 1535684701, 1692179908),
(28, '/static/upload/images/brand_category/2023/08/16/1692179764975951.jpg', '玩具乐器', 1, 0, 1535684707, 1692179949),
(29, '/static/upload/images/brand_category/2023/08/16/1692179764925617.jpg', '汽车用品', 1, 0, 1535684729, 1692179856),
(32, '/static/upload/images/brand_category/2023/08/16/1692179764824230.jpg', '名品潮包', 1, 0, 1692072013, 1692179885);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_brand_category_join`
--

DROP TABLE IF EXISTS `sxo_brand_category_join`;
CREATE TABLE `sxo_brand_category_join` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `brand_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '品牌id',
  `brand_category_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类id',
  `add_time` int(10) UNSIGNED DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='品牌分类关联';

--
-- 转存表中的数据 `sxo_brand_category_join`
--

INSERT INTO `sxo_brand_category_join` (`id`, `brand_id`, `brand_category_id`, `add_time`) VALUES
(29, 5, 10, 1598276166),
(51, 2, 18, 1610346851),
(55, 3, 10, 1610351690),
(56, 4, 16, 1690020306),
(57, 1, 7, 1690034759),
(58, 1, 18, 1690034759),
(59, 1, 27, 1690034759),
(60, 8, 25, 1691823135),
(61, 9, 25, 1691823909),
(62, 10, 25, 1691824649),
(63, 11, 25, 1691828270),
(64, 12, 25, 1691831867),
(65, 13, 26, 1691833083),
(66, 14, 26, 1691835147),
(67, 15, 26, 1691835998),
(68, 16, 26, 1691977742),
(69, 17, 26, 1691979564),
(70, 18, 26, 1691981114),
(71, 19, 26, 1691982275),
(72, 20, 26, 1691982949),
(73, 21, 26, 1691994296),
(74, 22, 26, 1691995356),
(75, 23, 26, 1691996725),
(76, 24, 26, 1691997915),
(77, 25, 26, 1691999101),
(80, 26, 26, 1692001820),
(81, 27, 26, 1692002232),
(82, 28, 32, 1692072103),
(83, 29, 32, 1692075664),
(84, 30, 26, 1692079111),
(85, 31, 26, 1692079921);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_cart`
--

DROP TABLE IF EXISTS `sxo_cart`;
CREATE TABLE `sxo_cart` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `user_id` int(10) UNSIGNED DEFAULT 0 COMMENT '用户id',
  `goods_id` int(10) UNSIGNED DEFAULT 0 COMMENT '商品id',
  `title` char(160) NOT NULL DEFAULT '' COMMENT '标题',
  `images` char(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `original_price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '原价',
  `price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '销售价格',
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '购买数量',
  `spec` text DEFAULT NULL COMMENT '规格',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='购物车';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_config`
--

DROP TABLE IF EXISTS `sxo_config`;
CREATE TABLE `sxo_config` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '基本设置id',
  `value` mediumtext DEFAULT NULL COMMENT '数据值',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `describe` char(255) NOT NULL DEFAULT '' COMMENT '描述',
  `error_tips` char(150) NOT NULL DEFAULT '' COMMENT '错误提示',
  `type` char(30) NOT NULL DEFAULT '' COMMENT '类型（admin后台, home前台）',
  `only_tag` char(60) NOT NULL DEFAULT '' COMMENT '唯一的标记',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='基本配置参数';

--
-- 转存表中的数据 `sxo_config`
--

INSERT INTO `sxo_config` (`id`, `value`, `name`, `describe`, `error_tips`, `type`, `only_tag`, `upd_time`) VALUES
(15, '30', '分页数量', '分页显示数量', '分页不能超过3位数', 'admin', 'common_page_size', 1705644570),
(59, '1', '订单商品扣减库存规则', '需扣减库存开启方可有效，默认订单支付成功', '', 'common', 'common_deduction_inventory_rules', 1693127007),
(60, '1', '是否扣减库存', '建议不要随意修改，以免造成库存数据错乱，关闭不影响库存回滚', '', 'common', 'common_is_deduction_inventory', 1693127007),
(11, '0', 'Excel编码', 'excel模块编码选择', '请选择编码', 'admin', 'admin_excel_charset', 1705644570),
(16, 'ShopXO企业级B2C电商系统提供商 - 演示站点', '站点标题', '浏览器标题，一般不超过80个字符', '站点标题不能为空', 'home', 'home_seo_site_title', 1699429135),
(17, '商城系统,开源电商系统,免费电商系统,PHP电商系统,商城系统,B2C电商系统,B2B2C电商系统', '站点关键字', '一般不超过100个字符，多个关键字以半圆角逗号 [ , ] 隔开', '站点关键字不能为空', 'home', 'home_seo_site_keywords', 1699429135),
(18, 'ShopXO是国内领先的商城系统提供商，为企业提供php商城系统、微信商城、小程序。', '站点描述', '站点描述，一般不超过200个字符', '站点描述不能为空', 'home', 'home_seo_site_description', 1699429135),
(19, '沪ICP备xxx号', 'ICP证书号', '如：沪ICP备xxx号', '', 'home', 'home_site_icp', 1713147178),
(20, '', '底部统计代码', '支持html，可用于添加流量统计代码', '', 'home', 'home_statistics_code', 0),
(21, '1', 'web端站点状态', '可暂时将站点关闭，其他人无法访问，但不影响管理员访问后台', '请选择web端站点状态', 'home', 'home_site_web_state', 1713147178),
(22, '升级中...', '关闭原因', '支持html，当网站处于关闭状态时，关闭原因将显示在前台', '', 'home', 'home_site_close_reason', 1713147178),
(23, 'Asia/Shanghai', '默认时区', '默认 亚洲/上海 [标准时+8]', '请选择默认时区', 'common', 'common_timezone', 1713147178),
(24, '<div style=\"background: rgb(255 224 224 / 60%);color: #f00;padding: 2px 5px;text-align:left;font-size: 12px;position: fixed;bottom: 0px;left: 0px;width: 195px;z-index: 100;border-top-right-radius: 3px;border: 1px solid #fc9797;\">\r\n演示站点请勿支付，可在后台站点配置->基础配置(底部代码)修改</div>', '底部代码', '支持html，可用于添加流量统计代码', '', 'home', 'home_footer_info', 1713147178),
(28, 'ShopXO', '站点名称', '', '站点名称不能为空', 'home', 'home_site_name', 1713147178),
(29, '0', '链接模式', '非兼容模式请确保配置好伪静态规则', '请选择url模式', 'home', 'home_seo_url_model', 1699429135),
(25, '204800000000', '图片最大限制', '单位B [上传图片还受到服务器空间PHP配置最大上传 20M 限制]', '请填写图片上传最大限制', 'home', 'home_max_limit_image', 1692097063),
(26, '512000000000', '文件最大限制', '单位B [上传文件还受到服务器空间PHP配置最大上传 20M 限制]', '请填写文件上传最大限制', 'home', 'home_max_limit_file', 1692097063),
(27, '1024000000000', '视频最大限制', '单位B [上传视频还受到服务器空间PHP配置最大上传 20M 限制]', '请填写视频上传最大限制', 'home', 'home_max_limit_video', 1692097063),
(30, 'html', '伪静态后缀', '链接后面的后缀别名，默认 [ html ]', '小写字母，不能超过8个字符', 'home', 'home_seo_url_html_suffix', 1699429135),
(31, '0', '用户注册开启审核', '默认关闭，开启后用户注册需要审核通过方可登录', '请选择用户注册开启审核', 'common', 'common_register_is_enable_audit', 1688461894),
(32, '/static/upload/images/common/2019/01/14/1547448748316693.png', '手机端logo', '支持 [jpg, png, gif]，建议220x66px', '请上传手机端网站logo', 'home', 'home_site_logo_wap', 1713147178),
(33, '/static/upload/images/common/2019/01/14/1547448705165706.png', '电脑端logo', '支持 [jpg, png, gif]，建议220x60px', '请上传电脑端网站logo', 'home', 'home_site_logo', 1713147178),
(34, '1200', '页面最大宽度', '页面最大宽度，单位px，0则100%', '请填写页面宽度值', 'home', 'home_content_max_width', 1600008688),
(35, '/static/upload/images/common/2019/01/14/1547448728921121.jpg', '正方形logo', '建议使用png格式，建议300x300px', '请上传正方形logo', 'common', 'home_site_logo_square', 1713147178),
(36, 'username,sms,email', '注册方式', '未选择、前端站点将无法注册，可选择 [ 短信, 邮箱, 用户名 ]', '请选择注册方式', 'home', 'home_user_reg_type', 1688461894),
(37, 'username,email,sms', '登录方式', '默认帐号密码，可选择 [ 帐号密码, 邮箱验证码, 手机验证码 ]', '请选择登录方式', 'home', 'home_user_login_type', 1688395276),
(38, '1', '获取验证码-开启图片验证码', '防止短信轰炸', '请选择是否开启强制图片验证码', 'common', 'common_img_verify_state', 1690031440),
(39, '60', '获取验证码时间间隔', '防止频繁获取验证码，一般在 30~120 秒之间，单位 [秒]', '请填写获取验证码时间间隔', 'home', 'common_verify_interval_time', 1690031440),
(40, '', '用户注册-短信模板ID', '验证码code', '请填写用户注册短信模板内容', 'home', 'home_sms_user_reg_template', 1693127455),
(41, '', '短信签名', '发送短信包含的签名', '短信签名 3~8 个的中英文字符', 'common', 'common_sms_sign', 1693127445),
(42, '', '短信KeyID', 'Access Key ID', '请填写Access Key ID', 'common', 'common_sms_apikey', 1693127445),
(43, '', '密码找回-短信模板ID', '验证码code', '请填写密码找回短信模板内容', 'home', 'home_sms_user_forget_pwd_template', 1693127455),
(44, '600', '验证码有效时间', '验证码过期时间，一般10分钟左右，单位 [秒]', '请填写验证码有效时间', 'home', 'common_verify_expire_time', 1690031440),
(45, '', 'SMTP服务器', '设置SMTP服务器的地址，如 smtp.163.com', '请填写SMTP服务器', 'common', 'common_email_smtp_host', 1693127472),
(46, '', 'SMTP端口', '设置SMTP服务器的端口，默认为 25', '请填写SMTP端口号', 'common', 'common_email_smtp_port', 1693127472),
(47, '', '发信人邮件地址', '发信人邮件地址，使用SMTP协议发送的邮件地址，如 shopxo@163.com', '请填写发信人邮件地址', 'common', 'common_email_smtp_account', 1693127472),
(48, '', 'SMTP身份验证用户名', '如 shopxo@163.com', '请填写SMTP身份验证用户名', 'common', 'common_email_smtp_name', 1693127472),
(49, '', 'SMTP身份验证密码', 'shopxo@163.com邮件的密码或授权码', '请填写SMTP身份验证密码', 'common', 'common_email_smtp_pwd', 1693127472),
(50, 'ShopXO', '发件人显示名称', '如 ShopXO', '', 'common', 'common_email_smtp_send_name', 1693127472),
(51, '', '通用-短信模板ID', '验证码code', '请填写通用短信模板内容', 'common', 'common_sms_currency_template', 1693127455),
(58, '', '短信KeySecret', 'Access Key Secret', '请填写Access Key Secret', 'common', 'common_sms_apisecret', 1693127445),
(53, '021-88888888', '商店电话', '空则不显示', '', 'common', 'common_customer_store_tel', 1713147160),
(56, '<p>通用模板，你的验证码是&nbsp;&nbsp;#code#</p>', '通用-邮件模板', '验证码变量标识符 [ #code# ]', '', 'common', 'common_email_currency_template', 1614775674),
(57, 'default', '默认模板', '前台默认模板', '请选择默认模板', 'common', 'common_default_theme', 1685070717),
(62, '', '百度地图api秘钥（浏览器端）', '百度地图的应用Key', '请填写百度地图api秘钥（浏览器端）', 'common', 'common_baidu_map_ak', 1705644570),
(63, '<p>用户注册，你的验证码是&nbsp;&nbsp;#code#</p>', '用户注册-邮件模板', '验证码变量标识符 [ #code# ]', '', 'home', 'home_email_user_reg_template', 1614775674),
(64, '<p>密码找回，你的验证码是&nbsp;&nbsp;#code#</p>', '密码找回-邮件模板', '验证码变量标识符 [ #code# ]', '', 'home', 'home_email_user_forget_pwd_template', 1614775674),
(65, '<p style=\"white-space: normal;\">邮箱绑定，你的验证码是&nbsp;&nbsp;#code#</p>', '邮箱绑定-邮件模板', '验证码变量标识符 [ #code# ]', '', 'home', 'home_email_user_email_binding_template', 1614775674),
(66, '20240425', 'css/js版本标记', '用于css/js浏览器缓存版本识别', '', 'home', 'home_static_cache_version', 1713147178),
(67, '', '手机号码绑定-短信模板ID', '验证码code', '请填写手机号码绑定短信模板内容', 'home', 'home_sms_user_mobile_binding_template', 1693127455),
(68, '连衣裙,帐篷,iphone,包包', '搜索关键字', '搜索框下热门关键字（输入回车）', '请填写关键字', 'home', 'home_search_keywords', 1691986290),
(69, '2', '搜索关键字类型', '自定义需要配置以下关键字', '请选择关键字类型', 'home', 'home_search_keywords_type', 1691986290),
(70, '0', '订单预约模式', '开启后用户提交订单需要管理员确认', '请选择是否开启预约模式', 'common', 'common_order_is_booking', 1693127007),
(71, 'ShopXO', '名称', '', '请填写名称', 'common', 'common_app_mini_alipay_title', 1652847626),
(72, '企业级B2C开源电商系统！', '描述', '', '请填写描述', 'common', 'common_app_mini_alipay_describe', 1652847626),
(73, '021-88888888', '客服电话', '空则不显示', '请填写客服电话', 'common', 'common_app_customer_service_tel', 1693128029),
(74, '', 'AppID', '小程序ID', '请填写AppID', 'common', 'common_app_mini_alipay_appid', 1652847626),
(75, '', '应用公钥', '', '请填写应用公钥', 'common', 'common_app_mini_alipay_rsa_public', 1652847626),
(182, '', '前缀', '默认 shopxo', '请填写前缀', 'common', 'common_cache_session_redis_prefix', 1626343978),
(76, '', '应用私钥', '', '请填写应用私钥', 'common', 'common_app_mini_alipay_rsa_private', 1652847626),
(171, '4', '首页楼层商品排序类型', '默认综合', '请选择首页楼层商品排序类型', 'home', 'home_index_floor_goods_order_by_type', 1693126976),
(173, '更多入口', '快捷导航名称', '默认 更多入口', '请填写快捷导航名称', 'home', 'home_navigation_main_quick_name', 1693127384),
(172, '0', '首页楼层商品排序规则', '默认降序(desc)', '请选择首页楼层商品排序规则', 'home', 'home_index_floor_goods_order_by_rule', 1693126976),
(188, 'default', '默认主题', '微信小程序默认主题', '请选择微信小程序默认主题', 'common', 'common_app_mini_weixin_default_theme', 1628599597),
(174, 'weixin,baidu,toutiao', '获取账户手机一键登录', '默认关闭', '请选择获取账户手机一键登录', 'common', 'common_user_onekey_bind_mobile_list', 1693127977),
(175, '1', '启用订单批量支付', '默认否', '请选择获启用订单批量支付', 'home', 'home_is_enable_order_bulk_pay', 1693127007),
(176, '0', 'Session使用缓存', '默认否', '请选择Session使用缓存', 'common', 'common_session_is_use_cache', 1626343978),
(177, '0', '数据使用缓存', '默认否', '请选择数据使用缓存', 'common', 'common_data_is_use_cache', 1626343978),
(78, '1', '是否启用搜索', '默认是', '', 'common', 'common_app_is_enable_search', 1693127977),
(77, '', '支付宝公钥', '', '请填写支付宝公钥', 'common', 'common_app_mini_alipay_out_rsa_public', 1652847626),
(167, '', 'css/js静态文件cdn域名', 'css/js静态文件', '请填写正确的css/js静态文件cdn域名', 'common', 'common_cdn_public_host', 1713147178),
(168, '8', '首页楼层商品数量', '默认8个', '请填写首页楼层商品数量', 'home', 'home_index_floor_goods_max_count', 1693126976),
(170, '{\"1\":\"手机,电脑\",\"2\":\"连衣裙,女装,男装\",\"3\":\"大牌,正品,新款\"}', '首页楼层顶部右侧关键字', '楼层下关键字（输入回车）', '请填写首页楼层顶部右侧关键字', 'home', 'home_index_floor_top_right_keywords', 1693126976),
(79, '0', '是否启用留言', '默认否', '', 'common', 'common_app_is_enable_answer', 1693127977),
(80, '3', '商品可添加规格最大数量', '建议不超过3个规格', '请填写谷歌最大数', 'common', 'common_spec_add_max_number', 1705644570),
(81, '-', '路由分隔符', '建议填写 [ - ]  默认 [ - ] ，仅PATHINFO模式+短地址模式下有效', '请填写路由分隔符', 'common', 'common_route_separator', 1693127480),
(82, '', 'AppID', '小程序ID', '请填写appid', 'common', 'common_app_mini_weixin_appid', 1699458088),
(83, '', 'AppSecret	', '小程序密钥', '请填写appsecret', 'common', 'common_app_mini_weixin_appsecret', 1699458088),
(84, 'ShopXO-weixin', '名称', '', '请填写名称', 'common', 'common_app_mini_weixin_title', 1699458088),
(85, '企业级B2C开源电商系统！', '描述', '', '请填写描述', 'common', 'common_app_mini_weixin_describe', 1699458088),
(61, '用户中心公告文字，后台手机管理、基础配置中修改，演示站点请勿下单。', '用户中心公告', '空则不显示公告', '', 'common', 'common_user_center_notice', 1693128029),
(8, '公告内容', '商城公告', '空则不显示公告', '', 'common', 'common_shop_notice', 1693128029),
(86, 'test@qq.com', '商店邮箱', '空则不显示', '客服邮箱格式有误', 'common', 'common_customer_store_email', 1713147160),
(87, '/static/upload/images/common/2019/04/09/1554805439263794.jpeg', '商店二维码', '空则不展示', '', 'common', 'common_customer_store_qrcode', 1713147160),
(152, '[{\"alias\":\"总部\",\"name\":\"devil\",\"tel\":\"13222333333\",\"lng\":\"121.594278\",\"lat\":\"31.207917\",\"address\":\"张江高科\",\"province\":\"9\",\"city\":\"155\",\"county\":\"1937\",\"province_name\":\"上海市\",\"city_name\":\"浦东新区\",\"county_name\":\"张江镇\",\"id\":0,\"logo\":\"\"},{\"logo\":\"\\/static\\/upload\\/images\\/common\\/2019\\/01\\/14\\/1547448728921121.jpg\",\"alias\":\"\",\"name\":\"sky\",\"tel\":\"021-88888888\",\"lng\":\"121.515632\",\"lat\":\"31.102277\",\"address\":\"浦江科技广场\",\"province\":\"9\",\"city\":\"152\",\"county\":\"1896\",\"province_name\":\"上海市\",\"city_name\":\"闵行区\",\"county_name\":\"浦江镇\",\"id\":1}]', '自提点地址', '', '请填写自提点地址', 'common', 'common_self_extraction_address', 1705636219),
(88, '上海市 闵行区 浦江科技广场', '商店地址', '空则不展示', '', 'common', 'common_customer_store_address', 1713147160),
(89, '<p>用户注册协议</p><p><br/></p>', '用户注册协议', '最多 105000 个字符', '用户注册协议最多 105000 个字符', 'common', 'common_agreement_userregister', 1690031778),
(90, '/static/upload/images/common/2019/05/17/1558025141249118.png', '用户注册背景图片', '', '请上传用户注册背景图片', 'home', 'home_site_user_register_bg_images', 1688461894),
(91, '/static/upload/images/common/2023/07/03/1688370464496578.png', '图片', '图片1 [ 建议使用 1920*350px ]', '', 'home', 'home_site_user_login_ad1_images', 1688395276),
(92, '/static/upload/images/common/2023/07/03/1688370477913669.png', '图片', '图片2 [ 建议使用 1920*350px ]', '', 'home', 'home_site_user_login_ad2_images', 1688395276),
(93, '', '图片', '图片2 [ 建议使用 1920*350px ]', '', 'home', 'home_site_user_login_ad3_images', 1688395276),
(272, '#d433f0', '用户注册背景色', '', '请选择用户注册背景色', 'home', 'home_site_user_register_bg_color', 1688461894),
(94, 'https://shopxo.net/', 'url地址', '地址1 [ 带http://或https:// ]', '', 'home', 'home_site_user_login_ad1_url', 1688395276),
(95, '', 'url地址', '地址2 [ 带http://或https:// ]', '', 'home', 'home_site_user_login_ad2_url', 1688395276),
(96, '', 'url地址', '地址3 [ 带http://或https:// ]', '', 'home', 'home_site_user_login_ad3_url', 1688395276),
(97, '#e93507', '背景色', '背景色1', '', 'home', 'home_site_user_login_ad1_bg_color', 1688395276),
(98, '#7a2ae9', '背景色', '背景色2', '', 'home', 'home_site_user_login_ad2_bg_color', 1688395276),
(99, '', '背景色', '背景色3', '', 'home', 'home_site_user_login_ad3_bg_color', 1688395276),
(100, '1', '登录图片验证码', '默认关闭，可以防止非法登录', '请选择是否开启登录图片验证码', 'home', 'home_user_login_img_verify_state', 1688395276),
(101, '/static/upload/images/common/2023/07/07/1688712376285299.png', '图片', '图片1 [ 建议使用 450*350px ]', '', 'home', 'home_site_user_forgetpwd_ad1_images', 1688713317),
(102, '/static/upload/images/common/2019/05/17/1558073623641199.jpg', '图片', '图片2 [ 建议使用 450*350px ]', '', 'home', 'home_site_user_forgetpwd_ad2_images', 1688713317),
(103, '', '图片', '图片2 [ 建议使用 450*350px ]', '', 'home', 'home_site_user_forgetpwd_ad3_images', 1688713317),
(104, 'https://shopxo.net/', 'url地址', '地址1 [ 带http://或https:// ]', '', 'home', 'home_site_user_forgetpwd_ad1_url', 1688713317),
(105, '', 'url地址', '地址2 [ 带http://或https:// ]', '', 'home', 'home_site_user_forgetpwd_ad2_url', 1688713317),
(106, '', 'url地址', '地址3 [ 带http://或https:// ]', '', 'home', 'home_site_user_forgetpwd_ad3_url', 1688713317),
(107, '#60dfff', '背景色', '背景色1', '', 'home', 'home_site_user_forgetpwd_ad1_bg_color', 1688713317),
(108, '#FAFAFA', '背景色', '背景色2', '', 'home', 'home_site_user_forgetpwd_ad2_bg_color', 1688713317),
(109, '', '背景色', '背景色3', '', 'home', 'home_site_user_forgetpwd_ad3_bg_color', 1688713317),
(110, '1', '用户注册图片验证码', '默认关闭，可以防止非法注册', '请选择是否开启用户注册图片验证码', 'home', 'home_user_register_img_verify_state', 1688461894),
(111, '', '图片验证码规则', '默认白底黑字，可根据需求i加大验证码识别难度', '', 'common', 'common_images_verify_rules', 1690031440),
(112, '1', 'SSL方式加密', '', '请选择是否使用SSL方式加密', 'common', 'common_email_is_use_ssl', 1693127472),
(113, '活动/优惠未生效\r\n空包裹\r\n包裹丢失\r\n配送超时\r\n未按约定时间发货\r\n未送货上门\r\n物流显示签收但实际未收到货\r\n不喜欢/不想要', '仅退款原因', '可换行，一行一个', '请填写仅退款原因', 'home', 'home_order_aftersale_return_only_money_reason', 1689846653),
(114, '7天无理由退换货\r\n配送超时\r\n未按约定时间发货\r\n未送货上门\r\n卖家发错货\r\n少件/漏发\r\n包装/商品破损/污渍\r\n商品信息描述不符\r\n使用后过敏\r\n已过/临近保质期\r\n无法溶解/结块/有异物', '退货退款原因', '可换行，一行一个', '请填写退货退款原因', 'home', 'home_order_aftersale_return_money_goods_reason', 1689846653),
(115, '1', '用户注册协议', '默认关闭，开启后用户注册需要同意协议才可以注册', '请选择是否启用用户注册协议', 'home', 'home_is_enable_userregister_agreement', 1688461894),
(116, '上海市闵行区浦江科技广场', '退货地址', '', '请填写退货地址', 'home', 'home_order_aftersale_return_goods_address', 1689846653),
(117, '0', '使用独立手机详情', '默认使用web详情', '请选择使用独立手机详情', 'common', 'common_app_is_use_mobile_detail', 1693127977),
(118, '0', '强制绑定手机', '默认否', '请选择是否强制绑定手机', 'common', 'common_user_is_mandatory_bind_mobile', 1693127977),
(121, '1', '固定顶部导航', '默认是、必须启用搜索', '请选择是否固定顶部导航', 'common', 'common_app_is_header_nav_fixed', 1693127977),
(122, '1', '开启在线客服', '默认否', '请选择是否开启在线客服', 'common', 'common_app_is_online_service', 1693127977),
(169, '10', '首页楼层左侧二级商品分类数量', '默认6个', '请填写首页楼层左侧二级商品分类数量', 'home', 'home_index_floor_left_goods_category_max_count', 1606316065),
(163, '30', '订单完成可发起售后时限', '单位 天，0则关闭售后、建议30天左右', '请填写订单完成可发起售后时限', 'common', 'home_order_aftersale_return_launch_day', 1689846653),
(240, '21600', '商品赠送积分时长', '单位 分钟，默认21600分钟/15天', '请填写商品赠送积分时长', 'common', 'common_goods_give_integral_limit_time', 1647520290),
(125, '', 'AppID', '智能小程序ID', '请填写AppID', 'common', 'common_app_mini_baidu_appid', 1693128332),
(126, '', 'AppKey', '智能小程序KEY', '请填写AppKey', 'common', 'common_app_mini_baidu_appkey', 1693128332),
(127, '', 'AppSecret', '智能小程序密匙', '请填写AppSecret', 'common', 'common_app_mini_baidu_appsecret', 1693128332),
(128, 'ShopXO', '名称', '', '请填写名称', 'common', 'common_app_mini_baidu_title', 1693128332),
(129, '企业级B2C开源电商系统！', '描述', '', '请填写描述', 'common', 'common_app_mini_baidu_describe', 1693128332),
(130, '0', '留言需要登录', '默认否', '请选择是否留言需要登录', 'common', 'common_is_login_answer', 1647520290),
(132, '30', '订单关闭脚本时长', '单位 分钟，默认30分钟', '请填写订单关闭脚本时长', 'common', 'common_order_close_limit_time', 1647520290),
(133, '21600', '订单自动收货脚本时长', '单位 分钟，默认21600分钟/15天', '请填写订单自动收货脚本时长', 'common', 'common_order_success_limit_time', 1647520290),
(134, '', 'AppID', '小程序ID', '请填写AppID', 'common', 'common_app_mini_toutiao_appid', 1693128338),
(135, '', 'AppSecret', '小程序Secret', '请填写AppSecret', 'common', 'common_app_mini_toutiao_appsecret', 1693128338),
(139, 'ShopXO-toutiao', '名称', '', '请填写名称', 'common', 'common_app_mini_toutiao_title', 1693128338),
(140, '企业级B2C开源电商系统！', '描述', '', '请填写描述', 'common', 'common_app_mini_toutiao_describe', 1693128338),
(141, '京公网安备xxx号', '公安备案号', '如：京公网安备xxx号', '请填写公安备案号', 'home', 'home_site_security_record_name', 1713147178),
(142, '', '公安备案地址', '备案展示页面的url地址', '请填写公安备案地址', 'home', 'home_site_security_record_url', 1713147178),
(143, '', 'AppID', '小程序ID', '请填写AppID', 'common', 'common_app_mini_qq_appid', 1693128345),
(144, '', 'AppSecret', '小程序Secret', '请填写AppSecret', 'common', 'common_app_mini_qq_appsecret', 1693128345),
(145, '', 'AppToken', '小程序Token', '请填写AppToken', 'common', 'common_app_mini_qq_apptoken', 1693128345),
(146, 'ShopXO', '名称', '', '请填写名称', 'common', 'common_app_mini_qq_title', 1693128345),
(147, '企业级B2C开源电商系统！', '描述', '', '请填写描述', 'common', 'common_app_mini_qq_describe', 1693128345),
(148, '1', '是否启用用户中心头部小导航', '默认是', '请选择是否启用用户中心头部小导航', 'common', 'common_app_is_head_vice_nav', 1693127977),
(151, '{\"pc\":\"4\",\"h5\":\"4\",\"ios\":\"4\",\"android\":\"4\",\"weixin\":\"4\",\"alipay\":\"4\",\"baidu\":\"4\",\"toutiao\":\"4\",\"qq\":\"4\",\"kuaishou\":\"4\"}', '站点类型', '默认快递', '请选择站点类型', 'common', 'common_site_type', 1705636219),
(150, '', '展示型操作名称', '默认 立即咨询', '请填写展示型操作名称', 'common', 'common_is_exhibition_mode_btn_text', 1705636219),
(153, '', '虚拟信息标题', '默认密钥信息', '请填写虚拟信息标题', 'common', 'common_site_fictitious_return_title', 1705636219),
(154, '', '提示信息', '', '请填写提示信息', 'common', 'common_site_fictitious_return_tips', 1705636219),
(155, '', '在线客服-企业编码', '空则不显示在线客服', '请填写在线客服-企业编码', 'common', 'common_app_mini_alipay_tnt_inst_id', 1652847626),
(156, '', '在线客服-聊天窗编码', '空则不显示在线客服', '请填写在线客服-聊天窗编码', 'common', 'common_app_mini_alipay_scene', 1652847626),
(157, '0', '商品详情页展示相册', '默认否', '请选择是否商品详情页展示相册', 'common', 'common_is_goods_detail_show_photo', 1692351536),
(158, '1', '手机简洁模式', '默认否', '请选择是否手机简洁模式', 'common', 'common_is_mobile_concise_model', 1693128029),
(159, '0', '启用直播', '默认否，需重新生成小程序包（启用则需到微信小程序后台申请权限）', '请选择是否启用直播', 'common', 'common_app_weixin_liveplayer', 1660449851),
(161, '1.3.0', '直播组件版本号', '', '请填写直播组件版本号', 'common', 'common_app_weixin_liveplayer_ver', 1660449851),
(162, '1', '后台登录页随机背景图', '默认启用', '请选择后台登录页随机背景图', 'common', 'admin_login_info_bg_images_rand', 1705644570),
(164, '30', '支付日志订单关闭脚本时长', '单位 分钟，默认30分钟', '请填写支付日志订单关闭脚本时长', 'common', 'common_pay_log_order_close_limit_time', 1647520290),
(165, '0', '分类展示层级', '默认 分类+商品', '请选择分类展示层级', 'common', 'common_show_goods_category_level', 1692351536),
(166, '', '附件cdn域名', '图片/视频/文件', '请填写正确的附件cdn域名', 'common', 'common_cdn_attachment_host', 1713147178),
(183, '', '连接地址', '默认 127.0.0.1', '请填写连接地址', 'common', 'common_cache_data_redis_host', 1626343978),
(184, '', '端口号', '默认 6379', '请填写端口号', 'common', 'common_cache_data_redis_port', 1626343978),
(185, '', '密码', '默认无密码', '请填写密码', 'common', 'common_cache_data_redis_password', 1626343978),
(186, '', '有效时间', '默认0表示永久', '请填写有效时间', 'common', 'common_cache_data_redis_expire', 1626343978),
(187, '', '前缀', '默认 shopxo', '请填写前缀', 'common', 'common_cache_data_redis_prefix', 1626343978),
(189, 'default', '默认主题', '支付宝小程序默认主题', '请选择支付宝小程序默认主题', 'common', 'common_app_mini_alipay_default_theme', 1572350417),
(190, 'default', '默认主题', '百度小程序默认主题', '请选择百度小程序默认主题', 'common', 'common_app_mini_baidu_default_theme', 1605944646),
(191, 'default', '默认主题', '头条小程序默认主题', '请选择头条小程序默认主题', 'common', 'common_app_mini_toutiao_default_theme', 1605945236),
(192, 'default', '默认主题', 'QQ小程序默认主题', '请选择QQ小程序默认主题', 'common', 'common_app_mini_qq_default_theme', 1605947370),
(193, '0', '首页楼层数据模式类型', '默认自动模式', '请选择首页楼层数据模式类型', 'home', 'home_index_floor_data_type', 1693126976),
(194, '{\"1\":[\"25\",\"32\",\"5\",\"6\",\"2\",\"1\",\"4\",\"3\"],\"2\":[\"11\",\"10\",\"8\",\"9\",\"100\",\"12\",\"109\",\"110\"],\"3\":[\"106\",\"104\",\"102\",\"101\",\"98\",\"7\",\"107\",\"108\"]}', '首页楼层商品配置', '自定义添加商品', '请选择首页楼层商品配置', 'home', 'home_index_floor_manual_mode_goods', 1693126976),
(195, '{\"1\":\"58,59,60,61,62,63\",\"2\":\"304,305,306,307,310,311\",\"3\":\"896,897,898,899,188,189\"}', '首页楼层左侧二级商品分类', '可以多选', '请填写首页楼层左侧二级商品分类', 'home', 'home_index_floor_left_top_category', 1693126976),
(196, '1', '快捷导航状态', '默认关闭', '请选择快捷导航状态', 'home', 'home_navigation_main_quick_status', 1693127384),
(197, '0', '用户地址地图', '默认关闭', '请选择用户地址地图', 'home', 'home_user_address_map_status', 1693127384),
(198, '0', '用户地址身份证', '默认关闭', '请选择用户地址身份证', 'home', 'home_user_address_idcard_status', 1693127384),
(199, '1', '首页轮播左侧商品分类', '默认开启', '请选择首页轮播左侧商品分类', 'home', 'home_index_banner_left_status', 1693126976),
(200, '1', '首页轮播右侧聚合内容', '默认开启', '请选择首页轮播右侧聚合内容', 'home', 'home_index_banner_right_status', 1693126976),
(202, '20', '搜索展示数据条数', '默认20', '请填写搜索展示数据条数', 'home', 'home_search_limit_number', 1691986290),
(201, '0', '手机模式下友情链接状态', '默认关闭', '请选择手机模式下友情链接状态', 'home', 'home_index_friendship_link_status', 1693127384),
(203, '1', '订单支付状态改变支付金额必须大于等于', '默认开启', '请选择订单支付状态改变支付金额必须大于等于', 'common', 'common_is_pay_price_must_max_equal', 1693127007),
(204, '1', '搜索页开启规格', '默认开启', '请选择搜索页开启规格', 'home', 'home_search_is_spec', 1691986291),
(205, '1', '搜索页开启参数', '默认开启', '请选择搜索页开启参数', 'home', 'home_search_is_params', 1691986291),
(206, '1', '搜索页开启价格', '默认开启', '请选择搜索页开启价格', 'home', 'home_search_is_price', 1691986291),
(207, '1', '顶部小导航非首页入口', '默认开启', '请选择顶部小导航非首页入口', 'home', 'home_header_top_is_home', 1693127384),
(208, '1', '搜索页开启品牌', '默认开启', '请选择搜索页开启品牌', 'home', 'home_search_is_brand', 1691986291),
(209, '1', '搜索页开启分类', '默认开启', '请选择搜索页开启分类', 'home', 'home_search_is_category', 1691986291),
(210, 'username', '登录方式', '默认帐号密码，可选择 [ 帐号密码, 邮箱验证码, 手机验证码 ]', '请至少选择一种登录方式', 'admin', 'admin_login_type', 1713147160),
(211, '0', '登录图片验证码', '默认关闭，可以防止非法登录', '请选择是否开启登录图片验证码', 'admin', 'admin_login_img_verify_state', 1705644570),
(212, '', '后台登录-短信模板ID', '验证码code', '请填写后台登录短信模板内容', 'admin', 'admin_sms_login_template', 1693127455),
(213, '<p>后台登录模板，你的验证码是&nbsp;&nbsp;#code#</p>', '后台登录-邮件模板', '验证码变量标识符 [ #code# ]', '', 'admin', 'admin_email_login_template', 1614775674),
(214, '', '用户登录-短信模板ID', '验证码code', '请填写用户登录短信模板内容', 'home', 'home_sms_login_template', 1693127455),
(215, '登录模板，你的验证码是  #code#', '用户登录-邮件模板', '验证码变量标识符 [ #code# ]', '', 'home', 'home_email_login_template', 1557728601),
(216, '1', '自提选择地理位置', '默认关闭', '请选择自提选择地理位置', 'home', 'home_extraction_address_position', 1693127384),
(217, '0', '搜索多个关键字并且关系', '默认否、或关系', '请选择搜索多个关键字并且关系', 'home', 'home_search_is_keywords_where_and', 1691986291),
(233, '0', '连接商店采用https', '默认http', '请选择连接商店采用https', 'common', 'common_is_https_connect_store', 1705644570),
(219, '0', '关闭商品优惠重叠', '默认否', '请选择关闭商品优惠重叠', 'common', 'is_close_goods_discount_overlap', 1690182022),
(221, 'Powered by <a href=\"https://www.freeb.vip/\" target=\"_blank\"><span class=\"b\">飞比智能</span><span class=\"o\">(Freeb)</span></a>', 'web端底部版权信息', '', '请填写web端底部版权信息', 'home', 'home_theme_footer_bottom_powered', 1618304831),
(224, '', '应用商店帐号', '', '请填写应用商店帐号', 'common', 'common_store_accounts', 1693119841),
(222, 'ShopXO', '后端站点名称', '', '请填写后端站点名称', 'admin', 'admin_theme_site_name', 1618304831),
(223, '2', '搜索参数类型', '默认基础', '请选择搜索参数类型', 'home', 'home_search_params_type', 1691986291),
(225, '', '应用商店密码', '', '请填写应用商店密码', 'common', 'common_store_password', 1693119841),
(282, '', '隐私弹窗说明', '空则系统默认通用说明', '请填写隐私弹窗说明', 'common', 'common_app_mini_weixin_privacy_content', 1699458088),
(226, '0', '搜索关键字包含SEO字段', '默认否', '请选择搜索关键字包含SEO字段', 'home', 'home_search_is_keywords_seo_fields', 1691986291),
(227, '<p>用户隐私政策内容</p>', '用户隐私政策', '最多 105000 个字符', '用户隐私政策最多 105000 个字符', 'common', 'common_agreement_userprivacy', 1690031857),
(231, '后台通知信息，系统配置中修改', '后台管理公告', '空则不显示、仅后台管理人员可见', '请填写后台管理公告', 'admin', 'admin_notice', 1705644570),
(229, '0', '退货地址使用仓库地址', '默认否、根据订单所属仓库', '请选择是否退货地址使用仓库地址', 'home', 'home_order_aftersale_is_use_warehouse_address', 1689846653),
(230, '1', '订单商品销量增加规则', '默认订单收货，请勿随意切换该配置、会造成商品销量不符', '请选择订单商品销量增加规则', 'common', 'common_goods_sales_count_inc_rules', 1693127007),
(232, '1', '展示商品评价', '默认是', '请选择是否展示商品评价', 'common', 'common_is_show_goods_comments', 1692351536),
(234, '', '手机端h5地址', 'uniapp端地址以(#/)结尾、比如：https://h5.shopxo.vip/#/', '请填写手机端h5地址', 'common', 'common_app_h5_url', 1693128029),
(235, '0', '线下支付正常进行', '默认否，线下支付提交进入正常订单状态流程、后续管理员可在后台确认操作收款', '请选择线下支付正常进行', 'common', 'common_is_under_line_order_normal', 1693127007),
(236, '增值电信业务经营许可证：沪B2-xxx', '增值电信业务经营许可证', '如：沪B2-xxx', '', 'home', 'home_site_telecom_license', 1713147178),
(237, '1', 'web端首页访问', '默认开启，仅针对web端首页，其他页面不受影响', '', 'home', 'home_site_web_home_state', 1713147178),
(238, '', '电子营业执照亮照', '执照页面展示地址、申请地址：https://zzlz.gsxt.gov.cn/businessShow', '', 'home', 'home_site_company_license', 1713147178),
(283, '/static/upload/images/common/2019/01/14/1547448748316693.png', '手机端logo', '支持 [jpg, png, gif]，建议60*60px', '请上传手机端网站logo', 'home', 'home_site_logo_app', 1713147178),
(239, '1', 'web端PC访问', '默认开启，仅针对web端PC', '', 'home', 'home_site_web_pc_state', 1713147178),
(250, '', '天地图api秘钥（浏览器端）', '天地图的应用Key', '请填写天地图api秘钥（浏览器端）', 'common', 'common_tianditu_map_ak', 1705644570),
(241, '', '主域名', '站点地址', '请填写正确的主域名', 'common', 'common_domain_host', 1713147178),
(242, 'weixin,alipay,baidu,toutiao,qq', '获取账户地址一键导入', '默认关闭', '请选择获取账户地址一键导入', 'common', 'common_user_address_platform_import_list', 1693127977),
(243, '', 'AppID', '小程序ID', '请填写AppID', 'common', 'common_app_mini_kuaishou_appid', 1652847650),
(244, '', 'AppSecret', '小程序Secret', '请填写AppSecret', 'common', 'common_app_mini_kuaishou_appsecret', 1652847650),
(245, 'ShopXO', '名称', '', '请填写名称', 'common', 'common_app_mini_kuaishou_title', 1652847650),
(246, '企业级B2C开源电商系统！', '描述', '', '请填写描述', 'common', 'common_app_mini_kuaishou_describe', 1652847650),
(247, '6dc8c668d7c28e64ab86ea35887e1c7f', '数据加密秘钥', '默认安装系统已自动生成、可以修改', '请填写数据加密秘钥', 'common', 'common_data_encryption_secret', 1705644570),
(248, '', 'Cookie有效域名', '默认空则是当前访问域名有效', '请填写Cookie有效域名', 'common', 'common_cookie_domain', 1705644570),
(249, '0', 'Excel导出类型', '默认CSV', '请选择Excel导出类型', 'common', 'common_excel_export_type', 1705644570),
(251, 'baidu', '地图类型', '默认百度地图', '请选择地图类型', 'common', 'common_map_type', 1705644570),
(252, '', '高德地图api秘钥（浏览器端）', '高德地图的应用Key', '请填写高德地图api秘钥（浏览器端）', 'common', 'common_amap_map_ak', 1705644570),
(253, '', '腾讯地图api秘钥（浏览器端）', '腾讯地图的应用Key', '请填写腾讯地图api秘钥（浏览器端）', 'common', 'common_tencent_map_ak', 1705644570),
(254, '', '高德地图安全秘钥', '高德地图的应用安全密钥', '请填写高德地图安全秘钥', 'common', 'common_amap_map_safety_ak', 1705644570),
(255, '{\"pc\":\"Lakala\",\"h5\":\"Alipay\",\"ios\":\"Lakala\",\"android\":\"\",\"weixin\":\"CashPayment\",\"alipay\":\"\",\"baidu\":\"\",\"toutiao\":\"CashPayment\",\"qq\":\"\",\"kuaishou\":\"Kuaishou\"}', '默认支付方式', '可对应平台设置', '请选择默认支付方式', 'common', 'common_default_payment', 1693127007),
(256, '0', '虚拟订单直接提交支付', '默认否，虚拟订单自动创建订单并直接进入订单列表发起支付、省去订单确认环节（请先设置默认支付方式）', '请选择是否虚拟订单直接提交支付', 'common', 'common_fictitious_order_direct_pay', 1693127007),
(257, '0', '开启搜索记录', '默认否', '请选择开启搜索记录', 'home', 'home_search_history_record', 1691986290),
(258, '<p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\">账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\"><br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\">账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\"><br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\"><br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; white-space: nowrap; top: 30px;\"><br/></p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\">账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\"><br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\">账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\"><br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\"><br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; white-space: nowrap; top: 30px;\"><br/></p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p>账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\">账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\"><br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\">账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\"><br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; top: 30px;\"><br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; white-space: nowrap; top: 30px;\">账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容<br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; white-space: nowrap; top: 30px;\"><br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; white-space: nowrap; top: 30px;\">账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容账号注销协议内容</p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; white-space: nowrap; top: 30px;\"><br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; white-space: nowrap; top: 30px;\"><br/></p><p style=\"position: absolute; width: 1px; height: 1px; overflow: hidden; left: -1000px; white-space: nowrap; top: 30px;\"><br/></p>', '账号注销协议', '最多 105000 个字符', '账号注销协议最多 105000 个字符', 'common', 'common_agreement_userlogout', 1668907622),
(259, '1', '主导航', '默认开启', '请选择主导航状态', 'home', 'home_main_header_status', 1693127384),
(260, '1', '顶部小导航', '默认开启', '请选择顶部小导航状态', 'home', 'home_main_top_header_status', 1693127384),
(261, '1', 'logo及搜索栏', '默认开启', '请选择logo及搜索栏状态', 'home', 'home_main_logo_search_status', 1693127384),
(262, '1', '面包屑导航', '默认开启', '请选择面包屑导航状态', 'home', 'home_main_breadcrumb_header_status', 1693127384),
(263, '1', '底部页脚内容', '默认开启', '请选择底部页脚内容状态', 'home', 'home_main_footer_content_status', 1693127384),
(264, '1', '后端使用多语言', '默认关闭', '请选择后端使用多语言', 'admin', 'admin_use_multilingual_status', 1693127384),
(265, '1', '前端使用多语言', '默认关闭', '请选择前端使用多语言', 'home', 'home_use_multilingual_status', 1693127384),
(266, 'zh,cht,en,spa', '可用多语言', '勾选则使用', '请勾选需要使用的语言', 'common', 'common_multilingual_choose_list', 1693127384),
(267, '0', '用户以系统类标识维度', '默认关闭', '请选择用户以系统类标识维度', 'common', 'common_user_unique_system_type_model', 1693127384),
(268, '0', '自动识别用户语言', '默认关闭', '请选择自动识别用户语言', 'common', 'common_multilingual_auto_status', 1693127384),
(269, '0', '默认语言', '默认中文', '请选择默认语言', 'common', 'common_multilingual_default_value', 1693127384),
(270, '', '域名绑定语言', '', '请配置域名绑定语言', 'common', 'common_domain_multilingual_bind_list', 1693127384),
(271, '0', '使用redis缓存', '默认否', '请选择是否使用redis缓存', 'common', 'common_data_is_use_redis_cache', 1558022648),
(273, '', '用户基础信息提示页面', '默认关闭', '请选择用户基础信息提示页面', 'common', 'common_app_user_base_popup_pages', 1693127977),
(274, '', '用户基础信息提示终端', '默认关闭', '请选择用户基础信息提示终端', 'common', 'common_app_user_base_popup_client', 1693127977),
(275, '1800', '用户基础信息提示间隔时间', '单位 秒、默认1800秒/30分钟', '请填写关闭后再次提示间隔时间', 'common', 'common_app_user_base_popup_interval_time', 1693127977),
(276, 'Devil', '退货联系人', '', '请填写退货联系人', 'home', 'home_order_aftersale_return_goods_contacts_name', 1689846653),
(277, '13888888888', '退货联系电话', '', '请填写退货联系电话', 'home', 'home_order_aftersale_return_goods_contacts_tel', 1689846653),
(278, '', '百度地图api秘钥（服务端）', '百度地图的应用Key', '请填写百度地图api秘钥（服务端）', 'common', 'common_baidu_map_ak_server', 1705644570),
(279, '', '腾讯地图api秘钥（服务端）', '腾讯地图的应用Key', '请填写腾讯地图api秘钥（服务端）', 'common', 'common_tencent_map_ak_server', 1705644570),
(280, '', '高德地图api秘钥（服务端）', '高德地图的应用Key和安全秘钥', '请填写高德地图api秘钥（服务端）', 'common', 'common_amap_map_ak_server', 1705644570),
(281, '', '天地图api秘钥（服务端）', '天地图的应用Key', '请填写天地图api秘钥（服务端）', 'common', 'common_tianditu_map_ak_server', 1705644570),
(284, '^1((3|4|5|6|7|8|9){1}\\d{1})\\d{8}$', '手机正则', '', '请填写手机正则', 'common', 'common_regex_mobile', 1559318982),
(285, '^\\d{3,4}-?\\d{8}$', '座机正则', '', '请填写座机正则', 'common', 'common_regex_tel', 1559318982),
(286, '^(\\d{15}$|^\\d{18}$|^\\d{17}(\\d|X|x))$', '身份证号码正则', '', '请填写身份证号码正则', 'common', 'common_regex_id_card', 1559318982),
(287, '', '微信小程序原始ID', '填写则APP分享到微信使用小程序', '请填写微信小程序原始ID', 'common', 'common_app_mini_weixin_share_original_id', 1559318982),
(288, '0', '同步微信发货', '默认关闭', '请选择是否同步微信发货', 'common', 'common_app_mini_weixin_upload_shipping_status', 1559318982),
(289, '/static/upload/images/common/2019/01/14/1547448748316693.png', '后台logo', '建议322*78px', '请上传后台logo', 'admin', 'admin_logo', 1705644570),
(290, '/static/upload/images/common/2019/01/14/1547448748316693.png', '后台登录logo', '建议220*60px', '请上传后台登录logo', 'admin', 'admin_login_logo', 1705644570),
(291, '/static/upload/images/common/2024/01/08/1704705821615587.png', '后台登录广告图片', '建议570*480px', '请上传后台登录广告图片', 'admin', 'admin_login_ad_images', 1705644570),
(292, '', '关闭手机端', '勾选则关闭', '请选择是否关闭手机端', 'home', 'home_site_app_state', 1713147178),
(293, '', '默认首页', '默认系统、仅web端', '请选择默认首页', 'common', 'common_site_default_index', 1559318982),
(294, '1', '显示商品售价', '', '请选择是否显示商品售价', 'common', 'common_goods_sales_price_status', 1559318982),
(295, '1', '显示商品原价', '', '请选择是否显示商品原价', 'common', 'common_goods_original_price_status', 1559318982),
(296, '0', '显示商品售价单位', '取值商品单位', '请选择是否显示商品售价单位', 'common', 'common_goods_sales_price_unit_status', 1559318982),
(297, '0', '显示商品原价单位', '取值商品单位', '请选择是否显示商品原价单位', 'common', 'common_goods_original_price_unit_status', 1559318982),
(298, '企业数字化、电商一体化解决方案！', '商店简介', '空则不展示', '', 'common', 'common_customer_store_describe', 1713147160);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_custom_view`
--

DROP TABLE IF EXISTS `sxo_custom_view`;
CREATE TABLE `sxo_custom_view` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `logo` char(255) NOT NULL DEFAULT '' COMMENT 'logo',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `html_content` longtext DEFAULT NULL COMMENT 'html代码',
  `css_content` longtext DEFAULT NULL COMMENT 'css样式',
  `js_content` longtext DEFAULT NULL COMMENT 'js代码',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `is_header` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否包含头部（0否, 1是）',
  `is_footer` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否包含尾部（0否, 1是）',
  `is_full_screen` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否满屏（0否, 1是）',
  `access_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '访问次数',
  `seo_title` char(100) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` char(130) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_desc` char(230) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='自定义页面';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_design`
--

DROP TABLE IF EXISTS `sxo_design`;
CREATE TABLE `sxo_design` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `logo` char(255) NOT NULL DEFAULT '' COMMENT 'logo',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `config` longtext DEFAULT NULL COMMENT '页面配置信息',
  `access_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '访问次数',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `is_header` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否含头部（0否，1是）',
  `is_footer` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否含尾部（0否，1是）',
  `seo_title` char(100) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` char(130) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_desc` char(230) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='页面设计';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_email_log`
--

DROP TABLE IF EXISTS `sxo_email_log`;
CREATE TABLE `sxo_email_log` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `smtp_host` char(180) NOT NULL DEFAULT '' COMMENT 'SMTP服务器',
  `smtp_port` char(10) NOT NULL DEFAULT '' COMMENT 'SMTP端口',
  `smtp_name` char(160) NOT NULL DEFAULT '' COMMENT '邮箱用户名',
  `smtp_account` char(160) NOT NULL DEFAULT '' COMMENT '发信人邮件',
  `smtp_send_name` char(160) NOT NULL DEFAULT '' COMMENT '发件人姓名',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态（0未发送，1已发送，2已失败）',
  `email` char(255) NOT NULL DEFAULT '' COMMENT '收件邮箱',
  `title` char(160) NOT NULL DEFAULT '' COMMENT '邮件标题',
  `template_value` mediumtext DEFAULT NULL COMMENT '邮件内容',
  `template_var` mediumtext DEFAULT NULL COMMENT '邮件变量（数组则json字符串存储）',
  `reason` char(255) NOT NULL DEFAULT '' COMMENT '失败原因',
  `tsc` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '耗时时间（单位秒）',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='邮件日志';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_express`
--

DROP TABLE IF EXISTS `sxo_express`;
CREATE TABLE `sxo_express` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id',
  `icon` char(255) NOT NULL DEFAULT '' COMMENT 'icon图标',
  `name` char(30) NOT NULL COMMENT '名称',
  `website_url` char(255) NOT NULL DEFAULT '' COMMENT '官网地址',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '顺序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='快递公司';

--
-- 转存表中的数据 `sxo_express`
--

INSERT INTO `sxo_express` (`id`, `pid`, `icon`, `name`, `website_url`, `is_enable`, `sort`, `add_time`, `upd_time`) VALUES
(1, 0, '/static/upload/images/express/images/20180917104528_logo.png', '顺丰快递', 'https://www.sf-express.com/', 1, 0, 1526350443, 1673094014),
(2, 0, '/static/upload/images/express/images/20180917104538_logo.png', '圆通快递', 'https://www.yto.net.cn/', 1, 0, 1526350453, 1673244554),
(3, 0, '/static/upload/images/express/images/20180917104550_logo.png', '申通快递', '', 1, 0, 1526350461, 1583070213),
(4, 0, '/static/upload/images/express/images/20180917104559_logo.png', '中通快递', '', 1, 0, 1526350469, 1537152359),
(5, 0, '/static/upload/images/express/images/20180917104839_logo.png', 'EMS速递', '', 1, 0, 1530429633, 1537152519),
(6, 0, '/static/upload/images/express/images/20180917104631_logo.png', '韵达快递', '', 1, 0, 1530429687, 1690020683),
(7, 0, '/static/upload/images/express/images/20180917104848_logo.png', '邮政包裹', '', 1, 0, 1530429743, 1537152528),
(8, 0, '/static/upload/images/express/images/20180917104816_logo.png', '百世汇通', '', 1, 0, 1530429765, 1537152496),
(9, 0, '/static/upload/images/express/images/20180917104616_logo.png', '国通快递', '', 1, 0, 1530429794, 1537152376),
(10, 0, '/static/upload/images/express/images/20180917104650_logo.png', '天天快递', '', 1, 0, 1530429830, 1537152410),
(11, 0, '/static/upload/images/express/images/20180917104707_logo.png', '优速快递', '', 1, 0, 1530429855, 1537152427),
(12, 0, '/static/upload/images/express/images/20180917104722_logo.png', '全峰快递', '', 1, 0, 1530429873, 1537152442),
(13, 0, '/static/upload/images/express/images/20180917104750_logo.png', '宅急送', '', 1, 0, 1530429907, 1537152470),
(14, 0, '/static/upload/images/express/images/20180917104757_logo.png', '京东快递', '', 1, 0, 1530429926, 1605775704);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_form_table_user_fields`
--

DROP TABLE IF EXISTS `sxo_form_table_user_fields`;
CREATE TABLE `sxo_form_table_user_fields` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员id或用户id',
  `user_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户类型（0后台管理员, 1用户端）',
  `md5_key` char(32) NOT NULL DEFAULT '' COMMENT 'form表格数据唯一key',
  `fields` text DEFAULT NULL COMMENT '字段数据（json格式存储）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='动态表格用户自定义字段';

--
-- 转存表中的数据 `sxo_form_table_user_fields`
--

INSERT INTO `sxo_form_table_user_fields` (`id`, `user_id`, `user_type`, `md5_key`, `fields`, `add_time`, `upd_time`) VALUES
(1, 1, 0, '03dbe746e511b06be44138bdaa6f7c0a', '[{\"label\":\"名称\",\"key\":\"name\",\"checked\":\"1\"},{\"label\":\"LOGO\",\"key\":\"payment-module-logo\",\"checked\":\"1\"},{\"label\":\"版本\",\"key\":\"version\",\"checked\":\"1\"},{\"label\":\"适用版本\",\"key\":\"apply_version\",\"checked\":\"1\"},{\"label\":\"适用终端\",\"key\":\"payment-module-apply_terminal\",\"checked\":\"1\"},{\"label\":\"作者\",\"key\":\"payment-module-author\",\"checked\":\"1\"},{\"label\":\"描述\",\"key\":\"desc\",\"checked\":\"1\"},{\"label\":\"是否启用\",\"key\":\"payment-module-enable\",\"checked\":\"1\"},{\"label\":\"用户开放\",\"key\":\"payment-module-open_user\",\"checked\":\"1\"}]', 1717946679, 1717946679);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods`
--

DROP TABLE IF EXISTS `sxo_goods`;
CREATE TABLE `sxo_goods` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `brand_id` int(10) UNSIGNED DEFAULT 0 COMMENT '品牌id',
  `site_type` tinyint(1) NOT NULL DEFAULT -1 COMMENT '商品类型（跟随站点类型一致[0销售, 1展示, 2自提, 3虚拟销售, 4销售+自提]）',
  `title` char(160) NOT NULL DEFAULT '' COMMENT '标题',
  `title_color` char(200) NOT NULL DEFAULT '' COMMENT '标题颜色',
  `simple_desc` char(230) NOT NULL DEFAULT '' COMMENT '简述',
  `model` char(30) NOT NULL DEFAULT '' COMMENT '型号',
  `place_origin` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '产地（地区省id）',
  `inventory` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '库存（所有规格库存总和）',
  `inventory_unit` char(15) NOT NULL DEFAULT '' COMMENT '库存单位',
  `images` char(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `original_price` char(60) NOT NULL DEFAULT '' COMMENT '原价（单值:10, 区间:10.00-20.00）一般用于展示使用',
  `min_original_price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '最低原价',
  `max_original_price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '最大原价',
  `price` char(60) NOT NULL DEFAULT '' COMMENT '销售价格（单值:10, 区间:10.00-20.00）一般用于展示使用',
  `min_price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '最低价格',
  `max_price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '最高价格',
  `give_integral` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '购买赠送积分比例',
  `buy_min_number` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '最低起购数量 （默认1）',
  `buy_max_number` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最大购买数量（最大数值 100000000, 小于等于0或空则不限）',
  `is_deduction_inventory` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否扣减库存（0否, 1是）',
  `is_shelves` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否上架（下架后用户不可见, 0否, 1是）',
  `content_web` mediumtext DEFAULT NULL COMMENT '电脑端详情内容',
  `photo_count` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '相册图片数量',
  `sales_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '销量',
  `access_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '访问次数',
  `video` char(255) NOT NULL DEFAULT '' COMMENT '短视频',
  `is_exist_many_spec` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否存在多个规格（0否, 1是）',
  `spec_base` text DEFAULT NULL COMMENT '规格基础数据',
  `fictitious_goods_value` text DEFAULT NULL COMMENT '虚拟商品展示数据',
  `seo_title` char(100) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` char(130) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_desc` char(230) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `is_delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已删除（0 未删除, 大于0则是删除时间）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品';

--
-- 转存表中的数据 `sxo_goods`
--

INSERT INTO `sxo_goods` (`id`, `brand_id`, `site_type`, `title`, `title_color`, `simple_desc`, `model`, `place_origin`, `inventory`, `inventory_unit`, `images`, `original_price`, `min_original_price`, `max_original_price`, `price`, `min_price`, `max_price`, `give_integral`, `buy_min_number`, `buy_max_number`, `is_deduction_inventory`, `is_shelves`, `content_web`, `photo_count`, `sales_count`, `access_count`, `video`, `is_exist_many_spec`, `spec_base`, `fictitious_goods_value`, `seo_title`, `seo_keywords`, `seo_desc`, `is_delete_time`, `add_time`, `upd_time`) VALUES
(1, 12, -1, '联想笔记本电脑小新16轻薄本 英特尔酷睿i5 16英寸超薄本(13代标压i5-13500H 16G 512G)灰 商务办公学生', '', '联想笔记本电脑小新16轻薄本 英特尔酷睿i5 16英寸超薄本(13代标压i5-13500H 16G 512G)灰 商务办公学生', '联想笔记本电脑小新16轻薄本', 1, 1517, '台', '/static/upload/images/goods/2023/08/12/1691832443984882.png', '9200.00', 9200.00, 9200.00, '2100.12', 2100.12, 2100.12, 10, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/12/1691832377600902.png\" title=\"1691832377600902.png\" alt=\"截屏2023-08-12 17.23.05.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691832382882584.png\" title=\"1691832382882584.png\" alt=\"截屏2023-08-12 17.23.34.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691832386387138.png\" title=\"1691832386387138.png\" alt=\"截屏2023-08-12 17.23.48.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691832391391925.png\" title=\"1691832391391925.png\" alt=\"截屏2023-08-12 17.24.06.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691832395282530.png\" title=\"1691832395282530.png\" alt=\"截屏2023-08-12 17.24.19.png\"/></p><p><br/></p><p><br/></p>', 1, 171, 6165, '', 0, '', '<p><a href=\"https://pan.baidu.com/s/18xyUNruvohr5JCdorvaz5w\" target=\"_blank\" style=\"box-sizing: border-box; background-color: rgb(255, 255, 255); color: rgb(0, 154, 97); text-decoration-line: none; font-family: \">https://pan.baidu.com/s/18xyUNruvohr5JCdorvaz5w</a><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: \">&nbsp; &nbsp; &nbsp;提取码&nbsp;</span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: \">v3y4-33</span></p>', '', '', '', 0, 1547450921, 1693126392),
(2, 8, -1, '华为笔记本电脑MateBook 14 2023 13代酷睿版 i5 16G 1T 14英寸轻薄办公本/2K触控全面屏/手机互联 深空灰', '', '华为笔记本电脑MateBook 14 2023 13代酷睿版 i5 16G 1T 14英寸轻薄办公本/2K触控全面屏/手机互联 深空灰', '华为笔记本电脑MateBook 14', 19, 266664, '台', '/static/upload/images/goods/2023/08/12/1691830293182918.png', '6000.00-6800.00', 6000.00, 6800.00, '4500.00-5500.00', 4500.00, 5500.00, 30, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/12/1691829803316804.png\" title=\"1691829803316804.png\" alt=\"截屏2023-08-12 16.40.01.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691829808460903.png\" title=\"1691829808460903.png\" alt=\"截屏2023-08-12 16.40.09.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691829813811407.png\" title=\"1691829813811407.png\" alt=\"截屏2023-08-12 16.40.19.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691829818501892.png\" title=\"1691829818501892.png\" alt=\"截屏2023-08-12 16.41.13.png\"/></p><p><br/></p><p><br/></p><p><br/></p>', 1, 199, 10127, '', 1, '', '', '', '', '', 0, 1547451624, 1693284683),
(3, 9, -1, 'Apple MacBook Pro 14英寸 M1 Pro芯片(8核中央处理器 14核图形处理器) 16G 512G 深空灰 笔记本 MKGP3CH/A', '', 'Apple MacBook Pro 14英寸 M1 Pro芯片(8核中央处理器 14核图形处理器) 16G 512G 深空灰 笔记本 MKGP3CH/A', 'Apple MacBook Pro 14英寸', 35, 888888, '台', '/static/upload/images/goods/2023/08/12/1691830511475725.png', '19000.00', 19000.00, 19000.00, '15900.00', 15900.00, 15900.00, 20, 1, 4, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/12/1691829236843759.jpeg\" title=\"1691829236843759.jpeg\" alt=\"c91987a552115e8a.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691829245863546.jpeg\" title=\"1691829245863546.jpeg\" alt=\"f23ebee50fae457c.jpeg\"/></p><p><br/></p>', 2, 215, 10427, '', 0, '', '', '', '', '', 0, 1547452007, 1693284931),
(4, 11, -1, '惠普（HP）战66 六代 2023酷睿14英寸(英特尔13代i5-1340P 16G 1T 长续航 高色域低功耗 AI新体验)高性能轻薄笔记本电脑', '', '惠普（HP）战66 六代 2023酷睿14英寸(英特尔13代i5-1340P 16G 1T 长续航 高色域低功耗 AI新体验)高性能轻薄笔记本电脑', '惠普惠普战66六代高性能轻薄商务本14英寸', 19, 878, '台', '/static/upload/images/goods/2023/08/12/1691831046790794.png', '2300.00', 2300.00, 2300.00, '1999.00', 1999.00, 1999.00, 19, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/12/1691828836385323.png\" title=\"1691828836385323.png\" alt=\"截屏2023-08-12 16.21.53.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691828844665561.png\" title=\"1691828844665561.png\" alt=\"截屏2023-08-12 16.22.06.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691828851964308.png\" title=\"1691828851964308.png\" alt=\"截屏2023-08-12 16.22.26.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691828857590839.png\" title=\"1691828857590839.png\" alt=\"截屏2023-08-12 16.22.51.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691828863550756.png\" title=\"1691828863550756.png\" alt=\"截屏2023-08-12 16.23.05.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691828867505745.png\" title=\"1691828867505745.png\" alt=\"截屏2023-08-12 16.23.23.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691828870712232.png\" title=\"1691828870712232.png\" alt=\"截屏2023-08-12 16.23.41.png\"/></p><p><br/></p><p><br/></p>', 2, 53, 2556, '', 0, '', '', '', '', '', 0, 1547452553, 1693237649),
(5, 8, -1, 'HUAWEI Mate 50 Pro 曲面旗舰 超光变XMAGE影像 北斗卫星消息 256GB 冰霜银 华为鸿蒙手机', '', 'HUAWEI Mate 50 Pro 曲面旗舰 超光变XMAGE影像 北斗卫星消息 256GB 冰霜银 华为鸿蒙手机', '', 19, 877, '台', '/static/upload/images/goods/2023/08/12/1691826776365755.png', '6800.00', 6800.00, 6800.00, '5000.00', 5000.00, 5000.00, 56, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/12/1691827475272976.png\" title=\"1691827475272976.png\" alt=\"截屏2023-08-12 15.53.51.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691827486979998.png\" title=\"1691827486979998.png\" alt=\"截屏2023-08-12 15.54.01.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691827499314526.png\" title=\"1691827499314526.png\" alt=\"截屏2023-08-12 15.54.14.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691827507900924.png\" title=\"1691827507900924.png\" alt=\"截屏2023-08-12 15.54.26.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691827515845673.png\" title=\"1691827515845673.png\" alt=\"截屏2023-08-12 15.55.14.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691827555731903.png\" title=\"1691827555731903.png\" alt=\"截屏2023-08-12 15.57.47.png\"/></p><p><br/></p>', 1, 181, 9237, '', 0, '', '', '', '', '', 0, 1547452798, 1693237221),
(6, 10, -1, 'vivo iQOO Neo8 12GB+256GB 冲浪 第一代骁龙8+ 自研芯片V1+ 120W超快闪充 144Hz高刷 5G游戏电竞性能手机', '', 'vivo iQOO Neo8 12GB+256GB 冲浪 第一代骁龙8+ 自研芯片V1+ 120W超快闪充 144Hz高刷 5G游戏电竞性能手机', 'vivo iQOO Neo', 19, 877, '台', '/static/upload/images/goods/2023/08/12/1691824925751938.png', '5400.00', 5400.00, 5400.00, '3500.00', 3500.00, 3500.00, 65, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/12/1691826204910905.png\" title=\"1691826204910905.png\" alt=\"截屏2023-08-12 15.34.50.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691826211530116.png\" title=\"1691826211530116.png\" alt=\"截屏2023-08-12 15.35.03.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691826216620275.png\" title=\"1691826216620275.png\" alt=\"截屏2023-08-12 15.35.17.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691826222270713.png\" title=\"1691826222270713.png\" alt=\"截屏2023-08-12 15.35.29.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691826226253803.png\" title=\"1691826226253803.png\" alt=\"截屏2023-08-12 15.35.42.png\"/></p><p><br/></p><p><br/></p>', 1, 146, 7872, '', 0, '', '', '', '', '', 0, 1547453135, 1693285023),
(7, 19, -1, 'CohnimKevin轻奢品牌女士包包女包单肩斜挎手提七夕情人节生日礼物送女友老婆 【时尚轻奢】c02054米白色', '', '七夕情人节生日礼物送女友老婆最佳选择', 'c02054米白色', 35, 877, '个', '/static/upload/images/goods/2023/08/15/1692080737909286.png', '700.00', 700.00, 700.00, '130.00', 130.00, 130.00, 11, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/14/1691982517423442.jpeg\" title=\"1691982517423442.jpeg\" alt=\"80bc7902d293e789.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691982541729356.jpeg\" title=\"1691982541729356.jpeg\" alt=\"26ba0e862c6c51d0.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691982548999591.jpeg\" title=\"1691982548999591.jpeg\" alt=\"4415c45dc5d01619.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691982552969620.jpeg\" title=\"1691982552969620.jpeg\" alt=\"020542b932c93818.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691982558477549.jpeg\" title=\"1691982558477549.jpeg\" alt=\"772934dec1af6306.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691982564622907.jpeg\" title=\"1691982564622907.jpeg\" alt=\"c674657dbf374805.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691982569733444.jpeg\" title=\"1691982569733444.jpeg\" alt=\"3641063eb694f484.jpeg\"/></p><p><br/></p>', 1, 60, 1917, '', 0, '', '', '', '', '', 0, 1547453967, 1693234922),
(8, 18, -1, '皮尔卡丹（pierre cardin）雪纺碎花连衣裙女装夏装2023年新款小个子初恋短裙气质收腰仙女裙 杏色 M-95-105斤', '', '皮尔卡丹（pierre cardin）雪纺碎花连衣裙女装夏装2023年新款小个子初恋短裙气质收腰仙女裙 杏色 M-95-105斤', '杏色 M-95-105斤', 19, 75, '件', '/static/upload/images/goods/2023/08/14/1691981199467207.png', '299.00-428.00', 299.00, 428.00, '268.00-356.00', 268.00, 356.00, 8, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/14/1691981552646443.jpeg\" title=\"1691981552646443.jpeg\" alt=\"f0c7c98f0e2d657a.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691981708429646.jpeg\" title=\"1691981708429646.jpeg\" alt=\"b194d13b209d8d51.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691981563790985.jpeg\" title=\"1691981563790985.jpeg\" alt=\"7d2857a3f7bd3b6e.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691981572751256.jpeg\" title=\"1691981572751256.jpeg\" alt=\"301b42184857f26f.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691981578921879.jpeg\" title=\"1691981578921879.jpeg\" alt=\"47ecec2e3b591018.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691981588510583.jpeg\" title=\"1691981588510583.jpeg\" alt=\"5c8029697913c145.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691981594441933.jpeg\" title=\"1691981594441933.jpeg\" alt=\"f70b6ceaf59bd1ad.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691981602797480.jpeg\" title=\"1691981602797480.jpeg\" alt=\"8f765f7675e2417f.jpeg\"/></p>', 1, 215, 10569, '', 1, '[{\"title\":\"颜色\",\"value\":[\"红色\",\"蓝色\"]}]', '<p><a href=\"https://pan.baidu.com/s/18xyUNruvohr5JCdorvaz5w\" target=\"_blank\" style=\"box-sizing: border-box; background-color: rgb(255, 255, 255); color: rgb(0, 154, 97); text-decoration-line: none; font-family: \">https://pan.baidu.com/s/18xyUNruvohr5JCdorvaz5w</a><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: \">&nbsp; &nbsp; &nbsp;提取码&nbsp;</span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: \">v3y4-33</span></p>', '', '', '', 0, 1547454269, 1693126392),
(9, 17, 4, 'SHIROMA 香港潮牌夏天显瘦长裙性感女装气质女神范衣服收腰修身包臀礼服无袖连衣裙 西瓜红 S', '', 'SHIROMA 香港潮牌夏天显瘦长裙性感女装气质女神范衣服收腰修身包臀礼服无袖连衣裙 西瓜红 S', '西瓜红 S', 33, 10666656, '件', '/static/upload/images/goods/2023/08/14/1691980079575635.png', '160.00', 160.00, 160.00, '120.00', 120.00, 120.00, 2, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/14/1691980637926632.jpeg\" title=\"1691980637926632.jpeg\" alt=\"afc398a681f799bd.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691980650796377.jpeg\" title=\"1691980650796377.jpeg\" alt=\"b389b005e23687d3.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691980676639236.jpeg\" title=\"1691980676639236.jpeg\" alt=\"c88f33e9ff202283.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691980683406570.jpeg\" title=\"1691980683406570.jpeg\" alt=\"c341c819b70e7d08.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691980688105551.jpeg\" title=\"1691980688105551.jpeg\" alt=\"d4bc071405542688.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691980696623584.jpeg\" title=\"1691980696623584.jpeg\" alt=\"46904123c8df65bc.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691980703617945.jpeg\" title=\"1691980703617945.jpeg\" alt=\"3a6b95cd1422b6bc.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691980708438566.jpeg\" title=\"1691980708438566.jpeg\" alt=\"5d145f272b217693.jpeg\"/></p><p><br/></p><p><br/></p><p><br/></p>', 1, 194, 9282, '/static/upload/video/goods/2023/08/17/1692267246598639.mp4', 1, '[{\"title\":\"颜色\",\"value\":[\"白色\",\"粉色\",\"黑色\"]},{\"title\":\"尺码\",\"value\":[\"S\",\"M\",\"L\",\"XL\"]}]', '', '', '', '', 0, 1547454786, 1693237071),
(10, 16, 1, '敲显瘦温柔长款羊毛连衣裙Polo领一片式系带针织长裙', '', '敲显瘦温柔长款羊毛连衣裙Polo领一片式系带针织长裙', '长款羊毛连衣裙Polo领', 9, 88888, '件', '/static/upload/images/goods/2023/08/14/1691977878897545.png', '568.00', 568.00, 568.00, '228.00', 228.00, 228.00, 20, 1, 0, 1, 1, '<p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: center;\"><span style=\"font-size: 18px;\"><strong><strong><span style=\"color: rgb(153, 51, 255);\">【品牌】欧单 学媛风 猫咪良品</span></strong></strong></span></p><p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: center;\"><span style=\"font-size: 18px;\"><strong><span style=\"color: rgb(153, 51, 255);\"><strong><strong>【吊牌】xueyuanfeng&nbsp;</strong></strong></span></strong></span><strong style=\"font-size: 18px; line-height: 27px;\"><strong><span style=\"color: rgb(153, 51, 255);\">猫咪良品</span></strong></strong></p><p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: center;\"><span style=\"font-size: 18px;\"><strong><strong><strong>【面料质地】涤棉</strong></strong></strong></span><span style=\"font-size: 18px;\"><strong><strong><strong>拼接蕾丝&nbsp;</strong></strong></strong></span><span style=\"font-size: 18px;\"><strong><strong><strong>&nbsp;</strong></strong></strong></span><strong style=\"font-size: 18px; line-height: 1.5;\"><strong><strong>后中拉链 有内衬</strong></strong></strong><strong style=\"font-size: 18px;\"><strong><strong><span style=\"font-family: 微软雅黑;\"><strong>（非专业机构鉴定，介意请慎拍）</strong></span></strong></strong></strong></p><p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: center;\"><strong><span style=\"font-size: 18px;\"><span style=\"color: rgb(153, 51, 255);\"></span><span style=\"color: rgb(153, 51, 255);\"></span><span style=\"color: rgb(153, 51, 255);\"><span style=\"background-color: rgb(255, 255, 0);\"><strong style=\"color: rgb(0, 0, 0);\"><span style=\"color: rgb(153, 51, 255);\">好的衣服需要好好呵护，务必请冷水手洗(切记别浸泡)拧干就晾晒或则干洗哦~</span></strong></span></span></span></strong></p><p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: center;\"><span style=\"font-size: 18px;\"><strong><span style=\"color: rgb(153, 51, 255);\"><strong><span style=\"color: rgb(153, 51, 153);\"><strong>【商品颜色】实物拍摄 蓝色 颜色很难拍有小色差属正常现象哦</strong></span></strong></span></strong></span></p><p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: center;\"><span style=\"font-size: 18px;\"><strong><span style=\"color: rgb(153, 51, 255);\"><strong><span style=\"color: rgb(153, 51, 153);\"></span></strong></span></strong></span><span style=\"font-size: 18px;\"><strong><span style=\"color: rgb(153, 51, 255);\"><strong><span style=\"color: rgb(153, 51, 153);\"></span><strong>【商品尺寸】XS/S/M/L 小高腰设计 胸口纽扣是装饰的哦</strong></strong></span></strong></span></p><p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: center;\"><span style=\"font-size: 18px;\"><strong><span style=\"color: rgb(153, 51, 255);\"></span></strong></span></p><p style=\"white-space: normal;\"><span style=\"color: rgb(255, 0, 0); font-family: 微软雅黑;\"><span style=\"font-size: 18px; line-height: 27px;\"></span></span></p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; text-align: center;\"><strong><strong><span style=\"color: rgb(64, 64, 64);\"><strong><span style=\"color: rgb(255, 0, 0);\"><span style=\"font-family: 微软雅黑;\">XS码尺寸: 悬挂衣长81CM.胸围80</span></span></strong></span><strong style=\"color: rgb(64, 64, 64);\"><span style=\"color: rgb(255, 0, 0);\"><span style=\"font-family: 微软雅黑;\">内合适</span></span></strong><span style=\"color: rgb(64, 64, 64);\"><strong><span style=\"color: rgb(255, 0, 0);\"><span style=\"font-family: 微软雅黑;\">.腰围63CM</span></span></strong></span><strong style=\"color: rgb(64, 64, 64);\"><span style=\"color: rgb(255, 0, 0);\"><span style=\"font-family: 微软雅黑;\">.臀围86CM</span></span></strong></strong></strong></p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; text-align: center;\"><strong><strong><span style=\"color: rgb(255, 0, 0);\">S码尺寸: 悬挂衣长82CM.胸围84</span></strong><strong style=\"line-height: 27px;\"><span style=\"color: rgb(255, 0, 0);\">内合适</span></strong><strong><span style=\"color: rgb(255, 0, 0);\">.腰围67CM</span></strong><strong style=\"line-height: 27px;\"><span style=\"color: rgb(255, 0, 0);\">.臀围90CM</span></strong></strong></p><p style=\"white-space: normal; text-align: center;\"><strong><strong><span style=\"color: rgb(255, 0, 0);\">M码尺寸: 悬挂衣长83CM.胸围88</span></strong><strong style=\"line-height: 27px;\"><span style=\"color: rgb(255, 0, 0);\">内合适</span></strong><strong><span style=\"color: rgb(255, 0, 0);\">.腰围71CM</span></strong><strong style=\"line-height: 27px;\"><span style=\"color: rgb(255, 0, 0);\">.臀围94CM</span></strong></strong></p><p style=\"white-space: normal; text-align: center;\"><strong><strong><span style=\"color: rgb(255, 0, 0);\">L码尺寸: 悬挂衣长84CM.胸围92</span></strong><strong style=\"line-height: 27px;\"><span style=\"color: rgb(255, 0, 0);\">内合适</span></strong><strong><span style=\"color: rgb(255, 0, 0);\">.腰围75CM</span></strong><strong style=\"line-height: 27px;\"><span style=\"color: rgb(255, 0, 0);\">.臀围98CM</span></strong></strong></p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; text-align: center;\"><strong style=\"font-size: 18px; line-height: 27px;\"><span style=\"color: rgb(255, 0, 0);\"><span style=\"font-family: 微软雅黑;\"><strong><strong style=\"color: rgb(0, 0, 0);\"><span style=\"color: rgb(153, 51, 255);\"><strong><span style=\"color: rgb(0, 0, 255);\"><strong><span style=\"color: rgb(255, 0, 0); font-family: 新宋体;\">（测量单位是CM，每个人的测量方式不一样，测量的尺寸数据可能会有1~3厘米的差异，请MM们谅解哦）</span></strong></span></strong></span></strong></strong></span></span></strong></p><p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: center;\"><strong><span style=\"color: rgb(153, 51, 255);\"><strong><span style=\"color: rgb(153, 51, 153);\"><span style=\"color: rgb(0, 0, 255);\"><span style=\"color: rgb(255, 0, 0); font-family: 新宋体;\"></span></span></span></strong></span></strong></p><p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: center;\"><strong style=\"color: rgb(64, 64, 64);\"><span style=\"color: rgb(68, 68, 68);\"><span style=\"background-color: rgb(92, 81, 80);\"><span style=\"background-color: rgb(255, 255, 255);\">PS：常规码数，可按平时号选择哦。修身</span></span></span></strong><strong style=\"line-height: 1.5; color: rgb(64, 64, 64);\"><span style=\"color: rgb(68, 68, 68);\"><span style=\"background-color: rgb(92, 81, 80);\"><span style=\"background-color: rgb(255, 255, 255);\">版型~如果上身偏大可以适当考虑大1号~下摆蕾丝拼接不会很平整的哦~</span></span></span></strong></p><p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: center;\"><strong>蕾丝花是手工修剪出来的，每件都有不同和不规则的哦，有小线头和节点是正常现象哦~请亲们谅解哦~</strong></p><p><br/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691979298102584.png\" title=\"1691979298102584.png\" alt=\"截屏2023-08-14 09.53.27.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691979323816227.png\" title=\"1691979323816227.png\" alt=\"截屏2023-08-14 09.54.10.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691979330939435.png\" title=\"1691979330939435.png\" alt=\"截屏2023-08-14 09.54.27.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691979342342357.png\" title=\"1691979342342357.png\" alt=\"截屏2023-08-14 09.54.36.png\"/></p>', 1, 52, 7690, '/static/upload/video/goods/2023/08/17/1692267725487534.mp4', 0, '', '', '', '', '', 0, 1547455375, 1693238016),
(11, 15, -1, 'FILA 斐乐官方女子连衣裙2023夏季新款简约V领网球运动短袖连身裙 RD宝蓝-NV 165/84A/M', '', 'FILA 斐乐官方女子连衣裙2023夏季新款简约V领网球运动短袖连身裙 RD宝蓝-NV 165/84A/M', 'NV 165/84A/M', 1, 2666664, '件', '/static/upload/images/goods/2023/08/12/1691836236770925.png', '0.00-422.00', 0.00, 422.00, '160.00-258.00', 160.00, 258.00, 0, 1, 0, 1, 1, '<p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: left;\"><img src=\"/static/upload/images/goods/2023/08/12/1691836485950943.jpeg\" title=\"1691836485950943.jpeg\" alt=\"50c31866f4514934.jpeg\"/></p><p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: left;\"><img src=\"/static/upload/images/goods/2023/08/12/1691836493757182.jpeg\" title=\"1691836493757182.jpeg\" alt=\"38ba5e598fcbaa86.jpeg\"/></p><p style=\"margin-top: 1.12em; margin-bottom: 1.12em; white-space: normal; padding: 0px; font-family: tahoma, arial, 宋体, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255); text-align: left;\"><img src=\"/static/upload/images/goods/2023/08/12/1691836499159372.jpeg\" title=\"1691836499159372.jpeg\" alt=\"b59f10cf74dd5d1d.jpeg\"/></p><p><br/></p>', 1, 200011, 10000701, '', 1, '', '', '', '', '', 0, 1547455700, 1693237903),
(12, 14, -1, '南极人（Nanjiren）裤子夏季男宽松休闲百搭裤华夫格长裤运动裤子潮流束脚裤休闲裤男', '', '南极人（Nanjiren）裤子夏季男宽松休闲百搭裤华夫格长裤运动裤子潮流束脚裤休闲裤男', '南极人35678', 19, 2000, '条', '/static/upload/images/goods/2023/08/12/1691835373537126.png', '1.00-673.00', 1.00, 673.00, '10.00-40.00', 10.00, 40.00, 100, 1, 0, 0, 1, '<p><img src=\"/static/upload/images/goods/2023/08/12/1691835697559233.png\" title=\"1691835697559233.png\" alt=\"截屏2023-08-12 18.17.06.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691835704154657.png\" title=\"1691835704154657.png\" alt=\"截屏2023-08-12 18.17.17.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691835708771308.png\" title=\"1691835708771308.png\" alt=\"截屏2023-08-12 18.17.27.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691835712880551.png\" title=\"1691835712880551.png\" alt=\"截屏2023-08-12 18.17.39.png\"/></p><p><br/></p>', 1, 27, 2047, '', 1, '', '', '', '连衣裙,裙子', '夏季连衣裙，瘦身裙子', 0, 1547456230, 1693237967),
(25, 9, -1, 'Apple iPhone 14 Pro Max (A2896) 256GB 暗紫色 支持移动联通电信5G 双卡双待手机', '', '支持移动联通电信5G 双卡双待手机', 'Apple iPhone 14 Pro Max', 35, 888888, '台', '/static/upload/images/goods/2023/08/12/1691824121231788.png', '9800.00', 9800.00, 9800.00, '3400.00', 3400.00, 3400.00, 0, 2, 4, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/12/1691825526466882.jpg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691825559410156.jpg\"/></p>', 1, 0, 262, '', 0, '', '', '', '', '', 0, 1651832307, 1693237146),
(32, 8, -1, '华为畅享 50z 5000万高清AI三摄 5000mAh超能续航 128GB 宝石蓝 大内存鸿蒙智能手机', '', '5000万高清AI三摄 5000mAh超能续航 128GB 宝石蓝 大内存鸿蒙智能手机', '华为华为畅享 50z', 9, 1517, '台', '/static/upload/images/goods/2023/08/12/1691823431903231.png', '3200.00', 3200.00, 3200.00, '2600.00', 2600.00, 2600.00, 10, 1, 0, 1, 1, '<p><br/></p><p><br/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691825240608986.jpg\"/></p><p><img src=\"https://img30.360buyimg.com/sku/jfs/t1/185684/32/36235/19245/64d1f415F481cce51/97374697a4f5d3ea.jpg.avif\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691825409695125.png\" title=\"1691825409695125.png\" alt=\"截屏2023-08-12 15.28.50.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691825418903590.png\" title=\"1691825418903590.png\" alt=\"截屏2023-08-12 15.29.15.png\"/></p><p><br/></p>', 1, 164, 5953, '', 0, '', '<p><a href=\"https://pan.baidu.com/s/18xyUNruvohr5JCdorvaz5w\" target=\"_blank\" style=\"box-sizing: border-box; background-color: rgb(255, 255, 255); color: rgb(0, 154, 97); text-decoration-line: none; font-family: \">https://pan.baidu.com/s/18xyUNruvohr5JCdorvaz5w</a><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: \">&nbsp; &nbsp; &nbsp;提取码&nbsp;</span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: \">v3y4-33</span></p>', '', '', '', 0, 1547450921, 1693237177),
(74, 13, 4, '花花公子（PLAYBOY）短袖T恤男2023夏季冰丝T恤男士立领休闲POLO打底衫上衣纯色衣服', '', '花花公子（PLAYBOY）短袖T恤男2023夏季冰丝T恤男士立领休闲POLO打底衫上衣纯色衣服', '短袖T恤男2023夏季冰丝', 19, 60, '件', '/static/upload/images/goods/2023/08/12/1691834999411165.png', '160.00', 160.00, 160.00, '120.00', 120.00, 120.00, 2, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/12/1691834871558142.png\" title=\"1691834871558142.png\" alt=\"截屏2023-08-12 17.54.04.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691834878159970.png\" title=\"1691834878159970.png\" alt=\"截屏2023-08-12 17.54.14.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691834882455325.png\" title=\"1691834882455325.png\" alt=\"截屏2023-08-12 17.54.23.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691834887521471.png\" title=\"1691834887521471.png\" alt=\"截屏2023-08-12 17.54.34.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/12/1691834891837211.png\" title=\"1691834891837211.png\" alt=\"截屏2023-08-12 17.54.47.png\"/></p><p><br/></p>', 1, 0, 220, '', 1, '[{\"title\":\"颜色\",\"value\":[\"白色\",\"粉色\",\"黑色\"]}]', '', '', '', '', 0, 1680668547, 1693238627),
(98, 20, -1, 'GUCCI古驰GG Marmont系列小号绗缝女士肩背包斜挎包 黑色 均码', '', 'GUCCI古驰GG Marmont系列小号绗缝女士肩背包斜挎包 黑色 均码', '均码', 35, 88888, '个', '/static/upload/images/goods/2023/08/15/1692078380856633.png', '28000.00', 28000.00, 28000.00, '26900.00', 26900.00, 26900.00, 0, 1, 5, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/14/1691983213889964.png\" title=\"1691983213889964.png\" alt=\"dae02c427f6d4ed0.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691983221350725.png\" title=\"1691983221350725.png\" alt=\"3b57b165392bf578.png\"/></p><p><br/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691983247500656.png\" title=\"1691983247500656.png\" alt=\"e12d5e7457909ce7.png\"/></p><p><br/></p><p><br/></p>', 1, 0, 14, '', 0, '', '', '', '', '', 0, 1691983301, 1693235317),
(99, 21, -1, 'HLA海澜之家短袖T恤男夏新疆棉微弹圆领数码印花t恤男', '', 'HLA海澜之家短袖T恤男夏新疆棉微弹圆领数码印花t恤男', '印花t恤男', 1, 88888, '件', '/static/upload/images/goods/2023/08/14/1691994396257476.png', '120.00', 120.00, 120.00, '88.00', 88.00, 88.00, 0, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/14/1691994898735499.png\" title=\"1691994898735499.png\" alt=\"截屏2023-08-14 14.28.02.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691994917804872.png\" title=\"1691994917804872.png\" alt=\"截屏2023-08-14 14.28.15.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691994924628415.png\" title=\"1691994924628415.png\" alt=\"截屏2023-08-14 14.28.36.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691994931354691.png\" title=\"1691994931354691.png\" alt=\"截屏2023-08-14 14.28.46.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691994935562238.png\" title=\"1691994935562238.png\" alt=\"截屏2023-08-14 14.29.06.png\"/></p>', 1, 0, 11, '', 0, '', '', '', '', '', 0, 1691994986, 1693236668),
(100, 22, -1, '棉致森马集团春季新款男士纯棉宽松工装牛仔上衣休闲外套男装夹克男 2201绿色 XL码（140-160斤）', '', '棉致森马集团春季新款男士纯棉宽松工装牛仔上衣休闲外套男装夹克男 2201绿色 XL码（140-160斤）', '2201绿色 XL码（140-160斤）', 21, 88888, '件', '/static/upload/images/goods/2023/08/14/1691995464796317.png', '200.00', 200.00, 200.00, '160.00', 160.00, 160.00, 0, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/14/1691995907479445.png\" title=\"1691995907479445.png\" alt=\"截屏2023-08-14 14.46.04.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691995915250299.png\" title=\"1691995915250299.png\" alt=\"截屏2023-08-14 14.46.39.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691995921520538.png\" title=\"1691995921520538.png\" alt=\"截屏2023-08-14 14.46.54.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691995926708720.png\" title=\"1691995926708720.png\" alt=\"截屏2023-08-14 14.47.02.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691995935519352.png\" title=\"1691995935519352.png\" alt=\"截屏2023-08-14 14.47.12.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691995941891876.png\" title=\"1691995941891876.png\" alt=\"截屏2023-08-14 14.47.21.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691995946950732.png\" title=\"1691995946950732.png\" alt=\"截屏2023-08-14 14.47.37.png\"/></p>', 1, 0, 19, '', 0, '', '', '', '', '', 0, 1691995961, 1693236598),
(101, 23, -1, '蔻驰（COACH）七夕礼物 女士中号手提单肩托特包卡其白色PVC4455IMDQC', '', '蔻驰（COACH）七夕礼物 女士中号手提单肩托特包卡其白色PVC4455IMDQC', '卡其白色PVC4455IMDQC', 35, 88888, '个', '/static/upload/images/goods/2023/08/14/1691996824307963.png', '16890.00', 16890.00, 16890.00, '13997.00', 13997.00, 13997.00, 0, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/14/1691997191254126.png\" title=\"1691997191254126.png\" alt=\"截屏2023-08-14 15.07.54.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691997198671732.png\" title=\"1691997198671732.png\" alt=\"截屏2023-08-14 15.08.06.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691997205743458.png\" title=\"1691997205743458.png\" alt=\"截屏2023-08-14 15.08.16.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691997211355818.png\" title=\"1691997211355818.png\" alt=\"截屏2023-08-14 15.08.29.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691997217563755.png\" title=\"1691997217563755.png\" alt=\"截屏2023-08-14 15.08.47.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691997220388481.png\" title=\"1691997220388481.png\" alt=\"截屏2023-08-14 15.08.57.png\"/></p><p><br/></p>', 1, 0, 9, '', 0, '', '', '', '', '', 0, 1691997246, 1693235925),
(102, 24, -1, '新秀丽（Samsonite）双肩包电脑包男士15.6英寸商务背包旅行包苹果笔记本书包 TX5黑色', '', '新秀丽（Samsonite）双肩包电脑包男士15.6英寸商务背包旅行包苹果笔记本书包 TX5黑色', '苹果笔记本书包 TX5黑色', 35, 88888, '个', '/static/upload/images/goods/2023/08/15/1692070668885949.png', '900.00', 900.00, 900.00, '680.00', 680.00, 680.00, 0, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/14/1691998232498590.png\" title=\"1691998232498590.png\" alt=\"截屏2023-08-14 15.27.47.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691998240310902.png\" title=\"1691998240310902.png\" alt=\"截屏2023-08-14 15.28.00.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691998248824258.png\" title=\"1691998248824258.png\" alt=\"截屏2023-08-14 15.28.13.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691998256740994.png\" title=\"1691998256740994.png\" alt=\"截屏2023-08-14 15.28.22.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691998264851776.png\" title=\"1691998264851776.png\" alt=\"截屏2023-08-14 15.28.32.png\"/></p><p><br/></p>', 1, 0, 13, '', 0, '', '', '', '', '', 0, 1691998269, 1693235663),
(103, 25, -1, 'Yves Saint LaurentYSL圣罗兰杨树林女包Monogram信封woc黑色鱼子酱链条包单肩斜挎包 金扣金链中号22.5*14*4 现货', '', 'Yves Saint LaurentYSL圣罗兰杨树林女包Monogram信封woc黑色鱼子酱链条包单肩斜挎包 金扣金链中号22.5*14*4 现货', '单肩斜挎包 金扣金链中号22.5*14*4 现货', 1, 88888, '个', '/static/upload/images/goods/2023/08/14/1691999334494128.png', '670.00', 670.00, 670.00, '489.00', 489.00, 489.00, 0, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/14/1691999752973519.png\" title=\"1691999752973519.png\" alt=\"截屏2023-08-14 15.49.32.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691999761383333.png\" title=\"1691999761383333.png\" alt=\"截屏2023-08-14 15.50.50.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691999770935537.png\" title=\"1691999770935537.png\" alt=\"截屏2023-08-14 15.51.02.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691999780130379.png\" title=\"1691999780130379.png\" alt=\"截屏2023-08-14 15.51.09.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691999786841156.png\" title=\"1691999786841156.png\" alt=\"截屏2023-08-14 15.51.22.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691999792436424.png\" title=\"1691999792436424.png\" alt=\"截屏2023-08-14 15.51.36.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1691999798629765.png\" title=\"1691999798629765.png\" alt=\"截屏2023-08-14 15.51.47.png\"/></p>', 1, 0, 13, '', 0, '', '', '', '', '', 0, 1691999830, 1693236446),
(104, 26, -1, 'Yves Saint LaurentYSL圣罗兰杨树林女包Monogram信封woc黑色鱼子酱链条包单肩斜挎包 金扣金链中号22.5*14*4 现货', '', '黑色鱼子酱链条包单肩斜挎包', '单肩斜挎包 金扣金链中号22.5*14*4 现货', 35, 88888, '个', '/static/upload/images/goods/2023/08/14/1692000963306994.png', '13000.00', 13000.00, 13000.00, '9800.00', 9800.00, 9800.00, 0, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/14/1692001124233965.jpeg\" title=\"1692001124233965.jpeg\" alt=\"15d0b2885e052ec2.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692001157164542.jpeg\" title=\"1692001157164542.jpeg\" alt=\"5aa001dda6e8feb8.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692001164283352.jpeg\" title=\"1692001164283352.jpeg\" alt=\"847997a3879f7eb1.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692001172263788.jpeg\" title=\"1692001172263788.jpeg\" alt=\"52788c672d19269e.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692001177919245.jpeg\" title=\"1692001177919245.jpeg\" alt=\"9874b3b8ac6c21cd.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692001183695897.jpeg\" title=\"1692001183695897.jpeg\" alt=\"45f3a6de7d6f5d9f.jpeg\"/></p>', 1, 0, 25, '', 0, '', '', '', '', '', 0, 1692001209, 1693236042),
(105, 27, -1, '纪诗哲包包女包单肩包女士斜挎包情人节送女友老婆生日礼物 【全国十仓/当次日达】咖啡色 精美礼盒', '', '全国十仓/当次日达/咖啡色 精美礼盒', '精美礼盒', 35, 88888, '个', '/static/upload/images/goods/2023/08/14/1692002344829640.png', '6600.00', 6600.00, 6600.00, '5000.00', 5000.00, 5000.00, 0, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/14/1692002536736863.jpeg\" title=\"1692002536736863.jpeg\" alt=\"2e7869813e67269b.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692002546723224.jpeg\" title=\"1692002546723224.jpeg\" alt=\"68bacc6d8cd79bd1.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692002553395716.jpeg\" title=\"1692002553395716.jpeg\" alt=\"811e2ba666a99ccf.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692002562225652.jpeg\" title=\"1692002562225652.jpeg\" alt=\"19882f9a4511ca20.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692002568893939.jpeg\" title=\"1692002568893939.jpeg\" alt=\"896511fd09de3ff3.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692002578810253.jpeg\" title=\"1692002578810253.jpeg\" alt=\"b71623404663d055.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692002587392229.jpeg\" title=\"1692002587392229.jpeg\" alt=\"bfcb3bd2daf6474f.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692002594491922.jpeg\" title=\"1692002594491922.jpeg\" alt=\"c4af8cc1d500081b.jpeg\"/></p>', 1, 0, 5, '', 0, '', '', '', '', '', 0, 1692002599, 1693235790),
(106, 23, -1, '蔻驰（COACH）七夕礼物 女士小号托特包单肩手提包PVC配皮C3599IMOTV', '', '蔻驰（COACH）七夕礼物 女士小号托特包单肩手提包PVC配皮C3599IMOTV', '单肩手提包PVC配皮C3599IMOTV', 35, 88888, '个', '/static/upload/images/goods/2023/08/14/1692002974459810.png', '19000.00', 19000.00, 19000.00, '5000.00', 5000.00, 5000.00, 0, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/14/1692003365184980.png\" title=\"1692003365184980.png\" alt=\"截屏2023-08-14 16.50.31.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692003387395668.png\" title=\"1692003387395668.png\" alt=\"截屏2023-08-14 16.50.43.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692003399805476.png\" title=\"1692003399805476.png\" alt=\"截屏2023-08-14 16.50.51.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692003420445359.png\" title=\"1692003420445359.png\" alt=\"截屏2023-08-14 16.51.00.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692003445795336.png\" title=\"1692003445795336.png\" alt=\"截屏2023-08-14 16.51.09.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/14/1692003482341718.png\" title=\"1692003482341718.png\" alt=\"截屏2023-08-14 16.51.35.png\"/></p>', 1, 0, 15, '', 0, '', '', '', '', '', 0, 1692003520, 1693235979),
(107, 28, -1, '珑骧（longchamp） longchamp 尼龙 奔马刺绣 长柄大号 单肩包 1899 619 海军蓝', '', 'longchamp 尼龙 奔马刺绣 长柄大号 单肩包 1899 619 海军蓝', '单肩包 1899 619 海军蓝', 35, 88888, '个', '/static/upload/images/goods/2023/08/15/1692072217123569.png', '1200.00', 1200.00, 1200.00, '800.00', 800.00, 800.00, 0, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/15/1692072701981145.png\" title=\"1692072701981145.png\" alt=\"截屏2023-08-15 12.04.31.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692072710401442.png\" title=\"1692072710401442.png\" alt=\"截屏2023-08-15 12.04.46.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692072719327504.png\" title=\"1692072719327504.png\" alt=\"截屏2023-08-15 12.05.47.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692072725221637.png\" title=\"1692072725221637.png\" alt=\"截屏2023-08-15 12.05.55.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692072732101446.png\" title=\"1692072732101446.png\" alt=\"截屏2023-08-15 12.06.08.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692072742531552.png\" title=\"1692072742531552.png\" alt=\"截屏2023-08-15 12.06.17.png\"/></p>', 1, 0, 11, '', 0, '', '', '', '', '', 0, 1692072769, 1693235615),
(108, 29, -1, 'PRADA/普拉达【七夕礼物】女士Saffiano 皮革迷你Hobo手袋腋下包 黑色', '', 'PRADA/普拉达【七夕礼物】女士Saffiano 皮革迷你Hobo手袋腋下包 黑色', '迷你Hobo手袋', 35, 88888, '个', '/static/upload/images/goods/2023/08/15/1692075761195908.png', '69870.00', 69870.00, 69870.00, '56390.00', 56390.00, 56390.00, 0, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/15/1692076179708949.png\" title=\"1692076179708949.png\" alt=\"截屏2023-08-15 13.03.17.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692076188300709.png\" title=\"1692076188300709.png\" alt=\"截屏2023-08-15 13.03.32.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692076194697122.png\" title=\"1692076194697122.png\" alt=\"截屏2023-08-15 13.03.49.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692076200985503.png\" title=\"1692076200985503.png\" alt=\"截屏2023-08-15 13.03.58.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692076213588120.png\" title=\"1692076213588120.png\" alt=\"截屏2023-08-15 13.04.38.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692076219520642.png\" title=\"1692076219520642.png\" alt=\"截屏2023-08-15 13.04.48.png\"/></p>', 1, 0, 15, '', 0, '', '', '', '', '', 0, 1692076243, 1693235460),
(109, 30, -1, '七匹狼夹克男外套时尚休闲立领jacket男装工装衣服男上衣 801(米白)1D1D50101040 170/88A/L', '', '七匹狼夹克男外套时尚休闲立领jacket男装工装衣服男上衣 801(米白)1D1D50101040 170/88A/L', '801(米白)1D1D50101040 170/88A/L', 19, 88888, '件', '/static/upload/images/goods/2023/08/15/1692079154656558.png', '800.00', 800.00, 800.00, '200.00', 200.00, 200.00, 0, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/15/1692079376396702.jpeg\" title=\"1692079376396702.jpeg\" alt=\"362ae8e5b9862e3f.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692079383645253.jpeg\" title=\"1692079383645253.jpeg\" alt=\"31219b6040aaa923.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692079396402382.jpeg\" title=\"1692079396402382.jpeg\" alt=\"104fd41433eacdb3.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692079403516194.jpeg\" title=\"1692079403516194.jpeg\" alt=\"2119b3d91942a7ab.jpeg\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692079408817649.jpeg\" title=\"1692079408817649.jpeg\" alt=\"4060f6ee9716eda4.jpeg\"/></p>', 1, 0, 10, '', 0, '', '', '', '', '', 0, 1692079417, 1693235270),
(110, 31, -1, '卡帝乐鳄鱼（CARTELO）短袖T恤男夏季纯色短袖男士宽松上衣圆领体恤男装 深灰色 XL', '', '卡帝乐鳄鱼（CARTELO）短袖T恤男夏季纯色短袖男士宽松上衣圆领体恤男装 深灰色 XL', '深灰色 XL', 9, 88888, '件', '/static/upload/images/goods/2023/08/15/1692079963737575.png', '671.00', 671.00, 671.00, '489.00', 489.00, 489.00, 0, 1, 0, 1, 1, '<p><img src=\"/static/upload/images/goods/2023/08/15/1692080266261183.png\" title=\"1692080266261183.png\" alt=\"截屏2023-08-15 14.14.13.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692080272322367.png\" title=\"1692080272322367.png\" alt=\"截屏2023-08-15 14.14.26.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692080277838680.png\" title=\"1692080277838680.png\" alt=\"截屏2023-08-15 14.14.39.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692080281756933.png\" title=\"1692080281756933.png\" alt=\"截屏2023-08-15 14.15.04.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692080285850637.png\" title=\"1692080285850637.png\" alt=\"截屏2023-08-15 14.15.13.png\"/></p><p><img src=\"/static/upload/images/goods/2023/08/15/1692080289803545.png\" title=\"1692080289803545.png\" alt=\"截屏2023-08-15 14.15.22.png\"/></p><p><br/></p>', 1, 0, 8, '', 0, '', '', '', '', '', 0, 1692080292, 1693234972);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_browse`
--

DROP TABLE IF EXISTS `sxo_goods_browse`;
CREATE TABLE `sxo_goods_browse` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户商品浏览';

--
-- 转存表中的数据 `sxo_goods_browse`
--

INSERT INTO `sxo_goods_browse` (`id`, `goods_id`, `user_id`, `add_time`, `upd_time`) VALUES
(1, 2, 1, 1717947031, 0),
(2, 32, 1, 1717951350, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_category`
--

DROP TABLE IF EXISTS `sxo_goods_category`;
CREATE TABLE `sxo_goods_category` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id',
  `icon` char(255) NOT NULL DEFAULT '' COMMENT 'icon图标',
  `icon_active` char(255) NOT NULL DEFAULT '' COMMENT '选中图标',
  `realistic_images` char(255) NOT NULL DEFAULT '' COMMENT '实景图',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `vice_name` char(80) NOT NULL DEFAULT '' COMMENT '副标题',
  `describe` char(255) NOT NULL DEFAULT '' COMMENT '描述',
  `bg_color` char(30) NOT NULL DEFAULT '' COMMENT 'css背景色值',
  `big_images` char(255) NOT NULL DEFAULT '' COMMENT '大图片',
  `is_home_recommended` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否首页推荐（0否, 1是）',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `seo_title` char(100) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` char(130) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_desc` char(230) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品分类';

--
-- 转存表中的数据 `sxo_goods_category`
--

INSERT INTO `sxo_goods_category` (`id`, `pid`, `icon`, `icon_active`, `realistic_images`, `name`, `vice_name`, `describe`, `bg_color`, `big_images`, `is_home_recommended`, `sort`, `is_enable`, `seo_title`, `seo_keywords`, `seo_desc`, `add_time`, `upd_time`) VALUES
(1, 0, '/static/upload/images/goods_category/2023/08/15/1692083230862352.png', '/static/upload/images/goods_category/2023/08/15/1692083246780484.png', '/static/upload/images/goods_category/2023/11/08/1699457036808561.png', '数码办公', '天天新品，科技带来快乐！', 'iphoneX新品发布了', '', '/static/upload/images/goods_category/2023/08/12/1691819575986269.png', 1, 0, 1, '数码办公标题', '数码办公,笔记本,手机', '数码办公周边产品', 1529042764, 1699457045),
(2, 0, '/static/upload/images/goods_category/2023/08/15/1692085007595445.png', '/static/upload/images/goods_category/2023/08/15/1692085019787788.png', '/static/upload/images/goods_category/2023/11/08/1699457036977194.png', '时尚服饰', '因为美丽、所以神秘！', '追求美、你值得拥有', '', '/static/upload/images/goods_category/2023/08/15/1692078936329508.png', 1, 0, 1, '', '连衣裙,裙子', '', 1529042764, 1699457059),
(3, 0, '/static/upload/images/goods_category/2023/08/15/1692083365962236.png', '/static/upload/images/goods_category/2023/08/15/1692083374446120.png', '/static/upload/images/goods_category/2023/11/08/1699457036877667.png', '名品潮包', '时尚，是一种生活态度！', '美食天下之美', '#ff9229', '/static/upload/images/goods_category/2023/08/15/1692070178969916.png', 1, 0, 1, '', '', '', 1529042764, 1699457071),
(7, 0, '/static/upload/images/goods_category/2023/08/15/1692083394485796.png', '/static/upload/images/goods_category/2023/08/15/1692083405644267.png', '/static/upload/images/goods_category/2023/11/08/1699457036179125.png', '个护化妆', '', '', '', '', 0, 0, 1, '', '', '', 1529042764, 1699457084),
(52, 0, '/static/upload/images/goods_category/2023/08/15/1692083430922728.png', '/static/upload/images/goods_category/2023/08/15/1692083442155794.png', '/static/upload/images/goods_category/2023/11/08/1699457036435422.png', '珠宝手表', '', '', '', '', 0, 0, 1, '', '', '', 1529042764, 1699457097),
(53, 0, '/static/upload/images/goods_category/2023/08/15/1692083465166195.png', '/static/upload/images/goods_category/2023/08/15/1692083473937545.png', '/static/upload/images/goods_category/2023/11/08/1699457036206296.png', '运动健康', '', '户外装备，应有尽有', '#53c0f3', '', 0, 0, 1, '', '', '', 1529042764, 1699457124),
(54, 0, '/static/upload/images/goods_category/2023/08/15/1692083492952738.png', '/static/upload/images/goods_category/2023/08/15/1692083505296275.png', '/static/upload/images/goods_category/2023/11/08/1699457036100017.png', '汽车用品', '', '', '', '', 0, 0, 1, '', '', '', 1529042764, 1699457138),
(55, 0, '/static/upload/images/goods_category/2023/08/15/1692083524902686.png', '/static/upload/images/goods_category/2023/08/15/1692083536757100.png', '/static/upload/images/goods_category/2023/11/08/1699457036304450.png', '玩具乐器', '', '', '', '', 0, 0, 1, '', '', '', 1529042764, 1699457158),
(56, 0, '/static/upload/images/goods_category/2023/08/15/1692083555223187.png', '/static/upload/images/goods_category/2023/08/15/1692083563885614.png', '/static/upload/images/goods_category/2023/11/08/1699457036853449.png', '母婴用品', '', '', '', '', 0, 0, 1, '', '', '', 1529042764, 1699457165),
(57, 0, '/static/upload/images/goods_category/2023/08/15/1692083590253616.png', '/static/upload/images/goods_category/2023/08/15/1692083600747987.png', '/static/upload/images/goods_category/2023/11/08/1699457036375930.png', '生活服务', '', '', '', '', 0, 0, 1, '', '', '', 1529042764, 1699457174),
(58, 1, '/static/upload/images/goods_category/2018/11/20/2018112015245128143.jpeg', '', '', '手机通讯', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1605687744),
(59, 1, '/static/upload/images/goods_category/2018/11/20/2018112015273175122.jpeg', '', '', '手机配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1542698851),
(60, 1, '/static/upload/images/goods_category/2018/11/20/2018112015252193663.jpeg', '', '', '摄影摄像', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1542698721),
(61, 1, '/static/upload/images/goods_category/2018/11/20/2018112015441996472.jpeg', '', '', '时尚影音', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1542699859),
(62, 1, '/static/upload/images/goods_category/2018/11/20/2018112015255390903.jpeg', '', '', '电脑整机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1542698753),
(63, 1, '', '', '', '电脑配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1534240077),
(64, 1, '', '', '', '外设产品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1534240077),
(65, 1, '', '', '', '网络产品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1534240077),
(66, 1, '', '', '', '办公打印', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1534240077),
(67, 1, '', '', '', '办公文仪', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1534240077),
(68, 58, '/static/upload/images/goods_category/2018/11/20/2018112015245128143.jpeg', '', '', '手机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1596730214),
(69, 58, '/static/upload/images/goods_category/2018/11/20/2018112015252193663.jpeg', '', '', '合约机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1596730232),
(70, 58, '/static/upload/images/goods_category/2018/11/20/2018112015273175122.jpeg', '', '', '对讲机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1596730248),
(71, 59, '', '', '', '手机电池', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(72, 59, '', '', '', '蓝牙耳机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(73, 59, '', '', '', '充电器/数据线', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(74, 59, '', '', '', '手机耳机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(75, 59, '', '', '', '手机贴膜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(76, 59, '', '', '', '手机存储卡', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(77, 59, '', '', '', '手机保护套', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(78, 59, '', '', '', '车载配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(79, 59, '', '', '', 'iPhone', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(80, 59, '', '', '', '配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(81, 59, '', '', '', '创意配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(82, 59, '', '', '', '便携/无线音响', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(83, 59, '', '', '', '手机饰品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(84, 60, '', '', '', '数码相机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(85, 60, '', '', '', '单电/微单相机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(86, 60, '', '', '', '单反相机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(87, 60, '', '', '', '摄像机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(88, 60, '', '', '', '拍立得', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(89, 60, '', '', '', '镜头', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(90, 102, '', '', '', '存储卡', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(91, 102, '', '', '', '读卡器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(92, 102, '', '', '', '滤镜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(93, 102, '', '', '', '闪光灯/手柄', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(94, 102, '', '', '', '相机包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(95, 102, '', '', '', '三脚架/云台', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(96, 102, '', '', '', '相机清洁', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(97, 102, '', '', '', '相机贴膜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(98, 102, '', '', '', '机身附件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(99, 102, '', '', '', '镜头附件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(100, 102, '', '', '', '电池/充电器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(101, 102, '', '', '', '移动电源', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(102, 1, '', '', '', '数码配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(103, 61, '', '', '', 'MP3/MP4', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(104, 61, '', '', '', '智能设备', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(105, 61, '', '', '', '耳机/耳麦', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(106, 61, '', '', '', '音箱', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(107, 61, '', '', '', '高清播放器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(108, 61, '', '', '', '电子书', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(109, 61, '', '', '', '电子词典', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(110, 61, '', '', '', 'MP3/MP4配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(111, 61, '', '', '', '录音笔', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(112, 61, '', '', '', '麦克风', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(113, 61, '', '', '', '专业音频', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(114, 61, '', '', '', '电子教育', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(115, 61, '', '', '', '数码相框', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(116, 62, '', '', '', '笔记本', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(117, 62, '', '', '', '超极本', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(118, 62, '', '', '', '游戏本', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(119, 62, '', '', '', '平板电脑', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(120, 62, '', '', '', '平板电脑配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(121, 62, '', '', '', '台式机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(122, 62, '', '', '', '服务器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(123, 62, '', '', '', '笔记本配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(124, 63, '', '', '', 'CPU', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(125, 63, '', '', '', '主板', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(126, 63, '', '', '', '显卡', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(127, 63, '', '', '', '硬盘', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(128, 63, '', '', '', 'SSD固态硬盘', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(129, 63, '', '', '', '内存', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(130, 63, '', '', '', '机箱', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(131, 63, '', '', '', '电源', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(132, 63, '', '', '', '显示器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(133, 63, '', '', '', '刻录机/光驱', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(134, 63, '', '', '', '散热器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(135, 63, '', '', '', '声卡/扩展卡', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(136, 63, '', '', '', '装机配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(137, 64, '', '', '', '鼠标', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(138, 64, '', '', '', '键盘', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(139, 64, '', '', '', '移动硬盘', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(140, 64, '', '', '', 'U盘', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(141, 64, '', '', '', '摄像头', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(142, 64, '', '', '', '外置盒', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(143, 64, '', '', '', '游戏设备', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(144, 64, '', '', '', '电视盒', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(145, 64, '', '', '', '手写板', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(146, 64, '', '', '', '鼠标垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(147, 64, '', '', '', '插座', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(148, 64, '', '', '', 'UPS电源', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(149, 64, '', '', '', '线缆', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(150, 64, '', '', '', '电脑工具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(151, 64, '', '', '', '电脑清洁', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(152, 65, '', '', '', '路由器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(153, 65, '', '', '', '网卡', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(154, 65, '', '', '', '交换机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(155, 65, '', '', '', '网络存储', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(156, 65, '', '', '', '3G上网', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(157, 65, '', '', '', '网络盒子', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(158, 66, '', '', '', '打印机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(159, 66, '', '', '', '一体机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(160, 66, '', '', '', '投影机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(161, 66, '', '', '', '投影配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(162, 66, '', '', '', '传真机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(163, 66, '', '', '', '复合机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(164, 66, '', '', '', '碎纸机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(165, 66, '', '', '', '扫描仪', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(166, 66, '', '', '', '墨盒', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(167, 66, '', '', '', '硒鼓', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(168, 66, '', '', '', '墨粉', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(169, 66, '', '', '', '色带', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(170, 67, '', '', '', '办公文具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(171, 67, '', '', '', '文件管理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(172, 67, '', '', '', '笔类', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(173, 67, '', '', '', '纸类', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(174, 67, '', '', '', '本册/便签', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(175, 67, '', '', '', '学生文具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(176, 67, '', '', '', '财务用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(177, 67, '', '', '', '计算器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(178, 67, '', '', '', '激光笔', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(179, 67, '', '', '', '白板/封装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(180, 67, '', '', '', '考勤机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(181, 67, '', '', '', '刻录碟片/附件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(182, 67, '', '', '', '点钞机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(183, 67, '', '', '', '支付设备', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 1606379152),
(184, 67, '', '', '', '安防监控', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(185, 67, '', '', '', '呼叫/会议设备', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(186, 67, '', '', '', '保险柜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(187, 67, '', '', '', '办公家具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(188, 3, '', '', '', '潮流女包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(189, 3, '', '', '', '时尚男包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(190, 3, '', '', '', '功能箱包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(193, 188, '', '', '', '钱包/卡包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(194, 188, '', '', '', '手拿包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(195, 188, '', '', '', '单肩包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(196, 188, '', '', '', '双肩包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(197, 188, '', '', '', '手提包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(198, 188, '', '', '', '斜挎包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(199, 189, '', '', '', '钱包/卡包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(200, 189, '', '', '', '男士手包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(201, 189, '', '', '', '腰带／礼盒', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(202, 189, '', '', '', '商务公文包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(203, 190, '', '', '', '电脑数码包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(204, 190, '', '', '', '拉杆箱', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(205, 190, '', '', '', '旅行包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(206, 190, '', '', '', '旅行配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(207, 190, '', '', '', '休闲运动包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(208, 190, '', '', '', '登山包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(209, 190, '', '', '', '妈咪包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(210, 190, '', '', '', '书包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(304, 2, '', '', '', '女装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(305, 2, '', '', '', '男装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(306, 2, '', '', '', '内衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(307, 2, '', '', '', '运动', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(308, 2, '', '', '', '女鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(309, 2, '', '', '', '男鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(310, 2, '', '', '', '配饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(311, 2, '', '', '', '童装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(312, 304, '', '', '', 'T恤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(313, 304, '', '', '', '衬衫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(314, 304, '', '', '', '针织衫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(315, 304, '', '', '', '雪纺衫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(316, 304, '', '', '', '卫衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(317, 304, '', '', '', '马甲', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(318, 304, '', '', '', '连衣裙', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(319, 304, '', '', '', '半身裙', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(320, 304, '', '', '', '牛仔裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(321, 304, '', '', '', '休闲裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(322, 304, '', '', '', '打底裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(323, 304, '', '', '', '正装裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(324, 304, '', '', '', '西服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(325, 304, '', '', '', '短外套', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(326, 304, '', '', '', '风衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(327, 304, '', '', '', '大衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(328, 304, '', '', '', '皮衣皮草', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(329, 304, '', '', '', '棉服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(330, 304, '', '', '', '羽绒服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(331, 304, '', '', '', '孕妇装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(332, 304, '', '', '', '大码装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(333, 304, '', '', '', '中老年装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(334, 304, '', '', '', '婚纱礼服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(335, 304, '', '', '', '其它', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(336, 305, '', '', '', '衬衫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(337, 305, '', '', '', 'T恤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(338, 305, '', '', '', 'POLO衫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(339, 305, '', '', '', '针织衫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(340, 305, '', '', '', '羊绒衫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(341, 305, '', '', '', '卫衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(342, 305, '', '', '', '马甲／背心', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(343, 305, '', '', '', '夹克', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(344, 305, '', '', '', '风衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(345, 305, '', '', '', '大衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(346, 305, '', '', '', '皮衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(347, 305, '', '', '', '外套', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(348, 305, '', '', '', '西服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(349, 305, '', '', '', '棉服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(350, 305, '', '', '', '羽绒服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(351, 305, '', '', '', '牛仔裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(352, 305, '', '', '', '休闲裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(353, 305, '', '', '', '西裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(354, 305, '', '', '', '西服套装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(355, 305, '', '', '', '大码装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(356, 305, '', '', '', '中老年装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(357, 305, '', '', '', '唐装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(358, 305, '', '', '', '工装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(359, 306, '', '', '', '文胸', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(360, 306, '', '', '', '女式内裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(361, 306, '', '', '', '男式内裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(362, 306, '', '', '', '家居', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(363, 306, '', '', '', '睡衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(364, 306, '', '', '', '塑身衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(365, 306, '', '', '', '睡袍／浴袍', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(366, 306, '', '', '', '泳衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(367, 306, '', '', '', '背心', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(368, 306, '', '', '', '抹胸', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(369, 306, '', '', '', '连裤袜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(370, 306, '', '', '', '美腿袜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(371, 306, '', '', '', '男袜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(372, 306, '', '', '', '女袜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(373, 306, '', '', '', '情趣内衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(374, 306, '', '', '', '保暖内衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(375, 307, '', '', '', '休闲鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(376, 307, '', '', '', '帆布鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(377, 307, '', '', '', '跑步鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(378, 307, '', '', '', '篮球鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(379, 307, '', '', '', '足球鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(380, 307, '', '', '', '训练鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(381, 307, '', '', '', '乒羽鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(382, 307, '', '', '', '拖鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(383, 307, '', '', '', '卫衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(384, 307, '', '', '', '夹克', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(385, 307, '', '', '', 'T恤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(386, 307, '', '', '', '棉服／羽绒服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(387, 307, '', '', '', '运动裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(388, 307, '', '', '', '套装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(389, 307, '', '', '', '运动包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(390, 307, '', '', '', '运动配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(391, 308, '', '', '', '平底鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(392, 308, '', '', '', '高跟鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(393, 308, '', '', '', '单鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(394, 308, '', '', '', '休闲鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(395, 308, '', '', '', '凉鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(396, 308, '', '', '', '女靴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(397, 308, '', '', '', '雪地靴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(398, 308, '', '', '', '拖鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(399, 308, '', '', '', '裸靴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(400, 308, '', '', '', '筒靴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(401, 308, '', '', '', '帆布鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(402, 308, '', '', '', '雨鞋／雨靴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(403, 308, '', '', '', '妈妈鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(404, 308, '', '', '', '鞋配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(405, 308, '', '', '', '特色鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(406, 308, '', '', '', '鱼嘴鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(407, 308, '', '', '', '布鞋／绣花鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(408, 309, '', '', '', '商务休闲鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(409, 309, '', '', '', '正装鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(410, 309, '', '', '', '休闲鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(411, 309, '', '', '', '凉鞋／沙滩鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(412, 309, '', '', '', '男靴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(413, 309, '', '', '', '功能鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(414, 309, '', '', '', '拖鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(415, 309, '', '', '', '传统布鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(416, 309, '', '', '', '鞋配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(417, 309, '', '', '', '帆布鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(418, 309, '', '', '', '豆豆鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(419, 309, '', '', '', '驾车鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(420, 310, '', '', '', '太阳镜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(421, 310, '', '', '', '框镜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(422, 310, '', '', '', '皮带', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(423, 310, '', '', '', '围巾', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(424, 310, '', '', '', '手套', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(425, 310, '', '', '', '帽子', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(426, 310, '', '', '', '领带', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(427, 310, '', '', '', '袖扣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(428, 310, '', '', '', '其他配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(429, 310, '', '', '', '丝巾', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(430, 310, '', '', '', '披肩', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(431, 310, '', '', '', '腰带', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(432, 310, '', '', '', '腰链／腰封', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(433, 310, '', '', '', '棒球帽', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(434, 310, '', '', '', '毛线', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(435, 310, '', '', '', '遮阳帽', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(436, 310, '', '', '', '防紫外线手套', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(437, 310, '', '', '', '草帽', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(438, 311, '', '', '', '套装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(439, 311, '', '', '', '上衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(440, 311, '', '', '', '裤子', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(441, 311, '', '', '', '裙子', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(442, 311, '', '', '', '内衣／家居服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(443, 311, '', '', '', '羽绒服／棉服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(444, 311, '', '', '', '亲子装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(445, 311, '', '', '', '儿童配饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(446, 311, '', '', '', '礼服／演出服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(447, 311, '', '', '', '运动鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(448, 311, '', '', '', '单鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(449, 311, '', '', '', '靴子', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(450, 311, '', '', '', '凉鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(451, 311, '', '', '', '功能鞋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(452, 7, '', '', '', '面部护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(453, 7, '', '', '', '身体护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(454, 7, '', '', '', '口腔护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(455, 7, '', '', '', '女性护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(456, 7, '', '', '', '男士护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(457, 7, '', '', '', '魅力彩妆', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(458, 7, '', '', '', '香水SPA', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(459, 452, '', '', '', '洁面乳', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(460, 452, '', '', '', '爽肤水', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(461, 452, '', '', '', '精华露', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(462, 452, '', '', '', '乳液面霜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(463, 452, '', '', '', '面膜面贴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(464, 452, '', '', '', '眼部护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(465, 452, '', '', '', '颈部护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(466, 452, '', '', '', 'T区护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(467, 452, '', '', '', '护肤套装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(468, 452, '', '', '', '防晒隔离', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(469, 453, '', '', '', '洗发护发', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(470, 453, '', '', '', '染发/造型', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(471, 453, '', '', '', '沐浴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(472, 453, '', '', '', '磨砂/浴盐', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(473, 453, '', '', '', '身体乳', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(474, 453, '', '', '', '手工/香皂', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(475, 453, '', '', '', '香薰精油', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(476, 453, '', '', '', '纤体瘦身', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(477, 453, '', '', '', '脱毛膏', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(478, 453, '', '', '', '手足护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(479, 453, '', '', '', '洗护套装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(480, 454, '', '', '', '牙膏/牙粉', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(481, 454, '', '', '', '牙刷/牙线', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(482, 454, '', '', '', '漱口水', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(483, 455, '', '', '', '卫生巾', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(484, 455, '', '', '', '卫生护垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(485, 455, '', '', '', '洗液', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(486, 455, '', '', '', '美容食品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(487, 455, '', '', '', '其他', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(488, 456, '', '', '', '脸部护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(489, 456, '', '', '', '眼部护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(490, 456, '', '', '', '身体护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(491, 456, '', '', '', '男士香水', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(492, 456, '', '', '', '剃须护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(493, 456, '', '', '', '防脱洗护', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(494, 456, '', '', '', '男士唇膏', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(495, 457, '', '', '', '粉底/遮瑕', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(496, 457, '', '', '', '腮红', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(497, 457, '', '', '', '眼影/眼线', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(498, 457, '', '', '', '眉笔', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(499, 457, '', '', '', '睫毛膏', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(500, 457, '', '', '', '唇膏唇彩', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(501, 457, '', '', '', '彩妆组合', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(502, 457, '', '', '', '卸妆', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(503, 457, '', '', '', '美甲', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(504, 457, '', '', '', '彩妆工具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(505, 457, '', '', '', '假发', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(506, 458, '', '', '', '女士香水', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(507, 458, '', '', '', '男士香水', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(508, 458, '', '', '', '组合套装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(509, 458, '', '', '', '迷你香水', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(510, 458, '', '', '', '香体走珠', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(511, 52, '', '', '', '时尚饰品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(512, 52, '', '', '', '纯金K金饰品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(513, 52, '', '', '', '金银投资', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(514, 52, '', '', '', '银饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(515, 52, '', '', '', '钻石饰品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(516, 52, '', '', '', '翡翠玉石', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(517, 52, '', '', '', '水晶玛瑙', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(518, 52, '', '', '', '宝石珍珠', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(519, 52, '', '', '', '婚庆', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(520, 52, '', '', '', '钟表手表', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(521, 511, '', '', '', '项链', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(522, 511, '', '', '', '手链/脚链', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(523, 511, '', '', '', '戒指', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(524, 511, '', '', '', '耳饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(525, 511, '', '', '', '头饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(526, 511, '', '', '', '胸针', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(527, 511, '', '', '', '婚庆饰品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(528, 511, '', '', '', '饰品配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(529, 512, '', '', '', '吊坠/项链', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(530, 512, '', '', '', '手镯/手链/脚链', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(531, 512, '', '', '', '戒指', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(532, 512, '', '', '', '耳饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(533, 513, '', '', '', '工艺金', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(534, 513, '', '', '', '工艺银', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(535, 514, '', '', '', '吊坠/项链', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(536, 514, '', '', '', '手镯/手链/脚链', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(537, 514, '', '', '', '戒指/耳饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(538, 514, '', '', '', '宝宝金银', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(539, 514, '', '', '', '千足银', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(540, 515, '', '', '', '裸钻', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(541, 515, '', '', '', '戒指', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(542, 515, '', '', '', '项链/吊坠', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(543, 515, '', '', '', '耳饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(544, 515, '', '', '', '手镯/手链', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(545, 516, '', '', '', '项链/吊坠', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(546, 516, '', '', '', '手镯/手串', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(547, 516, '', '', '', '戒指', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(548, 516, '', '', '', '耳饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(549, 516, '', '', '', '挂件/摆件/把件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(550, 516, '', '', '', '高值收藏', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(551, 517, '', '', '', '耳饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(552, 517, '', '', '', '手镯/手链/脚链', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(553, 517, '', '', '', '戒指', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(554, 517, '', '', '', '头饰/胸针', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(555, 517, '', '', '', '摆件/挂件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(556, 518, '', '', '', '项链/吊坠', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(557, 518, '', '', '', '耳饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(558, 518, '', '', '', '手镯/手链', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(559, 518, '', '', '', '戒指', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(560, 519, '', '', '', '婚嫁首饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(561, 519, '', '', '', '婚纱摄影', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(562, 519, '', '', '', '婚纱礼服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(563, 519, '', '', '', '婚庆服务', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(564, 519, '', '', '', '婚庆礼品/用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(565, 519, '', '', '', '婚宴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(566, 520, '', '', '', '瑞士品牌', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(567, 520, '', '', '', '国产品牌', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(568, 520, '', '', '', '日本品牌', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(569, 520, '', '', '', '时尚品牌', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(570, 520, '', '', '', '闹钟挂钟', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(571, 520, '', '', '', '儿童手表', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(572, 53, '', '', '', '户外鞋服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(573, 53, '', '', '', '户外装备', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(574, 53, '', '', '', '运动器械', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(575, 53, '', '', '', '纤体瑜伽', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(576, 53, '', '', '', '体育娱乐', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(577, 53, '', '', '', '成人用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(578, 53, '', '', '', '保健器械', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(579, 53, '', '', '', '急救卫生', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(580, 572, '', '', '', '户外服装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(581, 572, '', '', '', '户外鞋袜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(582, 572, '', '', '', '户外配饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(583, 573, '', '', '', '帐篷', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(584, 573, '', '', '', '睡袋', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(585, 573, '', '', '', '登山攀岩', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(586, 573, '', '', '', '户外背包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(587, 573, '', '', '', '户外照明', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(588, 573, '', '', '', '户外垫子', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(589, 573, '', '', '', '户外仪表', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(590, 573, '', '', '', '户外工具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(591, 573, '', '', '', '望远镜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(592, 573, '', '', '', '垂钓用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(593, 573, '', '', '', '旅游用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(594, 573, '', '', '', '便携桌椅床', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(595, 573, '', '', '', '烧烤用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(596, 573, '', '', '', '野餐炊具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(597, 573, '', '', '', '军迷用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(598, 573, '', '', '', '游泳用具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(599, 573, '', '', '', '泳衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(600, 574, '', '', '', '健身器械', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(601, 574, '', '', '', '运动器材', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(602, 574, '', '', '', '极限轮滑', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(603, 574, '', '', '', '骑行运动', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(604, 574, '', '', '', '运动护具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(605, 574, '', '', '', '武术搏击', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(606, 575, '', '', '', '瑜伽垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(607, 575, '', '', '', '瑜伽服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(608, 575, '', '', '', '瑜伽配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(609, 575, '', '', '', '瑜伽套装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(610, 575, '', '', '', '舞蹈鞋服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(611, 576, '', '', '', '羽毛球', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(612, 576, '', '', '', '乒乓球', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(613, 576, '', '', '', '篮球', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(614, 576, '', '', '', '足球', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(615, 576, '', '', '', '网球', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(616, 576, '', '', '', '排球', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(617, 576, '', '', '', '高尔夫球', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(618, 576, '', '', '', '棋牌麻将', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(619, 576, '', '', '', '其他', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(620, 577, '', '', '', '安全避孕', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(621, 577, '', '', '', '验孕测孕', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(622, 577, '', '', '', '人体润滑', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(623, 577, '', '', '', '情爱玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(624, 577, '', '', '', '情趣内衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(625, 577, '', '', '', '组合套装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(626, 578, '', '', '', '养生器械', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(627, 578, '', '', '', '保健用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(628, 578, '', '', '', '康复辅助', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(629, 578, '', '', '', '家庭护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(630, 579, '', '', '', '跌打损伤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(631, 579, '', '', '', '烫伤止痒', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(632, 579, '', '', '', '防裂抗冻', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(633, 579, '', '', '', '口腔咽部', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(634, 579, '', '', '', '眼部保健', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(635, 579, '', '', '', '鼻炎健康', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(636, 579, '', '', '', '风湿骨痛', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(637, 579, '', '', '', '生殖泌尿', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(638, 579, '', '', '', '美体塑身', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(639, 54, '', '', '', '电子电器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(640, 54, '', '', '', '系统养护', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(641, 54, '', '', '', '改装配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(642, 54, '', '', '', '汽车美容', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(643, 54, '', '', '', '座垫脚垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(644, 54, '', '', '', '内饰精品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(645, 54, '', '', '', '安全自驾', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(646, 54, '', '', '', '整车', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(647, 639, '', '', '', '便携GPS导航', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(648, 639, '', '', '', '嵌入式导航', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(649, 639, '', '', '', '安全预警仪', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(650, 639, '', '', '', '行车记录仪', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(651, 639, '', '', '', '跟踪防盗器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(652, 639, '', '', '', '倒车雷达', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(653, 639, '', '', '', '车载电源', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(654, 639, '', '', '', '车载蓝牙', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(655, 639, '', '', '', '车载影音', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(656, 639, '', '', '', '车载净化器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(657, 639, '', '', '', '车载冰箱', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(658, 639, '', '', '', '车载吸尘器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(659, 639, '', '', '', '充气泵', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(660, 639, '', '', '', '胎压监测', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(661, 639, '', '', '', '车载生活电器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(662, 640, '', '', '', '机油', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(663, 640, '', '', '', '添加剂', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(664, 640, '', '', '', '防冻冷却液', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(665, 640, '', '', '', '附属油', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(666, 640, '', '', '', '底盘装甲', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(667, 640, '', '', '', '空调清洗剂', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(668, 640, '', '', '', '金属养护', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(669, 641, '', '', '', '雨刷', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(670, 641, '', '', '', '车灯', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(671, 641, '', '', '', '轮胎', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(672, 641, '', '', '', '贴膜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(673, 641, '', '', '', '装饰贴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(674, 641, '', '', '', '后视镜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(675, 641, '', '', '', '机油滤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(676, 641, '', '', '', '空气滤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(677, 641, '', '', '', '空调滤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(678, 641, '', '', '', '燃油滤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(679, 641, '', '', '', '火花塞', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(680, 641, '', '', '', '喇叭', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(681, 641, '', '', '', '刹车片', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(682, 641, '', '', '', '刹车盘', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(683, 641, '', '', '', '减震器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(684, 641, '', '', '', '车身装饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(685, 641, '', '', '', '尾喉/排气管', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(686, 641, '', '', '', '踏板', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(687, 641, '', '', '', '蓄电池', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(688, 641, '', '', '', '其他配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(689, 642, '', '', '', '漆面美容', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(690, 642, '', '', '', '漆面修复', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(691, 642, '', '', '', '内饰清洁', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(692, 642, '', '', '', '玻璃美容', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(693, 642, '', '', '', '补漆笔', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(694, 642, '', '', '', '轮胎轮毂清洗', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(695, 642, '', '', '', '洗车器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(696, 642, '', '', '', '洗车水枪', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(697, 642, '', '', '', '洗车配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(698, 642, '', '', '', '洗车液', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(699, 642, '', '', '', '车掸', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(700, 642, '', '', '', '擦车巾/海绵', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(701, 643, '', '', '', '凉垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(702, 643, '', '', '', '四季垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(703, 643, '', '', '', '毛垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(704, 643, '', '', '', '专车专用座垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(705, 643, '', '', '', '专车专用座套', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(706, 643, '', '', '', '通用座套', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(707, 643, '', '', '', '多功能垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(708, 643, '', '', '', '专车专用脚垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(709, 643, '', '', '', '通用脚垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(710, 643, '', '', '', '后备箱垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(711, 644, '', '', '', '车用香水', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(712, 644, '', '', '', '车用炭包', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(713, 644, '', '', '', '空气净化', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(714, 644, '', '', '', '颈枕/头枕', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(715, 644, '', '', '', '抱枕/腰靠', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(716, 644, '', '', '', '方向盘套', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0);
INSERT INTO `sxo_goods_category` (`id`, `pid`, `icon`, `icon_active`, `realistic_images`, `name`, `vice_name`, `describe`, `bg_color`, `big_images`, `is_home_recommended`, `sort`, `is_enable`, `seo_title`, `seo_keywords`, `seo_desc`, `add_time`, `upd_time`) VALUES
(717, 644, '', '', '', '挂件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(718, 644, '', '', '', '摆件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(719, 644, '', '', '', '布艺软饰', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(720, 644, '', '', '', '功能用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(721, 644, '', '', '', '整理收纳', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(722, 644, '', '', '', 'CD夹', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(723, 645, '', '', '', '儿童安全座椅', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(724, 645, '', '', '', '应急救援', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(725, 645, '', '', '', '汽修工具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(726, 645, '', '', '', '自驾野营', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(727, 645, '', '', '', '自驾照明', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(728, 645, '', '', '', '保温箱', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(729, 645, '', '', '', '置物箱', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(730, 645, '', '', '', '车衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(731, 645, '', '', '', '遮阳挡雪挡', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(732, 645, '', '', '', '车锁地锁', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(733, 645, '', '', '', '摩托车装备', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(734, 646, '', '', '', '新车', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(735, 646, '', '', '', '二手车', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(736, 55, '', '', '', '适用年龄', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(737, 55, '', '', '', '遥控/电动', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(738, 55, '', '', '', '毛绒布艺', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(739, 55, '', '', '', '娃娃玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(740, 55, '', '', '', '模型玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(741, 55, '', '', '', '健身玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(742, 55, '', '', '', '动漫玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(743, 55, '', '', '', '益智玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(744, 55, '', '', '', '积木拼插', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(745, 55, '', '', '', 'DIY玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(746, 55, '', '', '', '创意减压', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(747, 55, '', '', '', '乐器相关', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(748, 736, '', '', '', '0-6个月', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(749, 736, '', '', '', '6-12个月', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(750, 736, '', '', '', '1-3岁', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(751, 736, '', '', '', '3-6岁', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(752, 736, '', '', '', '6-14岁', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(753, 736, '', '', '', '14岁以上', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(754, 737, '', '', '', '遥控车', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(755, 737, '', '', '', '遥控飞机', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(756, 737, '', '', '', '遥控船', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(757, 737, '', '', '', '机器人/电动', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(758, 737, '', '', '', '轨道/助力', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(759, 738, '', '', '', '毛绒/布艺', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(760, 738, '', '', '', '靠垫/抱枕', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(761, 739, '', '', '', '芭比娃娃', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(762, 739, '', '', '', '卡通娃娃', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(763, 739, '', '', '', '智能娃娃', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(764, 740, '', '', '', '仿真模型', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(765, 740, '', '', '', '拼插模型', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(766, 740, '', '', '', '收藏爱好', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(767, 741, '', '', '', '炫舞毯', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(768, 741, '', '', '', '爬行垫/毯', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(769, 741, '', '', '', '户外玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(770, 741, '', '', '', '戏水玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(771, 742, '', '', '', '电影周边', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(772, 742, '', '', '', '卡通周边', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(773, 742, '', '', '', '网游周边', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(774, 743, '', '', '', '摇铃/床铃', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(775, 743, '', '', '', '健身架', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(776, 743, '', '', '', '早教启智', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(777, 743, '', '', '', '拖拉玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(778, 744, '', '', '', '积木', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(779, 744, '', '', '', '拼图', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(780, 744, '', '', '', '磁力棒', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(781, 744, '', '', '', '立体拼插', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(782, 745, '', '', '', '手工彩泥', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(783, 745, '', '', '', '绘画工具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(784, 745, '', '', '', '情景玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(785, 746, '', '', '', '减压玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(786, 746, '', '', '', '创意玩具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(787, 747, '', '', '', '钢琴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(788, 747, '', '', '', '电子琴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(789, 747, '', '', '', '手风琴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(790, 747, '', '', '', '吉他/贝斯', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(791, 747, '', '', '', '民族管弦乐器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(792, 747, '', '', '', '西洋管弦乐', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(793, 747, '', '', '', '口琴/口风琴/竖笛', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(794, 747, '', '', '', '西洋打击乐器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(795, 747, '', '', '', '各式乐器配件', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(796, 747, '', '', '', '电脑音乐', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(797, 747, '', '', '', '工艺礼品乐器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(798, 56, '', '', '', '奶粉', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(799, 56, '', '', '', '营养辅食', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(800, 56, '', '', '', '尿裤湿巾', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(801, 56, '', '', '', '喂养用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(802, 56, '', '', '', '洗护用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(803, 56, '', '', '', '童车童床', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(804, 56, '', '', '', '服饰寝居', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(805, 56, '', '', '', '妈妈专区', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(806, 798, '', '', '', '品牌奶粉', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(807, 798, '', '', '', '妈妈奶粉', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(808, 798, '', '', '', '1段奶粉', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(809, 798, '', '', '', '2段奶粉', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(810, 798, '', '', '', '3段奶粉', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(811, 798, '', '', '', '4段奶粉', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(812, 798, '', '', '', '羊奶粉', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(813, 798, '', '', '', '特殊配方', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(814, 798, '', '', '', '成人奶粉', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(815, 799, '', '', '', '婴幼营养', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(816, 799, '', '', '', '初乳', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(817, 799, '', '', '', '米粉/菜粉', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(818, 799, '', '', '', '果泥/果汁', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(819, 799, '', '', '', '肉松/饼干', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(820, 799, '', '', '', '辅食', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(821, 799, '', '', '', '孕期营养', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(822, 799, '', '', '', '清火/开胃', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(823, 799, '', '', '', '面条/粥', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(824, 800, '', '', '', '品牌尿裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(825, 800, '', '', '', '新生儿', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(826, 800, '', '', '', 'S号', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(827, 800, '', '', '', 'M号', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(828, 800, '', '', '', 'L号', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(829, 800, '', '', '', 'XL/XXL号', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(830, 800, '', '', '', '裤型尿裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(831, 800, '', '', '', '湿巾', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(832, 800, '', '', '', '尿布/尿垫', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(833, 800, '', '', '', '成人尿裤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(834, 801, '', '', '', '奶瓶', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(835, 801, '', '', '', '奶嘴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(836, 801, '', '', '', '吸奶器', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(837, 801, '', '', '', '暖奶/消毒', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(838, 801, '', '', '', '餐具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(839, 801, '', '', '', '水具', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(840, 801, '', '', '', '牙胶/安抚', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(841, 801, '', '', '', '辅助用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(842, 802, '', '', '', '宝宝护肤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(843, 802, '', '', '', '洗浴用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(844, 802, '', '', '', '洗发沐浴', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(845, 802, '', '', '', '清洁用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(846, 802, '', '', '', '护理用品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(847, 802, '', '', '', '妈妈护肤', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(848, 803, '', '', '', '婴儿推车', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(849, 803, '', '', '', '餐椅摇椅', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(850, 803, '', '', '', '婴儿床', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(851, 803, '', '', '', '学步车', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(852, 803, '', '', '', '三轮车', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(853, 803, '', '', '', '自行车', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(854, 803, '', '', '', '电动车', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(855, 803, '', '', '', '健身车', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(856, 803, '', '', '', '安全座椅', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(857, 804, '', '', '', '婴儿外出服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(858, 804, '', '', '', '婴儿内衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(859, 804, '', '', '', '婴儿礼盒', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(860, 804, '', '', '', '婴儿鞋帽袜', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(861, 804, '', '', '', '安全防护', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(862, 804, '', '', '', '家居床品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(863, 804, '', '', '', '其他', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(864, 805, '', '', '', '包/背婴带', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(865, 805, '', '', '', '妈妈护理', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(866, 805, '', '', '', '产后塑身', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(867, 805, '', '', '', '孕妇内衣', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(868, 805, '', '', '', '防辐射服', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(869, 805, '', '', '', '孕妇装', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(870, 805, '', '', '', '孕妇食品', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(871, 805, '', '', '', '妈妈美容', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(872, 57, '', '', '', '餐饮娱乐', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(873, 57, '', '', '', '婚纱旅游', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(874, 57, '', '', '', '便民充值', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(875, 57, '', '', '', '游戏充值', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(876, 57, '', '', '', '票务服务', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(877, 872, '', '', '', '美食', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(878, 872, '', '', '', '电影票', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(879, 872, '', '', '', '自助餐', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(880, 872, '', '', '', '火锅', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(881, 873, '', '', '', '浪漫婚纱', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(882, 873, '', '', '', '旅游踏青', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(883, 874, '', '', '', '礼品卡', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(884, 874, '', '', '', '手机充值', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(885, 875, '', '', '', '游戏点卡', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(886, 875, '', '', '', 'QQ充值', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(887, 876, '', '', '', '代金券', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(888, 876, '', '', '', '演唱会', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(889, 876, '', '', '', '话剧/歌剧/音乐剧', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(890, 876, '', '', '', '体育赛事', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(891, 876, '', '', '', '舞蹈芭蕾', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(892, 876, '', '', '', '戏曲综艺', '', '', '', '', 1, 0, 1, '', '', '', 1529042764, 0),
(895, 57, '', '', '', '其他', '', '', '', '', 0, 0, 1, '', '', '', 1659924575, 0),
(896, 3, '', '', '', '旅行包', '', '', '', '', 0, 0, 1, '', '', '', 1692066437, 0),
(897, 3, '', '', '', '运动包', '', '', '', '', 0, 0, 1, '', '', '', 1692066456, 1692066501),
(898, 3, '', '', '', '电脑包', '', '', '', '', 0, 0, 1, '', '', '', 1692066481, 0),
(899, 3, '', '', '', '商务公文', '', '', '', '', 0, 0, 1, '', '', '', 1692066532, 0),
(900, 896, '', '', '', '书包', '', '', '', '', 0, 0, 1, '', '', '', 1692066654, 0),
(901, 896, '', '', '', '旅行配件', '', '', '', '', 0, 0, 1, '', '', '', 1692066663, 0),
(902, 897, '', '', '', '运动斜挎', '', '', '', '', 0, 0, 1, '', '', '', 1692066675, 0),
(903, 897, '', '', '', '手臂包', '', '', '', '', 0, 0, 1, '', '', '', 1692066687, 0),
(904, 897, '', '', '', '手机包', '', '', '', '', 0, 0, 1, '', '', '', 1692066702, 0),
(905, 898, '', '', '', '13寸包', '', '', '', '', 0, 0, 1, '', '', '', 1692066719, 0),
(906, 898, '', '', '', '15寸包', '', '', '', '', 0, 0, 1, '', '', '', 1692066727, 0),
(907, 898, '', '', '', '16寸包', '', '', '', '', 0, 0, 1, '', '', '', 1692066734, 0),
(908, 899, '', '', '', '手拿包', '', '', '', '', 0, 0, 1, '', '', '', 1692066745, 0),
(909, 899, '', '', '', '商务背包', '', '', '', '', 0, 0, 1, '', '', '', 1692066764, 0),
(910, 899, '', '', '', '商务挎包', '', '', '', '', 0, 0, 1, '', '', '', 1692066773, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_category_join`
--

DROP TABLE IF EXISTS `sxo_goods_category_join`;
CREATE TABLE `sxo_goods_category_join` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类id',
  `add_time` int(10) UNSIGNED DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品分类关联';

--
-- 转存表中的数据 `sxo_goods_category_join`
--

INSERT INTO `sxo_goods_category_join` (`id`, `goods_id`, `category_id`, `add_time`) VALUES
(889, 30, 68, 1611575535),
(890, 30, 69, 1611575535),
(891, 30, 70, 1611575535),
(892, 30, 59, 1611575535),
(893, 30, 74, 1611575535),
(980, 17, 68, 1612336141),
(1013, 20, 69, 1613830281),
(1023, 18, 58, 1614961172),
(1024, 18, 70, 1614961172),
(1026, 19, 58, 1614961233),
(1291, 15, 69, 1651831248),
(1328, 31, 68, 1654407225),
(1329, 31, 304, 1654407225),
(1335, 34, 68, 1654408179),
(1336, 34, 304, 1654408179),
(1447, 35, 68, 1654443513),
(1448, 35, 304, 1654443513),
(1451, 36, 68, 1654443568),
(1452, 36, 304, 1654443568),
(1491, 45, 68, 1654444090),
(1492, 45, 304, 1654444090),
(1503, 46, 68, 1654444255),
(1504, 46, 304, 1654444255),
(1517, 47, 68, 1654481357),
(1518, 47, 304, 1654481357),
(1519, 48, 68, 1654482350),
(1520, 48, 304, 1654482350),
(1567, 50, 68, 1654498297),
(1568, 50, 304, 1654498297),
(1590, 53, 68, 1657863488),
(1591, 53, 304, 1657863488),
(1592, 54, 68, 1657863560),
(1593, 54, 304, 1657863560),
(1708, 57, 68, 1667140265),
(1709, 57, 70, 1667140265),
(1710, 57, 574, 1667140265),
(1844, 1, 68, 1691832446),
(1852, 8, 318, 1691981714),
(1885, 7, 197, 1693234922),
(1886, 110, 2, 1693234972),
(1887, 109, 2, 1693235270),
(1888, 98, 3, 1693235317),
(1889, 108, 3, 1693235460),
(1890, 107, 3, 1693235615),
(1891, 102, 3, 1693235663),
(1893, 105, 3, 1693235790),
(1894, 101, 3, 1693235925),
(1895, 106, 3, 1693235979),
(1896, 104, 3, 1693236042),
(1897, 103, 3, 1693236446),
(1898, 100, 2, 1693236598),
(1899, 99, 2, 1693236668),
(1900, 9, 318, 1693237071),
(1901, 25, 69, 1693237146),
(1902, 32, 68, 1693237177),
(1903, 5, 68, 1693237221),
(1904, 4, 116, 1693237649),
(1906, 11, 318, 1693237903),
(1907, 12, 352, 1693237967),
(1908, 10, 318, 1693238016),
(1909, 74, 338, 1693238627),
(1910, 2, 116, 1693284683),
(1911, 3, 116, 1693284931),
(1912, 6, 68, 1693285023);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_comments`
--

DROP TABLE IF EXISTS `sxo_goods_comments`;
CREATE TABLE `sxo_goods_comments` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `order_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '业务订单id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `business_type` char(30) NOT NULL DEFAULT '' COMMENT '业务类型名称（如订单 order）',
  `content` char(255) NOT NULL DEFAULT '' COMMENT '评价内容',
  `images` text DEFAULT NULL COMMENT '图片数据（一维数组json）',
  `reply` char(255) NOT NULL DEFAULT '' COMMENT '回复内容',
  `rating` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评价级别（默认0 1~5）',
  `is_show` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否显示（0否, 1是）',
  `is_anonymous` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否匿名（0否，1是）',
  `is_reply` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否回复（0否，1是）',
  `reply_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '回复时间',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品评论';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_content_app`
--

DROP TABLE IF EXISTS `sxo_goods_content_app`;
CREATE TABLE `sxo_goods_content_app` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `images` char(255) NOT NULL DEFAULT '' COMMENT '图片',
  `content` text DEFAULT NULL COMMENT '内容',
  `sort` tinyint(3) UNSIGNED DEFAULT 0 COMMENT '顺序',
  `add_time` int(10) UNSIGNED DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品手机详情';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_favor`
--

DROP TABLE IF EXISTS `sxo_goods_favor`;
CREATE TABLE `sxo_goods_favor` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户商品收藏';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_give_integral_log`
--

DROP TABLE IF EXISTS `sxo_goods_give_integral_log`;
CREATE TABLE `sxo_goods_give_integral_log` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `order_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单id',
  `order_detail_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单详情id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品详情id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态（0待发放, 1已发放, 2已关闭）',
  `rate` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '赠送比例',
  `integral` int(11) NOT NULL DEFAULT 0 COMMENT '积分',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品积分赠送日志';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_params`
--

DROP TABLE IF EXISTS `sxo_goods_params`;
CREATE TABLE `sxo_goods_params` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `type` tinyint(3) UNSIGNED DEFAULT 1 COMMENT '展示范围（0全部, 1详情, 2基础）默认1详情',
  `name` char(180) NOT NULL DEFAULT '' COMMENT '参数名称',
  `value` char(180) NOT NULL DEFAULT '' COMMENT '参数值',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品参数';

--
-- 转存表中的数据 `sxo_goods_params`
--

INSERT INTO `sxo_goods_params` (`id`, `goods_id`, `type`, `name`, `value`, `add_time`) VALUES
(1495, 9, 2, '款式', '长款连衣裙', 1693237071),
(1496, 9, 0, '流行元素/工艺', '树脂固色', 1693237071),
(1497, 9, 2, '适用年龄', '30-34周岁', 1693237071),
(1498, 9, 1, '图案', '纯色', 1693237071),
(1499, 9, 1, '袖长', '短袖', 1693237071),
(1500, 9, 1, '风格', '复古风', 1693237071),
(1501, 9, 1, '衣门襟', '套头', 1693237071),
(1502, 9, 1, '裙型', '大摆型', 1693237071),
(1503, 9, 1, '组合形式', '单件', 1693237071),
(1504, 9, 0, '款式类别', '图案花纹', 1693237071),
(1505, 25, 1, 'CPU型号', 'A16', 1693237146),
(1516, 12, 0, '流行元素/工艺', '树脂固色', 1693237967),
(1517, 12, 2, '款式', '长款连衣裙', 1693237967),
(1518, 12, 2, '适用年龄', '30-34周岁', 1693237967),
(1519, 12, 1, '图案', '纯色', 1693237967),
(1520, 12, 1, '袖长', '短袖', 1693237967),
(1521, 12, 1, '风格', '复古风', 1693237967),
(1522, 12, 1, '衣门襟', '套头', 1693237967),
(1523, 12, 1, '裙型', '大摆型', 1693237967),
(1524, 12, 1, '组合形式', '单件', 1693237967),
(1525, 12, 0, '款式类别', '图案花纹', 1693237967),
(1526, 74, 2, '款式', '长款连衣裙', 1693238627),
(1527, 74, 0, '流行元素/工艺', '树脂固色', 1693238627),
(1528, 74, 2, '适用年龄', '30-34周岁', 1693238627),
(1529, 74, 1, '图案', '纯色', 1693238627),
(1530, 74, 1, '袖长', '短袖', 1693238627),
(1531, 74, 1, '风格', '复古风', 1693238627),
(1532, 74, 1, '衣门襟', '套头', 1693238627),
(1533, 74, 1, '裙型', '大摆型', 1693238627),
(1534, 74, 1, '组合形式', '单件', 1693238627),
(1535, 74, 0, '款式类别', '图案花纹', 1693238627);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_params_template`
--

DROP TABLE IF EXISTS `sxo_goods_params_template`;
CREATE TABLE `sxo_goods_params_template` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品分类id（含子级）',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否启用（0否，1是）',
  `config_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '参数配置数量',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品参数模板';

--
-- 转存表中的数据 `sxo_goods_params_template`
--

INSERT INTO `sxo_goods_params_template` (`id`, `category_id`, `name`, `is_enable`, `config_count`, `add_time`, `upd_time`) VALUES
(1, 2, '服饰鞋包连衣裙', 1, 10, 1606554077, 1690026407),
(2, 2, '裤子', 1, 1, 1675757615, 1690032205),
(3, 2, '鞋子', 1, 1, 1675757640, 1690026374);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_params_template_config`
--

DROP TABLE IF EXISTS `sxo_goods_params_template_config`;
CREATE TABLE `sxo_goods_params_template_config` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `template_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '模板id',
  `type` tinyint(3) UNSIGNED DEFAULT 1 COMMENT '展示范围（0全部, 1详情, 2基础）默认1详情',
  `name` char(180) NOT NULL DEFAULT '' COMMENT '参数名称',
  `value` char(230) NOT NULL DEFAULT '' COMMENT '参数值',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品参数模板值';

--
-- 转存表中的数据 `sxo_goods_params_template_config`
--

INSERT INTO `sxo_goods_params_template_config` (`id`, `template_id`, `type`, `name`, `value`, `add_time`) VALUES
(71, 3, 1, '款式', '常规', 1690026374),
(72, 1, 2, '款式', '长款连衣裙', 1690026407),
(73, 1, 0, '流行元素/工艺', '树脂固色', 1690026407),
(74, 1, 2, '适用年龄', '30-34周岁', 1690026407),
(75, 1, 1, '图案', '纯色', 1690026407),
(76, 1, 1, '袖长', '短袖', 1690026407),
(77, 1, 1, '风格', '复古风', 1690026407),
(78, 1, 1, '衣门襟', '套头', 1690026407),
(79, 1, 1, '裙型', '大摆型', 1690026407),
(80, 1, 1, '组合形式', '单件', 1690026407),
(81, 1, 0, '款式类别', '图案花纹', 1690026407),
(82, 2, 0, '长度', '中款', 1690032205);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_photo`
--

DROP TABLE IF EXISTS `sxo_goods_photo`;
CREATE TABLE `sxo_goods_photo` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `images` char(255) NOT NULL DEFAULT '' COMMENT '图片',
  `is_show` tinyint(3) UNSIGNED DEFAULT 1 COMMENT '是否显示（0否, 1是）',
  `sort` tinyint(3) UNSIGNED DEFAULT 0 COMMENT '顺序',
  `add_time` int(10) UNSIGNED DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品相册图片';

--
-- 转存表中的数据 `sxo_goods_photo`
--

INSERT INTO `sxo_goods_photo` (`id`, `goods_id`, `images`, `is_show`, `sort`, `add_time`) VALUES
(2167, 1, '/static/upload/images/goods/2023/08/12/1691832026565424.png', 1, 0, 1691832446),
(2177, 8, '/static/upload/images/goods/2023/08/14/1691981231897653.png', 1, 0, 1691981714),
(2210, 7, '/static/upload/images/goods/2023/08/15/1692080737909286.png', 1, 0, 1693234922),
(2211, 110, '/static/upload/images/goods/2023/08/15/1692079963737575.png', 1, 0, 1693234972),
(2212, 109, '/static/upload/images/goods/2023/08/15/1692079154656558.png', 1, 0, 1693235270),
(2213, 98, '/static/upload/images/goods/2023/08/15/1692078380856633.png', 1, 0, 1693235317),
(2214, 108, '/static/upload/images/goods/2023/08/15/1692075761195908.png', 1, 0, 1693235460),
(2215, 107, '/static/upload/images/goods/2023/08/15/1692072217123569.png', 1, 0, 1693235615),
(2216, 102, '/static/upload/images/goods/2023/08/15/1692070668885949.png', 1, 0, 1693235663),
(2218, 105, '/static/upload/images/goods/2023/08/14/1692002344829640.png', 1, 0, 1693235790),
(2219, 101, '/static/upload/images/goods/2023/08/14/1691996824307963.png', 1, 0, 1693235925),
(2220, 106, '/static/upload/images/goods/2023/08/14/1692002974459810.png', 1, 0, 1693235979),
(2221, 104, '/static/upload/images/goods/2023/08/14/1692000963306994.png', 1, 0, 1693236042),
(2222, 103, '/static/upload/images/goods/2023/08/14/1691999334494128.png', 1, 0, 1693236446),
(2223, 100, '/static/upload/images/goods/2023/08/14/1691995464796317.png', 1, 0, 1693236598),
(2224, 99, '/static/upload/images/goods/2023/08/14/1691994396257476.png', 1, 0, 1693236668),
(2225, 9, '/static/upload/images/goods/2023/08/14/1691980079575635.png', 1, 0, 1693237071),
(2226, 25, '/static/upload/images/goods/2023/08/12/1691824121231788.png', 1, 0, 1693237146),
(2227, 32, '/static/upload/images/goods/2023/08/12/1691823431903231.png', 1, 0, 1693237177),
(2228, 5, '/static/upload/images/goods/2023/08/12/1691826776365755.png', 1, 0, 1693237221),
(2229, 4, '/static/upload/images/goods/2023/08/12/1691831046790794.png', 1, 0, 1693237649),
(2230, 4, '/static/upload/images/goods/2023/08/12/1691828468899201.png', 1, 1, 1693237649),
(2232, 11, '/static/upload/images/goods/2023/08/12/1691836236770925.png', 1, 0, 1693237903),
(2233, 12, '/static/upload/images/goods/2023/08/12/1691835373537126.png', 1, 0, 1693237967),
(2234, 10, '/static/upload/images/goods/2023/08/14/1691977878897545.png', 1, 0, 1693238016),
(2235, 74, '/static/upload/images/goods/2023/08/12/1691834999411165.png', 1, 0, 1693238627),
(2236, 2, '/static/upload/images/goods/2023/08/12/1691830293182918.png', 1, 0, 1693284683),
(2237, 3, '/static/upload/images/goods/2023/08/12/1691830511475725.png', 1, 0, 1693284931),
(2238, 3, '/static/upload/images/goods/2023/08/12/1691829141472866.png', 1, 1, 1693284931),
(2239, 6, '/static/upload/images/goods/2023/08/12/1691824925751938.png', 1, 0, 1693285023);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_spec_base`
--

DROP TABLE IF EXISTS `sxo_goods_spec_base`;
CREATE TABLE `sxo_goods_spec_base` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '销售价',
  `original_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '原价',
  `inventory` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '库存',
  `buy_min_number` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '起购数',
  `buy_max_number` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '限购数',
  `weight` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '重量（kg）',
  `volume` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '体积（m³）',
  `coding` char(80) NOT NULL DEFAULT '' COMMENT '编码',
  `barcode` char(80) NOT NULL DEFAULT '' COMMENT '条形码',
  `extends` longtext DEFAULT NULL COMMENT '扩展数据(json格式存储)',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品规格基础';

--
-- 转存表中的数据 `sxo_goods_spec_base`
--

INSERT INTO `sxo_goods_spec_base` (`id`, `goods_id`, `price`, `original_price`, `inventory`, `buy_min_number`, `buy_max_number`, `weight`, `volume`, `coding`, `barcode`, `extends`, `add_time`) VALUES
(5321, 63, 23.00, 0.00, 0, 0, 0, 0.00, 0.00, 'bm209q9EmG2-6', '', NULL, 1677405612),
(5736, 80, 0.00, 0.00, 0, 0, 0, 0.00, 0.00, 'bm237dK0wTV-1', '', NULL, 1686624667),
(5737, 80, 0.00, 0.00, 0, 0, 0, 0.00, 0.00, 'bm238Q2fNrb-1', '', NULL, 1686624667),
(5738, 80, 0.00, 0.00, 0, 0, 0, 0.00, 0.00, 'bm239pXbLje-1', '', NULL, 1686624667),
(5739, 80, 0.00, 0.00, 0, 0, 0, 0.00, 0.00, 'bm240XI0A0q-1', '', NULL, 1686624667),
(5740, 80, 0.00, 0.00, 0, 0, 0, 0.00, 0.00, 'bm2419CpCUI-1', '', NULL, 1686624667),
(6331, 1, 2100.12, 9200.00, 1517, 0, 0, 12.31, 32.00, 'bbbbmmmm', '991498765456', NULL, 1691832446),
(6361, 8, 268.00, 299.00, 4, 0, 0, 0.00, 0.00, '', '6901236345686', NULL, 1691981714),
(6362, 8, 356.00, 428.00, 71, 0, 0, 0.00, 0.00, '', '6901236345687', NULL, 1691981714),
(6431, 7, 130.00, 700.00, 877, 0, 0, 0.00, 0.00, '', '6901236345684', NULL, 1693234922),
(6432, 110, 489.00, 671.00, 88888, 0, 0, 0.00, 0.00, '', '', NULL, 1693234972),
(6433, 109, 200.00, 800.00, 88888, 0, 0, 0.00, 0.00, '', '', NULL, 1693235270),
(6434, 98, 26900.00, 28000.00, 88888, 1, 5, 1.00, 0.00, '', '', NULL, 1693235317),
(6435, 108, 56390.00, 69870.00, 88888, 0, 0, 0.00, 0.00, '', '', NULL, 1693235460),
(6436, 107, 800.00, 1200.00, 88888, 0, 0, 0.00, 0.00, '', '', NULL, 1693235615),
(6437, 102, 680.00, 900.00, 88888, 0, 0, 0.00, 0.00, '', '', NULL, 1693235663),
(6439, 105, 5000.00, 6600.00, 88888, 0, 0, 0.00, 0.00, '', '', NULL, 1693235790),
(6440, 101, 13997.00, 16890.00, 88888, 0, 0, 0.00, 0.00, '', '', NULL, 1693235925),
(6441, 106, 5000.00, 19000.00, 88888, 0, 0, 0.00, 0.00, '', '', NULL, 1693235979),
(6442, 104, 9800.00, 13000.00, 88888, 0, 0, 0.00, 0.00, '', '', NULL, 1693236042),
(6443, 103, 489.00, 670.00, 88888, 0, 0, 0.00, 0.00, '', '', NULL, 1693236446),
(6444, 100, 160.00, 200.00, 88888, 0, 0, 0.00, 0.00, '', '', NULL, 1693236598),
(6445, 99, 88.00, 120.00, 88888, 0, 0, 0.00, 0.00, '', '', NULL, 1693236668),
(6446, 9, 120.00, 160.00, 888888, 0, 0, 17.00, 0.00, '', '', NULL, 1693237071),
(6447, 9, 120.00, 160.00, 888888, 0, 0, 17.00, 0.00, '', '', NULL, 1693237071),
(6448, 9, 120.00, 160.00, 888888, 0, 0, 17.00, 0.00, '', '', NULL, 1693237071),
(6449, 9, 120.00, 160.00, 888888, 0, 0, 17.00, 0.00, '', '', NULL, 1693237071),
(6450, 9, 120.00, 160.00, 888888, 0, 0, 17.00, 0.00, '', '', NULL, 1693237071),
(6451, 9, 120.00, 160.00, 888888, 0, 0, 17.00, 0.00, '', '', NULL, 1693237071),
(6452, 9, 120.00, 160.00, 888888, 0, 0, 17.00, 0.00, '', '', NULL, 1693237071),
(6453, 9, 120.00, 160.00, 888888, 0, 0, 17.00, 0.00, '', '', NULL, 1693237071),
(6454, 9, 120.00, 160.00, 888888, 0, 0, 17.00, 0.00, '', '', NULL, 1693237071),
(6455, 9, 120.00, 160.00, 888888, 0, 0, 17.00, 0.00, '', '', NULL, 1693237071),
(6456, 9, 120.00, 160.00, 888888, 0, 0, 17.00, 0.00, '', '', NULL, 1693237071),
(6457, 9, 120.00, 160.00, 888888, 0, 0, 17.00, 0.00, '', '', NULL, 1693237071),
(6458, 25, 3400.00, 9800.00, 888888, 2, 4, 0.00, 0.00, '', '', NULL, 1693237146),
(6459, 32, 2600.00, 3200.00, 1517, 0, 0, 12.00, 0.00, '12322', '6901236345698', NULL, 1693237177),
(6460, 5, 5000.00, 6800.00, 877, 0, 0, 0.00, 0.00, '', '6901236345674', NULL, 1693237221),
(6461, 4, 1999.00, 2300.00, 878, 0, 0, 0.00, 0.00, '', '6901236345673', NULL, 1693237649),
(6465, 11, 258.00, 268.00, 888888, 0, 0, 0.00, 0.00, 'bm82VKULTv-1', '6901236345700', '{\"plugins_distribution_rules_1\":\"r|5\\nr|3\\ns|2\",\"plugins_distribution_down_rules_1\":\"r|10\"}', 1693237903),
(6466, 11, 238.00, 0.00, 888888, 0, 0, 0.00, 0.00, 'bm83UTmxgp-1', '6901236345710', '{\"plugins_distribution_rules_1\":\"r|5\\nr|3\\ns|2\",\"plugins_distribution_down_rules_1\":\"r|1\"}', 1693237903),
(6467, 11, 160.00, 422.00, 888888, 0, 0, 0.00, 0.00, 'bm84zr0fK4-1', '6901236345720', '{\"plugins_distribution_rules_20191202164330784159\":\"r|8\",\"plugins_distribution_rules_20191204113948916981\":\"\"}', 1693237903),
(6468, 12, 10.00, 1.00, 500, 0, 0, 0.00, 12.00, '001', '6907992512761', '{\"plugins_wholesale_alone_newbuy_rules\":\"\",\"plugins_wholesale_alone_repurchase_rules\":\"\",\"plugins_wholesale_newbuy_rules_100\":\"8\",\"plugins_wholesale_newbuy_rules_200\":\"7\",\"plugins_wholesale_newbuy_rules_300\":\"6\",\"plugins_wholesale_repurchase_rules_100_0\":\"7\",\"plugins_wholesale_repurchase_rules_200_0\":\"6\",\"plugins_wholesale_repurchase_rules_300_0\":\"5\",\"plugins_distribution_rules_1\":\"\",\"plugins_distribution_down_rules_1\":\"\",\"plugins_distribution_self_buy_rules_1\":\"\",\"plugins_distribution_force_current_user_rules_1\":\"\",\"plugins_distribution_rules_2\":\"\",\"plugins_distribution_down_rules_2\":\"\",\"plugins_distribution_self_buy_rules_2\":\"\",\"plugins_distribution_force_current_user_rules_2\":\"\"}', 1693237967),
(6469, 12, 20.00, 10.00, 500, 0, 0, 0.00, 3.02, '', '', '{\"plugins_wholesale_alone_newbuy_rules\":\"\",\"plugins_wholesale_newbuy_rules_10\":\"\",\"plugins_wholesale_newbuy_rules_20\":\"\",\"plugins_distribution_rules_1\":\"\",\"plugins_distribution_down_rules_1\":\"\",\"plugins_distribution_self_buy_rules_1\":\"\",\"plugins_distribution_force_current_user_rules_1\":\"\"}', 1693237967),
(6470, 12, 30.00, 30.00, 500, 0, 0, 0.00, 0.00, '', '', '{\"plugins_wholesale_newbuy_rules_5\":\"9\",\"plugins_wholesale_newbuy_rules_10\":\"\",\"plugins_wholesale_newbuy_rules_20\":\"8\",\"plugins_wholesale_newbuy_rules_50\":\"7.8\",\"plugins_wholesale_newbuy_rules_100\":\"r|0.35\",\"plugins_wholesale_repurchase_rules_10_3000\":\"9\",\"plugins_wholesale_repurchase_rules_20_6000\":\"8\",\"plugins_wholesale_repurchase_rules_50_10000\":\"7\",\"plugins_wholesale_repurchase_rules_100_20000\":\"r|0.2\"}', 1693237967),
(6471, 12, 40.00, 673.00, 500, 0, 0, 0.00, 0.02, '', '', '{\"plugins_wholesale_alone_newbuy_rules\":\"\",\"plugins_wholesale_newbuy_rules_10\":\"s|160\",\"plugins_wholesale_newbuy_rules_20\":\"s|130\",\"plugins_distribution_rules_1\":\"\",\"plugins_distribution_down_rules_1\":\"\",\"plugins_distribution_self_buy_rules_1\":\"\",\"plugins_distribution_force_current_user_rules_1\":\"\"}', 1693237967),
(6472, 10, 228.00, 568.00, 88888, 0, 0, 0.00, 0.00, '', '6901236345685', NULL, 1693238016),
(6473, 74, 120.00, 160.00, 20, 0, 0, 17.00, 0.00, '', '', NULL, 1693238627),
(6474, 74, 120.00, 160.00, 20, 0, 0, 17.00, 0.00, '', '', NULL, 1693238627),
(6475, 74, 120.00, 160.00, 20, 0, 0, 17.00, 0.00, '', '', NULL, 1693238627),
(6476, 2, 4500.00, 6800.00, 88888, 0, 0, 23.00, 0.00, '', '6901236345681', NULL, 1693284683),
(6477, 2, 4800.00, 6600.00, 88888, 0, 0, 32.00, 0.00, '', '6901236345682', NULL, 1693284683),
(6478, 2, 5500.00, 6000.00, 88888, 0, 0, 11.00, 0.00, '', '6901236345683', NULL, 1693284683),
(6479, 3, 15900.00, 19000.00, 888888, 0, 4, 0.00, 0.00, '', '6901236345672', NULL, 1693284931),
(6480, 6, 3500.00, 5400.00, 877, 0, 0, 0.00, 0.00, '', '6901236345695', '{\"plugins_distribution_rules_1\":\"r|10\\nr|10\\nr|10\",\"plugins_distribution_down_rules_1\":\"\"}', 1693285023);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_spec_template`
--

DROP TABLE IF EXISTS `sxo_goods_spec_template`;
CREATE TABLE `sxo_goods_spec_template` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品分类id（含子级）',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '名称',
  `content` text DEFAULT NULL COMMENT '内容',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否启用（0否，1是）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品规格模板';

--
-- 转存表中的数据 `sxo_goods_spec_template`
--

INSERT INTO `sxo_goods_spec_template` (`id`, `category_id`, `name`, `content`, `is_enable`, `add_time`, `upd_time`) VALUES
(2, 2, '颜色', '白色,黑色,蓝色,咖啡色,浅蓝色,深灰色,灰色,紫色,浅灰色,黄色,红色', 1, 1661352726, 1661352759),
(3, 2, '尺码', 'S,M,L,XL,XXL,XXXL', 1, 1661415839, 1693117708);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_spec_type`
--

DROP TABLE IF EXISTS `sxo_goods_spec_type`;
CREATE TABLE `sxo_goods_spec_type` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `value` text NOT NULL COMMENT '类型值（json字符串存储）',
  `name` char(180) NOT NULL DEFAULT '' COMMENT '类型名称',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品规格类型';

--
-- 转存表中的数据 `sxo_goods_spec_type`
--

INSERT INTO `sxo_goods_spec_type` (`id`, `goods_id`, `value`, `name`, `add_time`) VALUES
(1669, 63, '[{\"name\":\"23233\",\"images\":\"\"}]', '规格1', 1677405612),
(1793, 80, '[{\"name\":\"Naranja\",\"images\":\"\"},{\"name\":\"Rojo\",\"images\":\"\"},{\"name\":\"Verde\",\"images\":\"\"},{\"name\":\"Azul\",\"images\":\"\"},{\"name\":\"Negro\",\"images\":\"\"}]', '规格1', 1686624667),
(1924, 8, '[{\"name\":\"红色\",\"images\":\"\"},{\"name\":\"蓝色\",\"images\":\"\"}]', '颜色', 1691981714),
(1932, 9, '[{\"name\":\"白色\",\"images\":\"\\/static\\/upload\\/images\\/goods\\/2023\\/08\\/14\\/1691980696623584.jpeg\"},{\"name\":\"粉色\",\"images\":\"\\/static\\/upload\\/images\\/goods\\/2023\\/08\\/14\\/1691980688105551.jpeg\"},{\"name\":\"黑色\",\"images\":\"\\/static\\/upload\\/images\\/goods\\/2023\\/08\\/14\\/1691980683406570.jpeg\"}]', '颜色', 1693237071),
(1933, 9, '[{\"name\":\"S\",\"images\":\"\"},{\"name\":\"M\",\"images\":\"\"},{\"name\":\"L\",\"images\":\"\"},{\"name\":\"XL\",\"images\":\"\"}]', '尺码', 1693237071),
(1935, 11, '[{\"name\":\"M\",\"images\":\"\"},{\"name\":\"L\",\"images\":\"\"},{\"name\":\"XL\",\"images\":\"\"}]', '尺码', 1693237903),
(1936, 12, '[{\"name\":\"粉色\",\"images\":\"\"},{\"name\":\"白色\",\"images\":\"\"}]', '颜色', 1693237967),
(1937, 12, '[{\"name\":\"S+S\",\"images\":\"\"},{\"name\":\"M+M\",\"images\":\"\"}]', '尺码', 1693237967),
(1938, 74, '[{\"name\":\"白色\",\"images\":\"\\/static\\/upload\\/images\\/goods\\/2023\\/08\\/12\\/1691834878159970.png\"},{\"name\":\"粉色\",\"images\":\"\\/static\\/upload\\/images\\/goods\\/2023\\/08\\/14\\/1691994898577354.png\"},{\"name\":\"黑色\",\"images\":\"\\/static\\/upload\\/images\\/goods\\/2023\\/08\\/15\\/1692080277838680.png\"}]', '颜色', 1693238627),
(1939, 2, '[{\"name\":\"套餐二\",\"images\":\"\"}]', '套餐', 1693284683),
(1940, 2, '[{\"name\":\"金色\",\"images\":\"\"},{\"name\":\"银色\",\"images\":\"\"}]', '颜色', 1693284683),
(1941, 2, '[{\"name\":\"32G\",\"images\":\"\"},{\"name\":\"128G\",\"images\":\"\"},{\"name\":\"64G\",\"images\":\"\"}]', '容量', 1693284683);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_goods_spec_value`
--

DROP TABLE IF EXISTS `sxo_goods_spec_value`;
CREATE TABLE `sxo_goods_spec_value` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `goods_spec_base_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品规格基础id',
  `value` char(230) NOT NULL DEFAULT '' COMMENT '规格值',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商品规格值';

--
-- 转存表中的数据 `sxo_goods_spec_value`
--

INSERT INTO `sxo_goods_spec_value` (`id`, `goods_id`, `goods_spec_base_id`, `value`, `add_time`) VALUES
(4996, 0, 0, '', 0),
(7927, 63, 5321, '23233', 1677405612),
(8624, 80, 5736, 'Naranja', 1686624667),
(8625, 80, 5737, 'Rojo', 1686624667),
(8626, 80, 5738, 'Verde', 1686624667),
(8627, 80, 5739, 'Azul', 1686624667),
(8628, 80, 5740, 'Negro', 1686624667),
(9258, 8, 6361, '红色', 1691981714),
(9259, 8, 6362, '蓝色', 1691981714),
(9335, 9, 6446, '白色', 1693237071),
(9336, 9, 6446, 'S', 1693237071),
(9337, 9, 6447, '白色', 1693237071),
(9338, 9, 6447, 'M', 1693237071),
(9339, 9, 6448, '白色', 1693237071),
(9340, 9, 6448, 'L', 1693237071),
(9341, 9, 6449, '白色', 1693237071),
(9342, 9, 6449, 'XL', 1693237071),
(9343, 9, 6450, '粉色', 1693237071),
(9344, 9, 6450, 'S', 1693237071),
(9345, 9, 6451, '粉色', 1693237071),
(9346, 9, 6451, 'M', 1693237071),
(9347, 9, 6452, '粉色', 1693237071),
(9348, 9, 6452, 'L', 1693237071),
(9349, 9, 6453, '粉色', 1693237071),
(9350, 9, 6453, 'XL', 1693237071),
(9351, 9, 6454, '黑色', 1693237071),
(9352, 9, 6454, 'S', 1693237071),
(9353, 9, 6455, '黑色', 1693237071),
(9354, 9, 6455, 'M', 1693237071),
(9355, 9, 6456, '黑色', 1693237071),
(9356, 9, 6456, 'L', 1693237071),
(9357, 9, 6457, '黑色', 1693237071),
(9358, 9, 6457, 'XL', 1693237071),
(9362, 11, 6465, 'M', 1693237903),
(9363, 11, 6466, 'L', 1693237903),
(9364, 11, 6467, 'XL', 1693237903),
(9365, 12, 6468, '粉色', 1693237967),
(9366, 12, 6468, 'S+S', 1693237967),
(9367, 12, 6469, '粉色', 1693237967),
(9368, 12, 6469, 'M+M', 1693237967),
(9369, 12, 6470, '白色', 1693237967),
(9370, 12, 6470, 'S+S', 1693237967),
(9371, 12, 6471, '白色', 1693237967),
(9372, 12, 6471, 'M+M', 1693237967),
(9373, 74, 6473, '白色', 1693238627),
(9374, 74, 6474, '粉色', 1693238627),
(9375, 74, 6475, '黑色', 1693238627),
(9376, 2, 6476, '套餐二', 1693284683),
(9377, 2, 6476, '金色', 1693284683),
(9378, 2, 6476, '32G', 1693284683),
(9379, 2, 6477, '套餐二', 1693284683),
(9380, 2, 6477, '金色', 1693284683),
(9381, 2, 6477, '128G', 1693284683),
(9382, 2, 6478, '套餐二', 1693284683),
(9383, 2, 6478, '银色', 1693284683),
(9384, 2, 6478, '64G', 1693284683);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_layout`
--

DROP TABLE IF EXISTS `sxo_layout`;
CREATE TABLE `sxo_layout` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `type` char(60) NOT NULL DEFAULT '' COMMENT '类型',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `config` longtext DEFAULT NULL COMMENT '配置信息',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='布局配置';

--
-- 转存表中的数据 `sxo_layout`
--

INSERT INTO `sxo_layout` (`id`, `type`, `name`, `config`, `is_enable`, `add_time`, `upd_time`) VALUES
(1, 'layout_index_home_data', '首页', '[{\"value\":\"100\",\"status\":1,\"config\":[],\"children\":[{\"config\":[],\"children\":[{\"value\":\"many-images\",\"name\":\"多图\",\"config\":{\"view_list_show_style\":\"routine\",\"view_list_show_style_value\":\"\",\"style_background_color\":\"\",\"style_border_radius\":\"\",\"style_border_width_top\":\"\",\"style_border_width_right\":\"\",\"style_border_width_bottom\":\"\",\"style_border_width_left\":\"\",\"style_border_color\":\"\",\"style_margin_top\":\"\",\"style_margin_right\":\"\",\"style_margin_bottom\":\"\",\"style_margin_left\":\"\",\"style_padding_top\":\"\",\"style_padding_right\":\"\",\"style_padding_bottom\":\"\",\"style_padding_left\":\"\",\"style_media_fixed_width\":\"\",\"style_media_fixed_height\":\"800\",\"style_media_fixed_border_radius\":\"\",\"style_border_style_top\":\"\",\"style_border_style_right\":\"\",\"style_border_style_bottom\":\"\",\"style_border_style_left\":\"\",\"style_media_fixed_is_width\":\"1\",\"style_media_fixed_is_height\":\"1\",\"style_media_fixed_is_auto\":\"1\",\"style_media_fixed_is_cover\":\"1\",\"style_mouse_hover_images_amplify_value\":\"1\",\"frontend_config\":{\"style\":\"\",\"nav_dot_ent\":\"\",\"list_ent\":\"\",\"media_fixed\":{\"media_container_ent\":\"module-fixed-doc \",\"media_container_style\":\"height:800px;\",\"media_ent\":\"module-fixed-doc-ent-width module-fixed-doc-ent-height module-fixed-doc-ent-auto module-fixed-doc-ent-cover module-mouse-hover-images-amplify \"}},\"data_list\":[{\"images\":\"\\/static\\/upload\\/images\\/goods\\/2019\\/01\\/14\\/1547454702543219.jpg\",\"type\":\"\",\"name\":\"\",\"value\":\"\"},{\"images\":\"\\/static\\/upload\\/images\\/goods\\/2019\\/01\\/14\\/1547454702543219.jpg\",\"type\":\"\",\"name\":\"\",\"value\":\"\"},{\"images\":\"\\/static\\/upload\\/images\\/goods\\/2019\\/01\\/14\\/1547454702814719.jpg\",\"type\":\"\",\"name\":\"\",\"value\":\"\"}]}}]}]}]', 1, 1626773296, 1682585770);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_link`
--

DROP TABLE IF EXISTS `sxo_link`;
CREATE TABLE `sxo_link` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '导航名称',
  `url` char(255) NOT NULL DEFAULT '' COMMENT 'url地址',
  `describe` char(60) NOT NULL DEFAULT '' COMMENT '描述',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `is_new_window_open` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否新窗口打开（0否，1是）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='友情链接';

--
-- 转存表中的数据 `sxo_link`
--

INSERT INTO `sxo_link` (`id`, `name`, `url`, `describe`, `sort`, `is_enable`, `is_new_window_open`, `add_time`, `upd_time`) VALUES
(1, 'uniapp主题', 'https://gitee.com/zongzhige/shopxo-uniapp', 'ShopXO主题uniapp端源码', 1, 1, 1, 1486292373, 1635056246),
(12, 'AmazeUI', 'http://amazeui.shopxo.net/', 'AmazeUI国内首个HTML5框架', 4, 1, 1, 1486353476, 1563088005),
(13, '龚哥哥的博客', 'http://gong.gg/', '龚哥哥的博客', 2, 1, 1, 1486353528, 1592320862),
(14, 'ThinkPHP', 'http://www.thinkphp.cn/', 'ThinkPHP', 3, 1, 1, 1487919160, 0),
(15, 'ShopXO', 'http://shopxo.net', 'ShopXO企业级B2C免费开源电商系统', 0, 1, 1, 1533711881, 1592320866),
(16, 'Gitee', 'https://gitee.com/zongzhige/shopxo', '代码托管平台', 0, 1, 1, 1547450105, 1626528557),
(17, 'GitHub', 'https://github.com/gongfuxiang/shopxo', '代码托管平台', 0, 1, 1, 1547450145, 1563088069),
(18, 'ShopXO应用商店', 'http://store.shopxo.net/', 'ShopXO应用商店', 0, 1, 1, 1563088117, 1563088129),
(20, '宝塔面板', 'https://www.bt.cn/?invite_code=MV9kZHh6b2Y=', '宝塔服务器控制面板', 5, 1, 1, 1566531114, 0),
(21, '西部数码', 'https://www.west.cn/active/freetc/?ReferenceID=934057', '西部数码国内知名服务器提供商', 6, 1, 1, 1566531132, 0),
(22, '纵之格科技', 'https://www.zongzhige.com/', '纵之格科技', 0, 1, 1, 1594273577, 1690027445);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_message`
--

DROP TABLE IF EXISTS `sxo_message`;
CREATE TABLE `sxo_message` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `system_type` char(60) NOT NULL DEFAULT 'default' COMMENT '系统类型（默认 default, 其他按照SYSTEM_TYPE常量类型）',
  `title` char(60) NOT NULL DEFAULT '' COMMENT '标题',
  `detail` char(255) NOT NULL DEFAULT '' COMMENT '详情',
  `business_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '业务id',
  `business_type` char(180) NOT NULL DEFAULT '' COMMENT '业务类型，字符串（如：订单、充值、提现、等...）',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '消息类型（0普通通知, ...）',
  `is_read` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否已读（0否, 1是）',
  `is_delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已删除（0否, 大于0删除时间）',
  `user_is_delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户是否已删除（0否, 大于0删除时间）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='消息';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_navigation`
--

DROP TABLE IF EXISTS `sxo_navigation`;
CREATE TABLE `sxo_navigation` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '导航名称',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '自定义url地址',
  `value` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '数据 id',
  `data_type` char(30) NOT NULL DEFAULT '' COMMENT '数据类型（custom:自定义导航, article_class:文章分类, customview:自定义页面）',
  `nav_type` char(30) NOT NULL DEFAULT '' COMMENT '导航类型（header:顶部导航, footer:底部导航）',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_show` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示（0否，1是）',
  `is_new_window_open` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否新窗口打开（0否，1是）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='导航';

--
-- 转存表中的数据 `sxo_navigation`
--

INSERT INTO `sxo_navigation` (`id`, `pid`, `name`, `url`, `value`, `data_type`, `nav_type`, `sort`, `is_show`, `is_new_window_open`, `add_time`, `upd_time`) VALUES
(24, 54, '服饰鞋包', '', 2, 'goods_category', 'header', 0, 1, 0, 1539150026, 1690027506),
(34, 0, '信息咨询', 'http://shopxo.net/', 0, 'custom', 'footer', 0, 1, 1, 1554795563, 1592232213),
(35, 0, '客户服务', 'http://shopxo.net/', 0, 'custom', 'footer', 0, 1, 0, 1554795788, 0),
(36, 0, '支付方式', 'http://shopxo.net/', 0, 'custom', 'footer', 0, 1, 0, 1554796068, 0),
(37, 0, '会员中心', 'http://shopxo.net/', 0, 'custom', 'footer', 0, 1, 0, 1554796082, 0),
(38, 34, '关于ShopXO', '', 29, 'article', 'footer', 0, 1, 0, 1554796171, 0),
(39, 34, '联系我们', '', 28, 'article', 'footer', 0, 1, 0, 1554796188, 0),
(40, 34, '招聘英才', '', 27, 'article', 'footer', 0, 1, 0, 1554796202, 0),
(41, 34, '合作及洽谈', '', 26, 'article', 'footer', 0, 1, 0, 1554796211, 0),
(42, 35, '如何注册成为会员', '', 1, 'article', 'footer', 0, 1, 0, 1554796239, 0),
(43, 35, '积分细则', '', 3, 'article', 'footer', 0, 1, 0, 1554796245, 0),
(44, 35, '如何搜索', '', 5, 'article', 'footer', 0, 1, 0, 1554796253, 0),
(45, 36, '分期付款', '', 12, 'article', 'footer', 0, 1, 0, 1554796281, 0),
(46, 36, '邮局汇款', '', 13, 'article', 'footer', 0, 1, 0, 1554796296, 0),
(47, 36, '在线支付', '', 16, 'article', 'footer', 0, 1, 0, 1554796312, 0),
(48, 36, '公司转账', '', 14, 'article', 'footer', 0, 1, 0, 1554796327, 0),
(49, 36, '如何注册支付宝', '', 15, 'article', 'footer', 0, 1, 0, 1554796339, 0),
(50, 37, '会员修改密码', '', 22, 'article', 'footer', 0, 1, 0, 1554796367, 0),
(51, 37, '会员修改个人资料', '', 23, 'article', 'footer', 0, 1, 0, 1554796375, 0),
(52, 37, '修改收货地址', '', 25, 'article', 'footer', 0, 1, 0, 1554796386, 0),
(53, 37, '如何管理店铺', '', 7, 'article', 'footer', 0, 1, 0, 1554796399, 0),
(54, 0, '商品分类', 'http://shopxo.net/', 0, 'custom', 'header', 0, 1, 0, 1556015784, 1690030842),
(55, 0, '如何注册成为会员', '', 1, 'article', 'footer', 0, 1, 0, 1616685505, 0),
(59, 0, 'ShopXO', 'https://shopxo.net/', 0, 'custom', 'header', 0, 1, 1, 1693131427, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_order`
--

DROP TABLE IF EXISTS `sxo_order`;
CREATE TABLE `sxo_order` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `order_no` char(60) NOT NULL DEFAULT '' COMMENT '订单号',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `warehouse_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '仓库id',
  `payment_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付方式id',
  `system_type` char(60) NOT NULL DEFAULT 'default' COMMENT '系统类型（默认 default, 其他按照SYSTEM_TYPE常量类型）',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单状态（0待确认, 1已确认/待支付, 2已支付/待发货, 3已发货/待收货, 4已完成, 5已取消, 6已关闭）',
  `pay_status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付状态（0未支付, 1已支付, 2已退款, 3部分退款）',
  `extension_data` longtext DEFAULT NULL COMMENT '扩展展示数据',
  `buy_number_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '购买商品总数量',
  `increase_price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '增加的金额',
  `preferential_price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '优惠金额',
  `price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '订单单价',
  `total_price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '订单总价(订单最终价格)',
  `pay_price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '已支付金额',
  `refund_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '退款金额',
  `returned_quantity` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '退货数量',
  `client_type` char(30) NOT NULL DEFAULT '' COMMENT '客户端类型（pc, h5, ios, android, alipay, weixin, baidu）取APPLICATION_CLIENT_TYPE常量值',
  `order_model` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单模式（0销售型, 1展示型, 2自提点, 3虚拟销售）',
  `is_under_line` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否线下支付（0否，1是）',
  `user_note` char(255) NOT NULL DEFAULT '' COMMENT '用户备注',
  `pay_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付时间',
  `confirm_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '确认时间',
  `delivery_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发货时间',
  `cancel_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '取消时间',
  `collect_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收货时间',
  `close_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关闭时间',
  `comments_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论时间',
  `is_comments` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已评论（0否, 大于0评论时间）',
  `user_is_comments` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户是否已评论（0否, 大于0评论时间）',
  `is_delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已删除（0否, 大于0删除时间）',
  `user_is_delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户是否已删除（0否, 大于0删除时间）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单';

--
-- 转存表中的数据 `sxo_order`
--

INSERT INTO `sxo_order` (`id`, `order_no`, `user_id`, `warehouse_id`, `payment_id`, `system_type`, `status`, `pay_status`, `extension_data`, `buy_number_count`, `increase_price`, `preferential_price`, `price`, `total_price`, `pay_price`, `refund_price`, `returned_quantity`, `client_type`, `order_model`, `is_under_line`, `user_note`, `pay_time`, `confirm_time`, `delivery_time`, `cancel_time`, `collect_time`, `close_time`, `comments_time`, `is_comments`, `user_is_comments`, `is_delete_time`, `user_is_delete_time`, `add_time`, `upd_time`) VALUES
(1, '20240609233114010112', 1, 1, 2, 'default', 1, 0, '', 1, 0.00, 0.00, 4800.00, 4800.00, 0.00, 0.00, 0, 'pc', 0, 0, '', 0, 1717947074, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1717947074, 0),
(2, '20240610004244017997', 1, 1, 2, 'default', 1, 0, '', 1, 0.00, 0.00, 2600.00, 2600.00, 0.00, 0.00, 0, 'pc', 0, 0, '', 0, 1717951364, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1717951364, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_order_address`
--

DROP TABLE IF EXISTS `sxo_order_address`;
CREATE TABLE `sxo_order_address` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `order_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `address_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收件地址id',
  `alias` char(60) NOT NULL DEFAULT '' COMMENT '别名',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '收件人-姓名',
  `tel` char(15) NOT NULL DEFAULT '' COMMENT '收件人-电话',
  `province` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收件人-省',
  `city` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收件人-市',
  `county` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收件人-县/区',
  `address` char(200) NOT NULL DEFAULT '' COMMENT '收件人-详细地址',
  `province_name` char(30) NOT NULL DEFAULT '' COMMENT '收件人-省-名称',
  `city_name` char(30) NOT NULL DEFAULT '' COMMENT '收件人-市-名称',
  `county_name` char(30) NOT NULL DEFAULT '' COMMENT '收件人-县/区-名称',
  `lng` decimal(13,10) NOT NULL DEFAULT 0.0000000000 COMMENT '收货地址-经度',
  `lat` decimal(13,10) NOT NULL DEFAULT 0.0000000000 COMMENT '收货地址-纬度',
  `idcard_name` char(60) NOT NULL DEFAULT '' COMMENT '身份证姓名',
  `idcard_number` char(30) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `idcard_front` char(255) NOT NULL DEFAULT '' COMMENT '身份证人像面图片',
  `idcard_back` char(255) NOT NULL DEFAULT '' COMMENT '身份证国微面图片',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单地址';

--
-- 转存表中的数据 `sxo_order_address`
--

INSERT INTO `sxo_order_address` (`id`, `order_id`, `user_id`, `address_id`, `alias`, `name`, `tel`, `province`, `city`, `county`, `address`, `province_name`, `city_name`, `county_name`, `lng`, `lat`, `idcard_name`, `idcard_number`, `idcard_front`, `idcard_back`, `add_time`, `upd_time`) VALUES
(1, 1, 1, 1, '', 'hongchunhua', '18813067855', 1, 36, 458, 'fsdff', '北京市', '北京市', '西城区', 0.0000000000, 0.0000000000, '', '', '', '', 1717947074, 0),
(2, 2, 1, 1, '', 'hongchunhua', '18813067855', 1, 36, 458, 'fsdff', '北京市', '北京市', '西城区', 0.0000000000, 0.0000000000, '', '', '', '', 1717951364, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_order_aftersale`
--

DROP TABLE IF EXISTS `sxo_order_aftersale`;
CREATE TABLE `sxo_order_aftersale` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `order_no` char(60) NOT NULL DEFAULT '' COMMENT '订单号',
  `order_detail_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单详情id',
  `order_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `system_type` char(60) NOT NULL DEFAULT 'default' COMMENT '系统类型（默认 default, 其他按照SYSTEM_TYPE常量类型）',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态（0待确认, 1待退货, 2待审核, 3已完成, 4已拒绝, 5已取消）',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '业务类型（0仅退款, 1退货退款）',
  `refundment` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '退款类型（0原路退回, 1退至钱包, 2手动处理）',
  `reason` char(180) NOT NULL DEFAULT '' COMMENT '申请原因',
  `number` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '退货数量',
  `price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '退款金额',
  `msg` text DEFAULT NULL COMMENT '退款说明',
  `images` text DEFAULT NULL COMMENT '凭证图片（一维数组json存储）',
  `refuse_reason` char(230) NOT NULL DEFAULT '' COMMENT '拒绝原因',
  `express_name` char(60) NOT NULL DEFAULT '' COMMENT '快递名称',
  `express_number` char(60) NOT NULL DEFAULT '' COMMENT '快递单号',
  `apply_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请时间',
  `confirm_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '确认时间',
  `delivery_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '退货时间',
  `audit_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '审核时间',
  `cancel_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '取消时间',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单售后';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_order_aftersale_status_history`
--

DROP TABLE IF EXISTS `sxo_order_aftersale_status_history`;
CREATE TABLE `sxo_order_aftersale_status_history` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `order_aftersale_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单售后id',
  `original_status` char(60) NOT NULL DEFAULT '' COMMENT '原始状态',
  `new_status` char(60) NOT NULL DEFAULT '' COMMENT '最新状态',
  `msg` text DEFAULT NULL COMMENT '操作描述',
  `creator` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建-用户id',
  `creator_name` varchar(60) NOT NULL DEFAULT '' COMMENT '创建人-姓名',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单售后状态历史纪录';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_order_currency`
--

DROP TABLE IF EXISTS `sxo_order_currency`;
CREATE TABLE `sxo_order_currency` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `order_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `currency_name` char(80) NOT NULL DEFAULT '' COMMENT '货币名称',
  `currency_code` char(60) NOT NULL DEFAULT '' COMMENT '货币代码',
  `currency_symbol` char(60) NOT NULL DEFAULT '' COMMENT '货币符号',
  `currency_rate` decimal(7,6) UNSIGNED NOT NULL DEFAULT 0.000000 COMMENT '货币汇率',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单货币';

--
-- 转存表中的数据 `sxo_order_currency`
--

INSERT INTO `sxo_order_currency` (`id`, `order_id`, `user_id`, `currency_name`, `currency_code`, `currency_symbol`, `currency_rate`, `add_time`) VALUES
(1, 1, 1, '人民币', 'RMB', '￥', 0.000000, 1717947074),
(2, 2, 1, '人民币', 'RMB', '￥', 0.000000, 1717951364);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_order_detail`
--

DROP TABLE IF EXISTS `sxo_order_detail`;
CREATE TABLE `sxo_order_detail` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `order_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `title` char(160) NOT NULL DEFAULT '' COMMENT '标题',
  `images` char(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `original_price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '原价',
  `price` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '价格',
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '当前总价(单价*数量)',
  `spec` text DEFAULT NULL COMMENT '规格',
  `buy_number` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '购买数量',
  `model` char(30) NOT NULL DEFAULT '' COMMENT '型号',
  `spec_weight` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '重量（kg）',
  `spec_volume` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '体积（m³）',
  `spec_coding` char(80) NOT NULL DEFAULT '' COMMENT '编码',
  `spec_barcode` char(80) NOT NULL DEFAULT '' COMMENT '条形码',
  `refund_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '退款金额',
  `returned_quantity` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '退货数量',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单详情';

--
-- 转存表中的数据 `sxo_order_detail`
--

INSERT INTO `sxo_order_detail` (`id`, `user_id`, `order_id`, `goods_id`, `title`, `images`, `original_price`, `price`, `total_price`, `spec`, `buy_number`, `model`, `spec_weight`, `spec_volume`, `spec_coding`, `spec_barcode`, `refund_price`, `returned_quantity`, `add_time`, `upd_time`) VALUES
(1, 1, 1, 2, '华为笔记本电脑MateBook 14 2023 13代酷睿版 i5 16G 1T 14英寸轻薄办公本/2K触控全面屏/手机互联 深空灰', '/static/upload/images/goods/2023/08/12/1691830293182918.png', 6600.00, 4800.00, 4800.00, '[{\"type\":\"套餐\",\"value\":\"套餐二\"},{\"type\":\"颜色\",\"value\":\"金色\"},{\"type\":\"容量\",\"value\":\"128G\"}]', 1, '华为笔记本电脑MateBook 14', 32.00, 0.00, '', '6901236345682', 0.00, 0, 1717947074, 0),
(2, 1, 2, 32, '华为畅享 50z 5000万高清AI三摄 5000mAh超能续航 128GB 宝石蓝 大内存鸿蒙智能手机', '/static/upload/images/goods/2023/08/12/1691823431903231.png', 3200.00, 2600.00, 2600.00, '', 1, '华为华为畅享 50z', 12.00, 0.00, '12322', '6901236345698', 0.00, 0, 1717951364, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_order_express`
--

DROP TABLE IF EXISTS `sxo_order_express`;
CREATE TABLE `sxo_order_express` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `order_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `express_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '快递id',
  `express_number` char(60) NOT NULL DEFAULT '' COMMENT '快递单号',
  `note` char(255) NOT NULL DEFAULT '' COMMENT '备注',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单快递';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_order_extraction_code`
--

DROP TABLE IF EXISTS `sxo_order_extraction_code`;
CREATE TABLE `sxo_order_extraction_code` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `order_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `code` char(30) NOT NULL DEFAULT '' COMMENT '取货码',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单自提取货码关联';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_order_fictitious_value`
--

DROP TABLE IF EXISTS `sxo_order_fictitious_value`;
CREATE TABLE `sxo_order_fictitious_value` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `order_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单id',
  `order_detail_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单详情id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `value` text DEFAULT NULL COMMENT '虚拟商品展示数据',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单虚拟销售数据关联';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_order_goods_inventory_log`
--

DROP TABLE IF EXISTS `sxo_order_goods_inventory_log`;
CREATE TABLE `sxo_order_goods_inventory_log` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `order_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单id',
  `order_detail_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单详情id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `order_status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单状态（0待确认, 1已确认/待支付, 2已支付/待发货, 3已发货/待收货, 4已完成, 5已取消, 6已关闭）',
  `original_inventory` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '原库存',
  `new_inventory` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最新库存',
  `is_rollback` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否回滚（0否, 1是）',
  `rollback_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '回滚时间',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单商品库存变更日志';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_order_status_history`
--

DROP TABLE IF EXISTS `sxo_order_status_history`;
CREATE TABLE `sxo_order_status_history` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `order_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单id',
  `original_status` varchar(60) NOT NULL DEFAULT '' COMMENT '原始状态',
  `new_status` varchar(60) NOT NULL DEFAULT '' COMMENT '最新状态',
  `msg` text DEFAULT NULL COMMENT '操作描述',
  `creator` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建-用户id',
  `creator_name` varchar(60) NOT NULL DEFAULT '' COMMENT '创建人-姓名',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单状态历史纪录';

--
-- 转存表中的数据 `sxo_order_status_history`
--

INSERT INTO `sxo_order_status_history` (`id`, `order_id`, `original_status`, `new_status`, `msg`, `creator`, `creator_name`, `add_time`) VALUES
(1, 1, '1', '0', '添加[待付款]', 1, 'test', 1717947074),
(2, 2, '1', '0', '添加[待付款]', 1, 'test', 1717951364);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_payment`
--

DROP TABLE IF EXISTS `sxo_payment`;
CREATE TABLE `sxo_payment` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `name` char(30) NOT NULL COMMENT '名称',
  `payment` char(60) NOT NULL DEFAULT '' COMMENT '唯一标记',
  `logo` char(255) NOT NULL DEFAULT '' COMMENT 'logo',
  `version` char(255) NOT NULL DEFAULT '' COMMENT '插件版本',
  `apply_version` char(255) NOT NULL DEFAULT '' COMMENT '适用系统版本',
  `desc` char(255) NOT NULL DEFAULT '' COMMENT '插件描述',
  `author` char(255) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` char(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `element` text DEFAULT NULL COMMENT '配置项规则',
  `config` text DEFAULT NULL COMMENT '配置数据',
  `apply_terminal` char(255) NOT NULL COMMENT '适用终端 php一维数组json字符串存储（pc, h5, ios, android, alipay, weixin, baidu, toutiao, qq）',
  `apply_terminal_old` char(255) NOT NULL COMMENT '原始适用终端 php一维数组json字符串存储（pc, h5, ios, android, alipay, weixin, baidu, toutiao, qq）',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `is_open_user` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否对用户开放',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '顺序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='支付方式';

--
-- 转存表中的数据 `sxo_payment`
--

INSERT INTO `sxo_payment` (`id`, `name`, `payment`, `logo`, `version`, `apply_version`, `desc`, `author`, `author_url`, `element`, `config`, `apply_terminal`, `apply_terminal_old`, `is_enable`, `is_open_user`, `sort`, `add_time`, `upd_time`) VALUES
(1, '易票联-支付宝', 'EPayLinksAlipay', '', '1.0.0', '不限', '支付宝支付、适用web端，支付链接生活、让生意更简单，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href=\"https://www.epaylinks.cn/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"customer_code\",\"placeholder\":\"\\u5546\\u6237\\u53f7\",\"title\":\"\\u5546\\u6237\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5546\\u6237\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"password\",\"placeholder\":\"\\u8bc1\\u4e66\\u5bc6\\u7801\",\"title\":\"\\u8bc1\\u4e66\\u5bc6\\u7801\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u8bc1\\u4e66\\u5bc6\\u7801\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"sign_no\",\"placeholder\":\"\\u8bc1\\u4e66\\u53f7\",\"title\":\"\\u8bc1\\u4e66\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u8bc1\\u4e66\\u53f7\"},{\"element\":\"select\",\"title\":\"\\u662f\\u5426\\u6d4b\\u8bd5\\u73af\\u5883\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u662f\\u5426\\u6d4b\\u8bd5\\u73af\\u5883\",\"name\":\"is_dev_env\",\"is_multiple\":0,\"element_data\":[{\"value\":0,\"name\":\"\\u5426\"},{\"value\":1,\"name\":\"\\u662f\"}]},{\"element\":\"message\",\"message\":\"1. \\u5c06\\u79c1\\u94a5\\u6587\\u4ef6\\u8bc1\\u4e66\\u6309\\u7167[ private_key.pfx ]\\u547d\\u540d\\u653e\\u5165\\u76ee\\u5f55\\u4e2d[\\/var\\/www\\/html\\/rsakeys\\/payment_epaylinks\\/private_key.pfx]\\u3001\\u5982\\u76ee\\u5f55\\u4e0d\\u5b58\\u5728\\u81ea\\u884c\\u521b\\u5efa\\u5373\\u53ef<br \\/>2. \\u5c06\\u6613\\u7968\\u8054\\u516c\\u94a5\\u8bc1\\u4e66\\u6309\\u7167[ efps_public_key.cer ]\\u547d\\u540d\\u653e\\u5165\\u76ee\\u5f55\\u4e2d[\\/var\\/www\\/html\\/rsakeys\\/payment_epaylinks\\/efps_public_key.cer]\\u3001\\u5982\\u76ee\\u5f55\\u4e0d\\u5b58\\u5728\\u81ea\\u884c\\u521b\\u5efa\\u5373\\u53ef\"}]', '', '[\"pc\"]', '[\"pc\"]', 0, 0, 0, 1717946886, 0),
(2, '微信', 'Weixin', '', '1.1.6', '不限', '适用公众号+PC+H5+APP+微信小程序，即时到帐支付方式，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href=\"https://pay.weixin.qq.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"app_appid\",\"placeholder\":\"\\u5f00\\u653e\\u5e73\\u53f0AppID\",\"title\":\"\\u5f00\\u653e\\u5e73\\u53f0AppID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5fae\\u4fe1\\u5f00\\u653e\\u5e73\\u53f0APP\\u652f\\u4ed8\\u5206\\u914d\\u7684AppID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appid\",\"placeholder\":\"\\u516c\\u4f17\\u53f7\\/\\u670d\\u52a1\\u53f7AppID\",\"title\":\"\\u516c\\u4f17\\u53f7\\/\\u670d\\u52a1\\u53f7AppID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5fae\\u4fe1\\u5206\\u914d\\u7684AppID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mini_appid\",\"placeholder\":\"\\u5c0f\\u7a0b\\u5e8fID\",\"title\":\"\\u5c0f\\u7a0b\\u5e8fID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5fae\\u4fe1\\u5206\\u914d\\u7684\\u5c0f\\u7a0b\\u5e8fID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mch_id\",\"placeholder\":\"\\u5fae\\u4fe1\\u652f\\u4ed8\\u5546\\u6237\\u53f7\",\"title\":\"\\u5fae\\u4fe1\\u652f\\u4ed8\\u5546\\u6237\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5fae\\u4fe1\\u652f\\u4ed8\\u5206\\u914d\\u7684\\u5546\\u6237\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"key\",\"placeholder\":\"\\u5bc6\\u94a5\",\"title\":\"\\u5bc6\\u94a5\",\"desc\":\"\\u5fae\\u4fe1\\u652f\\u4ed8\\u5546\\u6237\\u5e73\\u53f0API\\u914d\\u7f6e\\u7684\\u5bc6\\u94a5\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5bc6\\u94a5\"},{\"element\":\"textarea\",\"name\":\"apiclient_cert\",\"placeholder\":\"\\u8bc1\\u4e66(apiclient_cert.pem)\",\"title\":\"\\u8bc1\\u4e66(apiclient_cert.pem)\\uff08\\u9000\\u6b3e\\u64cd\\u4f5c\\u5fc5\\u586b\\u9879\\uff09\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u8bc1\\u4e66(apiclient_cert.pem)\"},{\"element\":\"textarea\",\"name\":\"apiclient_key\",\"placeholder\":\"\\u8bc1\\u4e66\\u5bc6\\u94a5(apiclient_key.pem)\",\"title\":\"\\u8bc1\\u4e66\\u5bc6\\u94a5(apiclient_key.pem)\\uff08\\u9000\\u6b3e\\u64cd\\u4f5c\\u5fc5\\u586b\\u9879\\uff09\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u8bc1\\u4e66\\u5bc6\\u94a5(apiclient_key.pem)\"},{\"element\":\"select\",\"title\":\"\\u5f02\\u6b65\\u901a\\u77e5\\u534f\\u8bae\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u534f\\u8bae\\u7c7b\\u578b\",\"name\":\"agreement\",\"is_multiple\":0,\"element_data\":[{\"value\":1,\"name\":\"\\u9ed8\\u8ba4\\u5f53\\u524d\\u534f\\u8bae\"},{\"value\":2,\"name\":\"\\u5f3a\\u5236https\\u8f6chttp\\u534f\\u8bae\"}]},{\"element\":\"select\",\"title\":\"h5\\u8df3\\u8f6c\\u5730\\u5740urlencode\",\"message\":\"\\u8bf7\\u9009\\u62e9h5\\u8df3\\u8f6c\\u5730\\u5740urlencode\",\"name\":\"is_h5_url_encode\",\"is_multiple\":0,\"element_data\":[{\"value\":1,\"name\":\"\\u662f\"},{\"value\":2,\"name\":\"\\u5426\"}]},{\"element\":\"select\",\"title\":\"H5\\u8d70NATIVE\\u6a21\\u5f0f\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u662f\\u5426H5\\u8d70NATIVE\\u6a21\\u5f0f\",\"desc\":\"\\u8d26\\u6237\\u6ca1\\u6709\\u53d6\\u5f97h5\\u652f\\u4ed8\\u6743\\u9650\\u7684\\u60c5\\u51b5\\u4e0b\\u53ef\\u4ee5\\u5f00\\u542f\",\"name\":\"is_h5_pay_native_mode\",\"is_multiple\":0,\"element_data\":[{\"value\":0,\"name\":\"\\u5426\"},{\"value\":1,\"name\":\"\\u662f\"}]}]', '{\"app_appid\":\"\",\"appid\":\"\",\"mini_appid\":\"\",\"mch_id\":\"\",\"key\":\"\",\"apiclient_cert\":\"\",\"apiclient_key\":\"\",\"agreement\":\"1\",\"is_h5_url_encode\":\"1\",\"is_h5_pay_native_mode\":\"0\"}', '[\"pc\",\"h5\",\"ios\",\"android\",\"weixin\",\"qq\"]', '[\"pc\",\"h5\",\"ios\",\"android\",\"weixin\",\"qq\"]', 1, 1, 0, 1717946893, 1717946983),
(3, '现金支付', 'CashPayment', '', '2.0.1', '不限', '现金方式支付货款、支持配置自定义支付信息', 'Devil', 'http://shopxo.net/', '[{\"element\":\"select\",\"title\":\"\\u81ea\\u5b9a\\u4e49\\u652f\\u4ed8\\u4fe1\\u606f\\u5c55\\u793a\",\"desc\":\"\\u4ec5web\\u7aef\\u6709\\u6548\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u662f\\u5426\\u5f00\\u542f\\u81ea\\u5b9a\\u4e49\\u652f\\u4ed8\",\"name\":\"is_custom_pay\",\"is_multiple\":0,\"element_data\":[{\"value\":0,\"name\":\"\\u5173\\u95ed\"},{\"value\":1,\"name\":\"\\u5f00\\u542f\"}]},{\"element\":\"textarea\",\"name\":\"content\",\"placeholder\":\"\\u81ea\\u5b9a\\u4e49\\u6587\\u672c\",\"title\":\"\\u81ea\\u5b9a\\u4e49\\u6587\\u672c\",\"desc\":\"\\u53ef\\u6362\\u884c\\u3001\\u4e00\\u884c\\u4e00\\u6761\\u6570\\u636e\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u81ea\\u5b9a\\u4e49\\u6587\\u672c\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"tips\",\"placeholder\":\"\\u7279\\u522b\\u63d0\\u793a\\u4fe1\\u606f\",\"title\":\"\\u7279\\u522b\\u63d0\\u793a\\u4fe1\\u606f\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u7279\\u522b\\u63d0\\u793a\\u4fe1\\u606f\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"images_url\",\"placeholder\":\"\\u56fe\\u7247\\u5730\\u5740\",\"title\":\"\\u56fe\\u7247\\u5730\\u5740\",\"desc\":\"\\u53ef\\u81ea\\u5b9a\\u4e49\\u56fe\\u7247\\u5c55\\u793a\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u56fe\\u7247\\u81ea\\u5b9a\\u4e49\\u7684\\u5730\\u5740\"}]', '', '[\"pc\",\"h5\",\"ios\",\"android\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', '[\"pc\",\"h5\",\"ios\",\"android\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 0, 0, 0, 1717947405, 0),
(4, 'Csgjs', 'Csgjs', '', '1.0.0', '不限', '熵基内部管理消费系统 <a href=\"https://www.zkteco.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"sn\",\"placeholder\":\"\\u6d88\\u8d39\\u8bbe\\u5907SN\",\"title\":\"\\u6d88\\u8d39\\u8bbe\\u5907SN\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u6d88\\u8d39\\u8bbe\\u5907SN\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"access_key\",\"placeholder\":\"AccessKey\",\"title\":\"AccessKey\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199AccessKey\\u79d8\\u94a5\"},{\"element\":\"message\",\"message\":\"\\u8be5\\u652f\\u4ed8\\u63d2\\u4ef6\\u65e0\\u9000\\u6b3e\\u64cd\\u4f5c\\uff0c\\u63a5\\u53e3\\u5747\\u4e3a\\u5185\\u90e8\\u73af\\u5883\\u4f7f\\u7528\\u3001\\u8bf7\\u52ff\\u5916\\u7f51\\u4f7f\\u7528\\uff01\"}]', '', '[\"pc\"]', '[\"pc\"]', 0, 0, 0, 1717947409, 0),
(5, '头条', 'Toutiao', '', '1.0.0', '不限', '适用头条小程序，担保交易支付方式。 <a href=\"https://microapp.bytedance.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"app_id\",\"placeholder\":\"\\u5c0f\\u7a0b\\u5e8fAppID\",\"title\":\"\\u5c0f\\u7a0b\\u5e8fAppID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5c0f\\u7a0b\\u5e8fAppID\"},{\"element\":\"select\",\"title\":\"\\u652f\\u4ed8\\u7c7b\\u578b\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u652f\\u4ed8\\u7c7b\\u578b\",\"name\":\"pay_type\",\"is_multiple\":0,\"element_data\":[{\"value\":0,\"name\":\"\\u62c5\\u4fdd\\u4ea4\\u6613(\\u666e\\u901a)\"},{\"value\":1,\"name\":\"\\u62c5\\u4fdd\\u4ea4\\u6613(\\u4f01\\u4e1a-\\u901a\\u7528\\u4ea4\\u6613\\u7cfb\\u7edf)\"}]},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"salt\",\"placeholder\":\"\\u5bc6\\u94a5SALT\",\"title\":\"\\u5bc6\\u94a5SALT\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5bc6\\u94a5SALT\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"token\",\"desc\":\"\\u81ea\\u884c\\u8f93\\u5165\\u82f1\\u6587\\u6216\\u6570\\u5b57\\u7ec4\\u5408\\u3001\\u4e0d\\u8d85\\u8fc732\\u4e2a\\u5b57\\u7b26\",\"placeholder\":\"Token(\\u4ee4\\u724c)\\uff0c\\u81ea\\u884c\\u8f93\\u5165\\u82f1\\u6587\\u6216\\u6570\\u5b57\\u7ec4\\u5408\\u3001\\u4e0d\\u8d85\\u8fc732\\u4e2a\\u5b57\\u7b26\",\"title\":\"Token(\\u4ee4\\u724c)\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199Token(\\u4ee4\\u724c)\"},{\"element\":\"textarea\",\"name\":\"rsa_public\",\"placeholder\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"title\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u516c\\u94a5\"},{\"element\":\"textarea\",\"name\":\"rsa_private\",\"placeholder\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"title\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u79c1\\u94a5\"},{\"element\":\"textarea\",\"name\":\"out_rsa_public\",\"placeholder\":\"\\u652f\\u4ed8\\u5e73\\u53f0\\u516c\\u94a5\",\"title\":\"\\u652f\\u4ed8\\u5e73\\u53f0\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u652f\\u4ed8\\u5e73\\u53f0\\u516c\\u94a5\"},{\"element\":\"select\",\"title\":\"\\u4ea4\\u6613\\u89c4\\u5219\\u6807\\u7b7e\\u7ec4\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u4ea4\\u6613\\u89c4\\u5219\\u6807\\u7b7e\\u7ec4\",\"name\":\"tag_group_id\",\"is_multiple\":0,\"element_data\":[{\"value\":\"tag_group_7272625659887943692\",\"name\":\"\\u901a\\u4fe1-\\u53f7\\u5361\\u5546\\u54c1(\\u672a\\u5236\\u5361\\u5168\\u989d\\u9000\\/\\u5236\\u5361\\u540e\\u534f\\u5546\\u9000)\"},{\"value\":\"tag_group_7272625659887960076\",\"name\":\"\\u901a\\u4fe1-\\u5b9a\\u5236\\u670d\\u52a1(\\u672a\\u5236\\u4f5c\\u5168\\u989d\\u9000\\/\\u5b9a\\u5236\\u540e\\u534f\\u5546\\u9000)\"},{\"value\":\"tag_group_7272625659887976460\",\"name\":\"\\u901a\\u4fe1-\\u5b9a\\u5236\\u670d\\u52a1(\\u672a\\u5236\\u4f5c\\u5168\\u989d\\u9000\\/\\u5b9a\\u5236\\u540e\\u4e0d\\u53ef\\u9000)\"},{\"value\":\"tag_group_7272625659887992844\",\"name\":\"\\u901a\\u4fe1-\\u865a\\u62df\\u5145\\u503c(\\u4e0d\\u53ef\\u9000)\"},{\"value\":\"tag_group_7272625659888009228\",\"name\":\"\\u54a8\\u8be2-\\u666e\\u901a\\u54a8\\u8be2(\\u672a\\u670d\\u52a1\\u5168\\u989d\\u9000\\/\\u5f00\\u59cb\\u670d\\u52a1\\u540e\\u534f\\u5546\\u9000)\"},{\"value\":\"tag_group_7272625659888025612\",\"name\":\"\\u54a8\\u8be2-\\u666e\\u901a\\u54a8\\u8be2(\\u672a\\u670d\\u52a1\\u5168\\u989d\\u9000\\/\\u5f00\\u59cb\\u670d\\u52a1\\u540e\\u4e0d\\u53ef\\u9000)\"},{\"value\":\"tag_group_7297888175123382299\",\"name\":\"\\u54a8\\u8be2-\\u4ee3\\u5199\\u6587\\u4e66(\\u672a\\u670d\\u52a1\\u5168\\u989d\\u9000\\/\\u5f00\\u59cb\\u670d\\u52a1\\u540e\\u534f\\u5546\\u9000)\"},{\"value\":\"tag_group_7272625659888041996\",\"name\":\"\\u54a8\\u8be2-\\u5185\\u5bb9\\u6d88\\u8d39(\\u4e0d\\u53ef\\u9000)\"},{\"value\":\"tag_group_7272625659888058380\",\"name\":\"\\u5de5\\u5177-\\u865a\\u62df\\u670d\\u52a1(\\u4e0d\\u53ef\\u9000)\"}]},{\"element\":\"message\",\"message\":\"\\u5f02\\u6b65\\u901a\\u77e5\\u5730\\u5740\\uff0c\\u5c06\\u8be5\\u5730\\u5740\\u914d\\u7f6e\\u5230\\u5934\\u6761\\u5c0f\\u7a0b\\u5e8f\\u540e\\u53f0->\\u652f\\u4ed8->\\u62c5\\u4fdd\\u4ea4\\u6613->\\u62c5\\u4fdd\\u4ea4\\u6613\\u8bbe\\u7f6e\\u4e2d<br \\/>http:\\/\\/127.0.0.1:8000\\/payment_default_order_toutiao_notify.php<br \\/><br \\/>PS\\uff1a\\u652f\\u4ed8\\u7c7b\\u578b \\u62c5\\u4fdd\\u4ea4\\u6613(\\u4f01\\u4e1a-\\u901a\\u7528\\u4ea4\\u6613\\u7cfb\\u7edf)\\u91c7\\u7528\\uff08\\u5e94\\u7528\\u516c\\u94a5\\u3001\\u79c1\\u94a5\\u3001\\u5e73\\u53f0\\u516c\\u94a5\\u3001\\u4ea4\\u6613\\u89c4\\u5219\\u6807\\u7b7e\\u7ec4\\uff09\\u914d\\u7f6e\\u9879\"}]', '', '[\"toutiao\"]', '[\"toutiao\"]', 0, 0, 0, 1717947413, 0),
(6, '支付宝扫码支付', 'AlipayScanQrcode', '', '1.0.0', '不限', '支付宝扫码支付、适用web端，一般用于线下扫码枪或手机对准用户付款码扫码完成支付，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金，（不要对用户开放、该插件不适用于在线支付）。 <a href=\"http://www.alipay.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appid\",\"placeholder\":\"appid\",\"title\":\"appid\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528appid\"},{\"element\":\"textarea\",\"name\":\"rsa_public\",\"placeholder\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"title\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u516c\\u94a5\"},{\"element\":\"textarea\",\"name\":\"rsa_private\",\"placeholder\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"title\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u79c1\\u94a5\"},{\"element\":\"textarea\",\"name\":\"out_rsa_public\",\"placeholder\":\"\\u652f\\u4ed8\\u5b9d\\u516c\\u94a5\",\"title\":\"\\u652f\\u4ed8\\u5b9d\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u652f\\u4ed8\\u5b9d\\u516c\\u94a5\"}]', '', '[\"pc\"]', '[\"pc\"]', 0, 0, 0, 1717947417, 0),
(7, 'UniPayment', 'UniPayment', '', '1.0.0', '不限', '适配PC+H5、使用 UniPayment 接受和管理加密货币付款<a href=\"https://www.unipayment.io/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"app_id\",\"placeholder\":\"AppId\",\"title\":\"AppId\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199AppId\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"client_id\",\"placeholder\":\"ClientID\",\"title\":\"ClientID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199ClientID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"client_secret\",\"placeholder\":\"ClientSecret\",\"title\":\"ClientSecret\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199ClientSecret\"},{\"element\":\"select\",\"title\":\"\\u4ef7\\u683c\\u8d27\\u5e01\\uff08\\u9ed8\\u8ba4 USD\\uff09\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u4ef7\\u683c\\u8d27\\u5e01\",\"name\":\"price_currency\",\"is_multiple\":0,\"element_data\":[{\"value\":\"BTC\",\"name\":\"Bitcoin\"},{\"value\":\"SGD\",\"name\":\"Singapore Dollar\"},{\"value\":\"ETH\",\"name\":\"Ethereum\"},{\"value\":\"USDT\",\"name\":\"USDT\"},{\"value\":\"USDC\",\"name\":\"USDC\"},{\"value\":\"CNY\",\"name\":\"Yuan Renminbi\"},{\"value\":\"USD\",\"name\":\"US Dollar\"},{\"value\":\"HKD\",\"name\":\"Hong Kong Dollar\"}]},{\"element\":\"select\",\"title\":\"\\u652f\\u4ed8\\u8d27\\u5e01\\uff08\\u9ed8\\u8ba4 USDT\\uff09\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u652f\\u4ed8\\u8d27\\u5e01\",\"name\":\"pay_currency\",\"is_multiple\":0,\"element_data\":[{\"value\":\"BTC\",\"name\":\"Bitcoin\"},{\"value\":\"SGD\",\"name\":\"Singapore Dollar\"},{\"value\":\"ETH\",\"name\":\"Ethereum\"},{\"value\":\"USDT\",\"name\":\"USDT\"},{\"value\":\"USDC\",\"name\":\"USDC\"},{\"value\":\"CNY\",\"name\":\"Yuan Renminbi\"},{\"value\":\"USD\",\"name\":\"US Dollar\"},{\"value\":\"HKD\",\"name\":\"Hong Kong Dollar\"}]}]', '', '[\"pc\",\"h5\"]', '[\"pc\",\"h5\"]', 0, 0, 0, 1717947421, 0),
(8, '易票联-微信', 'EPayLinksWeixin', '', '1.0.0', '不限', '支付宝支付、适用web端，支付链接生活、让生意更简单，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href=\"https://www.epaylinks.cn/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"customer_code\",\"placeholder\":\"\\u5546\\u6237\\u53f7\",\"title\":\"\\u5546\\u6237\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5546\\u6237\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"password\",\"placeholder\":\"\\u8bc1\\u4e66\\u5bc6\\u7801\",\"title\":\"\\u8bc1\\u4e66\\u5bc6\\u7801\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u8bc1\\u4e66\\u5bc6\\u7801\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"sign_no\",\"placeholder\":\"\\u8bc1\\u4e66\\u53f7\",\"title\":\"\\u8bc1\\u4e66\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u8bc1\\u4e66\\u53f7\"},{\"element\":\"select\",\"title\":\"\\u662f\\u5426\\u6d4b\\u8bd5\\u73af\\u5883\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u662f\\u5426\\u6d4b\\u8bd5\\u73af\\u5883\",\"name\":\"is_dev_env\",\"is_multiple\":0,\"element_data\":[{\"value\":0,\"name\":\"\\u5426\"},{\"value\":1,\"name\":\"\\u662f\"}]},{\"element\":\"message\",\"message\":\"1. \\u5c06\\u79c1\\u94a5\\u6587\\u4ef6\\u8bc1\\u4e66\\u6309\\u7167[ private_key.pfx ]\\u547d\\u540d\\u653e\\u5165\\u76ee\\u5f55\\u4e2d[\\/var\\/www\\/html\\/rsakeys\\/payment_epaylinks\\/private_key.pfx]\\u3001\\u5982\\u76ee\\u5f55\\u4e0d\\u5b58\\u5728\\u81ea\\u884c\\u521b\\u5efa\\u5373\\u53ef<br \\/>2. \\u5c06\\u6613\\u7968\\u8054\\u516c\\u94a5\\u8bc1\\u4e66\\u6309\\u7167[ efps_public_key.cer ]\\u547d\\u540d\\u653e\\u5165\\u76ee\\u5f55\\u4e2d[\\/var\\/www\\/html\\/rsakeys\\/payment_epaylinks\\/efps_public_key.cer]\\u3001\\u5982\\u76ee\\u5f55\\u4e0d\\u5b58\\u5728\\u81ea\\u884c\\u521b\\u5efa\\u5373\\u53ef\"}]', '', '[\"pc\"]', '[\"pc\"]', 0, 0, 0, 1717947426, 0),
(9, '支付宝证书支付', 'AlipayCert', '', '1.0.2', '不限', '2.0证书通信版本，适用PC+H5+APP，即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href=\"http://www.alipay.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appid\",\"placeholder\":\"\\u5e94\\u7528ID\",\"title\":\"\\u5e94\\u7528ID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528ID\"},{\"element\":\"textarea\",\"name\":\"rsa_public\",\"placeholder\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"title\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u516c\\u94a5\"},{\"element\":\"textarea\",\"name\":\"rsa_private\",\"placeholder\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"title\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u79c1\\u94a5\"},{\"element\":\"textarea\",\"name\":\"cert_content\",\"placeholder\":\"\\u5e94\\u7528\\u8bc1\\u4e66\",\"title\":\"\\u5e94\\u7528\\u8bc1\\u4e66\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u8bc1\\u4e66\"},{\"element\":\"textarea\",\"name\":\"out_cert_content\",\"placeholder\":\"\\u652f\\u4ed8\\u5b9d\\u8bc1\\u4e66\",\"title\":\"\\u652f\\u4ed8\\u5b9d\\u8bc1\\u4e66\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u652f\\u4ed8\\u5b9d\\u8bc1\\u4e66\"},{\"element\":\"textarea\",\"name\":\"out_root_cert_content\",\"placeholder\":\"\\u652f\\u4ed8\\u5b9dROOT\\u8bc1\\u4e66\",\"title\":\"\\u652f\\u4ed8\\u5b9dROOT\\u8bc1\\u4e66\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u652f\\u4ed8\\u5b9dROOT\\u8bc1\\u4e66\"}]', '', '[\"pc\",\"h5\",\"ios\",\"android\"]', '[\"pc\",\"h5\",\"ios\",\"android\"]', 0, 0, 0, 1717947430, 0),
(10, '货到付款', 'DeliveryPayment', '', '1.0.0', '不限', '送货上门后收取货款', 'Devil', 'http://shopxo.net/', '', '', '[\"pc\",\"h5\",\"ios\",\"android\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', '[\"pc\",\"h5\",\"ios\",\"android\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 0, 0, 0, 1717947434, 0),
(11, '建行聚合支付', 'CcbPay', '', '1.0.0', '不限', '适用PC(支付宝/微信/建行APP扫码支付)+微信小程序支付，即时到帐支付方式，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href=\"https://ibsbjstar.ccb.com.cn/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appid\",\"placeholder\":\"\\u5c0f\\u7a0b\\u5e8fAPPID\",\"title\":\"\\u5c0f\\u7a0b\\u5e8fAPPID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5c0f\\u7a0b\\u5e8fAPPID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"merchant_id\",\"placeholder\":\"\\u5546\\u6237\\u53f7\",\"title\":\"\\u5546\\u6237\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5546\\u6237\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"pos_id\",\"placeholder\":\"\\u67dc\\u53f0\\u53f7\",\"title\":\"\\u67dc\\u53f0\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u67dc\\u53f0\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"branch_id\",\"placeholder\":\"\\u5206\\u884c\\u4ee3\\u7801\",\"title\":\"\\u5206\\u884c\\u4ee3\\u7801\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5206\\u884c\\u4ee3\\u7801\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"pub\",\"placeholder\":\"\\u5546\\u6237\\u516c\\u94a5\\u540e30\\u4f4d\",\"title\":\"\\u5546\\u6237\\u516c\\u94a5\\u540e30\\u4f4d\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5546\\u6237\\u516c\\u94a5\\u540e30\\u4f4d\"},{\"element\":\"textarea\",\"name\":\"ccb_public\",\"placeholder\":\"\\u5546\\u6237\\u516c\\u94a5\",\"title\":\"\\u5546\\u6237\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5546\\u6237\\u516c\\u94a5\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"wlpt_url\",\"placeholder\":\"\\u5916\\u8054\\u5e73\\u53f0\\u5730\\u5740\",\"title\":\"\\u5916\\u8054\\u5e73\\u53f0\\u5730\\u5740\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5916\\u8054\\u5e73\\u53f0\\u5730\\u5740\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"user_id\",\"placeholder\":\"\\u64cd\\u4f5c\\u5458\\u53f7\",\"title\":\"\\u64cd\\u4f5c\\u5458\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5546\\u6237\\u670d\\u52a1\\u5e73\\u53f0\\u64cd\\u4f5c\\u5458\\u53f7\"},{\"element\":\"input\",\"type\":\"password\",\"default\":\"\",\"name\":\"user_pwd\",\"placeholder\":\"\\u64cd\\u4f5c\\u5458\\u4ea4\\u6613\\u5bc6\\u7801\",\"title\":\"\\u64cd\\u4f5c\\u5458\\u4ea4\\u6613\\u5bc6\\u7801\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5546\\u6237\\u670d\\u52a1\\u5e73\\u53f0\\u64cd\\u4f5c\\u5458\\u4ea4\\u6613\\u5bc6\\u7801\"},{\"element\":\"message\",\"message\":\"1. \\u540c\\u6b65\\u8df3\\u8f6c\\u5730\\u5740\\uff0c\\u5c06\\u8be5\\u5730\\u5740\\u914d\\u7f6e\\u5230\\u652f\\u4ed8\\u540e\\u53f0\\u9875\\u9762\\u540c\\u6b65\\u8df3\\u8f6c<br \\/>http:\\/\\/127.0.0.1:8000\\/payment_default_order_ccbpay_respond.php<br \\/><br \\/>2. \\u5f02\\u6b65\\u901a\\u77e5\\u5730\\u5740\\uff0c\\u5c06\\u8be5\\u5730\\u5740\\u914d\\u7f6e\\u5230\\u652f\\u4ed8\\u540e\\u53f0\\u5f02\\u6b65\\u901a\\u77e5<br \\/>http:\\/\\/127.0.0.1:8000\\/payment_default_order_ccbpay_notify.php\"}]', '', '[\"pc\",\"weixin\"]', '[\"pc\",\"weixin\"]', 0, 0, 0, 1717947439, 0),
(12, '支付宝当面付', 'AlipayFace', '', '1.0.2', '不限', '支付宝当面付、适用web端，用户主动扫码支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href=\"http://www.alipay.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appid\",\"placeholder\":\"appid\",\"title\":\"appid\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528appid\"},{\"element\":\"textarea\",\"name\":\"rsa_public\",\"placeholder\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"title\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u516c\\u94a5\"},{\"element\":\"textarea\",\"name\":\"rsa_private\",\"placeholder\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"title\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u79c1\\u94a5\"},{\"element\":\"textarea\",\"name\":\"out_rsa_public\",\"placeholder\":\"\\u652f\\u4ed8\\u5b9d\\u516c\\u94a5\",\"title\":\"\\u652f\\u4ed8\\u5b9d\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u652f\\u4ed8\\u5b9d\\u516c\\u94a5\"}]', '', '[\"pc\",\"h5\"]', '[\"pc\",\"h5\"]', 0, 0, 0, 1717947443, 0),
(13, 'XPay支付', 'XPay', '', '1.0.0', '不限', 'XPay支付、适用web端，支付链接生活、让生意更简单，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href=\"https://api.qfllqhi.cn/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mch_no\",\"placeholder\":\"\\u5546\\u6237\\u53f7\",\"title\":\"\\u5546\\u6237\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5546\\u6237\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"product_name\",\"placeholder\":\"\\u4ea7\\u54c1\\u540d\\u79f0\",\"title\":\"\\u4ea7\\u54c1\\u540d\\u79f0\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u4ea7\\u54c1\\u540d\\u79f0\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"key\",\"placeholder\":\"\\u5bc6\\u94a5\",\"title\":\"\\u5bc6\\u94a5\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5bc6\\u94a5\"}]', '', '[\"pc\"]', '[\"pc\"]', 0, 0, 0, 1717947447, 0),
(14, 'TrySeeMerPay', 'TrySeeMerPay', '', '1.0.0', '不限', 'TrySeeMerPay，即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href=\"https://www.try-see.net/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mch_id\",\"placeholder\":\"\\u4f1a\\u5458\\u5e97ID\",\"title\":\"\\u4f1a\\u5458\\u5e97ID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u4f1a\\u5458\\u5e97ID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mch_key\",\"placeholder\":\"\\u4f1a\\u5458\\u5e97\\u79d8\\u94a5\",\"title\":\"\\u4f1a\\u5458\\u5e97\\u79d8\\u94a5\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u4f1a\\u5458\\u5e97\\u79d8\\u94a5\"},{\"element\":\"textarea\",\"name\":\"rsa_private\",\"placeholder\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"title\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u79c1\\u94a5\"},{\"element\":\"textarea\",\"name\":\"rsa_public\",\"placeholder\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"title\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u516c\\u94a5\"},{\"element\":\"message\",\"message\":\"\\u8be5\\u652f\\u4ed8\\u63d2\\u4ef6\\u3010\\u652f\\u4ed8\\u91d1\\u989d\\u548c\\u9000\\u6b3e\\u91d1\\u989d\\u3011\\u5fc5\\u987b\\u4e3a\\u6574\\u6570\\uff0c\\u7cfb\\u7edf\\u5c06\\u81ea\\u52a8\\u8f6c\\u4e3a\\u6574\\u6570\\u53bb\\u9664\\u5c0f\\u6570\\u70b9\"}]', '', '[\"pc\"]', '[\"pc\"]', 0, 0, 0, 1717947451, 0),
(15, '微信扫码支付', 'WeixinScanQrcode', '', '1.0.0', '不限', '微信扫码支付、适用web端，一般用于线下扫码枪或手机对准用户付款码扫码完成支付，买家的交易资金直接打入卖家微信账户，快速回笼交易资金，（不要对用户开放、该插件不适用于在线支付）。 <a href=\"https://pay.weixin.qq.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appid\",\"placeholder\":\"\\u516c\\u4f17\\u53f7\\/\\u670d\\u52a1\\u53f7AppID\",\"title\":\"\\u516c\\u4f17\\u53f7\\/\\u670d\\u52a1\\u53f7AppID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5fae\\u4fe1\\u5206\\u914d\\u7684AppID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mch_id\",\"placeholder\":\"\\u5fae\\u4fe1\\u652f\\u4ed8\\u5546\\u6237\\u53f7\",\"title\":\"\\u5fae\\u4fe1\\u652f\\u4ed8\\u5546\\u6237\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5fae\\u4fe1\\u652f\\u4ed8\\u5206\\u914d\\u7684\\u5546\\u6237\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"key\",\"placeholder\":\"\\u5bc6\\u94a5\",\"title\":\"\\u5bc6\\u94a5\",\"desc\":\"\\u5fae\\u4fe1\\u652f\\u4ed8\\u5546\\u6237\\u5e73\\u53f0API\\u914d\\u7f6e\\u7684\\u5bc6\\u94a5\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5bc6\\u94a5\"},{\"element\":\"textarea\",\"name\":\"apiclient_cert\",\"placeholder\":\"\\u8bc1\\u4e66(apiclient_cert.pem)\",\"title\":\"\\u8bc1\\u4e66(apiclient_cert.pem)\\uff08\\u9000\\u6b3e\\u64cd\\u4f5c\\u5fc5\\u586b\\u9879\\uff09\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u8bc1\\u4e66(apiclient_cert.pem)\"},{\"element\":\"textarea\",\"name\":\"apiclient_key\",\"placeholder\":\"\\u8bc1\\u4e66\\u5bc6\\u94a5(apiclient_key.pem)\",\"title\":\"\\u8bc1\\u4e66\\u5bc6\\u94a5(apiclient_key.pem)\\uff08\\u9000\\u6b3e\\u64cd\\u4f5c\\u5fc5\\u586b\\u9879\\uff09\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u8bc1\\u4e66\\u5bc6\\u94a5(apiclient_key.pem)\"}]', '', '[\"pc\"]', '[\"pc\"]', 0, 0, 0, 1717947455, 0),
(16, 'OceanPayment', 'OceanPayment', '', '1.0.0', '不限', 'OceanPayment全球数字支付方案提供商，适用PC+H5，信用卡支付。 <a href=\"https://www.oceanpayment.com/?form=shopxo\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"account\",\"placeholder\":\"account\\u8d26\\u6237\\u53f7\",\"title\":\"account\\u8d26\\u6237\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199account\\u8d26\\u6237\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"terminal\",\"placeholder\":\"terminal\\u7ec8\\u7aef\\u53f7\",\"title\":\"terminal\\u7ec8\\u7aef\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199terminal\\u7ec8\\u7aef\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"secure_code\",\"placeholder\":\"secureCode\",\"title\":\"secureCode\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199secureCode\"},{\"element\":\"textarea\",\"name\":\"key\",\"placeholder\":\"Oceanpayment\\u516c\\u94a5\",\"title\":\"Oceanpayment\\u516c\\u94a5\",\"is_required\":0,\"rows\":4,\"message\":\"\\u8bf7\\u586b\\u5199Oceanpayment\\u516c\\u94a5\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"en_US\",\"name\":\"lang\",\"placeholder\":\"\\u8bed\\u8a00\",\"title\":\"\\u8bed\\u8a00\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u8bed\\u8a00\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"USD\",\"name\":\"order_currency\",\"placeholder\":\"\\u4ea4\\u6613\\u5e01\\u79cd\",\"title\":\"\\u4ea4\\u6613\\u5e01\\u79cd\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u4ea4\\u6613\\u5e01\\u79cd\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"US\",\"name\":\"billing_country\",\"placeholder\":\"\\u6d88\\u8d39\\u8005\\u7684\\u8d26\\u5355\\u56fd\\u5bb6\",\"title\":\"\\u6d88\\u8d39\\u8005\\u7684\\u8d26\\u5355\\u56fd\\u5bb6\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u6d88\\u8d39\\u8005\\u7684\\u8d26\\u5355\\u56fd\\u5bb6\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"N\\/A\",\"name\":\"billing_state\",\"placeholder\":\"\\u6d88\\u8d39\\u8005\\u7684\\u5dde\\uff08\\u7701\\u3001\\u90e1\\uff09\",\"title\":\"\\u6d88\\u8d39\\u8005\\u7684\\u5dde\\uff08\\u7701\\u3001\\u90e1\\uff09\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u6d88\\u8d39\\u8005\\u7684\\u5dde\\uff08\\u7701\\u3001\\u90e1\\uff09\"},{\"element\":\"select\",\"title\":\"\\u662f\\u5426\\u6d4b\\u8bd5\\u73af\\u5883\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u662f\\u5426\\u6d4b\\u8bd5\\u73af\\u5883\",\"name\":\"is_dev_env\",\"is_multiple\":0,\"element_data\":[{\"value\":0,\"name\":\"\\u5426\"},{\"value\":1,\"name\":\"\\u662f\"}]},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"show_images\",\"placeholder\":\"\\u81ea\\u5b9a\\u4e49\\u5c55\\u793a\\u56fe\\u7247\\u5730\\u5740\",\"title\":\"\\u81ea\\u5b9a\\u4e49\\u5c55\\u793a\\u56fe\\u7247\\u5730\\u5740\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u81ea\\u5b9a\\u4e49\\u5c55\\u793a\\u56fe\\u7247\\u5730\\u5740\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\\u8fdb\\u5165\\u6211\\u7684\\u8ba2\\u5355\",\"name\":\"button_order_name\",\"placeholder\":\"\\u8fdb\\u5165\\u6211\\u7684\\u8ba2\\u5355\\u6309\\u94ae\\u540d\\u79f0\",\"title\":\"\\u8fdb\\u5165\\u6211\\u7684\\u8ba2\\u5355\\u6309\\u94ae\\u540d\\u79f0\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u8fdb\\u5165\\u6211\\u7684\\u8ba2\\u5355\\u6309\\u94ae\\u540d\\u79f0\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\\u7acb\\u5373\\u652f\\u4ed8\",\"name\":\"button_pay_name\",\"placeholder\":\"\\u652f\\u4ed8\\u6309\\u94ae\\u540d\\u79f0\",\"title\":\"\\u652f\\u4ed8\\u6309\\u94ae\\u540d\\u79f0\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u652f\\u4ed8\\u6309\\u94ae\\u540d\\u79f0\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\\u652f\\u4ed8\\u91d1\\u989d\\uff1a\",\"name\":\"pay_first_name\",\"placeholder\":\"\\u4ef7\\u683c\\u63d0\\u793a\\u540d\\u79f0\",\"title\":\"\\u4ef7\\u683c\\u63d0\\u793a\\u540d\\u79f0\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u4ef7\\u683c\\u63d0\\u793a\\u540d\\u79f0\"}]', '', '[\"pc\",\"h5\"]', '[\"pc\",\"h5\"]', 0, 0, 0, 1717947460, 0),
(17, '支付宝小程序', 'AlipayMini', '', '1.1.3', '不限', '适用支付宝小程序、JSAPI，即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href=\"http://www.alipay.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appid\",\"placeholder\":\"appid\",\"title\":\"appid\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5c0f\\u7a0b\\u5e8fappid\"},{\"element\":\"textarea\",\"name\":\"rsa_public\",\"placeholder\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"title\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u516c\\u94a5\"},{\"element\":\"textarea\",\"name\":\"rsa_private\",\"placeholder\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"title\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u79c1\\u94a5\"},{\"element\":\"textarea\",\"name\":\"out_rsa_public\",\"placeholder\":\"\\u652f\\u4ed8\\u5b9d\\u516c\\u94a5\",\"title\":\"\\u652f\\u4ed8\\u5b9d\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u652f\\u4ed8\\u5b9d\\u516c\\u94a5\"},{\"element\":\"select\",\"title\":\"\\u662f\\u5426JSAPI\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u662f\\u5426JSAPI\",\"name\":\"is_jsapi\",\"is_multiple\":0,\"element_data\":[{\"value\":0,\"name\":\"\\u5426\"},{\"value\":1,\"name\":\"\\u662f\"}]}]', '', '[\"alipay\"]', '[\"alipay\"]', 0, 0, 0, 1717947465, 0),
(18, '银联商务-微信', 'ChinaUmsWeixin', '', '1.0.2', '不限', '适用微信小程序，即时到帐支付方式，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href=\"https://www.chinaums.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"msg_id\",\"placeholder\":\"\\u6765\\u6e90\\u7f16\\u53f7\",\"title\":\"\\u6765\\u6e90\\u7f16\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u6765\\u6e90\\u7f16\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"msg_src\",\"placeholder\":\"\\u6d88\\u606f\\u6765\\u6e90\",\"title\":\"\\u6d88\\u606f\\u6765\\u6e90\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u6d88\\u606f\\u6765\\u6e90\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"key\",\"placeholder\":\"\\u901a\\u8baf\\u5bc6\\u94a5\",\"title\":\"\\u901a\\u8baf\\u5bc6\\u94a5\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u901a\\u8baf\\u5bc6\\u94a5\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mid\",\"placeholder\":\"\\u5546\\u6237\\u53f7\",\"title\":\"\\u5546\\u6237\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5546\\u6237\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"tid\",\"placeholder\":\"\\u7ec8\\u7aef\\u53f7\",\"title\":\"\\u7ec8\\u7aef\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u7ec8\\u7aef\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appid\",\"placeholder\":\"\\u5c0f\\u7a0b\\u5e8fappid\",\"title\":\"\\u5c0f\\u7a0b\\u5e8fappid\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5c0f\\u7a0b\\u5e8fappid\"}]', '', '[\"weixin\"]', '[\"weixin\"]', 0, 0, 0, 1717947469, 0),
(19, 'QQ支付', 'QQ', '', '1.0.1', '不限', '适用PC+H5+APP+QQ小程序，即时到帐支付方式，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href=\"https://qpay.qq.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"app_appid\",\"placeholder\":\"\\u5f00\\u653e\\u5e73\\u53f0AppID\",\"title\":\"\\u5f00\\u653e\\u5e73\\u53f0AppID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199QQ\\u5f00\\u653e\\u5e73\\u53f0APP\\u652f\\u4ed8\\u5206\\u914d\\u7684AppID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appid\",\"placeholder\":\"QQ\\u5c0f\\u7a0b\\u5e8fID\",\"title\":\"QQ\\u5c0f\\u7a0b\\u5e8fID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199QQ\\u5206\\u914d\\u7684\\u5c0f\\u7a0b\\u5e8fID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mch_id\",\"placeholder\":\"QQ\\u652f\\u4ed8\\u5546\\u6237\\u53f7\",\"title\":\"QQ\\u652f\\u4ed8\\u5546\\u6237\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199QQ\\u652f\\u4ed8\\u5206\\u914d\\u7684\\u5546\\u6237\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"key\",\"placeholder\":\"\\u5bc6\\u94a5\",\"title\":\"\\u5bc6\\u94a5\",\"desc\":\"QQ\\u652f\\u4ed8\\u5546\\u6237\\u5e73\\u53f0API\\u914d\\u7f6e\\u7684\\u5bc6\\u94a5\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5bc6\\u94a5\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"op_user_id\",\"placeholder\":\"\\u64cd\\u4f5c\\u5458\\u5e10\\u53f7\",\"title\":\"\\u64cd\\u4f5c\\u5458\\u5e10\\u53f7\\uff08\\u9000\\u6b3e\\u64cd\\u4f5c\\u5fc5\\u586b\\u9879\\uff09\",\"desc\":\"\\u4e5f\\u53ef\\u4ee5\\u662f\\u5546\\u6237\\u53f7\\uff0c\\u64cd\\u4f5c\\u5458\\u5de5\\u521b\\u5efa\\u53c2\\u8003\\uff1ahttps:\\/\\/kf.qq.com\\/faq\\/170112AZ7Fzm170112VNz6zE.html\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u64cd\\u4f5c\\u5458\\u5e10\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"op_user_passwd\",\"placeholder\":\"\\u64cd\\u4f5c\\u5458\\u5bc6\\u7801\",\"title\":\"\\u64cd\\u4f5c\\u5458\\u5bc6\\u7801\\uff08\\u9000\\u6b3e\\u64cd\\u4f5c\\u5fc5\\u586b\\u9879\\uff09\",\"desc\":\"\\u5982\\u679c\\u64cd\\u4f5c\\u5458\\u5e10\\u53f7\\u586b\\u5199\\u7684\\u662f\\u5546\\u6237\\u53f7\\uff0c\\u90a3\\u8fd9\\u91cc\\u5c31\\u662f\\u5546\\u6237\\u53f7\\u767b\\u5f55\\u5bc6\\u7801\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u64cd\\u4f5c\\u5458\\u5bc6\\u7801\"},{\"element\":\"textarea\",\"name\":\"apiclient_cert\",\"placeholder\":\"\\u8bc1\\u4e66(apiclient_cert.pem)\",\"title\":\"\\u8bc1\\u4e66(apiclient_cert.pem)\\uff08\\u9000\\u6b3e\\u64cd\\u4f5c\\u5fc5\\u586b\\u9879\\uff09\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u8bc1\\u4e66(apiclient_cert.pem)\"},{\"element\":\"textarea\",\"name\":\"apiclient_key\",\"placeholder\":\"\\u8bc1\\u4e66\\u5bc6\\u94a5(apiclient_key.pem)\",\"title\":\"\\u8bc1\\u4e66\\u5bc6\\u94a5(apiclient_key.pem)\\uff08\\u9000\\u6b3e\\u64cd\\u4f5c\\u5fc5\\u586b\\u9879\\uff09\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u8bc1\\u4e66\\u5bc6\\u94a5(apiclient_key.pem)\"},{\"element\":\"select\",\"title\":\"\\u5f02\\u6b65\\u901a\\u77e5\\u534f\\u8bae\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u534f\\u8bae\\u7c7b\\u578b\",\"name\":\"agreement\",\"is_multiple\":0,\"element_data\":[{\"value\":1,\"name\":\"\\u9ed8\\u8ba4\\u5f53\\u524d\\u534f\\u8bae\"},{\"value\":2,\"name\":\"\\u5f3a\\u5236https\\u8f6chttp\\u534f\\u8bae\"}]}]', '', '[\"pc\",\"h5\",\"qq\",\"ios\",\"android\"]', '[\"pc\",\"h5\",\"qq\",\"ios\",\"android\"]', 0, 0, 0, 1717947474, 0),
(20, '快手', 'Kuaishou', '', '1.0.0', '不限', '适用快手小程序，担保交易支付方式。 <a href=\"https://mp.kuaishou.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"app_id\",\"placeholder\":\"\\u5c0f\\u7a0b\\u5e8fAppID\",\"title\":\"\\u5c0f\\u7a0b\\u5e8fAppID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5c0f\\u7a0b\\u5e8fAppID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"app_secret\",\"placeholder\":\"\\u5c0f\\u7a0b\\u5e8fAppSecret\",\"title\":\"\\u5c0f\\u7a0b\\u5e8fAppSecret\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5c0f\\u7a0b\\u5e8fAppSecret\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"type\",\"placeholder\":\"\\u5546\\u54c1\\u7c7b\\u578b\\u3001\\u62c5\\u4fdd\\u652f\\u4ed8\\u5546\\u54c1\\u7c7b\\u76ee\\u7f16\\u53f7\",\"title\":\"\\u5546\\u54c1\\u7c7b\\u578b\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5546\\u54c1\\u7c7b\\u578b\"},{\"element\":\"message\",\"message\":\"\\u5c06\\u5f53\\u524d\\u7f51\\u7ad9\\u57df\\u540d\\u914d\\u7f6e\\u5230\\u5feb\\u624b\\u5c0f\\u7a0b\\u5e8f\\u540e\\u53f0->\\u6743\\u9650\\u7ba1\\u7406->\\u652f\\u4ed8\\u8bbe\\u7f6e->\\u652f\\u4ed8\\u56de\\u8c03\\u57df\\u540d\\u4e2d\\u3001\\u7f51\\u7ad9\\u5fc5\\u987b\\u662fhttps\\u8bf7\\u6c42\\u8bbf\\u95ee\"}]', '', '[\"kuaishou\"]', '[\"kuaishou\"]', 0, 0, 0, 1717947479, 0),
(21, '挂账支付', 'ChargePayment', '', '1.0.0', '不限', '用于线下收款，挂账、赊账、月结类型客户使用', 'Devil', 'http://shopxo.net/', '', '', '[\"pc\",\"h5\",\"ios\",\"android\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', '[\"pc\",\"h5\",\"ios\",\"android\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 0, 0, 0, 1717947483, 0),
(22, '钱包支付', 'WalletPay', '', '0.0.5', '不限', '钱包余额支付', 'Devil', 'http://shopxo.net/', '', '', '[\"pc\",\"h5\",\"ios\",\"android\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', '[\"pc\",\"h5\",\"ios\",\"android\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 0, 0, 0, 1717947488, 0),
(23, '翼支付', 'Bestpay', '', '1.0.0', '不限', '适用翼支付APP中子应用模式发起支付，即时到帐支付方式，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href=\"https://www.bestpay.com.cn/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mch_id\",\"placeholder\":\"\\u652f\\u4ed8\\u5546\\u6237\\u53f7\",\"title\":\"\\u652f\\u4ed8\\u5546\\u6237\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u652f\\u4ed8\\u5206\\u914d\\u7684\\u5546\\u6237\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"password\",\"placeholder\":\"\\u8bc1\\u4e66\\u5bc6\\u7801\",\"title\":\"\\u8bc1\\u4e66\\u5bc6\\u7801\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u8bc1\\u4e66\\u5bc6\\u7801\"},{\"element\":\"message\",\"message\":\"\\u5c06p12\\u8bc1\\u4e66\\u6309\\u7167[bestpay.P12]\\u547d\\u540d\\u653e\\u5165\\u76ee\\u5f55\\u4e2d[\\/var\\/www\\/html\\/rsakeys\\/payment_bestpay\\/bestpay.P12]\\u3001\\u5982\\u76ee\\u5f55\\u4e0d\\u5b58\\u5728\\u81ea\\u884c\\u521b\\u5efa\\u5373\\u53ef\"}]', '', '[\"pc\",\"h5\"]', '[\"pc\",\"h5\"]', 0, 0, 0, 1717947492, 0),
(24, '百度', 'BaiduMini', '', '1.0.2', '不限', '适用百度小程序，百度收银台已集成度小满、支付宝、微信支付，即时到帐支付方式，买家的交易资金直接打入卖家百度账户，快速回笼交易资金。 <a href=\"https://smartprogram.baidu.com/docs/introduction/pay/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"dealid\",\"placeholder\":\"dealId\",\"title\":\"dealId\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199dealId\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appkey\",\"placeholder\":\"APP KEY\",\"title\":\"APP KEY\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199APP KEY\"},{\"element\":\"textarea\",\"name\":\"rsa_public\",\"placeholder\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"title\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u516c\\u94a5\"},{\"element\":\"textarea\",\"name\":\"rsa_private\",\"placeholder\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"title\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u79c1\\u94a5\"},{\"element\":\"textarea\",\"name\":\"out_rsa_public\",\"placeholder\":\"\\u5e73\\u53f0\\u516c\\u94a5\",\"title\":\"\\u5e73\\u53f0\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e73\\u53f0\\u516c\\u94a5\"},{\"element\":\"message\",\"message\":\"\\u5f02\\u6b65\\u901a\\u77e5\\u5730\\u5740\\uff0c\\u5c06\\u8be5\\u5730\\u5740\\u914d\\u7f6e\\u5230\\u767e\\u5ea6\\u5c0f\\u7a0b\\u5e8f\\u652f\\u4ed8\\u540e\\u53f0\\u5f02\\u6b65\\u901a\\u77e5<br \\/>http:\\/\\/127.0.0.1:8000\\/payment_default_order_baidumini_notify.php\"}]', '', '[\"baidu\"]', '[\"baidu\"]', 0, 0, 0, 1717947497, 0),
(25, '支付宝', 'Alipay', '', '1.1.5', '不限', '2.0版本，适用PC+H5+APP，即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href=\"http://www.alipay.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appid\",\"placeholder\":\"\\u5e94\\u7528ID\",\"title\":\"\\u5e94\\u7528ID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528ID\"},{\"element\":\"textarea\",\"name\":\"rsa_public\",\"placeholder\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"title\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u516c\\u94a5\"},{\"element\":\"textarea\",\"name\":\"rsa_private\",\"placeholder\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"title\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u79c1\\u94a5\"},{\"element\":\"textarea\",\"name\":\"out_rsa_public\",\"placeholder\":\"\\u652f\\u4ed8\\u5b9d\\u516c\\u94a5\",\"title\":\"\\u652f\\u4ed8\\u5b9d\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u652f\\u4ed8\\u5b9d\\u516c\\u94a5\"}]', '', '[\"pc\",\"h5\",\"ios\",\"android\"]', '[\"pc\",\"h5\",\"ios\",\"android\"]', 0, 0, 0, 1717947501, 0),
(26, 'JeePay-微信', 'JeePayWeixin', '', '1.0.0', '不限', '微信支付、适用web端，支付链接生活、让生意更简单，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href=\"https://pay.zhishouying.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mch_no\",\"placeholder\":\"\\u5546\\u6237\\u53f7\",\"title\":\"\\u5546\\u6237\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5546\\u6237\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appid\",\"placeholder\":\"\\u5e94\\u7528ID\",\"title\":\"\\u5e94\\u7528ID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528ID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"key\",\"placeholder\":\"\\u5bc6\\u94a5\",\"title\":\"\\u5bc6\\u94a5\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5bc6\\u94a5\"}]', '', '[\"pc\"]', '[\"pc\"]', 0, 0, 0, 1717947506, 0);
INSERT INTO `sxo_payment` (`id`, `name`, `payment`, `logo`, `version`, `apply_version`, `desc`, `author`, `author_url`, `element`, `config`, `apply_terminal`, `apply_terminal_old`, `is_enable`, `is_open_user`, `sort`, `add_time`, `upd_time`) VALUES
(27, 'PayPal', 'PayPal', '', '1.0.0', '不限', '适配PC+H5+APP、国际主流支付方式，即时到帐支付方式，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href=\"https://www.paypal.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"client_id\",\"placeholder\":\"ClientID\",\"title\":\"ClientID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199ClientID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"client_secret\",\"placeholder\":\"ClientSecret\",\"title\":\"ClientSecret\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199ClientSecret\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"webhook_id\",\"placeholder\":\"\\u8ba2\\u5355WebhookID\",\"title\":\"\\u8ba2\\u5355WebhookID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u8ba2\\u5355WebhookID\\u3001\\u914d\\u7f6e\\u5f02\\u6b65\\u901a\\u77e5\\u5730\\u5740\\u540e\\u5f97\\u5230\\u7684id\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"wallet_webhook_id\",\"placeholder\":\"\\u94b1\\u5305\\u5145\\u503cWebhookID\",\"title\":\"\\u94b1\\u5305\\u5145\\u503cWebhookID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u94b1\\u5305\\u5145\\u503cWebhookID\\u3001\\u914d\\u7f6e\\u5f02\\u6b65\\u901a\\u77e5\\u5730\\u5740\\u540e\\u5f97\\u5230\\u7684id\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"membershiplevelvip_webhook_id\",\"placeholder\":\"\\u4f1a\\u5458\\u8d2d\\u4e70WebhookID\",\"title\":\"\\u4f1a\\u5458\\u8d2d\\u4e70WebhookID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u4f1a\\u5458\\u8d2d\\u4e70WebhookID\\u3001\\u914d\\u7f6e\\u5f02\\u6b65\\u901a\\u77e5\\u5730\\u5740\\u540e\\u5f97\\u5230\\u7684id\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"scanpay_webhook_id\",\"placeholder\":\"\\u626b\\u7801\\u6536\\u6b3eWebhookID\",\"title\":\"\\u626b\\u7801\\u6536\\u6b3eWebhookID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u626b\\u7801\\u6536\\u6b3eWebhookID\\u3001\\u914d\\u7f6e\\u5f02\\u6b65\\u901a\\u77e5\\u5730\\u5740\\u540e\\u5f97\\u5230\\u7684id\"},{\"element\":\"select\",\"title\":\"\\u8d27\\u5e01\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u8d27\\u5e01\",\"name\":\"currency_code\",\"is_multiple\":0,\"element_data\":[{\"value\":\"CNY\",\"name\":\"Chinese Renmenbi\"},{\"value\":\"USD\",\"name\":\"U.S. Dollar\"},{\"value\":\"AUD\",\"name\":\"Australian Dollar\"},{\"value\":\"BRL\",\"name\":\"Brazilian Real\"},{\"value\":\"CAD\",\"name\":\"Canadian Dollar\"},{\"value\":\"CZK\",\"name\":\"Czech Koruna\"},{\"value\":\"DKK\",\"name\":\"Danish Krone\"},{\"value\":\"EUR\",\"name\":\"Euro\"},{\"value\":\"HKD\",\"name\":\"Hong Kong Dollar\"},{\"value\":\"HUF\",\"name\":\"Hungarian Forint\"},{\"value\":\"ILS\",\"name\":\"Israeli New Sheqel\"},{\"value\":\"JPY\",\"name\":\"Japanese Yen\"},{\"value\":\"MYR\",\"name\":\"Malaysian Ringgit\"},{\"value\":\"MXN\",\"name\":\"Mexican Peso\"},{\"value\":\"NOK\",\"name\":\"Norwegian Krone\"},{\"value\":\"NZD\",\"name\":\"New Zealand Dollar\"},{\"value\":\"PHP\",\"name\":\"Philippine Peso\"},{\"value\":\"PLN\",\"name\":\"Polish Zloty\"},{\"value\":\"GBP\",\"name\":\"Pound Sterling\"},{\"value\":\"RUB\",\"name\":\"Russian Ruble\"},{\"value\":\"SGD\",\"name\":\"Singapore Dollar\"},{\"value\":\"SEK\",\"name\":\"Swedish Krona\"},{\"value\":\"CHF\",\"name\":\"Swiss Franc\"},{\"value\":\"TWD\",\"name\":\"Taiwan New Dollar\"},{\"value\":\"THB\",\"name\":\"Thai Baht\"}]},{\"element\":\"select\",\"title\":\"\\u662f\\u5426\\u6c99\\u76d2\\u73af\\u5883\",\"message\":\"\\u8bf7\\u9009\\u62e9\\u662f\\u5426\\u6c99\\u76d2\\u73af\\u5883\",\"name\":\"is_dev_env\",\"is_multiple\":0,\"element_data\":[{\"value\":0,\"name\":\"\\u5426\"},{\"value\":1,\"name\":\"\\u662f\"}]},{\"element\":\"message\",\"message\":\"1. \\u8ba2\\u5355\\u5f02\\u6b65\\u901a\\u77e5\\u5730\\u5740\\uff0c\\u5c06\\u8be5\\u5730\\u5740\\u914d\\u7f6e\\u5230\\u652f\\u4ed8\\u540e\\u53f0\\u5f02\\u6b65\\u901a\\u77e5<br \\/>http:\\/\\/127.0.0.1:8000\\/payment_default_order_paypal_notify.php<br \\/><br \\/>2. \\u94b1\\u5305\\u5145\\u503c\\u5f02\\u6b65\\u901a\\u77e5\\u5730\\u5740\\uff0c\\u5c06\\u8be5\\u5730\\u5740\\u914d\\u7f6e\\u5230\\u652f\\u4ed8\\u540e\\u53f0\\u5f02\\u6b65\\u901a\\u77e5<br \\/>http:\\/\\/127.0.0.1:8000\\/payment_default_wallet_paypal_notify.php<br \\/><br \\/>3. \\u4f1a\\u5458\\u7b49\\u7ea7\\u8d2d\\u4e70\\u5f02\\u6b65\\u901a\\u77e5\\u5730\\u5740\\uff0c\\u5c06\\u8be5\\u5730\\u5740\\u914d\\u7f6e\\u5230\\u652f\\u4ed8\\u540e\\u53f0\\u5f02\\u6b65\\u901a\\u77e5<br \\/>http:\\/\\/127.0.0.1:8000\\/payment_default_membershiplevelvip_paypal_notify.php<br \\/><br \\/>4. \\u626b\\u7801\\u6536\\u6b3e\\u5f02\\u6b65\\u901a\\u77e5\\u5730\\u5740\\uff0c\\u5c06\\u8be5\\u5730\\u5740\\u914d\\u7f6e\\u5230\\u652f\\u4ed8\\u540e\\u53f0\\u5f02\\u6b65\\u901a\\u77e5<br \\/>http:\\/\\/127.0.0.1:8000\\/payment_default_scanpay_paypal_notify.php<br \\/><br \\/>\\u5f02\\u6b65\\u901a\\u77e5\\u7c7b\\u578b\\u52fe\\u9009\\u3010Payments & Payouts \\u4e0b\\u9762\\u7684 Payment capture completed\\u3011\\u5373\\u53ef\"}]', '', '[\"pc\",\"h5\",\"ios\",\"android\"]', '[\"pc\",\"h5\",\"ios\",\"android\"]', 0, 0, 0, 1717947509, 0),
(28, 'JeePay-支付宝', 'JeePayAlipay', '', '1.0.0', '不限', '支付宝支付、适用web端，支付链接生活、让生意更简单，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href=\"https://pay.zhishouying.com/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mch_no\",\"placeholder\":\"\\u5546\\u6237\\u53f7\",\"title\":\"\\u5546\\u6237\\u53f7\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5546\\u6237\\u53f7\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"appid\",\"placeholder\":\"\\u5e94\\u7528ID\",\"title\":\"\\u5e94\\u7528ID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528ID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"key\",\"placeholder\":\"\\u5bc6\\u94a5\",\"title\":\"\\u5bc6\\u94a5\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u5bc6\\u94a5\"}]', '', '[\"pc\"]', '[\"pc\"]', 0, 0, 0, 1717947513, 0),
(29, 'TrySeePayPay', 'TrySeePayPay', '', '1.0.0', '不限', 'TrySeePayPay支持（pc/h5/ios/android），即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href=\"https://www.try-see.net/\" target=\"_blank\">立即申请</a>', 'Devil', 'http://shopxo.net/', '[{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mch_id\",\"placeholder\":\"\\u4f1a\\u5458\\u5e97ID\",\"title\":\"\\u4f1a\\u5458\\u5e97ID\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u4f1a\\u5458\\u5e97ID\"},{\"element\":\"input\",\"type\":\"text\",\"default\":\"\",\"name\":\"mch_key\",\"placeholder\":\"\\u4f1a\\u5458\\u5e97\\u79d8\\u94a5\",\"title\":\"\\u4f1a\\u5458\\u5e97\\u79d8\\u94a5\",\"is_required\":0,\"message\":\"\\u8bf7\\u586b\\u5199\\u4f1a\\u5458\\u5e97\\u79d8\\u94a5\"},{\"element\":\"textarea\",\"name\":\"rsa_private\",\"placeholder\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"title\":\"\\u5e94\\u7528\\u79c1\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u79c1\\u94a5\"},{\"element\":\"textarea\",\"name\":\"rsa_public\",\"placeholder\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"title\":\"\\u5e94\\u7528\\u516c\\u94a5\",\"is_required\":0,\"rows\":6,\"message\":\"\\u8bf7\\u586b\\u5199\\u5e94\\u7528\\u516c\\u94a5\"},{\"element\":\"message\",\"message\":\"\\u8be5\\u652f\\u4ed8\\u63d2\\u4ef6\\u3010\\u652f\\u4ed8\\u91d1\\u989d\\u548c\\u9000\\u6b3e\\u91d1\\u989d\\u3011\\u5fc5\\u987b\\u4e3a\\u6574\\u6570\\uff0c\\u7cfb\\u7edf\\u5c06\\u81ea\\u52a8\\u8f6c\\u4e3a\\u6574\\u6570\\u53bb\\u9664\\u5c0f\\u6570\\u70b9\"}]', '', '[\"pc\",\"h5\",\"ios\",\"android\"]', '[\"pc\",\"h5\",\"ios\",\"android\"]', 0, 0, 0, 1717947518, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_pay_log`
--

DROP TABLE IF EXISTS `sxo_pay_log`;
CREATE TABLE `sxo_pay_log` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '支付日志id',
  `log_no` char(60) NOT NULL DEFAULT '' COMMENT '支付日志订单号',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `business_type` char(180) NOT NULL DEFAULT '' COMMENT '业务类型，字符串（如：订单 order、钱包充值 wallet、会员购买 member、等…）',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态（0待支付, 1已支付, 2已关闭）正常30分钟内未支付将关闭',
  `payment` char(60) NOT NULL DEFAULT '' COMMENT '支付方式标记',
  `payment_name` char(60) NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `subject` char(255) NOT NULL DEFAULT '' COMMENT '订单名称',
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '业务订单金额',
  `pay_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '支付金额',
  `trade_no` char(100) NOT NULL DEFAULT '' COMMENT '支付平台交易号',
  `buyer_user` char(60) NOT NULL DEFAULT '' COMMENT '支付平台用户帐号',
  `pay_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付时间',
  `close_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关闭时间',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='支付日志';

--
-- 转存表中的数据 `sxo_pay_log`
--

INSERT INTO `sxo_pay_log` (`id`, `log_no`, `user_id`, `business_type`, `status`, `payment`, `payment_name`, `subject`, `total_price`, `pay_price`, `trade_no`, `buyer_user`, `pay_time`, `close_time`, `add_time`) VALUES
(1, '20240609233116166969', 1, 'order', 0, 'Weixin', '微信', '订单支付', 4800.00, 0.00, '', '', 0, 0, 1717947076),
(2, '20240610004245162788', 1, 'order', 0, 'Weixin', '微信', '订单支付', 2600.00, 0.00, '', '', 0, 0, 1717951365);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_pay_log_value`
--

DROP TABLE IF EXISTS `sxo_pay_log_value`;
CREATE TABLE `sxo_pay_log_value` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `pay_log_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付日志id',
  `business_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '业务订单id',
  `business_no` char(60) NOT NULL DEFAULT '' COMMENT '业务订单号',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='支付日志关联业务数据';

--
-- 转存表中的数据 `sxo_pay_log_value`
--

INSERT INTO `sxo_pay_log_value` (`id`, `pay_log_id`, `business_id`, `business_no`, `add_time`) VALUES
(1, 1, 1, '20240609233114010112', 1717947076),
(2, 2, 2, '20240610004244017997', 1717951365);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_pay_request_log`
--

DROP TABLE IF EXISTS `sxo_pay_request_log`;
CREATE TABLE `sxo_pay_request_log` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `business_type` char(180) NOT NULL DEFAULT '' COMMENT '业务类型，字符串（如：订单、钱包充值、会员购买、等...）',
  `request_params` mediumtext DEFAULT NULL COMMENT '请求参数（数组则json字符串存储）',
  `response_data` mediumtext DEFAULT NULL COMMENT '响应参数（数组则json字符串存储）',
  `business_handle` text DEFAULT NULL COMMENT '业务处理结果（数组则json字符串存储）',
  `request_url` text DEFAULT NULL COMMENT '请求url地址',
  `server_port` char(10) NOT NULL DEFAULT '' COMMENT '端口号',
  `server_ip` char(15) NOT NULL DEFAULT '' COMMENT '服务器ip',
  `client_ip` char(15) NOT NULL DEFAULT '' COMMENT '客户端ip',
  `os` char(20) NOT NULL DEFAULT '' COMMENT '操作系统',
  `browser` char(20) NOT NULL DEFAULT '' COMMENT '浏览器',
  `method` char(4) NOT NULL DEFAULT '' COMMENT '请求类型',
  `scheme` char(5) NOT NULL DEFAULT '' COMMENT 'http类型',
  `version` char(5) NOT NULL DEFAULT '' COMMENT 'http版本',
  `client` char(255) NOT NULL DEFAULT '' COMMENT '客户端详情信息',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='支付请求日志';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_plugins`
--

DROP TABLE IF EXISTS `sxo_plugins`;
CREATE TABLE `sxo_plugins` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '插件名称',
  `plugins` char(60) NOT NULL DEFAULT '' COMMENT '唯一标记',
  `plugins_category_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '插件分类',
  `plugins_menu_control` char(120) NOT NULL DEFAULT '' COMMENT '插件所属左侧菜单',
  `data` longtext DEFAULT NULL COMMENT '应用数据',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='应用';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_plugins_category`
--

DROP TABLE IF EXISTS `sxo_plugins_category`;
CREATE TABLE `sxo_plugins_category` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '名称',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '顺序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='应用分类';

--
-- 转存表中的数据 `sxo_plugins_category`
--

INSERT INTO `sxo_plugins_category` (`id`, `name`, `is_enable`, `sort`, `add_time`, `upd_time`) VALUES
(1, '管理增强', 1, 0, 1685093165, 0),
(2, '营销活动', 1, 0, 1685093255, 1690030522),
(3, '其他工具', 1, 0, 1685093289, 1690035229),
(4, '扩展模块', 1, 0, 1685093456, 1690030515);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_power`
--

DROP TABLE IF EXISTS `sxo_power`;
CREATE TABLE `sxo_power` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '权限id',
  `pid` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '权限父级id',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '权限名称',
  `control` char(30) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action` char(30) NOT NULL DEFAULT '' COMMENT '方法名称',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '自定义url地址',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_show` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示（0否，1是）',
  `icon` char(60) NOT NULL DEFAULT '' COMMENT '图标class',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='权限';

--
-- 转存表中的数据 `sxo_power`
--

INSERT INTO `sxo_power` (`id`, `pid`, `name`, `control`, `action`, `url`, `sort`, `is_show`, `icon`, `add_time`, `upd_time`) VALUES
(1, 0, '权限', 'Power', 'Index', '', 3, 1, 'icon-admin-auth', 1481612301, 0),
(4, 1, '角色管理', 'Role', 'Index', '', 20, 1, '', 1481639037, 0),
(13, 1, '权限分配', 'Power', 'Index', '', 30, 1, '', 1482156143, 0),
(15, 13, '权限添加/编辑', 'Power', 'Save', '', 31, 0, '', 1482243750, 0),
(16, 13, '权限删除', 'Power', 'Delete', '', 33, 0, '', 1482243797, 1704334289),
(17, 4, '角色组添加/编辑页面', 'Role', 'SaveInfo', '', 21, 0, '', 1482243855, 0),
(18, 4, '角色组添加/编辑', 'Role', 'Save', '', 22, 0, '', 1482243888, 0),
(19, 22, '管理员添加/编辑页面', 'Admin', 'SaveInfo', '', 2, 0, '', 1482244637, 0),
(20, 22, '管理员添加/编辑', 'Admin', 'Save', '', 3, 0, '', 1482244666, 0),
(21, 22, '管理员删除', 'Admin', 'Delete', '', 4, 0, '', 1482244688, 0),
(22, 1, '管理员列表', 'Admin', 'Index', '', 1, 1, '', 1482568868, 0),
(23, 4, '角色删除', 'Role', 'Delete', '', 23, 0, '', 1482569155, 0),
(38, 0, '商品', 'Goods', 'Index', '', 5, 1, 'icon-admin-goods', 1483283430, 0),
(39, 38, '商品管理', 'Goods', 'Index', '', 1, 1, '', 1483283546, 0),
(41, 0, '系统', 'Config', 'Index', '', 1, 1, 'icon-admin-system', 1483362358, 1704280861),
(42, 41, '配置保存', 'Config', 'Save', '', 10, 0, '', 1483432335, 0),
(57, 39, '商品添加/编辑页面', 'Goods', 'SaveInfo', '', 2, 0, '', 1483616439, 0),
(58, 39, '商品添加/编辑', 'Goods', 'Save', '', 3, 0, '', 1483616492, 0),
(59, 39, '商品删除', 'Goods', 'Delete', '', 4, 0, '', 1483616569, 0),
(81, 0, '站点', 'Site', 'Index', '', 2, 1, 'icon-admin-site-config', 1486182943, 0),
(103, 81, '站点设置', 'Site', 'Index', '', 0, 1, '', 1486561470, 0),
(104, 81, '短信设置', 'Sms', 'Index', '', 10, 1, '', 1486561615, 0),
(105, 103, '站点设置编辑', 'Site', 'Save', '', 1, 0, '', 1486561780, 0),
(107, 104, '短信设置编辑', 'Sms', 'Save', '', 11, 0, '', 1486562011, 0),
(118, 0, '工具', 'Tool', 'Index', '', 50, 1, 'icon-admin-tools', 1488108044, 0),
(119, 118, '缓存管理', 'Cache', 'Index', '', 1, 1, '', 1488108107, 0),
(120, 119, '站点缓存更新', 'Cache', 'StatusUpdate', '', 2, 0, '', 1488108235, 0),
(121, 119, '模板缓存更新', 'Cache', 'TemplateUpdate', '', 2, 0, '', 1488108390, 0),
(122, 119, '模块缓存更新', 'Cache', 'ModuleUpdate', '', 3, 0, '', 1488108436, 0),
(126, 0, '用户', 'User', 'Index', '', 4, 1, 'icon-admin-user', 1490794162, 0),
(127, 126, '用户列表', 'User', 'Index', '', 0, 1, '', 1490794316, 0),
(128, 127, '用户编辑/添加页面', 'User', 'SaveInfo', '', 1, 0, '', 1490794458, 0),
(129, 127, '用户添加/编辑', 'User', 'Save', '', 2, 0, '', 1490794510, 0),
(130, 127, '用户删除', 'User', 'Delete', '', 3, 0, '', 1490794585, 0),
(153, 222, '地区管理', 'Region', 'Index', '', 70, 1, '', 1526304473, 0),
(154, 153, '地区添加/编辑', 'Region', 'Save', '', 0, 0, '', 1526304503, 0),
(155, 153, '地区删除', 'Region', 'Delete', '', 2, 0, '', 1526304531, 1704335398),
(156, 222, '快递管理', 'Express', 'Index', '', 80, 1, '', 1526304473, 0),
(157, 156, '快递添加/编辑', 'Express', 'Save', '', 0, 0, '', 1526304473, 0),
(158, 156, '快递删除', 'Express', 'Delete', '', 1, 0, '', 1526304473, 0),
(172, 222, '首页轮播', 'Slide', 'Index', '', 60, 1, '', 1527149117, 0),
(173, 172, '轮播添加/编辑页面', 'Slide', 'SaveInfo', '', 0, 0, '', 1527149152, 0),
(174, 172, '轮播添加/编辑', 'Slide', 'Save', '', 1, 0, '', 1527149186, 0),
(175, 172, '轮播状态更新', 'Slide', 'StatusUpdate', '', 2, 0, '', 1527156980, 0),
(176, 172, '轮播删除', 'Slide', 'Delete', '', 3, 0, '', 1527157260, 0),
(177, 0, '订单', 'Order', 'Index', '', 6, 1, 'icon-admin-order', 1522229870, 0),
(178, 177, '订单管理', 'Order', 'Index', '', 1, 1, '', 1522317898, 0),
(179, 178, '订单删除', 'Order', 'Delete', '', 2, 0, '', 1522317917, 0),
(180, 178, '订单取消', 'Order', 'Cancel', '', 3, 0, '', 1527497803, 0),
(181, 39, '商品状态更新', 'Goods', 'StatusUpdate', '', 5, 0, '', 1528080200, 0),
(182, 0, '数据', 'Data', 'Index', '', 22, 1, 'icon-admin-data', 1528096661, 0),
(183, 182, '消息管理', 'Message', 'Index', '', 10, 1, '', 1528080200, 0),
(184, 183, '消息删除', 'Message', 'Delete', '', 11, 0, '', 1528080200, 0),
(185, 182, '支付日志', 'PayLog', 'Index', '', 20, 1, '', 1528080200, 0),
(186, 182, '积分日志', 'IntegralLog', 'Index', '', 40, 1, '', 1528103067, 0),
(193, 222, '筛选价格', 'ScreeningPrice', 'Index', '', 50, 1, '', 1528708578, 0),
(194, 193, '筛选价格添加/编辑', 'ScreeningPrice', 'Save', '', 0, 0, '', 1528708609, 0),
(199, 81, 'SEO设置', 'Seo', 'Index', '', 30, 1, '', 1528771081, 0),
(200, 199, 'SEO设置编辑', 'Seo', 'Save', '', 31, 0, '', 1528771105, 0),
(201, 38, '商品分类', 'GoodsCategory', 'Index', '', 10, 1, '', 1529041901, 0),
(202, 201, '商品分类添加/编辑', 'GoodsCategory', 'Save', '', 11, 0, '', 1529041928, 0),
(203, 201, '商品分类删除', 'GoodsCategory', 'Delete', '', 13, 0, '', 1529041949, 1704335314),
(204, 0, '文章', 'Article', 'Index', '', 21, 1, 'icon-admin-article', 1530360560, 0),
(205, 204, '文章管理', 'Article', 'Index', '', 0, 1, '', 1530360593, 0),
(206, 205, '文章添加/编辑页面', 'Article', 'SaveInfo', '', 1, 0, '', 1530360625, 0),
(207, 205, '文章添加/编辑', 'Article', 'Save', '', 2, 0, '', 1530360663, 0),
(208, 205, '文章删除', 'Article', 'Delete', '', 3, 0, '', 1530360692, 0),
(209, 205, '文章状态更新', 'Article', 'StatusUpdate', '', 4, 0, '', 1530360730, 0),
(210, 204, '文章分类', 'ArticleCategory', 'Index', '', 10, 1, '', 1530361071, 0),
(211, 210, '文章分类编辑/添加', 'ArticleCategory', 'Save', '', 11, 0, '', 1530361101, 0),
(212, 210, '文章分类删除', 'ArticleCategory', 'Delete', '', 12, 0, '', 1530361126, 0),
(219, 81, '邮箱设置', 'Email', 'Index', '', 20, 1, '', 1533636067, 0),
(220, 219, '邮箱设置/编辑', 'Email', 'Save', '', 21, 0, '', 1533636109, 0),
(221, 219, '邮件发送测试', 'Email', 'EmailTest', '', 22, 0, '', 1533636157, 0),
(222, 0, '网站', 'Navigation', 'Index', '', 7, 1, 'icon-admin-web-manage', 1533692051, 0),
(223, 222, '导航管理', 'Navigation', 'Index', '', 0, 1, '', 1486183114, 0),
(226, 223, '导航添加/编辑', 'Navigation', 'Save', '', 0, 0, '', 1486183367, 0),
(227, 223, '导航删除', 'Navigation', 'Delete', '', 1, 0, '', 1486183410, 0),
(228, 223, '导航状态更新', 'Navigation', 'StatusUpdate', '', 2, 0, '', 1486183462, 0),
(234, 222, '自定义页面', 'CustomView', 'Index', '', 11, 1, '', 1486193400, 0),
(235, 234, '自定义页面添加/编辑页面', 'CustomView', 'SaveInfo', '', 0, 0, '', 1486193449, 0),
(236, 234, '自定义页面添加/编辑', 'CustomView', 'Save', '', 1, 0, '', 1486193473, 0),
(237, 234, '自定义页面删除', 'CustomView', 'Delete', '', 2, 0, '', 1486193516, 0),
(238, 234, '自定义页面状态更新', 'CustomView', 'StatusUpdate', '', 3, 0, '', 1486193582, 0),
(239, 222, '友情链接', 'Link', 'Index', '', 21, 1, '', 1486194358, 0),
(240, 239, '友情链接添加/编辑页面', 'Link', 'SaveInfo', '', 0, 0, '', 1486194392, 0),
(241, 239, '友情链接添加/编辑', 'Link', 'Save', '', 1, 0, '', 1486194413, 0),
(242, 239, '友情链接删除', 'Link', 'Delete', '', 2, 0, '', 1486194435, 0),
(243, 239, '友情链接状态更新', 'Link', 'StatusUpdate', '', 3, 0, '', 1486194479, 0),
(244, 222, '主题管理', 'ThemeAdmin', 'Index', '', 30, 1, '', 1494381693, 1711549593),
(245, 244, '主题管理添加/编辑', 'ThemeAdmin', 'Save', '', 0, 0, '', 1494398194, 1711549600),
(246, 244, '主题上传安装', 'ThemeAdmin', 'Upload', '', 1, 0, '', 1494405096, 1711549604),
(247, 244, '主题删除', 'ThemeAdmin', 'Delete', '', 2, 0, '', 1494410655, 1711549608),
(248, 205, '文章详情', 'Article', 'Detail', '', 5, 0, '', 1534156400, 0),
(249, 252, '品牌管理', 'Brand', 'Index', '', 0, 1, '', 1535683271, 0),
(250, 249, '品牌添加/编辑', 'Brand', 'Save', '', 2, 0, '', 1535683310, 0),
(251, 249, '品牌删除', 'Brand', 'Delete', '', 4, 0, '', 1535683351, 0),
(252, 0, '品牌', 'Brand', 'Index', '', 8, 1, 'icon-admin-brand', 1535684308, 0),
(253, 252, '品牌分类', 'BrandCategory', 'Index', '', 10, 1, '', 1535684401, 0),
(254, 253, '品牌分类添加/编辑', 'BrandCategory', 'Save', '', 11, 0, '', 1535684424, 0),
(255, 253, '品牌分类删除', 'BrandCategory', 'Delete', '', 12, 0, '', 1535684444, 0),
(256, 249, '品牌添加/编辑页面', 'Brand', 'SaveInfo', '', 1, 0, '', 1535694837, 0),
(257, 249, '品牌状态更新', 'Brand', 'StatusUpdate', '', 3, 0, '', 1535694880, 0),
(258, 193, '筛选价格删除', 'ScreeningPrice', 'Delete', '', 1, 0, '', 1536227071, 0),
(259, 222, '支付方式', 'Payment', 'Index', '', 90, 1, '', 1537156351, 0),
(260, 259, '支付方式安装/编辑页面', 'Payment', 'SaveInfo', '', 0, 0, '', 1537156423, 0),
(261, 259, '支付方式安装/编辑', 'Payment', 'Save', '', 1, 0, '', 1537156463, 0),
(262, 259, '支付方式删除', 'Payment', 'Delete', '', 2, 0, '', 1537156502, 0),
(263, 259, '支付方式安装', 'Payment', 'Install', '', 3, 0, '', 1537166090, 0),
(264, 259, '支付方式状态更新', 'Payment', 'StatusUpdate', '', 4, 0, '', 1537166149, 0),
(265, 259, '支付方式卸载', 'Payment', 'Uninstall', '', 5, 0, '', 1537167814, 0),
(266, 259, '支付方式上传', 'Payment', 'Upload', '', 6, 0, '', 1537173653, 0),
(267, 178, '订单发货', 'Order', 'Delivery', '', 1, 0, '', 1538413499, 0),
(268, 178, '订单收货', 'Order', 'Collect', '', 5, 0, '', 1538414034, 0),
(269, 178, '订单支付', 'Order', 'Pay', '', 6, 0, '', 1538757043, 0),
(310, 178, '订单确认', 'Order', 'Confirm', '', 7, 0, '', 1542011799, 0),
(311, 4, '角色状态更新', 'Role', 'StatusUpdate', '', 24, 0, '', 1542102071, 0),
(314, 319, '首页导航', 'AppHomeNav', 'Index', '', 10, 1, '', 1542558318, 0),
(315, 314, '首页导航添加/编辑页面', 'AppHomeNav', 'SaveInfo', '', 11, 0, '', 1542558686, 0),
(316, 314, '首页导航添加/编辑', 'AppHomeNav', 'Save', '', 12, 0, '', 1542558706, 0),
(317, 314, '首页导航状态更新', 'AppHomeNav', 'StatusUpdate', '', 13, 0, '', 1542558747, 0),
(318, 314, '首页导航删除', 'AppHomeNav', 'Delete', '', 14, 0, '', 1542558767, 0),
(319, 0, '手机', 'App', 'Index', '', 20, 1, 'icon-admin-phone', 1483362358, 0),
(326, 319, '基础配置', 'AppConfig', 'Index', '', 0, 1, '', 1543206359, 0),
(327, 326, '基础配置保存', 'AppConfig', 'Save', '', 1, 0, '', 1543206402, 0),
(331, 119, '日志删除', 'Cache', 'LogDelete', '', 4, 0, '', 1545642163, 0),
(333, 319, '小程序配置', 'AppMini', 'Config', '', 40, 1, '', 1546935090, 0),
(334, 333, '小程序配置保存', 'AppMini', 'Save', '', 41, 0, '', 1546935118, 0),
(339, 41, '系统配置', 'Config', 'Index', '', 0, 1, '', 1549419752, 0),
(342, 341, '应用状态更新', 'PluginsAdmin', 'StatusUpdate', '', 3, 0, '', 1549694138, 0),
(343, 341, '应用调用管理', 'Plugins', 'Index', '', 1, 0, '', 1549958187, 0),
(345, 341, '应用添加/编辑页面', 'PluginsAdmin', 'SaveInfo', '', 1, 0, '', 1549977925, 0),
(346, 341, '应用添加/编辑', 'PluginsAdmin', 'Save', '', 2, 0, '', 1549977958, 0),
(347, 341, '应用删除', 'PluginsAdmin', 'Delete', '', 4, 0, '', 1549977993, 0),
(348, 341, '应用上传', 'PluginsAdmin', 'Upload', '', 5, 0, '', 1550110821, 0),
(349, 118, 'SQL控制台', 'Sqlconsole', 'Index', '', 10, 1, '', 1550476002, 0),
(350, 349, 'SQL执行', 'Sqlconsole', 'Implement', '', 11, 0, '', 1550476023, 0),
(351, 341, '应用打包', 'PluginsAdmin', 'Download', '', 6, 0, '', 1553248727, 0),
(354, 41, '商店信息', 'Config', 'Store', '', 1, 1, '', 1554803430, 1704357821),
(356, 38, '商品评论', 'Goodscomments', 'Index', '', 40, 1, '', 1533112443, 0),
(357, 356, '商品评论回复', 'Goodscomments', 'Reply', '', 41, 0, '', 1533119660, 0),
(358, 356, '商品评论删除', 'Goodscomments', 'Delete', '', 42, 0, '', 1533119680, 0),
(359, 356, '商品评论状态更新', 'Goodscomments', 'StatusUpdate', '', 43, 0, '', 1533119704, 0),
(360, 356, '商品评论添加/编辑页面', 'Goodscomments', 'SaveInfo', '', 44, 0, '', 1553964318, 0),
(361, 356, '商品评论添加/编辑', 'Goodscomments', 'Save', '', 45, 0, '', 1553964354, 0),
(362, 81, '协议管理', 'Agreement', 'Index', '', 40, 1, '', 1486561615, 0),
(363, 362, '协议设置编辑', 'Agreement', 'Save', '', 41, 0, '', 1486562011, 0),
(364, 177, '订单售后', 'Orderaftersale', 'Index', '', 10, 1, '', 1522317898, 0),
(365, 364, '订单售后删除', 'Orderaftersale', 'Delete', '', 11, 0, '', 1522317917, 0),
(366, 364, '订单售后取消', 'Orderaftersale', 'Cancel', '', 12, 0, '', 1527497803, 0),
(367, 364, '订单售后审核', 'Orderaftersale', 'Audit', '', 13, 0, '', 1538413499, 0),
(368, 364, '订单售后确认', 'Orderaftersale', 'Confirm', '', 14, 0, '', 1538414034, 0),
(369, 364, '订单售后拒绝', 'Orderaftersale', 'Refuse', '', 15, 0, '', 1538757043, 0),
(372, 182, '退款日志', 'RefundLog', 'Index', '', 30, 1, '', 1528080200, 0),
(374, 341, '应用安装', 'PluginsAdmin', 'Install', '', 6, 0, '', 1561369950, 0),
(375, 341, '应用卸载', 'PluginsAdmin', 'Uninstall', '', 7, 0, '', 1561370063, 0),
(376, 319, '用户中心导航', 'AppCenterNav', 'Index', '', 20, 1, '', 1542558318, 0),
(377, 376, '用户中心导航添加/编辑页面', 'AppCenterNav', 'SaveInfo', '', 21, 0, '', 1542558686, 0),
(378, 376, '用户中心导航添加/编辑', 'AppCenterNav', 'Save', '', 22, 0, '', 1542558706, 0),
(379, 376, '用户中心导航状态更新', 'AppCenterNav', 'StatusUpdate', '', 23, 0, '', 1542558747, 0),
(380, 376, '用户中心导航删除', 'AppCenterNav', 'Delete', '', 24, 0, '', 1542558767, 0),
(387, 244, '主题下载', 'ThemeAdmin', 'Download', '', 3, 0, '', 1494410699, 1711549612),
(400, 178, '订单详情', 'Order', 'Detail', '', 8, 0, '', 1589534580, 0),
(401, 364, '订单售后详情', 'Orderaftersale', 'Detail', '', 16, 0, '', 1589538361, 0),
(402, 39, '商品详情', 'Goods', 'Detail', '', 6, 0, '', 1589539780, 0),
(403, 356, '商品评论详情', 'Goodscomments', 'Detail', '', 36, 0, '', 1591609904, 0),
(404, 127, '用户详情', 'User', 'Detail', '', 7, 0, '', 1591621569, 0),
(405, 22, '管理员详情', 'Admin', 'Detail', '', 5, 0, '', 1591951422, 0),
(406, 4, '角色详情', 'Role', 'Detail', '', 25, 0, '', 1542102071, 0),
(407, 234, '自定义页面详情', 'CustomView', 'Detail', '', 4, 0, '', 1592287822, 0),
(408, 172, '轮播详情', 'Slide', 'Detail', '', 4, 0, '', 1592413297, 0),
(409, 249, '品牌详情', 'Brand', 'Detail', '', 6, 0, '', 1592563170, 0),
(410, 314, '首页导航详情', 'AppHomeNav', 'Detail', '', 15, 0, '', 1592652323, 0),
(411, 376, '用户中心导航详情', 'AppCenterNav', 'Detail', '', 25, 0, '', 1592661364, 0),
(413, 183, '消息详情', 'Message', 'Detail', '', 12, 0, '', 1593181414, 0),
(414, 239, '友情链接详情', 'Link', 'Detail', '', 4, 0, '', 1593181677, 0),
(415, 185, '支付日志详情', 'PayLog', 'Detail', '', 21, 0, '', 1593355200, 0),
(416, 372, '退款日志详情', 'RefundLog', 'Detail', '', 31, 0, '', 1593355237, 0),
(417, 186, '积分日志详情', 'IntegralLog', 'Detail', '', 41, 0, '', 1593355265, 0),
(418, 38, '商品浏览', 'GoodsBrowse', 'Index', '', 50, 1, '', 1591609904, 0),
(419, 418, '商品浏览删除', 'GoodsBrowse', 'Delete', '', 51, 0, '', 1591609904, 0),
(420, 418, '商品浏览详情', 'GoodsBrowse', 'Detail', '', 52, 0, '', 1591609904, 0),
(421, 38, '商品收藏', 'GoodsFavor', 'Index', '', 60, 1, '', 1591609904, 0),
(422, 421, '商品收藏删除', 'GoodsFavor', 'Delete', '', 61, 0, '', 1591609904, 0),
(423, 421, '商品收藏详情', 'GoodsFavor', 'Detail', '', 62, 0, '', 1591609904, 0),
(425, 438, '仓库管理', 'Warehouse', 'Index', '', 0, 1, '', 1488108044, 0),
(426, 425, '仓库添加/编辑页面', 'Warehouse', 'SaveInfo', '', 1, 0, '', 1530360625, 0),
(427, 425, '仓库添加/编辑', 'Warehouse', 'Save', '', 2, 0, '', 1530360663, 0),
(428, 425, '仓库删除', 'Warehouse', 'Delete', '', 3, 0, '', 1530360692, 0),
(429, 425, '仓库状态更新', 'Warehouse', 'StatusUpdate', '', 4, 0, '', 1530360730, 0),
(430, 425, '仓库详情', 'Warehouse', 'Detail', '', 5, 0, '', 1534156400, 0),
(431, 438, '仓库商品管理', 'WarehouseGoods', 'Index', '', 10, 1, '', 1488108044, 0),
(432, 431, '仓库商品删除', 'WarehouseGoods', 'Delete', '', 12, 0, '', 1530360625, 0),
(433, 431, '仓库商品搜索添加', 'WarehouseGoods', 'GoodsAdd', '', 15, 0, '', 1530360663, 0),
(434, 431, '仓库商品搜索删除', 'WarehouseGoods', 'GoodsDel', '', 16, 0, '', 1530360692, 0),
(435, 431, '仓库商品状态更新', 'WarehouseGoods', 'StatusUpdate', '', 13, 0, '', 1530360730, 0),
(436, 431, '仓库商品详情', 'WarehouseGoods', 'Detail', '', 11, 0, '', 1534156400, 0),
(438, 0, '仓库', 'Warehouse', 'Index', '', 9, 1, 'icon-admin-warehouse', 1483362358, 0),
(439, 431, '仓库商品搜索', 'WarehouseGoods', 'GoodsSearch', '', 14, 0, '', 1534156400, 0),
(440, 431, '仓库商品库存编辑页面', 'WarehouseGoods', 'InventoryInfo', '', 17, 0, '', 1534156400, 0),
(441, 431, '仓库商品库存编辑', 'WarehouseGoods', 'InventorySave', '', 18, 0, '', 0, 0),
(442, 185, '支付日志关闭', 'PayLog', 'Close', '', 22, 0, '', 1593355200, 0),
(443, 222, '快捷导航', 'QuickNav', 'Index', '', 100, 1, '', 1542558318, 0),
(444, 443, '快捷导航添加/编辑页面', 'QuickNav', 'SaveInfo', '', 0, 0, '', 1542558686, 0),
(445, 443, '快捷导航添加/编辑', 'QuickNav', 'Save', '', 1, 0, '', 1542558706, 0),
(446, 443, '快捷导航状态更新', 'QuickNav', 'StatusUpdate', '', 2, 0, '', 1542558747, 0),
(447, 443, '快捷导航删除', 'QuickNav', 'Delete', '', 3, 0, '', 1542558767, 0),
(448, 443, '快捷导航详情', 'QuickNav', 'Detail', '', 4, 0, '', 1592652323, 0),
(449, 185, '支付请求日志列表', 'PayRequestLog', 'Index', '', 25, 0, '', 1593355200, 0),
(450, 185, '支付请求日志详情', 'PayRequestLog', 'Detail', '', 26, 0, '', 1593355200, 0),
(451, 126, '用户地址', 'UserAddress', 'Index', '', 10, 1, '', 1490794316, 0),
(452, 451, '用户地址编辑页面', 'UserAddress', 'SaveInfo', '', 11, 0, '', 1490794510, 0),
(453, 451, '用户地址编辑', 'UserAddress', 'Save', '', 12, 0, '', 1490794510, 0),
(454, 451, '用户地址删除', 'UserAddress', 'Delete', '', 13, 0, '', 1591621569, 0),
(455, 451, '用户地址详情', 'UserAddress', 'Detail', '', 14, 0, '', 0, 0),
(461, 103, '站点设置商品搜索', 'Site', 'GoodsSearch', '', 2, 0, '', 1486561780, 0),
(462, 38, '商品参数', 'GoodsParamsTemplate', 'Index', '', 20, 1, '', 1533112443, 0),
(463, 462, '商品参数删除', 'GoodsParamsTemplate', 'Delete', '', 21, 0, '', 1533119680, 0),
(464, 462, '商品参数状态更新', 'GoodsParamsTemplate', 'StatusUpdate', '', 22, 0, '', 1533119704, 0),
(465, 462, '商品参数添加/编辑页面', 'GoodsParamsTemplate', 'SaveInfo', '', 23, 0, '', 1553964318, 0),
(466, 462, '商品参数添加/编辑', 'GoodsParamsTemplate', 'Save', '', 24, 0, '', 1553964354, 0),
(467, 462, '商品参数详情', 'GoodsParamsTemplate', 'Detail', '', 25, 0, '', 1591609904, 0),
(468, 341, '应用排序保存', 'PluginsAdmin', 'SetupSave', '', 8, 0, '', 1609820501, 0),
(469, 341, '软件包安装页面', 'PackageInstall', 'Index', '', 11, 0, '', 1613967513, 0),
(471, 341, '软件包安装', 'PackageInstall', 'Install', '', 12, 0, '', 1613976708, 0),
(472, 341, '软件包更新', 'PackageUpgrade', 'Upgrade', '', 13, 0, '', 1619068484, 0),
(473, 41, '应用商店帐号绑定', 'Index', 'StoreAccountsBind', '', 60, 0, '', 1619435558, 1704286906),
(474, 41, '系统更新检查', 'Index', 'InspectUpgrade', '', 61, 0, '', 1619435587, 0),
(475, 41, '系统更新确认', 'Index', 'InspectUpgradeConfirm', '', 62, 0, '', 1619435611, 0),
(476, 103, '首页布局管理', 'Layout', 'LayoutIndexHomeSave', '', 3, 0, '', 1624341630, 0),
(477, 222, '页面设计', 'Design', 'Index', '', 110, 1, '', 1624519062, 0),
(478, 477, '页面设计添加/编辑页面', 'Design', 'SaveInfo', '', 0, 0, '', 1624519103, 0),
(479, 477, '页面设计添加/编辑', 'Design', 'Save', '', 1, 0, '', 1624519129, 0),
(480, 477, '页面设计状态更新', 'Design', 'StatusUpdate', '', 2, 0, '', 1624519162, 0),
(481, 477, '页面设计删除', 'Design', 'Delete', '', 6, 0, '', 1624519183, 0),
(482, 41, '首页统计数据', 'Index', 'Stats', '', 63, 0, '', 1630395987, 0),
(483, 41, '首页统计数据（收入统计）', 'Index', 'Income', '', 64, 0, '', 1558022648, 0),
(484, 477, '页面设计导入', 'Design', 'Upload', '', 3, 0, '', 1650331094, 0),
(485, 477, '页面设计同步首页', 'Design', 'Sync', '', 5, 0, '', 1650353616, 0),
(486, 477, '页面设计下载', 'Design', 'Download', '', 4, 0, '', 1650538022, 0),
(487, 38, '商品购物车', 'GoodsCart', 'Index', '', 70, 1, '', 1661314317, 0),
(488, 487, '商品购物车删除', 'GoodsCart', 'Delete', '', 71, 0, '', 1661314473, 0),
(489, 487, '商品购物车详情', 'GoodsCart', 'Detail', '', 72, 0, '', 1661314498, 0),
(490, 38, '商品规格', 'GoodsSpecTemplate', 'Index', '', 30, 1, '', 1533112443, 0),
(491, 490, '商品规格删除', 'GoodsSpecTemplate', 'Delete', '', 31, 0, '', 1533119680, 0),
(492, 490, '商品规格状态更新', 'GoodsSpecTemplate', 'StatusUpdate', '', 32, 0, '', 1533119704, 0),
(493, 490, '商品规格添加/编辑页面', 'GoodsSpecTemplate', 'SaveInfo', '', 33, 0, '', 1553964318, 0),
(494, 490, '商品规格添加/编辑', 'GoodsSpecTemplate', 'Save', '', 34, 0, '', 1553964354, 0),
(495, 490, '商品规格详情', 'GoodsSpecTemplate', 'Detail', '', 35, 0, '', 1553964354, 0),
(496, 39, '获取商品基础模板', 'Goods', 'BaseTemplate', '', 6, 0, '', 1553964354, 0),
(497, 153, '获取地区编号数据', 'Region', 'CodeData', '', 3, 0, '', 1553964354, 1704335391),
(498, 340, '应用分类', 'PluginsCategory', 'Index', '', 20, 0, '', 1553964354, 0),
(499, 498, '应用分类添加/编辑', 'PluginsCategory', 'Save', '', 21, 0, '', 1553964354, 0),
(500, 498, '应用分类删除', 'PluginsCategory', 'Delete', '', 23, 0, '', 1553964354, 1704335463),
(501, 341, '上传到商店页面', 'PluginsAdmin', 'StoreUploadInfo', '', 23, 0, '', 1553964354, 0),
(502, 341, '上传到商店处理', 'PluginsAdmin', 'StoreUpload', '', 24, 0, '', 1553964354, 0),
(503, 182, '短信日志', 'SmsLog', 'Index', '', 50, 1, '', 1661314317, 0),
(504, 503, '短信日志删除', 'SmsLog', 'Delete', '', 2, 0, '', 1661314473, 0),
(505, 503, '短信日志清空', 'SmsLog', 'AllDelete', '', 1, 0, '', 1661314473, 0),
(506, 503, '短信日志详情', 'SmsLog', 'Detail', '', 0, 0, '', 1661314498, 0),
(510, 13, '权限状态更新', 'Power', 'StatusUpdate', '', 32, 0, '', 1704334329, 1704334364),
(511, 201, '商品分类状态更新', 'GoodsCategory', 'StatusUpdate', '', 12, 0, '', 1704335339, 0),
(512, 153, '地区状态更新', 'Region', 'StatusUpdate', '', 1, 0, '', 1704335431, 0),
(513, 498, '应用状态更新', 'PluginsCategory', 'StatusUpdate', '', 22, 0, '', 1704335491, 1704335502),
(514, 41, '常用功能', 'Shortcutmenu', 'Index', '', 2, 0, '', 1704357796, 1704357829),
(515, 514, '常用功能保存', 'Shortcutmenu', 'Save', '', 0, 0, '', 1704357886, 1704357917),
(516, 514, '常用功能排序', 'Shortcutmenu', 'Sort', '', 1, 0, '', 1704357912, 1704360847),
(517, 514, '常用功能删除', 'Shortcutmenu', 'Delete', '', 2, 0, '', 1704360870, 0),
(518, 182, '邮件日志', 'EmailLog', 'Index', '', 60, 1, '', 1661314317, 0),
(519, 518, '邮件日志删除', 'EmailLog', 'Delete', '', 2, 0, '', 1661314473, 0),
(520, 518, '邮件日志清空', 'EmailLog', 'AllDelete', '', 1, 0, '', 1661314473, 0),
(521, 518, '邮件日志详情', 'EmailLog', 'Detail', '', 0, 0, '', 1661314498, 0),
(524, 178, '订单发货页面', 'Order', 'DeliveryInfo', '', 0, 0, '', 1553964354, 0),
(525, 222, '主题数据', 'ThemeData', 'Index', '', 40, 1, '', 1494381693, 1711549593),
(526, 525, '主题数据添加/编辑页面', 'ThemeData', 'SaveInfo', '', 1, 0, '', 1494410699, 1711549612),
(527, 525, '主题数据添加/编辑', 'ThemeData', 'Save', '', 2, 0, '', 1494410699, 1711549612),
(528, 525, '主题数据上传', 'ThemeData', 'Upload', '', 6, 0, '', 1494405096, 1711549604),
(529, 525, '主题数据删除', 'ThemeData', 'Delete', '', 5, 0, '', 1494410655, 1711549608),
(530, 525, '主题数据下载', 'ThemeData', 'Download', '', 7, 0, '', 1494410699, 1711549612),
(531, 525, '主题数据详情', 'ThemeData', 'Detail', '', 0, 0, '', 1494410699, 1711549612),
(532, 525, '主题数据商品搜索', 'ThemeData', 'GoodsSearch', '', 8, 0, '', 1494410699, 1711549612),
(533, 525, '主题数据文章搜索', 'ThemeData', 'ArticleSearch', '', 9, 0, '', 1494410699, 1711549612),
(534, 525, '主题数据状态更新', 'ThemeData', 'StatusUpdate', '', 4, 0, '', 1494410699, 1711549612);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_quick_nav`
--

DROP TABLE IF EXISTS `sxo_quick_nav`;
CREATE TABLE `sxo_quick_nav` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `platform` char(255) NOT NULL DEFAULT 'pc' COMMENT '所属平台（pc PC网站, h5 H5手机网站, ios 苹果APP, android 安卓APP, alipay 支付宝小程序, weixin 微信小程序, baidu 百度小程序, toutiao 头条小程序, qq QQ小程序, kuaishou 快手小程序）',
  `event_type` tinyint(4) NOT NULL DEFAULT -1 COMMENT '事件类型（0 WEB页面, 1 内部页面(小程序或APP内部地址), 2 外部小程序(同一个主体下的小程序appid), 3 打开地图, 4 拨打电话）',
  `event_value` char(255) NOT NULL DEFAULT '' COMMENT '事件值',
  `images_url` char(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `bg_color` char(30) NOT NULL DEFAULT '' COMMENT 'css背景色值',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='快捷导航';

--
-- 转存表中的数据 `sxo_quick_nav`
--

INSERT INTO `sxo_quick_nav` (`id`, `platform`, `event_type`, `event_value`, `images_url`, `name`, `is_enable`, `bg_color`, `sort`, `add_time`, `upd_time`) VALUES
(1, '[\"pc\"]', 0, 'index.php?s=category/index.html', '/static/upload/images/quick_nav/2020/09/11/1599806728463641.png', '分类', 1, '#FF9933', 0, 1599806738, 1690030856),
(2, '[\"pc\"]', 4, '17688888888', '/static/upload/images/quick_nav/2020/09/17/1600322667732829.png', '电话', 1, '#FF00CC', 0, 1599807003, 1600322701),
(3, '[\"pc\"]', 0, 'index.php?s=order/index.html', '/static/upload/images/quick_nav/2020/09/11/1599808001838784.png', '订单', 1, '#996633', 0, 1599808005, 1644162397),
(4, '[\"pc\"]', 3, 'ShopXO|上海浦东新区张江高科技园区XXX号|121.633055|31.21412', '/static/upload/images/quick_nav/2020/09/17/1600321639662998.png', '地图', 0, '#0066FF', 0, 1599808052, 1627539990),
(9, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/goods-category/goods-category', '/static/upload/images/quick_nav/2020/09/11/1599806728463641.png', '分类', 1, '#FF9933', 0, 1599806738, 1599810379),
(10, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 4, '17688888888', '/static/upload/images/quick_nav/2020/09/17/1600322667732829.png', '电话', 1, '#FF00CC', 0, 1599807003, 1600076541),
(11, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/user-order/user-order', '/static/upload/images/quick_nav/2020/09/11/1599808001838784.png', '订单', 1, '#996633', 0, 1599808005, 1599808064),
(12, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 3, 'ShopXO|上海浦东新区张江高科技园区XXX号|121.633055|31.21412', '/static/upload/images/quick_nav/2020/09/17/1600321639662998.png', '地图', 1, '#0066FF', 0, 1599808052, 1600321670);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_refund_log`
--

DROP TABLE IF EXISTS `sxo_refund_log`;
CREATE TABLE `sxo_refund_log` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '退款日志id',
  `pay_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `business_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '业务订单id',
  `business_type` char(180) NOT NULL DEFAULT '' COMMENT '业务类型，字符串（如：订单、钱包充值、会员购买、等...）',
  `trade_no` char(100) NOT NULL DEFAULT '' COMMENT '支付平台交易号',
  `buyer_user` char(60) NOT NULL DEFAULT '' COMMENT '支付平台用户帐号',
  `refund_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '退款金额',
  `pay_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '订单实际支付金额',
  `msg` text DEFAULT NULL COMMENT '描述',
  `payment` char(60) NOT NULL DEFAULT '' COMMENT '支付方式标记',
  `payment_name` char(60) NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `refundment` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '退款类型（0原路退回, 1退至钱包, 2手动处理）',
  `return_params` text DEFAULT NULL COMMENT '支付平台返回参数（以json存储）',
  `add_time` int(10) UNSIGNED NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='退款日志';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_region`
--

DROP TABLE IF EXISTS `sxo_region`;
CREATE TABLE `sxo_region` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '级别类型（1:一级[所有省], 2：二级[所有市], 3:三级[所有区县], 4:街道[所有街道]）',
  `letters` char(3) NOT NULL DEFAULT '' COMMENT '城市首字母',
  `code` char(30) NOT NULL DEFAULT '' COMMENT '编码',
  `lng` decimal(13,10) NOT NULL DEFAULT 0.0000000000 COMMENT '经度',
  `lat` decimal(13,10) NOT NULL DEFAULT 0.0000000000 COMMENT '纬度',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='地区';

--
-- 转存表中的数据 `sxo_region`
--

INSERT INTO `sxo_region` (`id`, `pid`, `name`, `level`, `letters`, `code`, `lng`, `lat`, `sort`, `is_enable`, `add_time`, `upd_time`) VALUES
(1, 0, '北京市', 1, '', '001', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 1678676868),
(2, 0, '天津市', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 1690031025),
(3, 0, '河北省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(4, 0, '山西省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(5, 0, '内蒙古自治区', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(6, 0, '辽宁省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(7, 0, '吉林省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(8, 0, '黑龙江省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(9, 0, '上海市', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(10, 0, '江苏省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(11, 0, '浙江省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(12, 0, '安徽省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(13, 0, '福建省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(14, 0, '江西省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(15, 0, '山东省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(16, 0, '河南省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(17, 0, '湖北省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(18, 0, '湖南省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(19, 0, '广东省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(20, 0, '广西壮族自治区', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(21, 0, '海南省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(22, 0, '重庆市', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(23, 0, '四川省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(24, 0, '贵州省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(25, 0, '云南省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(26, 0, '西藏自治区', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(27, 0, '陕西省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(28, 0, '甘肃省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(29, 0, '青海省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(30, 0, '宁夏回族自治区', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(31, 0, '新疆维吾尔自治区', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(32, 0, '台湾省', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(33, 0, '香港特别行政区', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(34, 0, '澳门特别行政区', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(35, 0, '海外', 1, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(36, 1, '北京市', 2, '', '002', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 1678676893),
(37, 2, '天津市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(38, 3, '石家庄市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(39, 3, '唐山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(40, 3, '秦皇岛市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(41, 3, '邯郸市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(42, 3, '邢台市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(43, 3, '保定市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(44, 3, '张家口市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(45, 3, '承德市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(46, 3, '沧州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(47, 3, '廊坊市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(48, 3, '衡水市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(49, 4, '太原市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(50, 4, '大同市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(51, 4, '阳泉市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(52, 4, '长治市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(53, 4, '晋城市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(54, 4, '朔州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(55, 4, '晋中市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(56, 4, '运城市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(57, 4, '忻州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(58, 4, '临汾市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(59, 4, '吕梁市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(60, 5, '呼和浩特市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(61, 5, '包头市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(62, 5, '乌海市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(63, 5, '赤峰市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(64, 5, '通辽市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(65, 5, '鄂尔多斯市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(66, 5, '呼伦贝尔市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(67, 5, '巴彦淖尔市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(68, 5, '乌兰察布市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(69, 5, '兴安盟', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(70, 5, '锡林郭勒盟', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(71, 5, '阿拉善盟', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(72, 6, '沈阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(73, 6, '大连市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(74, 6, '鞍山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(75, 6, '抚顺市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(76, 6, '本溪市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(77, 6, '丹东市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(78, 6, '锦州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(79, 6, '营口市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(80, 6, '阜新市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(81, 6, '辽阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(82, 6, '盘锦市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(83, 6, '铁岭市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(84, 6, '朝阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(85, 6, '葫芦岛市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(86, 7, '长春市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(87, 7, '吉林市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(88, 7, '四平市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(89, 7, '辽源市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(90, 7, '通化市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(91, 7, '白山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(92, 7, '松原市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(93, 7, '白城市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(94, 7, '延边朝鲜族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(95, 8, '哈尔滨市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(96, 8, '齐齐哈尔市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(97, 8, '鸡西市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(98, 8, '鹤岗市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(99, 8, '双鸭山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(100, 8, '大庆市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(101, 8, '伊春市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(102, 8, '佳木斯市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(103, 8, '七台河市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(104, 8, '牡丹江市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(105, 8, '黑河市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(106, 8, '绥化市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(107, 8, '大兴安岭地区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(108, 9, '上海市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(109, 10, '南京市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(110, 10, '无锡市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(111, 10, '徐州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(112, 10, '常州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(113, 10, '苏州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(114, 10, '南通市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(115, 10, '连云港市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(116, 10, '淮安市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(117, 10, '盐城市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(118, 10, '扬州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(119, 10, '镇江市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(120, 10, '泰州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(121, 10, '宿迁市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(122, 11, '杭州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(123, 11, '宁波市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(124, 11, '温州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(125, 11, '嘉兴市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(126, 11, '湖州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(127, 11, '绍兴市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(128, 11, '金华市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(129, 11, '衢州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(130, 11, '舟山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(131, 11, '台州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(132, 11, '丽水市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(133, 12, '合肥市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(134, 12, '芜湖市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(135, 12, '蚌埠市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(136, 12, '淮南市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(137, 12, '马鞍山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(138, 12, '淮北市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(139, 12, '铜陵市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(140, 12, '安庆市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(141, 12, '黄山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(142, 12, '滁州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(143, 12, '阜阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(144, 12, '宿州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(145, 12, '六安市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(146, 12, '亳州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(147, 12, '池州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(148, 12, '宣城市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(149, 13, '福州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(150, 13, '厦门市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(151, 13, '莆田市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(152, 13, '三明市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(153, 13, '泉州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(154, 13, '漳州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(155, 13, '南平市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(156, 13, '龙岩市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(157, 13, '宁德市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(158, 14, '南昌市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(159, 14, '景德镇市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(160, 14, '萍乡市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(161, 14, '九江市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(162, 14, '新余市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(163, 14, '鹰潭市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(164, 14, '赣州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(165, 14, '吉安市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(166, 14, '宜春市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(167, 14, '抚州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(168, 14, '上饶市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(169, 15, '济南市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(170, 15, '青岛市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(171, 15, '淄博市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(172, 15, '枣庄市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(173, 15, '东营市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(174, 15, '烟台市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(175, 15, '潍坊市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(176, 15, '济宁市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(177, 15, '泰安市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(178, 15, '威海市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(179, 15, '日照市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(180, 15, '临沂市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(181, 15, '德州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(182, 15, '聊城市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(183, 15, '滨州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(184, 15, '菏泽市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(185, 16, '郑州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(186, 16, '开封市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(187, 16, '洛阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(188, 16, '平顶山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(189, 16, '安阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(190, 16, '鹤壁市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(191, 16, '新乡市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(192, 16, '焦作市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(193, 16, '濮阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(194, 16, '许昌市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(195, 16, '漯河市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(196, 16, '三门峡市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(197, 16, '南阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(198, 16, '商丘市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(199, 16, '信阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(200, 16, '周口市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(201, 16, '驻马店市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(202, 16, '省直辖县级行政区划', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(203, 17, '武汉市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(204, 17, '黄石市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(205, 17, '十堰市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(206, 17, '宜昌市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(207, 17, '襄阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(208, 17, '鄂州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(209, 17, '荆门市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(210, 17, '孝感市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(211, 17, '荆州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(212, 17, '黄冈市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(213, 17, '咸宁市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(214, 17, '随州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(215, 17, '恩施土家族苗族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(216, 17, '省直辖县级行政区划', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(217, 18, '长沙市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(218, 18, '株洲市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(219, 18, '湘潭市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(220, 18, '衡阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(221, 18, '邵阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(222, 18, '岳阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(223, 18, '常德市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(224, 18, '张家界市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(225, 18, '益阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(226, 18, '郴州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(227, 18, '永州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(228, 18, '怀化市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(229, 18, '娄底市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(230, 18, '湘西土家族苗族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(231, 19, '广州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(232, 19, '韶关市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(233, 19, '深圳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(234, 19, '珠海市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(235, 19, '汕头市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(236, 19, '佛山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(237, 19, '江门市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(238, 19, '湛江市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(239, 19, '茂名市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(240, 19, '肇庆市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(241, 19, '惠州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(242, 19, '梅州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(243, 19, '汕尾市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(244, 19, '河源市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(245, 19, '阳江市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(246, 19, '清远市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(247, 19, '东莞市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(248, 19, '中山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(249, 19, '潮州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(250, 19, '揭阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(251, 19, '云浮市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(252, 20, '南宁市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(253, 20, '柳州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(254, 20, '桂林市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(255, 20, '梧州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(256, 20, '北海市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(257, 20, '防城港市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(258, 20, '钦州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(259, 20, '贵港市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(260, 20, '玉林市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(261, 20, '百色市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(262, 20, '贺州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(263, 20, '河池市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(264, 20, '来宾市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(265, 20, '崇左市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(266, 21, '海口市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(267, 21, '三亚市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(268, 21, '三沙市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(269, 21, '儋州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(270, 21, '省直辖县级行政区划', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(271, 22, '重庆市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(272, 22, '县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(273, 23, '成都市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(274, 23, '自贡市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(275, 23, '攀枝花市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(276, 23, '泸州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(277, 23, '德阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(278, 23, '绵阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(279, 23, '广元市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(280, 23, '遂宁市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(281, 23, '内江市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(282, 23, '乐山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(283, 23, '南充市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(284, 23, '眉山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(285, 23, '宜宾市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(286, 23, '广安市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(287, 23, '达州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(288, 23, '雅安市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(289, 23, '巴中市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(290, 23, '资阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(291, 23, '阿坝藏族羌族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(292, 23, '甘孜藏族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(293, 23, '凉山彝族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(294, 24, '贵阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(295, 24, '六盘水市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(296, 24, '遵义市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(297, 24, '安顺市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(298, 24, '毕节市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(299, 24, '铜仁市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(300, 24, '黔西南布依族苗族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(301, 24, '黔东南苗族侗族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(302, 24, '黔南布依族苗族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(303, 25, '昆明市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(304, 25, '曲靖市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(305, 25, '玉溪市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(306, 25, '保山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(307, 25, '昭通市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(308, 25, '丽江市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(309, 25, '普洱市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(310, 25, '临沧市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(311, 25, '楚雄彝族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(312, 25, '红河哈尼族彝族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(313, 25, '文山壮族苗族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(314, 25, '西双版纳傣族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(315, 25, '大理白族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(316, 25, '德宏傣族景颇族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(317, 25, '怒江傈僳族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(318, 25, '迪庆藏族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(319, 26, '拉萨市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(320, 26, '日喀则市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(321, 26, '昌都市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(322, 26, '林芝市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(323, 26, '山南市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(324, 26, '那曲市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(325, 26, '阿里地区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(326, 27, '西安市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(327, 27, '铜川市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(328, 27, '宝鸡市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(329, 27, '咸阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(330, 27, '渭南市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(331, 27, '延安市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(332, 27, '汉中市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(333, 27, '榆林市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(334, 27, '安康市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(335, 27, '商洛市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(336, 28, '兰州市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(337, 28, '嘉峪关市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(338, 28, '金昌市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(339, 28, '白银市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(340, 28, '天水市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(341, 28, '武威市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(342, 28, '张掖市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(343, 28, '平凉市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(344, 28, '酒泉市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(345, 28, '庆阳市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(346, 28, '定西市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(347, 28, '陇南市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(348, 28, '临夏回族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(349, 28, '甘南藏族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(350, 29, '西宁市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(351, 29, '海东市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(352, 29, '海北藏族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(353, 29, '黄南藏族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(354, 29, '海南藏族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(355, 29, '果洛藏族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(356, 29, '玉树藏族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(357, 29, '海西蒙古族藏族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(358, 30, '银川市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(359, 30, '石嘴山市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(360, 30, '吴忠市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(361, 30, '固原市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(362, 30, '中卫市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(363, 31, '乌鲁木齐市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(364, 31, '克拉玛依市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(365, 31, '吐鲁番市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(366, 31, '哈密市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(367, 31, '昌吉回族自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(368, 31, '博尔塔拉蒙古自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(369, 31, '巴音郭楞蒙古自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(370, 31, '阿克苏地区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(371, 31, '克孜勒苏柯尔克孜自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(372, 31, '喀什地区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(373, 31, '和田地区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(374, 31, '伊犁哈萨克自治州', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(375, 31, '塔城地区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(376, 31, '阿勒泰地区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(377, 31, '自治区直辖县级行政区划', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(378, 32, '台北市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(379, 32, '高雄市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(380, 32, '基隆市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(381, 32, '台中市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(382, 32, '台南市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(383, 32, '新竹市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(384, 32, '嘉义市', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(385, 32, '台北县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(386, 32, '宜兰县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(387, 32, '桃园县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(388, 32, '新竹县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(389, 32, '苗栗县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(390, 32, '台中县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(391, 32, '彰化县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(392, 32, '南投县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(393, 32, '云林县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(394, 32, '嘉义县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(395, 32, '台南县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(396, 32, '高雄县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(397, 32, '屏东县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(398, 32, '澎湖县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(399, 32, '台东县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(400, 32, '花莲县', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(401, 33, '中西区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(402, 33, '东区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(403, 33, '九龙城区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(404, 33, '观塘区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(405, 33, '南区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(406, 33, '深水埗区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(407, 33, '黄大仙区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(408, 33, '湾仔区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(409, 33, '油尖旺区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(410, 33, '离岛区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(411, 33, '葵青区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(412, 33, '北区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(413, 33, '西贡区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(414, 33, '沙田区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(415, 33, '屯门区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(416, 33, '大埔区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(417, 33, '荃湾区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(418, 33, '元朗区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(419, 34, '花地玛堂区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(420, 34, '圣安多尼堂区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(421, 34, '大堂区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(422, 34, '望德堂区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(423, 34, '风顺堂区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(424, 34, '嘉模堂区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(425, 34, '圣方济各堂区', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(426, 35, '美国', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(427, 35, '加拿大', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(428, 35, '澳大利亚', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(429, 35, '新西兰', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(430, 35, '英国', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(431, 35, '法国', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(432, 35, '德国', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(433, 35, '捷克', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(434, 35, '荷兰', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(435, 35, '瑞士', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(436, 35, '希腊', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(437, 35, '挪威', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(438, 35, '瑞典', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(439, 35, '丹麦', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(440, 35, '芬兰', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(441, 35, '爱尔兰', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(442, 35, '奥地利', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(443, 35, '意大利', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(444, 35, '乌克兰', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(445, 35, '俄罗斯', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(446, 35, '西班牙', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(447, 35, '韩国', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(448, 35, '新加坡', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(449, 35, '马来西亚', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(450, 35, '印度', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(451, 35, '泰国', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(452, 35, '日本', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(453, 35, '巴西', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(454, 35, '阿根廷', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(455, 35, '南非', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(456, 35, '埃及', 2, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(457, 36, '东城区', 3, '', '003', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 1678676992),
(458, 36, '西城区', 3, '', '004', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 1678676997),
(459, 36, '朝阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(460, 36, '丰台区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(461, 36, '石景山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(462, 36, '海淀区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(463, 36, '门头沟区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(464, 36, '房山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(465, 36, '通州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(466, 36, '顺义区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(467, 36, '昌平区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(468, 36, '大兴区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(469, 36, '怀柔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(470, 36, '平谷区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(471, 36, '密云区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(472, 36, '延庆区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(473, 37, '和平区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(474, 37, '河东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(475, 37, '河西区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(476, 37, '南开区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(477, 37, '河北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(478, 37, '红桥区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(479, 37, '东丽区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(480, 37, '西青区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(481, 37, '津南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(482, 37, '北辰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(483, 37, '武清区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(484, 37, '宝坻区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(485, 37, '滨海新区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559939, 0),
(486, 37, '宁河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(487, 37, '静海区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(488, 37, '蓟州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(489, 38, '长安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(490, 38, '桥西区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(491, 38, '新华区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(492, 38, '井陉矿区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(493, 38, '裕华区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(494, 38, '藁城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(495, 38, '鹿泉区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(496, 38, '栾城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(497, 38, '井陉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(498, 38, '正定县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(499, 38, '行唐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(500, 38, '灵寿县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(501, 38, '高邑县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(502, 38, '深泽县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(503, 38, '赞皇县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(504, 38, '无极县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(505, 38, '平山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(506, 38, '元氏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(507, 38, '赵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(508, 38, '石家庄高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(509, 38, '石家庄循环化工园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(510, 38, '辛集市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(511, 38, '晋州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(512, 38, '新乐市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(513, 39, '路南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(514, 39, '路北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(515, 39, '古冶区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(516, 39, '开平区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(517, 39, '丰南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(518, 39, '丰润区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(519, 39, '曹妃甸区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(520, 39, '滦南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(521, 39, '乐亭县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(522, 39, '迁西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(523, 39, '玉田县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(524, 39, '河北唐山芦台经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(525, 39, '唐山市汉沽管理区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(526, 39, '唐山高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(527, 39, '河北唐山海港经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(528, 39, '遵化市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(529, 39, '迁安市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(530, 39, '滦州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(531, 40, '海港区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(532, 40, '山海关区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(533, 40, '北戴河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(534, 40, '抚宁区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(535, 40, '青龙满族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(536, 40, '昌黎县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(537, 40, '卢龙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(538, 40, '秦皇岛市经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(539, 40, '北戴河新区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(540, 41, '邯山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(541, 41, '丛台区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(542, 41, '复兴区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(543, 41, '峰峰矿区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(544, 41, '肥乡区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(545, 41, '永年区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(546, 41, '临漳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(547, 41, '成安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(548, 41, '大名县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(549, 41, '涉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(550, 41, '磁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(551, 41, '邱县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(552, 41, '鸡泽县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(553, 41, '广平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(554, 41, '馆陶县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(555, 41, '魏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(556, 41, '曲周县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(557, 41, '邯郸经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(558, 41, '邯郸冀南新区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(559, 41, '武安市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(560, 42, '襄都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(561, 42, '信都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(562, 42, '任泽区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(563, 42, '南和区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(564, 42, '临城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(565, 42, '内丘县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(566, 42, '柏乡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(567, 42, '隆尧县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(568, 42, '宁晋县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(569, 42, '巨鹿县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(570, 42, '新河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(571, 42, '广宗县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(572, 42, '平乡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(573, 42, '威县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(574, 42, '清河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(575, 42, '临西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(576, 42, '河北邢台经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(577, 42, '南宫市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(578, 42, '沙河市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(579, 43, '竞秀区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(580, 43, '莲池区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(581, 43, '满城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(582, 43, '清苑区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(583, 43, '徐水区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(584, 43, '涞水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(585, 43, '阜平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(586, 43, '定兴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(587, 43, '唐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(588, 43, '高阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(589, 43, '容城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(590, 43, '涞源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(591, 43, '望都县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(592, 43, '安新县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(593, 43, '易县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(594, 43, '曲阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(595, 43, '蠡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(596, 43, '顺平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(597, 43, '博野县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(598, 43, '雄县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(599, 43, '保定高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(600, 43, '保定白沟新城', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(601, 43, '涿州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(602, 43, '定州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(603, 43, '安国市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(604, 43, '高碑店市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(605, 44, '桥东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(606, 44, '桥西区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(607, 44, '宣化区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(608, 44, '下花园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(609, 44, '万全区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(610, 44, '崇礼区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(611, 44, '张北县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(612, 44, '康保县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(613, 44, '沽源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(614, 44, '尚义县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(615, 44, '蔚县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(616, 44, '阳原县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(617, 44, '怀安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(618, 44, '怀来县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(619, 44, '涿鹿县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(620, 44, '赤城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(621, 44, '张家口经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(622, 44, '张家口市察北管理区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(623, 44, '张家口市塞北管理区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(624, 45, '双桥区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(625, 45, '双滦区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(626, 45, '鹰手营子矿区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(627, 45, '承德县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(628, 45, '兴隆县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(629, 45, '滦平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(630, 45, '隆化县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(631, 45, '丰宁满族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(632, 45, '宽城满族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(633, 45, '围场满族蒙古族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(634, 45, '承德高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(635, 45, '平泉市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(636, 46, '新华区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(637, 46, '运河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(638, 46, '沧县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(639, 46, '青县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(640, 46, '东光县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(641, 46, '海兴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(642, 46, '盐山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(643, 46, '肃宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(644, 46, '南皮县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(645, 46, '吴桥县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(646, 46, '献县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(647, 46, '孟村回族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(648, 46, '河北沧州经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(649, 46, '沧州高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(650, 46, '沧州渤海新区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(651, 46, '泊头市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(652, 46, '任丘市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0);
INSERT INTO `sxo_region` (`id`, `pid`, `name`, `level`, `letters`, `code`, `lng`, `lat`, `sort`, `is_enable`, `add_time`, `upd_time`) VALUES
(653, 46, '黄骅市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(654, 46, '河间市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(655, 47, '安次区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(656, 47, '广阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(657, 47, '固安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(658, 47, '永清县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(659, 47, '香河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(660, 47, '大城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(661, 47, '文安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(662, 47, '大厂回族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(663, 47, '廊坊经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(664, 47, '霸州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(665, 47, '三河市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(666, 48, '桃城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(667, 48, '冀州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(668, 48, '枣强县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(669, 48, '武邑县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(670, 48, '武强县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(671, 48, '饶阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(672, 48, '安平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(673, 48, '故城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(674, 48, '景县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(675, 48, '阜城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(676, 48, '河北衡水高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(677, 48, '衡水滨湖新区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(678, 48, '深州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(679, 49, '小店区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(680, 49, '迎泽区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(681, 49, '杏花岭区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(682, 49, '尖草坪区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(683, 49, '万柏林区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(684, 49, '晋源区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(685, 49, '清徐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(686, 49, '阳曲县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(687, 49, '娄烦县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(688, 49, '山西转型综合改革示范区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(689, 49, '古交市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(690, 50, '新荣区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(691, 50, '平城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(692, 50, '云冈区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(693, 50, '云州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(694, 50, '阳高县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(695, 50, '天镇县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(696, 50, '广灵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(697, 50, '灵丘县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(698, 50, '浑源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(699, 50, '左云县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(700, 50, '山西大同经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(701, 51, '城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(702, 51, '矿区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(703, 51, '郊区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(704, 51, '平定县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(705, 51, '盂县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(706, 52, '潞州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(707, 52, '上党区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(708, 52, '屯留区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(709, 52, '潞城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(710, 52, '襄垣县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(711, 52, '平顺县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(712, 52, '黎城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(713, 52, '壶关县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(714, 52, '长子县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(715, 52, '武乡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(716, 52, '沁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(717, 52, '沁源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(718, 52, '山西长治高新技术产业园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(719, 53, '城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(720, 53, '沁水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(721, 53, '阳城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(722, 53, '陵川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(723, 53, '泽州县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(724, 53, '高平市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(725, 54, '朔城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(726, 54, '平鲁区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(727, 54, '山阴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(728, 54, '应县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(729, 54, '右玉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(730, 54, '山西朔州经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(731, 54, '怀仁市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(732, 55, '榆次区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(733, 55, '太谷区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(734, 55, '榆社县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(735, 55, '左权县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(736, 55, '和顺县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(737, 55, '昔阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(738, 55, '寿阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(739, 55, '祁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(740, 55, '平遥县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(741, 55, '灵石县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(742, 55, '介休市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(743, 56, '盐湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(744, 56, '临猗县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(745, 56, '万荣县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(746, 56, '闻喜县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(747, 56, '稷山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(748, 56, '新绛县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(749, 56, '绛县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(750, 56, '垣曲县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(751, 56, '夏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(752, 56, '平陆县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(753, 56, '芮城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(754, 56, '永济市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(755, 56, '河津市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(756, 57, '忻府区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(757, 57, '定襄县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(758, 57, '五台县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(759, 57, '代县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(760, 57, '繁峙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(761, 57, '宁武县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(762, 57, '静乐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(763, 57, '神池县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(764, 57, '五寨县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(765, 57, '岢岚县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(766, 57, '河曲县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(767, 57, '保德县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(768, 57, '偏关县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(769, 57, '五台山风景名胜区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(770, 57, '原平市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(771, 58, '尧都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(772, 58, '曲沃县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(773, 58, '翼城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(774, 58, '襄汾县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(775, 58, '洪洞县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(776, 58, '古县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(777, 58, '安泽县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(778, 58, '浮山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(779, 58, '吉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(780, 58, '乡宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(781, 58, '大宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(782, 58, '隰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(783, 58, '永和县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(784, 58, '蒲县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(785, 58, '汾西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(786, 58, '侯马市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(787, 58, '霍州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(788, 59, '离石区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(789, 59, '文水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(790, 59, '交城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(791, 59, '兴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(792, 59, '临县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(793, 59, '柳林县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(794, 59, '石楼县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(795, 59, '岚县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(796, 59, '方山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(797, 59, '中阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(798, 59, '交口县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(799, 59, '孝义市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(800, 59, '汾阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(801, 60, '新城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(802, 60, '回民区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(803, 60, '玉泉区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(804, 60, '赛罕区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(805, 60, '土默特左旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(806, 60, '托克托县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(807, 60, '和林格尔县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(808, 60, '清水河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(809, 60, '武川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(810, 60, '呼和浩特经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(811, 61, '东河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(812, 61, '昆都仑区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(813, 61, '青山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(814, 61, '石拐区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(815, 61, '白云鄂博矿区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(816, 61, '九原区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(817, 61, '土默特右旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(818, 61, '固阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(819, 61, '达尔罕茂明安联合旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(820, 61, '包头稀土高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(821, 62, '海勃湾区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(822, 62, '海南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(823, 62, '乌达区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(824, 63, '红山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(825, 63, '元宝山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(826, 63, '松山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(827, 63, '阿鲁科尔沁旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(828, 63, '巴林左旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(829, 63, '巴林右旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(830, 63, '林西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(831, 63, '克什克腾旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(832, 63, '翁牛特旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(833, 63, '喀喇沁旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(834, 63, '宁城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(835, 63, '敖汉旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(836, 64, '科尔沁区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(837, 64, '科尔沁左翼中旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(838, 64, '科尔沁左翼后旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(839, 64, '开鲁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(840, 64, '库伦旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(841, 64, '奈曼旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(842, 64, '扎鲁特旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(843, 64, '通辽经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(844, 64, '霍林郭勒市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(845, 65, '东胜区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(846, 65, '康巴什区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(847, 65, '达拉特旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(848, 65, '准格尔旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(849, 65, '鄂托克前旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(850, 65, '鄂托克旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(851, 65, '杭锦旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(852, 65, '乌审旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(853, 65, '伊金霍洛旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(854, 66, '海拉尔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(855, 66, '扎赉诺尔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(856, 66, '阿荣旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(857, 66, '莫力达瓦达斡尔族自治旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(858, 66, '鄂伦春自治旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(859, 66, '鄂温克族自治旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(860, 66, '陈巴尔虎旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(861, 66, '新巴尔虎左旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(862, 66, '新巴尔虎右旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(863, 66, '满洲里市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(864, 66, '牙克石市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(865, 66, '扎兰屯市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(866, 66, '额尔古纳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(867, 66, '根河市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(868, 67, '临河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(869, 67, '五原县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(870, 67, '磴口县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(871, 67, '乌拉特前旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(872, 67, '乌拉特中旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(873, 67, '乌拉特后旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(874, 67, '杭锦后旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(875, 68, '集宁区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(876, 68, '卓资县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(877, 68, '化德县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(878, 68, '商都县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(879, 68, '兴和县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(880, 68, '凉城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(881, 68, '察哈尔右翼前旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(882, 68, '察哈尔右翼中旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(883, 68, '察哈尔右翼后旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(884, 68, '四子王旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(885, 68, '丰镇市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(886, 69, '乌兰浩特市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(887, 69, '阿尔山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(888, 69, '科尔沁右翼前旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(889, 69, '科尔沁右翼中旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(890, 69, '扎赉特旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(891, 69, '突泉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(892, 70, '二连浩特市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(893, 70, '锡林浩特市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(894, 70, '阿巴嘎旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(895, 70, '苏尼特左旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(896, 70, '苏尼特右旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(897, 70, '东乌珠穆沁旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(898, 70, '西乌珠穆沁旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(899, 70, '太仆寺旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(900, 70, '镶黄旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(901, 70, '正镶白旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(902, 70, '正蓝旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(903, 70, '多伦县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(904, 70, '乌拉盖管委会', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(905, 71, '阿拉善左旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(906, 71, '阿拉善右旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(907, 71, '额济纳旗', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(908, 71, '内蒙古阿拉善高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(909, 72, '和平区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(910, 72, '沈河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(911, 72, '大东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(912, 72, '皇姑区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(913, 72, '铁西区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(914, 72, '苏家屯区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(915, 72, '浑南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(916, 72, '沈北新区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(917, 72, '于洪区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(918, 72, '辽中区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(919, 72, '康平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(920, 72, '法库县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(921, 72, '新民市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(922, 73, '中山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(923, 73, '西岗区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(924, 73, '沙河口区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(925, 73, '甘井子区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(926, 73, '旅顺口区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(927, 73, '金州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(928, 73, '普兰店区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(929, 73, '长海县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(930, 73, '瓦房店市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(931, 73, '庄河市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(932, 74, '铁东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(933, 74, '铁西区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(934, 74, '立山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(935, 74, '千山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(936, 74, '台安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(937, 74, '岫岩满族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(938, 74, '海城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(939, 75, '新抚区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(940, 75, '东洲区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(941, 75, '望花区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(942, 75, '顺城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(943, 75, '抚顺县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(944, 75, '新宾满族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(945, 75, '清原满族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(946, 76, '平山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(947, 76, '溪湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(948, 76, '明山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(949, 76, '南芬区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(950, 76, '本溪满族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(951, 76, '桓仁满族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(952, 77, '元宝区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(953, 77, '振兴区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(954, 77, '振安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(955, 77, '宽甸满族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(956, 77, '东港市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(957, 77, '凤城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(958, 78, '古塔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(959, 78, '凌河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(960, 78, '太和区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(961, 78, '黑山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(962, 78, '义县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(963, 78, '凌海市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(964, 78, '北镇市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(965, 79, '站前区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(966, 79, '西市区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(967, 79, '鲅鱼圈区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(968, 79, '老边区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(969, 79, '盖州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(970, 79, '大石桥市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(971, 80, '海州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(972, 80, '新邱区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(973, 80, '太平区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(974, 80, '清河门区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(975, 80, '细河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(976, 80, '阜新蒙古族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(977, 80, '彰武县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(978, 81, '白塔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(979, 81, '文圣区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(980, 81, '宏伟区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(981, 81, '弓长岭区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(982, 81, '太子河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(983, 81, '辽阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(984, 81, '灯塔市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(985, 82, '双台子区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(986, 82, '兴隆台区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(987, 82, '大洼区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(988, 82, '盘山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(989, 83, '银州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(990, 83, '清河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(991, 83, '铁岭县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(992, 83, '西丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(993, 83, '昌图县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(994, 83, '调兵山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(995, 83, '开原市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(996, 84, '双塔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(997, 84, '龙城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(998, 84, '朝阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(999, 84, '建平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1000, 84, '喀喇沁左翼蒙古族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1001, 84, '北票市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1002, 84, '凌源市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1003, 85, '连山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1004, 85, '龙港区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1005, 85, '南票区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1006, 85, '绥中县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1007, 85, '建昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1008, 85, '兴城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1009, 86, '南关区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1010, 86, '宽城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1011, 86, '朝阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1012, 86, '二道区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1013, 86, '绿园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1014, 86, '双阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1015, 86, '九台区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1016, 86, '农安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1017, 86, '长春经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1018, 86, '长春净月高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1019, 86, '长春高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1020, 86, '长春汽车经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1021, 86, '榆树市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1022, 86, '德惠市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1023, 86, '公主岭市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1024, 87, '昌邑区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1025, 87, '龙潭区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1026, 87, '船营区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1027, 87, '丰满区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1028, 87, '永吉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1029, 87, '吉林经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1030, 87, '吉林高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1031, 87, '吉林中国新加坡食品区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1032, 87, '蛟河市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1033, 87, '桦甸市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1034, 87, '舒兰市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1035, 87, '磐石市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1036, 88, '铁西区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1037, 88, '铁东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1038, 88, '梨树县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1039, 88, '伊通满族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1040, 88, '双辽市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1041, 89, '龙山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1042, 89, '西安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1043, 89, '东丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1044, 89, '东辽县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1045, 90, '东昌区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1046, 90, '二道江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1047, 90, '通化县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1048, 90, '辉南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1049, 90, '柳河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1050, 90, '梅河口市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1051, 90, '集安市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1052, 91, '浑江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1053, 91, '江源区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1054, 91, '抚松县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1055, 91, '靖宇县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1056, 91, '长白朝鲜族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1057, 91, '临江市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1058, 92, '宁江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1059, 92, '前郭尔罗斯蒙古族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1060, 92, '长岭县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1061, 92, '乾安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1062, 92, '吉林松原经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1063, 92, '扶余市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1064, 93, '洮北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1065, 93, '镇赉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1066, 93, '通榆县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1067, 93, '吉林白城经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1068, 93, '洮南市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1069, 93, '大安市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1070, 94, '延吉市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1071, 94, '图们市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1072, 94, '敦化市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1073, 94, '珲春市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1074, 94, '龙井市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1075, 94, '和龙市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1076, 94, '汪清县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1077, 94, '安图县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1078, 95, '道里区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1079, 95, '南岗区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1080, 95, '道外区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1081, 95, '平房区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1082, 95, '松北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1083, 95, '香坊区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1084, 95, '呼兰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1085, 95, '阿城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1086, 95, '双城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1087, 95, '依兰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1088, 95, '方正县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1089, 95, '宾县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1090, 95, '巴彦县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1091, 95, '木兰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1092, 95, '通河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1093, 95, '延寿县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1094, 95, '尚志市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1095, 95, '五常市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1096, 96, '龙沙区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1097, 96, '建华区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1098, 96, '铁锋区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1099, 96, '昂昂溪区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1100, 96, '富拉尔基区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1101, 96, '碾子山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1102, 96, '梅里斯达斡尔族区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1103, 96, '龙江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1104, 96, '依安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1105, 96, '泰来县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1106, 96, '甘南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1107, 96, '富裕县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1108, 96, '克山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1109, 96, '克东县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1110, 96, '拜泉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1111, 96, '讷河市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1112, 97, '鸡冠区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1113, 97, '恒山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1114, 97, '滴道区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1115, 97, '梨树区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1116, 97, '城子河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1117, 97, '麻山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1118, 97, '鸡东县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1119, 97, '虎林市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1120, 97, '密山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1121, 98, '向阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1122, 98, '工农区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1123, 98, '南山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1124, 98, '兴安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1125, 98, '东山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1126, 98, '兴山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1127, 98, '萝北县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1128, 98, '绥滨县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1129, 99, '尖山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1130, 99, '岭东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1131, 99, '四方台区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1132, 99, '宝山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1133, 99, '集贤县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1134, 99, '友谊县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1135, 99, '宝清县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1136, 99, '饶河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1137, 100, '萨尔图区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1138, 100, '龙凤区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1139, 100, '让胡路区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1140, 100, '红岗区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1141, 100, '大同区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1142, 100, '肇州县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1143, 100, '肇源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1144, 100, '林甸县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1145, 100, '杜尔伯特蒙古族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1146, 100, '大庆高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1147, 101, '伊美区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1148, 101, '乌翠区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1149, 101, '友好区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1150, 101, '嘉荫县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1151, 101, '汤旺县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1152, 101, '丰林县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1153, 101, '大箐山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1154, 101, '南岔县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1155, 101, '金林区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1156, 101, '铁力市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1157, 102, '向阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1158, 102, '前进区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1159, 102, '东风区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1160, 102, '郊区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1161, 102, '桦南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1162, 102, '桦川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1163, 102, '汤原县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1164, 102, '同江市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1165, 102, '富锦市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1166, 102, '抚远市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1167, 103, '新兴区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1168, 103, '桃山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1169, 103, '茄子河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1170, 103, '勃利县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1171, 104, '东安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1172, 104, '阳明区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1173, 104, '爱民区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1174, 104, '西安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1175, 104, '林口县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1176, 104, '牡丹江经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1177, 104, '绥芬河市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1178, 104, '海林市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1179, 104, '宁安市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1180, 104, '穆棱市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1181, 104, '东宁市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1182, 105, '爱辉区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1183, 105, '逊克县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1184, 105, '孙吴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1185, 105, '北安市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1186, 105, '五大连池市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1187, 105, '嫩江市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1188, 106, '北林区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1189, 106, '望奎县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1190, 106, '兰西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1191, 106, '青冈县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1192, 106, '庆安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1193, 106, '明水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1194, 106, '绥棱县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1195, 106, '安达市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1196, 106, '肇东市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1197, 106, '海伦市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1198, 107, '漠河市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1199, 107, '呼玛县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1200, 107, '塔河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1201, 107, '加格达奇区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1202, 107, '松岭区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1203, 107, '新林区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1204, 107, '呼中区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1205, 108, '黄浦区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1206, 108, '徐汇区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1207, 108, '长宁区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1208, 108, '静安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1209, 108, '普陀区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1210, 108, '虹口区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1211, 108, '杨浦区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1212, 108, '闵行区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1213, 108, '宝山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1214, 108, '嘉定区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1215, 108, '浦东新区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1216, 108, '金山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1217, 108, '松江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1218, 108, '青浦区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1219, 108, '奉贤区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1220, 108, '崇明区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1221, 109, '玄武区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1222, 109, '秦淮区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1223, 109, '建邺区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1224, 109, '鼓楼区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1225, 109, '浦口区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1226, 109, '栖霞区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1227, 109, '雨花台区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1228, 109, '江宁区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1229, 109, '六合区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1230, 109, '溧水区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1231, 109, '高淳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1232, 110, '锡山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1233, 110, '惠山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1234, 110, '滨湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1235, 110, '梁溪区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1236, 110, '新吴区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1237, 110, '江阴市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1238, 110, '宜兴市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1239, 111, '鼓楼区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1240, 111, '云龙区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1241, 111, '贾汪区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1242, 111, '泉山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1243, 111, '铜山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1244, 111, '丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1245, 111, '沛县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1246, 111, '睢宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1247, 111, '徐州经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1248, 111, '新沂市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1249, 111, '邳州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1250, 112, '天宁区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1251, 112, '钟楼区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1252, 112, '新北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1253, 112, '武进区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1254, 112, '金坛区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1255, 112, '溧阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1256, 113, '虎丘区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1257, 113, '吴中区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1258, 113, '相城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1259, 113, '姑苏区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1260, 113, '吴江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1261, 113, '苏州工业园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1262, 113, '常熟市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1263, 113, '张家港市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1264, 113, '昆山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1265, 113, '太仓市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1266, 114, '通州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1267, 114, '崇川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1268, 114, '海门区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1269, 114, '如东县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1270, 114, '南通经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1271, 114, '启东市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1272, 114, '如皋市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1273, 114, '海安市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1274, 115, '连云区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1275, 115, '海州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1276, 115, '赣榆区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1277, 115, '东海县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1278, 115, '灌云县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1279, 115, '灌南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1280, 115, '连云港经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1281, 115, '连云港高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1282, 116, '淮安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1283, 116, '淮阴区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1284, 116, '清江浦区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1285, 116, '洪泽区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1286, 116, '涟水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1287, 116, '盱眙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1288, 116, '金湖县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1289, 116, '淮安经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1290, 117, '亭湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1291, 117, '盐都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1292, 117, '大丰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1293, 117, '响水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1294, 117, '滨海县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1295, 117, '阜宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1296, 117, '射阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0);
INSERT INTO `sxo_region` (`id`, `pid`, `name`, `level`, `letters`, `code`, `lng`, `lat`, `sort`, `is_enable`, `add_time`, `upd_time`) VALUES
(1297, 117, '建湖县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1298, 117, '盐城经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1299, 117, '东台市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1300, 118, '广陵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1301, 118, '邗江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1302, 118, '江都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1303, 118, '宝应县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1304, 118, '扬州经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1305, 118, '仪征市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1306, 118, '高邮市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1307, 119, '京口区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1308, 119, '润州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1309, 119, '丹徒区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1310, 119, '镇江新区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1311, 119, '丹阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1312, 119, '扬中市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1313, 119, '句容市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1314, 120, '海陵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1315, 120, '高港区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1316, 120, '姜堰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1317, 120, '泰州医药高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1318, 120, '兴化市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1319, 120, '靖江市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1320, 120, '泰兴市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1321, 121, '宿城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1322, 121, '宿豫区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1323, 121, '沭阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1324, 121, '泗阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1325, 121, '泗洪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1326, 121, '宿迁经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1327, 122, '上城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1328, 122, '拱墅区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1329, 122, '西湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1330, 122, '滨江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1331, 122, '萧山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1332, 122, '余杭区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1333, 122, '富阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1334, 122, '临安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1335, 122, '临平区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1336, 122, '钱塘区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1337, 122, '桐庐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1338, 122, '淳安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1339, 122, '建德市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1340, 123, '海曙区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1341, 123, '江北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1342, 123, '北仑区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1343, 123, '镇海区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1344, 123, '鄞州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1345, 123, '奉化区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1346, 123, '象山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1347, 123, '宁海县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1348, 123, '余姚市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1349, 123, '慈溪市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1350, 124, '鹿城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1351, 124, '龙湾区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1352, 124, '瓯海区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1353, 124, '洞头区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1354, 124, '永嘉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1355, 124, '平阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1356, 124, '苍南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1357, 124, '文成县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1358, 124, '泰顺县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1359, 124, '温州经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1360, 124, '瑞安市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1361, 124, '乐清市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1362, 124, '龙港市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1363, 125, '南湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1364, 125, '秀洲区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1365, 125, '嘉善县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1366, 125, '海盐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1367, 125, '海宁市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1368, 125, '平湖市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1369, 125, '桐乡市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1370, 126, '吴兴区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1371, 126, '南浔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1372, 126, '德清县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1373, 126, '长兴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1374, 126, '安吉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1375, 127, '越城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1376, 127, '柯桥区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1377, 127, '上虞区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1378, 127, '新昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1379, 127, '诸暨市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1380, 127, '嵊州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1381, 128, '婺城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1382, 128, '金东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1383, 128, '武义县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1384, 128, '浦江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1385, 128, '磐安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1386, 128, '兰溪市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1387, 128, '义乌市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1388, 128, '东阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1389, 128, '永康市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1390, 129, '柯城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1391, 129, '衢江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1392, 129, '常山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1393, 129, '开化县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1394, 129, '龙游县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1395, 129, '江山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1396, 130, '定海区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1397, 130, '普陀区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1398, 130, '岱山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1399, 130, '嵊泗县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1400, 131, '椒江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1401, 131, '黄岩区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1402, 131, '路桥区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1403, 131, '三门县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1404, 131, '天台县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1405, 131, '仙居县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1406, 131, '温岭市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1407, 131, '临海市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1408, 131, '玉环市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1409, 132, '莲都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1410, 132, '青田县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1411, 132, '缙云县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1412, 132, '遂昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1413, 132, '松阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1414, 132, '云和县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1415, 132, '庆元县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1416, 132, '景宁畲族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1417, 132, '龙泉市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1418, 133, '瑶海区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1419, 133, '庐阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1420, 133, '蜀山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1421, 133, '包河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1422, 133, '长丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1423, 133, '肥东县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1424, 133, '肥西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1425, 133, '庐江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1426, 133, '合肥高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1427, 133, '合肥经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1428, 133, '合肥新站高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1429, 133, '巢湖市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1430, 134, '镜湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1431, 134, '鸠江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1432, 134, '弋江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1433, 134, '湾沚区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1434, 134, '繁昌区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1435, 134, '南陵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1436, 134, '芜湖经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1437, 134, '安徽芜湖三山经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1438, 134, '无为市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1439, 135, '龙子湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1440, 135, '蚌山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1441, 135, '禹会区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1442, 135, '淮上区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1443, 135, '怀远县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1444, 135, '五河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1445, 135, '固镇县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1446, 135, '蚌埠市高新技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1447, 135, '蚌埠市经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1448, 136, '大通区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1449, 136, '田家庵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1450, 136, '谢家集区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1451, 136, '八公山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1452, 136, '潘集区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1453, 136, '凤台县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1454, 136, '寿县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1455, 137, '花山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1456, 137, '雨山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1457, 137, '博望区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1458, 137, '当涂县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1459, 137, '含山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1460, 137, '和县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1461, 138, '杜集区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1462, 138, '相山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1463, 138, '烈山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1464, 138, '濉溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1465, 139, '铜官区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1466, 139, '义安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1467, 139, '郊区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1468, 139, '枞阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1469, 140, '迎江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1470, 140, '大观区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1471, 140, '宜秀区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1472, 140, '怀宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1473, 140, '太湖县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1474, 140, '宿松县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1475, 140, '望江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1476, 140, '岳西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1477, 140, '安徽安庆经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1478, 140, '桐城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1479, 140, '潜山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1480, 141, '屯溪区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1481, 141, '黄山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1482, 141, '徽州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1483, 141, '歙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1484, 141, '休宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1485, 141, '黟县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1486, 141, '祁门县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1487, 142, '琅琊区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1488, 142, '南谯区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1489, 142, '来安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1490, 142, '全椒县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1491, 142, '定远县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1492, 142, '凤阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1493, 142, '中新苏滁高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1494, 142, '滁州经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1495, 142, '天长市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1496, 142, '明光市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1497, 143, '颍州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1498, 143, '颍东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1499, 143, '颍泉区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1500, 143, '临泉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1501, 143, '太和县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1502, 143, '阜南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1503, 143, '颍上县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1504, 143, '阜阳合肥现代产业园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1505, 143, '阜阳经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1506, 143, '界首市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1507, 144, '埇桥区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1508, 144, '砀山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1509, 144, '萧县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1510, 144, '灵璧县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1511, 144, '泗县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1512, 144, '宿州马鞍山现代产业园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1513, 144, '宿州经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1514, 145, '金安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1515, 145, '裕安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1516, 145, '叶集区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1517, 145, '霍邱县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1518, 145, '舒城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1519, 145, '金寨县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559940, 0),
(1520, 145, '霍山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1521, 146, '谯城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1522, 146, '涡阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1523, 146, '蒙城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1524, 146, '利辛县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1525, 147, '贵池区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1526, 147, '东至县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1527, 147, '石台县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1528, 147, '青阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1529, 148, '宣州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1530, 148, '郎溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1531, 148, '泾县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1532, 148, '绩溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1533, 148, '旌德县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1534, 148, '宣城市经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1535, 148, '宁国市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1536, 148, '广德市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1537, 149, '鼓楼区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1538, 149, '台江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1539, 149, '仓山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1540, 149, '马尾区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1541, 149, '晋安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1542, 149, '长乐区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1543, 149, '闽侯县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1544, 149, '连江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1545, 149, '罗源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1546, 149, '闽清县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1547, 149, '永泰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1548, 149, '平潭县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1549, 149, '福清市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1550, 150, '思明区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1551, 150, '海沧区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1552, 150, '湖里区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1553, 150, '集美区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1554, 150, '同安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1555, 150, '翔安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1556, 151, '城厢区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1557, 151, '涵江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1558, 151, '荔城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1559, 151, '秀屿区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1560, 151, '仙游县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1561, 152, '三元区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1562, 152, '沙县区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1563, 152, '明溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1564, 152, '清流县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1565, 152, '宁化县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1566, 152, '大田县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1567, 152, '尤溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1568, 152, '将乐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1569, 152, '泰宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1570, 152, '建宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1571, 152, '永安市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1572, 153, '鲤城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1573, 153, '丰泽区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1574, 153, '洛江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1575, 153, '泉港区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1576, 153, '惠安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1577, 153, '安溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1578, 153, '永春县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1579, 153, '德化县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1580, 153, '金门县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1581, 153, '石狮市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1582, 153, '晋江市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1583, 153, '南安市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1584, 154, '芗城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1585, 154, '龙文区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1586, 154, '龙海区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1587, 154, '长泰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1588, 154, '云霄县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1589, 154, '漳浦县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1590, 154, '诏安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1591, 154, '东山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1592, 154, '南靖县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1593, 154, '平和县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1594, 154, '华安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1595, 155, '延平区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1596, 155, '建阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1597, 155, '顺昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1598, 155, '浦城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1599, 155, '光泽县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1600, 155, '松溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1601, 155, '政和县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1602, 155, '邵武市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1603, 155, '武夷山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1604, 155, '建瓯市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1605, 156, '新罗区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1606, 156, '永定区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1607, 156, '长汀县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1608, 156, '上杭县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1609, 156, '武平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1610, 156, '连城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1611, 156, '漳平市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1612, 157, '蕉城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1613, 157, '霞浦县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1614, 157, '古田县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1615, 157, '屏南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1616, 157, '寿宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1617, 157, '周宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1618, 157, '柘荣县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1619, 157, '福安市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1620, 157, '福鼎市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1621, 158, '东湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1622, 158, '西湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1623, 158, '青云谱区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1624, 158, '青山湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1625, 158, '新建区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1626, 158, '红谷滩区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1627, 158, '南昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1628, 158, '安义县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1629, 158, '进贤县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1630, 159, '昌江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1631, 159, '珠山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1632, 159, '浮梁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1633, 159, '乐平市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1634, 160, '安源区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1635, 160, '湘东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1636, 160, '莲花县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1637, 160, '上栗县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1638, 160, '芦溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1639, 161, '濂溪区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1640, 161, '浔阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1641, 161, '柴桑区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1642, 161, '武宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1643, 161, '修水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1644, 161, '永修县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1645, 161, '德安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1646, 161, '都昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1647, 161, '湖口县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1648, 161, '彭泽县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1649, 161, '瑞昌市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1650, 161, '共青城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1651, 161, '庐山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1652, 162, '渝水区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1653, 162, '分宜县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1654, 163, '月湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1655, 163, '余江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1656, 163, '贵溪市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1657, 164, '章贡区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1658, 164, '南康区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1659, 164, '赣县区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1660, 164, '信丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1661, 164, '大余县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1662, 164, '上犹县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1663, 164, '崇义县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1664, 164, '安远县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1665, 164, '定南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1666, 164, '全南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1667, 164, '宁都县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1668, 164, '于都县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1669, 164, '兴国县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1670, 164, '会昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1671, 164, '寻乌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1672, 164, '石城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1673, 164, '瑞金市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1674, 164, '龙南市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1675, 165, '吉州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1676, 165, '青原区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1677, 165, '吉安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1678, 165, '吉水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1679, 165, '峡江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1680, 165, '新干县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1681, 165, '永丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1682, 165, '泰和县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1683, 165, '遂川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1684, 165, '万安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1685, 165, '安福县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1686, 165, '永新县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1687, 165, '井冈山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1688, 166, '袁州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1689, 166, '奉新县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1690, 166, '万载县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1691, 166, '上高县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1692, 166, '宜丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1693, 166, '靖安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1694, 166, '铜鼓县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1695, 166, '丰城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1696, 166, '樟树市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1697, 166, '高安市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1698, 167, '临川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1699, 167, '东乡区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1700, 167, '南城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1701, 167, '黎川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1702, 167, '南丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1703, 167, '崇仁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1704, 167, '乐安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1705, 167, '宜黄县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1706, 167, '金溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1707, 167, '资溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1708, 167, '广昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1709, 168, '信州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1710, 168, '广丰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1711, 168, '广信区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1712, 168, '玉山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1713, 168, '铅山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1714, 168, '横峰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1715, 168, '弋阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1716, 168, '余干县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1717, 168, '鄱阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1718, 168, '万年县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1719, 168, '婺源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1720, 168, '德兴市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1721, 169, '历下区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1722, 169, '市中区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1723, 169, '槐荫区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1724, 169, '天桥区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1725, 169, '历城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1726, 169, '长清区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1727, 169, '章丘区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1728, 169, '济阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1729, 169, '莱芜区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1730, 169, '钢城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1731, 169, '平阴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1732, 169, '商河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1733, 169, '济南高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1734, 170, '市南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1735, 170, '市北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1736, 170, '黄岛区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1737, 170, '崂山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1738, 170, '李沧区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1739, 170, '城阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1740, 170, '即墨区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1741, 170, '青岛高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1742, 170, '胶州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1743, 170, '平度市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1744, 170, '莱西市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1745, 171, '淄川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1746, 171, '张店区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1747, 171, '博山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1748, 171, '临淄区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1749, 171, '周村区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1750, 171, '桓台县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1751, 171, '高青县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1752, 171, '沂源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1753, 172, '市中区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1754, 172, '薛城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1755, 172, '峄城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1756, 172, '台儿庄区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1757, 172, '山亭区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1758, 172, '滕州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1759, 173, '东营区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1760, 173, '河口区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1761, 173, '垦利区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1762, 173, '利津县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1763, 173, '广饶县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1764, 173, '东营经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1765, 173, '东营港经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1766, 174, '芝罘区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1767, 174, '福山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1768, 174, '牟平区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1769, 174, '莱山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1770, 174, '蓬莱区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1771, 174, '烟台高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1772, 174, '烟台经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1773, 174, '龙口市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1774, 174, '莱阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1775, 174, '莱州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1776, 174, '招远市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1777, 174, '栖霞市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1778, 174, '海阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1779, 175, '潍城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1780, 175, '寒亭区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1781, 175, '坊子区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1782, 175, '奎文区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1783, 175, '临朐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1784, 175, '昌乐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1785, 175, '潍坊滨海经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1786, 175, '青州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1787, 175, '诸城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1788, 175, '寿光市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1789, 175, '安丘市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1790, 175, '高密市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1791, 175, '昌邑市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1792, 176, '任城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1793, 176, '兖州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1794, 176, '微山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1795, 176, '鱼台县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1796, 176, '金乡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1797, 176, '嘉祥县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1798, 176, '汶上县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1799, 176, '泗水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1800, 176, '梁山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1801, 176, '济宁高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1802, 176, '曲阜市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1803, 176, '邹城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1804, 177, '泰山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1805, 177, '岱岳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1806, 177, '宁阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1807, 177, '东平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1808, 177, '新泰市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1809, 177, '肥城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1810, 178, '环翠区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1811, 178, '文登区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1812, 178, '威海火炬高技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1813, 178, '威海经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1814, 178, '威海临港经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1815, 178, '荣成市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1816, 178, '乳山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1817, 179, '东港区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1818, 179, '岚山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1819, 179, '五莲县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1820, 179, '莒县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1821, 179, '日照经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1822, 180, '兰山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1823, 180, '罗庄区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1824, 180, '河东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1825, 180, '沂南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1826, 180, '郯城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1827, 180, '沂水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1828, 180, '兰陵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1829, 180, '费县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1830, 180, '平邑县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1831, 180, '莒南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1832, 180, '蒙阴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1833, 180, '临沭县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1834, 180, '临沂高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1835, 181, '德城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1836, 181, '陵城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1837, 181, '宁津县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1838, 181, '庆云县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1839, 181, '临邑县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1840, 181, '齐河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1841, 181, '平原县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1842, 181, '夏津县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1843, 181, '武城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1844, 181, '德州经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1845, 181, '德州运河经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1846, 181, '乐陵市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1847, 181, '禹城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1848, 182, '东昌府区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1849, 182, '茌平区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1850, 182, '阳谷县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1851, 182, '莘县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1852, 182, '东阿县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1853, 182, '冠县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1854, 182, '高唐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1855, 182, '临清市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1856, 183, '滨城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1857, 183, '沾化区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1858, 183, '惠民县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1859, 183, '阳信县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1860, 183, '无棣县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1861, 183, '博兴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1862, 183, '邹平市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1863, 184, '牡丹区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1864, 184, '定陶区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1865, 184, '曹县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1866, 184, '单县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1867, 184, '成武县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1868, 184, '巨野县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1869, 184, '郓城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1870, 184, '鄄城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1871, 184, '东明县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1872, 184, '菏泽经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1873, 184, '菏泽高新技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1874, 185, '中原区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1875, 185, '二七区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1876, 185, '管城回族区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1877, 185, '金水区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1878, 185, '上街区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1879, 185, '惠济区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1880, 185, '中牟县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1881, 185, '郑州经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1882, 185, '郑州高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1883, 185, '郑州航空港经济综合实验区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1884, 185, '巩义市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1885, 185, '荥阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1886, 185, '新密市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1887, 185, '新郑市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1888, 185, '登封市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1889, 186, '龙亭区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1890, 186, '顺河回族区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1891, 186, '鼓楼区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1892, 186, '禹王台区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1893, 186, '祥符区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1894, 186, '杞县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1895, 186, '通许县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1896, 186, '尉氏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1897, 186, '兰考县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1898, 187, '老城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1899, 187, '西工区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1900, 187, '瀍河回族区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1901, 187, '涧西区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1902, 187, '偃师区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1903, 187, '孟津区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1904, 187, '洛龙区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1905, 187, '新安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1906, 187, '栾川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1907, 187, '嵩县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1908, 187, '汝阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1909, 187, '宜阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1910, 187, '洛宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1911, 187, '伊川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1912, 187, '洛阳高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1913, 188, '新华区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1914, 188, '卫东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1915, 188, '石龙区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1916, 188, '湛河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1917, 188, '宝丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1918, 188, '叶县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1919, 188, '鲁山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1920, 188, '郏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1921, 188, '平顶山高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1922, 188, '平顶山市城乡一体化示范区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1923, 188, '舞钢市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1924, 188, '汝州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1925, 189, '文峰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1926, 189, '北关区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1927, 189, '殷都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1928, 189, '龙安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1929, 189, '安阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1930, 189, '汤阴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1931, 189, '滑县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0);
INSERT INTO `sxo_region` (`id`, `pid`, `name`, `level`, `letters`, `code`, `lng`, `lat`, `sort`, `is_enable`, `add_time`, `upd_time`) VALUES
(1932, 189, '内黄县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1933, 189, '安阳高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1934, 189, '林州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1935, 190, '鹤山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1936, 190, '山城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1937, 190, '淇滨区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1938, 190, '浚县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1939, 190, '淇县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1940, 190, '鹤壁经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1941, 191, '红旗区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1942, 191, '卫滨区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1943, 191, '凤泉区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1944, 191, '牧野区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1945, 191, '新乡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1946, 191, '获嘉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1947, 191, '原阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1948, 191, '延津县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1949, 191, '封丘县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1950, 191, '新乡高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1951, 191, '新乡经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1952, 191, '新乡市平原城乡一体化示范区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1953, 191, '卫辉市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1954, 191, '辉县市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1955, 191, '长垣市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1956, 192, '解放区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1957, 192, '中站区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1958, 192, '马村区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1959, 192, '山阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1960, 192, '修武县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1961, 192, '博爱县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1962, 192, '武陟县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1963, 192, '温县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1964, 192, '焦作城乡一体化示范区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1965, 192, '沁阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1966, 192, '孟州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1967, 193, '华龙区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1968, 193, '清丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1969, 193, '南乐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1970, 193, '范县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1971, 193, '台前县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1972, 193, '濮阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1973, 193, '河南濮阳工业园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1974, 193, '濮阳经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1975, 194, '魏都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1976, 194, '建安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1977, 194, '鄢陵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1978, 194, '襄城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1979, 194, '许昌经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1980, 194, '禹州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1981, 194, '长葛市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1982, 195, '源汇区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1983, 195, '郾城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1984, 195, '召陵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1985, 195, '舞阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1986, 195, '临颍县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1987, 195, '漯河经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1988, 196, '湖滨区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1989, 196, '陕州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1990, 196, '渑池县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1991, 196, '卢氏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1992, 196, '河南三门峡经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1993, 196, '义马市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1994, 196, '灵宝市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1995, 197, '宛城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1996, 197, '卧龙区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1997, 197, '南召县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1998, 197, '方城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(1999, 197, '西峡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2000, 197, '镇平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2001, 197, '内乡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2002, 197, '淅川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2003, 197, '社旗县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2004, 197, '唐河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2005, 197, '新野县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2006, 197, '桐柏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2007, 197, '南阳高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2008, 197, '南阳市城乡一体化示范区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2009, 197, '邓州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2010, 198, '梁园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2011, 198, '睢阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2012, 198, '民权县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2013, 198, '睢县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2014, 198, '宁陵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2015, 198, '柘城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2016, 198, '虞城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2017, 198, '夏邑县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2018, 198, '豫东综合物流产业聚集区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2019, 198, '河南商丘经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2020, 198, '永城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2021, 199, '浉河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2022, 199, '平桥区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2023, 199, '罗山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2024, 199, '光山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2025, 199, '新县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2026, 199, '商城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2027, 199, '固始县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2028, 199, '潢川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2029, 199, '淮滨县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2030, 199, '息县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2031, 199, '信阳高新技术产业开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2032, 200, '川汇区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2033, 200, '淮阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2034, 200, '扶沟县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2035, 200, '西华县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2036, 200, '商水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2037, 200, '沈丘县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2038, 200, '郸城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2039, 200, '太康县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2040, 200, '鹿邑县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2041, 200, '河南周口经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2042, 200, '项城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2043, 201, '驿城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2044, 201, '西平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2045, 201, '上蔡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2046, 201, '平舆县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2047, 201, '正阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2048, 201, '确山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2049, 201, '泌阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2050, 201, '汝南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2051, 201, '遂平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2052, 201, '新蔡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2053, 201, '河南驻马店经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2054, 202, '济源市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2055, 203, '江岸区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2056, 203, '江汉区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2057, 203, '硚口区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2058, 203, '汉阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2059, 203, '武昌区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2060, 203, '青山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2061, 203, '洪山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2062, 203, '东西湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2063, 203, '汉南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2064, 203, '蔡甸区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2065, 203, '江夏区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2066, 203, '黄陂区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2067, 203, '新洲区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2068, 204, '黄石港区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2069, 204, '西塞山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2070, 204, '下陆区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2071, 204, '铁山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2072, 204, '阳新县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2073, 204, '大冶市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2074, 205, '茅箭区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2075, 205, '张湾区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2076, 205, '郧阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2077, 205, '郧西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2078, 205, '竹山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2079, 205, '竹溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2080, 205, '房县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2081, 205, '丹江口市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2082, 206, '西陵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2083, 206, '伍家岗区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2084, 206, '点军区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2085, 206, '猇亭区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2086, 206, '夷陵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2087, 206, '远安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2088, 206, '兴山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2089, 206, '秭归县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2090, 206, '长阳土家族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2091, 206, '五峰土家族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2092, 206, '宜都市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2093, 206, '当阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2094, 206, '枝江市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2095, 207, '襄城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2096, 207, '樊城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2097, 207, '襄州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2098, 207, '南漳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2099, 207, '谷城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2100, 207, '保康县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2101, 207, '老河口市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2102, 207, '枣阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2103, 207, '宜城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2104, 208, '梁子湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2105, 208, '华容区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2106, 208, '鄂城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2107, 209, '东宝区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2108, 209, '掇刀区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2109, 209, '沙洋县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2110, 209, '钟祥市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2111, 209, '京山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2112, 210, '孝南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2113, 210, '孝昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2114, 210, '大悟县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2115, 210, '云梦县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2116, 210, '应城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2117, 210, '安陆市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2118, 210, '汉川市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2119, 211, '沙市区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2120, 211, '荆州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2121, 211, '公安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2122, 211, '江陵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2123, 211, '荆州经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2124, 211, '石首市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2125, 211, '洪湖市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2126, 211, '松滋市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2127, 211, '监利市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2128, 212, '黄州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2129, 212, '团风县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2130, 212, '红安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2131, 212, '罗田县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2132, 212, '英山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2133, 212, '浠水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2134, 212, '蕲春县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2135, 212, '黄梅县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2136, 212, '龙感湖管理区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2137, 212, '麻城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2138, 212, '武穴市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2139, 213, '咸安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2140, 213, '嘉鱼县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2141, 213, '通城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2142, 213, '崇阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2143, 213, '通山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2144, 213, '赤壁市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2145, 214, '曾都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2146, 214, '随县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2147, 214, '广水市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2148, 215, '恩施市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2149, 215, '利川市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2150, 215, '建始县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2151, 215, '巴东县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2152, 215, '宣恩县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2153, 215, '咸丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2154, 215, '来凤县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2155, 215, '鹤峰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2156, 216, '仙桃市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2157, 216, '潜江市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2158, 216, '天门市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2159, 216, '神农架林区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2160, 217, '芙蓉区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2161, 217, '天心区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2162, 217, '岳麓区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2163, 217, '开福区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2164, 217, '雨花区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2165, 217, '望城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2166, 217, '长沙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2167, 217, '浏阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2168, 217, '宁乡市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2169, 218, '荷塘区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2170, 218, '芦淞区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2171, 218, '石峰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2172, 218, '天元区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2173, 218, '渌口区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2174, 218, '攸县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2175, 218, '茶陵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2176, 218, '炎陵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2177, 218, '云龙示范区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2178, 218, '醴陵市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2179, 219, '雨湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2180, 219, '岳塘区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2181, 219, '湘潭县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2182, 219, '湖南湘潭高新技术产业园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2183, 219, '湘潭昭山示范区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2184, 219, '湘潭九华示范区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2185, 219, '湘乡市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2186, 219, '韶山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2187, 220, '珠晖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2188, 220, '雁峰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2189, 220, '石鼓区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2190, 220, '蒸湘区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2191, 220, '南岳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2192, 220, '衡阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2193, 220, '衡南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2194, 220, '衡山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2195, 220, '衡东县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2196, 220, '祁东县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2197, 220, '衡阳综合保税区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2198, 220, '湖南衡阳高新技术产业园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2199, 220, '湖南衡阳松木经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2200, 220, '耒阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2201, 220, '常宁市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2202, 221, '双清区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2203, 221, '大祥区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2204, 221, '北塔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2205, 221, '新邵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2206, 221, '邵阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2207, 221, '隆回县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2208, 221, '洞口县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2209, 221, '绥宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2210, 221, '新宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2211, 221, '城步苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2212, 221, '武冈市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2213, 221, '邵东市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2214, 222, '岳阳楼区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2215, 222, '云溪区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2216, 222, '君山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2217, 222, '岳阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2218, 222, '华容县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2219, 222, '湘阴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2220, 222, '平江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2221, 222, '岳阳市屈原管理区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2222, 222, '汨罗市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2223, 222, '临湘市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2224, 223, '武陵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2225, 223, '鼎城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2226, 223, '安乡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2227, 223, '汉寿县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2228, 223, '澧县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2229, 223, '临澧县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2230, 223, '桃源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2231, 223, '石门县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2232, 223, '常德市西洞庭管理区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2233, 223, '津市市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2234, 224, '永定区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2235, 224, '武陵源区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2236, 224, '慈利县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2237, 224, '桑植县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2238, 225, '资阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2239, 225, '赫山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2240, 225, '南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2241, 225, '桃江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2242, 225, '安化县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2243, 225, '益阳市大通湖管理区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2244, 225, '湖南益阳高新技术产业园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2245, 225, '沅江市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2246, 226, '北湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2247, 226, '苏仙区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2248, 226, '桂阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2249, 226, '宜章县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2250, 226, '永兴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2251, 226, '嘉禾县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2252, 226, '临武县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2253, 226, '汝城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2254, 226, '桂东县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2255, 226, '安仁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2256, 226, '资兴市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2257, 227, '零陵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2258, 227, '冷水滩区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2259, 227, '东安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2260, 227, '双牌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2261, 227, '道县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2262, 227, '江永县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2263, 227, '宁远县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2264, 227, '蓝山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2265, 227, '新田县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2266, 227, '江华瑶族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2267, 227, '永州经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2268, 227, '永州市回龙圩管理区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2269, 227, '祁阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2270, 228, '鹤城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2271, 228, '中方县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2272, 228, '沅陵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2273, 228, '辰溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2274, 228, '溆浦县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2275, 228, '会同县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2276, 228, '麻阳苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2277, 228, '新晃侗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2278, 228, '芷江侗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2279, 228, '靖州苗族侗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2280, 228, '通道侗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2281, 228, '怀化市洪江管理区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2282, 228, '洪江市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2283, 229, '娄星区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2284, 229, '双峰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2285, 229, '新化县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2286, 229, '冷水江市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2287, 229, '涟源市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2288, 230, '吉首市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2289, 230, '泸溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2290, 230, '凤凰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2291, 230, '花垣县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2292, 230, '保靖县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2293, 230, '古丈县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2294, 230, '永顺县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2295, 230, '龙山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2296, 231, '荔湾区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2297, 231, '越秀区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2298, 231, '海珠区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2299, 231, '天河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2300, 231, '白云区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2301, 231, '黄埔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2302, 231, '番禺区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2303, 231, '花都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2304, 231, '南沙区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2305, 231, '从化区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2306, 231, '增城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2307, 232, '武江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2308, 232, '浈江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2309, 232, '曲江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2310, 232, '始兴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2311, 232, '仁化县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2312, 232, '翁源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2313, 232, '乳源瑶族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2314, 232, '新丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2315, 232, '乐昌市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2316, 232, '南雄市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2317, 233, '罗湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2318, 233, '福田区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2319, 233, '南山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2320, 233, '宝安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2321, 233, '龙岗区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2322, 233, '盐田区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2323, 233, '龙华区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2324, 233, '坪山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2325, 233, '光明区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2326, 233, '大鹏新区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2327, 234, '香洲区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2328, 234, '斗门区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2329, 234, '金湾区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2330, 235, '龙湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2331, 235, '金平区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2332, 235, '濠江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2333, 235, '潮阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2334, 235, '潮南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2335, 235, '澄海区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2336, 235, '南澳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2337, 236, '禅城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2338, 236, '南海区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2339, 236, '顺德区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2340, 236, '三水区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2341, 236, '高明区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2342, 237, '蓬江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2343, 237, '江海区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2344, 237, '新会区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2345, 237, '台山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2346, 237, '开平市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2347, 237, '鹤山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2348, 237, '恩平市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2349, 238, '赤坎区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2350, 238, '霞山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2351, 238, '坡头区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2352, 238, '麻章区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2353, 238, '遂溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2354, 238, '徐闻县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2355, 238, '廉江市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2356, 238, '雷州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2357, 238, '吴川市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2358, 239, '茂南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2359, 239, '电白区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2360, 239, '高州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2361, 239, '化州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2362, 239, '信宜市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2363, 240, '端州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2364, 240, '鼎湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2365, 240, '高要区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2366, 240, '广宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2367, 240, '怀集县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2368, 240, '封开县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2369, 240, '德庆县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2370, 240, '四会市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2371, 241, '惠城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2372, 241, '惠阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2373, 241, '博罗县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2374, 241, '惠东县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2375, 241, '龙门县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2376, 242, '梅江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2377, 242, '梅县区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2378, 242, '大埔县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2379, 242, '丰顺县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2380, 242, '五华县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2381, 242, '平远县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2382, 242, '蕉岭县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2383, 242, '兴宁市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2384, 243, '城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2385, 243, '海丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2386, 243, '陆河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2387, 243, '陆丰市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2388, 244, '源城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2389, 244, '紫金县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2390, 244, '龙川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2391, 244, '连平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2392, 244, '和平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2393, 244, '东源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2394, 245, '江城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2395, 245, '阳东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2396, 245, '阳西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2397, 245, '阳春市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2398, 246, '清城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2399, 246, '清新区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2400, 246, '佛冈县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2401, 246, '阳山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2402, 246, '连山壮族瑶族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2403, 246, '连南瑶族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2404, 246, '英德市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2405, 246, '连州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2406, 247, '东莞市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2407, 248, '中山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2408, 249, '湘桥区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2409, 249, '潮安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2410, 249, '饶平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2411, 250, '榕城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2412, 250, '揭东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2413, 250, '揭西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2414, 250, '惠来县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2415, 250, '普宁市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2416, 251, '云城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2417, 251, '云安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2418, 251, '新兴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2419, 251, '郁南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2420, 251, '罗定市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2421, 252, '兴宁区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2422, 252, '青秀区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2423, 252, '江南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2424, 252, '西乡塘区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2425, 252, '良庆区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2426, 252, '邕宁区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2427, 252, '武鸣区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2428, 252, '隆安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2429, 252, '马山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2430, 252, '上林县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2431, 252, '宾阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2432, 252, '横州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2433, 253, '城中区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2434, 253, '鱼峰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2435, 253, '柳南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2436, 253, '柳北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2437, 253, '柳江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2438, 253, '柳城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2439, 253, '鹿寨县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2440, 253, '融安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2441, 253, '融水苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2442, 253, '三江侗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2443, 254, '秀峰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2444, 254, '叠彩区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2445, 254, '象山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2446, 254, '七星区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2447, 254, '雁山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2448, 254, '临桂区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2449, 254, '阳朔县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2450, 254, '灵川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2451, 254, '全州县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2452, 254, '兴安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2453, 254, '永福县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2454, 254, '灌阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2455, 254, '龙胜各族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2456, 254, '资源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2457, 254, '平乐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2458, 254, '恭城瑶族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2459, 254, '荔浦市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2460, 255, '万秀区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2461, 255, '长洲区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2462, 255, '龙圩区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2463, 255, '苍梧县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2464, 255, '藤县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2465, 255, '蒙山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2466, 255, '岑溪市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2467, 256, '海城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2468, 256, '银海区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2469, 256, '铁山港区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2470, 256, '合浦县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2471, 257, '港口区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2472, 257, '防城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2473, 257, '上思县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2474, 257, '东兴市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2475, 258, '钦南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2476, 258, '钦北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2477, 258, '灵山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2478, 258, '浦北县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2479, 259, '港北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2480, 259, '港南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2481, 259, '覃塘区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2482, 259, '平南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2483, 259, '桂平市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2484, 260, '玉州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2485, 260, '福绵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2486, 260, '容县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2487, 260, '陆川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2488, 260, '博白县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2489, 260, '兴业县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2490, 260, '北流市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2491, 261, '右江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2492, 261, '田阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2493, 261, '田东县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2494, 261, '德保县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2495, 261, '那坡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2496, 261, '凌云县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2497, 261, '乐业县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2498, 261, '田林县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2499, 261, '西林县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2500, 261, '隆林各族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2501, 261, '靖西市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2502, 261, '平果市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2503, 262, '八步区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2504, 262, '平桂区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2505, 262, '昭平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2506, 262, '钟山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2507, 262, '富川瑶族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2508, 263, '金城江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2509, 263, '宜州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2510, 263, '南丹县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2511, 263, '天峨县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2512, 263, '凤山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2513, 263, '东兰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2514, 263, '罗城仫佬族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2515, 263, '环江毛南族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2516, 263, '巴马瑶族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2517, 263, '都安瑶族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559941, 0),
(2518, 263, '大化瑶族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2519, 264, '兴宾区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2520, 264, '忻城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2521, 264, '象州县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2522, 264, '武宣县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2523, 264, '金秀瑶族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2524, 264, '合山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2525, 265, '江州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2526, 265, '扶绥县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2527, 265, '宁明县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2528, 265, '龙州县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2529, 265, '大新县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2530, 265, '天等县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2531, 265, '凭祥市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2532, 266, '秀英区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2533, 266, '龙华区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2534, 266, '琼山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2535, 266, '美兰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2536, 267, '海棠区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2537, 267, '吉阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2538, 267, '天涯区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2539, 267, '崖州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2540, 268, '西沙群岛', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2541, 268, '南沙群岛', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2542, 268, '中沙群岛的岛礁及其海域', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2543, 269, '儋州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2544, 270, '五指山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2545, 270, '琼海市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2546, 270, '文昌市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2547, 270, '万宁市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2548, 270, '东方市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2549, 270, '定安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2550, 270, '屯昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2551, 270, '澄迈县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2552, 270, '临高县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2553, 270, '白沙黎族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2554, 270, '昌江黎族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2555, 270, '乐东黎族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2556, 270, '陵水黎族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2557, 270, '保亭黎族苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2558, 270, '琼中黎族苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2559, 271, '万州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2560, 271, '涪陵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2561, 271, '渝中区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2562, 271, '大渡口区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2563, 271, '江北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2564, 271, '沙坪坝区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2565, 271, '九龙坡区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0);
INSERT INTO `sxo_region` (`id`, `pid`, `name`, `level`, `letters`, `code`, `lng`, `lat`, `sort`, `is_enable`, `add_time`, `upd_time`) VALUES
(2566, 271, '南岸区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2567, 271, '北碚区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2568, 271, '綦江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2569, 271, '大足区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2570, 271, '渝北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2571, 271, '巴南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2572, 271, '黔江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2573, 271, '长寿区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2574, 271, '江津区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2575, 271, '合川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2576, 271, '永川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2577, 271, '南川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2578, 271, '璧山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2579, 271, '铜梁区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2580, 271, '潼南区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2581, 271, '荣昌区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2582, 271, '开州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2583, 271, '梁平区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2584, 271, '武隆区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2585, 272, '城口县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2586, 272, '丰都县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2587, 272, '垫江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2588, 272, '忠县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2589, 272, '云阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2590, 272, '奉节县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2591, 272, '巫山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2592, 272, '巫溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2593, 272, '石柱土家族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2594, 272, '秀山土家族苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2595, 272, '酉阳土家族苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2596, 272, '彭水苗族土家族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2597, 273, '锦江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2598, 273, '青羊区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2599, 273, '金牛区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2600, 273, '武侯区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2601, 273, '成华区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2602, 273, '龙泉驿区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2603, 273, '青白江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2604, 273, '新都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2605, 273, '温江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2606, 273, '双流区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2607, 273, '郫都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2608, 273, '新津区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2609, 273, '金堂县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2610, 273, '大邑县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2611, 273, '蒲江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2612, 273, '都江堰市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2613, 273, '彭州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2614, 273, '邛崃市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2615, 273, '崇州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2616, 273, '简阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2617, 274, '自流井区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2618, 274, '贡井区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2619, 274, '大安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2620, 274, '沿滩区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2621, 274, '荣县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2622, 274, '富顺县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2623, 275, '东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2624, 275, '西区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2625, 275, '仁和区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2626, 275, '米易县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2627, 275, '盐边县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2628, 276, '江阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2629, 276, '纳溪区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2630, 276, '龙马潭区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2631, 276, '泸县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2632, 276, '合江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2633, 276, '叙永县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2634, 276, '古蔺县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2635, 277, '旌阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2636, 277, '罗江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2637, 277, '中江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2638, 277, '广汉市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2639, 277, '什邡市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2640, 277, '绵竹市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2641, 278, '涪城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2642, 278, '游仙区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2643, 278, '安州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2644, 278, '三台县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2645, 278, '盐亭县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2646, 278, '梓潼县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2647, 278, '北川羌族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2648, 278, '平武县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2649, 278, '江油市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2650, 279, '利州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2651, 279, '昭化区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2652, 279, '朝天区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2653, 279, '旺苍县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2654, 279, '青川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2655, 279, '剑阁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2656, 279, '苍溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2657, 280, '船山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2658, 280, '安居区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2659, 280, '蓬溪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2660, 280, '大英县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2661, 280, '射洪市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2662, 281, '市中区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2663, 281, '东兴区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2664, 281, '威远县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2665, 281, '资中县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2666, 281, '内江经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2667, 281, '隆昌市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2668, 282, '市中区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2669, 282, '沙湾区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2670, 282, '五通桥区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2671, 282, '金口河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2672, 282, '犍为县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2673, 282, '井研县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2674, 282, '夹江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2675, 282, '沐川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2676, 282, '峨边彝族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2677, 282, '马边彝族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2678, 282, '峨眉山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2679, 283, '顺庆区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2680, 283, '高坪区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2681, 283, '嘉陵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2682, 283, '南部县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2683, 283, '营山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2684, 283, '蓬安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2685, 283, '仪陇县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2686, 283, '西充县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2687, 283, '阆中市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2688, 284, '东坡区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2689, 284, '彭山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2690, 284, '仁寿县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2691, 284, '洪雅县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2692, 284, '丹棱县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2693, 284, '青神县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2694, 285, '翠屏区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2695, 285, '南溪区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2696, 285, '叙州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2697, 285, '江安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2698, 285, '长宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2699, 285, '高县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2700, 285, '珙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2701, 285, '筠连县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2702, 285, '兴文县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2703, 285, '屏山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2704, 286, '广安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2705, 286, '前锋区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2706, 286, '岳池县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2707, 286, '武胜县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2708, 286, '邻水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2709, 286, '华蓥市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2710, 287, '通川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2711, 287, '达川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2712, 287, '宣汉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2713, 287, '开江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2714, 287, '大竹县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2715, 287, '渠县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2716, 287, '达州经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2717, 287, '万源市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2718, 288, '雨城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2719, 288, '名山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2720, 288, '荥经县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2721, 288, '汉源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2722, 288, '石棉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2723, 288, '天全县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2724, 288, '芦山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2725, 288, '宝兴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2726, 289, '巴州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2727, 289, '恩阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2728, 289, '通江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2729, 289, '南江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2730, 289, '平昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2731, 289, '巴中经济开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2732, 290, '雁江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2733, 290, '安岳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2734, 290, '乐至县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2735, 291, '马尔康市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2736, 291, '汶川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2737, 291, '理县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2738, 291, '茂县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2739, 291, '松潘县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2740, 291, '九寨沟县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2741, 291, '金川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2742, 291, '小金县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2743, 291, '黑水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2744, 291, '壤塘县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2745, 291, '阿坝县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2746, 291, '若尔盖县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2747, 291, '红原县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2748, 292, '康定市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2749, 292, '泸定县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2750, 292, '丹巴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2751, 292, '九龙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2752, 292, '雅江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2753, 292, '道孚县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2754, 292, '炉霍县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2755, 292, '甘孜县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2756, 292, '新龙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2757, 292, '德格县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2758, 292, '白玉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2759, 292, '石渠县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2760, 292, '色达县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2761, 292, '理塘县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2762, 292, '巴塘县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2763, 292, '乡城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2764, 292, '稻城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2765, 292, '得荣县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2766, 293, '西昌市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2767, 293, '会理市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2768, 293, '木里藏族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2769, 293, '盐源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2770, 293, '德昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2771, 293, '会东县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2772, 293, '宁南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2773, 293, '普格县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2774, 293, '布拖县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2775, 293, '金阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2776, 293, '昭觉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2777, 293, '喜德县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2778, 293, '冕宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2779, 293, '越西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2780, 293, '甘洛县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2781, 293, '美姑县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2782, 293, '雷波县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2783, 294, '南明区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2784, 294, '云岩区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2785, 294, '花溪区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2786, 294, '乌当区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2787, 294, '白云区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2788, 294, '观山湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2789, 294, '开阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2790, 294, '息烽县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2791, 294, '修文县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2792, 294, '清镇市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2793, 295, '钟山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2794, 295, '六枝特区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2795, 295, '水城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2796, 295, '盘州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2797, 296, '红花岗区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2798, 296, '汇川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2799, 296, '播州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2800, 296, '桐梓县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2801, 296, '绥阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2802, 296, '正安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2803, 296, '道真仡佬族苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2804, 296, '务川仡佬族苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2805, 296, '凤冈县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2806, 296, '湄潭县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2807, 296, '余庆县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2808, 296, '习水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2809, 296, '赤水市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2810, 296, '仁怀市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2811, 297, '西秀区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2812, 297, '平坝区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2813, 297, '普定县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2814, 297, '镇宁布依族苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2815, 297, '关岭布依族苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2816, 297, '紫云苗族布依族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2817, 298, '七星关区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2818, 298, '大方县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2819, 298, '金沙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2820, 298, '织金县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2821, 298, '纳雍县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2822, 298, '威宁彝族回族苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2823, 298, '赫章县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2824, 298, '黔西市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2825, 299, '碧江区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2826, 299, '万山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2827, 299, '江口县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2828, 299, '玉屏侗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2829, 299, '石阡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2830, 299, '思南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2831, 299, '印江土家族苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2832, 299, '德江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2833, 299, '沿河土家族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2834, 299, '松桃苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2835, 300, '兴义市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2836, 300, '兴仁市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2837, 300, '普安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2838, 300, '晴隆县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2839, 300, '贞丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2840, 300, '望谟县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2841, 300, '册亨县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2842, 300, '安龙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2843, 301, '凯里市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2844, 301, '黄平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2845, 301, '施秉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2846, 301, '三穗县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2847, 301, '镇远县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2848, 301, '岑巩县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2849, 301, '天柱县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2850, 301, '锦屏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2851, 301, '剑河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2852, 301, '台江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2853, 301, '黎平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2854, 301, '榕江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2855, 301, '从江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2856, 301, '雷山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2857, 301, '麻江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2858, 301, '丹寨县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2859, 302, '都匀市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2860, 302, '福泉市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2861, 302, '荔波县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2862, 302, '贵定县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2863, 302, '瓮安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2864, 302, '独山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2865, 302, '平塘县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2866, 302, '罗甸县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2867, 302, '长顺县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2868, 302, '龙里县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2869, 302, '惠水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2870, 302, '三都水族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2871, 303, '五华区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2872, 303, '盘龙区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2873, 303, '官渡区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2874, 303, '西山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2875, 303, '东川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2876, 303, '呈贡区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2877, 303, '晋宁区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2878, 303, '富民县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2879, 303, '宜良县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2880, 303, '石林彝族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2881, 303, '嵩明县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2882, 303, '禄劝彝族苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2883, 303, '寻甸回族彝族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2884, 303, '安宁市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2885, 304, '麒麟区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2886, 304, '沾益区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2887, 304, '马龙区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2888, 304, '陆良县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2889, 304, '师宗县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2890, 304, '罗平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2891, 304, '富源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2892, 304, '会泽县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2893, 304, '宣威市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2894, 305, '红塔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2895, 305, '江川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2896, 305, '通海县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2897, 305, '华宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2898, 305, '易门县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2899, 305, '峨山彝族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2900, 305, '新平彝族傣族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2901, 305, '元江哈尼族彝族傣族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2902, 305, '澄江市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2903, 306, '隆阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2904, 306, '施甸县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2905, 306, '龙陵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2906, 306, '昌宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2907, 306, '腾冲市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2908, 307, '昭阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2909, 307, '鲁甸县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2910, 307, '巧家县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2911, 307, '盐津县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2912, 307, '大关县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2913, 307, '永善县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2914, 307, '绥江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2915, 307, '镇雄县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2916, 307, '彝良县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2917, 307, '威信县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2918, 307, '水富市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2919, 308, '古城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2920, 308, '玉龙纳西族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2921, 308, '永胜县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2922, 308, '华坪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2923, 308, '宁蒗彝族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2924, 309, '思茅区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2925, 309, '宁洱哈尼族彝族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2926, 309, '墨江哈尼族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2927, 309, '景东彝族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2928, 309, '景谷傣族彝族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2929, 309, '镇沅彝族哈尼族拉祜族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2930, 309, '江城哈尼族彝族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2931, 309, '孟连傣族拉祜族佤族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2932, 309, '澜沧拉祜族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2933, 309, '西盟佤族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2934, 310, '临翔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2935, 310, '凤庆县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2936, 310, '云县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2937, 310, '永德县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2938, 310, '镇康县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2939, 310, '双江拉祜族佤族布朗族傣族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2940, 310, '耿马傣族佤族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2941, 310, '沧源佤族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2942, 311, '楚雄市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2943, 311, '禄丰市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2944, 311, '双柏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2945, 311, '牟定县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2946, 311, '南华县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2947, 311, '姚安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2948, 311, '大姚县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2949, 311, '永仁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2950, 311, '元谋县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2951, 311, '武定县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2952, 312, '个旧市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2953, 312, '开远市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2954, 312, '蒙自市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2955, 312, '弥勒市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2956, 312, '屏边苗族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2957, 312, '建水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2958, 312, '石屏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2959, 312, '泸西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2960, 312, '元阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2961, 312, '红河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2962, 312, '金平苗族瑶族傣族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2963, 312, '绿春县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2964, 312, '河口瑶族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2965, 313, '文山市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2966, 313, '砚山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2967, 313, '西畴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2968, 313, '麻栗坡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2969, 313, '马关县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2970, 313, '丘北县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2971, 313, '广南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2972, 313, '富宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2973, 314, '景洪市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2974, 314, '勐海县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2975, 314, '勐腊县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2976, 315, '大理市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2977, 315, '漾濞彝族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2978, 315, '祥云县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2979, 315, '宾川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2980, 315, '弥渡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2981, 315, '南涧彝族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2982, 315, '巍山彝族回族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2983, 315, '永平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2984, 315, '云龙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2985, 315, '洱源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2986, 315, '剑川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2987, 315, '鹤庆县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2988, 316, '瑞丽市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2989, 316, '芒市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2990, 316, '梁河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2991, 316, '盈江县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2992, 316, '陇川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2993, 317, '泸水市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2994, 317, '福贡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2995, 317, '贡山独龙族怒族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2996, 317, '兰坪白族普米族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2997, 318, '香格里拉市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2998, 318, '德钦县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(2999, 318, '维西傈僳族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3000, 319, '城关区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3001, 319, '堆龙德庆区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3002, 319, '达孜区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3003, 319, '林周县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3004, 319, '当雄县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3005, 319, '尼木县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3006, 319, '曲水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3007, 319, '墨竹工卡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3008, 319, '格尔木藏青工业园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3009, 319, '拉萨经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3010, 319, '西藏文化旅游创意园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3011, 319, '达孜工业园区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3012, 320, '桑珠孜区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3013, 320, '南木林县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3014, 320, '江孜县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3015, 320, '定日县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3016, 320, '萨迦县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3017, 320, '拉孜县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3018, 320, '昂仁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3019, 320, '谢通门县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3020, 320, '白朗县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3021, 320, '仁布县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3022, 320, '康马县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3023, 320, '定结县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3024, 320, '仲巴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3025, 320, '亚东县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3026, 320, '吉隆县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3027, 320, '聂拉木县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3028, 320, '萨嘎县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3029, 320, '岗巴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3030, 321, '卡若区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3031, 321, '江达县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3032, 321, '贡觉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3033, 321, '类乌齐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3034, 321, '丁青县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3035, 321, '察雅县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3036, 321, '八宿县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3037, 321, '左贡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3038, 321, '芒康县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3039, 321, '洛隆县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3040, 321, '边坝县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3041, 322, '巴宜区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3042, 322, '工布江达县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3043, 322, '米林县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3044, 322, '墨脱县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3045, 322, '波密县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3046, 322, '察隅县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3047, 322, '朗县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3048, 323, '乃东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3049, 323, '扎囊县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3050, 323, '贡嘎县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3051, 323, '桑日县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3052, 323, '琼结县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3053, 323, '曲松县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3054, 323, '措美县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3055, 323, '洛扎县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3056, 323, '加查县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3057, 323, '隆子县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3058, 323, '错那县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3059, 323, '浪卡子县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3060, 324, '色尼区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3061, 324, '嘉黎县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3062, 324, '比如县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3063, 324, '聂荣县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3064, 324, '安多县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3065, 324, '申扎县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3066, 324, '索县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3067, 324, '班戈县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3068, 324, '巴青县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3069, 324, '尼玛县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3070, 324, '双湖县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3071, 325, '普兰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3072, 325, '札达县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3073, 325, '噶尔县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3074, 325, '日土县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3075, 325, '革吉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3076, 325, '改则县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3077, 325, '措勤县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3078, 326, '新城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3079, 326, '碑林区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3080, 326, '莲湖区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3081, 326, '灞桥区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3082, 326, '未央区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3083, 326, '雁塔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3084, 326, '阎良区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3085, 326, '临潼区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3086, 326, '长安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3087, 326, '高陵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3088, 326, '鄠邑区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3089, 326, '蓝田县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3090, 326, '周至县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3091, 327, '王益区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3092, 327, '印台区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3093, 327, '耀州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3094, 327, '宜君县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3095, 328, '渭滨区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3096, 328, '金台区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3097, 328, '陈仓区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3098, 328, '凤翔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3099, 328, '岐山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3100, 328, '扶风县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3101, 328, '眉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3102, 328, '陇县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3103, 328, '千阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3104, 328, '麟游县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3105, 328, '凤县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3106, 328, '太白县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3107, 329, '秦都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3108, 329, '杨陵区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3109, 329, '渭城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3110, 329, '三原县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3111, 329, '泾阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3112, 329, '乾县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3113, 329, '礼泉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3114, 329, '永寿县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3115, 329, '长武县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3116, 329, '旬邑县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3117, 329, '淳化县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3118, 329, '武功县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3119, 329, '兴平市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3120, 329, '彬州市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3121, 330, '临渭区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3122, 330, '华州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3123, 330, '潼关县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3124, 330, '大荔县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3125, 330, '合阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3126, 330, '澄城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3127, 330, '蒲城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3128, 330, '白水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3129, 330, '富平县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3130, 330, '韩城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3131, 330, '华阴市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3132, 331, '宝塔区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3133, 331, '安塞区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3134, 331, '延长县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3135, 331, '延川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3136, 331, '志丹县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3137, 331, '吴起县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3138, 331, '甘泉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3139, 331, '富县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3140, 331, '洛川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3141, 331, '宜川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3142, 331, '黄龙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3143, 331, '黄陵县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3144, 331, '子长市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3145, 332, '汉台区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3146, 332, '南郑区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3147, 332, '城固县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3148, 332, '洋县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3149, 332, '西乡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3150, 332, '勉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3151, 332, '宁强县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3152, 332, '略阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3153, 332, '镇巴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3154, 332, '留坝县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3155, 332, '佛坪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3156, 333, '榆阳区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3157, 333, '横山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3158, 333, '府谷县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3159, 333, '靖边县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3160, 333, '定边县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3161, 333, '绥德县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3162, 333, '米脂县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3163, 333, '佳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3164, 333, '吴堡县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3165, 333, '清涧县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3166, 333, '子洲县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3167, 333, '神木市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3168, 334, '汉滨区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3169, 334, '汉阴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3170, 334, '石泉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3171, 334, '宁陕县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3172, 334, '紫阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3173, 334, '岚皋县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3174, 334, '平利县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3175, 334, '镇坪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3176, 334, '白河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3177, 334, '旬阳市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3178, 335, '商州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3179, 335, '洛南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3180, 335, '丹凤县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3181, 335, '商南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3182, 335, '山阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3183, 335, '镇安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3184, 335, '柞水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3185, 336, '城关区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3186, 336, '七里河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3187, 336, '西固区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3188, 336, '安宁区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3189, 336, '红古区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3190, 336, '永登县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3191, 336, '皋兰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3192, 336, '榆中县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3193, 336, '兰州新区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3194, 337, '嘉峪关市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3195, 338, '金川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3196, 338, '永昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3197, 339, '白银区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3198, 339, '平川区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3199, 339, '靖远县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3200, 339, '会宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0);
INSERT INTO `sxo_region` (`id`, `pid`, `name`, `level`, `letters`, `code`, `lng`, `lat`, `sort`, `is_enable`, `add_time`, `upd_time`) VALUES
(3201, 339, '景泰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3202, 340, '秦州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3203, 340, '麦积区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3204, 340, '清水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3205, 340, '秦安县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3206, 340, '甘谷县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3207, 340, '武山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3208, 340, '张家川回族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3209, 341, '凉州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3210, 341, '民勤县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3211, 341, '古浪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3212, 341, '天祝藏族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3213, 342, '甘州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3214, 342, '肃南裕固族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3215, 342, '民乐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3216, 342, '临泽县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3217, 342, '高台县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3218, 342, '山丹县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3219, 343, '崆峒区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3220, 343, '泾川县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3221, 343, '灵台县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3222, 343, '崇信县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3223, 343, '庄浪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3224, 343, '静宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3225, 343, '华亭市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3226, 344, '肃州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3227, 344, '金塔县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3228, 344, '瓜州县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3229, 344, '肃北蒙古族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3230, 344, '阿克塞哈萨克族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3231, 344, '玉门市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3232, 344, '敦煌市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3233, 345, '西峰区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3234, 345, '庆城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3235, 345, '环县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3236, 345, '华池县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3237, 345, '合水县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3238, 345, '正宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3239, 345, '宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3240, 345, '镇原县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3241, 346, '安定区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3242, 346, '通渭县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3243, 346, '陇西县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3244, 346, '渭源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3245, 346, '临洮县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3246, 346, '漳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3247, 346, '岷县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3248, 347, '武都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3249, 347, '成县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3250, 347, '文县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3251, 347, '宕昌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3252, 347, '康县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3253, 347, '西和县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3254, 347, '礼县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3255, 347, '徽县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3256, 347, '两当县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3257, 348, '临夏市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3258, 348, '临夏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3259, 348, '康乐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3260, 348, '永靖县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3261, 348, '广河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3262, 348, '和政县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3263, 348, '东乡族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3264, 348, '积石山保安族东乡族撒拉族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3265, 349, '合作市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3266, 349, '临潭县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3267, 349, '卓尼县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3268, 349, '舟曲县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3269, 349, '迭部县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3270, 349, '玛曲县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3271, 349, '碌曲县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3272, 349, '夏河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3273, 350, '城东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3274, 350, '城中区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3275, 350, '城西区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3276, 350, '城北区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3277, 350, '湟中区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3278, 350, '大通回族土族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3279, 350, '湟源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3280, 351, '乐都区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3281, 351, '平安区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3282, 351, '民和回族土族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3283, 351, '互助土族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3284, 351, '化隆回族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3285, 351, '循化撒拉族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3286, 352, '门源回族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3287, 352, '祁连县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3288, 352, '海晏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3289, 352, '刚察县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3290, 353, '同仁市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3291, 353, '尖扎县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3292, 353, '泽库县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3293, 353, '河南蒙古族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3294, 354, '共和县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3295, 354, '同德县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3296, 354, '贵德县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3297, 354, '兴海县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3298, 354, '贵南县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3299, 355, '玛沁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3300, 355, '班玛县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3301, 355, '甘德县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3302, 355, '达日县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3303, 355, '久治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3304, 355, '玛多县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3305, 356, '玉树市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3306, 356, '杂多县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3307, 356, '称多县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3308, 356, '治多县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3309, 356, '囊谦县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3310, 356, '曲麻莱县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3311, 357, '格尔木市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3312, 357, '德令哈市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3313, 357, '茫崖市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3314, 357, '乌兰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3315, 357, '都兰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3316, 357, '天峻县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3317, 357, '大柴旦行政委员会', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3318, 358, '兴庆区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3319, 358, '西夏区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3320, 358, '金凤区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3321, 358, '永宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3322, 358, '贺兰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3323, 358, '灵武市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3324, 359, '大武口区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3325, 359, '惠农区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3326, 359, '平罗县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3327, 360, '利通区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3328, 360, '红寺堡区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3329, 360, '盐池县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3330, 360, '同心县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3331, 360, '青铜峡市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3332, 361, '原州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3333, 361, '西吉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3334, 361, '隆德县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3335, 361, '泾源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3336, 361, '彭阳县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3337, 362, '沙坡头区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3338, 362, '中宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3339, 362, '海原县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3340, 363, '天山区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3341, 363, '沙依巴克区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3342, 363, '新市区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3343, 363, '水磨沟区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3344, 363, '头屯河区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3345, 363, '达坂城区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3346, 363, '米东区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3347, 363, '乌鲁木齐县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3348, 364, '独山子区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3349, 364, '克拉玛依区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3350, 364, '白碱滩区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3351, 364, '乌尔禾区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3352, 365, '高昌区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3353, 365, '鄯善县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3354, 365, '托克逊县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3355, 366, '伊州区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3356, 366, '巴里坤哈萨克自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3357, 366, '伊吾县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3358, 367, '昌吉市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3359, 367, '阜康市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3360, 367, '呼图壁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3361, 367, '玛纳斯县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3362, 367, '奇台县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3363, 367, '吉木萨尔县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3364, 367, '木垒哈萨克自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3365, 368, '博乐市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3366, 368, '阿拉山口市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3367, 368, '精河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3368, 368, '温泉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3369, 369, '库尔勒市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3370, 369, '轮台县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3371, 369, '尉犁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3372, 369, '若羌县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3373, 369, '且末县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3374, 369, '焉耆回族自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3375, 369, '和静县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3376, 369, '和硕县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3377, 369, '博湖县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3378, 369, '库尔勒经济技术开发区', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3379, 370, '阿克苏市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3380, 370, '库车市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3381, 370, '温宿县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3382, 370, '沙雅县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3383, 370, '新和县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3384, 370, '拜城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3385, 370, '乌什县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3386, 370, '阿瓦提县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3387, 370, '柯坪县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3388, 371, '阿图什市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3389, 371, '阿克陶县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3390, 371, '阿合奇县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3391, 371, '乌恰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3392, 372, '喀什市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3393, 372, '疏附县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3394, 372, '疏勒县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3395, 372, '英吉沙县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3396, 372, '泽普县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3397, 372, '莎车县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3398, 372, '叶城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3399, 372, '麦盖提县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3400, 372, '岳普湖县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3401, 372, '伽师县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3402, 372, '巴楚县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3403, 372, '塔什库尔干塔吉克自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3404, 373, '和田市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3405, 373, '和田县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3406, 373, '墨玉县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3407, 373, '皮山县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3408, 373, '洛浦县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3409, 373, '策勒县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3410, 373, '于田县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3411, 373, '民丰县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3412, 374, '伊宁市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3413, 374, '奎屯市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3414, 374, '霍尔果斯市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3415, 374, '伊宁县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3416, 374, '察布查尔锡伯自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3417, 374, '霍城县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3418, 374, '巩留县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3419, 374, '新源县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3420, 374, '昭苏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3421, 374, '特克斯县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3422, 374, '尼勒克县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3423, 375, '塔城市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3424, 375, '乌苏市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3425, 375, '沙湾市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3426, 375, '额敏县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3427, 375, '托里县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3428, 375, '裕民县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3429, 375, '和布克赛尔蒙古自治县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3430, 376, '阿勒泰市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3431, 376, '布尔津县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3432, 376, '富蕴县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3433, 376, '福海县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3434, 376, '哈巴河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3435, 376, '青河县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3436, 376, '吉木乃县', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3437, 377, '石河子市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3438, 377, '阿拉尔市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3439, 377, '图木舒克市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3440, 377, '五家渠市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3441, 377, '北屯市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3442, 377, '铁门关市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3443, 377, '双河市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3444, 377, '可克达拉市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3445, 377, '昆玉市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3446, 377, '胡杨河市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0),
(3447, 377, '新星市', 3, '', '', 0.0000000000, 0.0000000000, 0, 1, 1649559942, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_role`
--

DROP TABLE IF EXISTS `sxo_role`;
CREATE TABLE `sxo_role` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '角色组id',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '角色名称',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色组';

--
-- 转存表中的数据 `sxo_role`
--

INSERT INTO `sxo_role` (`id`, `name`, `is_enable`, `add_time`, `upd_time`) VALUES
(1, '超级管理员', 1, 1481350313, 0),
(13, '管理员', 1, 1484402362, 1705636415);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_role_plugins`
--

DROP TABLE IF EXISTS `sxo_role_plugins`;
CREATE TABLE `sxo_role_plugins` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '关联id',
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色id',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '插件名称',
  `plugins` char(60) NOT NULL DEFAULT '' COMMENT '唯一标记',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色与权限插件';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_role_power`
--

DROP TABLE IF EXISTS `sxo_role_power`;
CREATE TABLE `sxo_role_power` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '关联id',
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色id',
  `power_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '权限id',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色与权限管理';

--
-- 转存表中的数据 `sxo_role_power`
--

INSERT INTO `sxo_role_power` (`id`, `role_id`, `power_id`, `add_time`) VALUES
(5207, 13, 41, 1705636415),
(5208, 13, 339, 1705636415),
(5209, 13, 354, 1705636415),
(5210, 13, 482, 1705636415),
(5211, 13, 483, 1705636415),
(5212, 13, 81, 1705636415),
(5213, 13, 103, 1705636415),
(5214, 13, 104, 1705636415),
(5215, 13, 219, 1705636415),
(5216, 13, 199, 1705636415),
(5217, 13, 362, 1705636415),
(5218, 13, 1, 1705636415),
(5219, 13, 22, 1705636415),
(5220, 13, 405, 1705636415),
(5221, 13, 4, 1705636415),
(5222, 13, 406, 1705636415),
(5223, 13, 13, 1705636415),
(5224, 13, 126, 1705636415),
(5225, 13, 127, 1705636415),
(5226, 13, 404, 1705636415),
(5227, 13, 451, 1705636415),
(5228, 13, 455, 1705636415),
(5229, 13, 38, 1705636415),
(5230, 13, 39, 1705636415),
(5231, 13, 402, 1705636415),
(5232, 13, 201, 1705636415),
(5233, 13, 462, 1705636415),
(5234, 13, 467, 1705636415),
(5235, 13, 495, 1705636415),
(5236, 13, 356, 1705636415),
(5237, 13, 403, 1705636415),
(5238, 13, 418, 1705636415),
(5239, 13, 420, 1705636415),
(5240, 13, 421, 1705636415),
(5241, 13, 423, 1705636415),
(5242, 13, 489, 1705636415),
(5243, 13, 177, 1705636415),
(5244, 13, 178, 1705636415),
(5245, 13, 180, 1705636415),
(5246, 13, 267, 1705636415),
(5247, 13, 268, 1705636415),
(5248, 13, 269, 1705636415),
(5249, 13, 310, 1705636415),
(5250, 13, 400, 1705636415),
(5251, 13, 364, 1705636415),
(5252, 13, 401, 1705636415),
(5253, 13, 222, 1705636415),
(5254, 13, 223, 1705636415),
(5255, 13, 234, 1705636415),
(5256, 13, 238, 1705636415),
(5257, 13, 407, 1705636415),
(5258, 13, 239, 1705636415),
(5259, 13, 414, 1705636415),
(5260, 13, 244, 1705636415),
(5261, 13, 172, 1705636415),
(5262, 13, 175, 1705636415),
(5263, 13, 408, 1705636415),
(5264, 13, 193, 1705636415),
(5265, 13, 153, 1705636415),
(5266, 13, 156, 1705636415),
(5267, 13, 259, 1705636415),
(5268, 13, 443, 1705636415),
(5269, 13, 448, 1705636415),
(5270, 13, 477, 1705636415),
(5271, 13, 252, 1705636415),
(5272, 13, 249, 1705636415),
(5273, 13, 409, 1705636415),
(5274, 13, 253, 1705636415),
(5275, 13, 438, 1705636415),
(5276, 13, 425, 1705636415),
(5277, 13, 430, 1705636415),
(5278, 13, 431, 1705636415),
(5279, 13, 436, 1705636415),
(5280, 13, 319, 1705636415),
(5281, 13, 326, 1705636415),
(5282, 13, 314, 1705636415),
(5283, 13, 410, 1705636415),
(5284, 13, 376, 1705636415),
(5285, 13, 411, 1705636415),
(5286, 13, 333, 1705636415),
(5287, 13, 204, 1705636415),
(5288, 13, 205, 1705636415),
(5289, 13, 248, 1705636415),
(5290, 13, 210, 1705636415),
(5291, 13, 182, 1705636415),
(5292, 13, 183, 1705636415),
(5293, 13, 413, 1705636415),
(5294, 13, 185, 1705636415),
(5295, 13, 415, 1705636415),
(5296, 13, 449, 1705636415),
(5297, 13, 450, 1705636415),
(5298, 13, 372, 1705636415),
(5299, 13, 416, 1705636415),
(5300, 13, 186, 1705636415),
(5301, 13, 417, 1705636415),
(5302, 13, 340, 1705636415),
(5303, 13, 341, 1705636415),
(5304, 13, 343, 1705636415),
(5305, 13, 373, 1705636415),
(5306, 13, 498, 1705636415),
(5307, 13, 118, 1705636415),
(5308, 13, 119, 1705636415),
(5309, 13, 120, 1705636415),
(5310, 13, 121, 1705636415),
(5311, 13, 122, 1705636415),
(5312, 13, 331, 1705636415);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_screening_price`
--

DROP TABLE IF EXISTS `sxo_screening_price`;
CREATE TABLE `sxo_screening_price` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '分类id',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id',
  `name` char(30) NOT NULL COMMENT '名称',
  `min_price` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最小价格',
  `max_price` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最大价格',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '顺序',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='筛选价格';

--
-- 转存表中的数据 `sxo_screening_price`
--

INSERT INTO `sxo_screening_price` (`id`, `pid`, `name`, `min_price`, `max_price`, `is_enable`, `sort`, `add_time`, `upd_time`) VALUES
(7, 0, '100以下', 0, 100, 1, 0, 0, 1605776851),
(10, 0, '100-300', 100, 300, 1, 0, 0, 1605776847),
(16, 0, '300-600', 300, 600, 1, 0, 1482840545, 1536284623),
(17, 0, '600-1000', 600, 1000, 1, 0, 1482840557, 1690034274),
(18, 0, '1000-1500', 1000, 1500, 1, 0, 1482840577, 1690031032),
(24, 0, '1500-2000', 1500, 2000, 1, 0, 1483951541, 1536284667),
(25, 0, '2000-3000', 2000, 3000, 1, 0, 1535684676, 1536284683),
(26, 0, '3000-5000', 3000, 5000, 1, 0, 1535684688, 1536284701),
(27, 0, '5000-8000', 5000, 8000, 1, 0, 1535684701, 1536284736),
(28, 0, '8000-12000', 8000, 12000, 1, 0, 1535684707, 1536284767),
(29, 0, '12000-16000', 12000, 16000, 1, 0, 1535684729, 1536284787),
(30, 0, '16000-20000', 16000, 20000, 1, 0, 1535684745, 1536284805),
(31, 0, '20000以上', 20000, 0, 1, 0, 1535684797, 1536284828);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_search_history`
--

DROP TABLE IF EXISTS `sxo_search_history`;
CREATE TABLE `sxo_search_history` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `brand_ids` text DEFAULT NULL COMMENT '品牌id（json存储）',
  `category_ids` text DEFAULT NULL COMMENT '商品分类id（json存储）',
  `keywords` char(230) NOT NULL DEFAULT '' COMMENT '搜索关键字',
  `screening_price_values` text DEFAULT NULL COMMENT '价格区间（json存储）',
  `goods_params_values` text DEFAULT NULL COMMENT '商品参数/属性（json存储）',
  `goods_spec_values` text DEFAULT NULL COMMENT '商品规格（json存储）',
  `order_by_field` char(60) NOT NULL DEFAULT '' COMMENT '排序类型（字段名称）',
  `order_by_type` char(60) NOT NULL DEFAULT '' COMMENT '排序方式（asc, desc）',
  `search_result` text DEFAULT NULL COMMENT '搜索结果（json存储）',
  `ymd` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '日期 ymd',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='搜索日志';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_shortcut_menu`
--

DROP TABLE IF EXISTS `sxo_shortcut_menu`;
CREATE TABLE `sxo_shortcut_menu` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '自增id',
  `icon` char(255) NOT NULL DEFAULT '' COMMENT 'icon图标',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `menu` char(80) NOT NULL DEFAULT '' COMMENT '权限菜单',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='常用功能菜单';

--
-- 转存表中的数据 `sxo_shortcut_menu`
--

INSERT INTO `sxo_shortcut_menu` (`id`, `icon`, `name`, `menu`, `url`, `sort`, `add_time`, `upd_time`) VALUES
(1, '/static/upload/images/shortcutmenu/2024/01/19/1705643171275170.png', '网站设置', '103siteset', '', 0, 1705643147, 1705643172),
(2, '/static/upload/images/shortcutmenu/2024/01/19/1705643189818756.png', '商品管理', '39', '', 0, 1705643190, 0),
(3, '/static/upload/images/shortcutmenu/2024/01/19/1705643202921226.png', '订单管理', '178', '', 0, 1705643204, 0),
(4, '/static/upload/images/shortcutmenu/2024/01/19/1705643229569522.png', '订单售后', '364', '', 0, 1705643230, 0),
(5, '/static/upload/images/shortcutmenu/2024/01/19/1705643248100080.png', '商品管理', '39', '', 0, 1705643249, 0),
(6, '/static/upload/images/shortcutmenu/2024/01/19/1705643266349927.png', '应用管理', '341', '', 0, 1705643268, 0),
(7, '/static/upload/images/shortcutmenu/2024/01/19/1705643291349422.png', '分销', '', '/admin.php?s=plugins/index/pluginsname/distribution/pluginscontrol/admin/pluginsaction/index.html', 0, 1705643292, 0),
(8, '/static/upload/images/shortcutmenu/2024/01/19/1705643307781359.png', '优惠券', '', '/admin.php?s=plugins/index/pluginsname/coupon/pluginscontrol/admin/pluginsaction/index.html', 0, 1705643308, 0),
(9, '/static/upload/images/shortcutmenu/2024/01/19/1705643321962121.png', '限时秒杀', '', '/admin.php?s=plugins/index/pluginsname/seckill/pluginscontrol/admin/pluginsaction/index.html', 0, 1705643321, 1705643343);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_slide`
--

DROP TABLE IF EXISTS `sxo_slide`;
CREATE TABLE `sxo_slide` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `platform` char(255) NOT NULL DEFAULT 'pc' COMMENT '所属平台（pc PC网站, h5 H5手机网站, ios 苹果APP, android 安卓APP, alipay 支付宝小程序, weixin 微信小程序, baidu 百度小程序, toutiao 头条小程序, qq QQ小程序, kuaishou 快手小程序）',
  `event_type` tinyint(4) NOT NULL DEFAULT -1 COMMENT '事件类型（0 WEB页面, 1 内部页面(小程序或APP内部地址), 2 外部小程序(同一个主体下的小程序appid), 3 打开地图, 4 拨打电话）',
  `event_value` char(255) NOT NULL DEFAULT '' COMMENT '事件值',
  `images_url` char(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `describe` char(255) NOT NULL DEFAULT '' COMMENT '描述',
  `bg_color` char(30) NOT NULL DEFAULT '' COMMENT 'css背景色值',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `start_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '开始时间',
  `end_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '结束时间',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='轮播图片';

--
-- 转存表中的数据 `sxo_slide`
--

INSERT INTO `sxo_slide` (`id`, `platform`, `event_type`, `event_value`, `images_url`, `name`, `describe`, `bg_color`, `is_enable`, `sort`, `start_time`, `end_time`, `add_time`, `upd_time`) VALUES
(10, '[\"pc\"]', 0, 'https://shopxo.net/', '/static/upload/images/slide/2022/05/27/1653645476953808.jpg', '美酒', '', '#fbe7cf', 1, 0, 0, 0, 1533867066, 1686132962),
(11, '[\"pc\"]', 0, 'https://ask.shopxo.net/', '/static/upload/images/slide/2022/05/27/1653646660511503.jpg', '助力七夕', '', '#bbb7e9', 1, 0, 0, 0, 1533867114, 1686132980),
(22, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/goods-search/goods-search?keywords=包', '/static/upload/images/slide/2021/11/22/1637564231868321.png', '红色1', '', '#FDBAD0', 1, 0, 0, 0, 1533865442, 1690034269),
(23, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/goods-detail/goods-detail?goods_id=12', '/static/upload/images/slide/2021/11/22/1637564273444601.png', '绿色2', '', '#73F7C8', 1, 0, 0, 0, 1533866350, 1669303910),
(24, '[\"ios\",\"android\",\"h5\",\"weixin\",\"alipay\",\"baidu\",\"toutiao\",\"qq\",\"kuaishou\"]', 1, '/pages/goods-search/goods-search?keywords=iphone', '/static/upload/images/slide/2023/11/08/1699440411819595.png', '蓝色3', '', '#BBD8FC', 1, 0, 0, 0, 1533866891, 1699440418);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_sms_log`
--

DROP TABLE IF EXISTS `sxo_sms_log`;
CREATE TABLE `sxo_sms_log` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `platform` char(60) NOT NULL DEFAULT '' COMMENT '短信平台（aliyun 阿里云、等等...）',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态（0未发送，1已发送，2已失败）',
  `mobile` char(30) NOT NULL DEFAULT '' COMMENT '手机号码',
  `sign_name` char(60) NOT NULL DEFAULT '' COMMENT '短信签名',
  `template_value` char(255) NOT NULL DEFAULT '' COMMENT '短信模板',
  `template_var` mediumtext DEFAULT NULL COMMENT '短信模板变量（数组则json字符串存储）',
  `request_url` char(255) NOT NULL DEFAULT '' COMMENT '请求接口地址',
  `request_params` mediumtext DEFAULT NULL COMMENT '请求参数（数组则json字符串存储）',
  `response_data` mediumtext DEFAULT NULL COMMENT '响应参数（数组则json字符串存储）',
  `reason` char(255) NOT NULL DEFAULT '' COMMENT '失败原因',
  `tsc` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '耗时时间（单位秒）',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='短信日志';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_theme_data`
--

DROP TABLE IF EXISTS `sxo_theme_data`;
CREATE TABLE `sxo_theme_data` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `unique` char(128) NOT NULL DEFAULT '' COMMENT '唯一标识',
  `theme` char(80) NOT NULL DEFAULT '' COMMENT '主题',
  `view` tinyint(3) NOT NULL DEFAULT -1 COMMENT '页面（0首页、1商品搜索、2商品分类、3商品详情、4文章分类、5文章详情、6确认订单、7用户登录、8用户注册、9用户密码找回、10用户中心）',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '名称',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '数据类型（0单图文、1多图文、2视频、3商品、4文章、5商品组、6文章组）',
  `data` longtext DEFAULT NULL COMMENT '数据内容',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='主题数据';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_user`
--

DROP TABLE IF EXISTS `sxo_user`;
CREATE TABLE `sxo_user` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `number_code` char(30) NOT NULL DEFAULT '' COMMENT '会员码',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态（0正常, 1禁止发言, 2禁止登录）',
  `salt` char(32) NOT NULL DEFAULT '' COMMENT '配合密码加密串',
  `pwd` char(32) NOT NULL DEFAULT '' COMMENT '登录密码',
  `username` char(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` char(60) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `mobile` char(30) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` char(60) NOT NULL DEFAULT '' COMMENT '电子邮箱（最大长度60个字符）',
  `gender` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '性别（0保密，1女，2男）',
  `avatar` char(255) NOT NULL DEFAULT '' COMMENT '用户头像地址',
  `province` char(60) NOT NULL DEFAULT '' COMMENT '所在省',
  `city` char(60) NOT NULL DEFAULT '' COMMENT '所在市',
  `county` char(60) NOT NULL DEFAULT '' COMMENT '所在市',
  `birthday` int(11) NOT NULL DEFAULT 0 COMMENT '生日',
  `address` char(150) NOT NULL DEFAULT '' COMMENT '详细地址',
  `integral` int(11) NOT NULL DEFAULT 0 COMMENT '有效积分',
  `locking_integral` int(11) NOT NULL DEFAULT 0 COMMENT '锁定积分',
  `referrer` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '推荐人用户id',
  `is_delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已删除（0否, 大于0删除时间）',
  `is_logout_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已注销（0否, 大于0删除时间）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户';

--
-- 转存表中的数据 `sxo_user`
--

INSERT INTO `sxo_user` (`id`, `number_code`, `status`, `salt`, `pwd`, `username`, `nickname`, `mobile`, `email`, `gender`, `avatar`, `province`, `city`, `county`, `birthday`, `address`, `integral`, `locking_integral`, `referrer`, `is_delete_time`, `is_logout_time`, `add_time`, `upd_time`) VALUES
(1, '88884302038481', 0, '220437', 'f20bca418df0f2f42f6391a8899b5d00', 'test', '', '', '', 0, '', '', '', '', 0, '', 0, 0, 0, 0, 0, 1717947021, 1717947021);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_user_address`
--

DROP TABLE IF EXISTS `sxo_user_address`;
CREATE TABLE `sxo_user_address` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '站点id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `alias` char(60) NOT NULL DEFAULT '' COMMENT '别名',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '姓名',
  `tel` char(15) NOT NULL DEFAULT '' COMMENT '电话',
  `province` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所在省',
  `city` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所在市',
  `county` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所在县/区',
  `address` char(80) NOT NULL DEFAULT '' COMMENT '详细地址',
  `lng` decimal(13,10) NOT NULL DEFAULT 0.0000000000 COMMENT '经度',
  `lat` decimal(13,10) NOT NULL DEFAULT 0.0000000000 COMMENT '纬度',
  `idcard_name` char(60) NOT NULL DEFAULT '' COMMENT '身份证姓名',
  `idcard_number` char(30) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `idcard_front` char(255) NOT NULL DEFAULT '' COMMENT '身份证人像面图片',
  `idcard_back` char(255) NOT NULL DEFAULT '' COMMENT '身份证国微面图片',
  `is_default` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否默认地址（0否, 1是）',
  `is_delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除（0否，1删除时间戳）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户地址';

--
-- 转存表中的数据 `sxo_user_address`
--

INSERT INTO `sxo_user_address` (`id`, `user_id`, `alias`, `name`, `tel`, `province`, `city`, `county`, `address`, `lng`, `lat`, `idcard_name`, `idcard_number`, `idcard_front`, `idcard_back`, `is_default`, `is_delete_time`, `add_time`, `upd_time`) VALUES
(1, 1, '', 'hongchunhua', '18813067855', 1, 36, 458, 'fsdff', 0.0000000000, 0.0000000000, '', '', '', '', 1, 0, 1717947070, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_user_integral_log`
--

DROP TABLE IF EXISTS `sxo_user_integral_log`;
CREATE TABLE `sxo_user_integral_log` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作类型（0减少, 1增加）',
  `original_integral` int(11) NOT NULL DEFAULT 0 COMMENT '原始积分',
  `new_integral` int(11) NOT NULL DEFAULT 0 COMMENT '最新积分',
  `operation_integral` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作积分',
  `msg` text DEFAULT NULL COMMENT '操作原因',
  `operation_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作人员id',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户积分日志';

-- --------------------------------------------------------

--
-- 表的结构 `sxo_user_platform`
--

DROP TABLE IF EXISTS `sxo_user_platform`;
CREATE TABLE `sxo_user_platform` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '自增id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `system_type` char(60) NOT NULL DEFAULT 'default' COMMENT '系统类型（默认 default, 其他按照SYSTEM_TYPE常量类型）',
  `platform` char(30) NOT NULL DEFAULT 'pc' COMMENT '所属平台（pc PC网站, h5 H5手机网站, ios 苹果APP, android 安卓APP, alipay 支付宝小程序, weixin 微信小程序, baidu 百度小程序, toutiao 头条小程序, qq QQ小程序, kuaishou 快手小程序）',
  `token` char(60) NOT NULL DEFAULT '' COMMENT 'token',
  `alipay_openid` char(60) NOT NULL DEFAULT '' COMMENT '支付宝openid',
  `weixin_openid` char(60) NOT NULL DEFAULT '' COMMENT '微信openid',
  `weixin_unionid` char(60) NOT NULL DEFAULT '' COMMENT '微信unionid',
  `weixin_web_openid` char(60) NOT NULL DEFAULT '' COMMENT '微信web用户openid',
  `baidu_openid` char(60) NOT NULL DEFAULT '' COMMENT '百度openid',
  `toutiao_openid` char(60) NOT NULL DEFAULT '' COMMENT '百度openid',
  `toutiao_unionid` char(60) NOT NULL DEFAULT '' COMMENT '头条unionid',
  `qq_openid` char(60) NOT NULL DEFAULT '' COMMENT 'QQopenid',
  `qq_unionid` char(60) NOT NULL DEFAULT '' COMMENT 'QQunionid',
  `kuaishou_openid` char(60) NOT NULL DEFAULT '' COMMENT '快手openid',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户平台';

--
-- 转存表中的数据 `sxo_user_platform`
--

INSERT INTO `sxo_user_platform` (`id`, `user_id`, `system_type`, `platform`, `token`, `alipay_openid`, `weixin_openid`, `weixin_unionid`, `weixin_web_openid`, `baidu_openid`, `toutiao_openid`, `toutiao_unionid`, `qq_openid`, `qq_unionid`, `kuaishou_openid`, `add_time`, `upd_time`) VALUES
(1, 1, 'default', 'weixin', '4e09b76066ca530b167ed32f55a763a3', '', 'oB6US5SNOngn196qzb3lagEBTh6w', 'o1Ang5xo5tiJD-l7Y1kY7-hT6emk', '', '', '', '', '', '', '', 1699451379, 1699456405),
(2, 1, 'default', 'pc', '', '', '', '', '', '', '', '', '', '', '', 1717947021, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_warehouse`
--

DROP TABLE IF EXISTS `sxo_warehouse`;
CREATE TABLE `sxo_warehouse` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `alias` char(60) NOT NULL DEFAULT '' COMMENT '别名',
  `level` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '权重（数字越大权重越高）',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `contacts_name` char(60) NOT NULL DEFAULT '' COMMENT '联系人姓名',
  `contacts_tel` char(15) NOT NULL DEFAULT '' COMMENT '联系电话',
  `province` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所在省',
  `city` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所在市',
  `county` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所在县/区',
  `address` char(80) NOT NULL DEFAULT '' COMMENT '详细地址',
  `lng` decimal(13,10) NOT NULL DEFAULT 0.0000000000 COMMENT '经度',
  `lat` decimal(13,10) NOT NULL DEFAULT 0.0000000000 COMMENT '纬度',
  `is_default` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否默认（0否, 1是）',
  `is_delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除（0否，大于0删除时间戳）',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='仓库';

--
-- 转存表中的数据 `sxo_warehouse`
--

INSERT INTO `sxo_warehouse` (`id`, `name`, `alias`, `level`, `is_enable`, `contacts_name`, `contacts_tel`, `province`, `city`, `county`, `address`, `lng`, `lat`, `is_default`, `is_delete_time`, `add_time`, `upd_time`) VALUES
(1, '默认仓库', '上海仓', 1, 1, 'Devil', '17666666655', 9, 152, 1896, '浦江科技广场', 121.5140560000, 31.1023570000, 1, 0, 1594207406, 1693126392);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_warehouse_goods`
--

DROP TABLE IF EXISTS `sxo_warehouse_goods`;
CREATE TABLE `sxo_warehouse_goods` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `warehouse_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '仓库id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `is_enable` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用（0否，1是）',
  `inventory` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '总库存',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `upd_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='仓库商品';

--
-- 转存表中的数据 `sxo_warehouse_goods`
--

INSERT INTO `sxo_warehouse_goods` (`id`, `warehouse_id`, `goods_id`, `is_enable`, `inventory`, `add_time`, `upd_time`) VALUES
(4, 1, 9, 1, 10666656, 1626018145, 1693237071),
(5, 1, 8, 1, 75, 1626018145, 1693126336),
(6, 1, 3, 1, 888888, 1626018146, 1693284931),
(7, 1, 4, 1, 878, 1626018147, 1693237649),
(8, 1, 5, 1, 877, 1626018148, 1693237221),
(9, 1, 6, 1, 877, 1626018148, 1693285023),
(10, 1, 7, 1, 877, 1626018149, 1693234922),
(11, 1, 2, 1, 266664, 1626018150, 1693284683),
(12, 1, 1, 1, 1517, 1626018150, 1691832446),
(35, 1, 12, 1, 2000, 1650639953, 1693237967),
(70, 1, 32, 1, 1517, 1654478441, 1693237177),
(142, 1, 74, 1, 60, 1680668622, 1693238627),
(158, 1, 110, 1, 88888, 1693124711, 1693234972),
(159, 1, 109, 1, 88888, 1693124728, 1693235270),
(160, 1, 108, 1, 88888, 1693124737, 1693235460),
(161, 1, 107, 1, 88888, 1693124747, 1693235615),
(162, 1, 106, 1, 88888, 1693124753, 1693235979),
(163, 1, 105, 1, 88888, 1693124762, 1693235790),
(164, 1, 104, 1, 88888, 1693124768, 1693236042),
(165, 1, 103, 1, 88888, 1693124774, 1693236446),
(166, 1, 102, 1, 88888, 1693124781, 1693235663),
(167, 1, 101, 1, 88888, 1693124787, 1693235925),
(168, 1, 100, 1, 88888, 1693124792, 1693236598),
(169, 1, 99, 1, 88888, 1693124799, 1693236668),
(170, 1, 98, 1, 88888, 1693124806, 1693235317),
(171, 1, 10, 1, 88888, 1693124811, 1693238016),
(172, 1, 11, 1, 2666664, 1693126267, 1693237903),
(173, 1, 25, 1, 888888, 1693126268, 1693237146);

-- --------------------------------------------------------

--
-- 表的结构 `sxo_warehouse_goods_spec`
--

DROP TABLE IF EXISTS `sxo_warehouse_goods_spec`;
CREATE TABLE `sxo_warehouse_goods_spec` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增id',
  `warehouse_goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '仓库商品id',
  `warehouse_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '仓库id',
  `goods_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品id',
  `md5_key` char(32) NOT NULL DEFAULT '' COMMENT 'md5key值',
  `spec` text DEFAULT NULL COMMENT '规格值',
  `inventory` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '库存',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='仓库商品规格';

--
-- 转存表中的数据 `sxo_warehouse_goods_spec`
--

INSERT INTO `sxo_warehouse_goods_spec` (`id`, `warehouse_goods_id`, `warehouse_id`, `goods_id`, `md5_key`, `spec`, `inventory`, `add_time`) VALUES
(2813, 12, 1, 1, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 1517, 1691832446),
(2952, 5, 1, 8, '52636511861a0e08cbe6a0eb1c27d816', '[{\"type\":\"颜色\",\"value\":\"红色\"}]', 4, 1693126336),
(2953, 5, 1, 8, '9c9aabab3f7627ff4bb224b2738b26ea', '[{\"type\":\"颜色\",\"value\":\"蓝色\"}]', 71, 1693126336),
(2981, 10, 1, 7, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 877, 1693234922),
(2982, 158, 1, 110, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693234972),
(2983, 159, 1, 109, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693235270),
(2984, 170, 1, 98, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693235317),
(2985, 160, 1, 108, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693235460),
(2986, 161, 1, 107, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693235615),
(2987, 166, 1, 102, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693235663),
(2989, 163, 1, 105, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693235790),
(2990, 167, 1, 101, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693235925),
(2991, 162, 1, 106, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693235979),
(2992, 164, 1, 104, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693236042),
(2993, 165, 1, 103, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693236446),
(2994, 168, 1, 100, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693236598),
(2995, 169, 1, 99, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693236668),
(2996, 4, 1, 9, 'd15560adc3d2ddf952ec40d874566fc0', '[{\"type\":\"颜色\",\"value\":\"白色\"},{\"type\":\"尺码\",\"value\":\"S\"}]', 888888, 1693237071),
(2997, 4, 1, 9, '2767f06d6a715b3915ca3c5443efc170', '[{\"type\":\"颜色\",\"value\":\"白色\"},{\"type\":\"尺码\",\"value\":\"M\"}]', 888888, 1693237071),
(2998, 4, 1, 9, 'd0d686188b3e2b0b7ae12040fd508f03', '[{\"type\":\"颜色\",\"value\":\"白色\"},{\"type\":\"尺码\",\"value\":\"L\"}]', 888888, 1693237071),
(2999, 4, 1, 9, '910613b82f64796470c2243f8e306797', '[{\"type\":\"颜色\",\"value\":\"白色\"},{\"type\":\"尺码\",\"value\":\"XL\"}]', 888888, 1693237071),
(3000, 4, 1, 9, '4acc2580a89270113477b4b8466ecef9', '[{\"type\":\"颜色\",\"value\":\"粉色\"},{\"type\":\"尺码\",\"value\":\"S\"}]', 888888, 1693237071),
(3001, 4, 1, 9, 'b0935dae40102d2bcbd588fc50a6a118', '[{\"type\":\"颜色\",\"value\":\"粉色\"},{\"type\":\"尺码\",\"value\":\"M\"}]', 888888, 1693237071),
(3002, 4, 1, 9, '5b51b52dde87b3608c97ff80a2215275', '[{\"type\":\"颜色\",\"value\":\"粉色\"},{\"type\":\"尺码\",\"value\":\"L\"}]', 888888, 1693237071),
(3003, 4, 1, 9, 'f3ad50abb66b28899bf085da136c1e5a', '[{\"type\":\"颜色\",\"value\":\"粉色\"},{\"type\":\"尺码\",\"value\":\"XL\"}]', 888888, 1693237071),
(3004, 4, 1, 9, '2f3924a3bf7c8491af2dad598b54764f', '[{\"type\":\"颜色\",\"value\":\"黑色\"},{\"type\":\"尺码\",\"value\":\"S\"}]', 888888, 1693237071),
(3005, 4, 1, 9, '0b21a799bb1659287fd9cd49ee7f6e5c', '[{\"type\":\"颜色\",\"value\":\"黑色\"},{\"type\":\"尺码\",\"value\":\"M\"}]', 888888, 1693237071),
(3006, 4, 1, 9, '237d77a5504afa496f192524501af2cc', '[{\"type\":\"颜色\",\"value\":\"黑色\"},{\"type\":\"尺码\",\"value\":\"L\"}]', 888888, 1693237071),
(3007, 4, 1, 9, '5f077e081d727adcb617a413f038d043', '[{\"type\":\"颜色\",\"value\":\"黑色\"},{\"type\":\"尺码\",\"value\":\"XL\"}]', 888888, 1693237071),
(3008, 173, 1, 25, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 888888, 1693237146),
(3009, 70, 1, 32, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 1517, 1693237177),
(3010, 8, 1, 5, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 877, 1693237221),
(3011, 7, 1, 4, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 878, 1693237649),
(3015, 172, 1, 11, '69691c7bdcc3ce6d5d8a1361f22d04ac', '[{\"type\":\"尺码\",\"value\":\"M\"}]', 888888, 1693237903),
(3016, 172, 1, 11, 'd20caec3b48a1eef164cb4ca81ba2587', '[{\"type\":\"尺码\",\"value\":\"L\"}]', 888888, 1693237903),
(3017, 172, 1, 11, 'a7a4ccc5e1a068d87f4965e014329201', '[{\"type\":\"尺码\",\"value\":\"XL\"}]', 888888, 1693237903),
(3018, 35, 1, 12, '4f40eff650a2fc7768605aa9c16ef67d', '[{\"type\":\"颜色\",\"value\":\"粉色\"},{\"type\":\"尺码\",\"value\":\"S+S\"}]', 500, 1693237967),
(3019, 35, 1, 12, 'f5c75bc99305a664f1d92d572ee78289', '[{\"type\":\"颜色\",\"value\":\"粉色\"},{\"type\":\"尺码\",\"value\":\"M+M\"}]', 500, 1693237967),
(3020, 35, 1, 12, 'eda0100ccdfa2942729825effc96f571', '[{\"type\":\"颜色\",\"value\":\"白色\"},{\"type\":\"尺码\",\"value\":\"S+S\"}]', 500, 1693237967),
(3021, 35, 1, 12, 'ec0bf37ae952cd7c60f2cdbb5153086a', '[{\"type\":\"颜色\",\"value\":\"白色\"},{\"type\":\"尺码\",\"value\":\"M+M\"}]', 500, 1693237967),
(3022, 171, 1, 10, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 88888, 1693238016),
(3023, 142, 1, 74, '2fc96b2704b05d6a9f299c442573ee77', '[{\"type\":\"颜色\",\"value\":\"白色\"}]', 20, 1693238627),
(3024, 142, 1, 74, '97542386a90fa65e896c4b8459fd6bc0', '[{\"type\":\"颜色\",\"value\":\"粉色\"}]', 20, 1693238627),
(3025, 142, 1, 74, '9d2d1f62ae2c59a675bf6827a2394378', '[{\"type\":\"颜色\",\"value\":\"黑色\"}]', 20, 1693238627),
(3026, 11, 1, 2, '224734fbe895debc3c7999a2f157f05d', '[{\"type\":\"套餐\",\"value\":\"套餐二\"},{\"type\":\"颜色\",\"value\":\"金色\"},{\"type\":\"容量\",\"value\":\"32G\"}]', 88888, 1693284683),
(3027, 11, 1, 2, '35c55bca127aa2f3c77fecfdd802c18f', '[{\"type\":\"套餐\",\"value\":\"套餐二\"},{\"type\":\"颜色\",\"value\":\"金色\"},{\"type\":\"容量\",\"value\":\"128G\"}]', 88888, 1693284683),
(3028, 11, 1, 2, '3d09a0554e685e5de759819564ca592b', '[{\"type\":\"套餐\",\"value\":\"套餐二\"},{\"type\":\"颜色\",\"value\":\"银色\"},{\"type\":\"容量\",\"value\":\"64G\"}]', 88888, 1693284683),
(3029, 6, 1, 3, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 888888, 1693284931),
(3030, 9, 1, 6, 'c21f969b5f03d33d43e04f8f136e7682', '[]', 877, 1693285023);

--
-- 转储表的索引
--

--
-- 表的索引 `sxo_admin`
--
ALTER TABLE `sxo_admin`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `sxo_app_center_nav`
--
ALTER TABLE `sxo_app_center_nav`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE;

--
-- 表的索引 `sxo_app_home_nav`
--
ALTER TABLE `sxo_app_home_nav`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE;

--
-- 表的索引 `sxo_article`
--
ALTER TABLE `sxo_article`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `title` (`title`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE,
  ADD KEY `access_count` (`access_count`) USING BTREE,
  ADD KEY `image_count` (`images_count`) USING BTREE,
  ADD KEY `article_category_id` (`article_category_id`) USING BTREE;

--
-- 表的索引 `sxo_article_category`
--
ALTER TABLE `sxo_article_category`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE;

--
-- 表的索引 `sxo_attachment`
--
ALTER TABLE `sxo_attachment`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `path_type` (`path_type`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE;

--
-- 表的索引 `sxo_brand`
--
ALTER TABLE `sxo_brand`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE;

--
-- 表的索引 `sxo_brand_category`
--
ALTER TABLE `sxo_brand_category`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE;

--
-- 表的索引 `sxo_brand_category_join`
--
ALTER TABLE `sxo_brand_category_join`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `brand_id` (`brand_id`) USING BTREE,
  ADD KEY `brand_category_id` (`brand_category_id`) USING BTREE;

--
-- 表的索引 `sxo_cart`
--
ALTER TABLE `sxo_cart`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE,
  ADD KEY `title` (`title`) USING BTREE,
  ADD KEY `stock` (`stock`) USING BTREE;

--
-- 表的索引 `sxo_config`
--
ALTER TABLE `sxo_config`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `only_tag` (`only_tag`) USING BTREE;

--
-- 表的索引 `sxo_custom_view`
--
ALTER TABLE `sxo_custom_view`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `title` (`name`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE,
  ADD KEY `access_count` (`access_count`) USING BTREE;

--
-- 表的索引 `sxo_design`
--
ALTER TABLE `sxo_design`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE,
  ADD KEY `is_header` (`is_header`) USING BTREE,
  ADD KEY `is_footer` (`is_footer`) USING BTREE;

--
-- 表的索引 `sxo_email_log`
--
ALTER TABLE `sxo_email_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `smtp_host` (`smtp_host`) USING BTREE,
  ADD KEY `smtp_name` (`smtp_name`) USING BTREE,
  ADD KEY `smtp_account` (`smtp_account`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `email` (`email`) USING BTREE,
  ADD KEY `tsc` (`tsc`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE;

--
-- 表的索引 `sxo_express`
--
ALTER TABLE `sxo_express`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE;

--
-- 表的索引 `sxo_form_table_user_fields`
--
ALTER TABLE `sxo_form_table_user_fields`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `user_type` (`user_type`) USING BTREE,
  ADD KEY `md5_key` (`md5_key`) USING BTREE;

--
-- 表的索引 `sxo_goods`
--
ALTER TABLE `sxo_goods`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `title` (`title`) USING BTREE,
  ADD KEY `access_count` (`access_count`) USING BTREE,
  ADD KEY `photo_count` (`photo_count`) USING BTREE,
  ADD KEY `is_shelves` (`is_shelves`) USING BTREE,
  ADD KEY `brand_id` (`brand_id`) USING BTREE,
  ADD KEY `sales_count` (`sales_count`) USING BTREE,
  ADD KEY `is_delete_time` (`is_delete_time`) USING BTREE;

--
-- 表的索引 `sxo_goods_browse`
--
ALTER TABLE `sxo_goods_browse`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `sxo_goods_category`
--
ALTER TABLE `sxo_goods_category`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE;

--
-- 表的索引 `sxo_goods_category_join`
--
ALTER TABLE `sxo_goods_category_join`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE,
  ADD KEY `category_id` (`category_id`) USING BTREE;

--
-- 表的索引 `sxo_goods_comments`
--
ALTER TABLE `sxo_goods_comments`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE;

--
-- 表的索引 `sxo_goods_content_app`
--
ALTER TABLE `sxo_goods_content_app`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE;

--
-- 表的索引 `sxo_goods_favor`
--
ALTER TABLE `sxo_goods_favor`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `sxo_goods_give_integral_log`
--
ALTER TABLE `sxo_goods_give_integral_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `order_detail_id` (`order_detail_id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `rate` (`rate`) USING BTREE,
  ADD KEY `integral` (`integral`) USING BTREE;

--
-- 表的索引 `sxo_goods_params`
--
ALTER TABLE `sxo_goods_params`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE,
  ADD KEY `value` (`value`) USING BTREE;

--
-- 表的索引 `sxo_goods_params_template`
--
ALTER TABLE `sxo_goods_params_template`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE,
  ADD KEY `config_count` (`config_count`) USING BTREE,
  ADD KEY `category_id` (`category_id`) USING BTREE;

--
-- 表的索引 `sxo_goods_params_template_config`
--
ALTER TABLE `sxo_goods_params_template_config`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `template_id` (`template_id`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE;

--
-- 表的索引 `sxo_goods_photo`
--
ALTER TABLE `sxo_goods_photo`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE,
  ADD KEY `is_show` (`is_show`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE;

--
-- 表的索引 `sxo_goods_spec_base`
--
ALTER TABLE `sxo_goods_spec_base`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE;

--
-- 表的索引 `sxo_goods_spec_template`
--
ALTER TABLE `sxo_goods_spec_template`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `category_id` (`category_id`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE;

--
-- 表的索引 `sxo_goods_spec_type`
--
ALTER TABLE `sxo_goods_spec_type`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE;

--
-- 表的索引 `sxo_goods_spec_value`
--
ALTER TABLE `sxo_goods_spec_value`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE,
  ADD KEY `goods_spec_base_id` (`goods_spec_base_id`) USING BTREE;

--
-- 表的索引 `sxo_layout`
--
ALTER TABLE `sxo_layout`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE;

--
-- 表的索引 `sxo_link`
--
ALTER TABLE `sxo_link`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE;

--
-- 表的索引 `sxo_message`
--
ALTER TABLE `sxo_message`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `system_type` (`system_type`) USING BTREE;

--
-- 表的索引 `sxo_navigation`
--
ALTER TABLE `sxo_navigation`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `is_show` (`is_show`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE,
  ADD KEY `nav_type` (`nav_type`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE;

--
-- 表的索引 `sxo_order`
--
ALTER TABLE `sxo_order`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `order_no` (`order_no`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `pay_status` (`pay_status`) USING BTREE,
  ADD KEY `warehouse_id` (`warehouse_id`) USING BTREE,
  ADD KEY `system_type` (`system_type`) USING BTREE;

--
-- 表的索引 `sxo_order_address`
--
ALTER TABLE `sxo_order_address`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- 表的索引 `sxo_order_aftersale`
--
ALTER TABLE `sxo_order_aftersale`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `system_type` (`system_type`) USING BTREE;

--
-- 表的索引 `sxo_order_aftersale_status_history`
--
ALTER TABLE `sxo_order_aftersale_status_history`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `order_aftersale_id` (`order_aftersale_id`) USING BTREE,
  ADD KEY `original_status` (`original_status`) USING BTREE,
  ADD KEY `new_status` (`new_status`) USING BTREE;

--
-- 表的索引 `sxo_order_currency`
--
ALTER TABLE `sxo_order_currency`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `currency_name` (`currency_name`) USING BTREE,
  ADD KEY `currency_code` (`currency_code`) USING BTREE,
  ADD KEY `currency_rate` (`currency_rate`) USING BTREE;

--
-- 表的索引 `sxo_order_detail`
--
ALTER TABLE `sxo_order_detail`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE;

--
-- 表的索引 `sxo_order_express`
--
ALTER TABLE `sxo_order_express`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE;

--
-- 表的索引 `sxo_order_extraction_code`
--
ALTER TABLE `sxo_order_extraction_code`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- 表的索引 `sxo_order_fictitious_value`
--
ALTER TABLE `sxo_order_fictitious_value`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `order_detail_id` (`order_detail_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- 表的索引 `sxo_order_goods_inventory_log`
--
ALTER TABLE `sxo_order_goods_inventory_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE,
  ADD KEY `order_status` (`order_status`) USING BTREE,
  ADD KEY `order_detail_id` (`order_detail_id`) USING BTREE;

--
-- 表的索引 `sxo_order_status_history`
--
ALTER TABLE `sxo_order_status_history`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `original_status` (`original_status`) USING BTREE,
  ADD KEY `new_status` (`new_status`) USING BTREE;

--
-- 表的索引 `sxo_payment`
--
ALTER TABLE `sxo_payment`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `payment` (`payment`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE;

--
-- 表的索引 `sxo_pay_log`
--
ALTER TABLE `sxo_pay_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `payment` (`payment`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `business_type` (`business_type`) USING BTREE,
  ADD KEY `total_price` (`total_price`) USING BTREE,
  ADD KEY `pay_price` (`pay_price`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `pay_time` (`pay_time`) USING BTREE,
  ADD KEY `close_time` (`close_time`) USING BTREE;

--
-- 表的索引 `sxo_pay_log_value`
--
ALTER TABLE `sxo_pay_log_value`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `pay_log_id` (`pay_log_id`) USING BTREE,
  ADD KEY `business_id` (`business_id`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE;

--
-- 表的索引 `sxo_pay_request_log`
--
ALTER TABLE `sxo_pay_request_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `business_type` (`business_type`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE;

--
-- 表的索引 `sxo_plugins`
--
ALTER TABLE `sxo_plugins`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `plugins` (`plugins`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE;

--
-- 表的索引 `sxo_plugins_category`
--
ALTER TABLE `sxo_plugins_category`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE;

--
-- 表的索引 `sxo_power`
--
ALTER TABLE `sxo_power`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `sxo_quick_nav`
--
ALTER TABLE `sxo_quick_nav`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE;

--
-- 表的索引 `sxo_refund_log`
--
ALTER TABLE `sxo_refund_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `payment` (`payment`) USING BTREE,
  ADD KEY `business_id` (`business_id`) USING BTREE,
  ADD KEY `business_type` (`business_type`) USING BTREE,
  ADD KEY `pay_id` (`pay_id`) USING BTREE;

--
-- 表的索引 `sxo_region`
--
ALTER TABLE `sxo_region`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE,
  ADD KEY `level` (`level`) USING BTREE,
  ADD KEY `letters` (`letters`) USING BTREE,
  ADD KEY `code` (`code`) USING BTREE;

--
-- 表的索引 `sxo_role`
--
ALTER TABLE `sxo_role`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `sxo_role_plugins`
--
ALTER TABLE `sxo_role_plugins`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `role_id` (`role_id`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE,
  ADD KEY `plugins` (`plugins`) USING BTREE;

--
-- 表的索引 `sxo_role_power`
--
ALTER TABLE `sxo_role_power`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `role_id` (`role_id`) USING BTREE,
  ADD KEY `power_id` (`power_id`) USING BTREE;

--
-- 表的索引 `sxo_screening_price`
--
ALTER TABLE `sxo_screening_price`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE;

--
-- 表的索引 `sxo_search_history`
--
ALTER TABLE `sxo_search_history`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `sxo_shortcut_menu`
--
ALTER TABLE `sxo_shortcut_menu`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `menu` (`menu`) USING BTREE;

--
-- 表的索引 `sxo_slide`
--
ALTER TABLE `sxo_slide`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE;

--
-- 表的索引 `sxo_sms_log`
--
ALTER TABLE `sxo_sms_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `platform` (`platform`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `mobile` (`mobile`) USING BTREE,
  ADD KEY `tsc` (`tsc`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE;

--
-- 表的索引 `sxo_theme_data`
--
ALTER TABLE `sxo_theme_data`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `unique` (`unique`) USING BTREE,
  ADD KEY `theme` (`theme`) USING BTREE,
  ADD KEY `view` (`view`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE;

--
-- 表的索引 `sxo_user`
--
ALTER TABLE `sxo_user`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `mobile` (`mobile`) USING BTREE,
  ADD KEY `username` (`username`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `is_delete_time` (`is_delete_time`) USING BTREE,
  ADD KEY `is_logout_time` (`is_logout_time`) USING BTREE,
  ADD KEY `email` (`email`) USING BTREE;

--
-- 表的索引 `sxo_user_address`
--
ALTER TABLE `sxo_user_address`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `is_delete_time` (`is_delete_time`) USING BTREE;

--
-- 表的索引 `sxo_user_integral_log`
--
ALTER TABLE `sxo_user_integral_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE;

--
-- 表的索引 `sxo_user_platform`
--
ALTER TABLE `sxo_user_platform`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `system_type` (`system_type`) USING BTREE,
  ADD KEY `platform` (`platform`) USING BTREE,
  ADD KEY `token` (`token`) USING BTREE,
  ADD KEY `alipay_openid` (`alipay_openid`) USING BTREE,
  ADD KEY `weixin_openid` (`weixin_openid`) USING BTREE,
  ADD KEY `weixin_unionid` (`weixin_unionid`) USING BTREE,
  ADD KEY `weixin_web_openid` (`weixin_web_openid`) USING BTREE,
  ADD KEY `baidu_openid` (`baidu_openid`) USING BTREE,
  ADD KEY `toutiao_openid` (`toutiao_openid`) USING BTREE,
  ADD KEY `toutiao_unionid` (`toutiao_unionid`) USING BTREE,
  ADD KEY `qq_openid` (`qq_openid`) USING BTREE,
  ADD KEY `qq_unionid` (`qq_unionid`) USING BTREE,
  ADD KEY `kuaishou_openid` (`kuaishou_openid`) USING BTREE;

--
-- 表的索引 `sxo_warehouse`
--
ALTER TABLE `sxo_warehouse`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE,
  ADD KEY `level` (`level`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE,
  ADD KEY `is_default` (`is_default`) USING BTREE,
  ADD KEY `is_delete_time` (`is_delete_time`) USING BTREE;

--
-- 表的索引 `sxo_warehouse_goods`
--
ALTER TABLE `sxo_warehouse_goods`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `warehouse_id` (`warehouse_id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE,
  ADD KEY `is_enable` (`is_enable`) USING BTREE,
  ADD KEY `inventory` (`inventory`) USING BTREE;

--
-- 表的索引 `sxo_warehouse_goods_spec`
--
ALTER TABLE `sxo_warehouse_goods_spec`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `warehouse_goods_id` (`warehouse_goods_id`) USING BTREE,
  ADD KEY `warehouse_id` (`warehouse_id`) USING BTREE,
  ADD KEY `goods_id` (`goods_id`) USING BTREE,
  ADD KEY `md5_key` (`md5_key`) USING BTREE,
  ADD KEY `inventory` (`inventory`) USING BTREE;

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `sxo_admin`
--
ALTER TABLE `sxo_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理员id', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `sxo_app_center_nav`
--
ALTER TABLE `sxo_app_center_nav`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=73;

--
-- 使用表AUTO_INCREMENT `sxo_app_home_nav`
--
ALTER TABLE `sxo_app_home_nav`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=92;

--
-- 使用表AUTO_INCREMENT `sxo_article`
--
ALTER TABLE `sxo_article`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=30;

--
-- 使用表AUTO_INCREMENT `sxo_article_category`
--
ALTER TABLE `sxo_article_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类id', AUTO_INCREMENT=25;

--
-- 使用表AUTO_INCREMENT `sxo_attachment`
--
ALTER TABLE `sxo_attachment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=2549;

--
-- 使用表AUTO_INCREMENT `sxo_brand`
--
ALTER TABLE `sxo_brand`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=32;

--
-- 使用表AUTO_INCREMENT `sxo_brand_category`
--
ALTER TABLE `sxo_brand_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类id', AUTO_INCREMENT=33;

--
-- 使用表AUTO_INCREMENT `sxo_brand_category_join`
--
ALTER TABLE `sxo_brand_category_join`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=86;

--
-- 使用表AUTO_INCREMENT `sxo_cart`
--
ALTER TABLE `sxo_cart`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_config`
--
ALTER TABLE `sxo_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '基本设置id', AUTO_INCREMENT=299;

--
-- 使用表AUTO_INCREMENT `sxo_custom_view`
--
ALTER TABLE `sxo_custom_view`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_design`
--
ALTER TABLE `sxo_design`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_email_log`
--
ALTER TABLE `sxo_email_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_express`
--
ALTER TABLE `sxo_express`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=15;

--
-- 使用表AUTO_INCREMENT `sxo_form_table_user_fields`
--
ALTER TABLE `sxo_form_table_user_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `sxo_goods`
--
ALTER TABLE `sxo_goods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=111;

--
-- 使用表AUTO_INCREMENT `sxo_goods_browse`
--
ALTER TABLE `sxo_goods_browse`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `sxo_goods_category`
--
ALTER TABLE `sxo_goods_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=911;

--
-- 使用表AUTO_INCREMENT `sxo_goods_category_join`
--
ALTER TABLE `sxo_goods_category_join`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=1913;

--
-- 使用表AUTO_INCREMENT `sxo_goods_comments`
--
ALTER TABLE `sxo_goods_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_goods_content_app`
--
ALTER TABLE `sxo_goods_content_app`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_goods_favor`
--
ALTER TABLE `sxo_goods_favor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_goods_give_integral_log`
--
ALTER TABLE `sxo_goods_give_integral_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_goods_params`
--
ALTER TABLE `sxo_goods_params`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=1536;

--
-- 使用表AUTO_INCREMENT `sxo_goods_params_template`
--
ALTER TABLE `sxo_goods_params_template`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `sxo_goods_params_template_config`
--
ALTER TABLE `sxo_goods_params_template_config`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=83;

--
-- 使用表AUTO_INCREMENT `sxo_goods_photo`
--
ALTER TABLE `sxo_goods_photo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=2240;

--
-- 使用表AUTO_INCREMENT `sxo_goods_spec_base`
--
ALTER TABLE `sxo_goods_spec_base`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=6481;

--
-- 使用表AUTO_INCREMENT `sxo_goods_spec_template`
--
ALTER TABLE `sxo_goods_spec_template`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `sxo_goods_spec_type`
--
ALTER TABLE `sxo_goods_spec_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=1942;

--
-- 使用表AUTO_INCREMENT `sxo_goods_spec_value`
--
ALTER TABLE `sxo_goods_spec_value`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=9385;

--
-- 使用表AUTO_INCREMENT `sxo_layout`
--
ALTER TABLE `sxo_layout`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `sxo_link`
--
ALTER TABLE `sxo_link`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=23;

--
-- 使用表AUTO_INCREMENT `sxo_message`
--
ALTER TABLE `sxo_message`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_navigation`
--
ALTER TABLE `sxo_navigation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=60;

--
-- 使用表AUTO_INCREMENT `sxo_order`
--
ALTER TABLE `sxo_order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `sxo_order_address`
--
ALTER TABLE `sxo_order_address`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `sxo_order_aftersale`
--
ALTER TABLE `sxo_order_aftersale`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_order_aftersale_status_history`
--
ALTER TABLE `sxo_order_aftersale_status_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_order_currency`
--
ALTER TABLE `sxo_order_currency`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `sxo_order_detail`
--
ALTER TABLE `sxo_order_detail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `sxo_order_express`
--
ALTER TABLE `sxo_order_express`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_order_extraction_code`
--
ALTER TABLE `sxo_order_extraction_code`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_order_fictitious_value`
--
ALTER TABLE `sxo_order_fictitious_value`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_order_goods_inventory_log`
--
ALTER TABLE `sxo_order_goods_inventory_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_order_status_history`
--
ALTER TABLE `sxo_order_status_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `sxo_payment`
--
ALTER TABLE `sxo_payment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=30;

--
-- 使用表AUTO_INCREMENT `sxo_pay_log`
--
ALTER TABLE `sxo_pay_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '支付日志id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `sxo_pay_log_value`
--
ALTER TABLE `sxo_pay_log_value`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `sxo_pay_request_log`
--
ALTER TABLE `sxo_pay_request_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_plugins`
--
ALTER TABLE `sxo_plugins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_plugins_category`
--
ALTER TABLE `sxo_plugins_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `sxo_power`
--
ALTER TABLE `sxo_power`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '权限id', AUTO_INCREMENT=535;

--
-- 使用表AUTO_INCREMENT `sxo_quick_nav`
--
ALTER TABLE `sxo_quick_nav`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=30;

--
-- 使用表AUTO_INCREMENT `sxo_refund_log`
--
ALTER TABLE `sxo_refund_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '退款日志id';

--
-- 使用表AUTO_INCREMENT `sxo_region`
--
ALTER TABLE `sxo_region`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=3457;

--
-- 使用表AUTO_INCREMENT `sxo_role`
--
ALTER TABLE `sxo_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色组id', AUTO_INCREMENT=14;

--
-- 使用表AUTO_INCREMENT `sxo_role_plugins`
--
ALTER TABLE `sxo_role_plugins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '关联id';

--
-- 使用表AUTO_INCREMENT `sxo_role_power`
--
ALTER TABLE `sxo_role_power`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '关联id', AUTO_INCREMENT=5313;

--
-- 使用表AUTO_INCREMENT `sxo_screening_price`
--
ALTER TABLE `sxo_screening_price`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类id', AUTO_INCREMENT=32;

--
-- 使用表AUTO_INCREMENT `sxo_search_history`
--
ALTER TABLE `sxo_search_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_shortcut_menu`
--
ALTER TABLE `sxo_shortcut_menu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `sxo_slide`
--
ALTER TABLE `sxo_slide`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=40;

--
-- 使用表AUTO_INCREMENT `sxo_sms_log`
--
ALTER TABLE `sxo_sms_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_theme_data`
--
ALTER TABLE `sxo_theme_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_user`
--
ALTER TABLE `sxo_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `sxo_user_address`
--
ALTER TABLE `sxo_user_address`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '站点id', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `sxo_user_integral_log`
--
ALTER TABLE `sxo_user_integral_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id';

--
-- 使用表AUTO_INCREMENT `sxo_user_platform`
--
ALTER TABLE `sxo_user_platform`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `sxo_warehouse`
--
ALTER TABLE `sxo_warehouse`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `sxo_warehouse_goods`
--
ALTER TABLE `sxo_warehouse_goods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=174;

--
-- 使用表AUTO_INCREMENT `sxo_warehouse_goods_spec`
--
ALTER TABLE `sxo_warehouse_goods_spec`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=3031;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
