<?php
// PHPの共通設定のファイルを読み込む
$loader = new Clay_Plugin("facebook");
$loader->LoadCommon("Facebook");

/**
 * Facebook API用のモデルクラスです。
 *
 * @category  Models
 * @package   File
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 */
class Facebook_Api_BaseModel extends Clay_Plugin_Model{
	/**
	 * Facebook APIのエンティティ
	 */
	private $entity;
	
	/**
	 * パーミッション
	 */
	private $permissions;
	
	/**
	 * コンストラクタ
	 * @param string $accessToken アクセストークン
	 * @param string $callbackUrl ログイン戻り先URL
	 * @param array $permissions 設定するパーミッション
	 */
	public function __construct($accessToken = null, $callbackUrl = "", $permissions = array()){
		// この機能の権限を追加
		foreach($permissions as $permission){
			$this->addPermission($permission);
		}
		$this->initialize($accessToken, $callbackUrl);
	}
	
	/**
	 * 初期化処理
	 * @param string $accessToken
	 * @param string $callbackUrl
	 * @param array $permissions
	 */
	protected function initialize($accessToken = null, $callbackUrl = ""){
		$this->entity = new Facebook($_SERVER["CONFIGURE"]->facebook);
		if($accessToken != null){
			// アクセストークンを設定して初期化
			$this->entity->setAccessToken($accessToken);
		}
		
		// アクセストークンを取得
		$accessToken = $this->entity->getAccessToken();
	
		// ユーザーIDを取得
		$uid = $this->entity->getUser();
	
		// ユーザーIDが取得できなかった場合には認証ページへ遷移させる。
		if (!$uid) {
			// 戻り先のURLを設定
			if(empty($callbackUrl)){
				$callbackUrl = $_SERVER["CONFIGURE"]->facebook["protocol"].$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
			
			// ログイン用のURLを取得
			$loginUrl = $this->entity->getLoginUrl(array(
				"client_id" => $_SERVER["CONFIGURE"]->facebook["appId"], 
				"canvas" => 1, 
				"fbconnect" => 0, 
				"scope" => $this->getPermissionsText(),
				"redirect_uri" => $callbackUrl
			));
			
			// アプリ未登録ユーザーなら facebook の認証ページへ遷移
			header("Location: ".$loginUrl);
			exit;
		}
	}
	
	/**
	 * Facebookログイン時のパーミッションを追加する。
	 * @param string $permission
	 */
	protected function addPermission($permission){
		if(!is_array($this->permissions)){
			$this->permissions = array();
		}
		if(!in_array($permission, $this->permission)){
			$this->permissions[] = $permission;
		}
	}
	
	private function getPermissionsText(){
		if(is_array($this->permissions)){
			return implode(",", $this->permissions);
		}
		return "";
	}
	
	/**
	 * アクセストークンを取得する
	 */
	protected function getAccessToken(){
		return $this->entity->getAccessToken();
	}

	/**
	 * Graph APIにGETリクエストを送る。
	 * @param string $path リクエストに使用するエンドポイント
	 */
	protected function get($path){
		return $this->entity->api($path, "GET");
	}
	
	/**
	 * Graph APIにPOSTリクエストを送る
	 * @param string $path リクエストに使用するエンドポイント
	 * @param array $parameters リクエストに使用するパラメータ
	 */
	protected function post($path, $parameters = array()){
		return $this->entity->api($path, "POST", $parameters);
	}
	
	/**
	 * Graph APIにDELETEリクエストを送る
	 * @param string $path リクエストに使用するエンドポイント
	 */
	protected function delete($path){
		return $this->entity->api($path, "DELETE");
	}
}
