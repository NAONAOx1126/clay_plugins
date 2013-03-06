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
 * ### Facebook.Theme.List
 * テーマのリストを取得する。
 */
class Facebook_Theme_List extends Clay_Plugin_Module_List{
	function execute($params){
		$this->executeImpl($params, "Facebook", "ThemeModel", $params->get("result", "fbThemes"));
	}
}
