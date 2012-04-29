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
 * ### Base.Forms.SplitColumns
 * カラムを分割するクラスです。
 *
 * @param key 変数のキー
 * @param target 対象とするキー
 * @param regex クリアする対象の変数
 * @param result 分割対象のカラムリスト
 */
class Base_Forms_SplitColumns extends FrameworkModule{
	function execute($params){
		if(isset($_SERVER["ATTRIBUTES"][$params->get("key")]) && is_array($_SERVER["ATTRIBUTES"][$params->get("key")])){
			foreach($_SERVER["ATTRIBUTES"][$params->get("key")] as $index => $data){
				if(preg_match("/".$params->get("regex")."/", $data[$params->get("target")], $p)){
					$columns = explode(",", $params->get("result"));
					foreach($p as $i => $param){
						$_SERVER["ATTRIBUTES"][$params->get("key")][$index][$columns[$i]] = $param;
					}
				}
			}
		}
	}
}
?>
