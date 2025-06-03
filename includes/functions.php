<?php
// ----------------------------------------------------------------------------------------------
// GENERAL FUNCTIONS

function randomString($chars=10) { //generate random string
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randstring = '';
	for ($i = 0; $i < $chars; $i++) { $randstring .= $characters[rand(0, strlen($characters) -1)]; }
	return $randstring;
}

function currentFileName() { //return current file name
	return basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
}

function baseURL($sub=0) { //return base url for cron jobs
	 $requesturi = explode("?",$_SERVER["REQUEST_URI"]);
	 $subdir =  $requesturi[0];
	 $pageURL = 'http';
	 if(isset($_SERVER["HTTPS"])) { if($_SERVER["HTTPS"] == "on") {$pageURL .= "s";} }
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"] . $subdir;
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"] . $subdir;
	 }
	 return $pageURL;
}

function getGravatar($email,$size) { //get gravatar image for the given email address
	global $database;

	$grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=mm" . "&s=" . $size;
	$avatar = $database->get("people", "avatar", [ "email" => strtolower($email) ]);

	if($avatar != "") { return "data:image/jpeg;base64," . base64_encode($avatar); }

	else return $grav_url;
}

function curlReturn($url) { //get url with curl
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
	curl_setopt($ch,CURLOPT_URL, $url);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function rand_color() { //generate random color
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

function ttruncat($text,$numb=30) { //truncate text
	if (strlen($text) > $numb) {
	  	$text = substr($text, 0, $numb);
	  	$text = substr($text,0,strrpos($text," "));
	  	$etc = " ...";
	  	$text = $text.$etc;
	  }
	return $text;
}



function escapeJavaScriptText($string) {
    return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
}


function get_mime_content($filename) {
	global $scriptpath;
	$mime_types = array(

		'txt' => 'text/plain',
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'text/html',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'swf' => 'application/x-shockwave-flash',
		'flv' => 'video/x-flv',

		// images
		'png' => 'image/png',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'bmp' => 'image/bmp',
		'ico' => 'image/vnd.microsoft.icon',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',

		// archives
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'exe' => 'application/x-msdownload',
		'msi' => 'application/x-msdownload',
		'cab' => 'application/vnd.ms-cab-compressed',

		// audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',

		// adobe
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',

		// ms office
		'doc' => 'application/msword',
		'rtf' => 'application/rtf',
		'xls' => 'application/vnd.ms-excel',
		'ppt' => 'application/vnd.ms-powerpoint',

	);

	$file_parts = pathinfo($filename);
	if (array_key_exists(strtolower($file_parts['extension']), $mime_types)) {
		return $mime_types[$file_parts['extension']];
	}

	else {
		return 'application/octet-stream';
	}


}

function isHTML($str) {
	return preg_match("/\/[a-z]*>/i", $str) != 0;
}

function check_recaptcha($response) {

	$url = 'https://www.google.com/recaptcha/api/siteverify';

	$data = array(
		'secret' => getConfigValue("recaptcha_secretkey"),
		'response' => $response
	);

	$options = array(
		'http' => array (
			'method' => 'POST',
			'content' => http_build_query($data)
		)
	);

	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success=json_decode($verify);

	if ($captcha_success->success==false) {
		return "bot";
	} else if ($captcha_success->success==true) {
		return "ok";
	}

}



// ----------------------------------------------------------------------------------------------
// GENERAL DATABASE FUNCTIONS

function getRowById($table,$id) { //return associative array from one row by id
	global $database;
	$row = $database->get($table, "*", ["id" => $id]);
	return $row;
}

function getSingleValue($table,$column,$id) { //returns single value from table row by id
	global $database;
	$value = $database->get($table, $column, ["id" => $id]);
	return $value;
}

function getTable($table,$columns="*",$sortby="id",$sortway="ASC") { //get entire table
	global $database;
	$table = $database->select($table, $columns, [ "ORDER" => [$sortby => $sortway] ]);
	return $table;
}

function getTableFiltered($table,$filterColumn1,$filterValue1,$filterColumn2="",$filterValue2="",$columns="*",$sortby="id",$sortway="ASC") { //get entire table filtered
	global $database;
	if ($filterColumn2 == "") {
		$table = $database->select($table, $columns, [ $filterColumn1 => $filterValue1, "ORDER" => [$sortby => $sortway] ]);
	}
	else {
		$table = $database->select($table, $columns, [ "AND" => [$filterColumn1 => $filterValue1, $filterColumn2 => $filterValue2], "ORDER" => [$sortby => $sortway] ]);
	}
	return $table;
}

function countTable($table) { //count table rows
	global $database;
	$count = $database->count($table);
	return $count;
}

function countTableFiltered($table,$filterColumn1,$filterValue1,$filterColumn2="",$filterValue2="") { //count table rows with filter
	global $database;
	if ($filterColumn2 == "") { $count = $database->count($table, [$filterColumn1 => $filterValue1]); }
	else { $count = $database->count($table, [ "AND" => [$filterColumn1 => $filterValue1, $filterColumn2 => $filterValue2] ]); }
	return $count;
}

function getConfigValue($name) { //return config value from database
	global $database;
	return $database->get("config", "value", ["name" => $name]);
}

function deleteRowById($table,$id) { //detete row(s) by id
	global $database;
    $database->delete($table, [ "id" => $id ]);
	return "delOK";
}


// ----------------------------------------------------------------------------------------------
// DATE FORMAT


function smartDate($timestamp) {
	$diff = time() - $timestamp;

	if ($diff <= 0) {
		return __('Now');
	}
	else if ($diff < 60) {
		return _x("%d second ago","%d seconds ago",floor($diff));
	}
	else if ($diff < 60*60) {
		return _x("%d minute ago","%d minutes ago",floor($diff/60));
	}
	else if ($diff < 60*60*24) {
		return _x("%d hour ago","%d hours ago",floor($diff/(60*60)));
	}
	else if ($diff < 60*60*24*30) {
		return _x("%d day ago","%d days ago",floor($diff/(60*60*24)));
	}
	else if ($diff < 60*60*24*30*12) {
		return _x("%d month ago","%d months ago",floor($diff/(60*60*24*30)));
	}
	else {
		return _x("%d year ago","%d years ago",floor($diff/(60*60*24*30*12)));
	}
}

function duration($date, $start, $end) {

	$timestamp1 = strtotime($date . " " . $start);
	$timestamp2 = strtotime($date . " " . $end);

	$diff = $timestamp2 - $timestamp1;

	return $diff;
}

function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%H:%I');
}


function phpFormat() {
	$format = explode(";",getConfigValue("date_format"));
	return $format[0];
}

function jsFormat() {
	$format = explode(";",getConfigValue("date_format"));
	return $format[1];
}



function dateDisplay($date) {
	$format = explode(";",getConfigValue("date_format"));

	if($date != "") return date($format[0], strtotime($date) );
	elseif($date == "0000-00-00") return "";
	else return "";
}

function dateDb($date) {
	$format = explode(";",getConfigValue("date_format"));

	if($date != "")  {
		$dateObj = date_create_from_format($format[0],$date);
		return date_format($dateObj,"Y-m-d");
	}

	else return "";
}


function dateTimeDisplay($date) {
	$format = explode(";",getConfigValue("date_format"));

	if($date != "") return date($format[0]." H:i:s", strtotime($date) );
	elseif($date == "0000-00-00 00:00:00") return "";
	else return "";
}




// ----------------------------------------------------------------------------------------------
// NAVIGATION

function reroute($data,$status=0) {
	$location = "Location:?route=" . $data['route'];
	if(isset($data['routeid'])) $location .= "&id=" . $data['routeid'];
	if(isset($data['section'])) $location .= "&section=" . $data['section'];
	setStatus($status);
	header($location);
}

function setStatus($status) {
	if($status != 0 && $status != "") $_SESSION["statuscode"] = $status;
}

function clearStatus() {
	$_SESSION["statuscode"] = "";
}


// ----------------------------------------------------------------------------------------------
// CLASS LOADERS

function vendorClassAutoload($classname) {
	global $scriptpath;
	$file = $scriptpath . DIRECTORY_SEPARATOR .'vendor'. DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class.' . strtolower($classname) . '.php';
	if (file_exists($file)) require($file);
}

function appClassAutoload($classname) {
	global $scriptpath;
	$file = $scriptpath . DIRECTORY_SEPARATOR .'includes'. DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class.' . strtolower($classname) . '.php';
	if (file_exists($file)) require($file);
}


// ----------------------------------------------------------------------------------------------
// TEXT OUTPUT

function __($text) {
	global $t;
	if(isset($t)) return $t->translate($text);
	else return $text;
}

function _e($text) {
	echo __($text);
}

function _x($sg,$pl,$count) {
	global $t;
	if(isset($t)) return sprintf($t->ngettext($sg,$pl,intval($count)), $count);
	else {
		if($count == "1") return sprintf($sg,$count);
		elseif($count > 1) return sprintf($pl,$count);
	}
}

// ----------------------------------------------------------------------------------------------
// AUTHENTICATION FUNCTIONS

function signIn($email,$password) //login and set session
	{
		global $database;
		$email = strtolower($email);

		$internal = 0;
		$ldap = 0;

		$internal = $database->count("people",["AND" => ["email" => $email,"password" => sha1($password)]]);
		if(getConfigValue("ldap_enable") == "true") {
			$ldap = $database->count("people", ["ldap_user" => $email]);
		}

		if ($internal == "1") {
			//session_start();
			$sessionid = session_id();
			$database->update("people", ["sessionid" => $sessionid], ["email" => $email]);
			$people = $database->get("people","*",["email" => $email]);
			logSystem("User/Admin Logged In - ID: " . $people['id']);
			header("Location:?route=dashboard");
			exit;
		}
		elseif ($ldap == 1) {

			$ldap_conn = ldap_connect( getConfigValue("ldap_host"), getConfigValue("ldap_port") ) or die("Could not connect to LDAP/AD server!");
			ldap_set_option($ldap_conn,LDAP_OPT_PROTOCOL_VERSION,3);
			ldap_set_option($ldap_conn,LDAP_OPT_REFERRALS,0);

			$bind_addr = "uid=" . $email . "," . getConfigValue("ldap_dn");

			//$bind = ldap_bind($ldap_conn, "uid=riemann,dc=example,dc=com", "password");
			//print_r($bind);
			//exit;

			if ($bind = ldap_bind($ldap_conn, $bind_addr, $password)) {
			    // log them in!
				//echo "bingo";
				//exit;

				$sessionid = session_id();
				$database->update("people", ["sessionid" => $sessionid], ["ldap_user" => $email]);
				$people = $database->get("people","*",["ldap_user" => $email]);
				logSystem("User/Admin Logged In - ID: " . $people['id']);
				header("Location:?route=dashboard");
			} else {
				logSystem("User/Admin LDAP Login Failure - USERNAME: " . $email);
				setStatus(1200);
				header("Location:?route=signin");
				exit;
			}
		}
		else {
			logSystem("User/Admin Login Failure - EMAIL: " . $email);
			setStatus(1200);
			header("Location:?route=signin");
			exit;
		}
	}

function signInApi($email,$password) //login and set session
	{
		global $database;
		$email = strtolower($email);

		$internal = 0;
		$ldap = 0;

		$internal = $database->count("people",["AND" => ["email" => $email,"password" => sha1($password)]]);
		if(getConfigValue("ldap_enable") == "true") {
			$ldap = $database->count("people", ["ldap_user" => $email]);
		}

		if ($internal == "1") {
			//session_start();
			$sessionid = session_id();
			$database->update("people", ["sessionid" => $sessionid], ["email" => $email]);
			$people = $database->get("people","*",["email" => $email]);
			logSystem("User/Admin Logged In - ID: " . $people['id']);
			return $people['id'];
		}

		elseif ($ldap == 1) {

			$ldap_conn = ldap_connect( getConfigValue("ldap_host"), getConfigValue("ldap_port") ) or die("Could not connect to LDAP/AD server!");
			ldap_set_option($ldap_conn,LDAP_OPT_PROTOCOL_VERSION,3);
			ldap_set_option($ldap_conn,LDAP_OPT_REFERRALS,0);

			$bind_addr = "uid=" . $email . "," . getConfigValue("ldap_dn");


			if ($bind = ldap_bind($ldap_conn, $bind_addr, $password)) {

				$sessionid = session_id();
				$database->update("people", ["sessionid" => $sessionid], ["ldap_user" => $email]);
				$people = $database->get("people","*",["ldap_user" => $email]);
				logSystem("User/Admin Logged In - ID: " . $people['id']);
				return $people['id'];
			} else {
				logSystem("User/Admin LDAP Login Failure - USERNAME: " . $email);
				return 0;
				exit;
			}
		}
		else {
			logSystem("User/Admin Login Failure - EMAIL: " . $email);
			return 0;
		}
	}

function resetConfirmation($email) //set password resetkey and send confirmation email for password reset
	{
		global $database;
		$email = strtolower($email);
		$count = $database->count("people",["email" => $email]);

		if ($count == "1") {
			$people = $database->get("people","*",["email" => $email]);
			$resetkey = randomString(32);
			$database->update("people", ["resetkey" => $resetkey], ["email" => $email]);
			$resetlink = baseURL(-14) . "/?route=forgot&resetkey=" . $resetkey;
			Notification::passwordReset($people['id'],$resetlink);
			setStatus(1300);
			header("Location:?route=forgot");
			exit;
		}
		else { setStatus(1400); header("Location:?route=forgot");  exit; }
	}

function resetPassword($resetkey,$password) //reset password
	{
		global $database;
		$count = $database->count("people",["resetkey" => $resetkey]);

		if ($count == "1") {
			$people = $database->get("people","*",["resetkey" => $resetkey]);
			$database->update("people", ["password" => sha1($password),"resetkey" => ""], ["resetkey" => $resetkey]);
			logSystem("User/Admin Password Reset - ID: " . $people['id']);
			setStatus(1600);
			header("Location:?route=login");
			exit;
		}
		else { setStatus(1500); header("Location:?route=forgot");  exit; }
	}

function signOut($id) //unset user/admin session
	{
		global $database;
		$database->update("people", ["sessionid" => ""], ["id" => $id]);
		logSystem("User/Staff Logged Out - ID: " . $id);
		header("Location:?route=signin");
		exit;
	}

function isSignedIn() //check if someone is logged in, if not redirect to login page
	{
	global $database;
	//session_start();
	$sessionid = session_id();
	$people = $database->count("people", ["sessionid" => $sessionid]);
	if($people != 1) { header("Location:?route=signin"); exit; }
	}


function isAuthorized($action) {
	global $perms;

	if(!in_array($action,$perms)) { setStatus("1"); header("Location:?route=dashboard"); exit; }
}

function isOwner($objects_clientid) {
	global $isAdmin;
	global $liu;

	if($isAdmin) {
		return TRUE;
	} else {
		if($objects_clientid == $liu['clientid']) {
			return TRUE;
		} else {
			setStatus("1");
			header("Location:?route=dashboard");
			exit;
		}
	}

}

function isAuthorizedApi($action) {
	global $perms;
	global $request_method;
	global $request_resource;

	if(!in_array($action, $perms)) {
		$response = [ "status" => 905, "status_message" => "You are not authorized to perform " . $request_method . " for " . $request_resource ];
		echo json_encode($response);
		exit;
	}


}


// ----------------------------------------------------------------------------------------------
// APP LOGGING FUNCTIONS

function logSystem($description) //add to system log
	{
		global $liu;
		if(isset($liu['id'])) $peopleid = $liu['id']; else $peopleid = -1;
		global $database;
		$database->insert("systemlog", [
			"peopleid" => $peopleid,
			"ipaddress" => $_SERVER['REMOTE_ADDR'],
			"description" => $description,
			"timestamp" => date('Y-m-d H:i:s')
		]);
	}

function logSystemApi($data, $peopleid) //add to system log
	{
		global $database;
		$lastid = $database->insert("systemlog", [
			"peopleid" => $data['peopleid'],
			"ipaddress" => $_SERVER['REMOTE_ADDR'],
			"description" => $data['description'],
			"timestamp" => date('Y-m-d H:i:s')
		]);

		if ($lastid == "0") { return "11"; } else { return "10"; }
	}

function logEmail($clientid,$peopleid,$to,$subject,$message) //add to email log
	{
		global $database;
		$database->insert("emaillog", [
			"peopleid" => $peopleid,
			"clientid" => $clientid,
			"to" => $to,
			"subject" => $subject,
			"message" => $message,
			"timestamp" => date('Y-m-d H:i:s')
		]);
	}

function logSMS($clientid,$peopleid,$mobile,$sms) //add to sms log
	{
		global $database;
		$database->insert("smslog", [
			"peopleid" => $peopleid,
			"clientid" => $clientid,
			"mobile" => $mobile,
			"sms" => $sms,
			"timestamp" => date('Y-m-d H:i:s')
		]);
	}


// ----------------------------------------------------------------------------------------------
// COMMUNICATIONS FUNCTIONS

function sendEmail($to,$subject,$message,$clientid="0",$peopleid="0",$ccs=array(),$attachments=array()) //send email
	{
		global $database;
		global $scriptpath;
		$mail = new PHPMailer\PHPMailer\PHPMailer();

		$mail->CharSet = "UTF-8";
		if (getConfigValue("email_smtp_enable") == "true") {
			$mail->isSMTP();
			$mail->Host = getConfigValue("email_smtp_host");
			$mail->SMTPAuth = getConfigValue("email_smtp_auth");
			$mail->Username = getConfigValue("email_smtp_username");
			$mail->Password = getConfigValue("email_smtp_password");
			$mail->SMTPSecure = getConfigValue("email_smtp_security");
			$mail->Port = getConfigValue("email_smtp_port");

			//$mail->SMTPAutoTLS = false;
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true,
				),
			);


			if (getConfigValue("email_smtp_domain") != "") {
				$mail->AuthType = 'NTLM';
				$mail->Realm = getConfigValue("email_smtp_domain");
			}

		}

		$mail->From = getConfigValue("email_from_address");
		$mail->FromName = getConfigValue("email_from_name");
		$mail->addAddress($to);

		foreach($ccs as $cc) { $mail->AddCC($cc); }

		foreach($attachments as $attachment) {
			$file = getRowById("files",$attachment);
			$targetfile = $scriptpath . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $file['file'];
			$mail->addAttachment($targetfile);

			$message = str_replace("?qa=show&id=" . $file['id'], $file['file'], $message);
		}

		$message = $mail->msgHTML($message, $scriptpath . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR);

		$mail->Subject = $subject;
		//$mail->Body    = $message;
		$mail->IsHTML(true);

		if(!$mail->send()) {
			logEmail($clientid,$peopleid,$to,$subject,$mail->ErrorInfo);
			return 0; //error
		}
		else {
			logEmail($clientid,$peopleid,$to,$subject,$message);
			return 1; //success
		}
	}


