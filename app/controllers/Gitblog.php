<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Gitblog extends CI_Controller {
	
	//配置信息
	private $confObj;
	
	//渲染时的数据绑定
	private $data;
	
	function __construct() {
		parent::__construct();
		
		$this->load->helper('file');
		$this->load->helper('url');
		
		$this->load->library('configload');
		$this->load->library('markdown');
		$this->load->library('twig');
		
		$this->init();
 	}
 	
 	//首页
 	public function index() {
 		$this->page(1);
 	}
 	
 	//初始化博客数据
	public function init() {
		
		//加载配置文件
		$this->confObj = $this->configload->loadConfig();
		
		if (!$this->confObj) {
			show_error($this->configload->errMsg(), 500, '解析配置文件出错');
		}
		
		//初始化博客信息
		$this->markdown->initAllBlogData();
		
		//所有分类
		$this->data['categoryList'] = $this->markdown->getAllCategorys();
		
		//所有标签
		$this->data['tagsList'] = $this->markdown->getAllTags();
		
		//归档月份
		$this->data['yearMonthList'] = $this->markdown->getAllYearMonths();
		
		//配置文件
		$this->data['confObj'] = $this->confObj;
		
		//最近博客
		$recentSize = $this->confObj['blog']['recentSize'];
		$this->data['recentBlogList'] = $this->markdown->getBlogsRecent($recentSize);
	}
	
	//分类下的博客列表
	public function category($categoryId, $pageNo=1) {
		$categoryId = (int)$categoryId;
		$pageNo = (int)$pageNo;
		$pageSize = $this->confObj['blog']['pageSize'];
		
		$pages = $this->markdown->getCategoryTotalPages($categoryId, $pageSize);
		
		if ($pageNo <= 0) {
			$pageNo = 1;
		}
		
		if ($pageNo > $pages) {
			$pageNo = $pages;
		}
		
		$pageData = $this->markdown->getBlogsPageByCategory($categoryId, $pageNo, $pageSize);
		
		$category = $this->markdown->getCategoryById($categoryId);
		
		$this->setData("pageName", "category");
		$this->setData("categoryId", $categoryId);
		$this->setData("category", $category);
		$this->setData("pageNo", $pageNo);
		$this->setData("pages", $pageData['pages']);
		$this->setData("blogList", $pageData['blogList']);
		
		$this->render('index.html');
	}
	
	//按月归档下的博客列表
	public function archive($yearMonth, $pageNo=1) {
		$pageNo = (int)$pageNo;
		$pageSize = $this->confObj['blog']['pageSize'];
		
		$pages = $this->markdown->getYearMonthTotalPages($yearMonth, $pageSize);
		
		if ($pageNo <= 0) {
			$pageNo = 1;
		}
		
		if ($pageNo > $pages) {
			$pageNo = $pages;
		}
		
		$pageData = $this->markdown->getBlogsPageByYearMonth($yearMonth, $pageNo, $pageSize);
		
		$this->setData("pageName", "archive");
		$this->setData("yearMonth", $yearMonth);
		$this->setData("pageNo", $pageNo);
		$this->setData("pages", $pageData['pages']);
		$this->setData("blogList", $pageData['blogList']);
		
		$this->render('index.html');
	}
	
	//标签下的博客列表
	public function tags($tagId, $pageNo=1) {
		$this->pageName = "tags";
		
		$tagId = (int)$tagId;
		$pageNo = (int)$pageNo;
		$pageSize = $this->confObj['blog']['pageSize'];
		
		$pages = $this->markdown->getTagTotalPages($tagId, $pageSize);
		
		if ($pageNo <= 0) {
			$pageNo = 1;
		}
		
		if ($pageNo > $pages) {
			$pageNo = $pages;
		}
		
		$pageData = $this->markdown->getBlogsPageByTag($tagId, $pageNo, $pageSize);
		
		$tag = $this->markdown->getTagById($tagId);
		
		$this->setData("pageName", "tags");
		$this->setData("tagId", $tagId);
		$this->setData("tag", $tag);
		$this->setData("pageNo", $pageNo);
		$this->setData("pages", $pageData['pages']);
		$this->setData("blogList", $pageData['blogList']);
		
		$this->render('index.html');
	}
	
	//首页，博客列表
	public function page($pageNo=1) {
		$pageNo = (int)$pageNo;
		$pageSize = $this->confObj['blog']['pageSize'];
		
		$pages = $this->markdown->getTotalPages($pageSize);
		
		if ($pageNo <= 0) {
			$pageNo = 1;
		}
		
		if ($pageNo > $pages) {
			$pageNo = $pages;
		}
		
		$pageData = $this->markdown->getBlogsByPage($pageNo, $pageSize);
		
		$this->setData("pageName", "home");
		$this->setData("pageNo", $pageNo);
		$this->setData("pages", $pageData['pages']);
		$this->setData("blogList", $pageData['blogList']);
		
		$this->render('index.html');
	}
	
	//博客详情页
	public function blog() {
		$openPage = uri_string();
		$blogId = md5($openPage);
		
		$blog = $this->markdown->getBlogById($blogId);
		if ($blog == null) {
			show_404();
		}
		
		$this->setData("pageName", "blog");
		$this->setData("blog", $blog);
		$this->render('detail.html');
	}
	
	//设置渲染数据
	private function setData($key, $dataObj) {
 		$this->data[$key] = $dataObj;
 	}
 	
 	//渲染页面
 	private function render($tpl) {
 		$this->twig->render($tpl, $this->data, TRUE);
 	}
}
