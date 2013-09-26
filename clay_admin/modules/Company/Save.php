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
 * ### Admin.Company.Save
 * 組織のデータを保存する。
 */
class Admin_Company_Save extends Clay_Plugin_Module_Save{
	function execute($params){
		$this->continue = "1";
		$this->executeImpl("Admin", "CompanyModel", "company_id");
		
		// トランザクションの開始
		Clay_Database_Factory::begin("admin");

		try{
		
			$company_id = $_POST["company_id"];
			$loader = new Clay_Plugin("Admin");
			$model = $loader->loadModel("CompanyCloseModel");
			foreach($_POST["close_flg"] as $week => $closes){
				foreach($closes as $weekday => $close){
					$model = $loader->loadModel("CompanyCloseModel");
					$model->findByCompanyDay($company_id, $week, $weekday);
					$model->company_id = $company_id;
					$model->week = $week;
					$model->weekday = $weekday;
					$model->close_flg = $close;
					$model->save();
				}
			}
			
			$model = $loader->loadModel("CompanyCloseSpecialModel");
			$result = $model->findAllByCompanyId($company_id);
			foreach($result as $data){
				$data->delete();
			}
			foreach($_POST["close_flg2"] as $close){
				if(!empty($close["year"]) && !empty($close["month"]) && !empty($close["day"])){
					$model = $loader->loadModel("CompanyCloseSpecialModel");
					$model->company_id = $company_id;
					$model->close_day = $close["year"]."-".$close["month"]."-".$close["day"];
					$model->close_flg = "1";
					$model->save();
				}
			}
			
			// エラーが無かった場合、処理をコミットする。
			Clay_Database_Factory::commit("admin");
			if($this->continue != "1"){
				$this->removeInput("add");
				$this->removeInput("save");
				$this->reload();
			}
		}catch(Exception $e){
			Clay_Database_Factory::rollBack(strtolower($type));
			throw $e;
		}
	}
}
