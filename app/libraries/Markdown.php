<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

require APPPATH . 'third_party/parsedown/Parsedown.php';
  
class Markdown {
	
	//所有的博客文章
	private $blogs;
	
	//所有的标签
	private $tags;
	
	//所有的分类
	private $categorys;
	
	//所有月份
	private $yearMonths;
	
	//博客文件目录
	private $postPath;
	
	//CI
	private $CI;
	
	//博客属性
	public $notePropArray;
	
	//博客分类缓存
	private $_cacheCategoryBlogs = array();
	
	//博客分标签缓存
	private $_cacheTagBlogs = array();
	
	//博客按关键字缓存
	private $_cacheTitleBlogs = array();
	
	//博客按月缓存
	private $_cacheYearMonthBlogs = array();
	
	public function __construct() {
		if (!isset($this->CI)) {
			$this->CI =& get_instance();
		}
		
		$this->notePropArray = array("author", "date", "title", "summary", "tags", "category", "status");
		
		$this->CI->load->helper('file');
		$this->CI->load->helper('url');
		
    	$this->postPath = str_replace("\\", "/", dirname(APPPATH)) . '/posts/';
	}
	
	//按分类查找博客
	public function getBlogByCategory($categoryId) {
		if (isset($this->_cacheCategoryBlogs[$categoryId])) {
			return $this->_cacheCategoryBlogs[$categoryId];
		}
		
		$blogList = array();
		foreach ($this->blogs as $idx => $blog) {
			$categoryArr = $blog['category'];
			
			if (count($categoryArr) > 0) {
				foreach ($categoryArr as $idx => $cateObj) {
					if ($cateObj['id'] == $categoryId) {
						array_push($blogList, $blog);
						continue;
					}
				}
			}
		}
		$this->_cacheCategoryBlogs[$categoryId] = $blogList;
		return $blogList;
	}
	
	//按标签查找博客
	public function getBlogByTag($tagId) {
		if (isset($this->_cacheTagBlogs[$tagId])) {
			return $this->_cacheTagBlogs[$tagId];
		}
		
		$blogList = array();
		foreach ($this->blogs as $idx => $blog) {
			$tagArr = $blog['tags'];
			
			if (count($tagArr) > 0) {
				foreach ($tagArr as $idx => $tagObj) {
					if ($tagObj['id'] == $tagId) {
						array_push($blogList, $blog);
						continue;
					}
				}
			}
		}
		
		$this->_cacheTagBlogs[$tagId] = $blogList;
		
		return $blogList;
	}
	
	//按月份查找博客
	public function getBlogByYearMonthId($yearMonthId) {
		if (isset($this->_cacheYearMonthBlogs[$yearMonthId])) {
			return $this->_cacheYearMonthBlogs[$yearMonthId];
		}
		
		$blogList = array();
		foreach ($this->blogs as $idx => $blog) {
			$_yearMonthId = date("Ym", strtotime($blog['ctime']));
			if ($yearMonthId == $_yearMonthId) {
				array_push($blogList, $blog);
			}
		}
		
		$this->_cacheYearMonthBlogs[$yearMonthId] = $blogList;
		
		return $blogList;
	}
	
	//按标题关键字查找博客
	public function getBlogByTitle($title) {
		if (isset($this->_cacheTitleBlogs[$title])) {
			return $this->_cacheTitleBlogs[$title];
		}
		
		$blogList = array();
		foreach ($this->blogs as $idx => $blog) {
			$blogTitle = $blog['title'];
			
			if (strpos($blogTitle, $title) >= 0) {
				array_push($blogList, $blog);
			}
		}
		
		$this->_cacheTagBlogs[$title] = $blogList;
		return $blogList;
	}
	
	//根据Id获取博客
	public function getBlogById($blogId) {
		foreach ($this->blogs as $idx => $blog) {
			if ($blog['blogId'] == $blogId) {
				return $blog;
			}
		}
		return NULL;
	}
	
	//根据Id获取分类
	public function getCategoryById($categoryId) {
		foreach ($this->categorys as $idx => $category) {
			if ($category['id'] == $categoryId) {
				return $category;
			}
		}
		return NULL;
	}
	
