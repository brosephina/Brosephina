<?php

// Include CForm
include(__DIR__.'/CForm.php');

// Create a class for a contact-form with name, email and phonenumber.
class CFormContact extends CForm {

  // Create all form elements and validation rules
  public function __construct() {
    parent::__construct();
    
    $this->AddElement(new CFormElementText('name', array('required'=>true)))
         ->AddElement(new CFormElementText('email', array('required'=>true)))
         ->AddElement(new CFormElementText('phone', array('required'=>true)))
         ->AddElement(new CFormElementSubmit('submit', array('callback'=>array($this, 'DoSubmit'))));

    $this->SetValidation('name', array('not_empty'))
         ->SetValidation('email', array('not_empty'))
         ->SetValidation('phone', array('not_empty'));  
  }
  
  // The callback for valid submitted forms
  protected function DoSubmit() {
    echo "<i>Form was submitted and valid.</i>";
  }
}

// Create a instance from the new contact-form-class.
session_start();
$form = new CFormContact();
if($form->Check() === false) {
  echo "<i>Form was submitted and INVALID.</i>";
}
?>
<!DOCTYPE html>
<head>
<meta charset=utf-8>
<title>How to use Brosephinas CForm class</title>
</head>
<body>
<h1>Example form using Brosephinas´ <code>CForm</code></h1>
<p>Fill in, press submit and se what happens. Review the sourcecode to see how it works.</p>

<!-- Echo out the form -->
<?=$form->GetHTML()?>
<a href='../../guestbook'>Tillbaka till Guestbook</a>
</body>
</html>
