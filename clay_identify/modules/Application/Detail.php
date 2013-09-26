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
 * ### Identify.Application.Detail
 * 診断アプリケーションの詳細データを取得する。
 */
class Identify_Application_Detail extends Clay_Plugin_Module_Detail{
	function execute($params){
		$this->executeImpl("Identify", "ApplicationModel", $_POST["application_id"], $params->get("result", "application"));
	}
}
