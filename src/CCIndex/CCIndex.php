<?php
class CCIndex extends CObject implements IController {

  public function __construct() { parent::__construct(); }
  


  public function Index() {			
    $modules = new CMModules();
    $controllers = $modules->AvailableControllers();
    $this->views->SetTitle('Index');
    $this->views->AddInclude(__DIR__ . '/index.tpl.php', array(), 'primary');
    $this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array('controllers'=>$controllers), 'sidebar');
  }


  private function Menu() {	
    $items = array();
    foreach($this->config['controllers'] as $key => $val) {
      if($val['enabled']) {
        $rc = new ReflectionClass($val['class']);
        $items[] = $key;
        $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach($methods as $method) {
          if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index') {
            $items[] = "$key/" . mb_strtolower($method->name);
          }
        }
      }
    }
    return $items;
  }
  
}
