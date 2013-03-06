<?php
/**
 * Facebookの情報でグループの情報を更新するバッチです。
 *
 * Ex: /usr/bin/php batch.php "Facebook.UpdateGroup" <ホスト名>
 */
class Facebook_UpdateGroup extends Clay_Plugin_Module{
	public function execute($argv){
		$loader = new Clay_Plugin("facebook");
		$loader->LoadSetting();
		$group = $loader->loadModel("GroupModel");
		// ユーザーのリストを取得する。
		$groups = $user->findAllBy(array());
		foreach($users as $user){
			// Facebookのインスタンスを初期化
			$facebook = new Facebook($_SERVER["CONFIGURE"]->facebook);
			// Facebookからユーザーの情報を取得する。
			$facebook->setAccessToken($user->access_token);
			$userGroups = $facebook->api("/me/groups/");
			foreach($userGroups["data"] as $userGroup){
				$group = $loader->loadModel("GroupModel");
				$group->findByFacebookId($userGroup["id"]);
				if($group->group_id > 0){
					// グループの情報を取得
					$fbGroup = $facebook->api("/".$userGroup["id"]);
					$group->name = $fbGroup["name"];
						
					// オーナーの情報を取得
					$owner_id = $fbGroup["owner"]["id"];
					$owner = $loader->loadModel("UserModel");
					$owner->findByFacebookId($owner_id);
					$group->access_token = $owner->access_token;
					
					// ユーザーの質問投稿を取得
					$feeds = $facebook->api("/me/feed?limit=100");
					$questionFeeds = array();
					foreach($feeds["data"] as $feed){
						if($feed["type"] == "question" && $feed["to"]["data"]["id"] == $group->facebook_id){
							// 質問のオブジェクトを取得する。
							$object = $facebook->api("/".$feed["object_id"]);
							$feed["object"] = $object["data"];
							$questionFeeds[] = $feed;
						}
					}
					print_r($questionFeeds);
					exit;
					
					// トランザクションの開始
					Clay_Database_Factory::begin();
						
					try{
						$model->save();
					
						// エラーが無かった場合、処理をコミットする。
						Clay_Database_Factory::commit();
					}catch(Exception $e){
						Clay_Database_Factory::rollBack();
						throw $e;
					}
				}
			}
		}
	}
}
