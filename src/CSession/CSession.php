<?php
class CSession {

	/**
	 * Members
	 */
	private $key;
	private $data = array();
	private $flash = null;


	/**
	 * Constructor
	 */
  public function __construct($key) {
    $this->key = $key;
  }


	/**
	 * Set values
	 */
   public function __set($key, $value) {
    $this->data[$key] = $value;
  }


	/**
	 * Get values
	 */
  public function __get($key) {
    return isset($this->data[$key]) ? $this->data[$key] : null;
  }


  /**
   * Set flash values, to be remembered one page request
   */
  public function SetFlash($key, $value) { $this->data['flash'][$key] = $value; }


  /**
   * Get flash values, if any.
   */
  public function GetFlash($key) { return isset($this->flash[$key]) ? $this->flash[$key] : null; }


  
  public function AddMessage($type, $message) {
    $this->data['flash']['messages'][] = array('type' => $type, 'message' => $message);
  }



  public function GetMessages() {
    return isset($this->flash['messages']) ? $this->flash['messages'] : null;
  }


  /**
   * Store values into session.
   */
  public function StoreInSession() {
    $_SESSION[$this->key] = $this->data;
  }


  public function PopulateFromSession() {
    if(isset($_SESSION[$this->key])) {
      $this->data = $_SESSION[$this->key];
      if(isset($this->data['flash'])) {
        $this->flash = $this->data['flash'];
        unset($this->data['flash']);
      }
    }
  }
  /**
   * Get, Set or Unset the authenticated user
   */
  public function SetAuthenticatedUser($profile) { $this->data['authenticated_user'] = $profile; }
  public function UnsetAuthenticatedUser() { unset($this->data['authenticated_user']); }
  public function GetAuthenticatedUser() { return $this->authenticated_user; }


}
