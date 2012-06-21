<?php

class CObject {

	protected $br;
	protected $config;
	protected $request;
	protected $data;
	protected $db;
	protected $views;
	protected $session;
	protected $user;

   protected function __construct($br=null) {
	  if(!$br) {
	    $br = CBrosephina::Instance();
	  }
    $this->br       = &$br;
    $this->config   = &$br->config;
    $this->request  = &$br->request;
    $this->data     = &$br->data;
    $this->db       = &$br->db;
    $this->views    = &$br->views;
    $this->session  = &$br->session;
    $this->user     = &$br->user;
	}
 	 /**
	 * Redirect to another url and store the session
	 */
  protected function RedirectTo($urlOrController=null, $method=null, $arguments=null) {
    $this->br->RedirectTo($urlOrController, $method, $arguments);
  }
	
  protected function RedirectToController($method=null, $arguments=null) {
    $this->br->RedirectToController($method, $arguments);
  }



   protected function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
    $this->br->RedirectToControllerMethod($controller, $method, $arguments);
  }
  
  protected function AddMessage($type, $message, $alternative=null) {
    return $this->br->AddMessage($type, $message, $alternative);
  }
  
  protected function CreateUrl($urlOrController=null, $method=null, $arguments=null) {
    return $this->br->CreateUrl($urlOrController, $method, $arguments);
  }

}
