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
	
	//是否开启缓存
	private $enableCache = false;
	
	//CI
	private $CI;
	
	public function __construct() {
		if (!isset($this->CI)) {
			$this->CI =& get_instance();
		}
		
		$this->CI->load->helper('file');
		$this->CI->load->helper('url');
		$this->CI->load->driver('cache');
	}
	
	//按分类查找博客
	public function getBlogByCategory($categoryId) {
		$cacheKey = "getBlogByCategory_" . $categoryId . ".gb";
		$blogList = $this->gbReadCache($cacheKey);
		
		if ($blogList === false) {
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
			$this->gbWriteCache($cacheKey, $blogList);
		}
		return $blogList;
	}
	
	//按标签查找博客
	public function getBlogByTag($tagId) {
		$cacheKey = "getBlogByTag_" . $tagId . ".gb";
		$blogList = $this->gbReadCache($cacheKey);
		
		if ($blogList === false) {
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
			$this->gbWriteCache($cacheKey, $blogList);
		}
		
		return $blogList;
	}
	
	//按月份查找博客
	public function getBlogByYearMonthId($yearMonthId) {
		$cacheKey = "getBlogByYearMonthId_" . $yearMonthId . ".gb";
		$blogList = $this->gbReadCache($cacheKey);
		
		if ($blogList === false) {
			$blogList = array();
			foreach ($this->blogs as $idx => $blog) {
				$_yearMonthId = date("Ym", strtotime($blog['date']));
				if ($yearMonthId == $_yearMonthId) {
					array_push($blogList, $blog);
				}
			}
			$this->gbWriteCache($cacheKey, $blogList);
		}
		return $blogList;
	}
	
	//按标题关键字查找博客
	public function getBlogByTitle($title, $max = 50) {
		$cacheKey = "getBlogByTitle_" . (md5($title)) . "_" . $max . ".gb";
		$blogList = $this->gbReadCache($cacheKey);
		
		if ($blogList === false) {
			$blogList = array();
			foreach ($this->blogs as $idx => $blog) {
				$blogTitle = strtolower($blog['title']);
				$title = strtolower($title);
				
				if (strpos($blogTitle, $title) !== FALSE) {
					array_push($blogList, $blog);
				}
				
				if (count($blogList) >= $max) break;
			}
			$this->gbWriteCache($cacheKey, $blogList);
		}
		return $blogList;
	}
	
	//根据Id获取博客
	public function getBlogById($blogId) {
		$cacheKey = "getBlogById_" . $blogId . ".gb";
		$blogObj = $this->gbReadCache($cacheKey);
		
		if ($blogObj === false) {
			foreach ($this->blogs as $idx => $blog) {
				if ($blog['blogId'] == $blogId) {
					$blogObj = $blog;
					$this->gbWriteCache($cacheKey, $blogObj);
					break;
				}
			}
		}
		return $blogObj;
	}
	
	//根据Id获取分类
	public function getCategoryById($categoryId) {
		$cacheKey = "getCategoryById_" . $categoryId . ".gb";
		$categoryObj = $this->gbReadCache($cacheKey);
		
		if ($categoryObj === false) {
			foreach ($this->categorys as $idx => $category) {
				if ($category['id'] == $categoryId) {
					$categoryObj = $category;
					$this->gbWriteCache($cacheKey, $categoryObj);
					break;
				}
			}
		}
		return $categoryObj;
	}
	
	//根据Id获取标签
	public function getTagById($tagId) {
		$cacheKey = "getTagById_" . $tagId . ".gb";
		$tagObj = $this->gbReadCache($cacheKey);
		
		if ($tagObj === false) {
			foreach ($this->tags as $idx => $tag) {
				if ($tag['id'] == $tagId) {
					$tagObj = $tag;
					$this->gbWriteCache($cacheKey, $tagObj);
					break;
				}
			}
		}
		return $tagObj;
	}
	
		//根据Id获取标签
	public function getYearMonthById($yearMonthId) {
		$cacheKey = "getYearMonthById_" . $yearMonthId . ".gb";
		$yearMonthObj = $this->gbReadCache($cacheKey);
		
		if ($yearMonthObj === false) {
			foreach ($this->yearMonths as $idx => $yearMonth) {
				if ($yearMonth['id'] == $yearMonthId) {
					$yearMonthObj = $yearMonth;
					$this->gbWriteCache($cacheKey, $yearMonthObj);
					break;
				}
			}
		}
		return $yearMonthObj;
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
	public function initAllBlogData($postPath, $enableCache=false) {
		$this->blogs = array();
		$this->tags = array();
		$this->categorys = array();
		$this->yearMonths = array();
		
		$this->enableCache = $enableCache;
		
		//先读缓存
		if (!$this->globalDataCacheRead()) {
			//列出所有文件，可能包含非markdown文件
			$mdfiles = get_dir_file_info($postPath, FALSE);
			
			$this->readAllPostInfo($mdfiles, $postPath);
		}
	}
	
	//读取博客的基本信息
	private function readPostBaseInfo($serverPath) {
		$keywrodsArr = array();
		$tagsArr = array();
		$cateArr = array();
		
		$matches = null;
		$noteBlockArr = array();
		$noteTmpArr = array();
		$pattern1 = '/<\!\-\-(.*?)\-\->/is';
		$pattern2 = '/^\s*(author|head|date|title|summary|images|tags|category|status)\s*:(.*?)$/im';
	    
		$subject = file_get_contents($serverPath);
	    
		$blogProp = array(
			"author" => "",
			"head" => "",
			"date" => "",
			"title" => "",
			"summary" => "",
			"keywords" => "",
			"images" => array(),
			"tags" => array(),
			"category" => array(),
			"status" => "publish",
			"content" => (string)$this->parseMarkdown($subject)
		);
		
		preg_match($pattern1, $subject, $matches);
	    
		if (isset($matches[1])) {
			$procontent = trim($matches[1]);
			$proarr = explode("\n", $procontent);
	        
			foreach($proarr as $proline) {
				$proline = trim($proline);
				if ($proline) {
					preg_match($pattern2, $proline, $matches);
					if(isset($matches[2])) {
						$propName = trim($matches[1]);
						$propVal = trim($matches[2]);
						//echo $proName . " --> " . $proVal . "\n";
						switch($propName) {
							case "author":
								$blogProp['author'] = $propVal;
								break;
							case "head":
								$blogProp['head'] = $propVal;
								break;
							case "date":
								$blogProp['date'] = $propVal;
								break;
							case "title":
								$blogProp['title'] = $propVal;
								break;
							case "summary":
								$blogProp['summary'] = $this->parseMarkdown($propVal);
								break;
							case "images":
								$blogProp['images'] = $this->cleanKeywords2Arr($propVal);
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
				}
			}
		}
		
		$keywrodsArr = array_merge($tagsArr, $cateArr);
		
		//关键字去重
		$keywrodsArr = array_unique($keywrodsArr);
		$blogProp['keywords'] = implode(",", $keywrodsArr);
		
		$blogProp = $this->autoCheckBlogProps($blogProp);
		return $blogProp;
	}
	
	//自动获取未填写的属性
	private function autoCheckBlogProps($blogProp) {
		$content = $blogProp['content'];
		
		if (empty($blogProp['title'])) {
			$pattern = '/<h1>(.*?)<\/h1>/i';
			preg_match($pattern, $content, $matches);
			
			if (isset($matches[1])) {
				$blogProp['title'] = trim($matches[1]);
			} else {
				$pattern = '/<h2>(.*?)<\/h2>/si';
				preg_match($pattern, $content, $matches);
				if (isset($matches[1])) {
					$blogProp['title'] = trim($matches[1]);
				}
			}
		}
		
		if (empty($blogProp['summary'])) {
			$pattern = "/<p>(.{50,600})<\/p>/i";
			preg_match($pattern, $content, $matches);
			if (isset($matches[1])) {
				$blogProp['summary'] = trim($matches[1]);
			}
			
			if (empty($blogProp['summary'])) {
				$blogProp['summary'] = $blogProp['title'];
			}
		}
		
		if (empty($blogProp['images'])) {
			$pattern = '/<img.*?src="(.*?)".*?>/i';
			preg_match_all($pattern, $content, $matches);
			if (isset($matches[1])) {
				$blogProp['images'] = $matches[1];
			}
		}
		
		if (empty($blogProp['author'])) {
			$blogProp['author'] = "admin";
		}
		
		return $blogProp;
	}
	
	//获取标签，分类数组
	private function cleanKeywords2Arr($keywordsStr) {
		$tagsArr = array();
		
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
	private function readAllPostInfo($mdfiles, $postPath) {
		foreach ($mdfiles as $idx => $fileProp) {
			
			$fileName = $fileProp['name'];
            
			//非markdown文件，不处理，直接过滤
			if (!$this->checkFileExt($fileName)) continue;
			
			$mtime = date("Y-m-d H:i:s", $fileProp['date']);
			$ctime = date("Y-m-d H:i:s", $fileProp['cdate']);
			$serverPath = str_replace("\\", "/", $fileProp['server_path']);
			$relativePath = str_replace($postPath, "", $serverPath);
			
			$sitePath = $this->changeFileExt($relativePath);
			$siteURL = "/blog/" . $this->changeFileExt($relativePath);
			
            $siteURL = $this->urlencodeFileName($siteURL);
			$blogId = md5($siteURL);
			
			$blog = array(
				"blogId" => $blogId,
				"fileName" => $fileName,
				"serverPath" => $serverPath,
				"sitePath" => $sitePath,
				"mtime" => $mtime,
				"ctime" => $ctime,
				"siteURL" => $siteURL
			);
			
			//读取自定义博客属性信息
			$blogProp = $this->readPostBaseInfo($serverPath);
			
			//没有title的博客不处理
			if (empty($blogProp['title'])) continue;
			
			//草稿状态的不处理
			if ($blogProp['status'] == "draft") continue;
			
			$btime = strtotime($ctime);
			if (empty($blogProp['date'])) {
				$blogProp['date'] = date("Y-m-d", $btime);
			}
			
			//按显示日期归档
			$atime = strtotime($blogProp['date']);
			$month = date("Y-m", $atime);
			$yearMonthId = date("Ym", $atime);
			$monthObj = array(
				"id" => $yearMonthId,
				"name" => $month,
				"url" => "/archive/" . $yearMonthId . ".html"
			);
			
			if (!$this->checkObjInArr($monthObj, "yearMonths")) {
				array_unshift($this->yearMonths, $monthObj);
			}
			
			$blog = array_merge($blog, $blogProp);
			array_push($this->blogs, $blog);
		}
		
		$this->sortBlogs($this->blogs, 'date');
		
		//缓存全局数据
		$this->globalDataCacheWrite();
	}
	
	//写缓存
	private function gbWriteCache($key, $objdata) {
		if (ENVIRONMENT != "development" && $this->enableCache && !empty($objdata)) {
			$this->CI->cache->file->save($key, serialize($objdata), GB_DATA_CACHE_TIME);
		}
	}
	
	//读缓存
	private function gbReadCache($key) {
		$stream = $this->CI->cache->file->get($key);
		if ($stream) {
			return unserialize($stream);
		}
		return false;
	}
	
	//缓存全局数据
	private function globalDataCacheWrite() {
		if (ENVIRONMENT != "development" && $this->enableCache) {
			$this->gbWriteCache(GB_BLOG_CACHE, $this->blogs);
			$this->gbWriteCache(GB_TAG_CACHE, $this->tags);
			$this->gbWriteCache(GB_CATEGORY_CACHE, $this->categorys);
			$this->gbWriteCache(GB_ARCHIVE_CACHE, $this->yearMonths);
		}
	}
	
	//从文件缓存中读取数据
	private function globalDataCacheRead() {
		$blogs = $this->gbReadCache(GB_BLOG_CACHE);
		$tags = $this->gbReadCache(GB_TAG_CACHE);
		$categorys = $this->gbReadCache(GB_CATEGORY_CACHE);
		$yearMonths = $this->gbReadCache(GB_ARCHIVE_CACHE);
		
		if ($blogs === false || $tags === false || $categorys === false || $yearMonths === false) {
			return false;
		} else {
			$this->blogs = $blogs;
			$this->tags = $tags;
			$this->categorys = $categorys;
			$this->yearMonths = $yearMonths;
		}
		return true;
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
        $len = count($pics);
		if ($len > 1) {
			$pics[$len - 1] = $ext;
		}
		
		return implode(".", $pics);
	}
    
    //对URL中的中文编码
    private function urlencodeFileName($fileName) {
		$pics = explode('/' , $fileName);
		$len = count($pics);
		if ($len > 0) {
			$pics[$len - 1] = urlencode(urlencode($pics[$len - 1]));
		}
		return implode("/", $pics);
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
		
		if (empty($objArr)) return false;
		
		foreach ($objArr as $idx => $obj) {
			if ($obj['id'] == $tagObj['id']) {
				return true;
			}
		}
		return false;
	}
}
