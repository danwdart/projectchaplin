<?php
class default_Form_Video_Comment extends Zend_Form
{
    public function init()
    {
        $this->setAction('');
        $this->setMethod('post');
        
        $comment = new Zend_Form_Element_Textarea('Comment');
        $comment->setAttribs(array(
            'style' => 'width:300px;height:150px;'
        ));
        $comment->setLabel('Your Comment');

        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setLabel('Submit Comment');
        
        $this->addElements(array($comment, $submit));
    }
}
        
        
