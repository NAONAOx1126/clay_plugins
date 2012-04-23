<?php
/**
 * ### Base.Operator.Login
 * 管理画面のログイン処理を実行する。
 * 
 * @category  Module
 * @package   Operator
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   1.0.0
 */
class Base_Operator_Login extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader();
		
		// 管理者モデルを取得する。
		$companyOperator = $loader->loadModel("CompanyOperatorModel");
	
		if(empty($_SESSION["OPERATOR"])){
			// 渡されたログインIDでレコードを取得する。
			$companyOperator->findByLoginId($_POST["login_id"]);
		}else{
			// 渡されたログインIDでレコードを取得する。
			$companyOperator->findByLoginId($_SESSION["OPERATOR"]["login_id"]);
		}
			
		// ログインIDに該当するアカウントが無い場合
		Logger::writeDebug("Try Login AS :\r\n".var_export($companyOperator->toArray(), true));
		if(!($companyOperator->operator_id > 0)){
			Logger::writeDebug("ログインIDに該当するアカウントがありません。");
			throw new InvalidException(array("ログイン情報が正しくありません。"));
		}
		
		// 保存されたパスワードと一致するか調べる。
		if($companyOperator->password != sha1($companyOperator->login_id.":".$_POST["password"])){
			Logger::writeDebug("パスワードが一致しません");
			throw new InvalidException(array("ログイン情報が正しくありません。"));
		}
		
		// アクセス権限のあるサイトか調べる
		$company = $companyOperator->company();
		$site = $company->site($_SERVER["CONFIGURE"]->site_id);
		if($site->site_id != $_SERVER["CONFIGURE"]->site_id){
			Logger::writeDebug("このアカウントでは、このサイトにアクセスできません");
			throw new InvalidException(array("ログイン情報が正しくありません。"));
		}
		
		// ログインに成功した場合には管理者情報をセッションに格納する。
		$_SESSION["OPERATOR"] = $companyOperator->toArray();
		$_SERVER["ATTRIBUTES"]["OPERATOR"] = $companyOperator;
	}
}
?>
