<?php
class default_Form_Video_Edit extends Zend_Form
{
    private $_modelVideo;

    public function __construct(Chaplin_Model_Video $modelVideo)
    {
        $this->_modelVideo = $modelVideo;
        parent::__construct();
    }
    
    public function init()
    {
        $subform = new default_Form_Video_SubForm($this->_modelVideo);            
        $submit = new Zend_Form_Element_Submit('Save');
        $submit->setAttribs(array('style' => 'clear:both; width: 140px; height: 40px;'));
        
        $this->addSubForm($subform, 'Video');
        $this->addElement($submit);
        $this->setAttribs(array('style' => 'width: 800px; margin: 0 auto;'));
    }   
}
