<?php
class CCModules extends CObject implements IController {

  public function __construct() { parent::__construct(); }


  public function Index() {
    $modules = new CMModules();
    $controllers = $modules->AvailableControllers();
    $allModules = $modules->ReadAndAnalyse();
    $this->views->SetTitle('Manage Modules');
    $this->views->AddInclude(__DIR__ . '/index.tpl.php', array('controllers'=>$controllers), 'primary');
    $this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
  }
  

  public function Install() {
    $modules = new CMModules();
    $results = $modules->Install();
    $allModules = $modules->ReadAndAnalyse();
    $this->views->SetTitle('Install Modules');
    $this->views->AddInclude(__DIR__ . '/install.tpl.php', array('modules'=>$results), 'primary');
    $this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
  }
  
  public function View($module){
		if(!preg_match('/^C[a-zA-Z]+$/', $module)){throw new Exception('Invalid characters in module name.');}
		$modules = new CMModules();
		$controllers = $modules->AvailableControllers();
		$allModules = $modules->ReadAndAnalyze();
		$aModule = $modules->ReadAndAnalyzeModule($module);
		$this->views->SetTitle('Manage Modules');
		$this->views->AddInclude(__DIR__ . '/view.tpl.php', array('module'=>$aModule), 'primary');
		$this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
	}


}
