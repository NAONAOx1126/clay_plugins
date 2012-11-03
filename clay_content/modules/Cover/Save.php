<?php
/**
 * ### Content.Cover.Save
 * カバー画像を登録する。
 */
class Content_Cover_Save extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin();
			
			try{
				// POSTされたデータを元にモデルを作成
				$cover = $loader->loadModel("CoverModel");
				$cover->findByPrimaryKey($_POST["cover_id"]);
				
				// データを設定
				$cover->cover_title = $_POST["cover_title"];
				$cover->cover_image = $_POST["cover_image"];
				$cover->cover_url = $_POST["cover_url"];
				$cover->start_time = $_POST["start_time"];
				$cover->end_time = $_POST["end_time"];
				$cover->sort_order = $_POST["sort_order"];
				
				// カテゴリを保存
				$cover->save();
						
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit();
				
				unset($_POST);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				Clay_Database_Factory::rollback();
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
