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
 * ### Base.Checks.TextValidate
 * テキストに正規表現が含まれるかどうかのチェックを行うCheckパッケージのクラスです。
 *
 * @param key チェック対象のキー名
 * @param value チェック対象の名称
 * @param suffix エラーメッセージのサフィックス
 */
class Error_Check_TextValidate extends Clay_Plugin_Module{
	function execute($params){
		if($params->check("key")){
			if(!is_array($_SERVER["ERRORS"])){
				$_SERVER["ERRORS"] = array();
			}
			
			// サイトのコンテンツを取得
			$text = $_POST[$params->get("key")];
			if(!empty($text)){
				// テキストに正規表現が含まれているかどうかチェック
				if(preg_match($params->get("regex"), $text, $p) > 0){
					$_SERVER["URL_PARAMS"][$params->get("key")] = $p;
				}else{
					$_SERVER["ERRORS"][$params->get("key")] = $params->get("value").$params->get("suffix", "は正しくありません。");
				}
			}
		}
	}
}
