CREATE TABLE IF NOT EXISTS `admin_companys` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理組織ID',
  `company_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '管理組織名',
  `company_name_kana` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '管理組織名カナ',
  `zip1` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '郵便番号1',
  `zip2` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '郵便番号2',
  `pref` int(11) DEFAULT NULL COMMENT '都道府県ID',
  `address1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '住所1',
  `address2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '住所2',
  `free_tel1` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'フリーダイヤル用電話番号1',
  `free_tel2` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'フリーダイヤル用電話番号2',
  `free_tel3` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'フリーダイヤル用電話番号3',
  `tel1` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '電話番号1',
  `tel2` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '電話番号2',
  `tel3` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '電話番号3',
  `fax1` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'FAX番号1',
  `fax2` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'FAX番号2',
  `fax3` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'FAX番号3',
  `url` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'ホームページURL',
  `email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '代表メールアドレス',
  `location` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '所在地',
  `support_limit` int(11) DEFAULT NULL COMMENT '受付可能人数',
  `description` varchar(2048) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '備考',
  `contact_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '受付担当者名',
  `display_flg` int(11) NOT NULL DEFAULT '1' COMMENT '表示フラグ',
  `create_time` datetime NOT NULL COMMENT 'レコード作成日時',
  `update_time` datetime NOT NULL COMMENT 'レコード最終更新日時',
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理組織テーブル'