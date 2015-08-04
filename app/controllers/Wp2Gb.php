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
 		$wpObj = $this->wordpress->loadWP();
 	}
}