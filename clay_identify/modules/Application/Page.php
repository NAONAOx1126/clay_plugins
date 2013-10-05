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
 * ### Identify.Application.Page
 * 診断アプリケーションのリストをページング付きで取得する。
 */
class Identify_Application_Page extends Clay_Plugin_Module_Page{
	function execute($params){
		$this->executeImpl($params, "Identify", "ApplicationModel", $params->get("result", "application"));
	}
}