<?php
/**
 * ### Content.News.Delete
 * 新着情報を削除する。
 */
class Content_News_Delete extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// トランザクションの開始
			DBFactory::begin();
			
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
					$news->delete();
				}
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit();
				
				unset($_POST["news_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				DBFactory::rollback();
				unset($_POST["news_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
