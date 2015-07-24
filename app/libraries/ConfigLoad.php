<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class ConfigLoad {
	
	//配置文件
	private $configPath;
	private $CI;
	private $_error;
	
	public function __construct() {
		if (!isset($this->CI)) {
			$this->CI =& get_instance();
		}
    	$this->configPath = str_replace("\\", "/", dirname(APPPATH)) . '/conf.json';
	}
	
	//读取配置文件
	public function loadConfig() {
		$confText = read_file($this->configPath);
		
		$confObj = false;
		
		if ($confText == null) {
			$this->_error = "读取配置文件时出错";
		} else {
			$confObj = json_decode($confText, TRUE);
			if (empty($confObj) OR !isset($confObj['author']) OR !isset($confObj['blog'])) {
				$this->_error = "配置文件格式错误";
			}
		}
		
		return $confObj;
	}
	
	//返回错误信息
	public function errMsg() {
		return $this->_error;
	}
}
