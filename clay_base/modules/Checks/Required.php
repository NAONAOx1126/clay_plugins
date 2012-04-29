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
 * ### Base.Checks.Required
 * 必須入力のチェックを行うCheckパッケージのクラスです。
 *
 * @param key チェック対象のキー名
 * @param value チェック対象の名称
 * @param suffix エラーメッセージのサフィックス
 */
class Base_Checks_Required extends FrameworkModule{
	/**
	 * モジュールのエンドポイント
	 * @param $param モジュールのパラメータオブジェクト
	 */
	function execute($params){
		if(!is_array($_SERVER["ERRORS"])){
			$_SERVER["ERRORS"] = array();
		}
		if((!isset($_POST[$params->get("key")]) || $_POST[$params->get("key")] == "") && empty($_SERVER["ERRORS"][$params->get("key")])){
			$_SERVER["ERRORS"][$params->get("key")] = $params->get("value").$params->get("suffix", "が未入力です。");
		}
	}
}
?>
