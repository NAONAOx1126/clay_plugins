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
 * ### Base.Company.Delete
 * サイトのデータを削除する。
 */
class Facebook_Group_Delete extends Clay_Plugin_Module_Delete{
	function execute($params){
		$this->executeImpl("Facebook", "GroupModel", "group_id");
	}
}
