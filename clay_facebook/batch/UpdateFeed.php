<?php
/**
 * Facebookの情報でグループの情報を更新するバッチです。
 *
 * Ex: /usr/bin/php batch.php "Facebook.UpdateGroup" <ホスト名>
 */
class Facebook_UpdateFeed extends Clay_Plugin_Module{
	private $facebook;
	
	public function execute($argv){
		$loader = new Clay_Plugin("facebook");
		$loader->LoadSetting();
		$group = $loader->loadModel("GroupModel");
		// ユーザーのリストを取得する。
		$groups = $group->findAllBy(array());
		foreach($groups as $group){
			echo "===========".$group->group_name."============<br>\r\n";
			// Facebookのインスタンスを初期化
			$this->facebook = new Facebook($_SERVER["CONFIGURE"]->facebook);
			// Facebookからユーザーの情報を取得する。
			$this->facebook->setAccessToken($group->access_token);
			
			// ユーザーのフィードを取得する。
			$feeds = $this->facebook->api("/".$group->facebook_id."/feed");
			
			// 対象グループ宛のフィードかどうか検索する。
			foreach($feeds["data"] as $feed){
				// トランザクションの開始
				Clay_Database_Factory::begin("Facebook");
							
				try{
					// ターゲットがグループの場合は投稿として処理し、次のフィードへ
					$this->registerPost($group->group_id, $feed);
																	
					// エラーが無かった場合、処理をコミットする。
					Clay_Database_Factory::commit("Facebook");
				}catch(Exception $e){
					Clay_Database_Factory::rollBack("Facebook");
					throw $e;
				}
				break;
			}
		}
	}
	
	protected function registerPost($group_id, $feed){
		// 取得したデータを元にデータを作成する。
		$loader = new Clay_Plugin("facebook");
		$loader->LoadSetting();
		
		// 投稿の投稿者データを取得する。
		$user = $loader->loadModel("UserModel");
		$user->findByFacebookId($feed["from"]["id"]);
		
		// ユーザーが存在した場合のみ登録
		if($user->user_id > 0){
			// 投稿データを作成
			$post = $loader->loadModel("PostModel");
			$post->findByFacebookId($feed["id"]);
			$post->facebook_id = $feed["id"];
			$post->group_id = $group_id;

			// テーマと開始日終了日はグループから取得する。
			$group = $loader->loadModel("GroupModel");
			$group->findByPrimaryKey($group_id);
			if($group->group_id > 0){
				$post->theme_id = $group->theme_id;
				$post->start_time = $group->start_time;
				$post->end_time = $group->end_time;
			}
				
			if(array_key_exists("place", $feed)){
				$post->location_name = $feed["place"]["name"];
				$post->location_latitude = $feed["place"]["location"]["latitude"];
				$post->location_longitude = $feed["place"]["location"]["longitude"];
			}
			$post->save();
			
			// 投稿に対してのメインコメントデータを作成する。
			$comment = $loader->loadModel("PostCommentModel");
			$comment->findByFacebookId($feed["id"]);
			$comment->facebook_id = $feed["id"];
			$comment->post_id = $post->post_id;
			$comment->group_id = $group_id;
			$comment->user_id = $user->user_id;
			$comment->comment_time = date("Y-m-d H:i:s", strtotime($feed["created_time"]));
			$comment->comment = (array_key_exists("message", $feed)?trim($feed["message"]):trim($feed["story"]));
			$comment = $this->splitLinkComment($comment);
			$comment->like_count = (array_key_exists("likes", $feed)?$feed["likes"]["count"]:0);
			$comment->save();
			
			// メインコメントを投稿に関連づけ
			$post->comment_id = $comment->comment_id;
			$post->save();
			
			// 投稿に対してのいいねデータを作成する。
			$likes = $this->facebook->api("/".$feed["id"]."/likes");
			foreach($likes["data"] as $like){
				$model = $loader->loadModel("LikeModel");
				// いいねのユーザーデータを取得する。
				$user = $loader->loadModel("UserModel");
				$user->findByFacebookId($like["id"]);
				if($user->user_id > 0){
					$model->findByCommentUser($comment->comment_id, $user->user_id);
					$model->post_id = $comment->post_id;
					$model->comment_id = $comment->comment_id;
					$model->user_id = $user->user_id;
					$model->save();
				}
			}
				
			// 投稿に対するコメントのリストを登録する。
			if($feed["comments"]["count"] > 0){
				foreach($feed["comments"]["data"] as $tempData){
					// コメントの本体を取得する。
					$data = $this->facebook->api("/".$tempData["id"]);
					
					// 投稿の投稿者データを取得する。
					$user = $loader->loadModel("UserModel");
					$user->findByFacebookId($data["from"]["id"]);

					if($user->user_id > 0){
						$comment = $loader->loadModel("PostCommentModel");
						$comment->findByFacebookId($data["id"]);
						$comment->facebook_id = $data["id"];
						$comment->post_id = $post->post_id;
						$comment->group_id = $group_id;
						$comment->user_id = $user->user_id;
						$comment->comment_time = date("Y-m-d H:i:s", strtotime($data["created_time"]));
						$comment->comment = trim($data["message"]);
						$comment = $this->splitLinkComment($comment);
						$comment->like_count = $data["like_count"];
						$comment->save();
						// コメントに対してのいいねデータを作成する。
						$likes = $this->facebook->api("/".$comment->facebook_id."/likes");
						foreach($likes["data"] as $like){
							$model = $loader->loadModel("LikeModel");
							// いいねのユーザーデータを取得する。
							$user = $loader->loadModel("UserModel");
							$user->findByFacebookId($like["id"]);
							if($user->user_id > 0){
								$model->findByCommentUser($comment->comment_id, $user->user_id);
								$model->post_id = $comment->post_id;
								$model->comment_id = $comment->comment_id;
								$model->user_id = $user->user_id;
								$model->save();
							}
						}
					}
				}
			}
			
			// 投稿が質問形式の場合には、質問データを追加登録する。
			if($feed["type"] == "question"){
				$this->registerQuestion($group_id, $post->post_id, $feed["object_id"]);
			}
		}
		
	}
	
