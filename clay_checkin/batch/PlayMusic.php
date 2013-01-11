<?php
/**
 * 確定していないログを確定させます。
 *
 * Ex: /usr/bin/php batch.php "Checkin.PlayMusic" <ホスト名>
 */
class Checkin_PlayMusic extends Clay_Plugin_Module{
	public function execute($argv){
		// この機能で使用するモデルクラス
		$loader = new Clay_Plugin("Checkin");
		$loader->LoadSetting();
		
		// ログモデルから未確定ログを抽出
		$log = $loader->LoadModel("LogModel");
		$result = $log->findAllByNotImported(CHECKIN_LOG_TYPE_MUSIC);
		
		foreach($result as $data){
			$post = unserialize($data->log_data);
			if($post["customer_id"] > 0 && !empty($post["name"])){
				// トランザクションの開始
				Clay_Database_Factory::begin("checkin");
				
				try{
					// 楽曲再生ログを初期化
					$musicLog = $loader->loadModel("CustomerMusicLogModel");
					$musicLog->customer_id = $post["customer_id"];
					
					// 楽曲カテゴリが存在しない場合は登録
					$category = $loader->loadModel("MusicCategoryModel");
					if(!empty($post["category"])){
						$category->findByName($post["category"]);
						if(!($category->category_id > 0)){
							$category->category_name = $post["category"];
							$category->save();
						}
					}
					// カテゴリIDを設定
					if($category->category_id > 0){
						$musicLog->category_id = $category->category_id;
					}else{
						$musicLog->category_id = 0;
					}
					
					// アルバムカテゴリが存在しない場合は登録
					$album = $loader->loadModel("MusicAlbumModel");
					if(!empty($post["album"])){
						$album->findByName($post["album"], $post["disk_no"], $post["track_no"]);
						if(!($album->album_id > 0)){
							$album->album_name = $post["album"];
							$album->disk_no = $post["disk_no"];
							$album->track_no = $post["track_no"];
							$album->save();
						}
					}
					// アルバムIDを設定
					if($album->album_id > 0){
						$musicLog->album_id = $album->album_id;
					}else{
						$musicLog->album_id = 0;
					}
		
					// アルバムカテゴリが存在しない場合は登録
					$music = $loader->loadModel("MusicModel");
					$music->findByName($post["artist"], $post["name"]);
					if(!($music->music_id > 0)){
						$music->artist_name = $post["artist"];
						$music->music_name = $post["name"];
						$music->manufacture = $post["manufacture"];
						$music->bpm = $post["bpm"];
						$music->group = $post["group"];
						$music->composer = $post["composer"];
						$music->save();
					}
					if($music->music_id > 0){
						$musicLog->music_id = $music->music_id;
						$musicLog->save();
					}
					
					// 使用済み
					$data->log_imported = 1;
					$data->save();
					
					// エラーが無く、変更後のポイントが0以上の場合、処理をコミットする。
					Clay_Database_Factory::commit("checkin");
				}catch(Exception $ex){
					Clay_Database_Factory::rollback("checkin");
				}
			}
		}
	}
}
