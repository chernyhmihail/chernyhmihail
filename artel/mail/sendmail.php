<?php

if (!array_key_exists("phone", $_POST) || $_POST["phone"] == "" || $_POST["phone"] == " ")
	exit("error");

function decode_POST($str) {
	while (stripos($str, "`") != false) {
		$str = str_replace("`", "&", $str);
	}
	return $str;
}

$name = $_POST["name"];
$phone = $_POST["phone"];
$message = $_POST["comment"];
$referUrl = $_POST["ref"];
$subject = "Новая заявка Сайт - Артель";
$curentUrl = $_SERVER["HTTP_REFERER"];
// for popup
if (array_key_exists("url_from_popup", $_POST))
	$curentUrl = decode_POST($_POST["url_from_popup"]);

$referUrlArray = parse_url($referUrl);

if (array_key_exists("query", $referUrlArray))
	parse_str(urldecode($referUrlArray["query"]));
if (isset($q)){
	$keyWord = $q;
} else if (isset($text)){
	$keyWord = $text;
} else if (isset($words)){
	$keyWord = $words;
} else if (isset($query)){
	$keyWord = $query;
} else {
	$keyWord = "---";
	$referUrl = "---";
}

$curentUrlArray = parse_url($curentUrl);
$utm_multi = "---";

parse_str(urldecode($curentUrlArray["query"]));
if (isset($utm_term)){
	$keyWordUTM = $utm_term;
} else $keyWordUTM = "---";
if (!isset($utm_source)) $utm_source = "---";
if (!isset($utm_campaign)) $utm_campaign = "---";

// $subject = $subject.$curentUrlArray["host"].$curentUrlArray["path"];

$mess = "";
if ($name != "") $mess = '<b>Имя: </b>'.$name.'<br/>';
if ($_POST["mail"] != "") $mess .= '<b>E-mail: </b>'.$_POST["mail"]."<br/>";

if ($phone != "") $mess .= '<b>Телефон: </b>'.$phone."<br/><br/>";
if ($message != "") $mess .= '<b>Сообщение: </b>'.$message."<br/><br/>";

$mess .= '<b>Название формы: </b>'.$_POST["formname"]."<br/>";
$mess .= "<b>IP - Adress: </b>".$_SERVER["REMOTE_ADDR"]."<br/>";

if (!array_key_exists("first_visit", $_COOKIE)) {
	setcookie("first_visit", time()+3600);
	$mess .= "<b>Посещение: </b> первое";
} else if (time() > $_COOKIE["first_visit"]){
	$mess .= "<b>Посещение: </b> уже было";
} else $mess .= "<b>Посещение: </b> первое";

require_once("PHPMailer-master/class.phpmailer.php");

$mail = new PHPMailer();
$mail->CharSet='utf-8';
$mail->From = 'sender@'.$curentUrlArray["host"];
$mail->FromName = 'Артель';
$mail->AddAddress('chernyh.mihail@gmail.com','realizator-m@mail.ru');
$mail->IsHTML(true);
$mail->Subject = $subject;
$mail->Body = $mess;
if (!$mail->Send()){
	die('Mailer Error: '.$mail->ErrorInfo);
}

// $mail_sms = new PHPMailer();
// $mail_sms->CharSet='utf-8';
// $mail_sms->From = 'sender@'.$curentUrlArray["host"];
// $mail_sms->FromName = 'ВИТОМАСТЕР';
// $mail_sms->AddAddress('1100dcff-4355-9784-b97c-64c5e1c4b5a5+79626846005@sms.ru', '1100dcff-4355-9784-b97c-64c5e1c4b5a5+79626846005@sms.ru');
// $mail_sms->IsHTML(false);
// $mail_sms->Subject = "from:ВИТОМАСТЕР";
// if ($_POST["formname"] == undefined) {
// 	$subject_sms = "---";
// } else $subject_sms = $_POST["formname"];
// if ($_POST["name"] == undefined) {
// 	$name_sms = "---";
// } else $name_sms = $_POST["name"];
// if ($_POST["phone"] == undefined) {
// 	$phone_sms = "---";
// } else $phone_sms = $_POST["phone"];
// $mes_sms = "Название формы: ".$subject_sms;
// $mes_sms .= " Имя: ".$name_sms;
// $mes_sms .= " Тел.: ".$phone_sms;
// $mail_sms->Body = $mes_sms;
// $mail_sms->Send();

exit("success");
?>