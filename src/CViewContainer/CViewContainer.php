<?php
class CViewContainer {

	private $data = array();
	private $views = array();

	public function __construct() { ; }

  public function GetData() { return $this->data; }

	public function SetTitle($value) {
    return $this->SetVariable('title', $value);
  }


	public function SetVariable($key, $value) {
	  $this->data[$key] = $value;
	  return $this;
  }


  public function AddStyle($value) {
    if(isset($this->data['inline_style'])) {
      $this->data['inline_style'] .= $value;
    } else {
      $this->data['inline_style'] = $value;
    }
    return $this;
  }


  public function AddInclude($file, $variables=array(), $region='default') {
    $this->views[$region][] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
    return $this;
  }
  

  public function AddString($string, $variables=array(), $region='default') {
    $this->views[$region][] = array('type' => 'string', 'string' => $string, 'variables' => $variables);
    return $this;
  }
  

  public function RegionHasView($region) {
    if(is_array($region)) {
      foreach($region as $val) {
        if(isset($this->views[$val])) {
          return true;
        }
      }
      return false;
    } else {
      return(isset($this->views[$region]));
    }
  }
  
  
  public function Render($region='default') {
    if(!isset($this->views[$region])) return;
    foreach($this->views[$region] as $view) {
      switch($view['type']) {
        case 'include': if(isset($view['variables'])) extract($view['variables']); include($view['file']); break;
        case 'string':  if(isset($view['variables'])) extract($view['variables']); echo $view['string']; break;
      }
    }
  }
  

}
