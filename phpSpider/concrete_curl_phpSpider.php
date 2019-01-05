<?php

/*
*将demo1-01换成curl爬虫
*爬虫程序--原型
*从给定的url获取html内容
* @param string $url
* @return string
*/

function _getUrlContent($url)
{
	//初始化一个curl会话
	$ch = curl_init();
	//设置获取的 URL地址
	curl_setopt($ch, CURLOPT_URL, $url);
	//获取的信息已字符串返回,而不是直接输出
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//启用时会将头文件的信息作为数据流输出
	curl_setopt($ch,CURLOPT_HEADER,1);
	//设置浏览器的特定header
	curl_setopt($ch,CURLOPT_HTTPHEADER,array(
		"Host: www.baidu.com",
        "Connection: keep-alive",
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
        "Upgrade-Insecure-Requests: 1",
        "DNT:1",
        "Accept-Language: zh-CN,zh;q=0.8,en-GB;q=0.6,en;q=0.4,en-US;q=0.2",
	));
	//执行一个CURL会话
	$result = curl_exec($ch);
	//最后一个收到的的HTTP代码
	$code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	if($code!='404' && $result)
	{
		return $result;
	}
	else 
		return NULL;
	//关闭curl
	curl_close($ch);
}

/**
*从html内容中筛选内容
*@param string $werb_content
*@param array
*/
function filterUrl($web_content)
{
	$reg_tag_a = '/<[a|A].*?href=[\'\"]{0,1}([^>\'\"\ ]*).*?>/';
	$result = preg_match_all($reg_tag_a, $web_content ,$match_result);
	if($result){
		return $match_result[1];
	}
}

/*
*修正相对路径
* @param string $base
* @param array $url_list
* array
*/
function _reviseUrl($base_url, $url_list) {
    $url_info = parse_url($base_url);//解析url
    $base_url = $url_info["scheme"] . '://';
    if ($url_info["user"] && $url_info["pass"]) {
        $base_url .= $url_info["user"] . ":" . $url_info["pass"] . "@";
    } 
    $base_url .= $url_info["host"];
    if ($url_info["port"]) {
        $base_url .= ":" . $url_info["port"];
    } 
    $base_url .= $url_info["path"];
    print_r($base_url);
    if (is_array($url_list)) {
        foreach ($url_list as $url_item) {
            if (preg_match('/^http/', $url_item)) {
                // 已经是完整的url
                $result[] = $url_item;
            } else {
                // 不完整的url
                $real_url = $base_url . '/' . $url_item;
                $result[] = $real_url;
            } 
        } 
        return $result;
    } else {
        return;
    } 
} 

/**
*爬虫
*@param string $url
*@param array
*/
function crawler($url)
{
	$content = _getUrlContent($url);
	if($content){
		$url_list = _reviseUrl($url,filterUrl($content));
		if($url_list){
			return $url_list;
		}else{
			return ;
		}

	}else {
		return ;
	}
}

/**
*测试用主程序
*/
function main()
{
	$file_path = "./url-03";
	if(file_exists($file_path)){
		unlink($file_path);
	}
	$current_url = "http://www.baidu.com";
	//追加打开一个二进制文件,并在文件末尾写数据
	$fp_puts = fopen($file_path, "ab");
	//保存url列表 r-只读方式打开，将文件指向文件夹
	$fp_gets = fopen($file_path, "r");
	do{
		$result_url_arr = crawler($current_url);
		echo "<p>$current_url</p>";
		if($result_url_arr)
		{
			foreach ($result_url_arr as $url) {
				fputs($fp_puts,$url."\r\n");
			}
		}

	}while($current_url = fgets($fp_gets,1024));
}
main();
?>