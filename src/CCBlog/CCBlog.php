<?php
class CCBlog extends CObject implements IController {

  public function __construct() {
    parent::__construct();
  }

  public function Index() {
    $content = new CMContent();
    $this->views->SetTitle('Blog');
    $this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
                  'contents' => $content->ListAll(array('type'=>'post', 'order-by'=>'title', 'order-order'=>'DESC')),
                ));
  }


}
