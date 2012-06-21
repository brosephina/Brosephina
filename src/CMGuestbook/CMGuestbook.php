<?php
class CMGuestbook extends CObject implements IHasSQL, IModule {


  public function __construct() {
    parent::__construct();
  }


  public static function SQL($key=null) {
    $queries = array(
      'create table guestbook'  => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now')));",
      'insert into guestbook'   => 'INSERT INTO Guestbook (entry) VALUES (?);',
      'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY id ASC;',
      'delete from guestbook'   => 'DELETE FROM Guestbook;',
     );
    if(!isset($queries[$key])) {
      throw new Exception("No such SQL query, key '$key' was not found.");
    }
    return $queries[$key];
  }



  public function Manage($action=null) {
    switch($action) {
      case 'install': 
        try {
          $this->db->ExecuteQuery(self::SQL('create table guestbook'));
          return array('success', 'Successfully created the database tables (or left them untouched if they already existed).');
        } catch(Exception$e) {
          die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
        }
      break;
      
      default:
        throw new Exception('Unsupported action for this module.');
      break;
    }
  }
  

  public function Add($entry) {
    $this->db->ExecuteQuery(self::SQL('insert into guestbook'), array($entry));
    $this->session->AddMessage('success', 'Successfully inserted new message.');
    if($this->db->rowCount() != 1) {
      die('Failed to insert new guestbook item into database.');
    }
  }
  

  public function DeleteAll() {
    $this->db->ExecuteQuery(self::SQL('delete from guestbook'));
    $this->session->AddMessage('info', 'Removed all messages from the database table.');
  }
  
  
  public function ReadAll() {
    try {
      return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * from guestbook'));
    } catch(Exception $e) {
      return array();    
    }
  }
  
}
