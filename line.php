<?php

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'KiwDFhY6im6LoTnTbDz0H/jiSb9KQBZqK/YXOto8HoEMo4/kZI0JQEe10dBi4LRwbrwClO7YyXt/XybRI83oKaqZXxZkF+liuucK0NXTjKRipaRC6Mv5vPKks6YPd17HD+L0C1/XSl+xz8lU3iMtbgdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array
if ( sizeof($request_array['events']) > 0 )
{

 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];

  if ( $event['type'] == 'message' ) 
  {
   
   if( $event['message']['type'] == 'text' )
   {
  $text = $event['message']['text'];
  if($text == "ชื่ออะไร" || $text == "ชื่ออะไรครับ" || $text == "ชื่ออะไรคะ")
  {
   $reply_message = 'Rabbittt';   
  }
     else if($text == "ทำไรอยู่" || $text == "ทำอะไรอยู่" || $text == "ทำรายอะ")
  {
   $reply_message = 'คิดถึงคนถามอยู่ค้าบ อิอิ'; 
  }
     else if($text == "ชื่อผู้พัฒนา")
  {
   $reply_message = 'นางสาวนภัสสร แสงเรือง รหัสนิสิต 61160063'; 
  }
     else if($text == "CDMA" || $text == "cdma")
  {
   /*
     Bit 0
     Bit +1
     Bit silant
     Bit +1
   */
   $reply_message = 'CDMA : +1,-3,-1,-1'; 
  }
     if($text == "สถานการณ์โควิดวันนี้" || $text == "covid19" || $text == "covid-19" || $text == "Covid-19")
  {
       $url = 'https://covid19.th-stat.com/api/open/today';
       $ch = curl_init($url);
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
       $result = curl_exec($ch);
       curl_close($ch);   

       $obj = json_decode($result);

       //$reply_message = $result;
       $reply_message = 'ติดเชื้อสะสม : '. $obj->{'Confirmed'} . ' คน'."\n".'รักษาหายแล้ว : ' . $obj->{'Recovered'} . ' คน';
     // $reply_message = 'ผลการบันทึก '. $obj->{'Confirmed'} . ' OK'."\n".'รักษาหายแล้ว : ' . $obj->{'Recovered'} . ' คน';
  }
  //$reply_message = '('.$text.') ได้รับข้อความเรียบร้อย!!';   
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 
  if( strlen($reply_message) > 0 )
  {
   //$reply_message = iconv("tis-620","utf-8",$reply_message);
   $data = [
    'replyToken' => $reply_token,
    'messages' => [['type' => 'text', 'text' => $reply_message]]
   ];
   $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

   $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
   echo "Result: ".$send_result."\r\n";
  }
 }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}

?>
