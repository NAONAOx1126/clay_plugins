<?php
// この機能で使用するモデルクラス
LoadModel("Setting", "Members");
LoadModel("MailTemplateModel");

/**
 * 携帯の個体番号でのログインを実行するモジュールです。
 *
 * @params error ログイン失敗時に遷移するページのテンプレートパス
 * @params redirect ログイン成功時にリダイレクトするURL
 * @params session 顧客情報を保存するセッション名
 * @params auto 1を設定すると、携帯の個体番号が渡っていた場合、自動でユーザー情報を作成する
 * @params result 顧客情報をページで使うためのキー名
 */
class Members_Clay_Sendmail extends Clay_Plugin_Module{
	function execute($params){
		if($params->check("mail")){
			// トランザクションの開始
			Clay_Database_Factory::begin("member");
			
			try{
				// 登録完了メール送信
				if($params->check("html")){
					$Clay_Sendmail = new SendPCHtmlMail();
				}else{
					$Clay_Sendmail = new Clay_Sendmail();
				}
				$emailKey = $params->get("email", "email");
				$smarty = new Template();
				foreach($_SERVER as $name =>$value){
					$smarty->assign($name, $value);
				}
				
				// メールの送信元（サイトメールアドレス）を設定
				$Clay_Sendmail->setFrom($_SERVER["CONFIGURE"]["SITE"]["site_email"], $_SERVER["CONFIGURE"]["SITE"]["site_name"]);
				// メールの送信先を設定
				$Clay_Sendmail->setTo($_SERVER["ATTRIBUTES"][$params->get("result", "customer")]->$emailKey);
				// テキストメールの設定からタイトルと本文を取得
				$mailTemplate = new MailTemplateModel();
				$mailTemplate->findByPrimaryKey($params->get("mail"));
				$Clay_Sendmail->setSubject($mailTemplate->subject);
				$body = $smarty->fetch("string:".$mailTemplate->body);
				// HTMLメールのテンプレートから本文を取得
				if($params->check("html")){
					$Clay_Sendmail->addExtBody($body);
					$mailHtmlTemplate = new MailTemplateModel();
					$mailHtmlTemplate->findByPrimaryKey($params->get("html"));
					$body = $smarty->fetch("string:".$mailHtmlTemplate->body);
				}
				$Clay_Sendmail->addBody($body);
				$Clay_Sendmail->send();
				$Clay_Sendmail->reply();
				Clay_Database_Factory::commit("member");
			}catch(Exception $ex){
				unset($_POST["regist"]);
				Clay_Database_Factory::rollback("member");
				throw $ex;
			}
		}
	}
}
?>