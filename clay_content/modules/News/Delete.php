<?php
/**
 * ### Content.News.Delete
 * 新着情報を削除する。
 */
class Content_News_Delete extends FrameworkModule{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new PluginLoader("Content");
			$loader->LoadSetting();
			
			// トランザクションデータベースの取得
			$db = DBFactory::getConnection("content");
			
			// トランザクションの開始
			$db->beginTransaction();
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$news = $loader->loadModel("NewsModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["news_id"])){
					$_POST["news_id"] = array($_POST["news_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["news_id"] as $news_id){
					// カテゴリを削除
					$news->findByPrimaryKey($news_id);
					$news->delete($db);
				}
				
				// エラーが無かった場合、処理をコミットする。
				$db->commit();
				
				unset($_POST["news_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				$db->rollBack();
				unset($_POST["news_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
