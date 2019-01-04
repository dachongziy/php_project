<?php
	function _getUrlContent($url)
	{
		$handle = fopen($url,"r");
		if($handle){
			//读取资源流到到$content中。第一个参数是
			//读取的资源流数据，第二个(-1)表示的是读取全部缓冲区的数据
			$content = stream_get_contents($handle,-1);
			return $content;
		}else{
			return false;
		}
	}
	

	/*从html内容中筛选链接
	@param string $web_content
	@return array
	*/
	function _filterUrl($web_content)
	{
		$reg_tag_a = '/<[a|A].*?href=[\'\"]{0,1}([^>\'\"\ ]*).*?>/';
		
		/*
			$matches[1] 将包含第一个捕获子组匹配到的文本，以此类推
		*/
		$result = preg_match_all($reg_tag_a, $web_content,$match_result);
		if($result)
		{

			return $match_result[1];
		}
	}
$testFilePath = "match_result.txt";
if(file_exists($testFilePath))
{
	unlink($testFilePath);
}
$match_result = fopen($testFilePath,"a");
$array_result = _filterUrl(_getUrlContent("http://www.baidu.com/"));
foreach ($array_result as $result_single) {
	fputs($match_result,$result_single."\r\n");
	
}

	function _filterUrlAll($web_content)
	{
		$reg_tag_a = '/<[a|A].*?href=[\'\"]{0,1}([^>\'\"\ ]*).*?>/';
		
		/*
			$matches[1] 将包含第一个捕获子组匹配到的文本，以此类推
		*/
		$result = preg_match_all($reg_tag_a, $web_content,$match_result);
		if($result)
		{
			return $match_result;
		}
		else
			return NULL;
	}

	function test2($url)
	{
		$web_content = _getUrlContent($url);
	    //var_dump($web_content);
		$match_result = _filterUrlAll($web_content);
		foreach ($match_result as $key => $value) {
			echo $value."<br />";
		}
	}
    
    /*
		修正相对相对路径
		@param string $base_url
		@param array $url_list
		@return array
    */
function _reviseUrl($base_url,$url_list)
{
	$url_info = parse_url($base_url);
	$base_url = $url_info["scheme"].'://';
	if($url_info['user']&&$url_info['pass'])
	{
		$base_url .= $url_info['user'].":".$url_info['pass']."@";
	}
	$base_url .= $url_info['host'];
	if($url_info["port"]){
		$base_url .= ":".$url_info["port"];
	}
	$base_url.= $url_info["path"];
	print_r($base_url);
	if(is_array($url_list))
	{
		foreach ($url_list as $url_item) {
			if(preg_match('/^http/',$url_item)){
				//已经是完整的url
				$result[] = $url_item;
			}else
			{
				//不完整的url
				$real_url = $base_url.'/'.$url_item;
				$result[] = $real_url;
			}
		}
		return $result;
	}
	else
	{
		return;
	}
	
	
}


/**
*@param string $url
*@return array
*/
function crawler($url)
{
	$content = _getUrlContent($url);
	if($content)
	{
		$url_list = _reviseUrl($url,_filterUrl($content));
		if($url_list)
		{
			return $url_list;
		}
		else
		{
			return;
		}
	}
	else
		return;
}
/*
*测试用主程序
*/
function main()
{
	$file_path = "2.txt";
	//初始url
	$current_url = "http://www.baidu.com/";
	if(file_exists($file_path))
	{
		unlink($file_path);
	}
	$fp_puts = fopen($file_path, "ab"); //记录url列表
	//读取url列表
	$fp_gets = fopen($file_path,"r");
	do{
		$result_url_arr = crawler($current_url);
		if($result_url_arr)
		{
			foreach ($result_url_arr as $url) {
				fputs($fp_puts,$url."\r\n");
			}
		}
	}while($current_url = fgets($fp_gets,1024));

}
//main();

?>