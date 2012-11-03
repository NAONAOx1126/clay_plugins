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
 * ### Base.Operator.Login
 * 管理画面のログイン処理を実行する。
 * 
 */
class Base_Operator_Login extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader();
		if(empty($_SESSION["OPERATOR"])){
			if(!empty($_POST["login_id"])){
				// 管理者モデルを取得する。
				$companyOperator = $loader->loadModel("CompanyOperatorModel");
		
				// 渡されたログインIDでレコードを取得する。
				$companyOperator->findByLoginId($_POST["login_id"]);
				
				// ログインIDに該当するアカウントが無い場合
				Logger::writeDebug("Try Login AS :\r\n".var_export($companyOperator->toArray(), true));
				if(!($companyOperator->operator_id > 0)){
					Logger::writeDebug("ログインIDに該当するアカウントがありません。");
					throw new Clay_Exception_Invalid(array("ログイン情報が正しくありません。"));
				}
				
				// 保存されたパスワードと一致するか調べる。
				if($companyOperator->password != sha1($companyOperator->login_id.":".$_POST["password"])){
					Logger::writeDebug("パスワードが一致しません");
					throw new Clay_Exception_Invalid(array("ログイン情報が正しくありません。"));
				}
				
				// アクセス権限のあるサイトか調べる
				$company = $companyOperator->company();
				$site = $company->site($_SERVER["CONFIGURE"]->site_id);
				if($site->site_id != $_SERVER["CONFIGURE"]->site_id){
					Logger::writeDebug("このアカウントでは、このサイトにアクセスできません");
					throw new Clay_Exception_Invalid(array("ログイン情報が正しくありません。"));
				}
				
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
?>