	//根据Id获取标签
	public function getTagById($tagId) {
		foreach ($this->tags as $idx => $tag) {
			if ($tag['id'] == $tagId) {
				return $tag;
			}
		}
		return NULL;
	}
	
		//根据Id获取标签
	public function getYearMonthById($yearMonthId) {
		foreach ($this->yearMonths as $idx => $yearMonth) {
			if ($yearMonth['id'] == $yearMonthId) {
				return $yearMonth;
			}
		}
		return NULL;
	}
	
	//获取总页数
	public function getTotalPages($pageSize) {
		$total = count($this->blogs);
		return ceil($total / $pageSize);
	}
	
	//获取某个分类的总页数
	public function getCategoryTotalPages($categoryId, $pageSize) {
		$blogList = $this->getBlogByCategory($categoryId);
		$total = count($blogList);
		return ceil($total / $pageSize);
	}
	
	//获取某个标签的总页数
	public function getTagTotalPages($tagId, $pageSize) {
		$blogList = $this->getBlogByTag($tagId);
		$total = count($blogList);
		return ceil($total / $pageSize);
	}
	
	//获取某个月的总页数
	public function getYearMonthTotalPages($yearMonthId, $pageSize) {
		$blogList = $this->getBlogByYearMonthId($yearMonthId);
		$total = count($blogList);
		return ceil($total / $pageSize);
	}
	
	//按分类获取分页列表
	public function getBlogsPageByCategory($categoryId, $pageNo, $pageSize) {
		$blogList = $this->getBlogByCategory($categoryId);
		$total = count($blogList);
		$pages = ceil($total / $pageSize);
		$offset = ($pageNo - 1) * $pageSize;
		$blogList = array_slice($blogList, $offset, $pageSize);
		
		return array(
			"total" => $total,
			"pages" => $pages,
			"blogList" => $blogList
		);
	}
	
	//按标签获取分页列表
	public function getBlogsPageByTag($tagId, $pageNo, $pageSize) {
		$blogList = $this->getBlogByTag($tagId);
		$total = count($blogList);
		$pages = ceil($total / $pageSize);
		$offset = ($pageNo - 1) * $pageSize;
		$blogList = array_slice($blogList, $offset, $pageSize);
		
		return array(
			"total" => $total,
			"pages" => $pages,
			"blogList" => $blogList
		);
	}
	
	//按月份获取分页列表
	public function getBlogsPageByYearMonth($yearMonthId, $pageNo, $pageSize) {
		$blogList = $this->getBlogByYearMonthId($yearMonthId);
		$total = count($blogList);
		$pages = ceil($total / $pageSize);
		$offset = ($pageNo - 1) * $pageSize;
		$blogList = array_slice($blogList, $offset, $pageSize);
		
		return array(
			"total" => $total,
			"pages" => $pages,
			"blogList" => $blogList
		);
	}
	
	//博客分页列表
	public function getBlogsByPage($pageNo, $pageSize) {
		$total = count($this->blogs);
		$pages = ceil($total / $pageSize);
		$offset = ($pageNo - 1) * $pageSize;
		$blogList = array_slice($this->blogs, $offset, $pageSize);
		
		return array(
			"total" => $total,
			"pages" => $pages,
			"blogList" => $blogList
		);
	}
	
	//最新的博客
	public function getBlogsRecent($pageSize) {
		return array_slice($this->blogs, 0, $pageSize);
	}
	
	//获取所有分类
	public function getAllCategorys() {
		return $this->categorys;
	}
	
	//获取所有标签
	public function getAllTags() {
		return $this->tags;
	}
	
	//获取所有月份
	
	public function getAllYearMonths() {
		return $this->yearMonths;
	}
	
	//获取所有博客
	public function getAllBlogs() {
		return $this->blogs;
	}
	
	//解析markdown文件内容为html文本
	private function parseMarkdown($text) {
		return Parsedown::instance()->parse($text);
	}
	
