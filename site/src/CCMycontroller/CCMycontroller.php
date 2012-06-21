<?php
class CCMycontroller extends CObject implements IController {

  public function __construct() { parent::__construct(); }
  

  public function Index(){
		$content = new CMContent(5);
		$this->views->SetTitle('About me'.htmlEnt($content['title']))
					->AddInclude(__DIR__ . '/page.tpl.php', array(
						'content' => $content,
					));
	}


  public function Blog() {
    $content = new CMContent();
    $this->views->SetTitle('My blog');
    $this->views->AddInclude(__DIR__ . '/blog.tpl.php', array(
                  'contents' => $content->ListAll(array('type'=>'post', 'order-by'=>'title', 'order-order'=>'ASC')),
                ));
  }

  public function Guestbook() {
    $guestbook = new CMGuestbook();
    $form = new CFormMyGuestbook($guestbook);
    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', 'The form could not be processed.');
      $this->RedirectToControllerMethod();
    } else if($status === true) {
      $this->RedirectToControllerMethod();
    }
    
    $this->views->SetTitle('My Guestbook');
    $this->views->AddInclude(__DIR__ . '/guestbook.tpl.php', array(
            'entries'=>$guestbook->ReadAll(), 
            'form'=>$form,
         ));
  }
  

} 



class CFormMyGuestbook extends CForm {


  private $object;


  public function __construct($object) {
    parent::__construct();
    $this->objecyt = $object;
    $this->AddElement(new CFormElementTextarea('data', array('label'=>'Add entry:')));
    $this->AddElement(new CFormElementSubmit('add', array('callback'=>array($this, 'DoAdd'), 'callback-args'=>array($object))));
  }
  


  public function DoAdd($form, $object) {
    return $object->Add(strip_tags($form['data']['value']));
  }
 
 
}
