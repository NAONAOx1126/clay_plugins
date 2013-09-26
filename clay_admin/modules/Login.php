<?php
/**
 * Copyright (C) 2012 Clay System All Rights Reserved.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Clay System
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   4.0.0
 */

/**
 * ### Admin.Login
 * 管理画面のログイン処理を実行する。
 * 
 */
class Admin_Login extends Clay_Plugin_Module{
	function execute($params){
		$loader = new Clay_Plugin("Admin");
		$loader->LoadSetting();
		if(empty($_SESSION[OPERATOR_SESSION_KEY])){
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
				
				// アカウントが有効期限内か調べる。
				if(!empty($companyOperator->start_time) && time() < strtotime($companyOperator->start_time)){
					Clay_Logger::writeDebug("アカウントが利用開始されていません。");
					throw new Clay_Exception_Invalid(array("アカウントが利用開始されていません。"));
				}
				
				// アカウントが有効期限内か調べる。
				if(!empty($companyOperator->end_time) && time() > strtotime($companyOperator->end_time)){
					Clay_Logger::writeDebug("アカウントが有効期限切れです。");
					throw new Clay_Exception_Invalid(array("アカウントが有効期限切れです。"));
				}
				
				// アクセス権限のあるサイトか調べる
				$company = $companyOperator->company();
				
				// ログインに成功した場合には管理者情報をセッションに格納する。
				$_SESSION[OPERATOR_SESSION_KEY] = $companyOperator->toArray();
			}else{
				// ログインIDが渡っていない場合には認証しない
				throw new Clay_Exception_Invalid(array());
			}
		}
		// 管理者モデルを復元する。
		$companyOperator = $loader->loadModel("CompanyOperatorModel", $_SESSION[OPERATOR_SESSION_KEY]);
		$_SERVER["ATTRIBUTES"][OPERATOR_ATTRIBUTE_KEY] = $companyOperator;
	}
}
