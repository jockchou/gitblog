<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pager {
	
	public function splitPage($pages, $pageNo, $showLen=4, $path="/") {
		$pagination = array();
		$pageList = array();
		$showList = array();
		
		$next = null;
		$prev = null;
		
		for ($i = 1; $i <= $pages; $i++) {
			$page = array(
				"num" =>  $i,
				"url" => $path . "page/" . $i . ".html",
				"isCurrent" => $i == $pageNo
			);
			array_push($pageList, $page);
		}
		
		if ($pageNo > 1) {
			$prev = array(
				"num" =>  $pageNo - 1,
				"url" => $path . "page/" . ($pageNo - 1) . ".html",
				"isCurrent" => false
			);
		}
		
		if ($pageNo < $pages) {
			$next = array(
				"num" =>  $pageNo + 1,
				"url" => $path . "page/" . ($pageNo + 1) . ".html",
				"isCurrent" => false
			);
		}
		
		$showStart = $pageNo - $showLen;
		$showStop = $pageNo + $showLen;
		for($i = $showStart; $i <= $showStop; $i++) {
			if ($i < 1 || $i > $pages) continue;
			array_push($showList, $pageList[$i - 1]);
		}
		
		$pagination['pageList'] = $pageList;
		$pagination['showList'] = $showList;
		$pagination['next'] = $next;
		$pagination['prev'] = $prev;
		$pagination['pages'] = $pages;
		
		return $pagination;
    }
}