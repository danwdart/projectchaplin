<?php
class default_Form_Video_Comment extends Zend_Form
{
    public function init()
    {
        $this->setAction('');
        $this->setMethod('post');
        $this->setAttribs(array(
            'class' => 'ajax',
            'rel' => 'comments'
        ));
        
        $comment = new Zend_Form_Element_Textarea('Comment');
        $comment->setAttribs(array(
            'style' => 'width:250px;height:40px;margin:0;',
            'placeholder' => 'Your Comment'
        ));
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setLabel('Say it!');
        
        $this->addElements(array($comment, $submit));
    }
}
        
        
