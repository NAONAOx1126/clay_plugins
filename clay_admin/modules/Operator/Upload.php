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
 * ### Admin.Operator.Upload
 * オペレータのリストを取得する。
 */
class Admin_Operator_Upload extends Clay_Plugin_Module{
	function execute($params){
		// 実行時間制限を解除
		ini_set("max_execution_time", 0);
		
		if($_FILES[$params->get("key")]["error"] == 0){
			// アップロードファイルを開く
			if(($orgFp = fopen($_FILES[$params->get("key")]["tmp_name"], "r")) !== FALSE){
				// SJISのCSVファイルをUTF8に変換
				$fp = tmpfile();
				$i = 0;
				while (($buffer = fgets($orgFp)) !== false){
					$buffer = mb_convert_encoding($buffer, "UTF-8", "Shift_JIS");
					$buffer = str_replace("\r", "\n", str_replace("\r\n", "\n", $buffer));
					$buffer = str_replace("\n", "\r\n", $buffer);
					fwrite($fp, $buffer);
				}
				rewind($fp);
				
				// ヘッダ行をスキップ
				for($i = 0; $i < $params->get("skip", 0); $i ++){
					fgetcsv($fp);
				}
				
				// トランザクションの開始
				Clay_Database_Factory::begin("admin");
				
				try{
					// データを取得
					while(($data = fgetcsv($fp)) !== FALSE){
						// データを登録
						$loader = new Clay_Plugin("Admin");
						$company = $loader->loadModel("CompanyModel");
						$company->findByPrimaryKey($data[0]);
						$company->company_name = $data[1];
						$company->email = $data[2];
						$company->open_time = $data[3];
						$company->close_time = $data[4];
						$company->support_limit = $data[5];
						$company->save();
						$operator = $company->operator();
						$operator->login_id = $data[2];
						$operator->operator_name = $data[1];
						$operator->email = $data[2];
						$operator->save();
						if(!empty($data[6])){
							foreach(explode("|", $data[6]) as $week => $weekData){
								foreach(explode(",", $weekData) as $weekday => $close_flg){
									$close = $loader->loadModel("CompanyCloseModel");
									$close->findByCompanyDay($company->company_id, $week + 1, $weekday + 1);
									$close->company_id = $company->company_id;
									$close->week = $week + 1;
									$close->weekday = $weekday + 1;
									$close->close_flg = $close_flg;
									$close->save();
								}
							}
						}
						if(!empty($data[7])){
							foreach(explode("|", $data[7]) as $dateStr){
								if(!empty($dateStr)){
									$close = $loader->loadModel("CompanyCloseSpecialModel");
									$date = date("Y-m-d", strtotime(date("Y")."-".substr($dateStr, 0, 2)."-".substr($dateStr, 0, 2)));
									$close->findByCompanyDay($company->company_id, $date);
									$close->company_id = $company->company_id;
									$close->close_day = $date;
									$close->close_flg = "1";
									$close->save();
								}
							}
						}
					}
					Clay_Database_Factory::commit("admin");
				}catch(Exception $e){
					Clay_Database_Factory::rollBack("admin");
					throw $e;
				}
			}
		}
	}
}
