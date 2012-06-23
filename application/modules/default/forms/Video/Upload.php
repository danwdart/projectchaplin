<?php
class default_Form_Video_Upload extends Zend_Form
{
    public function init()
    {
        $this->setAction('');
        $this->setMethod('post');
        $this->setEncType('multipart/form-data');
        
        $upload = new Zend_Form_Element_File('File');
        $upload->setMaxFileSize(2000000);
        $strLocation = realpath(APPLICATION_PATH.'/../public/uploads');
        $upload->setDestination($strLocation);
        
        $submit = new Zend_Form_Element_Submit('Upload');
        $submit->setLabel('Upload');
        
        $this->addElements(array($upload, $submit));
    }
}
        
        
