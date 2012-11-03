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

/**
 * 一意性をある程度考慮したランダムコードを発行します。
 */
class Base_Pages_UniqueCode extends Clay_Plugin_Module{
	function execute($params){
		if($params->check("code")){
			// ランダムコードを発行します。
			$result = "";
			$codes = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			for($i = 0; $i < $params->get("length", "5"); $i ++){
				$result .= substr($codes, mt_rand(0, strlen($codes) - 1), 1);
			}
			// uniqidをランダム数値化します。
			$uid = uniqid();
			$num = 0;
			for($i = 0; $i < strlen($uid); $i ++){
				$token = substr($uid, $i, 1);
				if($token == "a"){
					$token = 10;
				}elseif($token == "b"){
					$token = 11;
				}elseif($token == "c"){
					$token = 12;
				}elseif($token == "d"){
					$token = 13;
				}elseif($token == "e"){
					$token = 14;
				}elseif($token == "f"){
					$token = 15;
				}
				$num = $num * 16 + $token;
			}
			while($num > strlen($codes)){
				$result .= $codes[$num % strlen($codes)];
				$num = $num - ($num % strlen($codes)) / strlen($codes);
			}
			$result .= $codes[$num];
			
			if($params->check("original", "0") == "0" || !isset($_POST[$params->get("code")])){
				$_POST[$params->get("code")] = uniqid($result);
			}
		}
	}
}
?>
