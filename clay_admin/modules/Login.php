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
 * ### Admin.Login
 * 管理画面のログイン処理を実行する。
 * 
 */
class Admin_Login extends Clay_Plugin_Module{
	function execute($params){
		$loader = new Clay_Plugin("admin");
		$loader->LoadSetting();
		if(empty($_SESSION["OPERATOR"])){
			if(!empty($_POST["login_id"])){
				// 管理者モデルを取得する。
				$companyOperator = $loader->loadModel("CompanyOperatorModel");
		
				// 渡されたログインIDでレコードを取得する。
				$companyOperator->findByLoginId($_POST["login_id"]);
				
				// ログインIDに該当するアカウントが無い場合
				Clay_Logger::writeDebug("Try Login AS :\r\n".var_export($companyOperator->toArray(), true));
				if(!($companyOperator->operator_id > 0)){
					Clay_Logger::writeDebug("ログインIDに該当するアカウントがありません。");
					throw new Clay_Exception_Invalid(array("ログイン情報が正しくありません。"));
				}
				
				// 保存されたパスワードと一致するか調べる。
				if($companyOperator->password != $this->encryptPassword($companyOperator->login_id, $_POST["password"])){
					Clay_Logger::writeDebug("パスワードが一致しません");
					throw new Clay_Exception_Invalid(array("ログイン情報が正しくありません。"));
				}
				
				// アクセス権限のあるサイトか調べる
				$company = $companyOperator->company();
				
				// ログインに成功した場合には管理者情報をセッションに格納する。
				$_SESSION["OPERATOR"] = $companyOperator->toArray();
			}else{
				// ログインIDが渡っていない場合には認証しない
				throw new Clay_Exception_Invalid(array());
			}
		}
		// 管理者モデルを復元する。
		$companyOperator = $loader->loadModel("CompanyOperatorModel", $_SESSION["OPERATOR"]);
		$_SERVER["ATTRIBUTES"]["OPERATOR"] = $companyOperator;
	}
}