	//加载所有的博客
	public function initAllBlogData() {
		$this->blogs = array();
		$this->tags = array();
		$this->categorys = array();
		$this->yearMonths = array();
		
		//列出所有文件，可能包含非markdown文件
		$mdfiles = get_dir_file_info($this->postPath, FALSE);
		
		$this->readAllPostInfo($mdfiles);
	}
	
	//读取post的内容
	private function readPostContent($filePath) {
		$blogHtml = "";
		$content = read_file($filePath);
		if (!empty($content)) {
			$blogHtml = $this->parseMarkdown($content);
		}
		return $blogHtml;
	}
	
	//获取博客头部的注释块信息
	private function getCleanNoteBlockArr($serverPath) {
		$noteBlockArr = array();
		$fcontents = file($serverPath);
		$start = false;
		
		if (!empty($fcontents)) {
			foreach ($fcontents as $textLine) {
				$textLine = trim($textLine);
				if ($textLine == "<!--")  {
					$start = true;
				} else if ($textLine == "-->") {
					$start = false;
					break;
				} else {
					if ($this->checkNoteLine($textLine)) {
						array_push($noteBlockArr, $textLine);
					}
				}
			}
		}
		return $noteBlockArr;
	}
	
	//读取博客的基本信息
	private function readPostBaseInfo($serverPath) {
		$noteBlockArr = $this->getCleanNoteBlockArr($serverPath);
		$keywrodsArr = array();
		$tagsArr = array();
		$cateArr = array();
		
		$blogProp = array(
			"author" => "",
			"date" => "",
			"title" => "",
			"summary" => "",
			"keywords" => "",
			"tags" => array(),
			"category" => array(),
			"status" => "publish"
		);
		
		foreach ($noteBlockArr as $textLine) {
			$noteTmpArr = explode(":", $textLine);
			$propName = trim($noteTmpArr[0]);
			$propVal = trim($noteTmpArr[1]);
			switch($propName) {
				case "author":
					$blogProp['author'] = $propVal;
					break;
				case "date":
					$blogProp['date'] = $propVal;
					break;
				case "title":
					$blogProp['title'] = $propVal;
					break;
				case "summary":
					$blogProp['summary'] = $propVal;
					break;
				case "tags":
					$blogProp['tags'] = $this->converStrArr($propVal, "tags");
					$tagsArr = $this->cleanKeywords2Arr($propVal);
					break;
				case "category":
					$blogProp['category'] = $this->converStrArr($propVal, "category");
					$cateArr = $this->cleanKeywords2Arr($propVal);
					break;
				case "status":
					$blogProp['status'] = $propVal == "draft" ? $propVal : "publish";
					break;
			}
		}
		
		$keywrodsArr = array_merge($tagsArr, $cateArr);
		
		$blogProp['keywords'] = implode(",", $keywrodsArr);
		
		return $blogProp;
	}
	
	//获取标签，分类数组
	private function cleanKeywords2Arr($keywordsStr) {
		$tagsArr = array();
		
		//$tagArrTmp1 = explode(",", $keywordsStr);
		//$tagArrTmpl = preg_split("[，,|；、\s]+", $keywordsStr);
		
		mb_regex_encoding("UTF-8");
		mb_internal_encoding("UTF-8");
		$tagArrTmpl = mb_split("[\s,;|，；、]+", $keywordsStr);
		foreach ($tagArrTmpl as $tag) {
			$tag = trim($tag);
			if ($tag != "" && !in_array($tag, $tagsArr)) {
				array_push($tagsArr, $tag);
			}
		}
		
		return $tagsArr;
	}
	
