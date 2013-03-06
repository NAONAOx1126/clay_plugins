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
class Facebook_Report_QuestionList extends Clay_Plugin_Module_List{
	function execute($params){
		if(!empty($_POST["cmd_search"])){
			$loader = new Clay_Plugin("Facebook");
			$loader->loadSetting();
	
			// 結果格納に使うモデルクラス
			$post = $loader->loadModel("PostModel");
			
			// クエリで使うテーブルの初期化
			$posts = $loader->loadTable("PostsTable");
			$comments = $loader->loadTable("PostCommentsTable");
			$votes = $loader->loadTable("PostVotesTable");
			$users = $loader->loadTable("UsersTable");
			
			// SELECT文の初期化
			$select = new Clay_Query_Select($posts);
			
			// クエリのカラムを設定
			$select->addColumn($posts->_W);
			$select->addColumn($comments->comment);
			for($i = 1; $i <= 5; $i ++){
				$option_name = "option".$i;
				$select->addColumn("SUM(".$votes->$option_name.")", $option_name."_real");
			}
			
			// クエリのJOINを設定
			$select->join($comments, array($posts->comment_id." = ".$comments->comment_id));
			$select->joinLeft($votes, array($posts->post_id." = ".$votes->post_id));
			$select->joinLeft($users, array($votes->user_id." = ".$users->user_id));
			
			// option1が無い場合は質問として見ない。
			$select->addWhere($posts->option1." IS NOT NULL");

			// クエリのWHERE
			if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $_POST["search"]["start_time"]) > 0){
				$select->addWhere($posts->end_time." < ?", array($_POST["search"]["start_time"]." 00:00:00"));
			}
			if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $_POST["search"]["end_time"]) > 0){
				$select->addWhere($posts->start_time." > ?", array($_POST["search"]["end_time"]." 23:59:59"));
			}
			if(is_array($_POST["search"]["sex"]) && !empty($_POST["search"]["sex"])){
				$sexes = array();
				if($_POST["search"]["sex"]["1"] == "1"){
					$sexes[] = "male";
				}
				if($_POST["search"]["sex"]["2"] == "2"){
					$sexes[] = "female";
				}
				if(!empty($sexes)){
					$select->addWhere($users->gender." IN ('".implode("', '", $sexes)."')");
				}
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
				if(!empty($conditions)){
					$select->addWhere(implode(" OR ", $conditions));
				}
			}
			if(!empty($_POST["search"]["theme_id"])){
				$select->addWhere($posts->theme_id." = ?", array($_POST["search"]["theme_id"]));
			}
			if(!empty($_POST["search"]["title"])){
				$select->addWhere($comments->comment." LIKE ?", array("%".$_POST["search"]["title"]."%"));
			}
			
			// クエリのグループ
			$select->addGroupBy($posts->post_id);
			
			// クエリのオーダー
			$select->addOrder($posts->start_time, ($_POST["search"]["post_sort"] == "asc")?false:true);
			
			// コメントの生リストを取得する。
			$questionList = $post->queryAllBy($select);
			
			// 顧客データを検索する。
			$_SERVER["ATTRIBUTES"][$params->get("result", "questions")] = $questionList;
		}else{
			$_SERVER["ATTRIBUTES"][$params->get("result", "questions")] = array();
		}
	}
}
