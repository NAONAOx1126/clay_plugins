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
 * ### Identify.Node.Delete
 * 診断項目のデータを削除する。
 */
class Identify_Node_Delete extends Clay_Plugin_Module_Delete{
	function execute($params){
		$this->executeImpl("Identify", "NodeModel", "node_id");
	}
}
