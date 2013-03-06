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
class Facebook_PostModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("facebook");
		parent::__construct($loader->loadTable("PostsTable"), $values);
	}
	
	function findByPrimaryKey($post_id){
		$this->findBy(array("post_id" => $post_id));
	}
	
	function findByFacebookId($facebook_id){
		$this->findBy(array("facebook_id" => $facebook_id));
	}
	
	function findAllByGroup($group_id, $order = "", $reverse = false){
		return $this->findAllBy(array("group_id" => $group_id), $order, $reverse);
	}
	
	function comment(){
		$loader = new Clay_Plugin("Facebook");
		$comment = $loader->loadModel("PostCommentModel");
		$comment->findByPrimaryKey($this->comment_id);
		return $comment;
	}
	
	function theme(){
		$loader = new Clay_Plugin("Facebook");
		$theme = $loader->loadModel("ThemeModel");
		$theme->findByPrimaryKey($this->theme_id);
		return $theme;
	}
	
	function group(){
		$loader = new Clay_Plugin("Facebook");
		$group = $loader->loadModel("GroupModel");
		$group->findByPrimaryKey($this->group_id);
		return $group;
	}

	function likes(){
		$loader = new Clay_Plugin("Facebook");
		$like = $loader->loadModel("LikeModel");
		return $like->findAllByPost($this->post_id);
	}
	
	function comments(){
		$loader = new Clay_Plugin("Facebook");
		$comment = $loader->loadModel("PostCommentModel");
		return $comment->findAllByPost($this->post_id);
	}
}
?>