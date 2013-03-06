<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   4.0.0
 */

/**
 * ### Base.Company.Save
 * サイトのデータを保存する。
 */
class Admin_Operator_Save extends Clay_Plugin_Module{
	function execute($params){
		// サイトデータを取得する。
		$loader = new Clay_Plugin("Admin");
		$operator = $loader->loadModel("CompanyOperatorModel");
		$operator->findByPrimaryKey($_POST["operator_id"]);
		foreach($_POST as $key => $value){
			$operator->$key = $value;
		}
		if(!empty($_POST["plain_password"])){
			$operator->password = $this->encryptPassword($operator->login_id, $operator->plain_password);
		}
		if($params->check("role")){
			$operator->role_id = $params->get("role");
		}
		
		// トランザクションの開始
		Clay_Database_Factory::begin();
		
		if(empty($_POST["operator_name"])){
			throw new Clay_Exception_Invalid(array("担当者名は必須です"));
		}
		
		try{
			$operator->save();
			$_POST["operator_id"] = $operator->operator_id;
				
			// エラーが無かった場合、処理をコミットする。
			Clay_Database_Factory::commit();
		}catch(Exception $e){
			Clay_Database_Factory::rollBack();
			throw $e;
		}
	}
}
