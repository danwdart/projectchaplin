<?php
class default_Form_Video_Comment extends Zend_Form
{
    public function init()
    {
        $this->setAction('');
        $this->setMethod('post');
        
        $comment = new Zend_Form_Element_Textarea('Comment');
        $comment->setAttribs(array(
            'style' => 'width:250px;height:40px;'
        ));
        $comment->setLabel('Your Comment');

        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setLabel('Submit Comment');
        
        $this->addElements(array($comment, $submit));
    }
}
        
        
