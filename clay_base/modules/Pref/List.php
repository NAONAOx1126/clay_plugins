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
 * ### Base.Pref.List
 * 都道府県のリストを取得する。
 */
class Base_Pref_List extends Clay_Plugin_Module{
	function execute($params){
		// モデルの初期化
		$loader = new Clay_Plugin();
		$pref = $loader->loadModel("PrefModel");
		
		$prefs = $pref->findAllBy();
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "prefs")] = $prefs;
	}
}
?>
