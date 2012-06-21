<?php
class CCTheme extends CObject implements IController {



  public function __construct() { parent::__construct(); 
    $this->views->AddStyle('body:hover{background:#fff url('.$this->request->base_url.'themes/grid/grid_12_60_20.png) repeat-y center top;}');
  }



  public function Index() {
    // Get a list of all kontroller methods
    $rc = new ReflectionClass(__CLASS__);
    $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
    $items = array();
    foreach($methods as $method) {
      if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index') {
        $items[] = $this->request->controller . '/' . mb_strtolower($method->name);
      }
   }

    $this->views->SetTitle('Theme');
    $this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
                  'theme_name' => $this->config['theme']['name'],
                  'methods' => $items,
                ));
  }



  public function SomeRegions() {
    $this->views->SetTitle('Theme display content for some regions');
    $this->views->AddString('This is the primary region', array(), 'primary');
                
    if(func_num_args()) {
      foreach(func_get_args() as $val) {
        $this->views->AddString("This is region: $val", array(), $val);
        $this->views->AddStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
      }
    }
  }


  public function AllRegions() {
    $this->views->SetTitle('Theme display content for all regions');
    foreach($this->config['theme']['regions'] as $val) {
      $this->views->AddString("This is region: $val", array(), $val);
      $this->views->AddStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
    }
  }


  /**
   * Display text as h1h6 and paragraphs with some inline formatting.
   */
  public function H1H6() {
    $this->views->SetTitle('Theme testing headers and paragraphs');
    $this->views->AddInclude(__DIR__ . '/h1h6.tpl.php', array(), 'primary');
  }


} 
