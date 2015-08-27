<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'third_party/spyc/Spyc.php';

class Yaml {
	
	public function load($filePath) {
		return Spyc::YAMLLoad($filePath);
    }
    
    public function dump($array) {
        return Spyc::YAMLDump($array, 4);
    }
    
    public function getConfObject($filePath) {
    	$confObj = $this->load($filePath);
    	if (isset($confObj['text']['intro']) && is_array($confObj['text']['intro'])) {
    		$confObj['text']['intro'] = implode("", $confObj['text']['intro']);
    	}
    	
    	$defaultConf = array(
    		"url" => "/",
    		"title" => "Your-GitBlog",
    		"subtitle" => "自豪地采用GitBlog",
    		"theme" => "simple",
    		"enableCache" => true,
    		"highlight" => true,
    		"mathjax" => false,
    		"duoshuo" => "",
    		"baiduAnalytics" => "",
    		"keywords" => "GitBlog,博客,Markdown博客,jockchou",
    		"description" => "GitBlog是一个简单易用的Markdown博客系统",
    		"version" => "2.1",
    		"author" => array(
    			"name" => "your-name",
    			"email" => "your-email@example.com",
    			"github" => "your-github",
    			"weibo" => "your-weibo",
    			"avatar" => ""
    		),
    		"blog" => array(
    			"recentSize" => 5,
    			"pageSize" => 10,
    			"pageBarSize" => 5,
    			"allBlogsForPage" => false
    		),
    		"text" => array(
    			"title" => "介绍",
    			"intro" => "本博客使用Gitblog搭建，欢迎你!"
    		)
    	);
    	
    	$conf = array();
    	
    	foreach ($defaultConf as $k => $val) {
    		if(isset($confObj[$k])) {
    			if (is_array($confObj[$k]) && is_array($val)) {
    				$conf[$k] = array_merge($val, $confObj[$k]);
    			} else {
    				$conf[$k] = $confObj[$k];
    			}
    		} else {
    			$conf[$k] = $val;
    		}
    	}
    	return $conf;
    }
}