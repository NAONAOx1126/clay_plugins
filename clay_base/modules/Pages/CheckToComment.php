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

class Base_Pages_CheckToComment extends Clay_Plugin_Module{
	function execute($params){
		if($params->check("key") && $params->check("param") && $params->check("result") && $params->check("text")){			
			if(is_array($_SERVER["ATTRIBUTES"][$params->get("key")]) && !empty($_SERVER["ATTRIBUTES"][$params->get("key")][$params->get("param")])){
				if($_SERVER["ATTRIBUTES"][$params->get("key")][$params->get("param")] == "1"){
					$_SERVER["ATTRIBUTES"][$params->get("key")][$params->get("result")] = $params->get("text");
				}
			}
			$source = $params->get("param");
			$destination = $params->get("result");
			if(is_object($_SERVER["ATTRIBUTES"][$params->get("key")]) && !empty($_SERVER["ATTRIBUTES"][$params->get("key")]->$source)){
				if($_SERVER["ATTRIBUTES"][$params->get("key")]->$source == "1"){
					$_SERVER["ATTRIBUTES"][$params->get("key")]->$destination = $params->get("text");
				}
			}
		}
	}
}
?>