	protected function registerQuestion($group_id, $post_id, $question_id){
		// 質問のオブジェクトを取得する。
		$question = $this->facebook->api("/".$question_id);
		print_r($question);
		
		// 質問の元となる投稿データを取得する。
		$loader = new Clay_Plugin("facebook");
		$loader->LoadSetting();
		
		// 投稿の投稿者データを取得する。
		$post = $loader->loadModel("PostModel");
		$post->findByPrimaryKey($post_id);
		
		foreach($question["options"]["data"] as $index => $option){
			if($index < 5){
				$option_name = "option".($index + 1);
				$option_count_name = "option".($index + 1)."_count";
				$post->$option_name = $option["name"];
				$post->$option_count_name = ($option["vote_count"] > 0)?$option["vote_count"]:0;
				
				// 投票者のリストを取得する。
				$votes = $this->facebook->api("/".$option["id"]."/votes");
				foreach($votes["data"] as $vote){
					$user = $loader->loadModel("UserModel");
					$user->findByFacebookId($vote["id"]);
					if($user->user_id > 0){
						$data = $loader->loadModel("PostVoteModel");
						$data->findByPostUser($post_id, $user->user_id);
						$data->post_id = $post_id;
						$data->user_id = $user->user_id;
						$data->$option_name = 1;
						$data->save();
					}
				}
			}
		}
		
		$post->save();
	}
	
	private function splitLinkComment($comment){
		if(substr($comment->comment, 0, 7) == "http://"){
			if(strpos($comment->comment, "\n") > 0){
				list($url, $newComment) = explode("\n", $comment->comment, 2);
			}else{
				$url = $comment->comment;
				$newComment = "";
			}
			// コメントがhttp://で始まっていた場合はリンクとして扱う
			echo "URL = ".$url."\r\n";
			$content = file_get_contents($url);
			if(preg_match("/\\<title\\>([^<]+)\\<\\/title\\>/i", $content, $params) > 0){
				$comment->link_name = $params[1];
				$comment->link_url = $url;
				$comment->comment = $newComment;
			}
		}
		print_r($comment->toArray());
		return $comment;
	}
}
