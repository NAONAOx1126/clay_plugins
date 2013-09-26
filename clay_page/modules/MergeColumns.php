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
		if($params->check("target") && $params->check("result")){
			$columns = explode(",", $params->get("target"));
			$value = "";
			foreach($columns as $i => $column){
				if($i > 0){
					$value .= $params->get("delimiter");
				}
				if(is_array($_POST[$column])){
					foreach($_POST[$column] as $j => $data){
						if($i > 0 || $j > 0){
							$data .= $params->get("delimiter");
						}
						$value .= $data;
					}
				}else{
					$value .= $_POST[$column];
				}
			}
			$_POST[$params->get("result")] = $value;
		}
	}
}
?>
