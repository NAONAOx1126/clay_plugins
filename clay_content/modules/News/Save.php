<?php
/**
 * ### Content.News.Save
 * カバー画像を登録する。
 */
class Content_News_Save extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin();
			
			try{
				// POSTされたデータを元にモデルを作成
				$news = $loader->loadModel("NewsModel");
				$news->findByPrimaryKey($_POST["news_id"]);
				
				// データを設定
				$news->news_title = $_POST["news_title"];
				$news->news_url = $_POST["news_url"];
				$news->news_body = $_POST["news_body"];
				$news->start_time = $_POST["start_time"];
				$news->end_time = $_POST["end_time"];
				
				// カテゴリを保存
				$news->save();
						
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit();
				
				unset($_POST["save"]);
				
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
