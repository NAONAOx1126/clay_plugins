<?php
/**
 * ### Content.Cover.Delete
 * カバー画像を削除する。
 */
class Content_Cover_Delete extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// トランザクションの開始
			DBFactory::begin();
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$cover = $loader->loadModel("CoverModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["cover_id"])){
					$_POST["cover_id"] = array($_POST["cover_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["cover_id"] as $cover_id){
					// カテゴリを削除
					$cover->findByPrimaryKey($cover_id);
					$cover->delete();
				}
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit();
				
				unset($_POST["cover_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				DBFactory::rollback();
				unset($_POST["cover_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
