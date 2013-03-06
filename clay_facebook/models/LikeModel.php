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
class Facebook_LikeModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Facebook");
		parent::__construct($loader->loadTable("LikesTable"), $values);
	}
	
	function findByPrimaryKey($like_id){
		$this->findBy(array("like_id" => $like_id));
	}
	
	function findAllByPost($post_id, $order = "", $reverse = false){
		return $this->findAllBy(array("post_id" => $post_id, "comment_id" => 0), $order, $reverse);
	}
	
	function findAllByComment($comment_id, $order = "", $reverse = false){
		return $this->findAllBy(array("comment_id" => $comment_id), $order, $reverse);
	}
	
	function findAllByUser($user_id, $order = "", $reverse = false){
		return $this->findAllBy(array("user_id" => $user_id), $order, $reverse);
	}
	
	function post(){
		$loader = new Clay_Plugin("Facebook");
		$post = $loader->loadModel("PostModel");
		$post->findByPrimaryKey($this->post_id);
		return $post;
	}

	function comment(){
		$loader = new Clay_Plugin("Facebook");
		$comment = $loader->loadModel("PostCommentModel");
		$comment->findByPrimaryKey($this->comment_id);
		return $comment;
	}

	function user(){
		$loader = new Clay_Plugin("Facebook");
		$user = $loader->loadModel("UserModel");
		$user->findByPrimaryKey($this->user_id);
		return $user;
	}
}
?>