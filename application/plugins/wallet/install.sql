# 钱包
CREATE TABLE `{PREFIX}plugins_wallet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0正常, 1异常, 2已注销）',
  `normal_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '有效金额（包含赠送金额）',
  `frozen_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `give_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '赠送金额（所有赠送金额总计）',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `upd_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET={CHARSET} ROW_FORMAT=DYNAMIC COMMENT='钱包 - 应用';

# 钱包日志
CREATE TABLE `{PREFIX}plugins_wallet_log` (
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
) ENGINE=InnoDB DEFAULT CHARSET={CHARSET} ROW_FORMAT=DYNAMIC COMMENT='钱包日志 - 应用';

# 充值
CREATE TABLE `{PREFIX}plugins_wallet_recharge` (
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
) ENGINE=InnoDB DEFAULT CHARSET={CHARSET} ROW_FORMAT=DYNAMIC COMMENT='钱包充值 - 应用';

# 钱包提现
CREATE TABLE `{PREFIX}plugins_wallet_cash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `wallet_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '钱包id',
  `cash_no` char(60) NOT NULL DEFAULT '' COMMENT '提现单号',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0未打款, 1已打款, 2打款失败）',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `pay_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '打款金额',
  `bank_name` char(60) NOT NULL DEFAULT '' COMMENT '收款银行',
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
) ENGINE=InnoDB DEFAULT CHARSET={CHARSET} ROW_FORMAT=DYNAMIC COMMENT='钱包提现 - 应用';