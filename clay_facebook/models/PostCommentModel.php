<?php
$loader = new Clay_Plugin("facebook");
$loader->LoadCommon("Facebook");

/**
 * CSVファイルのファイル情報を扱うモデルです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Models
 * @package   File
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 */
class Facebook_PostCommentModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("facebook");
		parent::__construct($loader->loadTable("PostCommentsTable"), $values);
	}
	
	function findByPrimaryKey($comment_id){
		$this->findBy(array("comment_id" => $comment_id));
	}
	
	function findByFacebookId($facebook_id){
		$this->findBy(array("facebook_id" => $facebook_id));
	}
	
	function findAllByPost($post_id, $order = "", $reverse = false){
		return $this->findAllBy(array("post_id" => $post_id), $order, $reverse);
	}
	
	function findAllByOwner($user_id, $order = "", $reverse = false){
		return $this->findAllBy(array("user_id" => $user_id), $order, $reverse);
	}
	
	function group(){
		$loader = new Clay_Plugin("Facebook");
		$group = $loader->loadModel("GroupModel");
		$group->findByPrimaryKey($this->group_id);
		return $group;
	}

	function owner(){
		$loader = new Clay_Plugin("Facebook");
		$owner = $loader->loadModel("UserModel");
		$owner->findByPrimaryKey($this->user_id);
		return $owner;
	}
	
	function filtered_comment(){
		$comment = $this->comment;
		foreach($this->findAllByPost($this->post_id) as $userComment){
			$user = $userComment->owner();
			$comment = str_replace($user->name, "*****", $comment);
			$comment = str_replace($user->first_name, "*****", $comment);
			$comment = str_replace($user->last_name, "*****", $comment);
		}
		return $comment;
	}
}
?>