function sendFCM($peopleid, $title, $body) {
	global $database;
	global $scriptpath;

	if($peopleid == "") return 0;
	if($peopleid == 0) return 0;

	$people = getRowById("people", $peopleid);
	if(empty($people)) return 0;

	if($people['fcmtoken'] != "") {
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array (
				'to' => $people['fcmtoken'],
				'data' => array (
						"body" => $body,
						"title" => $title,
				)
		);
		$fields = json_encode ( $fields );
		$headers = array (
				'Authorization: key=' . "AAAALNWJYRg:APA91bEW_hzLQ-ra4mRWh-YBYSAzbyw3x6o2Za0DEKk099wBTAj520JXDi_mehjPDMg6uskfaPTN7RRlJ6uZj_QNhNVaH0KNNKxXdfOsQB2OeLAXrh3YGQTwFJL4JFhCG5Ffrma5Kw4KHV6jExg7m2yIauTedV9bNQ",
				'Content-Type: application/json'
		);

		$ch = curl_init();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

		$result = curl_exec ( $ch );

		$file = fopen($scriptpath . "/fcmlog.txt","a+");
		fwrite($file,$result);
		fclose($file);

		curl_close ( $ch );

		return 1;
	}

}


function sendSMS($mobile,$sms,$clientid="0",$peopleid="0") { //send sms
	$provider = getConfigValue("sms_provider");
	$user = getConfigValue("sms_user");
	$password = getConfigValue("sms_password");
	$api_id = getConfigValue("sms_api_id");
	$from = getConfigValue("sms_from");

	if ($provider == "smsglobal") {
		$url = 'http://www.smsglobal.com/http-api.php' . '?action=sendsms' . '&user=' . $user . '&password=' . $password . '&from=' . $from . '&to=' . $mobile . '&text=' . substr(rawurlencode($sms), 0, 153);
		$returnedData = file_get_contents($url);
	}
	if ($provider == "clickatell") {
		$url = 'http://api.clickatell.com/http/sendmsg?user=' . $user . '&password=' . $password . '&api_id=' . $api_id . '&to=' . $mobile . '&text=' . $sms;
		$returnedData = file_get_contents($url);
	}

	logSMS($clientid,$peopleid,$mobile,$sms);
}

