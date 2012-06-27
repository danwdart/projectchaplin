<?php
class default_Form_Video_Upload extends Zend_Form
{
    public function init()
    {
        $this->setAction('');
        $this->setMethod('post');
        $this->setEncType('multipart/form-data');
        
        $title = new Zend_Form_Element_Text('Title');
        $title->setLabel('Title');
        
        $upload = new Zend_Form_Element_File('File');
        $upload->addValidators(array(
       //     new Zend_Validate_File_Extension(array(
//     //           'extension' => array(
       //             'webm',
      //              'ogg',
      //              'ogv'
  //  //            )
      //      )),
            //new Zend_Validate_File_MimeType(array(
            //    'type' => array(
            //        'video/ogg',
            //        'video/webm'
            //    )
            //)),
            new Zend_Validate_File_Size(array(
                'max' => 2*1024*1024*1024
            ))
        ));            
        $strLocation = realpath(APPLICATION_PATH.'/../public/uploads');
        $upload->setDestination($strLocation);
        
        $submit = new Zend_Form_Element_Submit('Upload');
        $submit->setLabel('Upload');
        
        $this->addElements(array($title, $upload, $submit));
    }
}
        
        
