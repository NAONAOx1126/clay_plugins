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
 * ### Base.Checks.Alnums
 * 半角英数かどうかのチェックを行うCheckパッケージのクラスです。
 * 
 * @param key チェック対象のキー名
 * @param value チェック対象の名称
 * @param suffix エラーメッセージのサフィックス
 */
class Base_Checks_Alnums extends Clay_Plugin_Module{
	/**
	 * モジュールのエンドポイント
	 */
	function execute($params){
		if(!is_array($_SERVER["ERRORS"])){
			$_SERVER["ERRORS"] = array();
		}
		if(!empty($_POST[$params->get("key")]) && !ctype_alnum($_POST[$params->get("key")])){
			$_SERVER["ERRORS"][$params->get("key")] = $params->get("value").$params->get("suffix", "は半角英数字で入力してください。");
		}
	}
}
