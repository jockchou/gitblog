<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'third_party/Twig/Autoloader.php';

class Twig {
	private $loader;
	private $twig;
	private $CI;
    
    function __construct($params) {
		$this->CI = & get_instance();
		$cachePath = APPPATH . 'cache';
		
		Twig_Autoloader::register();
		$this->loader = new Twig_Loader_Filesystem(dirname(APPPATH) . '/theme/' . $params['theme']);
		if(!is_writable($cachePath) || ENVIRONMENT == "development") {
			$this->twig = new Twig_Environment($this->loader, array('auto_reload' => true));          
        } else {
        	$this->twig = new Twig_Environment($this->loader, array('cache' => $cachePath, 'auto_reload' => true));                      
        }
	}
	
	public function render($tpl, $data, $return = FALSE) {
		$output = $this->twig->render($tpl, $data);
        if ($return) return $output;
    }
    
    public function __call($method, $args) {
        return call_user_func_array(array($this->twig, $method), $args);
    }
}