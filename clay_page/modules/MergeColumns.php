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
 * ### Page.MergeColumns
 * カラムを結合するクラスです。
 *
 * @param key 変数のキー
 * @param target 対象とするカラムのリスト
 * @param delimiter 結合時に設定するデリミタ
 * @param result 結合後のカラム
 */
class Page_MergeColumns extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_SERVER["ATTRIBUTES"][$params->get("key")]) && is_array($_SERVER["ATTRIBUTES"][$params->get("key")])){
			foreach($_SERVER["ATTRIBUTES"][$params->get("key")] as $index => $data){
				$columns = explode(",", $params->get("target"));
				$value = "";
				foreach($columns as $i => $column){
					if($i > 0){
						$value .= $params->get("delimiter");
					}
					$value .= $data[$column];
				}
				$_SERVER["ATTRIBUTES"][$params->get("key")][$index][$params->get("result")] = $value;
			}
		}
	}
}
?>
