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

$loader = new Clay_Plugin("facebook");
$loader->LoadCommon("Facebook");

/**
 * ### Admin.Detail
 * Facebookのログイン処理を実行し、ユーザーの情報を更新する。
 * 
 */
class Facebook_Group_MyList extends Clay_Plugin_Module{
	function execute($params){
		$_SERVER["ATTRIBUTES"][$params->get("result", "groups")] = array();
		if($_SERVER["ATTRIBUTES"]["facebook"]){
			$fbGroups = $_SERVER["ATTRIBUTES"]["facebook"]->api("/me/groups");
			foreach($fbGroups["data"] as $item){
				$_SERVER["ATTRIBUTES"][$params->get("result", "groups")][$item["id"]] = $item["name"];
			}
		}
	}
}
