<?php

    /**
    *  项目中的工厂类
    */
    class Factory{
        /*
        *生成模型的单例对象
        */
        public static function M($model_name)
        {
        	//存储已经实例好的模型对象的列表，下标模型名，值模型对象
        	static $model_list = array();
        	if(!isset($model_list[$model_name]))
        	{
        		//没有实例化
        		require './'.$model_name.'.class.php';
        		$model_list[$model_name] = new $model_name;
        	}
        	return $model_list[$model_name];
        }
    }

?>
