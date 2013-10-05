CREATE TABLE IF NOT EXISTS `address_zips` (
  `code` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '郵便番号コード',
  `old_zipcode` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '旧郵便番号',
  `zipcode` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '郵便番号',
  `state_kana` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '都道府県カナ',
  `city_kana` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '市区町村カナ',
  `town_kana` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '町村番地カナ',
  `state` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '都道府県',
  `city` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '市区町村',
  `town` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '町村番地',
  `flg1` tinyint(1) DEFAULT NULL COMMENT 'フラグ1',
  `flg2` tinyint(1) DEFAULT NULL COMMENT 'フラグ2',
  `flg3` tinyint(1) DEFAULT NULL COMMENT 'フラグ3',
  `flg4` tinyint(1) DEFAULT NULL COMMENT 'フラグ4',
  `flg5` tinyint(1) DEFAULT NULL COMMENT 'フラグ5',
  `flg6` tinyint(1) DEFAULT NULL COMMENT 'フラグ6',
  KEY `zipcode` (`zipcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='郵便番号テーブル'