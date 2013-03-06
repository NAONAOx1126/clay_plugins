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
 * ### Base.Company.List
 * サイトデータのリストを取得する。
 */
class Facebook_Report_UserList extends Clay_Plugin_Module_List{
	function execute($params){
		if(!($_POST["pagesize"] > 0)){
			$_POST["pagesize"] = 50;
		}
		
		$_POST["pagesize"] = $params->get("pagesize", $_POST["pagesize"]);
		
		// ページャの初期化
		$pager = new Clay_Pager(Clay_Pager::PAGE_SLIDE, Clay_Pager::DISPLAY_ATTR, $_POST["pagesize"], 3);
		$pager->importTemplates($params);
		
		if(!empty($_POST["cmd_search"])){
			$loader = new Clay_Plugin("Facebook");
			$loader->loadSetting();
	
			// 結果格納に使うモデルクラス
			$user = $loader->loadModel("UserModel");
			
			// クエリで使うテーブルの初期化
			$users = $loader->loadTable("UsersTable");
			$comments = $loader->loadTable("PostCommentsTable");
			$likes = $loader->loadTable("LikesTable");
			
			// SELECT文の初期化
			$select = new Clay_Query_Select($users);
			
			// クエリのカラムを設定
			$select->addColumn($users->_W);
			$select->addColumn("(YEAR(CURDATE())-YEAR(".$users->birthday.")) - (RIGHT(CURDATE(),5) < RIGHT(".$users->birthday.",5))", "age");
			$select->addColumn("COUNT(".$comments->comment_id.")", "comment_count");
			$select->addColumn("MIN(".$comments->comment_time.")", "first_comment_time");
			$select->addColumn("MAX(".$comments->comment_time.")", "last_comment_time");
			$select->addColumn("COUNT(".$likes->like_id.")", "like_count");
			$select->addColumn("MIN(".$likes->create_time.")", "first_like_time");
			$select->addColumn("MAX(".$likes->create_time.")", "last_like_time");
			
			// クエリのJOINを設定
			$commentJoin = array($users->user_id." = ".$comments->user_id);
			if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $_POST["search"]["start_time"]) > 0){
				$commentJoin[] = $comments->comment_time." >= '".$_POST["search"]["start_time"]." 00:00:00'";
				$select->addWhere($comments->like_id." IS NOT NULL");
			}
			if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $_POST["search"]["end_time"]) > 0){
				$commentJoin[] = $comments->comment_time." <= '".$_POST["search"]["end_time"]." 23:59:59'";
				$select->addWhere($comments->like_id." IS NOT NULL");
			}
			$select->joinLeft($comments, $commentJoin);
			$likeJoin = array($users->user_id." = ".$likes->user_id);
			if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $_POST["search"]["start_time"]) > 0){
				$likeJoin[] = $likes->create_time." >= '".$_POST["search"]["start_time"]." 00:00:00'";
				$select->addWhere($likes->like_id." IS NOT NULL");
			}
			if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $_POST["search"]["end_time"]) > 0){
				$likeJoin[] = $likes->create_time." <= '".$_POST["search"]["end_time"]." 23:59:59'";
				$select->addWhere($likes->like_id." IS NOT NULL");
			}
			$select->joinLeft($likes, $likeJoin);
			
			// クエリのグループ
			$select->addGroupBy($users->user_id);
			
			// クエリのWHERE
			if(is_array($_POST["search"]["sex"]) && !empty($_POST["search"]["sex"])){
				$sexes = array();
				if($_POST["search"]["sex"]["1"] == "1"){
					$sexes[] = "male";
				}
				if($_POST["search"]["sex"]["2"] == "2"){
					$sexes[] = "female";
				}
				$select->addWhere($users->gender." IN ('".implode("', '", $sexes)."')");
			}
			if(is_array($_POST["search"]["age"]) && !empty($_POST["search"]["age"])){
				$conditions = array();
				for($i = 1; $i < 7; $i ++){
					if($_POST["search"]["age"][$i] == $i){
						$conditions[] = "FLOOR((YEAR(CURDATE())-YEAR(".$users->birthday.")) - (RIGHT(CURDATE(),5) < RIGHT(".$users->birthday.",5)) / 10) = ".$i;
					}
				}
				if($_POST["search"]["age"][7] == 7){
					$conditions[] = "FLOOR((YEAR(CURDATE())-YEAR(".$users->birthday.")) - (RIGHT(CURDATE(),5) < RIGHT(".$users->birthday.",5)) / 10) >= ".$i;
				}
				$select->addWhere(implode(" OR ", $conditions));
			}
			if(!empty($_POST["search"]["name"])){
				$select->addWhere($users->user_name." LIKE ?", array("%".$_POST["search"]["name"]."%"));
			}
			
			// クエリのオーダー
			if(!empty($_POST["order_key"])){
				if(strlen($_POST["order_key"]) > 3 && substr($_POST["order_key"], 0, 2) == "r:"){
					$select->addOrder(substr($_POST["order_key"], 2), true);
				}else{
					$select->addOrder($_POST["order_key"]);
				}
			}else{
				$select->addOrder($users->create_time, true);
			}
			
			// 顧客データを検索する。
			$pager->setDataSize($select->count());
			$user->limit($pager->getPageSize(), $pager->getCurrentFirstOffset());
			$_SERVER["ATTRIBUTES"][$params->get("result", "users")."_pager"] = $pager;
			$_SERVER["ATTRIBUTES"][$params->get("result", "users")] = $user->queryAllBy($select);
		}else{
			$pager->setDataSize(0);
			$_SERVER["ATTRIBUTES"][$params->get("result", "users")."_pager"] = $pager;
			$_SERVER["ATTRIBUTES"][$params->get("result", "users")] = array();
		}
	}
}
