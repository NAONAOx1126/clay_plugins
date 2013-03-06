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
class Facebook_PostVoteModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("facebook");
		parent::__construct($loader->loadTable("PostVotesTable"), $values);
	}
	
	function findByPrimaryKey($votes_id){
		$this->findBy(array("votes_id" => $votes_id));
	}
	
	function findByPostUser($post_id, $user_id){
		$this->findBy(array("post_id" => $post_id, "user_id" => $user_id));
	}
	
	function findAllByPost($post_id, $order = "", $reverse = false){
		return $this->findAllBy(array("post_id" => $post_id), $order, $reverse);
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

	function user(){
		$loader = new Clay_Plugin("Facebook");
		$user = $loader->loadModel("UserModel");
		$user->findByPrimaryKey($this->user_id);
		return $user;
	}
}
?>