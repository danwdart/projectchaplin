<?php
class default_Form_Video_Upload extends Zend_Form
{
    public function init()
    {
        $this->setAction('');
        $this->setMethod('post');
        $this->setEncType('multipart/form-data');
        
        $upload = new Zend_Form_Element_File(
            'Files[]',
            array(
                'label' => 'Upload files...',
                'multiple' => 'multiple',
                'isArray' => true
            )
        );
        $upload->addValidators(array(
            new Zend_Validate_File_Size(array(
                'max' => 2*1024*1024*1024
            ))
        ));            
        $strLocation = realpath(APPLICATION_PATH.'/../public/uploads');
        $upload->setDestination($strLocation);
        
        $submit = new Zend_Form_Element_Submit('Upload');
        $submit->setLabel('Upload');
        
        $this->addElements(array($upload, $submit));
    }
}
        
        
