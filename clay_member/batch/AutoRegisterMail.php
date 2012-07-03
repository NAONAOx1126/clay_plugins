<?php
/**
 * 空メールによりメール会員登録するバッチです。
 *
 * Ex: php batch.php Member.AutoRegisterMail members.clay info@clay-system.jp mail13.heteml.jp test@clay-system.jp mina-nao register
 */
class Member_AutoRegisterMail extends FrameworkModule{
	private $socket;
	
	private $email;
	
	private $server;
	
	private $login;
	
	private $password;
	
	private $template;
	
	public function execute($argv){
		$this->socket = null;
		$this->email = $argv[0];
		$this->server = $argv[1];
		$this->login = $argv[2];
		$this->password = $argv[3];
		$this->template = $argv[4];
		
		$this->fetchMail();
	}
	
	private function fetchMail($max = 100){
		// メールサーバへ接続
		if(($this->socket = fsockopen($this->server, 110, $err, $errno, 10)) === FALSE){
			Logger::writeError("ｻｰﾊﾞｰに接続できません");
			exit;
		}
		
		// 接続メッセージを受信
		$buf = fgets($this->socket, 1024);
		if(substr($buf, 0, 3) != '+OK'){
			Logger::writeError($buf);
			exit;
		}
		
		// メールサーバへログインする。
		$buf = $this->sendCommand("USER ".$this->login);
		$buf = $this->sendCommand("PASS ".$this->password);
		
		//STAT -件数とサイズ取得 +OK 8 1234
		$data = $this->sendCommand("STAT");
		$num = 0;
		$size = 0;
		sscanf($data, '+OK %d %d', $num, $size);
		
		if($num > $max){
			$num = $max;
		}
		
		// 件数分メールの本文を取得する。
		for($i=1;$i<=$num;$i++) {
			echo "-----------------------".$i."----------------------\r\n";
			//RETR n - n番目のメッセージ取得（ヘッダ含）
			$line = $this->sendCommand("RETR $i");
			//EOFの.まで読む
			while (!ereg("^\.\r\n",$line)) {
				$line = fgets($this->socket,512);
				$data .= $line;
			}
			
			// メールの内容をチェックして処理する。
			$this->parseMail($data);
			
			//DELE n n番目のメッセージ削除
			$data = $this->sendCommand("DELE $i");
		}
		
		// メールサーバへの接続を切断する。
		$buf = $this->sendCommand("QUIT"); //バイバイ
		fclose($this->socket);
	}
	
