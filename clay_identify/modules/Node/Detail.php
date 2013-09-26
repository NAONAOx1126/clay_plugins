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
 * ### Identify.Node.Detail
 * 診断項目の詳細データを取得する。
 */
class Identify_Node_Detail extends Clay_Plugin_Module_Detail{
	function execute($params){
		$this->executeImpl("Identify", "NodeModel", $_POST["node_id"], $params->get("result", "node"));
	}
}
