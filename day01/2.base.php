<?php
	//测试php代码可执行
	$i=10;
	$i++;
	echo "<h3>abcd-----$i</h3>";
	//显示当前时间(测试时间配置)
	echo date("Y-m-d H:i:s");
	//连接mysql 数据库（测试数据库连接）
	$conn = mysqli_connect("localhost","root","123","yang");
	echo "<hr />";
	var_dump($conn);
	mysqli_close($conn);
	phpinfo();
?>