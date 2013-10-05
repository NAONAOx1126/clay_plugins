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
 * ### Admin.Operator.Download
 * オペレータのリストを取得する。
 */
class Admin_Operator_Download extends Clay_Plugin_Module_Page{
	function execute($params){
		if(!$params->check("search") || isset($_POST[$params->get("search")])){
			// 実行時間制限を解除
			ini_set("max_execution_time", 0);
			
			$_POST["pageID"] = 1;
			parent::executeImpl($params, "Admin", "CompanyModel", "companys");
		
			// ヘッダを送信
			header("Content-Type: application/csv");
			header("Content-Disposition: attachment; filename=\"".$params->get("prefix", "csvfile").date("YmdHis").".csv\"");
			
			$titles = array("ID", "店舗名", "メールアドレス", "営業開始時間", "営業終了時間", "同時受付上限", "定休日", "不定休日");
			
			// CSVヘッダを出力
			echo mb_convert_encoding("\"".implode("\",\"", $titles)."\"\r\n", "Shift_JIS", "UTF-8");
			
			// データが０件以上の場合は繰り返し
			while(count($_SERVER["ATTRIBUTES"]["companys"]) > 0){
				foreach($_SERVER["ATTRIBUTES"]["companys"] as $company){
					$operator = $company->operator();
					echo mb_convert_encoding("\"".$company->company_id."\"", "Shift_JIS", "UTF-8");
					echo mb_convert_encoding(",\"".$company->company_name."\"", "Shift_JIS", "UTF-8");
					echo mb_convert_encoding(",\"".$operator->login_id."\"", "Shift_JIS", "UTF-8");
					echo mb_convert_encoding(",\"".$company->open_time."\"", "Shift_JIS", "UTF-8");
					echo mb_convert_encoding(",\"".$company->close_time."\"", "Shift_JIS", "UTF-8");
					echo mb_convert_encoding(",\"".$company->support_limit."\"", "Shift_JIS", "UTF-8");
					echo mb_convert_encoding(",\"", "Shift_JIS", "UTF-8");
					$closes = array(
							array(0, 0, 0, 0, 0, 0, 0), 
							array(0, 0, 0, 0, 0, 0, 0), 
							array(0, 0, 0, 0, 0, 0, 0), 
							array(0, 0, 0, 0, 0, 0, 0), 
							array(0, 0, 0, 0, 0, 0, 0)
						);
					foreach($company->closes() as $close){
						$closes[$close->week - 1][$close->weekday - 1] = $close->close_flg;
					}
					foreach($closes as $i => $c1){
						if($i > 0) echo "|";
						echo mb_convert_encoding(implode(",", $c1), "Shift_JIS", "UTF-8");
					}
					echo mb_convert_encoding("\"", "Shift_JIS", "UTF-8");
					echo mb_convert_encoding(",\"", "Shift_JIS", "UTF-8");
					$specialCloses = array();
					foreach($company->specialCloses() as $close){
						if($close->close_flg == "1"){
							$specialCloses[] = mb_convert_encoding(substr($close->close_day, 5, 2).substr($close->close_day, 8, 2), "Shift_JIS", "UTF-8");
						}
					}
					echo implode("|", $specialCloses);
					echo mb_convert_encoding("\"", "Shift_JIS", "UTF-8");
					echo mb_convert_encoding("\r\n", "Shift_JIS", "UTF-8");
				}
				$_POST["pageID"] ++;
				parent::executeImpl($params, "Admin", "CompanyModel", "companys");
			}
			exit;
		}
	}
}
