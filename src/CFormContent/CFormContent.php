<?php
class CFormContent extends CForm {


  private $content;


  public function __construct($content) {
    parent::__construct();
    $this->content = $content;
    $save = isset($content['id']) ? 'save' : 'create';
    $this->AddElement(new CFormElementHidden('id', array('value'=>$content['id'])))
         ->AddElement(new CFormElementText('title', array('value'=>$content['title'])))
         ->AddElement(new CFormElementText('key', array('value'=>$content['key'])))
         ->AddElement(new CFormElementTextarea('data', array('label'=>'Content:', 'value'=>$content['data'])))
         ->AddElement(new CFormElementText('type', array('value'=>$content['type'])))
         ->AddElement(new CFormElementText('filter', array('value'=>$content['filter'])))
         ->AddElement(new CFormElementSubmit($save, array('callback'=>array($this, 'DoSave'), 'callback-args'=>array($content))))
         ->AddElement(new CFormElementSubmit('delete', array('callback'=>array($this, 'DoDelete'), 'callback-args'=>array($content))));

    $this->SetValidation('title', array('not_empty'))
         ->SetValidation('key', array('not_empty'));
  }
  


  public function DoSave($form, $content) {
    $content['id']     = $form['id']['value'];
    $content['title']  = $form['title']['value'];
    $content['key']    = $form['key']['value'];
    $content['data']   = $form['data']['value'];
    $content['type']   = $form['type']['value'];
    $content['filter'] = $form['filter']['value'];
    return $content->Save();
  }
  
  public function DoDelete($form, $content) {
    $content['id'] = $form['id']['value'];
    $content->Delete();
    CBrosephina::Instance()->RedirectTo('content');
  }
  
  
}
