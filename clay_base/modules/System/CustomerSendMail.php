<?php
class Base_System_CustomerClay_Sendmail extends Clay_Plugin_Module{
	function execute($params){
		// ローダーを初期化
		$loader = new Clay_Plugin();
		$loader->LoadSetting();
		
		// 引数を処理する。
		$template_code = $params->get("template");
		
		// templateが設定されていて、hiddenに設定されたパラメータがPOSTで渡されている場合に処理を実行
		if(!empty($template_code)){
			$template = $loader->LoadModel("MailTemplateModel");
			
			// テンプレートデータを取得
			$template->findByPrimaryKey($template_code);
			
			// データが取得できた場合のみ処理を実行
			if($template->template_code == $template_code){
				$mail = new Clay_Sendmail();
				$mail->setFrom($params->get("sendaddress", "info@clay-system.jp"), $params->get("sender", ""));
				$mail->setTo($_POST[$params->get("email", "email")]);
				$mail->setSubject($template->subject);
				
				// メール本文用にテンプレートを使う
				$TEMPLATE_ENGINE = $_SERVER["CONFIGURE"]->TEMPLATE_ENGINE;
				$templateEngine = new $TEMPLATE_ENGINE();
				
				foreach($_SERVER as $name =>$value){
					$templateEngine->assign($name, $value);
				}
				$body = $templateEngine->fetch("string:".$template->body);
				$mail->addBody($body);
				$mail->send();
			}
			/*
			// 送信通知用のメール処理
			// メールボディを作成
			$subject = "";
			$body = "";
			if(!empty($result)){
				$subject .= $result[0]["subject"];
				$body .= $result[0]["header"];
				$body .= "\r\n-----------------------------------\r\n";
			}
			foreach($_POST[$hidden] as $form){
				list($form_id, $form_name, $sp) = explode(":", $form);
				$body .= "■".$form_name."\r\n     ";
				$body .= (is_array($_POST[$form_id])?implode($sp, $_POST[$form_id]):$_POST[$form_id])."\r\n\r\n";
			}
			if(!empty($result)){
				$body .= "\r\n-----------------------------------\r\n";
				$body .= $result[0]["footer"];
			}
			
			// この機能で使用するテーブルモデルを初期化
			$siteManagerGroups = new SiteManagerGroupsTable();
			$managerGroups = new ManagerGroupsTable();
	
			// 管理グループのリストを取得する処理
			$select = new Clay_Query_Select($siteManagerGroups);
			$select->addColumn($managerGroups->_W);
			$select->joinInner($managerGroups, array($siteManagerGroups->manager_group_id." = ".$managerGroups->manager_group_id));
			$select->addOrder($managerGroups->manager_group_id);
			$select->addWhere($siteManagerGroups->site_id." = ?", array($_SERVER["CONFIGURE"]["SITE"]["site_id"]));
			$result = $select->execute();

			foreach($result as $index => $group){
				$mail = new Clay_Sendmail();
				$mail->setFrom($_SERVER["CONFIGURE"]["site_email"], $_SERVER["CONFIGURE"]["site_name"]);
				$mail->setTo($group["email"], $group["manager_group_name"]);
				$mail->setSubject($subject);
				$mail->addBody($body);
				$mail->send();
			}
			*/
		}
	}
}
?>
