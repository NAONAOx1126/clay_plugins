<?php
require_once(dirname(__FILE__)."/BaseModel.php");

/**
 * FacebookのUserデータ用のモデルです。
 *
 * @category  Models
 * @package   File
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 */
class Facebook_Api_UserModel extends Clay_Plugin_Model{
	/**
	 * コンストラクタ
	 * @param string $accessToken アクセストークン
	 * @param string $callbackUrl ログインの戻り先URL
	 * @param unknown $permissions 個別設定するパーミッション
	 */
	public function __construct($accessToken = null, $callbackUrl = "", $permissions = array()){
		// この機能の権限を追加
		foreach($permissions as $permission){
			$this->addPermission($permission);
		}
		$this->initialize($accessToken, $callbackUrl);
	}
	
	/**
	 * 自分のユーザー情報を取得する。
	 */
	public static function me(){
		
	}
}
?>