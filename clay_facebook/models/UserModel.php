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
class Facebook_UserModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("facebook");
		parent::__construct($loader->loadTable("UsersTable"), $values);
	}
	
	function findByPrimaryKey($user_id){
		$this->findBy(array("user_id" => $user_id));
	}
	
	function findByFacebookId($facebook_id){
		$this->findBy(array("facebook_id" => $facebook_id));
	}
	
	function likes(){
		$loader = new Clay_Plugin("Facebook");
		$like = $loader->loadModel("LikeModel");
		return $like->findAllByUser($this->user_id);
	}
	
	function comments(){
		$loader = new Clay_Plugin("Facebook");
		$comment = $loader->loadModel("PostCommentModel");
		return $comment->findAllByOwner($this->user_id);
	}
}
?>