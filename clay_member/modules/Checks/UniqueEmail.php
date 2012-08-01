<?php
/**
 * 必須入力のチェックを行うCheckパッケージのクラスです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Modules
 * @package   Check
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 */

class Member_Checks_UniqueEmail extends FrameworkModule{
	function execute($params){
		if(!is_array($_SERVER["ERRORS"])){
			$_SERVER["ERRORS"] = array();
		}
		
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();

		// 商品データを検索する。
		$customer = $loader->LoadModel("CustomerModel");
		if(!empty($_POST["email"])){
			$customer->findByEmail($_POST["email"]);
			if($customer->customer_id > 0 && $_POST["customer_id"] != $customer->customer_id){
				throw new InvalidException(array("このメールアドレスは既に登録されております。"));
			}else{
				$customer->findByEmailMobile($_POST["email"]);
				if($customer->customer_id > 0 && $_POST["customer_id"] != $customer->customer_id){
					throw new InvalidException(array("このメールアドレスは既に登録されております。"));
				}
			}
		}
		if(!empty($_POST["email_mobile"])){
			$customer->findByEmail($_POST["email_mobile"]);
			if($customer->customer_id > 0 && $_POST["customer_id"] != $customer->customer_id){
				throw new InvalidException(array("このメールアドレスは既に登録されております。"));
			}else{
				$customer->findByEmailMobile($_POST["email_mobile"]);
				if($customer->customer_id > 0 && $_POST["customer_id"] != $customer->customer_id){
					throw new InvalidException(array("このメールアドレスは既に登録されております。"));
				}
			}
		}
	}
}
?>
