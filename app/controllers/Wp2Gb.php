<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * wordpress博客导入Gitblog工具
 */
class Wp2Gb extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->library('WordPress');
 	}
 	
 	public function index() {
 	
 		//非命令行访问，返回404
 		if (!$this->input->is_cli_request()) show_404();
 		
 		$result = $this->wordpress->loadWP();
 		echo $this->wordpress->errMsg();
 	}
}