	//读取所有博客的信息
	private function readAllPostInfo($mdfiles) {
		foreach ($mdfiles as $fileName => $fileProp) {
			
			//非markdown文件，不处理，直接过滤
			if (!$this->checkFileExt($fileName)) continue;
			
			$fileName = $fileProp['name'];
			$mtime = date("Y-m-d H:i:s", $fileProp['date']);
			$ctime = date("Y-m-d H:i:s", $fileProp['cdate']);
			$serverPath = str_replace("\\", "/", $fileProp['server_path']);
			$relativePath = str_replace($this->postPath, "", $serverPath);
			
			$sitePath = $this->changeFileExt($relativePath);
			$siteURL = "/blog/" . $this->changeFileExt($relativePath);
			
			$blogId = md5($siteURL);
			
			//读取博客内容
			$content = $this->readPostContent($serverPath);
			
			//读取自定义博客属性信息
			$blogProp = $this->readPostBaseInfo($serverPath);
			
			//没有title的博客不处理
			if (empty($blogProp['title'])) continue;
			
			//草稿状态的不处理
			if ($blogProp == "draft") continue;
			
			$blog = array(
				"blogId" => $blogId,
				"fileName" => $fileName,
				"serverPath" => $serverPath,
				"sitePath" => $sitePath,
				"mtime" => $mtime,
				"ctime" => $ctime,
				"siteURL" => $siteURL,
				"content" => $content
			);
			
			$month = date("Y-m", strtotime($ctime));
			$yearMonthId = date("Ym", strtotime($ctime));
			$monthObj = array(
				"id" => $yearMonthId,
				"name" => $month,
				"url" => "/archive/" . $yearMonthId . ".html"
			);
			
			if (!$this->checkObjInArr($monthObj, "yearMonths")) {
				array_push($this->yearMonths, $monthObj);
			}
			$blog = array_merge($blog, $blogProp);
			array_push($this->blogs, $blog);
		}
		
		$this->sortBlogs($this->blogs, 'date');
	}
	
	//对所有博客排序
	private function sortBlogs($blogArray, $sortKey) {
		if (count($blogArray) <= 0) return $blogArray;
		
		$ctimeArr = null;
		$dateArr = null;
		
		foreach ($blogArray as $key => $row) {
			$dateArr[$key] = $row[$sortKey];
			$ctimeArr[$key] = $row['mtime'];
		}
		
		array_multisort($dateArr, SORT_DESC, $ctimeArr, SORT_DESC, $blogArray);
		
		$this->blogs = $blogArray;
	}
	
	//检查文件名是否是markdown文件
	private function checkFileExt($fileName) {
		$pics = explode('.' , $fileName);
		$fileExt = strtolower(end($pics));
		
		if (($fileExt != "md" && $fileExt != "markdown") || $fileExt == $fileName) {
			return false;
		}
		return true;
	}
	
	//修改后缀名
	public function changeFileExt($fileName, $ext="html") {
		$pics = explode('.' , $fileName);
		if (count($pics) > 1) {
			$pics[count($pics) -1] = $ext;
		}
		
		return implode(".", $pics);
	}
	
	//检查markdown注释块
	private function checkNoteLine($textLine) {
		$noteTmpArr = explode(":", $textLine);
		if (count($noteTmpArr) > 1 && in_array(trim($noteTmpArr[0]), $this->notePropArray)) {
			return true;
		}
		return false;
	}
	
	//将tags, category字符串转成数组
	private function converStrArr($tags, $type) {
		$tagsObjArr = array();
		
		$tagArrTmp1 = $this->cleanKeywords2Arr($tags);
		
		foreach ($tagArrTmp1 as $tag) {
			$tag = trim($tag);
			$id = abs(crc32(md5($tag)));
			
			$tagObj = array(
				"id" => $id,
				"name" => $tag,
				"url" => "/$type/" . $id . ".html"
			);
			
			array_push($tagsObjArr, $tagObj);
			
			if (!$this->checkObjInArr($tagObj, $type)) {
				if ($type == "tags") {
					array_push($this->tags, $tagObj);
				} else {
					array_push($this->categorys, $tagObj);
				}
			}
		}
		return $tagsObjArr;
	}
	
	private function checkObjInArr($tagObj, $type) {
		$objArr = null;
		if ($type == "category") {
			$objArr = $this->categorys;
		} else if ($type == "tags") {
			$objArr = $this->tags;
		} else if ($type == "yearMonths") {
			$objArr = $this->yearMonths;
		}
		
		foreach ($objArr as $idx => $obj) {
			if ($obj['id'] == $tagObj['id']) {
				return true;
			}
		}
		return false;
	}
}
