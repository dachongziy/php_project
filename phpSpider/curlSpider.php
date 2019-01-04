<?php
//初始化curl会话
$ch = curl_init("http://www.example.com/");
$filePath = "example_homepage.txt";
if(file_exists($filePath))
{
	unlink($filePath);
}
$fp = fopen($filePath,"w");
//设置curl相关操作
curl_setopt($ch,CURLOPT_FILE,$fp);

curl_setopt($ch,CURLOPT_HEADER,0);
//执行会话操作
curl_exec($ch);
curl_close($ch);
fclose($fp);
