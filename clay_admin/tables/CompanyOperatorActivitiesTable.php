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
 * @copyright Copyright (c) 2013, Clay System
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   4.0.0
 */
/**
 * admin_company_operator_activitiesテーブルの定義クラスです。
 */
class Admin_CompanyOperatorActivitiesTable extends Clay_Plugin_Table{
    /**
     * コンストラクタです。
     */
    public function __construct(){
        $this->db = Clay_Database_Factory::getConnection("admin");
        parent::__construct("admin_company_operator_activities", "admin");
    }
    /**
     * テーブルを作成するためのスタティックメソッドです。。
     */
    public static function install(){
        $connection = Clay_Database_Factory::getConnection("admin");
        $connection->query(file_get_contents(dirname(__FILE__)."/../sqls/company_operator_activities.sql"));
    }
}
