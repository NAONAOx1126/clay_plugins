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
 * ### Admin.Operator.Save
 * オペレータのデータを保存する。
 */
class Admin_Operator_Save extends Clay_Plugin_Module_Save{
	function execute($params){
		if(isset($_POST["password"]) && $_POST["password"] != ""){
			$_POST["password"] = $this->encryptPassword($_POST["login_id"], $_POST["password"]);
		}else{
			unset($_POST["password"]);
		}
		$this->executeImpl("Admin", "CompanyOperatorModel", "operator_id");
	}
}
