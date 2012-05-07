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
 * ### Base.Forms.MasterMaintenance
 * 画面に対してマスタメンテナンスの制御を行うモジュールです。
 *
 * @param table メンテナンス対象のテーブル名
 * @param requires 必須入力項目のコード
 */
class Base_Forms_MasterMaintenance extends FrameworkModule{
	function execute($params){
		if($params->check("table")){
			// テーブルのロード情報を取得する。
			$tableName = $params->get("table");
			$tableNameComponents = explode("_", $tableName);
			$tableClass = array_shift($tableNameComponents);
			$tableClass = strtoupper(substr($tableClass, 0, 1)).strtolower(substr($tableClass, 1));
			$tableClass = $params->get("class", $tableClass);
			$tableModule = "";
			foreach($tableNameComponents as $comp){
				$tableModule .= strtoupper(substr($comp, 0, 1)).strtolower(substr($comp, 1));
			}
			$tableModule .= "Table";
			
			// テーブルのオブジェクトをロードする。
			if($tableClass == "Base"){
				$loader = new PluginLoader();
			}else{
				$loader = new PluginLoader($tableClass);
			}
			$table = $loader->loadTable($tableModule);
			
			// 削除処理
			if(isset($_POST["delete"])){
				// トランザクションの開始
				DBFactory::begin();
				
				try{
					// DBのデータを削除
					$delete = new DatabaseDelete($table);
					foreach($_POST as $key => $value){
						if(in_array($key, $table->getColumns())){
							$delete->addWhere($table->$key." = ?", array($value));
						}
					}
					$delete->execute();
					// コミット
					DBFactory::commit();
				}catch(Exception $e){
					DBFactory::rollback();
				}
			}
			
			// 登録処理
			if(isset($_POST["regist"])){
				// 必須入力項目を取得
				if($params->check("requires")){
					$requires = explode(",", $params->get("requires"));
				}else{
					$requires = array();
				}
				
				// 新規登録がある場合はデータを追加
				foreach($_POST["newData"] as $key => $value){
					if(!empty($value)){
						$_POST["data"][] = $_POST["newData"];
						break;
					}
				}
				
				// 必須入力チェック
				foreach($_POST["data"] as $data){
					foreach($requires as $key){
						if(empty($data[$key])){
							// データを取得
							$select = new DatabaseSelect($table);
							$result = $select->addColumn($table->_W)->execute();
							$_SERVER["ATTRIBUTES"]["data"] = $result;
							// 全ての入力を無効にする。
							$_SESSION["INPUT_DATA"][TEMPLATE_DIRECTORY] = array();
							$_POST = array();
							throw new InvalidException(array("必須入力の項目が未入力です。"));
						}
					}
				}
				
				// トランザクションの開始
				DBFactory::begin();
				
				try{
					// DBの内容をクリア
					$truncate = new DatabaseTruncate($table);
					$truncate->execute();
					// DBにデータを登録
					$insert = new DatabaseInsert($table);
					foreach($_POST["data"] as $data){
						if(empty($data["create_time"])){
							$data["create_time"] = $data["update_time"] = date("Y-m-d H:i:s");
						}else{
							$data["update_time"] = date("Y-m-d H:i:s");
						}
						$insert->execute($data);
					}
					// コミット
					DBFactory::commit();
				}catch(Exception $e){
					DBFactory::rollback();
				}
			}
			
			// データを取得
			$select = new DatabaseSelect($table);
			$result = $select->addColumn($table->_W)->execute();
			$_SERVER["ATTRIBUTES"]["data"] = $result;
			
			// 全ての入力を無効にする。
			$_SESSION["INPUT_DATA"][TEMPLATE_DIRECTORY] = array();
			$_POST = array();
		}
	}
}
?>
