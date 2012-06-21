<?php

class CRequest {

	/**
	 * Member variables
	 */
	public $cleanUrl;
  public $querystringUrl;


	
	public function __construct($urlType=0) {
    $this->cleanUrl       = $urlType= 1 ? true : false;
    $this->querystringUrl = $urlType= 2 ? true : false;
	}


   public function CreateUrl($url=null, $method=null, $arguments=null) {

		if(!empty($url) && (strpos($url, '://') || $url[0] == '/')) {
			return $url;
		}
    
    if(empty($url) && (!empty($method) || !empty($arguments))) {
      $url = $this->controller;
    }
    
    if(empty($method) && !empty($arguments)) {
      $method = $this->method;
    }
    
    $prepend = $this->base_url;
    if($this->cleanUrl) {
      ;
    } elseif ($this->querystringUrl) {
      $prepend .= 'index.php?q=';
    } else {
      $prepend .= 'index.php/';
    }
    $url = trim($url, '/');
    $method = empty($method) ? null : '/' . trim($method, '/');
    $arguments = empty($arguments) ? null : '/' . trim($arguments, '/');    
    return $prepend . rtrim("$url$method$arguments", '/');
  }


	
  public function Init($baseUrl = null, $routing=null) {
    $requestUri = $_SERVER['REQUEST_URI'];
    $scriptName = $_SERVER['SCRIPT_NAME'];    
    
    $i=0;
    $len = min(strlen($requestUri), strlen($scriptName));
    while($i<$len && $requestUri[$i] == $scriptName[$i]) {
      $i++;
    }
    $request = trim(substr($requestUri, $i), '/');
  
    $queryPos = strpos($request, '?');
    if($queryPos !== false) {
      $request = substr($request, 0, $queryPos);
    }
    
    if(empty($request) && isset($_GET['q'])) {
      $request = trim($_GET['q']);
    }
    
    $routed_from = null;
    if(is_array($routing) && isset($routing[$request]) && $routing[$request]['enabled']) {
      $routed_from = $request;
      $request = $routing[$request]['url'];
    }
    
    $splits = explode('/', $request);
    
    $controller =  !empty($splits[0]) ? $splits[0] : 'index';
    $method 		=  !empty($splits[1]) ? $splits[1] : 'index';
    $arguments = $splits;
    unset($arguments[0], $arguments[1]); 
    
    $currentUrl = $this->GetCurrentUrl();
    $parts 	    = parse_url($currentUrl);
    $baseUrl 		= !empty($baseUrl) ? $baseUrl : "{$parts['scheme']}://{$parts['host']}" . (isset($parts['port']) ? ":{$parts['port']}" : '') . rtrim(dirname($scriptName), '/');
    
    $this->base_url 	  = rtrim($baseUrl, '/') . '/';
    $this->current_url  = $currentUrl;
    $this->request_uri  = $requestUri;
    $this->script_name  = $scriptName;
    $this->routed_from  = $routed_from;
    $this->request      = $request;
    $this->splits	= $splits;
    $this->controller	= $controller;
    $this->method	= $method;
    $this->arguments    = $arguments;
  }


	public function GetCurrentUrl() {
    $url = "http";
    $url .= (@$_SERVER["HTTPS"] == "on") ? 's' : '';
    $url .= "://";
    $serverPort = ($_SERVER["SERVER_PORT"] == "80") ? '' :
    (($_SERVER["SERVER_PORT"] == 443 && @$_SERVER["HTTPS"] == "on") ? '' : ":{$_SERVER['SERVER_PORT']}");
    $url .= $_SERVER["SERVER_NAME"] . $serverPort . htmlspecialchars($_SERVER["REQUEST_URI"]);
		return $url;
	}

}
