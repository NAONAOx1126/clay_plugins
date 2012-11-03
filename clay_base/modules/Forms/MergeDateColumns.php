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
 * ### Base.Forms.MergeDateColumns
 * 日付形式としてテキストを結合するクラスです。
 *
 * @param key 変数のキー
 * @param target 対象とするカラムのリスト
 * @param delimiter 結合時に設定するデリミタ
 * @param result 結合後のカラム
 */
class Base_Forms_MergeDateColumns extends FrameworkModule{
	function execute($params){
		$_POST[$params->get("result")] = "";
		if($params->check("year") && isset($_POST[$params->get("year")]) && is_numeric($_POST[$params->get("year")])
			&& $params->check("month") && isset($_POST[$params->get("month")]) && is_numeric($_POST[$params->get("month")])
			&& $params->check("day") && isset($_POST[$params->get("day")]) && is_numeric($_POST[$params->get("day")])){
			$_POST[$params->get("result")] .= sprintf("%04d", $_POST[$params->get("year")]);
			$_POST[$params->get("result")] .= "-";
			$_POST[$params->get("result")] .= sprintf("%02d", $_POST[$params->get("month")]);
			$_POST[$params->get("result")] .= "-";
			$_POST[$params->get("result")] .= sprintf("%02d", $_POST[$params->get("day")]);
			if(date("Y-m-d", strtotime($_POST[$params->get("result")])) != $_POST[$params->get("result")]){
				$_POST[$params->get("result")] = "";
				throw new Clay_Exception_Invalid(array("日付の指定が正しくありません。"));
			}
			if($params->check("hour") && isset($_POST[$params->get("hour")]) && is_numeric($_POST[$params->get("hour")])
				&& $params->check("minute") && isset($_POST[$params->get("minute")]) && is_numeric($_POST[$params->get("minute")])){
				if(!empty($_POST[$params->get("result")])){
					$_POST[$params->get("result")] .= " ";
				}
				$_POST[$params->get("result")] .= sprintf("%02d", $_POST[$params->get("hour")]);
				$_POST[$params->get("result")] .= ":";
				$_POST[$params->get("result")] .= sprintf("%02d", $_POST[$params->get("minute")]);
				if(date("Y-m-d H:i", strtotime($_POST[$params->get("result")])) != $_POST[$params->get("result")]){
					$_POST[$params->get("result")] = "";
					throw new Clay_Exception_Invalid(array("日付の指定が正しくありません。"));
				}
				if($params->check("second") && isset($_POST[$params->get("second")]) && is_numeric($_POST[$params->get("second")])){
					$_POST[$params->get("result")] .= ":";
					$_POST[$params->get("result")] .= sprintf("%02d", $_POST[$params->get("second")]);
					if(date("Y-m-d H:i:s", strtotime($_POST[$params->get("result")])) != $_POST[$params->get("result")]){
						$_POST[$params->get("result")] = "";
						throw new Clay_Exception_Invalid(array("日付の指定が正しくありません。"));
					}
				}
			}
		}
	}
}
?>
