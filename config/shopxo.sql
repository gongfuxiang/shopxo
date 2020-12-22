/*
 Navicat Premium Data Transfer

 Source Server         : 本机
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : localhost:3306
 Source Schema         : shopxo

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 22/12/2020 15:31:20
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for s_admin
-- ----------------------------
DROP TABLE IF EXISTS `s_admin`;
CREATE TABLE `s_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `username` char(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `login_pwd` char(32) NOT NULL DEFAULT '' COMMENT '登录密码',
  `login_salt` char(6) NOT NULL DEFAULT '' COMMENT '登录密码配合加密字符串',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别（0保密，1女，2男）',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0正常, 1无效）',
  `login_total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所属角色组',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='管理员';

-- ----------------------------
-- Table structure for s_answer
-- ----------------------------
DROP TABLE IF EXISTS `s_answer`;
CREATE TABLE `s_answer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '联系人',
  `tel` char(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `title` char(60) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text COMMENT '详细内容',
  `reply` text COMMENT '回复内容',
  `is_reply` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已回复（0否, 1是）',
  `reply_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '回复时间',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示（0不显示, 1显示）',
  `access_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '访问次数',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`name`),
  KEY `is_show` (`is_show`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户留言/问答';

-- ----------------------------
-- Table structure for s_app_center_nav
-- ----------------------------
DROP TABLE IF EXISTS `s_app_center_nav`;
CREATE TABLE `s_app_center_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `platform` char(30) NOT NULL DEFAULT 'pc' COMMENT '所属平台（pc PC网站, h5 H5手机网站, ios 苹果APP, android 安卓APP, alipay 支付宝小程序, weixin 微信小程序, baidu 百度小程序, toutiao 头条小程序, qq QQ小程序）',
  `event_type` tinyint(2) NOT NULL DEFAULT '-1' COMMENT '事件类型（0 WEB页面, 1 内部页面(小程序或APP内部地址), 2 外部小程序(同一个主体下的小程序appid), 3 打开地图, 4 拨打电话）',
  `event_value` char(255) NOT NULL DEFAULT '' COMMENT '事件值',
  `images_url` char(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `desc` char(18) NOT NULL DEFAULT '' COMMENT '描述',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `is_need_login` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要登录（0否，1是）',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `platform` (`platform`),
  KEY `is_enable` (`is_enable`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='手机 - 用户中心导航';

-- ----------------------------
-- Table structure for s_app_home_nav
-- ----------------------------
DROP TABLE IF EXISTS `s_app_home_nav`;
CREATE TABLE `s_app_home_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `platform` char(30) NOT NULL DEFAULT 'pc' COMMENT '所属平台（pc PC网站, h5 H5手机网站, ios 苹果APP, android 安卓APP, alipay 支付宝小程序, weixin 微信小程序, baidu 百度小程序, toutiao 头条小程序, qq QQ小程序）',
  `event_type` tinyint(2) NOT NULL DEFAULT '-1' COMMENT '事件类型（0 WEB页面, 1 内部页面(小程序或APP内部地址), 2 外部小程序(同一个主体下的小程序appid), 3 打开地图, 4 拨打电话）',
  `event_value` char(255) NOT NULL DEFAULT '' COMMENT '事件值',
  `images_url` char(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `is_need_login` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要登录（0否，1是）',
  `bg_color` char(30) NOT NULL DEFAULT '' COMMENT 'css背景色值',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `platform` (`platform`),
  KEY `is_enable` (`is_enable`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='手机 - 首页导航';

-- ----------------------------
-- Table structure for s_article
-- ----------------------------
DROP TABLE IF EXISTS `s_article`;
CREATE TABLE `s_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` char(60) NOT NULL DEFAULT '' COMMENT '标题',
  `article_category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文章分类',
  `title_color` char(7) NOT NULL DEFAULT '' COMMENT '标题颜色',
  `jump_url` char(255) NOT NULL DEFAULT '' COMMENT '跳转url地址',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `content` longtext COMMENT '内容',
  `images` text COMMENT '图片数据（一维数组json）',
  `images_count` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '图片数量',
  `access_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '访问次数',
  `is_home_recommended` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否首页推荐（0否, 1是）',
  `seo_title` char(100) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` char(130) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_desc` char(230) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `is_enable` (`is_enable`),
  KEY `access_count` (`access_count`),
  KEY `image_count` (`images_count`),
  KEY `article_category_id` (`article_category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='文章';

-- ----------------------------
-- Table structure for s_article_category
-- ----------------------------
DROP TABLE IF EXISTS `s_article_category`;
CREATE TABLE `s_article_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `name` char(30) CHARACTER SET utf8 NOT NULL COMMENT '名称',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '顺序',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `is_enable` (`is_enable`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='文章分类';

-- ----------------------------
-- Table structure for s_attachment
-- ----------------------------
DROP TABLE IF EXISTS `s_attachment`;
CREATE TABLE `s_attachment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` char(160) NOT NULL DEFAULT '' COMMENT '名称',
  `original` char(160) NOT NULL DEFAULT '' COMMENT '原始名称',
  `path_type` char(80) NOT NULL DEFAULT '' COMMENT '路径标记',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '大小（单位kb）',
  `ext` char(30) NOT NULL DEFAULT '' COMMENT '类型（后缀名）',
  `type` char(30) NOT NULL DEFAULT '' COMMENT '类型（file文件, image图片, scrawl涂鸦, video视频, remote远程抓取文件）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT 'url路径',
  `hash` text COMMENT 'hash值',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `path_type` (`path_type`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=599 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='附件';

-- ----------------------------
-- Table structure for s_brand
-- ----------------------------
DROP TABLE IF EXISTS `s_brand`;
CREATE TABLE `s_brand` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `logo` char(255) NOT NULL DEFAULT '' COMMENT 'logo图标',
  `name` char(30) NOT NULL COMMENT '名称',
  `website_url` char(255) NOT NULL DEFAULT '' COMMENT '官网地址',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '顺序',
  `seo_title` char(100) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` char(130) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_desc` char(230) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `is_enable` (`is_enable`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='品牌';

-- ----------------------------
-- Table structure for s_brand_category
-- ----------------------------
DROP TABLE IF EXISTS `s_brand_category`;
CREATE TABLE `s_brand_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `name` char(30) CHARACTER SET utf8 NOT NULL COMMENT '名称',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '顺序',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `is_enable` (`is_enable`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='品牌分类';

-- ----------------------------
-- Table structure for s_brand_category_join
-- ----------------------------
DROP TABLE IF EXISTS `s_brand_category_join`;
CREATE TABLE `s_brand_category_join` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `brand_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '品牌id',
  `brand_category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `add_time` int(11) unsigned DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`),
  KEY `brand_category_id` (`brand_category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='品牌分类关联';

-- ----------------------------
-- Table structure for s_cart
-- ----------------------------
DROP TABLE IF EXISTS `s_cart`;
CREATE TABLE `s_cart` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  `goods_id` int(11) unsigned DEFAULT '0' COMMENT '商品id',
  `title` char(60) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `images` char(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `original_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '原价',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '销售价格',
  `stock` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `spec` text COMMENT '规格',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `title` (`title`),
  KEY `stock` (`stock`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='购物车';

-- ----------------------------
-- Table structure for s_config
-- ----------------------------
DROP TABLE IF EXISTS `s_config`;
CREATE TABLE `s_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '基本设置id',
  `value` text COMMENT '值',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `describe` char(255) NOT NULL DEFAULT '' COMMENT '描述',
  `error_tips` char(150) NOT NULL DEFAULT '' COMMENT '错误提示',
  `type` char(30) NOT NULL DEFAULT '' COMMENT '类型（admin后台, home前台）',
  `only_tag` char(60) NOT NULL DEFAULT '' COMMENT '唯一的标记',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `only_tag` (`only_tag`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='基本配置参数';

-- ----------------------------
-- Table structure for s_custom_view
-- ----------------------------
DROP TABLE IF EXISTS `s_custom_view`;
CREATE TABLE `s_custom_view` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` char(60) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `content` longtext COMMENT '内容',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `is_header` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否包含头部（0否, 1是）',
  `is_footer` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否包含尾部（0否, 1是）',
  `is_full_screen` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否满屏（0否, 1是）',
  `images` text COMMENT '图片数据（一维数组json）',
  `images_count` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '图片数量',
  `access_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '访问次数',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `is_enable` (`is_enable`),
  KEY `access_count` (`access_count`),
  KEY `image_count` (`images_count`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='自定义页面';

-- ----------------------------
-- Table structure for s_express
-- ----------------------------
DROP TABLE IF EXISTS `s_express`;
CREATE TABLE `s_express` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `icon` char(255) NOT NULL DEFAULT '' COMMENT 'icon图标',
  `name` char(30) NOT NULL COMMENT '名称',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '顺序',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `is_enable` (`is_enable`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='快递公司';

-- ----------------------------
-- Table structure for s_form_table_user_fields
-- ----------------------------
DROP TABLE IF EXISTS `s_form_table_user_fields`;
CREATE TABLE `s_form_table_user_fields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员id或用户id',
  `user_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户类型（0后台管理员, 1用户端）',
  `md5_key` char(32) NOT NULL DEFAULT '' COMMENT 'form表格数据唯一key',
  `fields` text COMMENT '字段数据（json格式存储）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_type` (`user_type`),
  KEY `md5_key` (`md5_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='动态表格用户自定义字段';

-- ----------------------------
-- Table structure for s_goods
-- ----------------------------
DROP TABLE IF EXISTS `s_goods`;
CREATE TABLE `s_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `brand_id` int(11) unsigned DEFAULT '0' COMMENT '品牌id',
  `site_type` tinyint(1) NOT NULL DEFAULT '-1' COMMENT '商品类型（跟随站点类型一致[0销售, 1展示, 2自提, 3虚拟销售, 4销售+自提]）',
  `title` char(160) NOT NULL DEFAULT '' COMMENT '标题',
  `title_color` char(7) NOT NULL DEFAULT '' COMMENT '标题颜色',
  `simple_desc` char(230) NOT NULL DEFAULT '' COMMENT '简述',
  `model` char(30) NOT NULL DEFAULT '' COMMENT '型号',
  `place_origin` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '产地（地区省id）',
  `inventory` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '库存（所有规格库存总和）',
  `inventory_unit` char(15) NOT NULL DEFAULT '' COMMENT '库存单位',
  `images` char(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `original_price` char(60) NOT NULL DEFAULT '' COMMENT '原价（单值:10, 区间:10.00-20.00）一般用于展示使用',
  `min_original_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '最低原价',
  `max_original_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '最大原价',
  `price` char(60) NOT NULL DEFAULT '' COMMENT '销售价格（单值:10, 区间:10.00-20.00）一般用于展示使用',
  `min_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '最低价格',
  `max_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '最高价格',
  `give_integral` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '购买赠送积分比例',
  `buy_min_number` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '最低起购数量 （默认1）',
  `buy_max_number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最大购买数量（最大数值 100000000, 小于等于0或空则不限）',
  `is_deduction_inventory` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否扣减库存（0否, 1是）',
  `is_shelves` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否上架（下架后用户不可见, 0否, 1是）',
  `is_home_recommended` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否首页推荐（0否, 1是）',
  `content_web` mediumtext COMMENT '电脑端详情内容',
  `photo_count` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '相册图片数量',
  `sales_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `access_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '访问次数',
  `video` char(255) NOT NULL DEFAULT '' COMMENT '短视频',
  `is_exist_many_spec` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否存在多个规格（0否, 1是）',
  `spec_base` text COMMENT '规格基础数据',
  `fictitious_goods_value` text COMMENT '虚拟商品展示数据',
  `seo_title` char(100) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` char(130) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_desc` char(230) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `is_delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否已删除（0 未删除, 大于0则是删除时间）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `access_count` (`access_count`),
  KEY `photo_count` (`photo_count`),
  KEY `is_shelves` (`is_shelves`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品';

-- ----------------------------
-- Table structure for s_goods_browse
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_browse`;
CREATE TABLE `s_goods_browse` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户商品浏览';

-- ----------------------------
-- Table structure for s_goods_category
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_category`;
CREATE TABLE `s_goods_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `icon` char(255) NOT NULL DEFAULT '' COMMENT 'icon图标',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `vice_name` char(80) NOT NULL DEFAULT '' COMMENT '副标题',
  `describe` char(255) NOT NULL DEFAULT '' COMMENT '描述',
  `bg_color` char(30) NOT NULL DEFAULT '' COMMENT 'css背景色值',
  `big_images` char(255) NOT NULL DEFAULT '' COMMENT '大图片',
  `is_home_recommended` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否首页推荐（0否, 1是）',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `seo_title` char(100) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` char(130) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_desc` char(230) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `is_enable` (`is_enable`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=893 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品分类';

-- ----------------------------
-- Table structure for s_goods_category_join
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_category_join`;
CREATE TABLE `s_goods_category_join` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `add_time` int(11) unsigned DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=753 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品分类关联';

-- ----------------------------
-- Table structure for s_goods_comments
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_comments`;
CREATE TABLE `s_goods_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '业务订单id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `business_type` char(30) NOT NULL DEFAULT '' COMMENT '业务类型名称（如订单 order）',
  `content` char(255) NOT NULL DEFAULT '' COMMENT '评价内容',
  `images` text COMMENT '图片数据（一维数组json）',
  `reply` char(255) NOT NULL DEFAULT '' COMMENT '回复内容',
  `rating` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '评价级别（默认0 1~5）',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示（0否, 1是）',
  `is_anonymous` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否匿名（0否，1是）',
  `is_reply` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否回复（0否，1是）',
  `reply_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '回复时间',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品评论';

-- ----------------------------
-- Table structure for s_goods_content_app
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_content_app`;
CREATE TABLE `s_goods_content_app` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `images` char(255) NOT NULL DEFAULT '' COMMENT '图片',
  `content` text COMMENT '内容',
  `sort` tinyint(3) unsigned DEFAULT '0' COMMENT '顺序',
  `add_time` int(11) unsigned DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=1057 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品手机详情';

-- ----------------------------
-- Table structure for s_goods_favor
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_favor`;
CREATE TABLE `s_goods_favor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户商品收藏';

-- ----------------------------
-- Table structure for s_goods_params
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_params`;
CREATE TABLE `s_goods_params` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `type` tinyint(1) unsigned DEFAULT '1' COMMENT '展示范围（0全部, 1详情, 2基础）默认1详情',
  `name` char(180) NOT NULL DEFAULT '' COMMENT '参数名称',
  `value` char(230) NOT NULL DEFAULT '' COMMENT '参数值',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=573 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品参数';

-- ----------------------------
-- Table structure for s_goods_params_template
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_params_template`;
CREATE TABLE `s_goods_params_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `config_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '参数配置数量',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `config_count` (`config_count`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品参数模板';

-- ----------------------------
-- Table structure for s_goods_params_template_config
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_params_template_config`;
CREATE TABLE `s_goods_params_template_config` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `template_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '模板id',
  `type` tinyint(1) unsigned DEFAULT '1' COMMENT '展示范围（0全部, 1详情, 2基础）默认1详情',
  `name` char(180) NOT NULL DEFAULT '' COMMENT '参数名称',
  `value` char(230) NOT NULL DEFAULT '' COMMENT '参数值',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品参数模板值';

-- ----------------------------
-- Table structure for s_goods_photo
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_photo`;
CREATE TABLE `s_goods_photo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `images` char(255) NOT NULL DEFAULT '' COMMENT '图片',
  `is_show` tinyint(3) unsigned DEFAULT '1' COMMENT '是否显示（0否, 1是）',
  `sort` tinyint(3) unsigned DEFAULT '0' COMMENT '顺序',
  `add_time` int(11) unsigned DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `is_show` (`is_show`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=823 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品相册图片';

-- ----------------------------
-- Table structure for s_goods_spec_base
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_spec_base`;
CREATE TABLE `s_goods_spec_base` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售价格',
  `inventory` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `weight` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '重量（kg） ',
  `coding` char(80) NOT NULL DEFAULT '' COMMENT '编码',
  `barcode` char(80) NOT NULL DEFAULT '' COMMENT '条形码',
  `original_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `extends` longtext COMMENT '扩展数据(json格式存储)',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1369 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品规格基础';

-- ----------------------------
-- Table structure for s_goods_spec_type
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_spec_type`;
CREATE TABLE `s_goods_spec_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `value` text NOT NULL COMMENT '类型值（json字符串存储）',
  `name` char(230) NOT NULL DEFAULT '' COMMENT '类型名称',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=444 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品规格类型';

-- ----------------------------
-- Table structure for s_goods_spec_value
-- ----------------------------
DROP TABLE IF EXISTS `s_goods_spec_value`;
CREATE TABLE `s_goods_spec_value` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `goods_spec_base_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品规格基础id',
  `value` char(230) NOT NULL DEFAULT '' COMMENT '规格值',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `goods_spec_base_id` (`goods_spec_base_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2675 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品规格值';

-- ----------------------------
-- Table structure for s_link
-- ----------------------------
DROP TABLE IF EXISTS `s_link`;
CREATE TABLE `s_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` char(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '导航名称',
  `url` char(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'url地址',
  `describe` char(60) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '描述',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `is_new_window_open` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否新窗口打开（0否，1是）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `is_enable` (`is_enable`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='友情链接';

-- ----------------------------
-- Table structure for s_message
-- ----------------------------
DROP TABLE IF EXISTS `s_message`;
CREATE TABLE `s_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` char(60) NOT NULL DEFAULT '' COMMENT '标题',
  `detail` char(255) NOT NULL DEFAULT '' COMMENT '详情',
  `business_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '业务id',
  `business_type` char(180) NOT NULL DEFAULT '' COMMENT '业务类型，字符串（如：订单、充值、提现、等...）',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '消息类型（0普通通知, ...）',
  `is_read` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否已读（0否, 1是）',
  `is_delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否已删除（0否, 大于0删除时间）',
  `user_is_delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户是否已删除（0否, 大于0删除时间）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='消息';

-- ----------------------------
-- Table structure for s_navigation
-- ----------------------------
DROP TABLE IF EXISTS `s_navigation`;
CREATE TABLE `s_navigation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '导航名称',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '自定义url地址',
  `value` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数据 id',
  `data_type` char(30) NOT NULL DEFAULT '' COMMENT '数据类型（custom:自定义导航, article_class:文章分类, customview:自定义页面）',
  `nav_type` char(30) NOT NULL DEFAULT '' COMMENT '导航类型（header:顶部导航, footer:底部导航）',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示（0否，1是）',
  `is_new_window_open` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否新窗口打开（0否，1是）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `is_show` (`is_show`),
  KEY `sort` (`sort`),
  KEY `nav_type` (`nav_type`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='导航';

-- ----------------------------
-- Table structure for s_order
-- ----------------------------
DROP TABLE IF EXISTS `s_order`;
CREATE TABLE `s_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_no` char(60) NOT NULL DEFAULT '' COMMENT '订单号',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `warehouse_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '仓库id',
  `user_note` char(255) NOT NULL DEFAULT '' COMMENT '用户备注',
  `express_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '快递id',
  `express_number` char(60) NOT NULL DEFAULT '' COMMENT '快递单号',
  `payment_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式id',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态（0待确认, 1已确认/待支付, 2已支付/待发货, 3已发货/待收货, 4已完成, 5已取消, 6已关闭）',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态（0未支付, 1已支付, 2已退款, 3部分退款）',
  `extension_data` longtext COMMENT '扩展展示数据',
  `buy_number_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买商品总数量',
  `increase_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '增加的金额',
  `preferential_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单单价',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单总价(订单最终价格)',
  `pay_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已支付金额',
  `refund_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `returned_quantity` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '退货数量',
  `client_type` char(30) NOT NULL DEFAULT '' COMMENT '客户端类型（pc, h5, ios, android, alipay, weixin, baidu）取APPLICATION_CLIENT_TYPE常量值',
  `order_model` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单模式（0销售型, 1展示型, 2自提点, 3虚拟销售）',
  `is_under_line` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否线下支付（0否，1是）',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `confirm_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '确认时间',
  `delivery_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发货时间',
  `cancel_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '取消时间',
  `collect_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收货时间',
  `close_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关闭时间',
  `comments_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论时间',
  `is_comments` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商家是否已评论（0否, 大于0评论时间）',
  `user_is_comments` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户是否已评论（0否, 大于0评论时间）',
  `is_delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商家是否已删除（0否, 大于0删除时间）',
  `user_is_delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户是否已删除（0否, 大于0删除时间）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `pay_status` (`pay_status`),
  KEY `warehouse_id` (`warehouse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='订单';

-- ----------------------------
-- Table structure for s_order_address
-- ----------------------------
DROP TABLE IF EXISTS `s_order_address`;
CREATE TABLE `s_order_address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `address_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收件地址id',
  `alias` char(60) NOT NULL DEFAULT '' COMMENT '别名',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '收件人-姓名',
  `tel` char(15) NOT NULL DEFAULT '' COMMENT '收件人-电话',
  `province` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收件人-省',
  `city` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收件人-市',
  `county` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收件人-县/区',
  `address` char(200) NOT NULL DEFAULT '' COMMENT '收件人-详细地址',
  `province_name` char(30) NOT NULL DEFAULT '' COMMENT '收件人-省-名称',
  `city_name` char(30) NOT NULL DEFAULT '' COMMENT '收件人-市-名称',
  `county_name` char(30) NOT NULL DEFAULT '' COMMENT '收件人-县/区-名称',
  `lng` decimal(13,10) NOT NULL DEFAULT '0.0000000000' COMMENT '收货地址-经度',
  `lat` decimal(13,10) NOT NULL DEFAULT '0.0000000000' COMMENT '收货地址-纬度',
  `idcard_name` char(60) NOT NULL DEFAULT '' COMMENT '身份证姓名',
  `idcard_number` char(30) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `idcard_front` char(255) NOT NULL DEFAULT '' COMMENT '身份证人像面图片',
  `idcard_back` char(255) NOT NULL DEFAULT '' COMMENT '身份证国微面图片',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='订单地址';

-- ----------------------------
-- Table structure for s_order_aftersale
-- ----------------------------
DROP TABLE IF EXISTS `s_order_aftersale`;
CREATE TABLE `s_order_aftersale` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_no` char(60) NOT NULL DEFAULT '' COMMENT '订单号',
  `order_detail_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单详情id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0待确认, 1待退货, 2待审核, 3已完成, 4已拒绝, 5已取消）',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '业务类型（0仅退款, 1退货退款）',
  `refundment` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '退款类型（0原路退回, 1退至钱包, 2手动处理）',
  `reason` char(180) NOT NULL DEFAULT '' COMMENT '申请原因',
  `number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '退货数量',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `msg` char(230) NOT NULL DEFAULT '' COMMENT '退款说明',
  `images` text COMMENT '凭证图片（一维数组json存储）',
  `refuse_reason` char(230) NOT NULL DEFAULT '' COMMENT '拒绝原因',
  `express_name` char(60) NOT NULL DEFAULT '' COMMENT '快递名称',
  `express_number` char(60) NOT NULL DEFAULT '' COMMENT '快递单号',
  `apply_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '申请时间',
  `confirm_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '确认时间',
  `delivery_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '退货时间',
  `audit_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '审核时间',
  `cancel_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '取消时间',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='订单售后';

-- ----------------------------
-- Table structure for s_order_currency
-- ----------------------------
DROP TABLE IF EXISTS `s_order_currency`;
CREATE TABLE `s_order_currency` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `currency_name` char(80) NOT NULL DEFAULT '' COMMENT '货币名称',
  `currency_code` char(60) NOT NULL DEFAULT '' COMMENT '货币代码',
  `currency_symbol` char(60) NOT NULL DEFAULT '' COMMENT '货币符号',
  `currency_rate` decimal(7,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '货币汇率',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `currency_name` (`currency_name`),
  KEY `currency_code` (`currency_code`),
  KEY `currency_rate` (`currency_rate`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='订单货币';

-- ----------------------------
-- Table structure for s_order_detail
-- ----------------------------
DROP TABLE IF EXISTS `s_order_detail`;
CREATE TABLE `s_order_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `title` char(60) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '标题',
  `images` char(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `original_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '原价',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '当前总价(单价*数量)',
  `spec` text COMMENT '规格',
  `buy_number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `model` char(30) NOT NULL DEFAULT '' COMMENT '型号',
  `spec_weight` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '重量（kg）',
  `spec_coding` char(80) NOT NULL DEFAULT '' COMMENT '编码',
  `spec_barcode` char(80) NOT NULL DEFAULT '' COMMENT '条形码',
  `refund_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `returned_quantity` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '退货数量',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='订单详情';

-- ----------------------------
-- Table structure for s_order_extraction_code
-- ----------------------------
DROP TABLE IF EXISTS `s_order_extraction_code`;
CREATE TABLE `s_order_extraction_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `code` char(30) NOT NULL DEFAULT '' COMMENT '取货码',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='订单自提取货码关联';

-- ----------------------------
-- Table structure for s_order_fictitious_value
-- ----------------------------
DROP TABLE IF EXISTS `s_order_fictitious_value`;
CREATE TABLE `s_order_fictitious_value` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `order_detail_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单详情id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `value` text COMMENT '虚拟商品展示数据',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `order_detail_id` (`order_detail_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='订单虚拟销售数据关联';

-- ----------------------------
-- Table structure for s_order_goods_inventory_log
-- ----------------------------
DROP TABLE IF EXISTS `s_order_goods_inventory_log`;
CREATE TABLE `s_order_goods_inventory_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `order_detail_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单详情id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `order_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态（0待确认, 1已确认/待支付, 2已支付/待发货, 3已发货/待收货, 4已完成, 5已取消, 6已关闭）',
  `original_inventory` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '原库存',
  `new_inventory` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最新库存',
  `is_rollback` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否回滚（0否, 1是）',
  `rollback_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '回滚时间',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`),
  KEY `order_status` (`order_status`),
  KEY `order_detail_id` (`order_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='订单商品库存变更日志';

-- ----------------------------
-- Table structure for s_order_status_history
-- ----------------------------
DROP TABLE IF EXISTS `s_order_status_history`;
CREATE TABLE `s_order_status_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `original_status` varchar(60) NOT NULL DEFAULT '' COMMENT '原始状态',
  `new_status` varchar(60) NOT NULL DEFAULT '' COMMENT '最新状态',
  `msg` varchar(255) NOT NULL DEFAULT '' COMMENT '操作描述',
  `creator` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建-用户id',
  `creator_name` varchar(60) NOT NULL DEFAULT '' COMMENT '创建人-姓名',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `original_status` (`original_status`),
  KEY `new_status` (`new_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='订单状态历史纪录';

-- ----------------------------
-- Table structure for s_pay_log
-- ----------------------------
DROP TABLE IF EXISTS `s_pay_log`;
CREATE TABLE `s_pay_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '支付日志id',
  `log_no` char(60) NOT NULL DEFAULT '' COMMENT '支付日志订单号',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `business_type` char(180) NOT NULL DEFAULT '' COMMENT '业务类型，字符串（如：订单、钱包充值、会员购买、等...）',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0待支付, 1已支付, 2已关闭）正常30分钟内未支付将关闭',
  `payment` char(60) NOT NULL DEFAULT '' COMMENT '支付方式标记',
  `payment_name` char(60) NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `subject` char(255) NOT NULL DEFAULT '' COMMENT '订单名称',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '业务订单金额',
  `pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `trade_no` char(100) NOT NULL DEFAULT '' COMMENT '支付平台交易号',
  `buyer_user` char(60) NOT NULL DEFAULT '' COMMENT '支付平台用户帐号',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `close_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关闭时间',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `payment` (`payment`),
  KEY `status` (`status`),
  KEY `business_type` (`business_type`),
  KEY `total_price` (`total_price`),
  KEY `pay_price` (`pay_price`),
  KEY `add_time` (`add_time`),
  KEY `pay_time` (`pay_time`),
  KEY `close_time` (`close_time`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='支付日志';

-- ----------------------------
-- Table structure for s_pay_log_value
-- ----------------------------
DROP TABLE IF EXISTS `s_pay_log_value`;
CREATE TABLE `s_pay_log_value` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `pay_log_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付日志id',
  `business_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '业务订单id',
  `business_no` char(60) NOT NULL DEFAULT '' COMMENT '业务订单号',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `pay_log_id` (`pay_log_id`),
  KEY `business_id` (`business_id`),
  KEY `add_time` (`add_time`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='支付日志关联业务数据';

-- ----------------------------
-- Table structure for s_pay_request_log
-- ----------------------------
DROP TABLE IF EXISTS `s_pay_request_log`;
CREATE TABLE `s_pay_request_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `business_type` char(180) NOT NULL DEFAULT '' COMMENT '业务类型，字符串（如：订单、钱包充值、会员购买、等...）',
  `request_params` mediumtext COMMENT '请求参数（数组则json字符串存储）',
  `response_data` mediumtext COMMENT '响应参数（数组则json字符串存储）',
  `business_handle` text COMMENT '业务处理结果（数组则json字符串存储）',
  `request_url` text COMMENT '请求url地址',
  `server_port` char(10) NOT NULL DEFAULT '' COMMENT '端口号',
  `server_ip` char(15) NOT NULL DEFAULT '' COMMENT '服务器ip',
  `client_ip` char(15) NOT NULL DEFAULT '' COMMENT '客户端ip',
  `os` char(20) NOT NULL DEFAULT '' COMMENT '操作系统',
  `browser` char(20) NOT NULL DEFAULT '' COMMENT '浏览器',
  `method` char(4) NOT NULL DEFAULT '' COMMENT '请求类型',
  `scheme` char(5) NOT NULL DEFAULT '' COMMENT 'http类型',
  `version` char(5) NOT NULL DEFAULT '' COMMENT 'http版本',
  `client` char(255) NOT NULL DEFAULT '' COMMENT '客户端详情信息',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `business_type` (`business_type`),
  KEY `add_time` (`add_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='支付请求日志';

-- ----------------------------
-- Table structure for s_payment
-- ----------------------------
DROP TABLE IF EXISTS `s_payment`;
CREATE TABLE `s_payment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` char(30) NOT NULL COMMENT '名称',
  `payment` char(60) NOT NULL DEFAULT '' COMMENT '唯一标记',
  `logo` char(255) NOT NULL DEFAULT '' COMMENT 'logo',
  `version` char(255) NOT NULL DEFAULT '' COMMENT '插件版本',
  `apply_version` char(255) NOT NULL DEFAULT '' COMMENT '适用系统版本',
  `desc` char(255) NOT NULL DEFAULT '' COMMENT '插件描述',
  `author` char(255) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` char(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `element` text COMMENT '配置项规则',
  `config` text COMMENT '配置数据',
  `apply_terminal` char(255) NOT NULL COMMENT '适用终端 php一维数组json字符串存储（pc, h5, ios, android, alipay, weixin, baidu, toutiao, qq）',
  `apply_terminal_old` char(255) NOT NULL COMMENT '原始适用终端 php一维数组json字符串存储（pc, h5, ios, android, alipay, weixin, baidu, toutiao, qq）',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `is_open_user` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否对用户开放',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '顺序',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment` (`payment`),
  KEY `is_enable` (`is_enable`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='支付方式';

-- ----------------------------
-- Table structure for s_plugins
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins`;
CREATE TABLE `s_plugins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `plugins` char(60) NOT NULL DEFAULT '' COMMENT '唯一标记',
  `data` longtext COMMENT '应用数据',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `plugins` (`plugins`),
  KEY `is_enable` (`is_enable`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='应用';

-- ----------------------------
-- Table structure for s_plugins_answers_goods
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_answers_goods`;
CREATE TABLE `s_plugins_answers_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='问答系统商品 - 应用';

-- ----------------------------
-- Table structure for s_plugins_answers_slide
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_answers_slide`;
CREATE TABLE `s_plugins_answers_slide` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '别名',
  `images_url` char(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `url` char(255) NOT NULL DEFAULT '' COMMENT 'url地址',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `is_enable` (`is_enable`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='问答系统轮播图 - 应用';

-- ----------------------------
-- Table structure for s_plugins_distribution_integral_log
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_distribution_integral_log`;
CREATE TABLE `s_plugins_distribution_integral_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `integral` int(11) unsigned NOT NULL COMMENT '积分',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0待发放, 1已发放, 2已退回）',
  `msg` char(255) NOT NULL DEFAULT '' COMMENT '描述（一般用于退回描述）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='分销积分明细 - 应用';

-- ----------------------------
-- Table structure for s_plugins_distribution_level
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_distribution_level`;
CREATE TABLE `s_plugins_distribution_level` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '名称',
  `images_url` char(255) NOT NULL DEFAULT '' COMMENT '图标',
  `rules_min` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '消费最小金额',
  `rules_max` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '消费最大金额',
  `level_rate_one` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级返佣比例',
  `level_rate_two` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级返佣比例',
  `level_rate_three` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '三级返佣比例',
  `down_return_rate` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '向下返佣比例',
  `self_buy_rate` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '内购返佣比例',
  `force_current_user_rate_one` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '强制返佣至取货点返佣比例（一级）',
  `force_current_user_rate_two` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '强制返佣至取货点返佣比例（二级）',
  `force_current_user_rate_three` decimal(6,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '强制返佣至取货点返佣比例（三级）',
  `is_level_auto` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否自动分配等级（0否, 1是）',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用（0否, 1是）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `is_enable` (`is_enable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='分销等级 - 应用';

-- ----------------------------
-- Table structure for s_plugins_distribution_profit_log
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_distribution_profit_log`;
CREATE TABLE `s_plugins_distribution_profit_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单id',
  `order_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单用户id',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `profit_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收益金额',
  `rate` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '等级返佣比例 0~100 的数字（创建时写入，防止发生退款重新计算时用户等级变更）',
  `spec_extends` mediumtext COMMENT '订单中所购买的商品对应规格的扩展数据（用于重新计算时候使用）',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '当前级别（1~3）0则是向下返佣',
  `user_level_id` char(60) NOT NULL DEFAULT '' COMMENT '用户等级id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '结算状态（0待生效, 1待结算, 2已结算, 3已失效）',
  `msg` char(255) NOT NULL DEFAULT '' COMMENT '描述（一般用于退款描述）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='分销佣金明细 - 应用';

-- ----------------------------
-- Table structure for s_plugins_distribution_user_self_extraction
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_distribution_user_self_extraction`;
CREATE TABLE `s_plugins_distribution_user_self_extraction` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '站点id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '站点所属用户id',
  `alias` char(60) NOT NULL DEFAULT '' COMMENT '别名',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '姓名',
  `tel` char(15) NOT NULL DEFAULT '' COMMENT '电话',
  `province` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在省',
  `city` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在市',
  `county` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在县/区',
  `address` char(80) NOT NULL DEFAULT '' COMMENT '详细地址',
  `lng` decimal(13,10) unsigned NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lat` decimal(13,10) unsigned NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0待审核, 1已通过, 2已拒绝, 3已解约）',
  `fail_reason` char(200) NOT NULL DEFAULT '' COMMENT '审核拒绝原因',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='分销商取货点 - 应用';

-- ----------------------------
-- Table structure for s_plugins_distribution_user_self_extraction_order
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_distribution_user_self_extraction_order`;
CREATE TABLE `s_plugins_distribution_user_self_extraction_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '站点id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '站点所属用户id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `self_extraction_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '取货点地址id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0待处理, 1已处理）',
  `notes` char(200) NOT NULL DEFAULT '' COMMENT '备注',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `self_extraction_id` (`self_extraction_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='分销商取货点关联订单 - 应用';

-- ----------------------------
-- Table structure for s_plugins_invoice
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_invoice`;
CREATE TABLE `s_plugins_invoice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `business_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '业务类型（0订单、...更多）',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0待审核、1待开票、2已开票、3已拒绝）',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '发票金额',
  `apply_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '申请类型（0个人、1企业）',
  `invoice_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '发票类型（0增值税普通电子发票、1增值税普通纸质发票、2增值税专业纸质发票）',
  `invoice_content` char(230) NOT NULL DEFAULT '' COMMENT '发票内容',
  `invoice_title` char(200) NOT NULL DEFAULT '' COMMENT '个人姓名或企业名称',
  `invoice_code` char(160) NOT NULL DEFAULT '' COMMENT '企业统一社会信用代码或纳税识别号',
  `invoice_bank` char(200) NOT NULL DEFAULT '' COMMENT '企业开户行名称',
  `invoice_account` char(160) NOT NULL DEFAULT '' COMMENT '企业开户帐号',
  `invoice_tel` char(15) NOT NULL DEFAULT '' COMMENT '企业联系电话',
  `invoice_address` char(230) NOT NULL DEFAULT '' COMMENT '企业注册地址',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '收件人姓名',
  `tel` char(15) NOT NULL DEFAULT '' COMMENT '收件人电话',
  `email` char(60) NOT NULL DEFAULT '' COMMENT '电子邮箱（接收电子发票）',
  `address` char(230) NOT NULL DEFAULT '' COMMENT '收件人详细地址',
  `electronic_invoice` text COMMENT '附件地址（电子发票json存储[title,url]）',
  `express_name` char(60) NOT NULL DEFAULT '' COMMENT '快递名称（纸质）',
  `express_number` char(60) NOT NULL DEFAULT '' COMMENT '快递单号（纸质）',
  `refuse_reason` char(230) NOT NULL DEFAULT '' COMMENT '拒绝原因',
  `user_note` char(255) NOT NULL DEFAULT '' COMMENT '用户备注',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `business_type` (`business_type`),
  KEY `apply_type` (`apply_type`),
  KEY `invoice_type` (`invoice_type`),
  KEY `invoice_title` (`invoice_title`),
  KEY `invoice_bank` (`invoice_bank`),
  KEY `status` (`status`),
  KEY `name` (`name`),
  KEY `tel` (`tel`),
  KEY `email` (`email`),
  KEY `express_number` (`express_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='发票数据 - 应用';

-- ----------------------------
-- Table structure for s_plugins_invoice_value
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_invoice_value`;
CREATE TABLE `s_plugins_invoice_value` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `invoice_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付日志id',
  `business_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '业务订单id',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `business_id` (`business_id`),
  KEY `add_time` (`add_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='发票数据业务id - 应用';

-- ----------------------------
-- Table structure for s_plugins_ordergoodsform
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_ordergoodsform`;
CREATE TABLE `s_plugins_ordergoodsform` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` char(60) NOT NULL DEFAULT '' COMMENT '标题',
  `config_data` longtext COMMENT '配置表单数据',
  `config_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '配置表单数量',
  `goods_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联商品数量',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `is_enable` (`is_enable`),
  KEY `config_count` (`config_count`),
  KEY `goods_count` (`goods_count`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='下单商品表单 - 应用';

-- ----------------------------
-- Table structure for s_plugins_ordergoodsform_goods
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_ordergoodsform_goods`;
CREATE TABLE `s_plugins_ordergoodsform_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `form_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品表单id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='下单商品表单关联商品 - 应用';

-- ----------------------------
-- Table structure for s_plugins_ordergoodsform_goods_data
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_ordergoodsform_goods_data`;
CREATE TABLE `s_plugins_ordergoodsform_goods_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `form_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品表单id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` char(60) NOT NULL DEFAULT '' COMMENT '标题',
  `md5_key` char(32) NOT NULL DEFAULT '' COMMENT '数据唯一md5key',
  `content` text COMMENT '数据值',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `goods_id` (`goods_id`),
  KEY `md5_key` (`md5_key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='下单商品表单关联商品数据 - 应用';

-- ----------------------------
-- Table structure for s_plugins_ordergoodsform_order_data
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_ordergoodsform_order_data`;
CREATE TABLE `s_plugins_ordergoodsform_order_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `form_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品表单id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `md5_key` char(32) NOT NULL DEFAULT '' COMMENT '数据唯一md5key',
  `title` char(60) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text COMMENT '数据值',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `goods_id` (`goods_id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `md5_key` (`md5_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='下单商品表单关联订单数据 - 应用';

-- ----------------------------
-- Table structure for s_plugins_signin_qrcode
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_signin_qrcode`;
CREATE TABLE `s_plugins_signin_qrcode` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `request_id` int(11) NOT NULL DEFAULT '0' COMMENT '来源签到码id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `reward_master` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '邀请人奖励积分',
  `reward_invitee` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '受邀人奖励积分',
  `continuous_rules` text COMMENT '连续签到翻倍奖励配置数据',
  `specified_time_reward` char(255) NOT NULL DEFAULT '' COMMENT '指定时段额外奖励',
  `max_number_limit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最大奖励数量限制（0则不限制）',
  `day_number_limit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '一天奖励数量限制（0则不限制）',
  `right_images` char(255) NOT NULL DEFAULT '' COMMENT '右侧图片',
  `right_images_url_rules` text COMMENT '右侧图片链接地址规则（客户端类型=>链接地址）',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '联系人姓名',
  `tel` char(15) NOT NULL DEFAULT '' COMMENT '联系人电话',
  `address` char(230) NOT NULL DEFAULT '' COMMENT '联系地址',
  `goods_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联商品数量',
  `access_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '访问次数',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用（0否，1是）',
  `note` char(255) NOT NULL DEFAULT '' COMMENT '备注',
  `footer_code` text COMMENT '底部代码',
  `seo_title` char(100) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` char(130) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_desc` char(230) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `reward_master` (`reward_master`),
  KEY `reward_invitee` (`reward_invitee`),
  KEY `goods_count` (`goods_count`),
  KEY `access_count` (`access_count`),
  KEY `name` (`name`),
  KEY `tel` (`tel`),
  KEY `is_enable` (`is_enable`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='签到码 - 应用';

-- ----------------------------
-- Table structure for s_plugins_signin_qrcode_data
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_signin_qrcode_data`;
CREATE TABLE `s_plugins_signin_qrcode_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `qrcode_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '签到码id',
  `integral` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '奖励积分',
  `ymd` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '年月日',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `qrcode_id` (`qrcode_id`),
  KEY `integral` (`integral`),
  KEY `ymd` (`ymd`),
  KEY `add_time` (`add_time`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='签到码数据 - 应用';

-- ----------------------------
-- Table structure for s_plugins_signin_qrcode_goods
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_signin_qrcode_goods`;
CREATE TABLE `s_plugins_signin_qrcode_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `qrcode_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '活动id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `qrcode_id` (`qrcode_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='签到码关联商品 - 应用';

-- ----------------------------
-- Table structure for s_plugins_speedplaceorder_cart
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_speedplaceorder_cart`;
CREATE TABLE `s_plugins_speedplaceorder_cart` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  `goods_id` int(11) unsigned DEFAULT '0' COMMENT '商品id',
  `title` char(60) NOT NULL DEFAULT '' COMMENT '标题',
  `images` char(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `original_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '原价',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '销售价格',
  `stock` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `spec` text COMMENT '规格',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `title` (`title`),
  KEY `stock` (`stock`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='快速下单购物车 - 应用';

-- ----------------------------
-- Table structure for s_plugins_wallet
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_wallet`;
CREATE TABLE `s_plugins_wallet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0正常, 1异常, 2已注销）',
  `normal_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '有效金额（包含赠送金额）',
  `frozen_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `give_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '赠送金额（所有赠送金额总计）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`) USING BTREE,
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='钱包 - 应用';

-- ----------------------------
-- Table structure for s_plugins_wallet_cash
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_wallet_cash`;
CREATE TABLE `s_plugins_wallet_cash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `wallet_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '钱包id',
  `cash_no` char(60) NOT NULL DEFAULT '' COMMENT '提现单号',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0未打款, 1已打款, 2打款失败）',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `pay_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '打款金额',
  `bank_name` char(60) NOT NULL DEFAULT '' COMMENT '收款平台',
  `bank_accounts` char(60) NOT NULL DEFAULT '' COMMENT '收款账号',
  `bank_username` char(60) NOT NULL DEFAULT '' COMMENT '开户人姓名',
  `msg` char(200) NOT NULL DEFAULT '' COMMENT '描述（用户可见）',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '打款时间',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cash_no` (`cash_no`),
  KEY `status` (`status`),
  KEY `user_id` (`user_id`),
  KEY `wallet_id` (`wallet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='钱包提现 - 应用';

-- ----------------------------
-- Table structure for s_plugins_wallet_log
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_wallet_log`;
CREATE TABLE `s_plugins_wallet_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `wallet_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '钱包id',
  `business_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '业务类型（0系统, 1充值, 2提现, 3消费）',
  `money_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '金额类型（0正常, 1冻结, 2赠送）',
  `operation_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '操作类型（ 0减少, 1增加）',
  `operation_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '操作金额',
  `original_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原始金额',
  `latest_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '最新金额',
  `msg` char(200) NOT NULL DEFAULT '' COMMENT '变更说明',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `wallet_id` (`wallet_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='钱包日志 - 应用';

-- ----------------------------
-- Table structure for s_plugins_wallet_recharge
-- ----------------------------
DROP TABLE IF EXISTS `s_plugins_wallet_recharge`;
CREATE TABLE `s_plugins_wallet_recharge` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `wallet_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '钱包id',
  `recharge_no` char(60) NOT NULL DEFAULT '' COMMENT '充值单号',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0未支付, 1已支付）',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额',
  `pay_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `payment_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式id',
  `payment` char(60) NOT NULL DEFAULT '' COMMENT '支付方式标记',
  `payment_name` char(60) NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `recharge_no` (`recharge_no`),
  KEY `status` (`status`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='钱包充值 - 应用';

-- ----------------------------
-- Table structure for s_power
-- ----------------------------
DROP TABLE IF EXISTS `s_power`;
CREATE TABLE `s_power` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限父级id',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '权限名称',
  `control` char(30) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action` char(30) NOT NULL DEFAULT '' COMMENT '方法名称',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '自定义url地址',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示（0否，1是）',
  `icon` char(60) NOT NULL DEFAULT '' COMMENT '图标class',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=468 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='权限';

-- ----------------------------
-- Table structure for s_quick_nav
-- ----------------------------
DROP TABLE IF EXISTS `s_quick_nav`;
CREATE TABLE `s_quick_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `platform` char(30) NOT NULL DEFAULT 'pc' COMMENT '所属平台（pc PC网站, h5 H5手机网站, ios 苹果APP, android 安卓APP, alipay 支付宝小程序, weixin 微信小程序, baidu 百度小程序, toutiao 头条小程序, qq QQ小程序）',
  `event_type` tinyint(2) NOT NULL DEFAULT '-1' COMMENT '事件类型（0 WEB页面, 1 内部页面(小程序或APP内部地址), 2 外部小程序(同一个主体下的小程序appid), 3 打开地图, 4 拨打电话）',
  `event_value` char(255) NOT NULL DEFAULT '' COMMENT '事件值',
  `images_url` char(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `bg_color` char(30) NOT NULL DEFAULT '' COMMENT 'css背景色值',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `platform` (`platform`),
  KEY `is_enable` (`is_enable`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='快捷导航';

-- ----------------------------
-- Table structure for s_refund_log
-- ----------------------------
DROP TABLE IF EXISTS `s_refund_log`;
CREATE TABLE `s_refund_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '退款日志id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `business_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '业务订单id',
  `business_type` char(180) NOT NULL DEFAULT '' COMMENT '业务类型，字符串（如：订单、钱包充值、会员购买、等...）',
  `trade_no` char(100) NOT NULL DEFAULT '' COMMENT '支付平台交易号',
  `buyer_user` char(60) NOT NULL DEFAULT '' COMMENT '支付平台用户帐号',
  `refund_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单实际支付金额',
  `msg` char(255) NOT NULL DEFAULT '' COMMENT '描述',
  `payment` char(60) NOT NULL DEFAULT '' COMMENT '支付方式标记',
  `payment_name` char(60) NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `refundment` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '退款类型（0原路退回, 1退至钱包, 2手动处理）',
  `return_params` text COMMENT '支付平台返回参数（以json存储）',
  `add_time` int(11) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `payment` (`payment`),
  KEY `business_id` (`business_id`),
  KEY `business_type` (`business_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='退款日志';

-- ----------------------------
-- Table structure for s_region
-- ----------------------------
DROP TABLE IF EXISTS `s_region`;
CREATE TABLE `s_region` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `level` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '级别类型（1:一级[所有省], 2：二级[所有市], 3:三级[所有区县], 4:街道[所有街道]）',
  `letters` char(3) NOT NULL DEFAULT '' COMMENT '城市首字母',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `is_enable` (`is_enable`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=45065 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='地区';

-- ----------------------------
-- Table structure for s_role
-- ----------------------------
DROP TABLE IF EXISTS `s_role`;
CREATE TABLE `s_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色组id',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '角色名称',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='角色组';

-- ----------------------------
-- Table structure for s_role_power
-- ----------------------------
DROP TABLE IF EXISTS `s_role_power`;
CREATE TABLE `s_role_power` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '关联id',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  `power_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限id',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `power_id` (`power_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3562 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='角色与权限管理';

-- ----------------------------
-- Table structure for s_screening_price
-- ----------------------------
DROP TABLE IF EXISTS `s_screening_price`;
CREATE TABLE `s_screening_price` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `name` char(30) CHARACTER SET utf8 NOT NULL COMMENT '名称',
  `min_price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最小价格',
  `max_price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最大价格',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '顺序',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `is_enable` (`is_enable`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='筛选价格';

-- ----------------------------
-- Table structure for s_search_history
-- ----------------------------
DROP TABLE IF EXISTS `s_search_history`;
CREATE TABLE `s_search_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `brand_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '品牌id',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `keywords` char(80) NOT NULL DEFAULT '' COMMENT '搜索关键字',
  `screening_price` char(80) NOT NULL DEFAULT '' COMMENT '搜索价格区间',
  `order_by_field` char(60) NOT NULL DEFAULT '' COMMENT '排序类型（字段名称）',
  `order_by_type` char(60) NOT NULL DEFAULT '' COMMENT '排序方式（asc, desc）',
  `ymd` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日期 ymd',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='搜索日志';

-- ----------------------------
-- Table structure for s_slide
-- ----------------------------
DROP TABLE IF EXISTS `s_slide`;
CREATE TABLE `s_slide` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `platform` char(30) NOT NULL DEFAULT 'pc' COMMENT '所属平台（pc PC网站, h5 H5手机网站, ios 苹果APP, android 安卓APP, alipay 支付宝小程序, weixin 微信小程序, baidu 百度小程序, toutiao 头条小程序, qq QQ小程序）',
  `event_type` tinyint(2) NOT NULL DEFAULT '-1' COMMENT '事件类型（0 WEB页面, 1 内部页面(小程序或APP内部地址), 2 外部小程序(同一个主体下的小程序appid), 3 打开地图, 4 拨打电话）',
  `event_value` char(255) NOT NULL DEFAULT '' COMMENT '事件值',
  `images_url` char(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `bg_color` char(30) NOT NULL DEFAULT '' COMMENT 'css背景色值',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `is_enable` (`is_enable`),
  KEY `sort` (`sort`),
  KEY `platform` (`platform`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='轮播图片';

-- ----------------------------
-- Table structure for s_user
-- ----------------------------
DROP TABLE IF EXISTS `s_user`;
CREATE TABLE `s_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `alipay_openid` char(60) NOT NULL DEFAULT '' COMMENT '支付宝openid',
  `weixin_openid` char(60) NOT NULL DEFAULT '' COMMENT '微信openid',
  `weixin_unionid` char(60) NOT NULL DEFAULT '' COMMENT '微信unionid',
  `weixin_web_openid` char(60) NOT NULL DEFAULT '' COMMENT '微信web用户openid',
  `baidu_openid` char(60) NOT NULL DEFAULT '' COMMENT '百度openid',
  `toutiao_openid` char(60) NOT NULL DEFAULT '' COMMENT '百度openid',
  `qq_openid` char(60) NOT NULL DEFAULT '' COMMENT 'QQopenid',
  `qq_unionid` char(60) NOT NULL DEFAULT '' COMMENT 'QQunionid',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0正常, 1禁止发言, 2禁止登录）',
  `salt` char(32) NOT NULL DEFAULT '' COMMENT '配合密码加密串',
  `pwd` char(32) NOT NULL DEFAULT '' COMMENT '登录密码',
  `token` char(60) NOT NULL DEFAULT '' COMMENT 'token',
  `username` char(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` char(60) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` char(60) NOT NULL DEFAULT '' COMMENT '电子邮箱（最大长度60个字符）',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别（0保密，1女，2男）',
  `avatar` char(255) NOT NULL DEFAULT '' COMMENT '用户头像地址',
  `province` char(60) NOT NULL DEFAULT '' COMMENT '所在省',
  `city` char(60) NOT NULL DEFAULT '' COMMENT '所在市',
  `birthday` int(11) NOT NULL DEFAULT '0' COMMENT '生日',
  `address` char(150) NOT NULL DEFAULT '' COMMENT '详细地址',
  `integral` int(10) NOT NULL DEFAULT '0' COMMENT '有效积分',
  `locking_integral` int(10) NOT NULL DEFAULT '0' COMMENT '锁定积分',
  `referrer` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '推荐人用户id',
  `plugins_distribution_level` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分销等级',
  `is_delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否已删除（0否, 大于0删除时间）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `alipay_openid` (`alipay_openid`),
  KEY `weixin_openid` (`weixin_openid`),
  KEY `mobile` (`mobile`),
  KEY `username` (`username`),
  KEY `token` (`token`),
  KEY `baidu_openid` (`baidu_openid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户';

-- ----------------------------
-- Table structure for s_user_address
-- ----------------------------
DROP TABLE IF EXISTS `s_user_address`;
CREATE TABLE `s_user_address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '站点id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '站点所属用户id',
  `alias` char(60) NOT NULL DEFAULT '' COMMENT '别名',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '姓名',
  `tel` char(15) NOT NULL DEFAULT '' COMMENT '电话',
  `province` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在省',
  `city` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在市',
  `county` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在县/区',
  `address` char(80) NOT NULL DEFAULT '' COMMENT '详细地址',
  `lng` decimal(13,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lat` decimal(13,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `idcard_name` char(60) NOT NULL DEFAULT '' COMMENT '身份证姓名',
  `idcard_number` char(30) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `idcard_front` char(255) NOT NULL DEFAULT '' COMMENT '身份证人像面图片',
  `idcard_back` char(255) NOT NULL DEFAULT '' COMMENT '身份证国微面图片',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否默认地址（0否, 1是）',
  `is_delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除（0否，1删除时间戳）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_delete_time` (`is_delete_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户地址';

-- ----------------------------
-- Table structure for s_user_integral_log
-- ----------------------------
DROP TABLE IF EXISTS `s_user_integral_log`;
CREATE TABLE `s_user_integral_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '操作类型（0减少, 1增加）',
  `original_integral` int(11) NOT NULL DEFAULT '0' COMMENT '原始积分',
  `new_integral` int(11) NOT NULL DEFAULT '0' COMMENT '最新积分',
  `operation_integral` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作积分',
  `msg` char(255) DEFAULT '' COMMENT '操作原因',
  `operation_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作人员id',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户积分日志';

-- ----------------------------
-- Table structure for s_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `s_warehouse`;
CREATE TABLE `s_warehouse` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` char(60) NOT NULL DEFAULT '' COMMENT '名称',
  `alias` char(60) NOT NULL DEFAULT '' COMMENT '别名',
  `level` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权重（数字越大权重越高）',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `contacts_name` char(60) NOT NULL DEFAULT '' COMMENT '联系人姓名',
  `contacts_tel` char(15) NOT NULL DEFAULT '' COMMENT '联系电话',
  `province` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在省',
  `city` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在市',
  `county` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在县/区',
  `address` char(80) NOT NULL DEFAULT '' COMMENT '详细地址',
  `lng` decimal(13,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lat` decimal(13,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否默认（0否, 1是）',
  `is_delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除（0否，大于0删除时间戳）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `level` (`level`),
  KEY `is_enable` (`is_enable`),
  KEY `is_default` (`is_default`),
  KEY `is_delete_time` (`is_delete_time`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='仓库';

-- ----------------------------
-- Table structure for s_warehouse_goods
-- ----------------------------
DROP TABLE IF EXISTS `s_warehouse_goods`;
CREATE TABLE `s_warehouse_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `warehouse_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '仓库id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（0否，1是）',
  `inventory` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总库存',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `goods_id` (`goods_id`),
  KEY `is_enable` (`is_enable`),
  KEY `inventory` (`inventory`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='仓库商品';

-- ----------------------------
-- Table structure for s_warehouse_goods_spec
-- ----------------------------
DROP TABLE IF EXISTS `s_warehouse_goods_spec`;
CREATE TABLE `s_warehouse_goods_spec` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `warehouse_goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '仓库商品id',
  `warehouse_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '仓库id',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `md5_key` char(32) NOT NULL DEFAULT '' COMMENT 'md5key值',
  `spec` text COMMENT '规格值',
  `inventory` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `warehouse_goods_id` (`warehouse_goods_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `goods_id` (`goods_id`),
  KEY `md5_key` (`md5_key`),
  KEY `inventory` (`inventory`)
) ENGINE=InnoDB AUTO_INCREMENT=1305 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='仓库商品规格';

SET FOREIGN_KEY_CHECKS = 1;
