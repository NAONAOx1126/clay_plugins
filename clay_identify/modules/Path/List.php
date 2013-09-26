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
 * ### Identify.Path.List
 * 診断パスのリストを取得する。
 */
class Identify_Path_List extends Clay_Plugin_Module_List{
	function execute($params){
		$this->executeImpl($params, "Identify", "PathModel", $params->get("result", "path"));
	}
}
