<?php

class CBrosephina implements ISingleton {
    
    
    private static $instance = null;
    public $config = array();
    public $request;
    public $data;
    public $db;
    public $views;
    public $session;
    public $user;
    public $timer = array();

    protected function __construct() {
		// time page generation
		$this->timer['first'] = microtime(true); 

		// include the site specific config.php and create a ref to $ly to be used by config.php
		$br = &$this;
		require(Brosephina_SITE_PATH.'/config.php');

		// Start a named session
		session_name($this->config['session_name']);
		session_start();
		$this->session = new CSession($this->config['session_key']);
		$this->session->PopulateFromSession();

		// Set default date/time-zone
		date_default_timezone_set($this->config['timezone']);

		// Create a database object.
		if(isset($this->config['database'][0]['dsn'])){
			$this->db = new CDatabase($this->config['database'][0]['dsn']);

		// Create a container for all views and theme data
			$this->views = new CViewContainer();

		// Create a object for the user
			$this->user = new CMUser($this);
		}
  }


   public static function Instance() {
		if(self::$instance == null) {
			self::$instance = new CBrosephina();
		}
		return self::$instance;
	}
   
   public function FrontControllerRoute() {
    // Take current url and divide it in controller, method and parameters
    $this->request = new CRequest($this->config['url_type']);
    $this->request->Init($this->config['base_url'], $this->config['routing']);
    $controller = $this->request->controller;
    $method     = $this->request->method;
    $arguments  = $this->request->arguments;
    
    // Is the controller enabled in config.php?
    $controllerExists 	= isset($this->config['controllers'][$controller]);
    $controllerEnabled 	= false;
    $className		= false;
    $classExists 	= false;

    if($controllerExists) {
      $controllerEnabled 	= ($this->config['controllers'][$controller]['enabled'] == true);
      $className		= $this->config['controllers'][$controller]['class'];
      $classExists 		= class_exists($className);
    }
    
    // Check if controller has a callable method in the controller class, if then call it
    if($controllerExists && $controllerEnabled && $classExists) {
      $rc = new ReflectionClass($className);
      if($rc->implementsInterface('IController')) {
         $formattedMethod = str_replace(array('_', '-'), '', $method);
        if($rc->hasMethod($formattedMethod)) {
          $controllerObj = $rc->newInstance();
          $methodObj = $rc->getMethod($formattedMethod);
          if($methodObj->isPublic()) {
            $methodObj->invokeArgs($controllerObj, $arguments);
          } else {
            die("404. " . get_class() . ' error: Controller method not public.');          
          }
        } else {
          die("404. " . get_class() . ' error: Controller does not contain method.');
        }
      } else {
        die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
      }
    } 
    else { 
      die('404. Page is not found.');
    }
  }
  
  
  
     /**
   * ThemeEngineRender, renders the reply of the request to HTML or whatever.
   */
  public function ThemeEngineRender() {
    $this->session->StoreInSession();
  
    if(!isset($this->config['theme'])) { return; }
    
    $themePath 	= Brosephina_INSTALL_PATH . '/' . $this->config['theme']['path'];
    $themeUrl	= $this->request->base_url . $this->config['theme']['path'];

    $parentPath = null;
    $parentUrl = null;
    if(isset($this->config['theme']['parent'])) {
      $parentPath = Brosephina_INSTALL_PATH . '/' . $this->config['theme']['parent'];
      $parentUrl  = $this->request->base_url . $this->config['theme']['parent'];
    }
    
    $this->data['stylesheet'] = $this->config['theme']['stylesheet'];
    
    $this->themeUrl = $themeUrl;
    $this->themeParentUrl = $parentUrl;
    
    if(is_array($this->config['theme']['menu_to_region'])) {
      foreach($this->config['theme']['menu_to_region'] as $key => $val) {
        $this->views->AddString($this->DrawMenu($key), null, $val);
      }
    }

    $br = &$this;
    include(Brosephina_INSTALL_PATH . '/themes/functions.php');
    if($parentPath) {
      if(is_file("{$parentPath}/functions.php")) {
        include "{$parentPath}/functions.php";
      }
    }
    if(is_file("{$themePath}/functions.php")) {
      include "{$themePath}/functions.php";
    }

    extract($this->data); 
    extract($this->views->GetData());
    if(isset($this->config['theme']['data'])) {
      extract($this->config['theme']['data']);
    }

    $templateFile = (isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';
    if(is_file("{$themePath}/{$templateFile}")) {
      include("{$themePath}/{$templateFile}");
    } else if(is_file("{$parentPath}/{$templateFile}")) {
      include("{$parentPath}/{$templateFile}");
    } else {
      throw new Exception('No such template file.');
    }
  }


  public function RedirectTo($urlOrController=null, $method=null, $arguments=null) {
    if(isset($this->config['debug']['db-num-queries']) && $this->config['debug']['db-num-queries'] && isset($this->db)) {
      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
    }    
    if(isset($this->config['debug']['db-queries']) && $this->config['debug']['db-queries'] && isset($this->db)) {
      $this->session->SetFlash('database_queries', $this->db->GetQueries());
    }    
    if(isset($this->config['debug']['timer']) && $this->config['debug']['timer']) {
	    $this->session->SetFlash('timer', $this->timer);
    }    
    $this->session->StoreInSession();
    header('Location: ' . $this->request->CreateUrl($urlOrController, $method, $arguments));
    exit;
  }


  public function RedirectToController($method=null, $arguments=null) {
    $this->RedirectTo($this->request->controller, $method, $arguments);
  }


  public function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
	  $controller = is_null($controller) ? $this->request->controller : null;
	  $method = is_null($method) ? $this->request->method : null;	  
    $this->RedirectTo($this->request->CreateUrl($controller, $method, $arguments));
  }


  public function AddMessage($type, $message, $alternative=null) {
    if($type === false) {
      $type = 'error';
      $message = $alternative;
    } else if($type === true) {
      $type = 'success';
    }
    $this->session->AddMessage($type, $message);
  }


  public function CreateUrl($urlOrController=null, $method=null, $arguments=null) {
    return $this->request->CreateUrl($urlOrController, $method, $arguments);
  }


  public function DrawMenu($menu) {
    $items = null;
    if(isset($this->config['menus'][$menu])) {
      foreach($this->config['menus'][$menu] as $val) {
        $selected = null;
        if($val['url'] == $this->request->request || $val['url'] == $this->request->routed_from) {
          $selected = " class='selected'";
        }
        $items .= "<li><a {$selected} href='" . $this->CreateUrl($val['url']) . "'>{$val['label']}</a></li>\n";
      }
    } else {
      throw new Exception('No such menu.');
    }     
    return "<ul class='menu {$menu}'>\n{$items}</ul>\n";
  }
}
