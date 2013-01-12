<?php
class default_Form_Video_Name extends Zend_Form
{
    private $_ittVideos;

    public function __construct(Chaplin_Iterator_Interface $ittVideos)
    {
        $this->_ittVideos = $ittVideos;
        parent::__construct();
    }
    
    public function init()
    {
        $sfVideos = new Zend_Form_SubForm('Videos');
        $sfVideos->setAttribs(array('style' => 'width: 800px; margin: 0 auto;'));
        foreach($this->_ittVideos as $modelVideo) {
            $subform = new default_Form_Video_SubForm($modelVideo);            
            $sfVideos->addSubForm($subform, $modelVideo->getId());
        }
        
        $submit = new Zend_Form_Element_Submit('Save');
        $submit->setAttribs(array('style' => 'clear:both; width: 140px; height: 40px;'));
        
        $this->addSubForm($sfVideos, 'Videos');
        $this->addElement($submit);
        $this->setAttribs(array('style' => 'width: 800px;'));
    }   
}
