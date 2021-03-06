<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   4.0.0
 */

/**
 * ### Base.Checks.ContentValidate
 * コンテンツに正規表現が含まれるかどうかのチェックを行うCheckパッケージのクラスです。
 *
 * @param key チェック対象のキー名
 * @param message エラーメッセージ
 */
class Base_Checks_ContentValidate extends Clay_Plugin_Module{
	/**
	 * モジュールのエンドポイント
	 */
	function execute($params){
		if($params->check("key")){
			if(!is_array($_SERVER["ERRORS"])){
				$_SERVER["ERRORS"] = array();
			}
			
			// サイトのコンテンツを取得
			$url = $_POST[$params->get("key")];
			if(!empty($url)){
				if(!isset($_SERVER["URL_CONTENTS"][$params->get("key")]) || empty($_SERVER["URL_CONTENTS"][$params->get("key")])){
					ob_start();
					$_SERVER["URL_CONTENTS"][$params->get("key")] = file_get_contents($url);
					ob_end_clean();
				}
				
				// コンテンツに正規表現が含まれているかどうかチェック
				if(preg_match($params->get("regex"), $_SERVER["URL_CONTENTS"][$params->get("key")], $p) > 0){
					$_SERVER["URL_PARAMS"][$params->get("key")] = $p;
				}else{
					$_SERVER["ERRORS"][$params->get("key")] = $params->get("message", "該当のサイトには必要なタグが含まれていません。");
				}
			}
		}
	}
}
