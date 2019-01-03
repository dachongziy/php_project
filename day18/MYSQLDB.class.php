<?php

	//类名,也习惯（推荐）使用跟文件名相似的名字
	//定义一个mysql连接类,该类可以连接mysql数据库
	//并实现其单例模式
	//该类的功能还能够完成如下mysql基本操作
	//执行普通的增删改并返回结果集的语句
	//执行select语句并可以返回3种类型的数据
	//多行结果(二维数组)，单行结果(一位数组)
	//单行单列(单个数据)

class MYSQLDB{
	public $host;
	public $port;
	public $username;
	public $password;
	public $charset;
	public $dbname;

	//连接结果(结果)
	private static $link;

	private $resourc;

	public static function getInstance($config)
	{
		if(!isset(self::$link))
		{
			self::$link = new self($config);
		}
		return self::$link;
	}
	public function _construct($config){
		//初始化数据
		$this->host = isset($config['host'])?$config['host']:'localhost';
		$this->port = isset($config['port'])?$config['port']:'3306';
		$this->username = isset($config['username'])?$config['username']:'root';
		$this->password = isset($config['password'])?$config['password']:'';
		$this->charset  = isset($config['charset'])?$config['charset']:'utf8';
		$this->dbname = isset($config['dbname'])?$config['dbname']:'';
		
		//连接数据库
		$this->connect();
		//设定连接编码
		$this->setCharset($this->charset);
		//选定数据库
		$this->selectDb($this->dbname);

	}
		//禁止克隆
		private function _clone(){

		}
		//在这里进行连接
		public function connect()
		{
			$this->resourc = mysql_connect("$this->host:$this->port","username","$this->password") or die("连接数据库失败!");
		}
		public function setCharset($charset)
		{
			mysql_set_charset($charset,$this->resourc);
		}
		public function selectDb($dbname)
		{
			mysql_select_db($dbname,$this->resourc);
		}		
		public function query($sql)
		{
			if(!$result = mysql_query($sql,$this->resourc))
			{
				echo "<br />执行失败.";
				echo "<br />失败的sql语句为:".$sql;
				echo "<br />出错信息为:".mysql_erro();
				echo "<br />错误代号为:".mysql_errno();
			}
			return $result;
		}

		/**
		*功能:执行select语句,返回2维数组
		*参数:$sql 字符串类型select 语句
		*/
		public function getAll($sql)
		{
			$result = $this->query($sql);
			$arr = array(); //空数组
			//while循中每次可获得一行
			while($rec = mysql_fetch_assoc($result))
			{
				/**
				形如如下情况:
				***********
				***********
				***********
				***********
				*/
				//这样可形成二维数组
				$arr[] = $rec;
			}
			return $arr;
		}
		//返回一行数据(作为一维数组)
		public function getRow($sql)
		{
			$result = $this->query($sql);
			//$rec = array();
			//如果fetch出来有数据(也就是取得了一行数据)，结果自然是数组
			if($rec2 = mysql_fetch_assoc($result))
			{
				return $rec2;
			}
			return false;
		}
		//返回一个数据(select语句的第一行第一列)
	    public function getOne($sql)
	    {
	    	$result = $this->query($sql);
	    	$rec = mysql_fetch_row($result);
	    	if($result == false)
	    	{
	    		return false;
	    	}
	    	return $rec[0];
	    }
}		


?>