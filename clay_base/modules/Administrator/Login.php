<?php
/**
 * ### Base.Administrator.Login
 * 管理画面のログイン処理を実行する。
 * 
 * @category  Module
 * @package   Administrator
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   1.0.0
 */
class Base_Administrator_Login extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader();
		if(empty($_SESSION["ADMINISTRATOR"])){
			// 管理者モデルを取得する。
			$site = $loader->loadModel("SiteModel");
	
			// 渡されたログインIDでレコードを取得する。
			$site->findByPrimaryKey($_SERVER["CONFIGURE"]->site_id);
			
			// ログインIDが一致しない場合
			if($site->site_code != $_POST["login_id"]){
				throw new InvalidException(array("ログイン情報が正しくありません。"));
			}
			
			// 保存されたパスワードと一致するか調べる。
			if($site->site_password != $_POST["password"]){
				throw new InvalidException(array("ログイン情報が正しくありません。"));
			}
			
			// ログインに成功した場合には管理者情報をセッションに格納する。
			$_SESSION["ADMINISTRATOR"] = $site->toArray();
		}
		
		// 管理者モデルを復元する。
		$site = $loader->loadModel("SiteModel", $_SESSION["ADMINISTRATOR"]);
		$_SERVER["ATTRIBUTES"]["ADMINISTRATOR"] = $site;
	}
}
?>