	private function parseMail($data){
		// 変数の初期化
		$write = true;
		$subject = $from = $text = $atta = $part = $attach = $filename = "";
		$mailto = "";
		$mailfrom = "";
		$mailsubject = "";
		$description = "";
	
		// ヘッダとボディの分離
		list($head, $body) = $this->mimeSplit($data);
		
		// ヘッダの分解
		$headers = explode("\r\n", $head);
		
		foreach($headers as $header){
		
			// 受信者メールアドレスの抽出
			$mailtotmp = $this->getMailHeader($header, "To:");
			if(strpos($mailto, "<") !== FALSE){
				$start_index = strpos($mailto, "<") + 1;
				$end_index = strpos($mailto, ">", $start_index);
				$mailtotmp = substr($mailto, $start_index, $end_index - $start_index);
			}
			if(!empty($mailtotmp)){ $mailto = $mailtotmp; }
	
			// 送信者メールアドレスの抽出
			$mailfromtmp = $this->getMailHeader($header, "From:");
			if(strpos($mailfrom, "<") !== FALSE){
				$start_index = strpos($mailfrom, "<") + 1;
				$end_index = strpos($mailfrom, ">", $start_index);
				$mailfromtmp = substr($mailfrom, $start_index, $end_index - $start_index);
			}
			if(!empty($mailfromtmp)){ $mailfrom = $mailfromtmp; }
	
			// メールタイトルの抽出
			$mailsubjecttmp = $this->getMailHeader($header, "Subject:");
			if(!empty($mailsubjecttmp)){ $mailsubject =  mb_decode_mimeheader( $mailsubjecttmp ); }
		}
	
		if(preg_match("/^MAILER-DAEMON@/i", $mailfrom) > 0){
			return;
		}
		
		if(preg_match("/^MAILER-DAEMON@/i", $mailfrom) > 0){
			return;
		}
	
		echo "TO: ".$mailto."\r\n";
		echo "FROM: ".$mailfrom."\r\n";
		echo "SUBJECT: ".$mailsubject."\r\n";
	
		// メイン処理
		// この機能で使用するモデルクラス
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();
		
		// カスタマモデルを使用して顧客情報を取得
		$customer = $loader->LoadModel("CustomerModel");
		$customer->findByEmail($mailfrom);
		
		if(!($customer->customer_id > 0)){
			$customer->email = $mailfrom;
			$code = "";
			$codes = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			for($i = 0; $i < 5; $i ++){
				$code .= substr($codes, mt_rand(0, strlen($codes) - 1), 1);
			}
			$customer->external_id = uniqid($code);
			
			// トランザクションの開始
			DBFactory::begin("member");
			
			try{
				// データを保存する。
				$customer->save();
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
			}catch(Exception $ex){
				DBFactory::rollback("member");
				throw $ex;
			}
			// 再度登録したデータを取得する。
			$customer->findByEmail($mailfrom);
		}
		
		// ローダーを初期化
		$loader = new PluginLoader();
		$loader->LoadSetting();
		
		// templateが設定されていて、hiddenに設定されたパラメータがPOSTで渡されている場合に処理を実行
		if(!empty($this->template)){
			$mailTemplate = $loader->LoadModel("MailTemplateModel");
			
			// テンプレートデータを取得
			$mailTemplate->findByPrimaryKey($this->template);
			
			// データが取得できた場合のみ処理を実行
			if($mailTemplate->template_code == $this->template){
				$mail = new SendMail();
				$mail->setFrom($this->email);
				$mail->setTo($mailfrom);
				$mail->setSubject($mailTemplate->subject);
				
				// メール本文用にテンプレートを使う
				$TEMPLATE_ENGINE = $_SERVER["CONFIGURE"]->TEMPLATE_ENGINE;
				$templateEngine = new $TEMPLATE_ENGINE();
				$templateEngine->assign("u", FRAMEWORK_URL_BASE);
				$templateEngine->assign("ATTRIBUTES", array("customer" => $customer->toArray()));
				$body = $templateEngine->fetch("string:".$mailTemplate->body);
				$mail->addBody($body);
				print_r($mail);
				$mail->send();
			}
		}
	}
	
	/**
	 * メールヘッダのパラメータを取得する。
	 */
	private function getMailHeader($header, $key){
		if(strtolower(substr($header, 0, strlen($key))) == strtolower($key)){
			$value = trim(substr($header, strlen($key)));
			echo "Matched to ".$key." for ".$value."\r\n";
			return $value;
		}else{
			return "";
		}
	}	
	
	/**
	 * メールテキストをヘッダとボディに分解する。
	 */
	private function mimeSplit($data){
		$part = split("\r\n\r\n", $data, 2);
		$part[1] = ereg_replace("\r\n[\t ]+", " ", $part[1]);
	
		return $part;
	}
	
	/**
	 * テキストからメールアドレス部分を抽出する。
	 */
	private function getAddress($address){
		if (eregi("[-!#$%&\'*+\\./0-9A-Z^_`a-z{|}~]+@[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+", $address, $fromreg)) {
			return $fromreg[0];
		} else {
			return false;
		}
	}
	
	/**
	 * メールコマンドを送信する。
	 */
	private function sendCommand($command){
		echo $command."\r\n";
		fputs($this->socket, $command."\r\n");
		$buf = fgets($this->socket, 512);
		echo $buf."\r\n";
		if(substr($buf, 0, 3) == '+OK') {
			return $buf;
		} else {
			die($buf);
		}
		return false;
	}
}
?>
