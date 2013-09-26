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
class Facebook_Report_CommentList extends Clay_Plugin_Module_List{
	function execute($params){
		if(!empty($_POST["cmd_search"])){
			$loader = new Clay_Plugin("Facebook");
			$loader->loadSetting();
	
			// 結果格納に使うモデルクラス
			$user = $loader->loadModel("UserModel");
			
			// クエリで使うテーブルの初期化
			$users = $loader->loadTable("UsersTable");
			$comments = $loader->loadTable("PostCommentsTable");
			
			// SELECT文の初期化
			$select = new Clay_Query_Select($users);
			
			// クエリのカラムを設定
			$select->addColumn($users->_W);
			$select->addColumn("(YEAR(CURDATE())-YEAR(".$users->birthday.")) - (RIGHT(CURDATE(),5) < RIGHT(".$users->birthday.",5))", "age");
			$select->addColumn($comments->comment_time);
			$select->addColumn($comments->comment);
			
			// クエリのJOINを設定
			$commentJoin = array($users->user_id." = ".$comments->user_id);
			if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $_POST["search"]["start_time"]) > 0){
				$commentJoin[] = $comments->comment_time." >= '".$_POST["search"]["start_time"]." 00:00:00'";
				$select->addWhere($comments->comment_id." IS NOT NULL");
			}
			if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $_POST["search"]["end_time"]) > 0){
				$commentJoin[] = $comments->comment_time." <= '".$_POST["search"]["end_time"]." 23:59:59'";
				$select->addWhere($comments->comment_id." IS NOT NULL");
			}
			$select->joinLeft($comments, $commentJoin);
			
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
						$conditions[] = "FLOOR(FLOOR((YEAR(CURDATE())-YEAR(".$users->birthday.")) - (RIGHT(CURDATE(),5) < RIGHT(".$users->birthday.",5)) / 10) / 10) = ".$i;
					}
				}
				if($_POST["search"]["age"][7] == 7){
					$conditions[] = "FLOOR(FLOOR((YEAR(CURDATE())-YEAR(".$users->birthday.")) - (RIGHT(CURDATE(),5) < RIGHT(".$users->birthday.",5)) / 10) / 10) >= ".$i;
				}
				$select->addWhere(implode(" OR ", $conditions));
			}
			if(!empty($_POST["search"]["name"])){
				$select->addWhere($users->user_name." LIKE ?", array("%".$_POST["search"]["name"]."%"));
			}
			
			// キーワード検索
			$keywordWhere = "";
			$keywordValue = array();
			for($i = 1; $i <= 3; $i ++){
				if(isset($_POST["search"]["keyword".$i]) && trim($_POST["search"]["keyword".$i]) != ""){
					if($keywordWhere != ""){
						$keywordWhere .= (($_POST["search"]["keyword".$i."_connect"] == "OR")?" OR ":" AND ");
					}
					$keywordWhere .= $comments->comment.(($_POST["search"]["keyword".$i."_contain"] == "1")?" LIKE ?":" NOT LIKE ?");
					$keywordValue[] = "%".$_POST["search"]["keyword".$i]."%";
				}
			}
			if($keywordWhere != ""){
				$select->addWhere($keywordWhere, $keywordValue);
			}
			
			// クエリのオーダー
			$select->addOrder($comments->comment_time, ($_POST["search"]["comment_sort"] == "asc")?false:true);
			
			// コメントの生リストを取得する。
			$commentList = $user->queryAllBy($select);
			$users = array();
			$times = array();
			foreach($commentList as $comment){
				$day = date("Y-m-d", strtotime($comment->comment_time));
				$times[$day] = $day;
				$users[$comment->user_id] = array(
					"user_id" => $comment->user_id,
					"facebook_id" => $comment->facebook_id,
					"name" => $comment->name,
					"gender" => $comment->gender,
					"account_name" => $comment->account_name,
					"birthday" => $comment->birthday,
					"age" => $comment->age,
					"email" => $comment->email,
					"picture_url" => $comment->picture_url
				);
			}
			
			// ユーザーコメントデータの枠を作成
			$userComments = array();
			foreach($users as $user_id => $user){
				$user["comments"] = array();
				foreach($times as $day){
					$user["comments"][$day] = array();
				}
				$userComments[$user_id] = $user;
			}
			
			// コメントを設定
			foreach($commentList as $comment){
				$userComments[$comment->user_id]["comments"][date("Y-m-d", strtotime($comment->comment_time))][date("H:i:s", strtotime($comment->comment_time))] = $comment->comment;
			}
			
			// 顧客データを検索する。
			$_SERVER["ATTRIBUTES"][$params->get("result", "comments")] = $userComments;
		}else{
			$_SERVER["ATTRIBUTES"][$params->get("result", "comments")] = array();
		}
	}
}
