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
 * 携帯の個体番号をPOSTパラメータに取り込む。
 */
class Base_Pages_MobileCode extends Clay_Plugin_Module{
	function execute($params){
		if($params->check("code")){
			// アクセスしてきたモバイルIDを取得
			if(!empty($_SERVER["HTTP_X_DCMGUID"])){
				// ドコモID(GUID対応)
				$mobileId = $_SERVER["HTTP_X_DCMGUID"];
			}elseif(!empty($_SERVER["HTTP_X_UP_SUBNO"])){
				// au端末の場合
				$mobileId = $_SERVER["HTTP_X_UP_SUBNO"];
			}elseif(!empty($_SERVER["HTTP_X_JPHONE_UID"])){
				// Softbank端末の場合
				$mobileId = $_SERVER["HTTP_X_JPHONE_UID"];
			}elseif(preg_match("/^.+ser([0-9a-zA-Z]+).*$/", $_SERVER["HTTP_USER_AGENT"], $ua) > 0){
				// ドコモのユーザーエージェントから取得
				$mobileId = $ua[1];
			}elseif(preg_match("/^.+\/SN([0-9a-zA-Z]+).*$/", $_SERVER["HTTP_USER_AGENT"], $ua) > 0){
				// Softbankのユーザーエージェントから取得
				$mobileId = $ua[1];
			}else{
				$mobileId = $_SESSION["MOBILE_GUID"];
			}
			
			// モバイルIDが設定おらず、guidが未設定の場合は、GUID付きのURLにリダイレクトする。
			if(empty($mobileId)){
				if(empty($_POST["guid"])){
					header("Location: ".((strpos($_SERVER["REQUEST_URI"], "?") > 0)?$_SERVER["REQUEST_URI"]."&guid=ON":$_SERVER["REQUEST_URI"]."?guid=ON"));
					exit;
				}
				// guidが設定されていても取得できない場合は、エラーとする。
				throw new Clay_Exception_Invalid(array("個体番号の取得に失敗しました。"));
			}
			
			// 個体番号を取得できた場合は、パラメータとして取得
			$_POST[$params->get("code")] = $mobileId;
		}
	}
}
?>
