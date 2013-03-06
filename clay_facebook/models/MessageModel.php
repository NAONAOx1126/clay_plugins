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
class Facebook_MessageModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("facebook");
		parent::__construct($loader->loadTable("MessagesTable"), $values);
	}
	
	function findByPrimaryKey($message_id){
		$this->findBy(array("message_id" => $message_id));
	}
	
	function findByFacebookId($facebook_id){
		$this->findBy(array("facebook_id" => $facebook_id));
	}
	
	function findAllByAdmin($admin_facebook_id, $order = "", $reverse = false){
		return $this->findAllBy(array("admin_facebook_id" => $admin_facebook_id), $order, $reverse);
	}
	
	function findAllByUser($user_id, $order = "", $reverse = false){
		return $this->findAllBy(array("user_id" => $user_id), $order, $reverse);
	}

	function findAllByAdminUser($admin_facebook_id, $user_id, $order = "", $reverse = false){
		return $this->findAllBy(array("admin_facebook_id" => $admin_facebook_id, "user_id" => $user_id), $order, $reverse);
	}

	function user(){
		$loader = new Clay_Plugin("Facebook");
		$user = $loader->loadModel("UserModel");
		$user->findByPrimaryKey($this->user_id);
		return $user;
	}
}
?>