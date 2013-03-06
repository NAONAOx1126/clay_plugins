<?php
/**
 * Facebookの情報でグループの情報を更新するバッチです。
 *
 * Ex: /usr/bin/php batch.php "Facebook.UpdateGroup" <ホスト名>
 */
class Facebook_UpdateMessage extends Clay_Plugin_Module{
	private $facebook;
	
	public function execute($argv){
		$loader = new Clay_Plugin("facebook");
		$loader->LoadSetting();
		$user = $loader->loadModel("UserModel");
		// ユーザーのリストを取得する。
		$users = $user->findAllBy(array());
		foreach($users as $user){
			// Facebookのインスタンスを初期化
			$this->facebook = new Facebook($_SERVER["CONFIGURE"]->facebook);
			// Facebookからユーザーの情報を取得する。
			$this->facebook->setAccessToken($user->access_token);
			
			// ユーザーの受信メッセージを取得する。
			$messages = $this->facebook->api("/me/inbox?fields=id&limit=1000");
			
			// 対象グループ宛のフィードかどうか検索する。
			foreach($messages["data"] as $message){
				// メッセージの詳細を取得
				$message = $this->facebook->api("/".$message["id"]);
				
				// メッセージのやり取りをしている対象を取得
				if(count($message["to"]["data"]) == 2){
					// 1対1の会話のみ対象とし、ユーザーで無い方を管理者と仮定する。
					foreach($message["to"]["data"] as $target){
						if($target["id"] != $user->facebook_id){
							$admin_facebook_id = $target["id"];
						}
					}

					$commentUrl = "";
					$nextUrl = "/".$message["id"]."/comments";
										
					// トランザクションの開始
					Clay_Database_Factory::begin("Facebook");
					
					try{
						while($commentUrl != $nextUrl){
							$commentUrl = $nextUrl;
							$comments = $this->facebook->api($commentUrl);
						
							foreach($comments["data"] as $comment){
								// メッセージデータを登録する。
								$data = $loader->loadModel("MessageModel");
								$data->findByFacebookId($comment["id"]);
								$data->facebook_id = $comment["id"];
								$data->user_id = $user->user_id;
								$data->admin_facebook_id = $admin_facebook_id;
								if($comment["from"]["id"] == $user->facebook_id){
									$data->user_position = "FROM";
								}else{
									$data->user_position = "TO";
								}
								$data->send_time = date("Y-m-d H:i:s", strtotime($comment["created_time"]));
								$data->message = $comment["message"];
								$data->save();
							}
							
							if(array_key_exists("paging", $comments)){
								$nextUrl = str_replace("https://graph.facebook.com/", "/", $comments["paging"]["next"]);
							}
						}
						
						// エラーが無かった場合、処理をコミットする。
						Clay_Database_Factory::commit("Facebook");
					}catch(Exception $e){
						Clay_Database_Factory::rollBack("Facebook");
						throw $e;
					}
				}
			}
		}
	}
}
