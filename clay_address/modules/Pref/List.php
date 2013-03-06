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
 * ### Address.Pref.List
 * 都道府県のリストを取得する。
 */
class Address_Pref_List extends Clay_Plugin_Module_List{
	function execute($params){
		$this->executeImpl($params, "Address", "PrefModel", $params->get("result", "prefs"));
	}
}
?>
