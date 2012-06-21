<?php

class CCAdminControlPanel extends CObject implements IController {


  public function __construct() {
    parent::__construct();
  }


 
  public function Index() {
    $this->views->SetTitle('ACP: Admin Control Panel');
    $this->views->AddInclude(__DIR__ . '/index.tpl.php');
  }
 

} 
