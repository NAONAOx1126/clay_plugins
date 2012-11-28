<?php
class Checkin_PlayMusic{
	// 更新系の処理のため、キャッシュを無効化
	public $disable_cache = true;
	
	public function execute(){
		// 商品プラグインの初期化
		$loader = new Clay_Plugin("Checkin");
		$loader->LoadSetting();
		
		if($_POST["customer_id"] > 0 && !empty($_POST["name"])){
			// トランザクションの開始
			Clay_Database_Factory::begin("checkin");
			
			try{
				// 楽曲再生ログを初期化
				$musicLog = $loader->loadModel("CustomerMusicLogModel");
				$musicLog->customer_id = $_POST["customer_id"];
				
				// 楽曲カテゴリが存在しない場合は登録
				$category = $loader->loadModel("MusicCategoryModel");
				if(!empty($_POST["category"])){
					$category->findByName($_POST["category"]);
					if(!($category->category_id > 0)){
						$category->category_name = $_POST["category"];
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
				if(!empty($_POST["album"])){
					$album->findByName($_POST["album"], $_POST["disk_no"], $_POST["track_no"]);
					if(!($album->album_id > 0)){
						$album->album_name = $_POST["album"];
						$album->disk_no = $_POST["disk_no"];
						$album->track_no = $_POST["track_no"];
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
				$music->findByName($_POST["artist"], $_POST["name"]);
				if(!($music->music_id > 0)){
					$music->artist_name = $_POST["artist"];
					$music->music_name = $_POST["name"];
					$music->manufacture = $_POST["manufacture"];
					$music->bpm = $_POST["bpm"];
					$music->group = $_POST["group"];
					$music->composer = $_POST["composer"];
					$music->save();
				}
				if($music->music_id > 0){
					$musicLog->music_id = $music->music_id;
					$musicLog->save();
				}
				// エラーが無く、変更後のポイントが0以上の場合、処理をコミットする。
				Clay_Database_Factory::commit("checkin");
				return array("result" => "OK");
			}catch(Exception $ex){
				Clay_Database_Factory::rollback("checkin");
				return array("result" => "NG", "error" => $ex->getMessage());
			}
		}
	}
}
?>
