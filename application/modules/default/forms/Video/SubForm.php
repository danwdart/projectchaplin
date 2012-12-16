<?php
class default_Form_Video_SubForm extends Zend_Form_SubForm
{
    private $_modelVideo;

    public function __construct(Chaplin_Model_Video $modelVideo)
    {
        $this->_modelVideo = $modelVideo;
        
        parent::__construct();
    }

    public function init()
    {
        $modelVideo = $this->_modelVideo;
    
        $strImageURL = $modelVideo->getThumbnail();
        $elImage = new Zend_Form_Element_Image('Image');
        $elImage->setImage($strImageURL);
        $elImage->setAttribs(array('style' => 'max-width: 200px; height: 150px;'));
        $elTitle = new Zend_Form_Element_Text('Title');
        $elTitle->setLabel('Title');
        $elTitle->setValue($modelVideo->getSuggestedTitle());
        $elTitle->addValidators(array('NotEmpty'));
        $elDescription = new Zend_Form_Element_Textarea('Description');
        $elDescription->setAttribs(array('style' => 'width: 200px; height:75px;'));
        $elDescription->setLabel('Description');
        $elDescription->addValidators(array('NotEmpty'));
        $this->addElements(array($elImage, $elTitle, $elDescription));
        $this->setAttribs(array('style' => 'float:left; width: 240px; padding: 5px; border: 0; margin: 5px'));
        $this->removeDecorator('DtDdWrapper');
    }
}