// ----------------------------------------------------------------------------------------------
// DATA ENCRYPTION FUNCTIONS

// Encrypt Function
function mc_encrypt($encrypt){
	global $config;
	$key = $config['encryption_key'];
    $encrypt = serialize($encrypt);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
    $key = pack('H*', $key);
    $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
    $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
    $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
    return $encoded;
}
// Decrypt Function
function mc_decrypt($decrypt){
	global $config;
	$key = $config['encryption_key'];
    $decrypt = explode('|', $decrypt.'|');
    $decoded = base64_decode($decrypt[0]);
    $iv = base64_decode($decrypt[1]);
    if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
    $key = pack('H*', $key);
    $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
    $mac = substr($decrypted, -64);
    $decrypted = substr($decrypted, 0, -64);
    $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
    if($calcmac!==$mac){ return false; }
    $decrypted = unserialize($decrypted);
    return $decrypted;
}


function encrypt_decrypt($action, $string) {
	global $config;
	$secret_key = $config['encryption_key'];
	$secret_iv = $config['encryption_iv'];

    $output = false;
    $encrypt_method = "AES-256-CBC";

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

// ----------------------------------------------------------------------------------------------
// GRAPHS

function workHoursByMonth($month,$clientid=0) { //calculate how many hours were worked in a month
	global $database;
	$secs = 0;
	$startDate = $month."-01";
	$endDate = $month."-31";

	if ($clientid == 0) {
		$items = $database->select("timelog", "*", [
			"date[<>]" => [$startDate, $endDate]
		]);
	}
	else {
		$items = $database->select("timelog", "*", [ "AND" => [
			"date[<>]" => [$startDate, $endDate],
			"clientid" => $clientid
		]]);
	}

	foreach($items as $item) {
		$secs = $secs + duration($item['date'], $item['start'], $item['end']);
	}


	$dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$secs");
    return $dtF->diff($dtT)->format('%h.%i');


	//$hours = floor($minutes / 60);
	//$minutes = ($minutes % 60);

	//return $hours . "." . $minutes;
}

function countAssetsByCategory($categoryid,$clientid=0) {
	global $database;
	$items = 0;
	if ($clientid == 0) {
		$items = $database->count("assets", "*", [
			"categoryid" => $categoryid
		]);
	}
	else {
		$items = $database->count("assets", "*", [ "AND" => [
			"categoryid" => $categoryid,
			"clientid" => $clientid
		]]);
	}

	return $items;

}


function counTicketsByDepartment($departmentid, $startdate, $enddate, $clientid=0) {
	global $database;
	$items = 0;

	if($clientid == "0") {
		$tickets = $database->select("tickets", "*", [ "AND" => [
			"timestamp[<>]" => [$startdate, $enddate],
			"departmentid" => $departmentid
		]]);
	}
	else {

		$tickets = $database->select("tickets", "*", [ "AND" => [
			"timestamp[<>]" => [$startdate, $enddate],
			"clientid" => $_GET['clientid'],
			"departmentid" => $departmentid
		]]);
	}


	return count($tickets);

}



?>
