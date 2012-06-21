<?php
// En session krävs för att PFBC ska fungera
// startas alltid innan någonting skrivs ut
session_start();
require_once('PFBC/Form.php');
 
/*
Om ett formulär är inskickat, kollar vi om det är
login eller logout. loggar vi in så skapar vi en
ny variabel i sessionen och loggar vi ut så tar vi
bort densamma. Oavsett så laddar vi om sidan.
*/
 
if(isset($_POST['form'])){
  if($_POST['form'] == 'login'){
    if(PFBC\Form::isValid($_POST['form']))
      $_SESSION['loggedin'] = true;
  }
  elseif($_POST['form'] == 'logout')
    unset($_SESSION['loggedin']);
  header("Location: " . $_SERVER['PHP_SELF']);
}
 
// Sätt ihop ett inloggningsformulär med krav på email
// och lösenord
$login = new PFBC\Form("login", 300);
$login->addElement(new PFBC\Element\Hidden(
  "form", "login"
));
$login->addElement(new PFBC\Element\Email(
  "Användarnamn (e-mail):", "username", array(
    'required' => 1,
  )
));
$login->addElement(new PFBC\Element\Password(
  "Lösenord:", "password", array(
    'required' => 1,
  )
));
$login->addElement(new PFBC\Element\Button(
  "Logga In", "submit", array("icon" => "key")
));
 
// Sätt ihop ett utloggningsformulär
$logout = new PFBC\Form("logout", 300);
$logout->addElement(new PFBC\Element\Hidden(
  "form", "logout"
));
$logout->addElement(new PFBC\Element\Button(
  "Logga ut"
));
 
// Sid-specifik information, ej relevant för exemplet
$title = 'PHP Form Builder Class';
$slug = 'pfbc';
$content = "<h2>Inloggningsformulär</h2>";
 
// Skriv ut formulär beroende på inloggningsstatus
if(isset($_SESSION['loggedin']))
  $content .= $logout->render(true);
else
  $content .= $login->render(true);
 
// Hämta template för sidan (inte relaterat till PFBC)
require_once('page.tpl.php');
