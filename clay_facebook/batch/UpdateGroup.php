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
		$user = $loader->loadModel("UserModel");
		// ユーザーのリストを取得する。
		$users = $user->findAllBy(array());
		foreach($users as $user){
			// Facebookのインスタンスを初期化
			$facebook = new Facebook($_SERVER["CONFIGURE"]->facebook);
			// Facebookからユーザーの情報を取得する。
			$facebook->setAccessToken($user->access_token);
			$userGroups = $facebook->api("/me/groups");
			foreach($userGroups["data"] as $userGroup){
				$group = $loader->loadModel("GroupModel");
				$group->findByFacebookId($userGroup["id"]);
				if($group->group_id > 0){
					// オーナーの情報を取得
					$fbGroups = $facebook->api("/".$userGroup["id"]);
					$owner_id = $fbGroups["owner"]["id"];
					$owner = $loader->loadModel("UserModel");
					$owner->findByFacebookId($owner_id);
					$group->access_token = $owner->access_token;
					
					// トランザクションの開始
					Clay_Database_Factory::begin("Facebook");
						
					try{
						$group->save();
					
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
