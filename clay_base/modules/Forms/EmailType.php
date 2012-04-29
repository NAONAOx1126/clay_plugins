<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   3.0.0
 */

/**
 * ### Base.Forms.EmailType
 * カラムを分割するクラスです。
 *
 * @param key 変数のキー
 * @param target 対象とするメールアドレスのキー
 * @param result ドメイン照合結果を格納するカラム
 */
class Base_Forms_EmailType extends FrameworkModule{
	function execute($params){
		if(isset($_SERVER["ATTRIBUTES"][$params->get("key")]) && is_array($_SERVER["ATTRIBUTES"][$params->get("key")])){
			foreach($_SERVER["ATTRIBUTES"][$params->get("key")] as $index => $data){
				if(preg_match("/@(.+)$/", $data[$params->get("target")], $p)){
					// メールアドレスのドメインを取得する。
					$domain = $p[1];
					$loader = new PluginLoader();
					$mobileDomain = $loader->loadModel("MobileDomainModel");
					$mobileDomain->findByMobileDomain($domain);
					if($mobileDomain->mobile_domain_id > 0){
						$type = $mobileDomain->mobile_type;
					}else{
						$type = "PC";
					}
					$_SERVER["ATTRIBUTES"][$params->get("key")][$index][$params->get("result")] = $type;
				}
			}
		}
	}
}
?>
