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
 * ### Identify.Application.Save
 * 診断アプリケーションのデータを保存する。
 */
class Identify_Application_Save extends Clay_Plugin_Module_Save{
	function execute($params){
		$this->executeImpl("Identify", "ApplicationModel", "application_id");
	}
}
